<?
if (@pode_algum("oecmin", $_SESSION["permissao"])) {
	$sql= "select procedimentos.*,
			DATE_FORMAT(procedimentos.data_procedimento, '%d/%m/%Y') as data_procedimento2
			from  procedimentos
			where procedimentos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
			and   procedimentos.id_procedimento = '". $_POST["id_procedimento"] ."'
			and   DATE_FORMAT(procedimentos.data_procedimento, '%m/%Y') = '". ($_POST["periodo"]) ."'
		 ";

//echo $sql;

$result= mysql_query($sql) or die(mysql_error());

?>

<h2 class="titulos">Boletim diário de atos não médicos - <?= $_POST["periodo"]; ?> - <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<h3><?= pega_procedimentos($_POST["id_procedimento"]); ?></h3>

<div class="parte_total">
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Nº</th>
			<th width="50%" align="left">Nome</th>
			<th width="15%">Data</th>
			<th width="25%" align="left">Ass. do funcionário</th>
		</tr>
		<?
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $i; ?></td>
			<td align="left"><?= pega_nome($rs->id_pessoa); ?></td>
			<td align="center"><?= $rs->data_procedimento2; ?></td>
			<td>&nbsp;</td>
		</tr>
		<? $i++; } ?>
	</table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>