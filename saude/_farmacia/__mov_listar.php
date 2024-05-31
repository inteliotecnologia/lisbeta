<? if (@pode("f", $_SESSION["permissao"])) { ?>
<?
if (!isset($tipo_trans))
	$tipo_trans= "todos";

if ($_SESSION["id_cidade_sessao"]!="") {
	if ($local=="")
		$str= "almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ";
	else
		$str= "almoxarifado_mov.id_posto = '". $local ."' ";
	
	if ($local_d=="")
		$str2= " ";
	else {
		if ($local!="")
			$str2= "and almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ";
		
		$str2 .= "    
				and almoxarifado_mov.tipo_trans = 'm'
				and almoxarifado_mov.id_receptor= '". $local_d ."' ";
	}
	
	
	$sql= "select almoxarifado_mov.*, DATE_FORMAT(almoxarifado_mov.data_trans, '%d/%m/%Y %H:%i:%s') as data_trans,
								remedios.classificacao_remedio, remedios.remedio from almoxarifado_mov, remedios
								where almoxarifado_mov.id_remedio = remedios.id_remedio
								and   ". $str ." ". $str2 ."
								";
}
else {
	$sql= "select almoxarifado_mov.*, DATE_FORMAT(almoxarifado_mov.data_trans, '%d/%m/%Y %H:%i:%s') as data_trans,
								remedios.classificacao_remedio, remedios.remedio from almoxarifado_mov, remedios
								where almoxarifado_mov.id_remedio = remedios.id_remedio
								and   (almoxarifado_mov.id_posto = '". $_SESSION["id_posto_sessao"] ."'
										or almoxarifado_mov.tipo_trans = 'm' and almoxarifado_mov.id_receptor = '". $_SESSION["id_posto_sessao"] ."'  )
								";
}

if ($id_remedio!="") {
	$sql .= " and remedios.id_remedio = '$id_remedio' ";
}

if ($tipo_trans!="todos") {
	$sql .= " and almoxarifado_mov.tipo_trans = '$tipo_trans' ";
}

if ($observacoes!="") {
	$sql .= " and almoxarifado_mov.observacoes like '%". $observacoes ."%' ";
}

if (($inicio!="") && ($fim!="") ) {
	$data_inicio= desformata_data($inicio);
	$data_fim= desformata_data($fim);
	
	$data_fim= aumenta_dia($data_fim[0] ."-". $data_fim[1] ."-". $data_fim[2]);
	
	$sql .= " and data_trans between '". $data_inicio[2] ."-". $data_inicio[1] ."-". $data_inicio[0] ."'
				and '". $data_fim[2] ."-". $data_fim[1] ."-". $data_fim[0] ."' ";
}

$sql .= " order by almoxarifado_mov.id_mov desc ";
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

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/estoque_listar');">&lt;&lt; voltar para estoque</a>

<h2 class="titulos">Operações no almoxarifado</h2>

<div class="parte_total">

	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s).</p>
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
			<th width="25%" align="left">Remédio</th>
			<th width="3%" align="left">&nbsp;</th>
			<th width="12%" align="right">Qtde (apresentação)</th>
			<th width="12%"><?= $th_str; ?></th>
			<th width="8%">Data</th>
			<th width="25%" align="left">&nbsp;</th>
		</tr>
		<?
		$total_geral= 0;
		
		while ($rs= mysql_fetch_object($result)) {
			
			$total_geral += $rs->qtde;
			
			if ($rs->classificacao_remedio=="c")
				$antes= "<img src='images/preto.gif' alt='' /> ";
			else
				$antes= "";
		?>
		<tr class="corzinha" onmouseover="abreDivSo('mov_<?= $rs->id_mov; ?>');" onmouseout="fechaDiv('mov_<?= $rs->id_mov; ?>');">
			<td align="center">
				<div id="mov_<?= $rs->id_mov; ?>" class="mov_ver">
					<h2 class="titulos">Visualização da movimentação</h2>
					
					<label>Cód.:</label>
					<?= $rs->id_mov; ?>
					<br />
					
					<label>Tipo:</label>
					<?= pega_tipo_transacao($rs->tipo_trans); ?>
					<br />
					
					<label>Remédio:</label>
					<?= $antes . $rs->remedio; ?>
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
					if ($rs->id_fornecedor!="")
						echo pega_fornecedor($rs->id_fornecedor);
					else
						echo "Não informado.";
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
					
					if ($rs->id_posto!="")
						$origem= pega_posto($rs->id_posto);
					if ($rs->id_cidade!="")
						$origem= "FARMÁCIA CENTRAL - ". pega_cidade($rs->id_cidade);
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
				<?= $rs->id_mov; ?>			</td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/extrato&amp;id_remedio=<?= $rs->id_remedio; ?>&amp;origem=_farmacia/estoque_listar');"><?= $antes . $rs->remedio; ?></a></td>
			<td align="center">
            <? if (($rs->tipo_trans=="s") && (($rs->subtipo_trans=="b") || ($rs->subtipo_trans=="p") || ($rs->subtipo_trans=="r")) && ($rs->situacao_mov!=2)) { ?>
            <a href="javascript:ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/estorno&amp;id_mov=<?= $rs->id_mov; ?>&amp;origem=_farmacia/mov_listar|local=<?= $local; ?>|local_d=<?= $local_d; ?>|id_remedio=<?= $id_remedio; ?>|tipo_trans=<?= $tipo_trans; ?>|inicio=<?= $inicio; ?>|fim=<?= $fim; ?>|tudo=<?= $tudo; ?>|num_pagina=<?= $num_pagina; ?>');" onclick="return confirm('Tem certeza que deseja estornar esta entrega?\n\nOs dados do receptor serão perdidos e o estoque irá aumentar!');"><img border="0" src="images/ico_troca.gif" alt="" /></a>
            <? } else echo "&nbsp;"; ?>
            </td>
			<td align="right"><?= number_format($rs->qtde, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres); ?></td>
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
			
			?>			</td>
			<td align="center"><?= $rs->data_trans; ?></td>
			<td>
			<?
			if ($rs->id_receptor=="")
				echo "-";
			else
				echo pega_nome($rs->id_receptor);
			?>			</td>
		</tr>
		<? } ?>
	</table>
<br /><br />
    <?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_farmacia/mov_listar&amp;local=". $local ."&amp;local_d=". $local_d ."&amp;id_remedio=". $id_remedio ."&amp;tipo_trans=". $tipo_trans ."&amp;inicio=". $inicio ."&amp;fim=". $fim ."&amp;tudo=". $tudo ."&amp;num_pagina=";
			
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
	<?= number_format($total_geral, 0, ',', '.'); ?> unid(s)
	<br />
	
	<br /><br /><br /><br /><br /><br />
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>