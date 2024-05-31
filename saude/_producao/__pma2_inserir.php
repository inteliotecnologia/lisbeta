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
$result_dados= mysql_query("select dado from pma2_dados
							where mes = '$mes'
							and   ano = '$ano'
							and   id_posto= '". $id_posto ."'
							");
if (mysql_num_rows($result_dados)==0) $tem=0;
else $tem=1;

//---------------------------------------------------------------------

$pode_produzir= pega_status_producao_mes(pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]), $mes, $ano);
?>
<h2 class="titulos">Produção - PMA2 - <?= $mes ."/". $ano; ?></h2>

<?
if (!$pode_produzir)
	echo "<p class=\"vermelho\">O preenchimento dos dados da produção deste mês já foi finalizada pelo responsável pela digitação, entre em contato para caso necessite alterar algo.</p>";

if (!$tem)
	echo "<p class=\"vermelho\">Os valores que estão nos campos são valores gerados pelo sistema, confira-os e se necessário, altere-os.</p>";
?>

<form action="<?= AJAX_FORM; ?>formPMA2" method="post" id="formPMA2" name="formPMA2" onsubmit="return ajaxForm('conteudo', 'formPMA2');">
	<input type="hidden" name="mes" value="<?= $mes; ?>" class="escondido" />
	<input type="hidden" name="ano" value="<?= $ano; ?>" class="escondido" />
	
    <div class="parte50">
	<?
	$result_conta_ssa2= mysql_query("select * from ssa2_linhas order by id_linha");
	$total_linhas= mysql_num_rows($result_conta_ssa2);
	
	$result_cat= mysql_query("select * from pma2_categorias");
	
	while ($rs_cat= mysql_fetch_object($result_cat)) {
	?>
	<fieldset>
		<legend><?= $rs_cat->id_categoria .". ". $rs_cat->categoria; ?></legend>
		<table cellspacing="0">
	        <tr>
			    <th width="60%" align="left">Parâmetro</th>
			    <th width="15%" align="left">Valor real</th>
		        <th width="15%">Valor gerado</th>
	        </tr>
			<?
			$result_pma2= mysql_query("select * from pma2_linhas
										where id_categoria= '". $rs_cat->id_categoria ."'
										order by id_linha");
			
			while ($rs_pma2= mysql_fetch_object($result_pma2)) {
				$result_dado= mysql_query("select dado from pma2_dados
												where mes = '$mes'
												and   ano = '$ano'
												and   id_linha= '". $rs_pma2->id_linha ."'
												and   id_posto= '". $id_posto ."'
												");				
				// -------------- inicio do codigo para pegar os valores -------------------------------------------------------
				
				// 20/01/2008 -> 19/02/2008
				$mes_anterior= date("Y-m", mktime(0, 0, 0, $mes-1, $dia, $ano));
				$mes_atual= date("Y-m", mktime(0, 0, 0, $mes, $dia, $ano));
				$mes_proximo= date("Y-m", mktime(0, 0, 0, $mes+1, $dia, $ano));
				
				/*if ($dia>=20) {
					$sql_consultas_mais= "
											and   consultas.data_consulta >= '". $mes_atual ."-20'
											and   consultas.data_consulta < '". $mes_proximo ."-20'
											";
					$sql_procedimentos_mais= " 
											and   procedimentos.data_procedimento >= '". $mes_atual ."-20'
											and   procedimentos.data_procedimento < '". $mes_proximo ."-20'
											";
				}					
				else {*/
					$sql_consultas_mais= "
											and   consultas.data_consulta >= '". $mes_anterior ."-20'
											and   consultas.data_consulta < '". $mes_atual ."-20'
											";
					$sql_procedimentos_mais= " 
											and   procedimentos.data_procedimento >= '". $mes_anterior ."-20'
											and   procedimentos.data_procedimento < '". $mes_atual ."-20'
											";
				//}
					
				
				$sql_exames= "select count(consultas_exames.id_consulta_exame) as total from consultas, consultas_exames, exames
									where consultas.id_posto = '". $id_posto ."'
									and   consultas.id_consulta = consultas_exames.id_consulta
									and   exames.id_exame = consultas_exames.id_exame
									". $sql_consultas_mais ."
									";
				
				$sql_consultas= "select count(id_consulta) as total from consultas
									where id_posto = '". $id_posto ."'
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
				
				switch ($rs_pma2->id_linha) {
					// consultas médicas
					case 1: $sql= $sql_consultas ." and   area_abran= '0'"; break;
					case 3: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente < '1' "; break;
					case 4: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '1' and idade_paciente <= '4' "; break;
					case 5: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '5' and idade_paciente <= '9' "; break;
					case 6: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '10' and idade_paciente <= '14' "; break;
					case 7: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '15' and idade_paciente <= '19' "; break;
					case 8: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '20' and idade_paciente <= '39' "; break;
					case 9: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '40' and idade_paciente <= '49' "; break;
					case 10: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '50' and idade_paciente <= '59' "; break;
					case 11: $sql= $sql_consultas ." and   area_abran= '1' and idade_paciente >= '60' "; break;
					//case 12: $sql= $sql_consultas ." and   area_abran= '1' "; break;
					//case 13: $sql= $sql_consultas ." "; break;
					
					// atendimentos médico ou enfermagem
					
					case 12: $sql= $sql_consultas ." and   id_tipo_atendimento= '1' "; break;
					case 13: $sql= $sql_consultas ." and   id_tipo_atendimento= '2' "; break;
					case 14: $sql= $sql_consultas ." and   id_tipo_atendimento= '3' "; break;
					case 15: $sql= $sql_consultas ." and   id_tipo_atendimento= '4' "; break;
					case 16: $sql= $sql_consultas ." and   id_tipo_atendimento= '5' "; break;
					case 17: $sql= $sql_consultas ." and   id_tipo_atendimento= '6' "; break;
					case 18: $sql= $sql_consultas ." and   id_tipo_atendimento= '7' "; break;
					case 19: $sql= $sql_consultas ." and   id_tipo_atendimento= '8' "; break;
							
					// exames
					
					case 20: $sql= $sql_exames ." and   tipo_exame= '1' "; break;
					case 21: $sql= $sql_exames ." and   tipo_exame= '2' "; break;
					case 22: $sql= $sql_exames ." and   tipo_exame= '3' "; break;
					case 23: $sql= $sql_exames ." and   tipo_exame= '4' "; break;
					case 24: $sql= $sql_exames ." and   tipo_exame= '5' "; break;
						
					// encaminhamentos
					
					case 25: $sql= $sql_consultas ." and   encaminhamento= '3' "; break;
					case 26: $sql= $sql_consultas ." and   encaminhamento= '4' "; break;
					case 27: $sql= $sql_consultas ." and   encaminhamento= '5' "; break;
					
					// internação domiciliar
					
					case 28: $sql= $sql_consultas ." and   encaminhamento= '2' "; break;
							
					// procedimentos
					
					case 29: $sql= $sql_procedimentos ." and   id_procedimento= '1' "; break;
					case 30: $sql= $sql_procedimentos ." and   id_procedimento= '2' "; break;
					case 31: $sql= $sql_consultas ."     and   tipo_consulta_prof= 'e' "; break; //consultas de enfermagem
					case 32: $sql= $sql_consultas ."     and   tipo_consulta_prof= 'o' "; break; //consultas odontológicas
					case 33: $sql= $sql_procedimentos ." and   id_procedimento= '3' "; break;
					case 34: $sql= $sql_procedimentos ." and   id_procedimento= '4' "; break;
					case 35: $sql= $sql_procedimentos ." and   id_procedimento= '5' "; break;
					case 36: $sql= $sql_procedimentos ." and   id_procedimento= '6' "; break;
					case 37: $sql= $sql_procedimentos ." and   id_procedimento= '7' "; break;
					case 38: $sql= $sql_procedimentos ." and   id_procedimento= '8' "; break;
					case 39: $sql= $sql_procedimentos ." and   id_procedimento= '9' "; break;
					case 40: $sql= $sql_procedimentos ." and   id_procedimento= '10' "; break;
					case 41: $sql= $sql_procedimentos ." and   id_procedimento= '11' "; break;
					case 42: $sql= $sql_procedimentos ." and   id_procedimento= '12' "; break;
					case 43: $sql= $sql_procedimentos ." and   id_procedimento= '13' "; break;
					
					//marcadores
					case 53: $sql= $sql_ssa2 ."'3'"; break;
					case 54: $sql= $sql_ssa2 ."'26'"; break;
					case 55: $sql= $sql_ssa2 ."'35'"; break;
					case 56: $sql= $sql_ssa2 ."'36'"; break;
					case 57: $sql= $sql_ssa2 ."'37'"; break;
					case 58: $sql= $sql_ssa2 ."'38'"; break;
					case 59: $sql= $sql_ssa2 ."'39'"; break;
					case 60: $sql= $sql_ssa2 ."'40'"; break;
					case 61: $sql= $sql_ssa2 ."'53'"; break;
					case 62: $sql= $sql_ssa2 ."'51'"; break;
					case 63: $sql= $sql_ssa2 ."'52'"; break;
					case 64: $sql= $sql_ssa2 ."'54'"; break;
					case 65: $sql= $sql_ssa2 ."'58'"; break;
					
					case 66: $sql= $sql_consultas ." and local_consulta = 'd' and tipo_consulta_prof= 'm' "; break;
					case 67: $sql= $sql_consultas ." and local_consulta = 'd' and tipo_consulta_prof= 'e' "; break;
					case 68: $sql= $sql_consultas ." and local_consulta = 'd' and tipo_consulta_prof= 'o' "; break;
					case 69: $sql= $sql_procedimentos ." and   id_procedimento= '19' "; break;
					case 70: $sql= $sql_ssa2 ."'60'"; break;
					case 71: break;
					
					default: $nada=true;
				}//fim switch
				
				if (!$nada) {
					//echo "<code>".$sql ."</code>";
					
					$result= mysql_query($sql) or die(mysql_error());
					$rs= mysql_fetch_object($result);
					
					if ($rs->total=="") $valor_gerado[$rs_pma2->id_linha]= 0;
					else $valor_gerado[$rs_pma2->id_linha]= $rs->total;
					
					$valor_soma[$rs_pma2->id_linha]= $valor_gerado[$rs_pma2->id_linha];
				}
				else $valor_gerado[$rs_pma2->id_linha]= "<span class=\"vermelho\">ñ disp.</span>";
				
				$nada= false;
				// -------------- fim do codigo para pegar os valores -------------------------------------------------------
				
				if ($tem==1) {
					if (mysql_num_rows($result_dado)==0)
						$dado[$rs_pma2->id_linha]= "0";
					else {
						$rs_dado= mysql_fetch_object($result_dado);
						$dado[$rs_pma2->id_linha]= $rs_dado->dado;
					}
				}
				else {
					$dado[$rs_pma2->id_linha]= ($valor_gerado[$rs_pma2->id_linha]+0);
				}

				
			?>
			<tr>
				<td width="70%">
					<?
					echo "<em>". $rs_pma2->id_linha .".</em> ";
					if ($rs_pma2->negrito==1) echo "<strong>". $rs_pma2->linha ."</strong>";
					else echo $rs_pma2->linha;
					?>
                    </td>
				<td class="alinhar_centro">
                	<? if ($rs_pma2->vazio!=1) { ?>
					<input value="<?= $rs_pma2->id_linha; ?>" name="id_linha[]" type="hidden" class="escondido" />
					<input <? if (($_SESSION["id_cidade_sessao"]!="") || (!$pode_produzir)) echo "disabled=\"disabled\""; ?> value="<?= $dado[$rs_pma2->id_linha]; ?>" name="dado[]" onkeypress="return ajeitaTecla(event);" onblur="somaDadosPMA2(<?= $rs_pma2->id_linha; ?>, this);" onfocus="this.select();" id="campo_<?= $rs_pma2->id_linha; ?>" class="tamanho_ajuste alinhar_centro" />
                    <? } else echo "&nbsp;"; ?>
                    </td>
				<td class="alinhar_centro"><?= $valor_gerado[$rs_pma2->id_linha]; ?></td>
			</tr>
            <?
            if ($rs_pma2->id_linha==11) {
				$valor_campo1= $dado[3]+$dado[4]+$dado[5]+$dado[6]+$dado[7]+$dado[8]+$dado[9]+$dado[10]+$dado[11];
				$valor_campo2= $valor_campo1+$dado[1];
			?>
	        <tr>
                <td width="70%">
                    <strong>Total (residentes na área)</strong>
                </td>
                <td class="alinhar_centro">
                    <input disabled="disabled" value="<?= $valor_campo1; ?>" name="total_consultas_area" id="total_consultas_area" class="tamanho_ajuste alinhar_centro" />
                </td>
                <td class="alinhar_centro">
                    <?
					$rs_12_1= mysql_fetch_object(mysql_query($sql_consultas ." and   area_abran= '1' "));
					echo $rs_12_1->total;
					?>
                </td>
            </tr>
            <tr>
                <td width="70%">
                    <strong>Total (geral)</strong>
                </td>
                <td class="alinhar_centro">
                    <input disabled="disabled" value="<?= $valor_campo2; ?>" name="total_consultas" id="total_consultas" class="tamanho_ajuste alinhar_centro" />
                </td>
                <td class="alinhar_centro">
                    <?
					$rs_12_2= mysql_fetch_object(mysql_query($sql_consultas ." "));
					echo $rs_12_2->total;
					?>
                </td>
            </tr>
            <? } ?>
            <?
            if ($rs_pma2->id_linha==70) {
				$valor_campo= $dado[66]+$dado[67]+$dado[68]+$dado[69]+$dado[70];
				$valor_soma= $valor_gerado[66]+$valor_gerado[67]+$valor_gerado[68]+$valor_gerado[69]+$valor_gerado[70];
			?>
	        <tr>
                <td width="70%">
                    <strong>Total (visitas domiciliares)</strong>
                </td>
                <td class="alinhar_centro">
                    <input disabled="disabled" value="<?= $valor_campo; ?>" name="total_visitas_domiciliares" id="total_visitas_domiciliares" class="tamanho_ajuste alinhar_centro" />
                </td>
                <td class="alinhar_centro">
                    <?= $valor_soma; ?>
                </td>
            </tr>
            <? } ?>
            <? } ?>
		</table>
	  </fieldset>
	<br />
	<?
    if ($rs_cat->id_categoria==5)
		echo "</div><div class=\"parte50\">";
	}
	?>
	</div>
    
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