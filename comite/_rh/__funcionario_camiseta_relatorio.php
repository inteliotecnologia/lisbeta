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
	$pdf->SetAutoPageBreak(true, 2.5);
	$pdf->SetFillColor(210,210,210);
	$pdf->AddFont('ARIALNARROW');
	$pdf->AddFont('ARIAL_N_NEGRITO');
	$pdf->AddFont('ARIAL_N_ITALICO');
	$pdf->AddFont('ARIAL_N_NEGRITO_ITALICO');
	$pdf->SetFont('ARIALNARROW');
	
	$i=0;
	
	$pdf->AddPage();
	
	$pdf->SetXY(7,1.5);
	
	$result_total_funcionarios = mysql_query("select *
					from rh_funcionarios, pessoas, rh_carreiras
					where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
					and   rh_carreiras.atual = '1'
					and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
					and   rh_funcionarios.status_funcionario = '1'
					") or die(mysql_error());
	$linhas_total_funcionarios= mysql_num_rows($result_total_funcionarios);

	$result = mysql_query("select distinct rh_funcionarios.tamanho_uniforme
							from rh_funcionarios, pessoas, rh_carreiras
							where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
							and   rh_carreiras.atual = '1'
							and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
							and   rh_funcionarios.status_funcionario = '1'
										") or die(mysql_error());
	$linhas_bairros= mysql_num_rows($result);
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
	$pdf->Cell(0, 0.75, "RELATÓRIO DE COLABORADORES", 0, 1, 'R');
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	$pdf->Cell(0, 0.6, "POR TAMANHO DE CAMISETA", 0, 1, 'R');
	$pdf->Ln();
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	
	while ($rs= mysql_fetch_object($result)) {
		
		$result_fun= mysql_query("select * from rh_funcionarios, pessoas, rh_carreiras, rh_enderecos
											where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
											and   rh_carreiras.atual = '1'
											and   rh_funcionarios.id_pessoa = rh_enderecos.id_pessoa
											and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
											and   rh_funcionarios.status_funcionario <> '2'
											and   rh_funcionarios.status_funcionario = '1'
											and   rh_funcionarios.tamanho_uniforme = '". $rs->tamanho_uniforme ."'
											order by pessoas.nome_rz asc
											") or die(mysql_error());
		
		$linhas_funcionarios_bairros= mysql_num_rows($result_fun);
		
		$percentual= (($linhas_funcionarios_bairros*100)/$linhas_total_funcionarios);
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
		$pdf->Cell(0, 0.6, pega_tamanho_uniforme($rs->tamanho_uniforme) ." - ". $linhas_funcionarios_bairros ." COLABORADORES (". fnumf($percentual) ."%)", 0, 1, 'L', 0);
		$pdf->Ln();
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
		$pdf->SetFillColor(210,210,210);
		
		$pdf->Cell(12, 0.6, " NOME", 1, 0, 'L', 1);
		$pdf->Cell(0, 0.6, " CPF", 1, 1, 'L', 1);
		
		$pdf->SetFont('ARIALNARROW', '', 10);
		$pdf->SetFillColor(240,240,240);
		
		$i=0;
		while ($rs_fun= mysql_fetch_object($result_fun)) {
			$j= $i+1;
			
			if (($i%2)==0) $fill= 0;
			else $fill= 1;
			
			$pdf->Cell(12, 0.6, $rs_fun->nome_rz, 1, 0, 'L', $fill);
			$pdf->Cell(0, 0.6, " ". $rs_fun->cpf_cnpj, 1, 1, 'L', $fill);
						
			$i++;
		}
		$pdf->Ln();
	}
	
	$pdf->Ln();
	
	$pdf->AliasNbPages(); 
	$pdf->Output("funcionario_camiseta_relatorio_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>