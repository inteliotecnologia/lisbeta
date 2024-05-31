<form action="<?= AJAX_FORM; ?>formApelidoEditar" method="post" id="formApelidoEditar" name="formApelidoEditar" onsubmit="return ajaxForm('conteudo', 'formApelidoEditar');">
	
	<?
	$result= mysql_query("select * from apelidos where id_apelido = '$id_apelido' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_apelido; ?>
	<input name="id_apelido" id="id_apelido" type="hidden" class="escondido" value="<?= $rs->id_apelido; ?>" />
	<br />
	
	<label>Remédio:</label>
	<?= pega_remedio($rs->id_remedio); ?>
	<br />
	
	<label for="apelido">Apelido:</label>
	<input name="apelido" id="apelido" value="<?= $rs->apelido; ?>" />
	<br />

	<label>&nbsp;</label>
	<button>Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco("apelido");</script>