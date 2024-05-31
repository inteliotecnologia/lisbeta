<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result_con= mysql_query("select *,
                                    DATE_FORMAT(data_acompanhamento, '%d/%m/%Y') as data_acompanhamento from acompanhamento
                                    where id_pessoa = '". $id_pessoa_hist ."'
									order by id_acompanhamento desc
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

<h2 class="titulos" <?= $str_tit; ?>>Acompanhamento - <?= pega_nome($id_pessoa_hist); ?>  <? if ($cpf_mostra!="") echo formata_cpf($cpf_mostra); ?></h2>

<? if ($linhas_con>0) { ?>

<p>Foram encontrados <strong><?= $linhas_con; ?></strong> registros(s).</p>

<table cellspacing="0">
  <tr>
	<th width="10%">Data</th>
	<th width="20%" align="left">Posto</th>
	<th align="left" width="10%">Idade</th>
    <th width="10%">Tipo</th>
    <th width="10%">Peso</th>
    <th width="10%">Altura</th>
    <th width="10%">EN</th>
	<th width="20%">Atendido por</th>
  </tr>
<?
}
else
	echo "<span class=\"vermelho\">Nenhum registro encontrado!</span>";

while ($rs_con= mysql_fetch_object($result_con)) {
?>
<tr>
	<td align="center"><?= $rs_con->data_acompanhamento; ?></td>
	<td align="left"><?= pega_posto($rs_con->id_posto); ?></td>
    <td align="left">
	<?
    echo $rs_con->idade_anos ." anos";
	if ($rs_con->idade_meses!=0)
		echo $rs_con->idade_meses ." meses";
	?>
    </td>
    <td align="center">
    <?= pega_tipo_acompanhamento($rs_con->tipo_acompanhamento); ?>
    </td>
    <td align="center"><?= number_format($rs_con->peso, 2, ',', '.'); ?> kg</td>
    <td align="center"><?= number_format($rs_con->altura, 2, ',', '.'); ?> m</td>
    <td align="center">
    <?
	switch($rs_con->tipo_acompanhamento) {
		case 'c': $en= pega_en_crianca($rs_con->estado_nutricional); break;
		case 'a': $en= pega_en_adolescente($rs_con->estado_nutricional); break;
		case 'g':
		case 'd':
			$en= pega_en_gestante_adulto($rs_con->estado_nutricional); break;
		case 'i': $en= pega_en_idoso($rs_con->estado_nutricional); break;
	}
	echo $en;
	?>
    </td>
    <td align="center"><?= pega_nome_pelo_id_usuario($rs_con->id_usuario); ?></td>
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