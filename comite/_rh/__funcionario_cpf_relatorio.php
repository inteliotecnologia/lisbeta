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
	$pdf->SetAutoPageBreak(true, 2.25);
	$pdf->SetFillColor(210,210,210);
	$pdf->AddFont('ARIALNARROW');
	$pdf->AddFont('ARIAL_N_NEGRITO');
	$pdf->AddFont('ARIAL_N_ITALICO');
	$pdf->AddFont('ARIAL_N_NEGRITO_ITALICO');
	$pdf->SetFont('ARIALNARROW');
	
	$i=0;
	
	$pdf->AddPage();
	
	$pdf->SetXY(7,1.5);
	
	$result = mysql_query("select * from rh_funcionarios, pessoas, rh_carreiras
										where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										and   rh_carreiras.atual = '1'
										and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
										and   rh_funcionarios.status_funcionario <> '2'
										and   rh_funcionarios.status_funcionario = '1'
										AND   rh_carreiras.id_departamento = '29'
										". $str ."
										order by pessoas.nome_rz asc
										") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
	$pdf->Cell(0, 0.75, "CHAMADA", 0, 1, 'R');
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	$pdf->Cell(0, 0.5, $linhas ." FUNCION�RIOS", 0, 1, 'R');
	$pdf->Cell(0, 0.8, "DATA: ____ / ____ / _______", 0, 1, 'R');
	$pdf->Ln();
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	
	$pdf->Cell(1, 0.6, "C�D.", 1, 0, 'C', 1);
	$pdf->Cell(13, 0.6, " NOME", 1, 0, 'L', 1);
	$pdf->Cell(3, 0.6, "X", 1, 1, 'C', 1);
		
	$pdf->SetFont('ARIALNARROW', '', 9);
	$pdf->SetFillColor(240,240,240);
	
	$i=0;
	while ($rs= mysql_fetch_object($result)) {
		$j= $i+1;
		
		if (($i%2)==0) $fill= 0;
		else $fill= 1;
		
		$pdf->Cell(1, 0.6, $rs->num_func, 1, 0, 'C', $fill);
		$pdf->Cell(13, 0.6, " ". $rs->nome_rz, 1, 0, 'L', $fill);
		$pdf->Cell(3, 0.6, "", 1, 1, 'C', $fill);
					
		$i++;
	}
	
	$pdf->Ln();
	
	$pdf->AliasNbPages(); 
	$pdf->Output("funcionario_cpf_relatorio_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>