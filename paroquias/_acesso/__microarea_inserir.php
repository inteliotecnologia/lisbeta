<? if (pode_algum("m", $_SESSION["permissao"])) { ?>
<h2>Cadastro de quadra</h2>

<form action="<?= AJAX_FORM; ?>formMicroareaInserir" method="post" id="formMicroareaInserir" name="formMicroareaInserir" onsubmit="return ajaxForm('conteudo', 'formMicroareaInserir');">

	<label>Cidade:</label>
	<input type="hidden" name="id_posto" id="id_posto" value="<?= $id_posto; ?>" class="escondido" />
	<?= pega_posto($id_posto); ?>
	<br />

	<label for="microarea">Regi�o:</label>
	<input name="microarea" id="microarea" class="tamanho80" />
	<br />

	<label for="cpf_usuario">Mission�rio:</label>
	<input name="cpf" id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpf();" value="<?= $cpf_busca; ?>" class="tamanho80" />
	<button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button">busca</button>
    <br />
	
	<label>&nbsp;</label>
		<div id="cpf_usuario_atualiza">
			<input type="hidden" name="id_pessoa_form" id="id_pessoa_form" value="" class="escondido" />
		</div>
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('posto');</script>
<? } ?>