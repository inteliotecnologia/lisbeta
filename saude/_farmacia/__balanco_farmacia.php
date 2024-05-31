<?
if (@pode("f", $_SESSION["permissao"])) {
	switch($periodicidade) {
		case 1: $titulo= "1º trimestre de ";
				break;
		case 2: $titulo= "2º trimestre de ";
				break;
		case 3: $titulo= "3º trimestre de ";
				break;
		case 4: $titulo= "4º trimestre de ";
				break;
		case "a": $titulo= "anual de ";
					break;
	}
	$titulo .= $ano;
	
	$sql= "select remedios.* from almoxarifado_mov, remedios
							where almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							and   almoxarifado_mov.id_remedio = remedios.id_remedio
							". $astr_periodo ."
							group by almoxarifado_mov.id_remedio
							order by remedios.remedio asc
							";
		
	$result= mysql_query($sql) or die(mysql_error());

	$num= 500;
	$total_linhas = mysql_num_rows($result);
	$num_paginas = ceil($total_linhas/$num);
	if (!isset($num_pagina))
		$num_pagina = 0;
	$comeco = $num_pagina*$num;
	
	$result= mysql_query($sql ." limit $comeco, $num") or die(mysql_error());


?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/relatorio_stats');">&lt;&lt; voltar</a>

<h2 class="titulos">Balan&ccedil;o completo da farmácia (<?= $titulo; ?>)</h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th width="40%" align="left">Remédio</th>
            <th width="15%">Estoque inicial</th>
            <th width="15%">Entradas</th>
            <th width="15%">Sa&iacute;das</th>
            <th width="15%">Estoque final</th>
    	</tr>
		<?
		while($rs= mysql_fetch_object($result)) {
			$estoque_inicial= pega_estoque_inicial($ano, $periodicidade, $_SESSION["id_cidade_sessao"], $rs->id_remedio);
			$entradas= pega_entradas($ano, $periodicidade, $_SESSION["id_cidade_sessao"], $rs->id_remedio);
			$saidas= pega_saidas($ano, $periodicidade, $_SESSION["id_cidade_sessao"], $rs->id_remedio);
			
			if ($rs->classificacao_remedio=="c")
				$antes= "<img src='images/preto.gif' alt='' /> ";
			else
				$antes= "";
		?>
        <tr class="corzinha">
        	<td><?= $antes . $rs->remedio. " (". pega_tipo_remedio($rs->tipo_remedio) .")";; ?></td>
            <td align="center"><?= number_format($estoque_inicial, 0, ',', '.'); ?></td>
            <td align="center"><?= number_format($entradas, 0, ',', '.'); ?></td>
            <td align="center"><?= number_format($saidas, 0, ',', '.'); ?></td>
            <td align="center"><?= number_format(($estoque_inicial+$entradas)-$saidas, 0, ',', '.'); ?></td>
        </tr>
        <? } ?>
    </table>
    
    <?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_farmacia/balanco_farmacia&amp;ano=". $ano ."&amp;periodicidade=". $periodicidade ."&amp;num_pagina=";
			
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

    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>