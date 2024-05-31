<? if (@pode("t", $_SESSION["permissao"])) {
	$sql= "select tfds.*, DATE_FORMAT(data_partida, '%d/%m/%Y') as data_partida,
				DATE_FORMAT(data_retorno_prevista, '%d/%m/%Y') as data_retorno_prevista,
				tfds_veiculos.*
				
				from tfds, tfds_veiculos
				where tfds.id_tfd= '". $_GET["id_tfd"] ."'
				and   tfds.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
				and   tfds.id_veiculo = tfds_veiculos.id_veiculo
				";
	$result= mysql_query($sql) or die(mysql_error());
	$rs= mysql_fetch_object($result);
?>

<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_listar');" id="botao_voltar">&lt;&lt; voltar</a>

<a id="link_aux" class="flutuar_direita" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_ver&amp;id_tfd=<?= $id_tfd; ?>');">versão completa</a>

<h2 class="titulos">Visualização da TFD nº <?= $rs->id_tfd; ?></h2>

<fieldset>
    <legend>Dados gerais</legend>
    
    <label>Morotista:</label>
    <?= pega_nome($rs->id_motorista) ." - ". formata_cpf(pega_cpf_pelo_id_pessoa($rs->id_motorista)); ?>
    <br />

    <label>Veículo:</label>
    <?= $rs->veiculo ." (". $rs->placa .")"; ?>
    <br />
    
    <label>Destino:</label>
    <?= pega_cidade($rs->id_cidade_tfd); ?>
    <br />
    
    <label>Data:</label>
    <?= $rs->data_partida; ?>
    <br />
        
</fieldset>

<h2 class="titulos2">Pessoas nesta TFD</h2>

<fieldset>
    <legend>Dados gerais</legend>

    <table cellspacing="0">
    	<tr>
        	<th width="10%">Hora</th>
            <th width="20%" align="left">Local</th>
            <th width="30%" align="left">Nome</th>
            <th width="10%">CPF</th>
            <th width="30%" align="left">OBS</th>
        </tr>
    <?
	$result_pes= mysql_query("select tfds_pessoas.*, pessoas.id_responsavel, pessoas.nome, pessoas.cpf,
								
								tfds_entidades.entidade,
								
								tfds_pessoas.obs,
								DATE_FORMAT(tfds_solicitacoes.data_atividade, '%H:%i') as hora
								
								from tfds_pessoas, pessoas, tfds_solicitacoes, tfds_entidades
								
								where tfds_pessoas.id_tfd = '". $rs->id_tfd ."'
								and   tfds_pessoas.id_pessoa = tfds_solicitacoes.id_pessoa
								and   tfds_solicitacoes.id_entidade = tfds_entidades.id_entidade
								and   tfds_pessoas.id_solicitacao = tfds_solicitacoes.id_solicitacao
								and   tfds_pessoas.id_pessoa = pessoas.id_pessoa
								order by hora asc
								") or die(mysql_error());

	if (mysql_num_rows($result_pes)==0)
		echo "<span class=\"vermelho\">Ninguém nesta viagem!</span>";
	else {
		while ($rs_pes= mysql_fetch_object($result_pes)) {
			$id_solicitacao= $rs_pes->id_solicitacao;
	?>
        <tr>
        	<td valign="top" align="center"><?= $rs_pes->hora; ?></td>
            <td valign="top"><?= $rs_pes->entidade; ?></td>
       		<td valign="top"><?= $rs_pes->nome;; ?></td>
            <td valign="top" align="center"><?= mostra_cpf_ou_responsavel($rs_pes->cpf, $rs_pes->id_responsavel); ?></td>
            <td valign="top"><?= $rs_pes->obs; ?></td>
		</tr>
        <?
			$result_aco= mysql_query("select pessoas.nome, pessoas.cpf, pessoas.id_responsavel
										from pessoas, tfds_pessoas, tfds_pessoas_acompanhantes
										where tfds_pessoas.id_tfd_pessoa = tfds_pessoas_acompanhantes.id_tfd_pessoa
										and   tfds_pessoas_acompanhantes.id_pessoa = pessoas.id_pessoa
										and   tfds_pessoas.id_tfd_pessoa = '". $rs_pes->id_tfd_pessoa ."'
										");
			if (mysql_num_rows($result_aco)>0) {
				while ($rs_aco= mysql_fetch_object($result_aco)) {
				?>
				<tr class="menor">
					<td colspan="2" align="center" valign="top">ACOMPANHANTE</td>
					<td valign="top"><?= $rs_aco->nome; ?></td>
					<td valign="top" align="center"><?= mostra_cpf_ou_responsavel($rs_aco->cpf, $rs_aco->id_responsavel); ?></td>
					<td valign="top">&nbsp;</td>
				</tr>
				<?
				}//fim while acomapnhantes
			}//fim if
		}//fim while
	}//fim else
	
	$result_car= mysql_query("select tfds_pessoas.*, pessoas.id_responsavel, pessoas.nome, pessoas.cpf,
								tfds_pessoas.obs
								
								from tfds_pessoas, pessoas
								
								where tfds_pessoas.id_tfd = '". $rs->id_tfd ."'
								and   tfds_pessoas.id_pessoa = pessoas.id_pessoa
								and   tfds_pessoas.tipo = 'c'
								order by pessoas.nome asc
								") or die(mysql_error());

	while ($rs_car= mysql_fetch_object($result_car)) {
	?>
	<tr>
		<td colspan="2" align="center" valign="top">CARONA</td>
		<td valign="top"><?= $rs_car->nome; ?></td>
		<td valign="top" align="center"><?= mostra_cpf_ou_responsavel($rs_car->cpf, $rs_car->id_responsavel); ?></td>
		<td valign="top"><?= $rs_car->obs; ?></td>
	</tr>
	<? } ?>
    </table>
</fieldset>

<div class="soh_impressao">
    <h2 class="titulos2">Observações do responsável</h2>

    <fieldset>
        <legend><?= pega_nome($rs->id_usuario); ?></legend>
        <br /><br /><br /><br />
    </fieldset>
</div>
    <?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>