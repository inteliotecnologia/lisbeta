<? if (($_SESSION["id_cidade_sessao"]!="") && (@pode("f", $_SESSION["permissao"])) ) { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Entrada no estoque</h2>

<div class="parte_esquerda_i">
	<fieldset>
		<legend>Pesquisa de remédio</legend>
		
		<label>Data:</label>
		<?= date("d/m/Y"); ?>
		<br />
		
		<label>Remédio:</label>
		<input id="pesquisa" name="pesquisa" class="tamanho80" onkeyup="if (event.keyCode==13) remedioPesquisar(2, 'e');" />
		<button type="button" class="tamanho30" onclick="remedioPesquisar(2, 'e');">ok</button>
		<br />
			
		<div id="pesquisa_remedio_atualiza">
		</div>
	</fieldset>
</div>

<div class="parte_direita_i">
	
	<fieldset>
		<legend>Formulário de entrada</legend>
	
		<form action="<?= AJAX_FORM; ?>formAlmoxEntrada" method="post" id="formAlmoxEntrada" name="formAlmoxEntrada" onsubmit="return ajaxForm('conteudo', 'formAlmoxEntrada');">
			
			<input name="classificacao_remedio" id="classificacao_remedio" type="hidden" class="escondido" value="" />
			
			<label>Remédio:</label>
			<input name="id_remedio" id="id_remedio" type="hidden" class="escondido" />
			<input name="tit_remedio" id="tit_remedio" disabled="disabled" value="selecione" class="tamanho200" />
			<br />
			
			<label>Qtde atual:</label>
			<!--<input name="tit_qtde_c" id="tit_qtde_c" disabled="disabled" value="selecione" class="tamanho50" /> caixa(s)
			<br />
			<label>&nbsp;</label>-->
			<input name="tit_qtde_u" id="tit_qtde_u" disabled="disabled" value="selecione" class="tamanho50" /> unidade(s)
			<br />
			
			<label for="qtde">Quantidade:</label>
			<input name="qtde" id="qtde" class="tamanho50" disabled="disabled" />
			
		  <? /*<div style="display: none;"><input type="radio" id="tipo_apres_c" name="tipo_apres" value="c" class="tamanho30" /> <label for="tipo_apres_c" class="tamanho30">cxs</label></div> */ ?> unidade(s)<br />

			<label>&nbsp;</label>
			<a href="javascript:void(0);" onclick="abreFechaDiv('almox_direita');">controlar validade</a>
			<br />
		
			<label for="subtipo_trans">Origem:</label>
			<select name="subtipo_trans" id="subtipo_trans" disabled="disabled">
				<? /* <!--<option value="" selected="selected">--- selecione ---</option>-->*/ ?>
				<?
				$vetor= pega_origem_entrada('l');
				
				$i=0; $j=0; $k=0;
				while ($vetor[$i][$j]) {
				?>
				<option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $vetor[$i][0]; ?>"><?= $vetor[$i][1]; ?></option>
				<? $i++; $k++; } ?>
			</select>
			<br />
			
			<label for="id_fornecedor">Fornecedor:</label>
			<select name="id_fornecedor" id="id_fornecedor">
				<option value="" selected="selected">--- selecione ---</option>
				<?
				$result_fornecedor= mysql_query("select * from fornecedores order by fornecedor");
				$i=0;
				while ($rs_fornecedor= mysql_fetch_object($result_fornecedor)) {
				?>
				<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_fornecedor->id_fornecedor; ?>"><?= $rs_fornecedor->fornecedor; ?></option>
				<? $i++;  } ?>				
			</select>
			<br />

			<label for="observacoes">OBS:</label>
			<textarea name="observacoes" id="observacoes" disabled="disabled"></textarea>
			<br />
			
			<div id="almox_direita" class="nao_mostra">
				<fieldset>
					<legend>Dados para controle de validade</legend>

					<label for="lote">Lote:</label>
					<input name="lote" id="lote" />
					<br />
					
					<label for="data_validade">Validade:</label>
					<input name="data_validade" id="data_validade" onkeyup="formataData(this);" maxlength="10" />
					<br /><br />
				</fieldset>
			</div>

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