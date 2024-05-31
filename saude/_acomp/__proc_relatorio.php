<?
if ( (@pode("r", $_SESSION["permissao"])) || (@pode("c", $_SESSION["permissao"])) ) {
	if ($periodo=="")
		$periodo= date("m/Y");
?>

<h2 class="titulos">Procedimentos em <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens">
	<? include("__tratamento_msgs.php"); ?>
</div>

<div id="tela_aux_rapida" class="nao_mostra">
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formProcBuscar" method="post" id="formProcBuscar" name="formProcBuscar" onsubmit="return ajaxForm('conteudo', 'formProcBuscar');">
		
        <label for="periodo">Período:</label>
		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$result_per= mysql_query("select distinct(DATE_FORMAT(data_procedimento, '%m/%Y')) as data_procedimento from procedimentos");
			while ($rs_per= mysql_fetch_object($result_per)) {
			?>
			<option value="<?= $rs_per->data_procedimento; ?>" <? if ($_POST["periodo"]==$periodo) echo "selected=\"selected\""; ?>><?= $rs_per->data_procedimento; ?></option>
			<? } ?>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<div class="parte_total">
	<br /><br />
    
	<table cellspacing="0">
		<tr>
			<th width="70%" align="left">Procedimento</th>
			<th width="30%">Total</th>
		</tr>
		<?
		$vetor= pega_procedimentos('l');
		$i= 1;
		
		while ($vetor[$i]) {
			$rs= mysql_fetch_object(mysql_query("select sum(qtde) as total from procedimentos
													where id_procedimento = '$i'
													and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
													and   DATE_FORMAT(data_procedimento, '%m/%Y') = '$periodo'
													"));
		?>
		<tr class="corzinha">
			<td><?= $vetor[$i]; ?></td>
            <td align="center"><?= number_format($rs->total, 0, ',', '.'); ?></td>
		</tr>
		<? $i++; } ?>
	</table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>