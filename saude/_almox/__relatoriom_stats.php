<? if (@pode("x", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Estatísticas</h2>

<div class="parte50">
	<fieldset>
		<legend>Consumo mensal por material</legend>
		
		<form action="<?= AJAX_FORM; ?>formConsumoMensalMaterial" method="post" id="formConsumoMensalMaterial" name="formConsumoMensalMaterial" onsubmit="return ajaxForm('conteudo', 'formConsumoMensalMaterial');">

			<label for="id_material">Material:</label>
			<select name="id_material" id="id_material" class="tamanho200">
				
				<?
				if ($_SESSION["id_cidade_sessao"]!="") {
					$tabela= "almoxarifadom_atual";
					$id_lugar= "id_cidade";
					$valor= $_SESSION["id_cidade_sessao"];
				}
				else {
					$tabela= "postosm_estoque";
					$id_lugar= "id_posto";
					$valor= $_SESSION["id_posto_sessao"];
				}
				
				$result_mat= mysql_query("select materiais.* from materiais, ". $tabela ."
											where materiais.id_material = ". $tabela .".id_material
											and   ". $tabela .".". $id_lugar ."= '". $valor ."'
											order by materiais.material asc");

				$i=0;
				while ($rs_mat= mysql_fetch_object($result_mat)) {
				?>
				<option class="<? if (($i%2)==0) echo "cor_sim"; ?>" value="<?= $rs_mat->id_material; ?>"><?= $rs_mat->material ." (". pega_tipo_material($rs_mat->tipo_material) .")"; ?></option>
				<? $i++; } ?>
			</select>
			<br /><br />
			
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>
<div class="parte50">
	<fieldset>
		<legend>Consumo mensal geral</legend>
		
		<form action="<?= AJAX_FORM; ?>formConsumoMMensal" method="post" id="formConsumoMMensal" name="formConsumoMMensal" onsubmit="return ajaxForm('conteudo', 'formConsumoMMensal');">
			
			<label for="periodo">Período:</label>
			<select name="periodo" id="periodo">
				<?
				for ($i=-1; $i<6; $i++) {
					$valor= date("m/Y", mktime(0, 0, 0, date("m")-$i, 0, date("Y")));
				?>
            	<option class="<? if (($i%2)==0) echo "cor_sim"; ?>" value="<?= $valor; ?>"><?= $valor; ?></option>
                <? } ?>
            </select>
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