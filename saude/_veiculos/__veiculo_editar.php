<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<h2>Edição de veículo</h2>

<form action="<?= AJAX_FORM; ?>formVeiculoEditar" method="post" id="formVeiculoEditar" name="formVeiculoEditar" onsubmit="return ajaxForm('conteudo', 'formVeiculoEditar');">
	
	<?
	$result= mysql_query("select * from tfds_veiculos where id_veiculo = '$id_veiculo' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_veiculo; ?>
	<input name="id_veiculo" id="id_veiculo" type="hidden" class="escondido" value="<?= $rs->id_veiculo; ?>" />
	<br />
	
    <label for="id_cidade">Cidade:</label>
	<select name="id_cidade" id="id_cidade">
		<option value="" selected="selected">---</option>
		<?
		$result_cid= mysql_query("select * from cidades, ufs
								where cidades.id_uf = ufs.id_uf
								and   cidades.sistema = '1'
								") or die(mysql_error());
		
		while ($rs_cid= mysql_fetch_object($result_cid)) {
		?>
		<option value="<?= $rs_cid->id_cidade; ?>" <? if ($rs_cid->id_cidade==$rs->id_cidade) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade ."/". $rs_cid->uf; ?></option>
		<? } ?>
	</select>
	<br />

    
	<label for="veiculo">Veículo:</label>
	<input name="veiculo" id="veiculo" value="<?= $rs->veiculo; ?>" />
	<br />

	<label for="placa">Placa:</label>
	<input name="placa" id="placa" value="<?= $rs->placa; ?>" />
	<br />

	<label>&nbsp;</label>
	<button>Editar</button>
</form>

<script language="javascript" type="text/javascript">daFoco("veiculo");</script>
<? } ?>