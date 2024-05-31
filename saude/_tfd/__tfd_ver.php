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
	$id_tfd= $rs->id_tfd;
?>

<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_listar');" id="botao_voltar">&lt;&lt; voltar</a>

<a id="link_aux" class="flutuar_direita" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_resumo_ver&amp;id_tfd=<?= $id_tfd; ?>');">versão resumida para motorista</a>

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
    <?
	$result_pes= mysql_query("select tfds_pessoas.*, pessoas.id_responsavel, pessoas.nome, pessoas.cpf from tfds_pessoas, pessoas
								where tfds_pessoas.id_tfd = '". $rs->id_tfd ."'
								and   tfds_pessoas.id_pessoa = pessoas.id_pessoa
								order by tfds_pessoas.tipo desc
								");
	if (mysql_num_rows($result_pes)==0)
		echo "<span class=\"vermelho\">Ninguém nesta viagem!</span>";
	else {
		while ($rs_pes= mysql_fetch_object($result_pes)) {
			$id_solicitacao= $rs_pes->id_solicitacao;
			if ($rs_pes->tipo=="p")
				$tipo_str= "Paciente";
			else
				$tipo_str= "Carona";
			?>
            <fieldset>
                <legend><?= $tipo_str; ?></legend>
                    <label>Nome:</label>
                    <?= $rs_pes->nome ." ". mostra_cpf_ou_responsavel($rs_pes->cpf, $rs_pes->id_responsavel); ?>
                    <br />
            
                    <label>Trajeto:</label>
                    <?
                    if ($rs_pes->ida==1)
						echo "Ida";
					if (($rs_pes->ida==1) && ($rs_pes->volta==1))
						echo "/";
					if ($rs_pes->volta==1)
						echo "Volta";
					?>
                    <br />
                    
                    <label>OBS:</label>
                    <?
                    if ($rs_pes->obs=="")
						echo "<span class=\"vermelho\">Não informado</span>";
					else
						echo $rs_pes->obs;
					?>
                    <br />
                    
                    <?
                    if ($rs_pes->tipo=="p") {
						$parcial= 1;
						include("_tfd/__tfd_solicitacao_ver.php");
						
						$result_aco= mysql_query("select tfds_pessoas_acompanhantes.*, pessoas.nome, pessoas.cpf
													from  tfds_pessoas_acompanhantes, pessoas
													where tfds_pessoas_acompanhantes.id_pessoa = pessoas.id_pessoa
													and   tfds_pessoas_acompanhantes.id_tfd_pessoa= '". $rs_pes->id_tfd_pessoa ."'
													");
						
						if (mysql_num_rows($result_aco)>0) { ?>
	                        <fieldset>
			                	<legend>Acompanhantes</legend>
                                
                            	<ul class="recuo1">
                        <?
							while ($rs_aco= mysql_fetch_object($result_aco))
								echo "<li>". $rs_aco->nome ." - ". formata_cpf($rs_aco->cpf) ."</li>";
						?>
								</ul>
                            </fieldset>
                        <?
						}
					}
					?>
            </fieldset>
           <?
		}
	}
	?>
    
    <div class="soh_impressao">
	    <h2 class="titulos2">Observações do responsável</h2>

        <fieldset>
            <legend><?= pega_nome($rs->id_usuario); ?></legend>
            <br /><br /><br /><br />
        </fieldset>
    </div>
    
    <br /><br /><br />
    
    <a class="botao tamanho120" onclick="return confirm('Tem certeza que deseja excluir esta TFD?\n\nTODOS OS DADOS DE PACIENTES/ACOMPANHANTES/CARONAS SERÃO PERDIDOS!');" href="javascript:ajaxLink('conteudo', 'tfdExcluir&amp;id_tfd=<?= $id_tfd; ?>');">Excluir TFD</a>
    <br />

    <?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>