<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_proc= mysql_query("select procedimentos.*, postos.posto,
                                    DATE_FORMAT(procedimentos.data_procedimento, '%d/%m/%Y') as data_procedimento2
									from  procedimentos, postos
                                    where procedimentos.id_pessoa = '". $id_pessoa_hist ."'
									and   procedimentos.id_posto = postos.id_posto
									and   postos.id_cidade = '$id_cidade_emula'
									order by data_procedimento desc
                                    ");
							
$linhas_proc= mysql_num_rows($result_proc);

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

<h2 class="titulos" <?= $str_tit; ?>>Evolução de <?= pega_nome($id_pessoa_hist); ?>  <? if ($cpf_mostra!="") echo formata_cpf($cpf_mostra); ?></h2>

<? if ($linhas_proc>0) { ?>

<p>Foram encontrados <strong><?= $linhas_proc; ?></strong> registros(s).</p>

<table cellspacing="0" width="100%">
  <tr>
	<th width="3%">ID</th>
	<th width="11%">Data</th>
	<th width="15%" align="left">Posto</th>
	<th width="16%" align="left">Procedimento</th>
	<th width="7%">Qtde</th>
    <th width="31%" align="left">Evolução</th>
    <th width="17%" align="left">Atendido por</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_proc= mysql_fetch_object($result_proc)) {
?>
<tr>
	<td align="center"><?= $rs_proc->id_procedimento; ?></td>
	<td align="center"><?= $rs_proc->data_procedimento2; ?></td>
	<td align="left"><?= $rs->posto; ?></td>
	<td><?= pega_procedimentos($rs_proc->id_procedimento); ?></td>
	<td align="center"><? if ($rs->qtde=="") echo 1; else echo $rs->qtde; ?></td>
    <td><?= $rs->evolucao; ?></td>
    <td align="left"><?= pega_nome_pelo_id_usuario($rs_proc->id_usuario); ?></td>
</tr>
<? } ?>

<?
if ($linhas_proc>0) {
?>
</table>
<? } ?>
<br />
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>