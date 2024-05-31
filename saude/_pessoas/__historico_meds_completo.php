<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_con= mysql_query("select almoxarifado_mov.*, pessoas.nome as funcionario, DATE_FORMAT(data_trans, '%d/%m/%Y %H:%i:%s') as data_trans from
							almoxarifado_mov, usuarios, pessoas
							where almoxarifado_mov.id_receptor = '$id_pessoa_hist'
							and   ((almoxarifado_mov.tipo_trans= 's'
									and   almoxarifado_mov.subtipo_trans= 'b')
									or almoxarifado_mov.tipo_trans= 'd')
							and   almoxarifado_mov.situacao_mov is NULL
							and   almoxarifado_mov.situacao_mov is NULL
							and   almoxarifado_mov.id_usuario = usuarios.id_usuario
							and   usuarios.id_pessoa = pessoas.id_pessoa
							order by almoxarifado_mov.id_mov desc
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

<h2 class="titulos" <?= $str_tit; ?>>Histórico completo de medicamentos entregues - <?= pega_nome($id_pessoa_hist); ?> <? if ($cpf_mostra!="") echo formata_cpf($cpf_mostra); ?></h2>

<? if ($linhas_con>0) { ?>

<p>Foram encontrados <strong><?= $linhas_con; ?></strong> registros(s).</p>

<table cellspacing="0">
  <tr>
	<th width="5%">ID</th>
	<th width="16%">Data</th>
	<th width="20%" align="left">Medicamento</th>
	<th width="5%">Qtde</th>
	<th width="20%">Local</th>
	<th width="20%">Funcionário</th>
    <th width="13%" align="left">OBS</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_con= mysql_fetch_object($result_con)) { ?>
<tr>
	<td align="center"><?= $rs_con->id_mov; ?></td>
	<td align="center"><?= $rs_con->data_trans; ?></td>
	<td align="left"><?= pega_remedio($rs_con->id_remedio); ?></td>
	<td align="center"><?= $rs_con->qtde; ?></td>
	<td align="center">
		<?
		if ($rs_con->id_cidade!="")
			echo pega_cidade($rs_con->id_cidade);
		else
			echo pega_posto($rs_con->id_posto);
		?>
	</td>
	<td align="center"><?= $rs_con->funcionario; ?></td>
    <td><?= $rs_con->observacoes; ?></td>
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