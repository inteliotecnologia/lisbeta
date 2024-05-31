<? if (@pode("p", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formProducaoPeriodo" method="post" id="formProducaoPeriodo" name="formProducaoPeriodo" onsubmit="return ajaxForm('conteudo', 'formProducaoPeriodo');">

		<label class="tamanho100" for="periodo">Período:</label>

		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$result_per= mysql_query("select distinct mes, ano from ssa2_dados order by ano desc, mes desc");
			while ($rs_per= mysql_fetch_object($result_per)) {
				$periodo= $rs_per->mes ."/". $rs_per->ano;
			?>
			<option value="<?= $periodo; ?>" <? if ($_POST["periodo"]==$periodo) echo "selected=\"selected\""; ?>><?= $periodo; ?></option>
			<? } ?>
		</select>	

		<button>Buscar</button>
	</form>
</div>


<?
if ($_POST["periodo"]!="") {
	$periodo= explode("/", $_POST["periodo"]);
	$mes= $periodo[0];
	$ano= $periodo[1];
}
else {
	if ($_GET["mes"]=="") $mes= date("m");
	else $mes= $_GET["mes"];
		
	if ($_GET["ano"]=="") $ano= date("Y");
	else $ano= $_GET["ano"];
	
	$dia= date("d");
	if (($dia>25) && (date("m")==$mes) && (date("Y")==$ano) && ($_SESSION["id_posto_sessao"]!="")) {
		$mes= date("m", mktime(0, 0, 0, $mes+1, $dia, $ano));
		$ano= date("Y", mktime(0, 0, 0, $mes+1, $dia, $ano));
		
		$periodo_aux=1;
	}
	else $dia=1;
}
?>
<h2 class="titulos">Produção - <?= $mes ."/". $ano; ?></h2>

<label class="tamanho200">Situação da produção mensal:</label>

<br />

<table cellspacing="0">
	<tr>
		<th align="left">Postos</th>
		<?
		if ($_SESSION["id_cidade_sessao"]!="") {
			$result_pos= mysql_query("select postos.id_posto, postos.posto from postos
										where postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										and   psf = '1'
										");
			$num=0;
			while ($rs_pos= mysql_fetch_object($result_pos)) {
				$id_posto[$num]= $rs_pos->id_posto;
		?>
		<th><?= $rs_pos->posto; ?></th>
		<? $num++; } } else { ?>
		<th><?= pega_posto($_SESSION["id_posto_sessao"]); ?></th>
		<? } ?>
	</tr>
	
	<tr>
		<td>SSA2</td>
		<?
		if ($_SESSION["id_cidade_sessao"]!="") {
			for ($i=0; $i<$num; $i++) {
				$sn= pega_resultado_producao("ssa2", $mes, $ano, $id_posto[$i]);
			?>
		<td align="center">
		<? if ($sn) { ?>
		<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/ssa2_inserir&amp;id_posto=<?= $id_posto[$i] ?>&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
		<?
		}
		else
			echo sim_nao($sn);
		?>		</td>
		<?
			}
		}
		else {
			$sn= pega_resultado_producao("ssa2", $mes, $ano, $_SESSION["id_posto_sessao"]);
			?>
			<td align="center">
			<? if ($sn) { ?>
				<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/ssa2_inserir&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
				<?
				}
				else
					echo sim_nao($sn);
		?>		</td>
		<? } ?>
	</tr>
	<tr>
		<td>PMA2</td>
		<?
		if ($_SESSION["id_cidade_sessao"]!="") {
			for ($i=0; $i<$num; $i++) {
				$sn= pega_resultado_producao("pma2", $mes, $ano, $id_posto[$i]);
			?>
			<td align="center">
            <? if ($sn) { ?>
            <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/pma2_inserir&amp;id_posto=<?= $id_posto[$i] ?>&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
            <?
            }
            else
                echo sim_nao($sn);
            ?></td>
			<? }
		}
		else {
			$sn= pega_resultado_producao("pma2", $mes, $ano, $_SESSION["id_posto_sessao"]);
			?>
			<td align="center">
			<? if ($sn) { ?>
            <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/pma2_inserir&amp;id_posto=<?= $id_posto[$i] ?>&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
            <?
			}
			else
				echo sim_nao($sn);
		?>		</td>
		<? } ?>
	</tr>
	<tr>
	  <td>BPA</td>
	  <?
		if ($_SESSION["id_cidade_sessao"]!="") {
			for ($i=0; $i<$num; $i++) {
				$sn= pega_resultado_producao("bpa", $mes, $ano, $id_posto[$i]);
			?>
			<td align="center">
            <? if ($sn) { ?>
            <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/bpa_inserir&amp;id_posto=<?= $id_posto[$i] ?>&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
            <?
            }
            else
                echo sim_nao($sn);
            ?></td>
			<? }
		}
		else {
			$sn= pega_resultado_producao("bpa", $mes, $ano, $_SESSION["id_posto_sessao"]);
			?>
			<td align="center">
			<? if ($sn) { ?>
            <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/bpa_inserir&amp;id_posto=<?= $id_posto[$i] ?>&amp;mes=<?= $mes; ?>&amp;ano=<?= $ano; ?>');"><?= sim_nao($sn); ?></a>
            <?
			}
			else
				echo sim_nao($sn);
		?>
        </td>
        <? } ?>
  </tr>
</table>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>