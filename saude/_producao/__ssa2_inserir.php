<? if (@pode("p", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<?
if ($_GET["mes"]=="") $mes= date("m");
else $mes= $_GET["mes"];
	
if ($_GET["ano"]=="") $ano= date("Y");
else $ano= $_GET["ano"];

$dia= date("d");
if (($dia>25) && (date("m")==$mes) && (date("Y")==$ano) && ($_SESSION["id_posto_sessao"]!="")) {
	$mes= date("m", mktime(0, 0, 0, $mes+1, $dia, $ano));
	$ano= date("Y", mktime(0, 0, 0, $mes+1, $dia, $ano));
}
else $dia=1;

if ($_SESSION["id_cidade_sessao"]=="") $id_posto= $_SESSION["id_posto_sessao"];
else $id_posto= $_GET["id_posto"];

$pode_produzir= pega_status_producao_mes(pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]), $mes, $ano);
?>
<h2 class="titulos">Produção - SSA2 - <?= $mes ."/". $ano; ?></h2>

<?
if (!$pode_produzir)
	echo "<p class=\"vermelho\">O preenchimento dos dados da produção deste mês já foi finalizada pelo responsável pela digitação, entre em contato para caso necessite alterar algo.</p>";
?>

<form action="<?= AJAX_FORM; ?>formSSA2" method="post" id="formSSA2" name="formSSA2" onsubmit="return ajaxForm('conteudo', 'formSSA2');">
	<input type="hidden" name="mes" value="<?= $mes; ?>" class="escondido" />
	<input type="hidden" name="ano" value="<?= $ano; ?>" class="escondido" />
	
	<?
	$result_conta_ssa2= mysql_query("select * from ssa2_linhas order by id_linha");
	$total_linhas= mysql_num_rows($result_conta_ssa2);
	
	$result_cat= mysql_query("select * from ssa2_categorias");
	
	$tabindex= 0;
	$cont_x= 0;
	$cont_y= 1;
	
	while ($rs_cat= mysql_fetch_object($result_cat)) {
	?>
	<fieldset>
		<legend><?= $rs_cat->categoria; ?></legend>
		<table cellspacing="0">
			<tr>
				<th width="20%">&nbsp;</th>
				<?
				$result_mic= mysql_query("select id_microarea, microarea from microareas
											where id_posto= '". $id_posto ."'
											order by microarea asc ");
				
				$num= 1;
				while ($rs_mic= mysql_fetch_object($result_mic)) {
					$id_microarea[$num]= $rs_mic->id_microarea;
					$num++;
				?>
				<th><?= $rs_mic->microarea; ?></th>
				<? } ?>
				<th>Total</th>
			</tr>
			<?
			$result_ssa2= mysql_query("select * from ssa2_linhas
										where id_categoria= '". $rs_cat->id_categoria ."'
										order by id_linha");
			
			while ($rs_ssa2= mysql_fetch_object($result_ssa2)) {
			?>
			<tr>
				<td>
					<?
					echo $rs_ssa2->id_linha .". ";
					if ($rs_ssa2->negrito==1)
						echo "<strong>". $rs_ssa2->linha ."</strong>";
					else
						echo $rs_ssa2->linha;
					?>
				</td>
				<?
				for ($i=0; $i<$num-1; $i++) {
					$j= $i+1;
					/* Y */
					$cont_x++;
					$tabindex= ((($i*$total_linhas)+1)+$cont_y)-1;
					$id= "dado_". $cont_x ."_". $cont_y;
					
					$result_dado= mysql_query("select dado from ssa2_dados
												where mes = '$mes'
												and   ano = '$ano'
												and   id_linha= '". $rs_ssa2->id_linha ."'
												and   id_microarea= '". $id_microarea[$j] ."'
												");
					if (mysql_num_rows($result_dado)==0)
						$dado= "0";
					else {
						$rs_dado= mysql_fetch_object($result_dado);
						$dado= $rs_dado->dado;
					}
					$soma += $dado;
					
					if (($rs_ssa2->id_linha>34) && ($rs_ssa2->id_linha<40)) {
						$dado_total_hosp[$j] += $dado;
					}
					
					if (($rs_ssa2->id_linha>40) && ($rs_ssa2->id_linha<58)) {
						$dado_total_obito[$j] += $dado;
					}
				?>
				<td class="alinhar_centro">
					<input value="<?= $id_microarea[$j]; ?>" name="id_microarea[]" type="hidden" class="escondido" />
					<input value="<?= $rs_ssa2->id_linha; ?>" name="id_linha[]" type="hidden" class="escondido" />
					<input <? if (($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir)) echo "disabled=\"disabled\""; ?> value="<?= $dado; ?>" name="dado[]"
                    	onkeypress="return ajeitaTecla(event);" onfocus="this.select();" id="<?= $id; ?>" tabindex="<?= $tabindex; ?>" class="tamanho_ajuste alinhar_centro" onblur="somaDadosSSA2(<?= $cont_y; ?>, <?= $num-1; ?>, <?=$j;?>, this);" />
				</td>
				<?
				}
				?>
				<td>
					<input class="tamanho_ajuste alinhar_centro" id="soma_<?= $cont_y; ?>" value="<?= $soma; ?>" disabled="disabled" />
				</td>
				</tr>
                <? if ($rs_ssa2->id_linha==39) { ?>
                <tr>
				<td>
					<strong>Total</strong>
				</td>
				<?
				$total_hosp=0;
				for ($i=0; $i<$num-1; $i++) {
					$j= $i+1;
					$total_hosp += $dado_total_hosp[$j];
				?>
				<td class="alinhar_centro">
					<input disabled="disabled" value="<?= $dado_total_hosp[$j]; ?>" name="total_hosp_<?= $j; ?>" id="total_hosp_<?= $j; ?>" class="tamanho_ajuste alinhar_centro" />
				</td>
				<?
				}
				?>
				<td>
					<input disabled="disabled" value="<?= $total_hosp; ?>" name="total_hosp" id="total_hosp" class="tamanho_ajuste alinhar_centro" />
				</td>
				</tr>
				<? } ?>
                <? if ($rs_ssa2->id_linha==57) { ?>
                <tr>
				<td>
					<strong>Total de óbitos</strong>
				</td>
				<?
				$total_obito=0;
				for ($i=0; $i<$num-1; $i++) {
					$j= $i+1;
					$total_obito += $dado_total_obito[$j];
				?>
				<td class="alinhar_centro">
					<input disabled="disabled" value="<?= $dado_total_obito[$j]; ?>" name="total_obito_<?= $j; ?>" id="total_obito_<?= $j; ?>" class="tamanho_ajuste alinhar_centro" />
				</td>
				<?
				}
				?>
				<td>
					<input disabled="disabled" value="<?= $total_obito; ?>" name="total_obito" id="total_obito" class="tamanho_ajuste alinhar_centro" />
				</td>
				</tr>
				<? } ?>
			<?
				$soma= 0;
				$tabindex++;
				$cont_y++;
				$cont_x= 0;
			}
			?>
		</table>
		</fieldset>
	<br />
	<? } ?>
	
	<? if (!(($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir))) { ?>
	<center>
		<button type="submit">Salvar</button>
	</center>
	<? } ?>
	
	<br /><br />
</form>
<br />
<br />
<br />

<? /* <script language="javascript" type="text/javascript">daFoco('pesquisa');</script> */ ?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>