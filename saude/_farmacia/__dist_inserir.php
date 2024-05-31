<? if (@pode("f", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Distribuição de medicamentos</h2>

	<div class="parte_esquerda_i"><? /* onsubmit="return ajaxForm('conteudo', 'formConsultaInserir');" */ ?>
		
		<fieldset>
			<legend>Entrada de dados</legend>
			<form id="formDistBuscar" name="formDistBuscar" method="post" action="<?= AJAX_FORM ?>formDistBuscar" onsubmit="return false;">
				
				<label>Origem:</label>
				<?
				if ($_SESSION["id_posto_sessao"]!="")
					$str= pega_posto($_SESSION["id_posto_sessao"]);
				if ($_SESSION["id_cidade_sessao"]!="")
					$str= "ALMOXARIFADO - ". pega_cidade($_SESSION["id_cidade_sessao"]);
				//else
				//	die();
				
				echo $str;
				?>
				<br /><br />
				
				<label for="txt_busca">Busca:</label>
				<input name="txt_busca" id="txt_busca" maxlength="11" value="<?= $txt_busca; ?>" class="tamanho80" /> 
				<br />
				
				<label for="lugar">Buscar por:</label>
				<select name="lugar" id="lugar">
					<option value="cpf">CPF</option>
					<option value="id_consulta" class="cor_sim" selected="selected">Cod. consulta</option>
				</select>
				<br />
				
				<label>&nbsp;</label>
				<button type="submit" onclick="return ajaxForm('dist_atualiza', 'formDistBuscar');">Buscar >></button>
				<br />
			
			</form>
		</fieldset>
		<br />

	</div>
	
	<div class="parte_direita_i">
			
			<fieldset>
				<legend>Dados da consulta</legend>
				
				<div id="dist_atualiza">
					Informe os dados no formulário ao lado!
				</div>				
						
			</fieldset>
			<br />
	
	</div>
	<script language="javascript" type="text/javascript">
		daFoco('txt_busca');
		<? if ( (isset($msg)) && ($msg==0) ) { ?>
		ajaxForm('dist_atualiza', 'formDistBuscar');
		<? } ?>
	</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>