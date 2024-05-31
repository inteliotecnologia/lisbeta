<? if (@pode("f", $_SESSION["permissao"])) { ?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/relatoriom_stats');">&lt;&lt; voltar</a>

<h2 class="titulos">Consumo mensal do almoxarifado - mês <?= $periodo; ?></h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th align="left">Material</th>
            <th>Quantidade</th>
        </tr>
		<?
		$periodo2= explode("/", $periodo);
		$mes= $periodo2[0];
		$ano= $periodo2[1];

		$result= mysql_query("select materiais.*, sum(almoxarifadom_mov.qtde) as total from almoxarifadom_mov, materiais
								where almoxarifadom_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   almoxarifadom_mov.tipo_trans <> 'e'
								and   almoxarifadom_mov.id_material = materiais.id_material
								and   DATE_FORMAT(almoxarifadom_mov.data_trans, '%m') = '". $mes ."'
								and   DATE_FORMAT(almoxarifadom_mov.data_trans, '%Y') = '". $ano ."'
								group by almoxarifadom_mov.id_material
								order by materiais.material asc
								") or die(mysql_error());
		while($rs= mysql_fetch_object($result)) {
		?>
        <tr class="corzinha">
        	<td><?= $rs->material." (". pega_tipo_material($rs->tipo_material) .")"; ?></td>
            <td align="center"><?= number_format($rs->total, 0, ',', '.'); ?></td>
        </tr>
        <? } ?>
    </table>
    
    <br /><br />
    
    <h2 class="titulos2">Gráfico demonstrativo (12 mais)</h2>
    
    <center>
    	<img src="index2.php?pagina=_almox/consumom_mensal_grafico&periodo=<?= $periodo; ?>" alt="Consumo mensal geral" />
    </center>
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>