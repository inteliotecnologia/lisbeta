<? if (@pode("f", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Estat疄ticas</h2>

<div class="parte50">
	<fieldset>
		<legend>Consumo mensal por rem嶮io</legend>
		
		<form action="<?= AJAX_FORM; ?>formConsumoMensalRemedio" method="post" id="formConsumoMensalRemedio" name="formConsumoMensalRemedio" onsubmit="return ajaxForm('conteudo', 'formConsumoMensalRemedio');">

			<label for="id_remedio">Rem嶮io:</label>
			<select name="id_remedio" id="id_remedio" class="tamanho200">
				
				<?
				if ($_SESSION["id_cidade_sessao"]!="") {
					$tabela= "almoxarifado_atual";
					$id_lugar= "id_cidade";
					$valor= $_SESSION["id_cidade_sessao"];
				}
				else {
					$tabela= "postos_estoque";
					$id_lugar= "id_posto";
					$valor= $_SESSION["id_posto_sessao"];
				}
				
				$result_rem= mysql_query("select remedios.* from remedios, ". $tabela ."
											where remedios.id_remedio = ". $tabela .".id_remedio
											and   ". $tabela .".". $id_lugar ."= '". $valor ."'
											order by remedios.remedio asc");

				$i=0;
				while ($rs_rem= mysql_fetch_object($result_rem)) {
				?>
				<option class="<? if (($i%2)==0) echo "cor_sim"; if ($rs_rem->classificacao_remedio=="c") echo " cor_cont";  ?>" value="<?= $rs_rem->id_remedio; ?>"><?= $rs_rem->remedio ." (". pega_tipo_remedio($rs_rem->tipo_remedio) .")"; ?></option>
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
		
		<form action="<?= AJAX_FORM; ?>formConsumoMensal" method="post" id="formConsumoMensal" name="formConsumoMensal" onsubmit="return ajaxForm('conteudo', 'formConsumoMensal');">
			
			<label for="periodo">Per甐do:</label>
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

<div class="parte50">
	<fieldset>
		<legend>Balan蔞 completo</legend>
		
		<form action="<?= AJAX_FORM; ?>formBalancoFarmacia" method="post" id="formBalancoFarmacia" name="formBalancoFarmacia" onsubmit="return ajaxForm('conteudo', 'formBalancoFarmacia');">
			
			<label for="ano">Ano:</label>
			<select name="ano" id="ano">
				<?
				for ($i=date("Y"); $i>=2007; $i--) {
				?>
            	<option class="<? if (($i%2)==0) echo "cor_sim"; ?>" value="<?= $i; ?>"><?= $i; ?></option>
                <? } ?>
            </select>
			<br />

			<label for="periodo">Periodicidade:</label>
            <input type="radio" name="periodicidade" id="periodicidade_1" value="1" class="tamanho20" checked="checked" /> <label class="tamanho20 nao_negrito" for="periodicidade_1">1演ri</label>
			<input type="radio" name="periodicidade" id="periodicidade_2" value="2" class="tamanho20" /> <label class="tamanho20 nao_negrito" for="periodicidade_2">2演ri</label>
			<input type="radio" name="periodicidade" id="periodicidade_3" value="3" class="tamanho20" /> <label class="tamanho20 nao_negrito" for="periodicidade_3">3演ri</label>
			<input type="radio" name="periodicidade" id="periodicidade_4" value="4" class="tamanho20" /> <label class="tamanho20 nao_negrito" for="periodicidade_4">4演ri</label>	
            <input type="radio" name="periodicidade" id="periodicidade_a" value="a" class="tamanho20" /> <label class="tamanho20 nao_negrito" for="periodicidade_a">anual</label>	
			<br /><br />
            
            <label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
    &nbsp;
</div>

<div class="parte50">
	<fieldset>
		<legend>Rela誽o de pessoas/medicamentos</legend>
		
		<form action="<?= AJAX_FORM; ?>formPessoasMedicamentos" method="post" id="formPessoasMedicamentos" name="formPessoasMedicamentos" onsubmit="return ajaxForm('conteudo', 'formPessoasMedicamentos');">
			
            <br /><br />
			<label>&nbsp;</label>
			<button>Gerar</button>
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