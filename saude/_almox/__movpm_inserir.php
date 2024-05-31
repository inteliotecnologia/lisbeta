<? if ( ($_SESSION["id_cidade_sessao"]!="") && (@pode("x", $_SESSION["permissao"])) ) { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Movimentação para posto</h2>

<div class="parte_esquerda_i">
	<fieldset>
		<legend>Pesquisa de material</legend>

		<label>Data:</label>
		<?= date("d/m/Y"); ?>
		<br />
		
		<label>Material:</label>
		<input id="pesquisa" name="pesquisa" class="tamanho80" onkeyup="if (event.keyCode==13) materialPesquisar('m');" />
		<button type="button" class="tamanho30" onclick="materialPesquisar('m');">ok</button>
		<br />
			
		<div id="pesquisa_material_atualiza">
		</div>
	</fieldset>
</div>

<div class="parte_direita_i">
	
	<fieldset>
		<legend>Formulário de movimentação</legend>
	
		<form action="<?= AJAX_FORM; ?>formMAlmoxMovPosto" method="post" id="formMAlmoxMovPosto" name="formMAlmoxMovPosto" onsubmit="return ajaxForm('conteudo', 'formMAlmoxMovPosto');">
			
			<input name="subtipo_trans" id="subtipo_trans" class="escondido" disabled="disabled" />
			
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
			
			<div style="display: none;"><input type="radio" id="tipo_apres_c" name="tipo_apres" value="c" class="tamanho30" /> <label for="tipo_apres_c" class="tamanho30">cxs</label>
			<input type="radio" id="tipo_apres_u" name="tipo_apres" value="u" class="tamanho30" checked="checked" /> <label for="tipo_apres_u" class="tamanho30">unid</label></div>
			
			<br />
		
			<label for="id_posto_d">Posto:</label>
			<select name="id_posto_d" id="id_posto_d">
				<option value="">--- selecione ---</option>
				<?
				$result_pos= mysql_query("select postos.id_posto, postos.posto from postos
											where postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											");
				$i=0;
				while ($rs_pos= mysql_fetch_object($result_pos)) {
				?>
				<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pos->id_posto; ?>"><?= $rs_pos->posto; ?></option>
				<? $i++; } ?>
			</select>	
			<br />
		
			<label for="observacoes">OBS:</label>
			<textarea name="observacoes" id="observacoes" disabled="disabled"></textarea>
			<br />
		
			<label>&nbsp;</label>
			<input type="hidden" id="botaoInserir" class="escondido" />
			<button>Inserir</button>
		</form>
	</fieldset>
</div>
<script language="javascript" type="text/javascript">daFoco('pesquisa');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>