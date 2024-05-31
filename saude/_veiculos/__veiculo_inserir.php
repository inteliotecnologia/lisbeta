<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<h2>Inserção de veículo</h2>

<form action="<?= AJAX_FORM; ?>formVeiculoInserir" method="post" id="formVeiculoInserir" name="formVeiculoInserir" onsubmit="return ajaxForm('conteudo', 'formVeiculoInserir');">

	<label for="id_cidade">Cidade:</label>
	<select name="id_cidade" id="id_cidade">
		<option value="" selected="selected">---</option>
		<?
		$result= mysql_query("select * from cidades, ufs
								where cidades.id_uf = ufs.id_uf
								and   cidades.sistema = '1'
								") or die(mysql_error());
		
		while ($rs= mysql_fetch_object($result)) {
		?>
		<option value="<?= $rs->id_cidade; ?>"><?= $rs->cidade ."/". $rs->uf; ?></option>
		<? } ?>
	</select>
	<br />

	<label for="veiculo">Veículo:</label>
	<input name="veiculo" id="veiculo" />
	<br />
	
	<label for="placa">Placa:</label>
	<input name="placa" id="placa" />
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>

<script language="javascript" type="text/javascript">daFoco("material");</script>

<? } ?>