<? 
if (!$conexao)
	require_once("conexao.php");

if (@pode("x", $_SESSION["permissao"])) {

	$periodo2= explode("/", $periodo);
	$mes= $periodo2[0];
	$ano= $periodo2[1];

	$result= mysql_query("select materiais.*, sum(almoxarifadom_mov.qtde) as total from almoxarifadom_mov, materiais
							where almoxarifadom_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							and   almoxarifadom_mov.tipo_trans <> 'e'
							and   almoxarifadom_mov.id_material = materiais.id_material
							and   DATE_FORMAT(almoxarifadom_mov.data_trans, '%m') = '". $mes ."'
							and   DATE_FORMAT(almoxarifadom_mov.data_trans, '%Y') = '". $ano ."'
							group by almoxarifadom_mov.id_material
							order by total desc limit 12
							") or die(mysql_error());
	$i= 0;
	while($rs= mysql_fetch_object($result)) {
		$data[$i]= $rs->total;
		$legenda[$i]= $rs->material ." (". pega_tipo_material($rs->tipo_material) .")";;
		$i++;
	}

	include ("jpgraph/jpgraph.php");
	include ("jpgraph/jpgraph_pie.php");

	$graph = new PieGraph(790,480,"auto");
	$graph->SetShadow();

	$graph->title->Set("Consumo mensal - ". $periodo);
	$graph->title->SetFont(FF_FONT2,FS_BOLD);
	
	$p1 = new PiePlot($data);
	$p1->SetLegends($legenda);
	//$p1->ExplodeSlice(1);
	$p1->SetCenter(0.23);
	
	$graph->Add($p1);
	$graph->Stroke();
}
?>