<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<fieldset>
	<a href="javascript:void(0);" onclick="abreFechaDiv('pessoa_buscar');" class="fechar fechar2">x</a>
	
	<legend>Busca por nome</legend>
	
	<label class="tamanho30" for="nomeb">Nome:</label>
	
	<input name="nomeb" id="nomeb" class="tamanho80" maxlength="11" value="<?= $txt_busca; ?>" onkeydown="if (event.keyCode==13) pessoaPesquisar();" onmouseover="Tip('Digite o início do nome da pessoa ou seu nome completo.<br />Exemplo: \'ped\', \'mari\' ou \'pedro\', \'maria\'.');" />

    <input type="hidden" id="campo_retorno" value="" class="escondido" />
    <input type="hidden" id="tipo_consulta" value="" class="escondido" />
    
    <input type="hidden" id="tipo_volta" value="" class="escondido" />
    
    <input type="hidden" id="mostratudo" value="" class="escondido" />
    
	<button type="button" onclick="pessoaPesquisar();">Buscar</button>
	
	<br />
	
	<div id="pessoa_buscar_resultado">
		
	</div>
</fieldset>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>