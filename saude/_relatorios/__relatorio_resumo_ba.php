<? if (@pode("s", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Resumo</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formRelatorioResumo" method="post" id="formRelatorioResumo" name="formRelatorioResumo" onsubmit="return ajaxForm('conteudo', 'formRelatorioResumo');">
			<label for="local">Local:</label>
			<select name="local" id="local" class="tamanho120">
				<option value="0">TODOS</option>
				<?
				$result_postos= mysql_query("select postos.* from postos, cidades
												where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
												and   postos.id_cidade = cidades.id_cidade
												and   cidades.sistema = '1'
												") or die(mysql_error());
				while ($rs_postos= mysql_fetch_object($result_postos)) {
				?>
				<option value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
				<? } ?>
			</select>	
			<br />
			
			<label for="periodo">Período:</label>
			<input type="radio" name="tipo_periodo" id="tipo_periodo_f" value="f" class="tamanho30" checked="checked" onclick="fechaDiv('periodo_p'); abreDiv('periodo_f', '', '', '', ''); daFoco('mes_periodo');" /> <label class="abrange" for="tipo_periodo_f">Mês/ano</label>
			<input type="radio" name="tipo_periodo" id="tipo_periodo_p" value="p" class="tamanho30" onclick="fechaDiv('periodo_f'); abreDiv('periodo_p', '', '', '', ''); daFoco('inicio');" /> <label class="abrange" for="tipo_periodo_p">Entre períodos</label>
			<br />
			
			<label>&nbsp;</label>
			<div id="periodo_f">
				<select name="mes_periodo" id="mes_periodo" class="tamanho120">
					<? for ($i=1; $i<13; $i++) { ?>
					<option value="<?= $i ?>"><?= traduz_mes($i); ?></option>
					<? } ?>
				</select>
				
				<select name="ano_periodo" id="ano_periodo" class="tamanho120">
					<? for ($i=2006; $i<=date("Y"); $i++) { ?>
					<option value="<?= $i ?>"><?= $i; ?></option>
					<? } ?>
				</select>
			</div>
			<div id="periodo_p">
				<span class="flutuar_esquerda">De&nbsp;</span>
				<input name="inicio" id="inicio" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["inicio"]; ?>" title="Data inicial" />
				<span class="flutuar_esquerda">a&nbsp;</span>
				<input name="fim" id="fim" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["fim"]; ?>" title="Data final" />
			</div>
			<br /><br />
			
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>