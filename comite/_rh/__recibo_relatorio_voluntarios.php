<?
require_once("conexao.php");
require_once("funcoes.php");

$_SESSION["id_empresa_atendente2"]= 4;

if (pode("rh", $_SESSION["permissao"])) {
	define('FPDF_FONTPATH','includes/fpdf/font/');
	require("includes/fpdf/fpdf.php");
	require("includes/fpdf/modelo_retrato_rh.php");
	
	$pdf=new PDF("P", "cm", "A4");
	$pdf->SetMargins(2, 3, 2);
	$pdf->SetAutoPageBreak(true, 3);
	$pdf->SetFillColor(210,210,210);
	$pdf->AddFont('ARIALNARROW');
	$pdf->AddFont('ARIAL_N_NEGRITO');
	$pdf->AddFont('ARIAL_N_ITALICO');
	$pdf->AddFont('ARIAL_N_NEGRITO_ITALICO');
	$pdf->SetFont('ARIALNARROW');
	
	$i=0;
			
	if ($_GET["id_departamento"]!="") $str= " and   rh_carreiras.id_departamento= '". $_GET["id_departamento"] ."' ";
	if ($_GET["id_turno"]!="") $str= " and   rh_carreiras.id_turno= '". $_GET["id_turno"] ."' ";
	if ($_GET["status_funcionario"]!="") $status_funcionario= $_GET["status_funcionario"];
	else $status_funcionario= 1;
	
	$result= mysql_query("select *, pessoas.data as data_nasc
								from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras, rh_departamentos, rh_turnos
								where pessoas.id_pessoa = rh_funcionarios.id_pessoa
								and   pessoas.tipo = 'f'
								and   rh_enderecos.id_pessoa = pessoas.id_pessoa
								and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
								and   rh_carreiras.atual = '1'
								and   rh_carreiras.id_departamento = rh_departamentos.id_departamento
								and   rh_carreiras.id_turno = rh_turnos.id_turno
								and   rh_departamentos.id_empresa = '". $_SESSION["id_empresa"] ."'
								and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
								and   rh_funcionarios.status_funcionario <> '2'
								and   (rh_carreiras.id_departamento = '29' or rh_carreiras.id_departamento = '31')
								AND   rh_funcionarios.oficial= '2'
								". $str2 ."
								order by rh_carreiras.id_departamento desc, pessoas.nome_rz asc
								") or die(mysql_error());
	$linhas= mysql_num_rows($result);
		
	$i=0;
	while ($rs= mysql_fetch_object($result)) {
		
		
		$pdf->AddPage();
		
		$pdf->SetXY(2,1.75);
			
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 14);
		$pdf->Cell(0, 0.55, "RECIBO", 0 , 1, 'C');
				
		$pdf->Ln();
		
		$pdf->SetFont('ARIAL', '', 10);
		
		$pdf->MultiCell(0, 0.4, "Recebi do Sr(a). ____________________________________________ brasileiro(a), residente e domiciliado(a) na rua _______________________,  n°________, cidade de _________________, Estado ___________________ a quantia de R$ (_________,___), ________________________ _________________________________________( valor por extenso), referente à venda de ___________________________________, de minha propriedade, dando-lhe por este recibo a devida quitação.");
$pdf->Ln();
		
		$i++;
	}
	
	//$pdf->SetFont('ARIALNARROW', '', 7);
		
	$pdf->Ln();
	
	$pdf->AliasNbPages(); 
	$pdf->Output("recibo_relatorio_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>