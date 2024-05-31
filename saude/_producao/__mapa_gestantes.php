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
<h2 class="titulos">Produção - Mapa mensal da gestante - <?= $mes_tit ."/". $ano_tit; ?></h2>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formMapaGestantesBuscar" method="post" id="formMapaGestantesBuscar" name="formMapaGestantesBuscar" onsubmit="return ajaxForm('conteudo', 'formMapaGestantesBuscar');">
		
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
              <th width="15%" rowspan="2" align="left">PSF</th>
              <th width="12.5%" rowspan="2" align="left">N&ordm; cadastradas</th>
              <th width="12.5%" rowspan="2">N&ordm; acompanhadas</th>
              <th colspan="2">Baixo peso</th>
              <th colspan="2">Normal</th>
              <th colspan="2" align="center">Sobrepeso</th>
              <th colspan="2">Obesidade</th>
        </tr>
	        <tr>
              <th width="7.5%" align="center">Qtde</th>
              <th width="7.5%" align="center">%</th>
              <th width="7.5%" align="center">Qtde</th>
              <th width="7.5%" align="center">%</th>
              <th width="7.5%" align="center">Qtde</th>
              <th width="7.5%" align="center">%</th>
              <th width="7.5%" align="center">Qtde</th>
              <th width="7.5%" align="center">%</th>
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
			
			if ($_SESSION["id_cidade_sessao"]!="") $id_cidade= $_SESSION["id_cidade_sessao"];
			else $id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
			
			$result_p= mysql_query("select * from postos
									where id_cidade = '". $id_cidade ."'
									");
			
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
			
			while ($rs_p= mysql_fetch_object($result_p)) {
				
				if (($_SESSION["id_cidade_sessao"]!="") || (($_SESSION["id_posto_sessao"]!="") && ($_SESSION["id_posto_sessao"]==$rs_p->id_posto)) ) {
					//if ($_SESSION["id_posto_sessao"]==$rs_p->id_posto) {
						$result_cad= mysql_query("select count(id_pessoa) as total
													from acomp_grupos_pessoas
													where id_posto = '". $rs_p->id_posto ."'
													and   id_grupo = '1'
													");
						$rs_cad= mysql_fetch_object($result_cad);
						
						$result_acomp= mysql_query("select distinct(id_pessoa), estado_nutricional
													from acompanhamento
													where tipo_acompanhamento = 'g'
													and   data_acompanhamento >= '". $periodo1 ."-20'
													and   data_acompanhamento < '". $periodo2 ."-20'
													and   id_posto = '". $rs_p->id_posto ."'
													group by id_pessoa
													");
						
						$acompanhados= mysql_num_rows($result_acomp);
						
						$total_cadastrados += $rs_cad->total;
						$total_acompanhados += $acompanhados;
						
						$en1=0; $en2=0; $en3=0; $en4=0;
						
						while ($rs_acomp= mysql_fetch_object($result_acomp)) {
							switch($rs_acomp->estado_nutricional) {
								case 1: $en1++; break;
								case 2: $en2++; break;
								case 3: $en3++; break;
								case 4: $en4++; break;
							}
						}
						$total_linha= $en1+$en2+$en3+$en4;
						
						if ($total_linha==0) {
							$p1=0; $p2=0; $p3=0; $p4=0;
						}
						else {
							$p1= number_format((($en1*100)/$total_linha), 2, ',', '.');
							$p2= number_format((($en2*100)/$total_linha), 2, ',', '.');
							$p3= number_format((($en3*100)/$total_linha), 2, ',', '.');
							$p4= number_format((($en4*100)/$total_linha), 2, ',', '.');
						}
							
						$total_en1 += $en1;
						$total_en2 += $en2;
						$total_en3 += $en3;
						$total_en4 += $en4;
			?>
            <tr>
              <td align="left"><?= $rs_p->posto; ?></td>
              <td align="center"><?= $rs_cad->total; ?></td>
              <td align="center"><?= $acompanhados; ?></td>
              <td align="center"><?= $en1; ?></td>
              <td align="center"><?= $p1; ?> %</td>
              <td align="center"><?= $en2; ?></td>
              <td align="center"><?= $p2; ?> %</td>
              <td align="center"><?= $en3; ?></td>
              <td align="center"><?= $p3; ?> %</td>
              <td align="center"><?= $en4; ?></td>
              <td align="center"><?= $p4; ?> %</td>
            </tr>
			<?
            } } //}
			if ($_SESSION["id_cidade_sessao"]!="") {
			
				if ($total_acompanhados==0) {
					$t1=0; $t2=0; $t3=0; $t4=0;
				}
				else {
					$t1= number_format((($total_en1*100)/$total_acompanhados), 2, ',', '.');
					$t2= number_format((($total_en2*100)/$total_acompanhados), 2, ',', '.');
					$t3= number_format((($total_en3*100)/$total_acompanhados), 2, ',', '.');
					$t4= number_format((($total_en4*100)/$total_acompanhados), 2, ',', '.');
				}
			?>
            <tr>
              <td align="left"><strong>Total</strong></td>
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
            </tr>
            <? } ?>
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
	