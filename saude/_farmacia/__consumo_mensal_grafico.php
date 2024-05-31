<? 
if (!$conexao)
	require_once("conexao.php");

if (@pode("f", $_SESSION["permissao"])) {

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
							order by total desc limit 12
							") or die(mysql_error());
	$i= 0;
	while($rs= mysql_fetch_object($result)) {
		$data[$i]= $rs->total;
		$legenda[$i]= $rs->remedio;
		$i++;
	}

	include ("jpgraph/jpgraph.php");
	include ("jpgraph/jpgraph_pie.php");

	$graph = new PieGraph(640,480,"auto");
	$graph->SetShadow();

	$graph->title->Set("Consumo mensal - ". $periodo);
	$graph->title->SetFont(FF_FONT2,FS_BOLD);
	
	$p1 = new PiePlot($data);
	$p1->SetLegends($legenda);
	//$p1->ExplodeSlice(1);
	$p1->SetCenter(0.3);
	
	$graph->Add($p1);
	$graph->Stroke();
}
?>