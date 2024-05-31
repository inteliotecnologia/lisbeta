<? 
if (!$conexao)
	require_once("conexao.php");

if (@pode("f", $_SESSION["permissao"])) {
    for ($i=-1; $i<6; $i++) {
        $mes= date("m", mktime(0, 0, 0, date("m")-$i, 0, date("Y")));
        $ano= date("Y", mktime(0, 0, 0, date("m")-$i, 0, date("Y")));
    
		$result= mysql_query("select sum(qtde) as total from almoxarifadom_mov
								where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   tipo_trans <> 'e'
								and   DATE_FORMAT(data_trans, '%m') = '". $mes ."'
								and   DATE_FORMAT(data_trans, '%Y') = '". $ano ."'
								and   id_material = '". $id_material ."'
								");
		$rs= mysql_fetch_object($result);
		
		$data[$i+1]= $rs->total;
		$legenda[$i+1]= $mes ."/". $ano;
	}
	
	include ("jpgraph/jpgraph.php");
	include ("jpgraph/jpgraph_bar.php");
	
	$graph = new Graph(640,480,'auto');
	$graph->SetShadow();
	
	// Use a "text" X-scale
	$graph->SetScale("textlin");
	
	// Specify X-labels
	$graph->xaxis->SetTickLabels($legenda);
	$graph->title->Set("Consumo de ". pega_material($id_material)." (". pega_tipo_material(pega_tipo_material_db($id_material)) .")");
	$graph->title->SetFont(FF_FONT2,FS_BOLD);
	
	$b1 = new BarPlot($data);
	$b1->SetLegend("Consumo");
	$b1->value->Show();
	
	//$b1->SetAbsWidth(6);
	//$b1->SetShadow();
	
	// The order the plots are added determines who's ontop
	$graph->Add($b1);
	
	// Finally output the  image
	$graph->Stroke();

}
?>