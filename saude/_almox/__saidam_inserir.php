<? if (@pode("x", $_SESSION["permissao"])) { ?>

<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<div id="tela_cadastro">
</div>

<h2 class="titulos">Saída no estoque</h2>

<div class="parte_esquerda_i">
	<fieldset>
		<legend>Pesquisa de material</legend>

		<label>Local:</label>
		<?
		if ($_SESSION["id_posto_sessao"]!="") {
			$local= pega_posto($_SESSION["id_posto_sessao"]);
			$ident_local= 'p';
		}
		if ($_SESSION["id_cidade_sessao"]!="") {
			$local= pega_cidade($_SESSION["id_cidade_sessao"]);
			$ident_local= 'c';
		}
		
		echo $local;
		?>
		<br /><br />
		
		<label>Material:</label>
		<input id="pesquisa" name="pesquisa" class="tamanho80" value="<?= $termo_pesquisado; ?>" onkeyup="if (event.keyCode==13) materialPesquisar('s');" />
		<button type="button" class="tamanho30" onclick="materialPesquisar('s');">ok</button>
		<br />
			
		<div id="pesquisa_material_atualiza">
		</div>
	</fieldset>
</div>

<div class="parte_direita_i">
    <div id="pessoa_buscar" class="escondido">
		<?
		include("_pessoas/__pessoa_buscar.php");
		?>
	</div>
    
    <fieldset>
		<legend>Formulário de saída</legend>

		<form action="<?= AJAX_FORM; ?>formMAlmoxSaida" method="post" id="formMAlmoxSaida" name="formMAlmoxSaida" onsubmit="return ajaxForm('conteudo', 'formMAlmoxSaida');">
			
			<input name="ident_local" id="ident_local" type="hidden" class="escondido" value="<?= $ident_local; ?>" />
			
            <?
			if ($_SESSION["id_cidade_sessao"]!="")
				$id_cidade_emula= $_SESSION["id_cidade_sessao"];
			else
				$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
			
			?>
            
            <input name="modo_almox" id="modo_almox" type="hidden" class="escondido" value="<?= pega_modo_almox($id_cidade_emula); ?>" />
            
			<label>Material:</label>
			<input name="id_material" id="id_material" type="hidden" class="escondido" />
			<input name="tit_material" id="tit_material" disabled="disabled" value="selecione" class="tamanho200" />
			<br />
			
			<label>Qtde atual:</label>
			<!--<input name="tit_qtde_c" id="tit_qtde_c" disabled="disabled" value="selecione" class="tamanho50" /> caixa(s)
			<br />
			<label>&nbsp;</label>-->
			<input name="tit_qtde_u" id="tit_qtde_u" disabled="disabled" value="selecione" class="tamanho50" />
			<br />
			
			<label for="qtde">Quantidade:</label>
			<input name="qtde" id="qtde" class="tamanho50" disabled="disabled" />
			
			<div style="display: none;"><input type="radio" id="tipo_apres_c" name="tipo_apres" value="c" class="tamanho30" /> <label for="tipo_apres_c" class="tamanho30">cxs</label></div>
			<div style="display: none;"><input type="radio" id="tipo_apres_u" name="tipo_apres" value="u" class="tamanho30" checked="checked" /> <label for="tipo_apres_u" class="tamanho30">unid</label></div>
			<br />
		
			<label for="subtipo_trans">Motivo:</label>
			<select name="subtipo_trans" id="subtipo_trans" onchange="alteraDestinoSaidaMats();" disabled="disabled">
				<option value="" selected="selected">--- selecione ---</option>
				<?
				$vetor= pega_origem_saida('l');
				
				$i=0; $j=0; $k=0;
				while ($vetor[$i][$j]) {
				?>
				<option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>"><?= $vetor[$i][1]; ?></option>
				<? $i++; $k++; } ?>
			</select>
			<br />
			
            <div id="destino_saida_atualiza">
				<input type="hidden" name="id_pessoa_mesmo" id="id_pessoa_mesmo" value="0" class="escondido" />
			</div>
            
			<label for="observacoes">OBS:</label>
			<textarea name="observacoes" id="observacoes" disabled="disabled"></textarea>
			<br />
		
			<label>&nbsp;</label>
			<button id="botaoInserir">Inserir</button>
		</form>
	</fieldset>
</div>
<script language="javascript" type="text/javascript">
	daFoco('pesquisa');
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>