<? if (@pode("p", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<?
if (!isset($_GET["mes"])) $mes= date("m");
else $mes= $_GET["mes"];
	
if (!isset($_GET["ano"])) $ano= date("Y");
else $ano= $_GET["ano"];

$dia= date("d");

if ($_SESSION["id_cidade_sessao"]!="") $id_posto= $_GET["id_posto"];
else $id_posto= $_SESSION["id_posto_sessao"];

$pode_produzir= pega_status_producao_mes(pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]), $mes, $ano);

$mes_tit= $mes;
$ano_tit= $ano;

if ($_POST["periodo"]!="") {
	$periodot= explode('/', $periodo);
	
	$mes_tit= $periodot[0];
	$ano_tit= $periodot[1];
}
?>
<h2 class="titulos">Produção - Mapa mensal da criança - <?= $mes_tit ."/". $ano_tit; ?></h2>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formMapaCriancasBuscar" method="post" id="formMapaCriancasBuscar" name="formMapaCriancasBuscar" onsubmit="return ajaxForm('conteudo', 'formMapaCriancasBuscar');">
		
        <label for="periodo">Período:</label>
		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$result_per= mysql_query("select distinct(DATE_FORMAT(data_acompanhamento, '%m/%Y')) as data_acompanhamento from acompanhamento order by data_acompanhamento desc ");
			while ($rs_per= mysql_fetch_object($result_per)) {
			?>
			<option value="<?= $rs_per->data_acompanhamento; ?>" <? if ($_POST["periodo"]==$rs_per->data_acompanhamento) echo "selected=\"selected\""; ?>><?= $rs_per->data_acompanhamento; ?></option>
			<? } ?>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<?
if (!$pode_produzir)
	echo "<p class=\"vermelho\">O preenchimento dos dados da produção deste mês já foi finalizada pelo responsável pela digitação, entre em contato para caso necessite alterar algo.</p>";
