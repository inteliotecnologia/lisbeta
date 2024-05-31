<?
if (@pode("r", $_SESSION["permissao"])) {
	
	if (isset($_SESSION["id_posto_sessao"]))
		$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	if (isset($_SESSION["id_cidade_sessao"]))
		$id_cidade= $_SESSION["id_cidade_sessao"];
	
	$result= mysql_query("select pessoas.id_pessoa, pessoas.nome, pessoas.cpf, pessoas.id_responsavel,
							consultas.*, DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
							consultas.id_usuario as id_med
							from  pessoas, consultas, postos
							where consultas.id_consulta = '$id_consulta'
							and   consultas.id_pessoa = pessoas.id_pessoa
							and   consultas.id_posto = postos.id_posto
							and   postos.id_cidade = '$id_cidade'
	
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
?>

<? if ($fechar==1) { ?>
<a href="javascript:void(0);" onclick="fechaDiv('tela_relatorio');" class="fechar">x</a>
<? } ?>

<h2 class="titulos_mais">Receita nº <?= $rs->id_consulta; ?></h2>

<fieldset>
    <legend>Dados do paciente</legend>
    
    <label>Paciente:</label>
    <?
    echo $rs->nome ." ";
    //echo mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel);
    ?>
    <br />
</fieldset>

<fieldset>
    <legend>Dados da consulta</legend>
    
    <label>Posto:</label>
    <?= pega_posto($_SESSION["id_posto_sessao"]); ?>
    <br />
    
    <label>Médico:</label>
    <?= pega_nome_pelo_id_usuario($rs->id_med); ?>
    <br />
</fieldset>

<? /*
<fieldset>
	<legend>Exames solicitados:</legend>
    
    <?
    $result_exa= mysql_query("select exames.exame from consultas_exames, exames
                                where consultas_exames.id_exame = exames.id_exame
                                and   consultas_exames.id_consulta = '$id_consulta' ");
    if (mysql_num_rows($result_exa) > 0) {
    ?>
    <ul class="recuo1">
        <? while ($rs_exa= mysql_fetch_object($result_exa)) { ?>
        <li><?= $rs_exa->exame; ?>;</li>
        <? } ?>
    </ul>
    <?
    }
    else
        echo "<span class=\"vermelho\">Nenhum exame solicitado!</span>";
    ?>
</fieldset>
 */ ?>
 
<fieldset>
    <legend>Receituário:</legend>
    
    <?
    $result_rec= mysql_query("select * from consultas_remedios, remedios
							 	where consultas_remedios.id_consulta = '$rs->id_consulta'
								and   consultas_remedios.id_remedio = remedios.id_remedio
								and   remedios.classificacao_remedio = 'n'
								");
    if (mysql_num_rows($result_rec) > 0) {
    ?>
    
    <ul class="recuo1">
        <?
        while ($rs_rec= mysql_fetch_object($result_rec)) {
        ?>
        <li>
        <div class="parte50_receita">
        	<span class="fundo_branco"><?= pega_remedio($rs_rec->id_remedio); ?></span>
        </div>
        <div class="parte50_receita alinhar_direita">
        	<span class="fundo_branco"><?= "<b>". $rs_rec->qtde ." ". pega_apresentacao($rs_rec->tipo_apres) ."</b>"; ?></span>
        </div>
        <br />
        <?
        $receita = "";
        
		if ($rs_rec->tipo_acao=='a') $via_string= pega_vias_aplicacao($rs_rec->acao_local);
		
		if ($rs_rec->tipo_acao=='n') {
			$via_string= $rs_rec->neb_com;
            $receita .= pega_tipo_acao($rs_rec->tipo_acao) ." ". pega_remedio($rs_rec->id_remedio) ." (". $rs_rec->qtde_tomar ." ". pega_tipo_tomar($rs_rec->tipo_tomar) .") com ". $via_string;
		}
		else
			$receita .= pega_tipo_acao($rs_rec->tipo_acao) ." ". $via_string ." ". $rs_rec->qtde_tomar ." ". pega_tipo_tomar($rs_rec->tipo_tomar) .", ";
		
        if ($rs_rec->tipo_periodicidade=="d")
            $receita .= " ".$rs_rec->periodicidade ." vez(es) ao dia ";
        elseif ($rs_rec->tipo_periodicidade=="h")
            $receita .= " de ". $rs_rec->periodicidade ." em ". $rs_rec->periodicidade. " hora(s) ";
            
		if ($rs_rec->periodo!="0")
	        $receita .= " por ". $rs_rec->periodo ." dia(s)";
        ?>
        <?= $receita; $receita = ""; ?> <br />
        <em><?= $rs_rec->observacoes; ?></em>
        <br />
        </li>
        <? } ?>
    </ul>
    
    <? } else { ?>
    <span class="vermelho">Nenhum medicamento receitado!</span>
    <? } ?>
    
</fieldset>

<fieldset>
    <legend>Assinatura e outras observações do médico:</legend>
    <br /><br /><br /><br />
    <div id="assinatura">
        <?= pega_nome_pelo_id_usuario($rs->id_med); ?>
        <?= pega_crm_pelo_id_usuario($rs->id_med); ?>
    </div>
</fieldset>

<script language="javascript" type="text/javascript">
	//window.print();
</script>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>