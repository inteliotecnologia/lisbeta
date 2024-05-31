<? if (@pode("f", $_SESSION["permissao"])) { ?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/relatorio_stats');">&lt;&lt; voltar</a>

<h2 class="titulos">Consumo mensal da farmácia - mês <?= $periodo; ?></h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th align="left">Remédio</th>
            <th>Quantidade (un)</th>
        </tr>
		<?
		$periodo2= explode("/", $periodo);
		$mes= $periodo2[0];
		$ano= $periodo2[1];

		$result= mysql_query("select remedios.*, sum(almoxarifado_mov.qtde) as total from almoxarifado_mov, remedios
								where almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   almoxarifado_mov.tipo_trans <> 'e'
								and   almoxarifado_mov.id_remedio = remedios.id_remedio
								and   DATE_FORMAT(almoxarifado_mov.data_trans, '%m') = '". $mes ."'
								and   DATE_FORMAT(almoxarifado_mov.data_trans, '%Y') = '". $ano ."'
								group by almoxarifado_mov.id_remedio
								order by remedios.remedio asc
								") or die(mysql_error());
		while($rs= mysql_fetch_object($result)) {
		?>
        <tr class="corzinha">
        	<td><?= $rs->remedio; ?></td>
            <td align="center"><?= number_format($rs->total, 0, ',', '.'); ?></td>
        </tr>
        <? } ?>
    </table>
    
    <br /><br />
    
    <h2 class="titulos2">Gráfico demonstrativo (12 mais)</h2>
    
    <center>
    	<img src="index2.php?pagina=_farmacia/consumo_mensal_grafico&periodo=<?= $periodo; ?>" alt="Consumo mensal geral" />
    </center>
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>