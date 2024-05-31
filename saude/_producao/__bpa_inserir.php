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

if ($_SESSION["id_cidade_sessao"]!="") $id_posto= $_GET["id_posto"];
else $id_posto= $_SESSION["id_posto_sessao"];

//-----------------------------------------------------------------------
// ver se já existe algum dado inserido no relatório do mês, caso tiver ele pega esses valores no campo, senão tiver, ele coloca os valores gerados.
$result_dados= mysql_query("select dado from bpa_dados
							where mes = '$mes'
							and   ano = '$ano'
							and   id_posto= '". $id_posto ."'
							");
if (mysql_num_rows($result_dados)==0) $tem=0;
else $tem=1;

//---------------------------------------------------------------------

$pode_produzir= pega_status_producao_mes(pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]), $mes, $ano);
?>
<h2 class="titulos">Produção - BPA - <?= $mes ."/". $ano; ?></h2>

<?
if (!$pode_produzir)
	echo "<p class=\"vermelho\">O preenchimento dos dados da produção deste mês já foi finalizada pelo responsável pela digitação, entre em contato para caso necessite alterar algo.</p>";
	
if (!$tem)
	echo "<p class=\"vermelho\">Os valores que estão nos campos são valores gerados pelo sistema, confira-os e se necessário, altere-os.</p>";
?>

