<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<div id="tela_cadastro">
	<?
	$pagina= "_pessoas/pessoa_inserir";
	include("index2.php");
	?>
</div>

<h2>Cadastro de usuário</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioInserir" method="post" id="formUsuarioInserir" name="formUsuarioInserir" onsubmit="return ajaxForm('conteudo', 'formUsuarioInserir');">

	<label class="tamanho50" for="cpf_usuario">CPF:</label>
	<input name="cpf" id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpf();" value="<?= $cpf_busca; ?>" />
    <button type="button" onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');">buscar</button>
	<br />
	
	<label class="tamanho50">&nbsp;</label>
		<div id="cpf_usuario_atualiza">
            <input type="hidden" name="id_pessoa_form" id="id_pessoa_form" value="" class="escondido" />
		</div>
	<br />
	
	<label class="tamanho50" for="tipo_usuario">Tipo:</label>
	<select name="tipo_usuario" id="tipo_usuario">
		<option selected="selected" value="">--- selecione ---</option>
		<option value="a" class="cor_sim">Administrador(a)</option>
		<option value="c">Na cidade</option>
		<option value="p" class="cor_sim">No posto</option>
	</select>
	<br />
	
	<label class="tamanho50" for="usuario">Usuário:</label>
	<input name="usuario" id="usuario" onblur="verificaUsuario(0);" />
	<br />

	<label class="tamanho50">&nbsp;</label>
		<div id="nome_usuario_atualiza">
			<input type="hidden" name="permissao" id="permissao" value="0" class="escondido" />
		</div>
	<br />

	<label class="tamanho50" for="senha">Senha:</label>
	<input type="password" name="senha" id="senha" />
	<br />

	<label class="tamanho50" for="senha2">Confirma senha:</label>
	<input type="password" name="senha2" id="senha2" />
	<br />

	<label class="tamanho50">&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">
	daFoco('cpf_usuario');
	<? if ($msg==3) { ?>
	usuarioRetornaCpf();
	<? } ?>
</script>
<? } ?>