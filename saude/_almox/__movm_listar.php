<? if (@pode("x", $_SESSION["permissao"])) { ?>
<?
if (!isset($tipo_trans))
	$tipo_trans= "todos";

if ($_SESSION["id_cidade_sessao"]!="") {
	if ($local=="")
		$str= "almoxarifadom_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ";
	else
		$str= "almoxarifadom_mov.id_posto = '". $local ."' ";
	
	if ($local_d=="")
		$str2= " ";
	else {
		if ($local!="")
			$str2= "and almoxarifadom_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ";
		
		$str2 .= "    
				and almoxarifadom_mov.tipo_trans = 'm'
				and almoxarifadom_mov.id_receptor= '". $local_d ."' ";
	}
	
	$sql= "select almoxarifadom_mov.*, DATE_FORMAT(almoxarifadom_mov.data_trans, '%d/%m/%Y') as data_trans,
								materiais.* from almoxarifadom_mov, materiais
								where almoxarifadom_mov.id_material = materiais.id_material
								and   ". $str ." ". $str2 ."
								";
}
else {
	$sql= "select almoxarifadom_mov.*, DATE_FORMAT(almoxarifadom_mov.data_trans, '%d/%m/%Y') as data_trans,
								materiais.* from almoxarifadom_mov, materiais
								where almoxarifadom_mov.id_material = materiais.id_material
								and   (almoxarifadom_mov.id_posto = '". $_SESSION["id_posto_sessao"] ."'
										or almoxarifadom_mov.tipo_trans = 'm' and almoxarifadom_mov.id_receptor = '". $_SESSION["id_posto_sessao"] ."'  )
								";
}
							
if ($id_material!="") {
	$sql .= " and materiais.id_material = '$id_material' ";
}

if ($tipo_trans!="todos") {
	$sql .= " and almoxarifadom_mov.tipo_trans = '$tipo_trans' ";
}

if ($observacoes!="") {
	$sql .= " and almoxarifadom_mov.observacoes like '%". $observacoes ."%' ";
}


if ( ($inicio!="") && ($fim!="") ) {
	$data_inicio= desformata_data($inicio);
	$data_fim= desformata_data($fim);
	
	$data_fim= aumenta_dia($data_fim[0] ."-". $data_fim[1] ."-". $data_fim[2]);
	
	$sql .= " and data_trans between '". $data_inicio[2] ."-". $data_inicio[1] ."-". $data_inicio[0] ."'
				and '". $data_fim[2] ."-". $data_fim[1] ."-". $data_fim[0] ."' ";
}

$sql .= " order by almoxarifadom_mov.id_mov desc ";
//echo $sql;
$result= mysql_query($sql) or die(mysql_error());

$total_antes= mysql_num_rows($result);
	
if ($tudo!=1) {
	$num= 30;
	$total_linhas = mysql_num_rows($result);
	$num_paginas = ceil($total_linhas/$num);
	if (!isset($num_pagina))
		$num_pagina = 0;
	$comeco = $num_pagina*$num;
	
	$result= mysql_query($sql ." limit $comeco, $num") or die(mysql_error());
}
?>

<div id="tela_mensagens">
<? include("__tratamento_msgs.php"); ?>
</div>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/estoquem_listar');">&lt;&lt; voltar para estoque</a>

<h2 class="titulos">Operações no almoxarifado</h2>

<div class="parte_total">

	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<?
	if ( (!isset($tipo_trans)) || ($tipo_trans=="todos") ) {
		$th_str= "Tipo de transação";
	}
	
	switch ($tipo_trans) {
		case 'm': $th_str= "Posto";
					break;
		case 'd': $th_str= "Para";
					break;
		case 's': $th_str= "Tipo saída";
					break;
	}
	?>
	
	<table cellspacing="0">
		<tr>
			<th width="5%">Cód.</th>
			<th width="30%" align="left">Material</th>
			<th width="15%" align="right">Qtde</th>
			<th width="15%"><?= $th_str; ?></th>
			<th width="15%">Data</th>
			<th width="20%" align="left">Informações adicionais</th>
		</tr>
		<?
		$total_geral= 0;
		while ($rs= mysql_fetch_object($result)) {
			$total_geral += $rs->qtde;
		?>
		<tr class="corzinha" onmouseover="abreDivSo('mov_<?= $rs->id_mov; ?>');" onmouseout="fechaDiv('mov_<?= $rs->id_mov; ?>');" <? /*onclick="ajaxLink('conteudo', 'movVer&amp;id_mov=<?= $rs->id_mov; ?>');" */ ?>>
			<td align="center">
				<div id="mov_<?= $rs->id_mov; ?>" class="mov_ver">
					<h2 class="titulos">Visualização da movimentação</h2>
					
					<label>Cód.:</label>
					<?= $rs->id_mov; ?>
					<br />
					
					<label>Tipo:</label>
					<?= pega_tipo_transacao($rs->tipo_trans); ?>
					<br />
					
					<label>Material:</label>
					<?= $rs->material; ?>
					<br />
					
					<label>Quantidade:</label>
					<?= number_format($rs->qtde, 0, ',', '.'); ?>
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
                </div>
				<?= $rs->id_mov; ?>
			</td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/extratom&amp;id_material=<?= $rs->id_material; ?>&amp;origem=_almox/estoquem_listar');"><?= $rs->material; ?></a></td>
			<td align="right"><?= number_format($rs->qtde, 0, ',', '.'); ?></td>
			<td align="center">
			<?
			switch ($rs->tipo_trans) {
				case 'm': $td_str= pega_posto($rs->id_receptor);
							break;
				case 'd': $td_str= pega_nome($rs->id_receptor);
							break;
				case 's': $td_str= pega_origem_saida($rs->subtipo_trans);
							break;
			}
			if ( (!isset($tipo_trans)) || ($tipo_trans=="todos") )
				echo pega_tipo_transacao($rs->tipo_trans);
			else
				echo $td_str;
			$td_str= "";
			
			?>
			</td>
			<td align="center"><?= $rs->data_trans; ?></td>
			<td>
			<?
			if ($rs->id_receptor=="")
				echo "-";
			else
				echo pega_nome($rs->id_receptor);
			?>
			</td>
		</tr>
		<? } ?>
	</table>
	<br /><br />
    <?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_almox/movm_listar&amp;local=". $local ."&amp;local_d=". $local_d ."&amp;id_material=". $id_material ."&amp;tipo_trans=". $tipo_trans ."&amp;inicio=". $inicio ."&amp;fim=". $fim ."&amp;tudo=". $tudo ."&amp;num_pagina=";
			
			$texto_paginacao .= "<div id=\"paginacao\">
					<ul>";
			if ($num_pagina > 0) {
				$menos = $num_pagina - 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $menos ."')\">&laquo; Anterior</a></li>";
			}
	
			for ($i=0; $i<$num_paginas; $i++) {
				$link = $i + 1;
				if ($num_pagina==$i)
					$texto_paginacao .= "<li class=\"paginacao_atual\">". $link ."</li>";
				else
					$texto_paginacao .=  "<li><a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $i ."')\">". $link ."</a></li>";
			}
		
			if ($num_pagina < ($num_paginas - 1)) {
				$mais = $num_pagina + 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $mais ."')\">Pr&oacute;xima &raquo;</a></li>";
			}
			$texto_paginacao .=  "</ul>
				</div>";
	
			echo $texto_paginacao;
		}
	}
	?>
    <br /><br />
    
    <label>Total:</label>
	<?= number_format($total_geral, 0, ',', '.'); ?>
	<br />
    
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>