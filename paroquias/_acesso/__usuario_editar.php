<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Edição de usuário</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioEditar" method="post" id="formUsuarioEditar" name="formUsuarioEditar" onsubmit="return ajaxForm('conteudo', 'formUsuarioEditar');">

<?
$result= mysql_query("select pessoas.nome, usuarios.* from pessoas, usuarios
						where pessoas.id_pessoa = usuarios.id_pessoa
						and usuarios.id_usuario = '$id_usuario'
						");
$rs= mysql_fetch_object($result);
?>

	<label class="tamanho50">Nome:</label>
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?= $rs->id_usuario; ?>" class="escondido" />
	<?= $rs->nome; ?>
	<br />
	
	<label class="tamanho50" for="usuario">Tipo:</label>
	<select name="tipo_usuario" id="tipo_usuario">
		<option value="a" <? if ($rs->tipo_usuario == "a") echo "selected=\"selected\""; ?>>Administrador(a)</option>
		<option value="c" <? if ($rs->tipo_usuario == "c") echo "selected=\"selected\""; ?>>Na cidade</option>
		<option value="p" <? if ($rs->tipo_usuario == "p") echo "selected=\"selected\""; ?>>No posto</option>
	</select>
	<br />
	
	<label class="tamanho50" for="usuario">Usuário:</label>
	<input name="usuario" id="usuario" value="<?= $rs->usuario; ?>" onblur="verificaUsuario(<?= $rs->id_usuario; ?>);" />
	<br />

	<label class="tamanho50">&nbsp;</label>
		<div id="nome_usuario_atualiza">
			<input type="hidden" name="permissao_acesso" id="permissao_acesso" value="0" class="escondido" />
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
	daFoco('tipo_usuario');
	verificaUsuario(<?= $rs->id_usuario; ?>);
</script>
<? } ?>