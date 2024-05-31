<? if (@pode("x", $_SESSION["permissao"])) { ?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/relatoriom_stats');">&lt;&lt; voltar</a>

<h2 class="titulos">Consumo mensal de <?= pega_material($id_material)." (". pega_tipo_material(pega_tipo_material_db($id_material)) .")"; ?></h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th>Período</th>
            <th>Quantidade</th>
        </tr>
	<?
    for ($i=-1; $i<6; $i++) {
        $mes= date("m", mktime(0, 0, 0, date("m")-$i, 0, date("Y")));
        $ano= date("Y", mktime(0, 0, 0, date("m")-$i, 0, date("Y")));
    
		$result= mysql_query("select sum(qtde) as total from almoxarifadom_mov
								where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   tipo_trans <> 'e'
								and   DATE_FORMAT(data_trans, '%m') = '". $mes ."'
								and   DATE_FORMAT(data_trans, '%Y') = '". $ano ."'
								and   id_material = '". $id_material ."'
								");
		$rs= mysql_fetch_object($result);
		?>
        <tr class="corzinha">
        	<td align="center"><?= $mes ."/". $ano; ?></td>
            <td align="center"><?= number_format($rs->total, 0, ',', '.'); ?></td>
        </tr>
        <? } ?>
        </table>
        
        <br /><br />
        
        <h2 class="titulos2">Gráfico demonstrativo</h2>
        
        <center>
            <img src="index2.php?pagina=_almox/consumom_mensal_material_grafico&id_material=<?= $id_material; ?>" alt="Consumo mensal" />
        </center>

</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>