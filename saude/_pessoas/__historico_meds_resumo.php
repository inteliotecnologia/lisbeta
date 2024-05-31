<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_con= mysql_query("select almoxarifado_mov.*, DATE_FORMAT(data_trans, '%d/%m/%Y') as data_trans from almoxarifado_mov
							where almoxarifado_mov.id_receptor = '$id_pessoa_hist'
							and   ((almoxarifado_mov.tipo_trans= 's'
									and   almoxarifado_mov.subtipo_trans= 'b')
									or almoxarifado_mov.tipo_trans= 'd')
							and   almoxarifado_mov.situacao_mov is NULL
							order by almoxarifado_mov.id_mov desc limit 5
							");
							
$linhas_con= mysql_num_rows($result_con);

if ($linhas_con>0) {
?>

<div id="tela_relatorio">
</div>

<p>Foram encontrados <strong><?= $linhas_con; ?></strong> registros(s).</p>
<? //pega_cidade($id_cidade_emula); ?>

<table cellspacing="0">
  <tr>
	<th width="25%">Data</th>
	<th width="54%" align="left">Medicamento</th>
	<th width="11%">Qtde</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_con= mysql_fetch_object($result_con)) {

	echo "
	<tr>
		<td align=\"center\">". $rs_con->data_trans ."</td>
		<td>". pega_remedio($rs_con->id_remedio) ."</td>
		<td align=\"center\">". $rs_con->qtde ."</td>
	</tr>";
}

if ($linhas_con>0) { ?>
	</table>
	<br />
	<center>
    <? if ($tipo_hist=="v") { ?>
	<button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $id_pessoa_hist; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">mais histórico</button>
    <? } else { ?>
    <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $id_pessoa_hist; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">mais histórico</button>
    <? } ?>
</center>
<? } ?>
<br />
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>