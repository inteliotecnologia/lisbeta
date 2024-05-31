<?
if (@pode("z", $_SESSION["permissao"])) {
?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/relatorio_familias');">&lt;&lt; voltar</a>

<h2 class="titulos">Relatório de arredacação (<?= $_POST["periodo"]; ?>)</h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th width="22%" align="left">Coordenação</th>
            <? for ($i=1; $i<14; $i++) { ?>
            <th align="right" width="6%"><?= substr(traduz_mes($i), 0, 3); ?></th>
            <? } ?>
    	</tr>
		<?
		$sql= "select * from microareas_coord
							where id_posto = '". $_SESSION["id_posto_sessao"] ."'
							order by coordenacao asc
							";
		
		$result= mysql_query($sql) or die(mysql_error());
	
		while($rs= mysql_fetch_object($result)) {
		?>
        <tr class="corzinha">
        	<td><?= $rs->coordenacao ." - ". pega_nomes($rs->id_pessoas); ?></td>
            <? for ($i=1; $i<14; $i++) { ?>
            <td align="right">
            <?
				$result_arr= mysql_query("select sum(valor) as total from arrecadacoes, familias, microareas
										 	where arrecadacoes.id_familia = familias.id_familia
											and   familias.id_microarea = microareas.id_microarea
											and   microareas.id_coord = '". $rs->id_coordenacao ."'
											and   mes = '$i'
											and   ano = '". $_POST["periodo"] ."'
											") or die(mysql_error());
				$rs_arr= mysql_fetch_object($result_arr);
				
				$total_mes[$i] += $rs_arr->total;
				
				echo fnum($rs_arr->total);
			}
			?>
            </td>
        </tr>
        <? } ?>
        
        <tr class="corzinha">
        	<th align="right">Total:</th>
            <?
            for ($i=1; $i<14; $i++) {
				$total_geral += $total_mes[$i];
			?>
            <th align="right">
            <?= fnum($total_mes[$i]); ?>
            </th>
            <? } ?>
        </tr>
        
        <tr class="fonte_maior corzinha">
        	<th align="right">Total do ano:</th>
            <th align="right" colspan="13">
            R$ <?= fnum($total_geral); ?>
            </th>
        </tr>
        
    </table>
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>