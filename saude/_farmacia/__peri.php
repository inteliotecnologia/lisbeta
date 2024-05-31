<? if (@pode("f", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Distribuição periódica de medicamentos</h2>
	
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
	if ($msg==0) {
		echo "<div class=\"atencao\">Medicamento(s) distribuído(s) com sucesso!</div>";
		if ($ne>0)
			echo "<div class=\"atencao\">Você escolheu não entregar <em>". $ne ."</em> medicamento(s)!</div>";
	}
	else
		echo "<div class=\"atencao\">Não foi possível distribuir, consulte o relatório de movimentação e tente novamente!</div>";
}
?>
</div>

<div class="parte_esquerda_i">
	<fieldset>
		<legend>Busca da pessoa</legend>
		
        <label for="cpf_usuario">CPF:</label>
        <input name="cpf" id="cpf_usuario" maxlength="11" value="" onblur="pegaPeriodico();" class="tamanho100 espaco_dir" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo abaixo.');" />
        <br />
        <br />
        
        <label>&nbsp;</label>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('tipo_volta', '3');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">buscar</button>
        <br />

	</fieldset>
</div>

<div class="parte_direita_i">
	<div id="periodico_atualiza">
	
	</div>
</div>


<script language="javascript" type="text/javascript">
	//abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('tipo_volta', '3');
	daFoco('cpf_usuario');
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>