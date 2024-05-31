<?
require_once("conexao.php");
require_once("funcoes.php");

$_SESSION["id_empresa_atendente2"]= 4;

define('FPDF_FONTPATH','includes/fpdf/font/');
require("includes/fpdf/fpdf.php");
require("includes/fpdf/modelo_retrato_rh.php");
	
	$pdf=new PDF("P", "cm", "A4");
	$pdf->SetMargins(2, 3, 2);
	$pdf->SetAutoPageBreak(true, 3);
	$pdf->SetFillColor(230,230,230);
	$pdf->AddFont('ARIALNARROW');
	$pdf->AddFont('ARIAL_N_NEGRITO');
	$pdf->AddFont('ARIAL_N_ITALICO');
	$pdf->AddFont('ARIAL_N_NEGRITO_ITALICO');
	$pdf->SetFont('ARIALNARROW');
	
	$i=0;
	
	$pdf->AddPage();
	
	$pdf->SetXY(7,1.75);
	
		
	if ($_GET["id_departamento"]!="") $str2.= " and   rh_carreiras.id_departamento= '". $_GET["id_departamento"] ."' ";
	else $str2.= " and   (rh_carreiras.id_departamento = '29' or rh_carreiras.id_departamento = '31') ";
	
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
								/*and   rh_funcionarios.status_funcionario = '1'
								
								AND   rh_funcionarios.oficial= '1' */
								". $str2 ."
								order by rh_carreiras.id_departamento desc, pessoas.nome_rz asc
								") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 14);
	$pdf->Cell(0, 0.6, "RELATÓRIO DE ". $linhas ." COLABORADORES", 0 , 1, 'L');
	
	//$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
	//$pdf->Cell(0, 0.7, "", 0 , 1, 'R');
	
	$pdf->Ln();
		
	
								
	while ($rs= mysql_fetch_object($result)) {
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(0.75, 0.35, "NOME:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(10, 0.35, $rs->nome_rz, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(2, 0.35, "TÍTULO ELEITOR:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(0, 0.35, $rs->tit_eleitor, 0, 1, 'L');
		
		//-------------------------------------------------------------------------------------------------
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(0.75, 0.35, "SEXO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.7, 0.35, strtoupper(pega_sexo($rs->sexo)), 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(0.6, 0.35, "CPF:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(2, 0.35, $rs->cpf_cnpj, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(0.5, 0.35, "RG:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.5, 0.35, $rs->rg_ie, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1.25, 0.35, "ORG. EXP.:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.3, 0.35, $rs->org_exp_rg, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(0.85, 0.35, "UF RG:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1, 0.35, pega_uf($rs->uf_rg), 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1.25, 0.35, "TELEFONE:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.3, 0.35, $rs->telefone, 0, 1, 'L');
		
		//-------------------------------------------------------------------------------------------------
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(2.5, 0.35, "DATA DE NASCIMENTO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(2.55, 0.35, desformata_data($rs->data_nasc), 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1, 0.35, "SETOR:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(2.5, 0.35, $rs->departamento, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1, 0.35, "CARGO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(2.5, 0.35, pega_cargo($rs->id_cargo), 0, 0, 'L');
		
		//-------------------------------------------------------------------------------------------------
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(2.25, 0.35, "DATA DE ADMISSÃO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.65, 0.35, pega_data_admissao($rs->id_funcionario), 0, 1, 'L');
				
		//-------------------------------------------------------------------------------------------------
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1.4, 0.35, "ENDEREÇO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(8.2, 0.35, $rs->rua .", ". $rs->numero .". ". $rs->complemento .". ". $rs->cep, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1.0, 0.35, "BAIRRO:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(2.65, 0.35, $rs->bairro, 0, 0, 'L');
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
		$pdf->Cell(1, 0.35, "CIDADE:", 0, 0, 'L');
		$pdf->SetFont('ARIALNARROW', '', 7);
		$pdf->Cell(1.65, 0.35, pega_cidade($rs->id_cidade), 0, 1, 'L');
		
		
		
		$pdf->Cell(0, 0.17, "", 'B', 1, 'L');
		$pdf->Cell(0, 0.17, "", 0, 1, 'L');
		
	}
	
	$pdf->AliasNbPages(); 
	$pdf->Output("funcionario_lista_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
	
?>