<? if (@pode("f", $_SESSION["permissao"])) { ?>
<?
if ($_SESSION["id_posto_sessao"]!="")
	$str= "almoxarifado_mov.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
if ($_SESSION["id_cidade_sessao"]!="")
	$str= "almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";


$result= mysql_query("select almoxarifado_mov.*, DATE_FORMAT(data_trans, '%d/%m/%Y %H:%i:%s') as data_trans
						from  almoxarifado_mov
						where almoxarifado_mov.id_mov = '$id_mov'
						and  ". $str ." 
						") or die(mysql_error());
$rs= mysql_fetch_object($result);
?>
<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'movListar');">&lt;&lt; voltar</a>

<h2 class="titulos">Dados da movimentação</h2>

<div class="parte_total">

	<fieldset>
		<legend>Dados da movimentação</legend>
		
		<label>Cód.:</label>
		<?= $rs->id_mov; ?>
		<br />
		
		<label>Tipo:</label>
		<?= pega_tipo_transacao($rs->tipo_trans); ?>
		<br />
		
		<label>Remédio:</label>
		<?= pega_remedio($rs->id_remedio); ?>
		<br />
		
		<label>Quantidade:</label>
		<?= number_format($rs->qtde, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres) ; ?>
		<br />
		
		<?
		if ( ($rs->tipo_trans=='e') || ($rs->tipo_trans=='s') ) {
			
			if ($rs->tipo_trans=='e') {
				$subtipo= pega_origem_entrada($rs->subtipo_trans);
				?>
		<label>Fornecedor:</label>
		<?= pega_fornecedor($rs->id_fornecedor); ?>
		<br />
				<?
			}
			else
				$subtipo= pega_origem_saida($rs->subtipo_trans);
				
		?>
		<label>Subtipo:</label>
		<?= $subtipo; ?>
		<br />
		<?
		}
		else {
			if ($rs->tipo_trans=='m')
				$destino= pega_posto($rs->id_receptor);
			else
				$destino= pega_nome($rs->id_receptor);

		?>
		
		<?
		if ($rs->id_posto!="")
			$origem= pega_posto($rs->id_posto);
		if ($rs->id_cidade!="")
			$origem= "ALMOXARIFADO CENTRAL - ". pega_cidade($rs->id_cidade);
		?>
		
		<label>Origem:</label>
		<?= $origem; ?>
		<br />
		
		<label>Destino:</label>
		<?= $destino; ?>
		<br />
		
		<? } ?>
		
		<? if (($rs->tipo_trans=='s') && ($rs->id_receptor!='')) { ?>
		<label>Destino:</label>
		<?= pega_nome($rs->id_receptor); ?>
		<br />
		<? } ?>
		
		<label>Data:</label>
		<?= $rs->data_trans; ?>
		<br />
	
		<label>Funcionário:</label>
		<?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
		<br />
		
		<label>Observações:</label>
		<?
		if ($rs->observacoes!="")
			echo $rs->observacoes;
		else
			echo "<span class=\"vermelho\">Não informado!</span>";
		?>
		<br />
		

	</fieldset>
	
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>