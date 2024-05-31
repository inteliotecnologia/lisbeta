<?
if (@pode_algum("oecmin", $_SESSION["permissao"])) {

if ($_POST["id_usuario"]!="")
	$sql1 = " and   consultas.id_usuario = '". $_POST["id_usuario"] ."' ";
	
	$sql= "select consultas.*,
			DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
			pessoas.id_pessoa, pessoas.nome, postos.posto, pessoas.sexo
		
			from  consultas, pessoas, postos
			where consultas.id_pessoa = pessoas.id_pessoa
			". $sql1 ."
			and   consultas.id_posto = postos.id_posto
			and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
			and   consultas.tipo_consulta_prof = '". $_POST["tipo_consulta_prof"] ."'
			and   DATE_FORMAT(consultas.data_consulta, '%Y%m%d') = '". formata_data($_POST["data"]) ."'
		 ";
//and   postos.id_cidade = '". pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]) ."'
/*
if (@pode("o", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'o' ";
}
if (@pode("e", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'e' ";
}	
if (@pode("c", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'c' ";
}
*/

//if ($_POST["id_consulta"]!="") {
//	$sql .= " and consultas.id_consulta = '". $_POST["id_consulta"] ."' ";
//}

if ($id_posto!="") {
	$sql .= " and consultas.id_posto = '$id_posto' ";
}

$sql .= "order by consultas.id_consulta asc ";

//echo $sql;

$result= mysql_query($sql) or die(mysql_error());

//echo $_SESSION["id_posto_sessao"] ." ";
//echo pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);


?>


<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2>BOLETIM DIÁRIO DE CONSULTA <?= strtoupper(pega_tipo_consulta_prof($_POST["tipo_consulta_prof"])); ?> (<?= $_POST["data"]; ?>)</h2>

<p><?= pega_posto($_SESSION["id_posto_sessao"]); ?></p>

<div class="parte_total">
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="5%">Nº</th>
			<th width="6%" align="left">C&oacute;d.</th>
			<th width="35%" align="left">Nome</th>
			<th width="10%">Sexo</th>
			<th width="10%" align="left">Idade</th>
			<th width="34%" align="left">OBS</th>
		</tr>
		<?
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $i; ?></td>
			<td align="left"><?= $rs->id_consulta; ?></td>
			<td align="left"><?= $rs->nome; ?></td>
			<td align="center"><?= $rs->sexo; ?></td>
			<td><?= $rs->idade_paciente; ?></td>
			<td><?= $rs->boletim_obs; ?></td>
		</tr>
		<? $i++; } ?>
	</table>

    <br /><br /><br />
    
    <? if ($_POST["id_usuario"]!="") { ?>
    <fieldset>
        <legend>Assinatura e outras observações do médico:</legend>
        <br /><br /><br /><br />
        <div id="assinatura">
            <?= pega_nome_pelo_id_usuario($_POST["id_usuario"]); ?>
            <?= pega_crm_pelo_id_usuario($_POST["id_usuario"]); ?>
        </div>
    </fieldset>
	<? } ?>
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>