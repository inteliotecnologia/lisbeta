<? if (@pode("x", $_SESSION["permissao"])) { ?>
<?
if ($_SESSION["id_cidade_sessao"]!="") {
	
	$result_val= mysql_query("select *,
								DATE_FORMAT(data_validade, '%d') as dia,
								DATE_FORMAT(data_validade, '%m') as mes,
								DATE_FORMAT(data_validade, '%Y') as ano,
								DATE_FORMAT(data_validade, '%d/%m/%Y') as data_validade2
								from almoxarifadom_mov
								where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   tipo_trans = 'e'
								and   data_validade <> ''
								order by data_validade asc
								");
	
	$hoje= date("Ymd");
	$var= "";
	$num_v= 0;
			
	while ($rs_val= mysql_fetch_object($result_val)) {
		$data_validade= date("Ymd", mktime(0, 0, 0, $rs_val->mes, $rs_val->dia-30, $rs_val->ano));
		
		$data_aviso= date("Ymd", mktime(0, 0, 0, $rs_val->mes, $rs_val->dia+1, $rs_val->ano));
		
		if (($hoje >= $data_validade) && ($hoje <= $data_aviso)) {
			$num_v++;
			$var .= "<li>
						<span class=\"texto_destaque\">". pega_material($rs_val->id_remedio) ."</span> <br />
						Lote <strong>".  $rs_val->lote ."</strong> vence em <strong>". $rs_val->data_validade2 ."</strong>
					</li>";
		}
	}
	
	
	if (($_POST["local"]=="") || ($_POST["local"]=="0")) {
		$tit= pega_cidade($_SESSION["id_cidade_sessao"]);
		$sql= "select materiais.*, almoxarifadom_atual.qtde_atual, almoxarifadom_atual.tipo_apres from almoxarifadom_atual, materiais
					where almoxarifadom_atual.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
					and   almoxarifadom_atual.id_material = materiais.id_material
					order by materiais.material, almoxarifadom_atual.tipo_apres asc
					";
	}
	else {
		$tit= pega_posto($_POST["local"]);
		$sql= "select materiais.*, postosm_estoque.qtde_atual, postosm_estoque.tipo_apres from postosm_estoque, materiais
					where postosm_estoque.id_posto = '". $_POST["local"] ."'
					and   postosm_estoque.id_material = materiais.id_material
					order by materiais.material, postosm_estoque.tipo_apres asc
					";
	}
}
else {
	$tit= pega_posto($_SESSION["id_posto_sessao"]);
	$sql= "select materiais.*, postosm_estoque.qtde_atual, postosm_estoque.tipo_apres from postosm_estoque, materiais
				where postosm_estoque.id_posto = '". $_SESSION["id_posto_sessao"] ."'
				and   postosm_estoque.id_material = materiais.id_material
				order by materiais.material, postosm_estoque.tipo_apres asc
				";
}

$result= mysql_query($sql) or die(mysql_error());

if ($num_v>0) {
?>

<div id="alerta_vencimentos" class="amarelo_transp">
	<h2>Alerta de validade</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('alerta_vencimentos');" class="fechar">x</a>
	<ul>
		<?= $var; ?>
	</ul>
</div>
<? } ?>

<div id="tela_mensagens">
<? include("__tratamento_msgs.php"); ?>
</div>

<? if ($_SESSION["id_cidade_sessao"]!="") { ?>
<div id="busca">
	<form action="<?= AJAX_FORM; ?>formMEstoqueBuscar" method="post" id="formMEstoqueBuscar" name="formMEstoqueBuscar" onsubmit="return ajaxForm('conteudo', 'formMEstoqueBuscar');">
		<label class="tamanho30" for="txt_busca">Local:</label>
	
		<select name="local" id="local" class="tamanho160">
			<option value="0">ALMOX. CENTRAL</option>
			<?
			$result_postos= mysql_query("select postos.* from postos, cidades
									where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
									and   postos.id_cidade = cidades.id_cidade
									and   cidades.sistema = '1'
									") or die(mysql_error());
			$i=0;
			while ($rs_postos= mysql_fetch_object($result_postos)) {
			?>
			<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
			<? $i++; } ?>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>
<? } ?>

<h2 class="titulos">Estoque do almoxarifado - <?= $tit; ?></h2>

<div class="parte_total">

	<p>Foram encontrados <?= mysql_num_rows($result); ?> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="70%" align="left">Material</th>
			<th width="30%" align="right">Qtde</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			//if ($rs->qtde_atual!=0) {
		?>
		<tr class="corzinha">
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/extratom&amp;id_material=<?= $rs->id_material; ?>&amp;origem=almox/estoquem_listar');"><?= $rs->material . " (". pega_tipo_material($rs->tipo_material) .")"; ?></a></td>
			<td align="right"><?= number_format($rs->qtde_atual, 0, ',', '.'); ?></td>
		</tr>
		<? } //} ?>
  </table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>