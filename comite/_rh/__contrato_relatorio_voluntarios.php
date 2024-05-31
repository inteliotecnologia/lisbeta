<?
require_once("conexao.php");
require_once("funcoes.php");

$_SESSION["id_empresa_atendente2"]= 4;

if (pode("rh", $_SESSION["permissao"])) {
	define('FPDF_FONTPATH','includes/fpdf/font/');
	require("includes/fpdf/fpdf.php");
	require("includes/fpdf/modelo_retrato_seco.php");
	
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
		
	$result_empresa= mysql_query("select * from  pessoas, rh_enderecos, empresas, cidades, ufs
									where empresas.id_empresa = '". $_SESSION["id_empresa"] ."'
									and   empresas.id_pessoa = pessoas.id_pessoa
									and   pessoas.id_pessoa = rh_enderecos.id_pessoa
									and   rh_enderecos.id_cidade = cidades.id_cidade
									and   cidades.id_uf = ufs.id_uf
									") or die(mysql_error());
	$rs_empresa= mysql_fetch_object($result_empresa);
			
	if ($_GET["id_funcionario"]!="") $str2 .= " and   rh_carreiras.id_funcionario= '". $_GET["id_funcionario"] ."' ";
	if ($_GET["id_departamento"]!="") $str2 .= " and   rh_carreiras.id_departamento= '". $_GET["id_departamento"] ."' ";
	if ($_GET["id_turno"]!="") $str2 .= " and   rh_carreiras.id_turno= '". $_GET["id_turno"] ."' ";
	
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
								
								AND   rh_funcionarios.oficial= '2'
								". $str2 ."
								order by pessoas.nome_rz asc
								") or die(mysql_error());
	$linhas= mysql_num_rows($result);
		
	$i=0;
	while ($rs= mysql_fetch_object($result)) {
		
		$pdf->AddPage();
			
		$pdf->SetXY(2,1.75);
		
		$pdf->SetFont('ARIAL', 'B', 12);
		$pdf->Cell(0, 0.5, "CADASTRO DE COLABORADOR - ATIVIDADE VOLUNTÁRIA", 0 , 1, 'C');
		
		$pdf->Ln();
		
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->MultiCell(0, 0.5, "(Atividade voluntária, pessoal e direta em apoio à candidatura/partido político/coligação, sem remuneração e sem estimativa não constituindo objeto de contabilidade eleitoral nos termos do §10º, art.30 da Resolução TSE n.º 23.376/2012.)");
		
		$pdf->Ln();
		
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(3, 0.5, "VOLUNTÁRIO:", 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "NOME:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, $rs->nome_rz, 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "RG:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs->rg_ie, 0 , 0);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(1.25, 0.5, "CPF:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs->cpf_cnpj, 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "BAIRRO:", 0, 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs->bairro, 0, 0);
					
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(1.25, 0.5, "CEP:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, $rs->cep, 0, 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "TELEFONE:", 0, 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs->telefone, 0, 0);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "CIDADE/UF:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, pega_cidade($rs->id_cidade), 0 , 1);
		
		$pdf->Ln();
		
		$pdf->SetFont('ARIAL', '', 9);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(3, 0.5, "COLIGAÇÃO/CANDIDATO:", 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "NOME:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, $rs_empresa->nome_rz, 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "CNPJ:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, $rs_empresa->cpf_cnpj, 0 , 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "BAIRRO:", 0, 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs_empresa->bairro, 0, 0);
					
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(1.25, 0.5, "CEP:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, $rs_empresa->cep, 0, 1);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "TELEFONE:", 0, 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(8.5, 0.5, $rs_empresa->telefone, 0, 0);
		
		$pdf->SetFont('ARIAL', 'B', 9);
		$pdf->Cell(2.2, 0.5, "CIDADE/UF:", 0 , 0);
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(0, 0.5, pega_cidade($rs_empresa->id_cidade), 0 , 1);
		
		$pdf->Ln();$pdf->Ln();
		
		$pdf->MultiCell(0, 0.4, "CLÁUSULA PRIMEIRA - OBJETO: A prestação de serviços é voluntária, sem remuneração/estimativa, a COLIGAÇÃO/CANDIDATO exercendo as seguintes atividades: ". pega_cargo($rs->id_cargo));
$pdf->Ln();

         $pdf->MultiCell(0, 0.4, "CLÁUSULA SEGUNDA – PRAZO: O prazo em que será voluntário iniciará em ". pega_data_admissao($rs->id_funcionario) ." com término em 06/10/2012.");
          $pdf->Ln();$pdf->Ln();$pdf->Ln();
		
		$pdf->MultiCell(0, 0.6, "               ". ucwords(strtolower($rs_empresa->cidade)) .", ". data_extenso_param(formata_data_hifen(pega_data_admissao($rs->id_funcionario))) .".", 0 , 1);
		
		
		$pdf->Ln();$pdf->Ln();$pdf->Ln();
		
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 0);
		$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 1, 'R');
		
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(9, 0.6, "COLIGAÇÃO/CANDIDATO", 0 , 0);
		$pdf->Cell(0, 0.6, "VOLUNTÁRIO", 0 , 1);
		
		$pdf->SetFont('ARIAL', '', 7.5);
		$pdf->Cell(9, 0.6, $rs_empresa->nome_rz, 0 , 0);
		$pdf->Cell(0, 0.6, $rs->nome_rz, 0 , 0);
		
		$pdf->Ln();$pdf->Ln();
		
		/*
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(0, 0.6, "TESTEMUNHAS:", 0 , 1);
		
		$pdf->Ln();$pdf->Ln();
		
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 0);
		$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 1, 'R');
		
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(9, 0.6, "Nome:", 0 , 0);
		$pdf->Cell(0, 0.6, "Nome:", 0 , 1);
		
		$pdf->SetFont('ARIAL', '', 9);
		$pdf->Cell(9, 0.6, "CPF:", 0 , 0);
		$pdf->Cell(0, 0.6, "CPF:", 0 , 0);
		*/
		
		$pdf->Ln();
		
		$i++;
	}
	
	//$pdf->SetFont('ARIALNARROW', '', 7);
		
	//$pdf->Ln();
	
	//$pdf->AliasNbPages(); 
	$pdf->Output("recibo_relatorio_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>