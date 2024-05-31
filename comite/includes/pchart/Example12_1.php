<?php
 /*
     Example12 : A true bar graph
 */

// Standard inclusions   
include("pChart/pData.class");
include("pChart/pChart.class");

// Dataset definition 
$DataSet = new pData;
$DataSet->AddPoint(array(9,8,7),"Serie1");
$DataSet->AddPoint(array(6,5,4),"Serie2");
$DataSet->AddPoint(array(3,2,1),"Serie3");
$DataSet->AddPoint(array(8.33,1,2),"Serie4");
$DataSet->AddPoint(array(7.83,1,2),"Serie5");
$DataSet->AddPoint(array("Qualidade da roupa", "Transporte", "Contato com a empresa"),"Serie6"); 

//$DataSet->AddAllSeries();

$DataSet->AddSerie("Serie1");
$DataSet->AddSerie("Serie2");
$DataSet->AddSerie("Serie3");
$DataSet->AddSerie("Serie4");
$DataSet->AddSerie("Serie5");

$DataSet->SetAbsciseLabelSerie("Serie6");
$DataSet->SetSerieName("18 março","Serie1");
$DataSet->SetSerieName("19 março","Serie2");
$DataSet->SetSerieName("20 março","Serie3");
$DataSet->SetSerieName("21 março","Serie4");
$DataSet->SetSerieName("22 março","Serie5");

// Initialise the graph
$Test = new pChart(800,450);
$Test->setFontProperties("Fonts/tahoma.ttf",9);
$Test->setGraphArea(40,40,700,260);
$Test->drawGraphArea(255,255,255,TRUE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,0,0,0,TRUE,0,2,TRUE);
$Test->drawGrid(4,TRUE,200,200,200,50);

// Draw the bar graph
$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,100);

// Finish the graph
$Test->setFontProperties("Fonts/tahoma.ttf",9);
$Test->drawLegend(710,35,$DataSet->GetDataDescription(),255,255,255,100,100,100);
$Test->setFontProperties("Fonts/tahoma.ttf",10);
$Test->drawTitle(50,22,"Example 12",50,50,50,585);
$Test->Stroke("example12.png");

?>