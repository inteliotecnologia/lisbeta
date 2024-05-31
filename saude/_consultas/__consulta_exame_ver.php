<?
if (@pode("r", $_SESSION["permissao"])) {
	
	if (isset($_SESSION["id_posto_sessao"]))
		$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	if (isset($_SESSION["id_cidade_sessao"]))
		$id_cidade= $_SESSION["id_cidade_sessao"];
	
	$result= mysql_query("select pessoas.id_pessoa, pessoas.nome, pessoas.cpf,
							pessoas.id_responsavel, pessoas.data_nasc, pessoas.sexo, pessoas.raca,
							DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc2,
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

<h2 class="titulos_mais">Requisição de exames nº <?= $rs->id_consulta; ?></h2>

<fieldset>
    <legend>Dados do paciente</legend>
    
    <div class="parte50">
        <label>Paciente:</label>
        <?= $rs->nome; ?>
        <br />
        
        <label>Idade:</label>
        <?= calcula_idade($rs->data_nasc2); ?>
        <br />
    </div>
    <div class="parte50">
        <label>Sexo:</label>
        <?= pega_sexo($rs->sexo); ?>
        <br />
        
        <label>Raça:</label>
        <?= $rs->raca; ?>
        <br />
    </div>
</fieldset>

<fieldset>
    <legend>Dados clínicos</legend>
    
    
</fieldset>

<fieldset>
	<legend>Exames requisitados:</legend>
    
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