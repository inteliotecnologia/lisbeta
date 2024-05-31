<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_con= mysql_query("select consultas.id_consulta, consultas.id_usuario, consultas.id_posto,
                                    DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta from consultas
                                    where consultas.id_pessoa = '". $id_pessoa_hist ."'
									order by consultas.id_consulta desc limit 5
                                    ");
							
$linhas_con= mysql_num_rows($result_con);

if ($tipo_hist=="v") { ?>
	<a href="javascript:void(0);" onclick="fechaDiv('tela_relatorio');" class="fechar">x</a>
	<?
    } else {
		$str_tit= "class=\"titulos\"";
	?>
	<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_ver&amp;id_pessoa=<?= $id_pessoa_hist; ?>');" id="botao_voltar">&lt;&lt; voltar para dados pessoais</a>
<? } ?>

<?
$cpf_mostra= pega_cpf_pelo_id_pessoa($id_pessoa_hist);
?>

<h2 class="titulos" <?= $str_tit; ?>>Histórico completo de consultas - <?= pega_nome($id_pessoa_hist); ?>  <? if ($cpf_mostra!="") echo formata_cpf($cpf_mostra); ?></h2>

<? if ($linhas_con>0) { ?>

<p>Foram encontrados <strong><?= $linhas_con; ?></strong> registros(s).</p>

<table cellspacing="0">
  <tr>
	<th width="5%">ID</th>
	<th width="25%">Data</th>
	<th width="25%" align="left">Posto</th>
	<th width="25%">Médico</th>
	<th width="20%">Relatório</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_con= mysql_fetch_object($result_con)) {
?>
<tr>
	<td align="center"><?= $rs_con->id_consulta; ?></td>
	<td align="center"><?= $rs_con->data_consulta; ?></td>
	<td align="left"><?= pega_posto($rs_con->id_posto); ?></td>
	<td align="center"><?= pega_nome_pelo_id_usuario($rs_con->id_usuario); ?></td>
	<td align="center">
    	<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_ver&amp;id_consulta=<?= $rs_con->id_consulta; ?>');">clique aqui</a>
    </td>
</tr>
<? } ?>

<?
if ($linhas_con>0)
	echo "</table>";
?>
<br />
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>