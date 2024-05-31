<? if (@pode_algum("fx", $_SESSION["permissao"])) { ?>

<h2>Edição de remédio</h2>

<form action="<?= AJAX_FORM; ?>formRemedioEditar" method="post" id="formRemedioEditar" name="formRemedioEditar" onsubmit="return ajaxForm('conteudo', 'formRemedioEditar');">
	
	<?
	$result= mysql_query("select * from remedios where id_remedio = '$id_remedio' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_remedio; ?>
	<input name="id_remedio" id="id_remedio" type="hidden" class="escondido" value="<?= $rs->id_remedio; ?>" />
	<br />
	
	<label for="remedio">Remédio:</label>
	<input name="remedio" id="remedio" value="<?= $rs->remedio; ?>" />
	<br />

	<label for="tipo_remedio">Tipo:</label>
	<select name="tipo_remedio" id="tipo_remedio">
	<?
	$vetor= pega_tipo_remedio('l');
	
	$i=0; $j=0;
	while ($vetor[$i][$j]) {
	?>
	<option value="<?= $vetor[$i][0]; ?>" <? if ($vetor[$i][0]==$rs->tipo_remedio) echo "selected=\"selected\""; ?>><?= $vetor[$i][1]; ?></option>
	<? $i++; } ?>
	</select>
	<br />

	<label>&nbsp;</label>
	<input name="classificacao_remedio" id="classificacao_remedio" type="checkbox" value="c" class="tamanho20" <? if ($rs->classificacao_remedio=='c') echo "checked=\"checked\""; ?> />
	<label for="classificacao_remedio">Controlado</label>
	<br />

	<label>&nbsp;</label>
	<button>Editar</button>
</form>

<script language="javascript" type="text/javascript">daFoco("remedio");</script>


<? } ?>