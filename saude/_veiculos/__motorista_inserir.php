<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<div id="tela_cadastro">
	<?
	$pagina= "_pessoas/pessoa_inserir";
	include("index2.php");
	?>
</div>

<h2>Cadastro de motorista</h2>

<form action="<?= AJAX_FORM; ?>formMotoristaInserir" method="post" id="formMotoristaInserir" name="formMotoristaInserir" onsubmit="return ajaxForm('conteudo', 'formMotoristaInserir');">

	<label for="cpf_usuario">CPF:</label>
	<input name="cpf" id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpf();" value="<?= $cpf_busca; ?>" />
    <a href="javascript:void(0);" onclick="abreFechaDiv('pessoa_buscar');">buscar</a>
	<br />
	
	<label>&nbsp;</label>
		<div id="cpf_usuario_atualiza">
			<input type="hidden" name="id_pessoa_form" id="id_pessoa_form" value="" class="escondido" />
		</div>
	<br />
	
	<label for="id_cidade">Cidade:</label>
	<select name="id_cidade" id="id_cidade">
		<option value="" selected="selected">---</option>
		<?
		$result= mysql_query("select * from cidades, ufs
								where cidades.id_uf = ufs.id_uf
								and   cidades.sistema = '1'
								") or die(mysql_error());
		
		while ($rs= mysql_fetch_object($result)) {
		?>
		<option value="<?= $rs->id_cidade; ?>"><?= $rs->cidade ."/". $rs->uf; ?></option>
		<? } ?>
	</select>
	<br /><br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">
	daFoco('cpf_usuario');
	<? if ($msg==3) { ?>
	usuarioRetornaCpf();
	<? } ?>
</script>
<? } ?>