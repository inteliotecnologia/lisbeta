<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Cadastro de posto</h2>

<form action="<?= AJAX_FORM; ?>formPostoInserir" method="post" id="formPostoInserir" name="formPostoInserir" onsubmit="return ajaxForm('conteudo', 'formPostoInserir');">

	<label>Cidade:</label>
	<input type="hidden" name="id_cidade" id="id_cidade" value="<?= $id_cidade; ?>" class="escondido" />
	<?= pega_cidade($id_cidade); ?>
	<br />

	<label for="posto">Posto:</label>
	<input name="posto" id="posto" />
	<br />
    
	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('posto');</script>
<? } ?>