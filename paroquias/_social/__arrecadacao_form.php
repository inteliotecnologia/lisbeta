<?
if (@pode_algum("zl", $_SESSION["permissao"]) ) {
	if ($_GET["ano_inicial"]!="") $ano_inicial= $_GET["ano_inicial"];
	else $ano_inicial= date("Y");
	
	if ($_GET["ano_final"]!="") $ano_final= $_GET["ano_final"];
	else $ano_final= date("Y")+3;
	
	if ($_GET["id_familia"]!="") $id_familia= $_GET["id_familia"];
	
	$dif_anos= $ano_final-$ano_inicial;
?>
<form action="<?= AJAX_FORM; ?>formArrecadacao" method="post" id="formArrecadacao" name="formArrecadacao" onsubmit="return ajaxForm('conteudo', 'formArrecadacao');">
	<input type="hidden" name="id_familia" value="<?= $id_familia; ?>" class="escondido" />
    
    <table cellspacing="0">
        <tr>
            <th width="25%">&nbsp;</th>
            <?
            for ($i=$ano_inicial; $i<=$ano_final; $i++) {
            ?>
            <th width="<?= floor(75/$dif_anos);?>%" align="left"><?= $i; ?></th>
            <? } ?>
        </tr>
        <?
		$tabindex= 0;
		$cont_x= 0;
		$cont_y= 1;
	
		for ($j=1; $j<14; $j++) {
		?>
        <tr>
            <td><?= traduz_mes($j); ?></td>
            <?
            for ($i=$ano_inicial; $i<=$ano_final; $i++) {
				/* Y */
                $cont_x++;
				$tabindex= ((($i*14)+1)+$cont_y)-1;
				
                $id= "dado_". $i ."_". $j;
				
				$result_dado= mysql_query("select valor from arrecadacoes
                                            where mes = '$j'
                                            and   ano = '$i'
                                            and   id_familia= '". $id_familia ."'
                                            and   id_cidade= '". $id_cidade_emula ."'
                                            ") or die(mysql_error());
				if (mysql_num_rows($result_dado)==0)
                    $dado= "0";
                else {
                    $rs_dado= mysql_fetch_object($result_dado);
                    $dado= $rs_dado->valor;
                }
				
				$soma[$i]+=$dado;
				
            ?>
            <td class="alinhar_centro">
                <input value="<?= $j; ?>" name="mes[]" type="hidden" class="escondido" />
                <input value="<?= $i; ?>" name="ano[]" type="hidden" class="escondido" />
                
                <input value="<?= number_format($dado, 2, ',', '.'); ?>" name="valor[]" onkeydown="formataValor(this,event);"
                    id="<?= $id; ?>" tabindex="<?= $tabindex; ?>" class="tamanho80 alinhar_direita fonte_maior" onblur="somaValoresAno(<?= $i; ?>, this);" />
            </td>
            <?
			}
			?>
        </tr>
        <?
			$tabindex++;
			$cont_y++;
			$cont_x= 0;
		}
		?>
        <tr>
        	<td>&nbsp;</td>
            <?
            for ($i=$ano_inicial; $i<=$ano_final; $i++) {
            ?>
            <td><input value="<?= number_format($soma[$i], 2, ',', '.'); ?>" id="soma_<?=$i;?>" class="tamanho80 alinhar_direita fonte_maior" disabled="disabled" /></td>
            <? } ?>
        </tr>
    </table>
    
    <br /><br />
    
    <center>
		<button type="submit">Salvar</button>
	</center>
    
    <br /><br />
    
</form>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>