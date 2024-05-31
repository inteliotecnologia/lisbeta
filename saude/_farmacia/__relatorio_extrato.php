<? if (@pode("f", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Extrato de movimentação de medicamento </h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formExtrato" method="post" id="formExtrato" name="formExtrato" onsubmit="return ajaxForm('conteudo', 'formExtrato');">

			<!--<label for="local">Local:</label>
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
			<br />-->
			
			<label for="id_remedio">Remédio:</label>
			<select name="id_remedio" id="id_remedio" class="tamanho200">
				
				<?
				$result_rem= mysql_query("select * from remedios order by remedio asc");
				$i=0;
				while ($rs_rem= mysql_fetch_object($result_rem)) {
				?>
				<option class="<? if (($i%2)==0) echo "cor_sim"; if ($rs_rem->classificacao_remedio=="c") echo " cor_cont";  ?>" value="<?= $rs_rem->id_remedio; ?>"><?= $rs_rem->remedio ." (". pega_tipo_remedio($rs_rem->tipo_remedio) .")"; ?></option>
				<? $i++; } ?>
			</select>
			<br />
			
			<label for="tipo_trans">Período:</label>
			
			<span class="flutuar_esquerda">De&nbsp;</span>
			
			<input name="inicio" id="inicio" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["inicio"]; ?>" title="Data inicial" />
			<span class="flutuar_esquerda">a&nbsp;</span>
			<input name="fim" id="fim" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["fim"]; ?>" title="Data final" />
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