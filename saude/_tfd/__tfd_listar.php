<? if (@pode("t", $_SESSION["permissao"])) { ?>
<?
$sql= "select *,

		DATE_FORMAT(tfds.data_partida, '%d/%m/%Y') as data_partida,
		DATE_FORMAT(tfds.data_retorno_prevista, '%d/%m/%Y') as data_retorno_prevista,
		DATE_FORMAT(tfds.data_retorno, '%d/%m/%Y') as data_retorno
		
		from tfds, tfds_veiculos
		
		where tfds.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
		and   tfds.id_veiculo = tfds_veiculos.id_veiculo
		 ";

if ($id_motorista!="") {
	$sql .= " and tfds.id_motorista = '$id_motorista' ";
}
if ($id_veiculo!="") {
	$sql .= " and tfds.id_veiculo = '$id_veiculo' ";
}
if ($id_cidade_tfd!="") {
	$sql .= " and tfds.id_cidade_tfd = '$id_cidade_tfd' ";
}


$sql .= "order by tfds.id_tfd desc ";

$result= mysql_query($sql) or die(mysql_error());

$total_antes= mysql_num_rows($result);
	
if (!isset($tudo)) {
	$num= 20;
	$total_linhas = mysql_num_rows($result);
	$num_paginas = ceil($total_linhas/$num);
	if (!isset($num_pagina))
		$num_pagina = 0;
	$inicio = $num_pagina*$num;
	
	$result= mysql_query($sql ." limit $inicio, $num") or die(mysql_error());
}
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">TFD's cadastradas</h2>

<div class="parte_total">
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="20%">Data partida</th>
			<th width="20%">Cidade</th>
			<th width="30%">Motorista</th>
			<th width="20%">Veículo</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="maozinha" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_ver&amp;id_tfd=<?= $rs->id_tfd; ?>')">
			<td align="center"><?= $rs->id_tfd; ?></td>
			<td align="center"><?= $rs->data_partida; ?></td>
			<td align="center"><?= pega_cidade($rs->id_cidade_tfd); ?></td>
			<td align="center"><?= pega_nome($rs->id_motorista); ?></td>
			<td align="center"><?= $rs->veiculo; ?></td>
		</tr>
		<? } ?>
	</table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>