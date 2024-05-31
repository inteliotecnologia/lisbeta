<? if (@pode("r", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Prontuário de atendimento</h2>
	
<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>


<div id="tela_mensagens">
<?
if (isset($msg)) {
	if ($msg==0)
		echo "<div class=\"atencao2\">Não foi possível inserir, tente novamente ou entre em contato com o suporte técnico!</div>";
	if ($msg==1)
		echo "<div class=\"atencao\">Pessoa inserida na fila com sucesso!</div>";
	if ($msg==2)
		echo "<div class=\"atencao\">Não foi possível cadastrar esta pessoa!</div>";
	if ($msg==3)
		echo "<div class=\"atencao\">Pessoa cadastrada com sucesso!</div>";
	if ($msg==4)
		echo "<div class=\"atencao\">Esta pessoa já está aguardando na fila!</div>";
}
?>
</div>

<div class="parte_esquerda_i">
	<fieldset>
		<legend>Acesso ao prontuário</legend>
		<form id="formProntuario" name="formProntuario" method="post" action="<?= AJAX_FORM ?>formProntuario" onsubmit="return false;">
		
			<label for="cpf_usuario">CPF:</label>
			<input name="cpf" id="cpf_usuario" maxlength="11" value="" onblur="pegaProntuario();" class="tamanho100 espaco_dir" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo abaixo.');" />
			<br />
	    	<br />
            
            <label>&nbsp;</label>
            <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('tipo_volta', '2');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">buscar</button>
			<br />
            <? /*
			<label>&nbsp;</label>
			<button type="submit" onclick="return ajaxForm('prontuario_atualiza', 'formProntuario');">Buscar >></button>
			<br />
			*/ ?>
		</form>
	</fieldset>
</div>

<div class="parte_direita_i">
	<div id="prontuario_atualiza">
	
	</div>
</div>


<script language="javascript" type="text/javascript">
	//daFoco('cpf_usuario');
	<? if ($msg==3) { ?>
	ajaxForm('prontuario_atualiza', 'formProntuario');
	<? } ?>
	abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('tipo_volta', '2');
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>