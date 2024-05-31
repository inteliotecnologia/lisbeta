<?
if (@pode("d", $_SESSION["permissao"])) {
?>

<h2 class="titulos">Procedimentos em <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens">
<?
include("__tratamento_msgs.php");
?>
</div>

<div id="tela_aux_rapida" class="nao_mostra">
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formProcBuscar" method="post" id="formProcBuscar" name="formProcBuscar" onsubmit="return ajaxForm('conteudo', 'formProcBuscar');">
		
        <label for="periodo">Período:</label>
		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$i=0;
			$result_per= mysql_query("select distinct(DATE_FORMAT(data_procedimento, '%m/%Y')) as data_procedimento from procedimentos order by data_procedimento desc");
			while ($rs_per= mysql_fetch_object($result_per)) {
			?>
			<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_per->data_procedimento; ?>" <? if ($_POST["periodo"]==$rs_per->data_procedimento) echo "selected=\"selected\""; ?>><?= $rs_per->data_procedimento; ?></option>
			<? $i++; } ?>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<div class="parte_total">
	<br /><br />
    
	<table cellspacing="0" width="100%">
		<tr>
			<th width="6%">Cód.</th>
            <th width="23%" align="left">Procedimento</th>
            <th width="13%">Data</th>
			<th width="13%">Quantidade</th>
            <th width="33%" align="left">Evolu&ccedil;&atilde;o</th>
            <th width="12%" align="left">Ações</th>
		</tr>
		<?
		if ($periodo=="") $periodo= date("m/Y");
		else $periodo= $_POST["periodo"];
		
		$result= mysql_query("select *, DATE_FORMAT(data_procedimento, '%d/%m/%Y') as data_procedimento2 from procedimentos
								where id_posto = '". $_SESSION["id_posto_sessao"] ."'
								and   DATE_FORMAT(data_procedimento, '%m/%Y') = '$periodo'
								order by data_procedimento desc
								") or die(mysql_error());
		while ($rs= mysql_fetch_object($result)) {
			$tip= "";
			
			if ($rs->id_pessoa!="")
				$tip .= "<strong>Em:</strong> ". pega_nome($rs->id_pessoa) ."<br />";
			$tip .= "<strong>Por:</strong> ". pega_nome_pelo_id_usuario($rs->id_usuario) ."<br />";
		?>
		<tr class="corzinha" onmouseover="Tip('<?=$tip;?>');">
        	<td align="center"><?= $rs->id; ?></td>
			<td><?= pega_procedimentos($rs->id_procedimento); ?></td>
            <td align="center"><?= $rs->data_procedimento2; ?></td>
            <td align="center"><?= number_format($rs->qtde, 0, ',', '.'); ?></td>
            <td><?= $rs->evolucao; ?></td>
            <td>
            	<a onclick="return confirm('Tem certeza que deseja excluir este registro?');" href="javascript:ajaxLink('conteudo', 'procExcluir&amp;id=<?= $rs->id; ?>');" class="link_excluir" title="Excluir">excluir</a>            </td>
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