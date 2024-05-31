<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_con= mysql_query("select consultas.id_consulta, consultas.id_usuario, consultas.id_posto,
                                    DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y') as data_consulta from consultas
                                    where consultas.id_pessoa = '$id_pessoa_hist'
									order by consultas.id_consulta desc limit 5
                                    ");
							
$linhas_con= mysql_num_rows($result_con);

if ($linhas_con>0) {
?>

<p>Foram encontrados <strong><?= $linhas_con; ?></strong> registros(s).</p>
<? //pega_cidade($id_cidade_emula); ?>

<table cellspacing="0">
  <tr>
	<th width="5%">Cód.</th>
    <th width="25%">Data</th>
	<th width="35%" align="left">Local</th>
	<th width="35%">Médico</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_con= mysql_fetch_object($result_con)) {

	echo "
	<tr>
		<td align=\"center\">". $rs_con->id_consulta ."</td>
		<td align=\"center\">". $rs_con->data_consulta ."</td>
		<td>". pega_posto($rs_con->id_posto) ."</td>
		<td align=\"center\">". pega_nome_pelo_id_usuario($rs_con->id_usuario) ."</td>
	</tr>";
}

if ($linhas_con>0) { ?>
	</table>
	<br />
	<center>
    <? if ($tipo_hist=="v") { ?>
	<button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_consultas_completo&amp;id_pessoa_hist=<?= $id_pessoa_hist; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">mais histórico</button>
    <? } else { ?>
    <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_consultas_completo&amp;id_pessoa_hist=<?= $id_pessoa_hist; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">mais histórico</button>
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