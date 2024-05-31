<? if (@pode("f", $_SESSION["permissao"])) { ?>
<?
if (!isset($tipo_trans))
	$tipo_trans= "todos";
	
if ($_SESSION["id_posto_sessao"]!="")
	$sql= "select almoxarifado_mov.*, DATE_FORMAT(almoxarifado_mov.data_trans, '%d/%m/%Y') as data_trans2, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%d') as dia, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%m') as mes, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%Y') as ano
							from almoxarifado_mov
							where almoxarifado_mov.id_remedio = '$id_remedio'
							and   (almoxarifado_mov.id_posto = '". $_SESSION["id_posto_sessao"] ."'
									or
									almoxarifado_mov.tipo_trans = 'm' and almoxarifado_mov.id_receptor = '". $_SESSION["id_posto_sessao"] ."'  )
							";

else
	$sql= "select almoxarifado_mov.*, DATE_FORMAT(almoxarifado_mov.data_trans, '%d/%m/%Y') as data_trans2, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%d') as dia, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%m') as mes, 
							DATE_FORMAT(almoxarifado_mov.data_trans, '%Y') as ano
							from almoxarifado_mov
							where almoxarifado_mov.id_remedio = '$id_remedio'
							and   almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							". $str ."
							";
		
$sql .= " order by almoxarifado_mov.id_mov asc ";
$result= mysql_query($sql) or die(mysql_error());

@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "tira extrato do rem�dio ". $_GET["id_remedio"] ." | ". pega_remedio($_GET["id_remedio"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
?>

<? if ($origem!="") { ?>
<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $origem; ?>');">&lt;&lt; voltar para estoque</a>
<? } ?>

<h2 class="titulos">Extrato de medicamento - <?= pega_remedio($id_remedio); ?></h2>
<? if ( (isset($_POST["inicio"])) && ($_POST["inicio"]!="") && (isset($_POST["fim"])) && ($_POST["fim"]!="") ) { ?>
	<h3>Entre <?= $_POST["inicio"] . " e ". $_POST["fim"]; ?></h3>
<? } ?>

<div class="parte_total">

	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s) para sua solicita��o</p>
	<br />
	
	<?
	if ( (!isset($tipo_trans)) || ($tipo_trans=="todos") ) {
		$th_str= "Tipo de transa��o";
	}
	
	switch ($tipo_trans) {
		case 'm': $th_str= "Posto";
					break;
		case 'd': $th_str= "Para";
					break;
		case 's': $th_str= "Tipo sa�da";
					break;
	}
	if (mysql_num_rows($result)>0) {
	?>
	
	<table cellspacing="0">
		<tr>
			<th width="10%">C�d.</th>
			<th width="10%">Data</th>
			<th width="10%">Tipo de transa��o</th>
			<th width="12%" align="right">Quantidade</th>
			<th width="12%" align="right">Saldo atual </th>
			<th width="20%">&nbsp;</th>
			<th width="26%" align="left">Autorizado por</th>
		</tr>
		<?
		$i= 0;

		if ( (isset($_POST["inicio"])) && ($_POST["inicio"]!="") && (isset($_POST["fim"])) && ($_POST["fim"]!="") ) {
			$datai= desformata_data($_POST["inicio"]);
			$dataf= desformata_data($_POST["fim"]);
			
			$data_inicial= date("Ymd", mktime(0, 0, 0, $datai[1], $datai[0], $datai[2]));
			$data_final= date("Ymd", mktime(0, 0, 0, $dataf[1], $dataf[0], $dataf[2]));
		}
		
		while ($rs= mysql_fetch_object($result)) {
			$sinal= "<img src=\"images/mais.png\" alt=\"+\" />";
			//diferenciar as movimentacoes do almoxarifado central
			//com as entradas de um posto
			if ($_SESSION["id_posto_sessao"]!="") {
				if ($i==0) {
					$saldo= $rs->qtde;
					$cor= "azul";
				}
				else {
					if (($rs->tipo_trans=="m") || ($rs->tipo_trans=="e")) {
						$saldo += $rs->qtde;
						$cor= "azul";
					}
					else {
						$saldo -= $rs->qtde;
						$sinal= "-";
						$cor= "vermelho";
					}
				}
			}
			else {
				if ($i==0)
					$saldo= $rs->qtde;
				else {
					if ($rs->tipo_trans=="e")
						$saldo += $rs->qtde;
					else {
						$saldo -= $rs->qtde;
						$sinal= "-";
					}
				}
				if ($rs->tipo_trans=="e")
					$cor= "azul";
				else
					$cor= "vermelho";
			}
			
			if ( (isset($_POST["inicio"])) && ($_POST["inicio"]!="") && (isset($_POST["fim"])) && ($_POST["fim"]!="") ) {
				$registro[$i]= date("Ymd", mktime(0, 0, 0, $rs->mes, $rs->dia, $rs->ano));
			}

			if ( (($registro[$i] >= $data_inicial) && ($registro[$i] <= $data_final) ) || !((isset($_POST["inicio"])) && ($_POST["inicio"]!="") && (isset($_POST["fim"])) && ($_POST["fim"]!="")) ) {
		?>
		<tr class="corzinha" onmouseover="abreDivSo('mov_<?= $rs->id_mov; ?>');" onmouseout="fechaDiv('mov_<?= $rs->id_mov; ?>');" <? /*onclick="ajaxLink('conteudo', 'movVer&amp;id_mov=<?= $rs->id_mov; ?>');" */ ?>>
			<td align="center">
				<div id="mov_<?= $rs->id_mov; ?>" class="mov_ver">
					<h2 class="titulos">Visualiza��o da movimenta��o</h2>
					
					<label>C�d.:</label>
					<?= $rs->id_mov; ?>
					<br />
					
					<label>Tipo:</label>
					<?= pega_tipo_transacao($rs->tipo_trans); ?>
					<br />
					
					<label>Rem�dio:</label>
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
					<?
					$forn= pega_fornecedor($rs->id_fornecedor);
					
					if ($forn=="")
						echo "<span class=\"vermelho\">N�o informado!</span>";
					else
						echo $forn;
					?>
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
					
                    <? if ($destino!="") { ?>
					<label>Destino:</label>
					<?= $destino; ?>
					<br />
                    <? } ?>
					
					<? } ?>
					
					<? if (($rs->tipo_trans=='s') && ($rs->id_receptor!='')) { ?>
					<label>Destino:</label>
					<?= pega_nome($rs->id_receptor); ?>
					<br />
					<? } ?>
					
					<label>Data:</label>
					<?= $rs->data_trans2; ?>
					<br />
				
					<label>Funcion�rio:</label>
					<?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
					<br />
					
					<label>Observa��es:</label>
					<?
					if ($rs->observacoes!="")
						echo $rs->observacoes;
					else
						echo "<span class=\"vermelho\">N�o informado!</span>";
					?>
					<br />
				</div>
				<?= $rs->id_mov; ?></td>
			<td align="center"><?= $rs->data_trans2; ?></td>
			<td align="center"><?= pega_tipo_transacao($rs->tipo_trans); ?></td>
			<td align="right" class="<?= $cor; ?>"><?= $sinal . number_format($rs->qtde, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres); ?></td>
			<td align="right" class="azul"><?= number_format($saldo, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres); ?></td>
			<td align="center">
			<?
			if ($rs->tipo_trans=="e")
				$destino= pega_origem_entrada($rs->subtipo_trans);
			else
				$destino= pega_origem_saida($rs->subtipo_trans);
			
			echo $destino;
			?>
			</td>
			<td><?= pega_nome_pelo_id_usuario($rs->id_usuario); ?></td>
		</tr>
		<?
			}
			$i++;
		}
		?>
	</table>
	
	<?
	}
	?>
	<br /><br /><br /><br /><br />
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>