<? if (@pode_algum("fx", $_SESSION["permissao"])) { ?>

<h2>Inserção de remédio</h2>

<form action="<?= AJAX_FORM; ?>formRemedioInserir" method="post" id="formRemedioInserir" name="formRemedioInserir" onsubmit="return ajaxForm('conteudo', 'formRemedioInserir');">

	<label for="remedio">Remédio:</label>
	<input name="remedio" id="remedio" />
	<br />
	
	<label for="tipo_remedio">Tipo:</label>
	<select name="tipo_remedio" id="tipo_remedio">
		<option value="" selected="selected">---</option>
		<?
		$vetor= pega_tipo_remedio('l');
		
		$i=0; $j=0;
		while ($vetor[$i][$j]) {
		?>
		<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>"><?= $vetor[$i][1]; ?></option>
		<? $i++; } ?>
	</select>
	<br />

	<label>&nbsp;</label>
	<input name="classificacao_remedio" id="classificacao_remedio" type="checkbox" value="c" class="tamanho20" />
	<label for="classificacao_remedio">Controlado</label>
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>

<script language="javascript" type="text/javascript">daFoco("remedio");</script>


<? } ?>