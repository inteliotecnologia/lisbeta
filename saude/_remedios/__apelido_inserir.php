<form action="<?= AJAX_FORM; ?>formApelidoInserir" method="post" id="formApelidoInserir" name="formApelidoInserir" onsubmit="return ajaxForm('conteudo', 'formApelidoInserir');">

	<label>Remédio:</label>
	<input type="hidden" name="id_remedio" id="id_remedio" value="<?= $id_remedio; ?>" class="escondido" />
	<?= pega_remedio($id_remedio); ?>
	<br />

	<label for="apelido">Apelido:</label>
	<input name="apelido" id="apelido" />
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('apelido');</script>