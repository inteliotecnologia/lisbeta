<? if (@pode("s", $_SESSION["permissao"])) { ?>
<?
if (($_POST["local"]=="") || ($_POST["local"]=="0")) {
	$tit= pega_cidade($_SESSION["id_cidade_sessao"]);
	$str1= "cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
}
else {
	$tit= pega_posto($_POST["local"]);
	$str1= "postos.id_posto = '". $_POST["local"] ."'";

}
$divisao_dias= 30;

if (isset($_POST["tipo_periodo"])) {
	if ($_POST["tipo_periodo"]=="p") {
		$data_inicio= desformata_data($_POST["inicio"]);
		$data_fim= desformata_data($_POST["fim"]);
		$tit2= " do período de ". $_POST["inicio"] ." a ". $_POST["fim"];
		
		$str2= "and consultas.data_consulta between '". $data_inicio[2] ."-". $data_inicio[1] ."-". $data_inicio[0] ."'
				and '". $data_fim[2] ."-". $data_fim[1] ."-". $data_fim[0] ."' ";
	
		$data1 = mktime(0, 0, 0, $data_fim[1], $data_fim[0], $data_fim[2]);
		$data2 = mktime(0, 0, 0, $data_inicio[1], $data_inicio[0], $data_inicio[2]);
		$divisao_dias= floor(($data1-$data2)/86400);
	}
	else {
		$mes_rel= $_POST["mes_periodo"];
		$ano_rel= $_POST["ano_periodo"];
		$tit2= " do mês ". traduz_mes($mes_rel) ."/". $ano_rel;
		
		$str2= "and DATE_FORMAT(consultas.data_consulta, '%m') = '". $mes_rel ."'
				and DATE_FORMAT(consultas.data_consulta, '%Y') = '". $ano_rel ."'
				";
	}
}
else {
	$mes_rel= date("m");
	$ano_rel= date("Y");
	$tit2= " do mês ". traduz_mes($mes_rel) ."/". $ano_rel;
	
	$str2= "and DATE_FORMAT(consultas.data_consulta, '%m') = '". $mes_rel ."'
			and DATE_FORMAT(consultas.data_consulta, '%Y') = '". $ano_rel ."'
			";
}

if ( ($mes_rel==date("m")) && ($ano_rel==date("Y")) )
	$divisao_dias= date("d");

if ($divisao_dias==0)
	$divisao_dias= 1;
?>
<h2 class="titulos">Resumo de <?= $tit; ?></h2>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formRelatorioResumoMini" method="post" id="formRelatorioResumoMini" name="formRelatorioResumoMini" onsubmit="return ajaxForm('conteudo', 'formRelatorioResumoMini');">
		<label class="tamanho30" for="txt_busca">Local:</label>
	
		<select name="local" id="local" class="tamanho160">
			<option value="0">TODOS</option>
			<?
			$result_postos= mysql_query("select postos.* from postos, cidades
											where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											and   postos.id_cidade = cidades.id_cidade
											and   cidades.sistema = '1'
											") or die(mysql_error());
			while ($rs_postos= mysql_fetch_object($result_postos)) {
			?>
			<option value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
			<? } ?>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Resumo <?= $tit2; ?> - <?= $divisao_dias; ?> dias </legend>
		
		<? if (($_POST["local"]=="") || ($_POST["local"]=="0")) { ?>
		<label>Nº postos:</label>
		<?= pega_num_postos($_SESSION["id_cidade_sessao"]); ?>
		<br />
		<? } ?>

		<label>Nº consultas:</label>
		<?
		$result_cons= mysql_query("select count(consultas.id_consulta) as num_consultas
									from consultas, postos, cidades
									where ". $str1 ."
									". $str2 ."
									and   consultas.id_posto = postos.id_posto
									and   postos.id_cidade = cidades.id_cidade
									") or die(mysql_error());
		$rs_cons= mysql_fetch_object($result_cons);
		$total_consultas= $rs_cons->num_consultas;
		
		echo $total_consultas ." (média de ". number_format($total_consultas/$divisao_dias, 2, ',', '.') ." consultas por dia)";
		?>
		<br />

		<label>Exames:</label>
		<br />
		<ul class="recuo4">
		<?
		$total_exames=0;
		$result_exam1= mysql_query("select * from exames order by exame ");
		while ($rs_exam1= mysql_fetch_object($result_exam1)) {
		
			$result_exam2= mysql_query("select count(consultas_exames.id_consulta_exame) as num_exames
											from consultas, consultas_exames, postos, cidades
											where ". $str1 ."
												  ". $str2 ."
											and   consultas_exames.id_exame = '". $rs_exam1->id_exame ."'
											and   consultas.id_consulta = consultas_exames.id_consulta
											and   consultas.id_posto = postos.id_posto
											and   postos.id_cidade = cidades.id_cidade
										") or die(mysql_error());
			$rs_exam2= mysql_fetch_object($result_exam2);
			$total_exames += $rs_exam2->num_exames;
			if ($rs_exam2->num_exames!=0) {
		?>
		<li><?= $rs_exam1->exame ." (". $rs_exam2->num_exames .")"; ?></li>
		<? } } ?>
		</ul>
		
		<? if ($total_exames!=0) { ?>
		<label>&nbsp;</label>
		<? } ?>
		<?= $total_exames ." solicitações no total (média de ". number_format($total_exames/$divisao_dias, 2, ',', '.') ." exames solicitados por dia)"; ?>
		<br />

		<label>Remédios:</label>
		<br />
		<ul class="recuo4">
		<?
		$total_remedios=0;
		$result_rem1= mysql_query("select * from remedios order by remedio ");
		while ($rs_rem1= mysql_fetch_object($result_rem1)) {
		
			$result_rem2= mysql_query("select count(consultas_remedios.id_consulta_remedio) as num_remedios
											from consultas, consultas_remedios, postos, cidades
											where ". $str1 ."
												  ". $str2 ."
											and   consultas_remedios.id_remedio = '". $rs_rem1->id_remedio ."'
											and   consultas.id_consulta = consultas_remedios.id_consulta
											and   consultas.id_posto = postos.id_posto
											and   postos.id_cidade = cidades.id_cidade
										") or die(mysql_error());
			$rs_rem2= mysql_fetch_object($result_rem2);
			$total_remedios += $rs_rem2->num_remedios;
			if ($rs_rem2->num_remedios!=0) {
		?>
		<li><?= $rs_rem1->remedio ." (". $rs_rem2->num_remedios .")"; ?></li>
		<? } } ?>
		</ul>
		
		<? if ($total_remedios!=0) { ?>
		<label>&nbsp;</label>
		<? } ?>
		<?= $total_remedios ." solicitações no total (média de ". number_format($total_remedios/$divisao_dias, 2, ',', '.') ." remédios solicitados por dia)"; ?>
		<br />
		
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>