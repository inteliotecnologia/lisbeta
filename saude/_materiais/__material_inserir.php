<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<h2>Inserção de material</h2>

<form action="<?= AJAX_FORM; ?>formMaterialInserir" method="post" id="formMaterialInserir" name="formMaterialInserir" onsubmit="return ajaxForm('conteudo', 'formMaterialInserir');">

	<label for="material">Material:</label>
	<input name="material" id="material" />
	<br />
	
	<label for="tipo_material">Tipo:</label>
	<select name="tipo_material" id="tipo_material">
		<option value="" selected="selected">---</option>
		<?
		$vetor= pega_tipo_material('l');
		
		$i=0; $j=0;
		while ($vetor[$i][$j]) {
		?>
		<option value="<?= $vetor[$i][0]; ?>"><?= $vetor[$i][1]; ?></option>
		<? $i++; } ?>
	</select>
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>

<script language="javascript" type="text/javascript">daFoco("material");</script>

<? } ?>