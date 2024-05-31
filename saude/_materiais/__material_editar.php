<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<h2>Edição de material</h2>

<form action="<?= AJAX_FORM; ?>formMaterialEditar" method="post" id="formMaterialEditar" name="formMaterialEditar" onsubmit="return ajaxForm('conteudo', 'formMaterialEditar');">
	
	<?
	$result= mysql_query("select * from materiais where id_material = '$id_material' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_material; ?>
	<input name="id_material" id="id_material" type="hidden" class="escondido" value="<?= $rs->id_material; ?>" />
	<br />
	
	<label for="material">Material:</label>
	<input name="material" id="material" value="<?= $rs->material; ?>" />
	<br />

	<label for="tipo_material">Tipo:</label>
	<select name="tipo_material" id="tipo_material">
	<?
	$vetor= pega_tipo_material('l');
	
	$i=0; $j=0;
	while ($vetor[$i][$j]) {
	?>
	<option value="<?= $vetor[$i][0]; ?>" <? if ($vetor[$i][0]==$rs->tipo_material) echo "selected=\"selected\""; ?>><?= $vetor[$i][1]; ?></option>
	<? $i++; } ?>
	</select>
	<br />

	<label>&nbsp;</label>
	<button>Editar</button>
</form>

<script language="javascript" type="text/javascript">daFoco("material");</script>


<? } ?>