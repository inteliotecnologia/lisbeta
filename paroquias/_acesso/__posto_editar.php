<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Edição de posto</h2>

<form action="<?= AJAX_FORM; ?>formPostoEditar" method="post" id="formPostoEditar" name="formPostoEditar" onsubmit="return ajaxForm('conteudo', 'formPostoEditar');">
	
	<?
	$result= mysql_query("select * from postos where id_posto = '$id_posto' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_posto; ?>
	<input name="id_posto" id="id_posto" type="hidden" class="escondido" value="<?= $rs->id_posto; ?>" />
	<br />
	
	<label for="posto">Posto:</label>
	<input name="posto" id="posto" value="<?= $rs->posto; ?>" />
	<br />
    
	<label>&nbsp;</label>
	<button type="submit">Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco("posto");</script>
<? } ?>