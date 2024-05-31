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

<h2 class="titulos_mais">Receituário de controle especial nº <?= $rs->id_consulta; ?></h2>


<fieldset>
    <legend>Identificação do emitente</legend>
    
    <?
	$result_em= mysql_query("select pessoas.*
							from  pessoas, usuarios
							where usuarios.id_pessoa =  pessoas.id_pessoa
							and   usuarios.id_usuario = '$rs->id_med'
							") or die(mysql_error());
	$rs_em= mysql_fetch_object($result_em);
	?>
    
    <div class="parte50">
        <label>Nome:</label>
        <?= $rs_em->nome; ?>
        <br />
        
        <label>CRM:</label>
        <?= pega_crm_pelo_id_usuario($rs->id_med); ?>
        <br />
    </div>
    <div class="parte50">
        <label>Endereço:</label>
        <?= $rs_em->endereco .", ". $rs_em->bairro ." ". $rs_em->complemento .". ". $rs_em->cep .". ". pega_cidade($rs_em->id_cidade); ?>
        <br />
        
        <label>Telefone:</label>
        <?= $rs_em->telefone; ?>
        <br />
    </div>
</fieldset>

<fieldset>
    <legend>Identificação do paciente</legend>
    
    <div class="parte50">
        <label>Nome:</label>
        <?= $rs->nome; ?>
        <br />
    </div>
    <div class="parte50">
    	<label>Telefone:</label>
        <?= $rs->telefone; ?>
        <br />
    </div>
    
    <label>Endereço:</label>
        <?= $rs->endereco .", ". $rs->bairro ." ". $rs->complemento .". ". $rs->cep .". ". pega_cidade($rs->id_cidade); ?>
        <br />
        
</fieldset>
 
<fieldset>
    <legend>Receituário:</legend>
    
    <?
    $result_rec= mysql_query("select * from consultas_remedios, remedios
							 	where consultas_remedios.id_consulta = '$rs->id_consulta'
								and   consultas_remedios.id_remedio = remedios.id_remedio
								and   remedios.classificacao_remedio = 'c'
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
    <legend>Identificação do comprador</legend>
    
    <div class="parte50">
        <label>Nome:</label>
        
        <br />
        
        <label>Endereço:</label>
        
        <br />
        
    </div>
    <div class="parte50">
        <label>RG/Órg. em.:</label>
        
        <br />
        
        <label>Cidade/UF:</label>
        
        <br />
        
        <label>Telefone:</label>
        
        <br />
    </div>
</fieldset>

<fieldset>
    <legend>Identificação do fornecedor</legend>
    
    <label>Data:</label> <br /><br /><br />
    ______/______/____________
    <br />
	
    
    <div id="assinatura">
        Assinatura do farmacêutico
    </div>
    
</fieldset>

<br /><br />

<? /*<fieldset>
    <legend>Assinatura e outras observações do médico:</legend>
    <br /><br /><br /><br />
    <div id="assinatura">
        <?= pega_nome_pelo_id_usuario($rs->id_med); ?>
        <?= pega_crm_pelo_id_usuario($rs->id_med); ?>
    </div>
</fieldset>*/ ?>

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