?>

      <table cellspacing="0">
	        <tr>
              <th width="16%" rowspan="2" align="left">Faixa et&aacute;ria</th>
              <th width="12%" rowspan="2" align="left">N&ordm; cadastrados</th>
              <th width="12%" rowspan="2">N&ordm; acompanhados</th>
              <th colspan="2">Peso muito baixo</th>
              <th colspan="2">Peso baixo</th>
              <th colspan="2" align="left">Risco Nutricional</th>
              <th colspan="2">Normal</th>
              <th colspan="2">Sobrepeso</th>
        </tr>
	        <tr>
              <th width="6%" align="center">Qtde</th>
              <th width="6%" align="center">%</th>
              <th width="6%" align="center">Qtde</th>
              <th width="6%" align="center">%</th>
              <th width="6%" align="center">Qtde</th>
              <th width="6%" align="center">%</th>
              <th width="6%" align="center">Qtde</th>
              <th width="6%" align="center">%</th>
              <th width="6%" align="center">Qtde</th>
              <th width="6%" align="center">%</th>
        </tr>
			<?
			if ($periodo=="") $periodo= $mes ."/". $ano;
			else $periodo= $_POST["periodo"];
			
			$periodon= explode('/', $periodo);
			
			// -------------- inicio do codigo para pegar os valores -------------------------------------------------------
			
			// 20/01/2008 -> 19/02/2008
			$mes_anterior= date("Y-m", mktime(0, 0, 0, $mes-1, $dia, $ano));
			$mes_atual= date("Y-m", mktime(0, 0, 0, $mes, $dia, $ano));
			$mes_proximo= date("Y-m", mktime(0, 0, 0, $mes+1, $dia, $ano));
			
			$intervalo[1][1]=0; $intervalo[1][2]=5;
			$intervalo[2][1]=6; $intervalo[2][2]=11;
			$intervalo[3][1]=12; $intervalo[3][2]=23;
			$intervalo[4][1]=24; $intervalo[4][2]=35;
			$intervalo[5][1]=36; $intervalo[5][2]=59;
			$intervalo[6][1]=60; $intervalo[6][2]=83;
			$intervalo[7][1]=84; $intervalo[7][2]=131;
			
			$d= date("d");
			$m= date("m");
			$a= date("Y");
			
			if ($_POST["periodo"]!="") {
				$periodo1= date("Y-m", mktime(0, 0, 0, $periodon[0]-1, $dia, $periodon[1]));
				$periodo2= $periodon[1] .'-'. $periodon[0];
			}
			else {
				if ($d<=25) {
					$periodo1= $mes_anterior;
					$periodo2= $mes_atual;
				}
				else {
					$periodo1= $mes_atual;
					$periodo2= $mes_proximo;
				}
			}
			
			for ($i=1; $i<8; $i++) {
				$data1= date("Ymd", mktime(0, 0, 0, ($m-$intervalo[$i][1]), $d, $a));
				$data2= date("Ymd", mktime(0, 0, 0, ($m-($intervalo[$i][2]+1)), $d-1, $a));
				
				$result_nasc= mysql_query("select count(id_pessoa) as total
											from pessoas
											where data_nasc BETWEEN '$data2' and '$data1'
											and   id_psf = '". $_SESSION["id_posto_sessao"] ."'
											");
				$rs_nasc= mysql_fetch_object($result_nasc);
				
				$result_acomp= mysql_query("select *
											from acompanhamento
											where tipo_acompanhamento = 'c'
											and   idade_meses >= '". $intervalo[$i][1] ."'
											and   idade_meses <= '". $intervalo[$i][2] ."'
											and   data_acompanhamento >= '". $periodo1 ."-20'
											and   data_acompanhamento < '". $periodo2 ."-20'
											and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
											group by id_pessoa
											");
				$acompanhados= mysql_num_rows($result_acomp);
				
				$total_cadastrados += $rs_nasc->total;
				$total_acompanhados += $acompanhados;
				
				$en1=0; $en2=0; $en3=0; $en4=0; $en5=0;
				
				while ($rs_acomp= mysql_fetch_object($result_acomp)) {
					switch($rs_acomp->estado_nutricional) {
						case 1: $en1++; break;
						case 2: $en2++; break;
						case 3: $en3++; break;
						case 4: $en4++; break;
						case 5: $en5++; break;
					}
				}
				$total_linha= $en1+$en2+$en3+$en4+$en5;
				
				if ($total_linha==0) {
					$p1=0; $p2=0; $p3=0; $p4=0; $p5= 0;
				}
				else {
					$p1= number_format((($en1*100)/$total_linha), 2, ',', '.');
					$p2= number_format((($en2*100)/$total_linha), 2, ',', '.');
					$p3= number_format((($en3*100)/$total_linha), 2, ',', '.');
					$p4= number_format((($en4*100)/$total_linha), 2, ',', '.');
					$p5= number_format((($en5*100)/$total_linha), 2, ',', '.');
				}
					
				$total_en1 += $en1;
				$total_en2 += $en2;
				$total_en3 += $en3;
				$total_en4 += $en4;
				$total_en5 += $en5;
			?>
            <tr>
              <td align="left"><?= $intervalo[$i][1] ." a ". $intervalo[$i][2]; ?> meses</td>
              <td align="center"><?= $rs_nasc->total; ?></td>
              <td align="center"><?= $acompanhados; ?></td>
              <td align="center"><?= $en1; ?></td>
              <td align="center"><?= $p1; ?> %</td>
              <td align="center"><?= $en2; ?></td>
              <td align="center"><?= $p2; ?> %</td>
              <td align="center"><?= $en3; ?></td>
              <td align="center"><?= $p3; ?> %</td>
              <td align="center"><?= $en4; ?></td>
              <td align="center"><?= $p4; ?> %</td>
              <td align="center"><?= $en5; ?></td>
              <td align="center"><?= $p5; ?> %</td>
            </tr>
			<?
            }
			if ($total_acompanhados==0) {
				$t1=0; $t2=0; $t3=0; $t4=0; $t5=0;
			}
			else {
				$t1= number_format((($total_en1*100)/$total_acompanhados), 2, ',', '.');
				$t2= number_format((($total_en2*100)/$total_acompanhados), 2, ',', '.');
				$t3= number_format((($total_en3*100)/$total_acompanhados), 2, ',', '.');
				$t4= number_format((($total_en4*100)/$total_acompanhados), 2, ',', '.');
				$t5= number_format((($total_en5*100)/$total_acompanhados), 2, ',', '.');
			}
			?>
            <tr>
              <td align="left">Total</td>
              <td align="center"><?= $total_cadastrados; ?></td>
              <td align="center"><?= $total_acompanhados; ?></td>
              <td align="center"><?= $total_en1; ?></td>
              <td align="center"><?= $t1; ?> %</td>
              <td align="center"><?= $total_en2; ?></td>
              <td align="center"><?= $t2; ?> %</td>
              <td align="center"><?= $total_en3; ?></td>
              <td align="center"><?= $t3; ?> %</td>
              <td align="center"><?= $total_en4; ?></td>
              <td align="center"><?= $t4; ?> %</td>
              <td align="center"><?= $total_en5; ?></td>
              <td align="center"><?= $t5; ?> %</td>
            </tr>
		</table>
  </fieldset>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>
  </button>
	</center>
	