<form action="<?= AJAX_FORM; ?>formBPA" method="post" id="formBPA" name="formBPA" onsubmit="return ajaxForm('conteudo', 'formBPA');">
	<input type="hidden" name="mes" value="<?= $mes; ?>" class="escondido" />
	<input type="hidden" name="ano" value="<?= $ano; ?>" class="escondido" />
	
	<?
	$result_cat= mysql_query("select * from bpa_categorias");
	
	while ($rs_cat= mysql_fetch_object($result_cat)) {
	?>
	<fieldset>
		<legend><?= $rs_cat->categoria; ?></legend>
  <table cellspacing="0">
	        <tr>
	          <th width="2%" align="left">&nbsp;</th>
			    <th width="38%" align="left">Parâmetro</th>
			    <th width="15%">C&oacute;d. procedimento</th>
			    <th width="10%">CBO</th>
			    <th width="10%">Idade</th>
			    <th width="10%" align="left">Quantidade</th>
		        <th width="15%">Quantidade gerada</th>
	        </tr>
			<?
			$result_bpa= mysql_query("select * from bpa_linhas
										where id_categoria= '". $rs_cat->id_categoria ."'
										order by id_linha");
			
			while ($rs_bpa= mysql_fetch_object($result_bpa)) {
					$result_dado= mysql_query("select * from bpa_dados
													where mes = '$mes'
													and   ano = '$ano'
													and   id_linha= '". $rs_bpa->id_linha ."'
													and   id_posto= '". $id_posto ."'
													and   idade = '0'
													and   id_cbo = '0'
													") or die(mysql_error());
					
					// -------------- inicio do codigo para pegar os valores -------------------------------------------------------
					
					// 20/01/2008 -> 19/02/2008
					$mes_anterior= date("Y-m", mktime(0, 0, 0, $mes-1, $dia, $ano));
					$mes_atual= date("Y-m", mktime(0, 0, 0, $mes, $dia, $ano));
					$mes_proximo= date("Y-m", mktime(0, 0, 0, $mes+1, $dia, $ano));
					
				
					$sql_consultas_mais= "
											and   consultas.data_consulta >= '". $mes_anterior ."-20'
											and   consultas.data_consulta < '". $mes_atual ."-20'
											";
					$sql_procedimentos_mais= " 
											and   procedimentos.data_procedimento >= '". $mes_anterior ."-20'
											and   procedimentos.data_procedimento < '". $mes_atual ."-20'
											";
					
					
					$sql_consultas= "select count(id_consulta) as total from consultas
										where id_posto = '". $id_posto ."'
										". $sql_consultas_mais ."
										";
					
					$sql_consultas_od= "
										SELECT count(consultas_odonto_procedimentos.id_oprocedimento) as total
										from  consultas, consultas_odonto_procedimentos
										where consultas.id_consulta = consultas_odonto_procedimentos.id_consulta
										and   consultas.id_posto= '". $id_posto ."'
										". $sql_consultas_mais ."
										";
					
					$sql_procedimentos= "select sum(qtde) as total from procedimentos
										where id_posto = '". $id_posto ."'
										". $sql_procedimentos_mais ."
										";
					
					$sql_ssa2= "select sum(dado) as total from ssa2_dados, microareas
									where ssa2_dados.id_microarea = microareas.id_microarea
									and   microareas.id_posto = '". $id_posto ."'
									and   mes = '". $mes ."'
									and   ano = '". $ano ."'
									and   ssa2_dados.id_linha = ";
					
					switch ($rs_bpa->id_linha) {
						// procedimentos
						
						case 1: $sql= $sql_procedimentos ." and   id_procedimento= '3' "; break;
						case 2: $sql= $sql_procedimentos ." and   id_procedimento= '4' "; break;
						case 3: $sql= $sql_procedimentos ." and   id_procedimento= '7' "; break;
						case 4: $sql= $sql_procedimentos ." and   id_procedimento= '6' "; break;
						case 5: $sql= $sql_procedimentos ." and   id_procedimento= '5' "; break;
						case 6: $sql= $sql_procedimentos ." and   id_procedimento= '14' "; break;
						case 7: $sql= $sql_procedimentos ." and   id_procedimento= '12' "; break;
						case 8: $sql= $sql_procedimentos ." and   id_procedimento= '13' "; break;
						case 9: $sql= $sql_procedimentos ." and   id_procedimento= '15' "; break;
						case 10: $sql= $sql_procedimentos ." and   id_procedimento= '16' "; break;
						case 11: $sql= $sql_procedimentos ." and   id_procedimento= '17' "; break;
						case 12: $sql= $sql_procedimentos ." and   id_procedimento= '18' "; break;
						case 13: $sql= $sql_procedimentos ."  and   id_procedimento= '19' "; break;
						case 14: $sql= $sql_ssa2 ." '61' "; break;
						
						case 22: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '2' "; break;
						case 23: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '3' "; break;
						case 24: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '4' "; break;
						case 25: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '5' "; break;
						case 26: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '6' "; break;
						case 27: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '7' "; break;
						case 28: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '8' "; break;
						case 29: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '9' "; break;
						case 30: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '10' "; break;
						case 31: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '11' "; break;
						case 32: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '12' "; break;
						case 33: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '13' "; break;
						case 34: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '14' "; break;
						case 35: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '15' "; break;
						case 36: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '16' "; break;
						case 37: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '17' "; break;
						case 38: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '18' "; break;
						case 39: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '19' "; break;
						case 40: $sql= $sql_consultas_od ." and   consultas_odonto_procedimentos.id_oprocedimento = '20' "; break;
						
						default: $nada=true;
					}
					if (!$nada) {
						//echo "<code>".$sql ."</code>";
						
						$result= mysql_query($sql) or die(mysql_error());
						$rs= mysql_fetch_object($result);
						
						$valor_gerado= number_format($rs->total, 0, ',', '.');
					}
					else $valor_gerado= "<span class=\"vermelho\">ñ disp.</span>";
					
					$nada= false;
					//echo "<code>".$sql ."</code>";
					
					// -------------- fim do codigo para pegar os valores -------------------------------------------------------
					
					if ($tem==1) {
						if (mysql_num_rows($result_dado)==0)
							$dado= "0";
						else {
							$rs_dado= mysql_fetch_object($result_dado);
							$dado= $rs_dado->dado;
						}
					}
					else {
						$dado= ($valor_gerado+0);
					}
				?>
				<tr>
				  <td><em><?= $rs_bpa->id_linha; ?>.</em></td>
					<td>
						<?
						if ($rs_bpa->negrito==1)
							echo "<strong>". $rs_bpa->linha ."</strong>";
						else
							echo $rs_bpa->linha;
						?></td>
					<td class="alinhar_centro"><?= $rs_bpa->cod_procedimento; ?></td>
					<td class="alinhar_centro"><? if ($rs_bpa->multi!=1) echo "---"; ?></td>
					<td class="alinhar_centro"><? if ($rs_bpa->multi!=1) echo "---"; ?></td>
					<td class="alinhar_centro">
						<input value="<?= rand(0,9827634892374918723918732); ?>" name="random[]" type="hidden" class="escondido" />
                        <input value="<?= $rs_bpa->id_linha; ?>" name="id_linha[]" type="hidden" class="escondido" />
                        <input value="" name="id_cbo[]" type="hidden" class="escondido" />
                        <input value="" name="idade[]" type="hidden" class="escondido" />
						<input value="<?= $dado; ?>" <? if (($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir)) echo "disabled=\"disabled\""; ?> name="dado[]" onkeypress="return ajeitaTecla(event);" onfocus="this.select();" id="<?= $id; ?>" class="tamanho45 alinhar_centro <? if ($rs_bpa->multi==1) echo " escondido "; ?> " />
					</td>
					<td class="alinhar_centro"><? if ($rs_bpa->multi!=1) echo $valor_gerado; ?></td>
				</tr>
				<?
                if ($rs_bpa->multi==1) {
					// -------------- inicio do codigo para pegar os valores -------------------------------------------------------
					
					$sql_consultas= "select count(id_consulta) as total, idade_paciente, id_cbo from consultas
										where id_posto = '". $id_posto ."'
										". $sql_consultas_mais ."
										";
					
					$sql_consultas_od= "
										SELECT count(consultas_odonto_procedimentos.id_oprocedimento) as total, idade_paciente, id_cbo
										from  consultas, consultas_odonto_procedimentos
										where consultas.id_posto = '". $id_posto ."'
										and   consultas.id_consulta = consultas_odonto_procedimentos.id_consulta
										". $sql_consultas_mais ."
										";
					
					$sql_procedimentos= "select sum(qtde) as total, id_cbo from procedimentos
										where id_posto = '". $id_posto ."'
										". $sql_procedimentos_mais ."
										";
					
					$sql_ssa2= "select sum(dado) as total from ssa2_dados, microareas
									where ssa2_dados.id_microarea = microareas.id_microarea
									and   microareas.id_posto = '". $id_posto ."'
									and   mes = '". $mes ."'
									and   ano = '". $ano ."'
									and   ssa2_dados.id_linha = ";
					
					$nada_in= false;
					switch ($rs_bpa->id_linha) {
						
						case 15: $sql= $sql_consultas ."
									and   local_consulta = 'd'
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						case 16: $sql= $sql_procedimentos ."
									and   id_procedimento = '11'
									group by id_cbo
									order by id_cbo, id_procedimento
									"; break;
						case 17: $sql= $sql_consultas ."
									and   local_consulta = 'p'
									and   tipo_consulta_prof = 'm'
									and   (id_tipo_atendimento <> '1' and id_tipo_atendimento <> '2')
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						case 18: $sql= $sql_consultas ."
									and   local_consulta = 'p'
									and   tipo_consulta_prof = 'e'
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						case 19: $sql= $sql_consultas ."
									and   local_consulta = 'p'
									and   id_tipo_atendimento= '2'
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						case 20: $sql= $sql_consultas ."
									and   local_consulta = 'p'
									and   id_tipo_atendimento= '10'
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						
						case 21: $sql= $sql_consultas_od ."
									and   consultas_odonto_procedimentos.id_oprocedimento = '1'
									group by id_cbo, idade_paciente
									order by id_cbo, idade_paciente
									"; break;
						
						default: $nada_in=true;
					}
					if (!$nada_in) {
						//echo "<code>".$sql ."</code>";
						$result_in= mysql_query($sql) or die(mysql_error());
						while ($rs_in= mysql_fetch_object($result_in)) {
						
							$result2_dado= mysql_query("select * from bpa_dados
														where mes = '$mes'
														and   ano = '$ano'
														and   id_linha= '". $rs_bpa->id_linha ."'
														and   id_posto= '". $id_posto ."'
														and   idade = '". $rs_in->idade_paciente ."'
														and   id_cbo = '". $rs_in->id_cbo ."'
														") or die(mysql_error());
							
							$valor_gerado2= $rs_in->total;
							
					// -------------- fim do codigo para pegar os valores -------------------------------------------------------
					
					if ($tem==1) {
						if (mysql_num_rows($result2_dado)==0) $dado2= "0";
						else {
							$rs2_dado= mysql_fetch_object($result2_dado);
							$dado2= $rs2_dado->dado;
						}
					}
					else {
						$dado2= ($valor_gerado2+0);
					}
				?>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <?
                        if ($rs_bpa->negrito==1)
                            echo "<strong>". $rs_bpa->linha ."</strong>";
                        else
                            echo $rs_bpa->linha;
                        ?></td>
                    <td class="alinhar_centro"><?= $rs_bpa->cod_procedimento; ?></td>
                    <td class="alinhar_centro"><?= pega_cbo($rs_in->id_cbo); ?></td>
                    <td class="alinhar_centro"><?= $rs_in->idade_paciente; ?></td>
                    <td class="alinhar_centro">
                    	<input value="<?= rand(0,9827634892374918723918732); ?>" name="random[]" type="hidden" class="escondido" />
                        <input value="<?= $rs_bpa->id_linha; ?>" name="id_linha[]" type="hidden" class="escondido" />
                        <input value="<?= $rs_in->id_cbo; ?>" name="id_cbo[]" type="hidden" class="escondido" />
                        <input value="<?= $rs_in->idade_paciente; ?>" name="idade[]" type="hidden" class="escondido" />
                        <input <? if (($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir)) echo "disabled=\"disabled\""; ?> value="<?= $dado2; ?>" name="dado[]" onkeypress="return ajeitaTecla(event);" onfocus="this.select();" id="<?= $id; ?>" class="tamanho45 alinhar_centro" />
                        </td>
                    <td class="alinhar_centro"><?= $valor_gerado2; ?></td>
                </tr>
                <?
							}//fim while in
						}//fim nada_in
					} //fim else multi
                }//fim while linhas
                ?>
		</table>
  </fieldset>
	<br />
	<? } //fim while categorias ?>
    
	<? if (!(($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir))) { ?>
	<center>
		<button type="submit">Salvar</button>
	</center>
	<? } ?>
	
	<br /><br />
</form>


<? /* <script language="javascript" type="text/javascript">daFoco('pesquisa');</script> */ ?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>