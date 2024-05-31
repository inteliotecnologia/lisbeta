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

	$result = mysql_query("select distinct rh_enderecos.bairro, rh_enderecos.id_cidade
						  				from rh_funcionarios, pessoas, rh_carreiras, rh_enderecos
										where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										and   rh_carreiras.atual = '1'
										and   rh_funcionarios.id_pessoa = rh_enderecos.id_pessoa
										and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
										and   rh_funcionarios.status_funcionario = '1'
										". $str ."
										order by rh_enderecos.bairro asc
										") or die(mysql_error());
	$linhas_bairros= mysql_num_rows($result);
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
	$pdf->Cell(0, 0.75, "RELATÓRIO DE FUNCIONÁRIOS", 0, 1, 'R');
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	$pdf->Cell(0, 0.6, "POR BAIRRO", 0, 1, 'R');
	$pdf->Ln();$pdf->Ln();
	
	$pdf->SetFont('ARIAL_N_NEGRITO', '', 10);
	
	while ($rs= mysql_fetch_object($result)) {
		
		$result_fun= mysql_query("select * from rh_funcionarios, pessoas, rh_carreiras, rh_enderecos
											where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
											and   rh_carreiras.atual = '1'
											and   rh_funcionarios.id_pessoa = rh_enderecos.id_pessoa
											and   rh_funcionarios.id_pessoa = pessoas.id_pessoa
											and   rh_funcionarios.status_funcionario <> '2'
											and   rh_funcionarios.status_funcionario = '1'
											and   rh_enderecos.bairro = '". $rs->bairro ."'
											and   rh_enderecos.id_cidade = '". $rs->id_cidade ."'
											". $str ."
											order by pessoas.sexo asc, pessoas.nome_rz asc
											") or die(mysql_error());
		
		$linhas_funcionarios_bairros= mysql_num_rows($result_fun);
		
		$percentual= (($linhas_funcionarios_bairros*100)/$linhas_total_funcionarios);
		
		$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
		$pdf->Cell(0, 0.6, $rs->bairro ." (". pega_cidade($rs->id_cidade) .") - ". fnumf($percentual) ."%", 0, 1, 'L', 0);
		$pdf->Ln();
		
		$i=0;
		while ($rs_fun= mysql_fetch_object($result_fun)) {
			$j= $i+1;
			
			if (($i%2)==0) $fill= 0;
			else $fill= 1;
			
			/*$pdf->Cell(1.5, 0.6, $rs_fun->num_func, 1, 0, 'C', $fill);
			$pdf->Cell(0, 0.6, " ". $rs_fun->nome_rz, 1, 1, 'L', $fill);
			*/
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.75, 0.35, "NOME:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(8.25, 0.35, $rs_fun->nome_rz, 0, 0, 'L');
			
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.3, 0.35, "TELEFONE:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(0, 0.35, $rs_fun->telefone, 0, 1, 'L');
			
			
			//-------------------------------------------------------------------------------------------------
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.75, 0.35, "SEXO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.7, 0.35, strtoupper(pega_sexo($rs_fun->sexo)), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.6, 0.35, "CPF:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(2, 0.35, $rs_fun->cpf_cnpj, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.6, 0.35, "ESTADO CIVIL:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(2.3, 0.35, strtoupper(pega_estado_civil($rs_fun->estado_civil)), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(3.05, 0.35, "Nº CARTEIRA PROFISSIONAL:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.5, 0.35, $rs_fun->ctps, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.85, 0.35, "SÉRIE:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.2, 0.35, $rs_fun->serie_ctps, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.45, 0.35, "UF:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1, 0.35, pega_uf($rs_fun->id_uf_ctps), 0, 1, 'L');
			
			//-------------------------------------------------------------------------------------------------
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.85, 0.35, "DEPARTAMENTO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(3.2, 0.35, $rs_fun->departamento, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.9, 0.35, "CARGO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(3, 0.35, pega_cargo($rs_fun->id_cargo), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.5, 0.35, "RG:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.5, 0.35, $rs_fun->rg_ie, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.25, 0.35, "ORG. EXP.:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.3, 0.35, $rs_fun->org_exp_rg, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.85, 0.35, "UF RG:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.3, 0.35, pega_uf($rs_fun->uf_rg), 0, 1, 'L');
			
			//-------------------------------------------------------------------------------------------------
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(2.5, 0.35, "DATA DE NASCIMENTO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(2.55, 0.35, desformata_data($rs_fun->data_nasc), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(2.25, 0.35, "DATA DE ADMISSÃO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.65, 0.35, pega_data_admissao($rs_fun->id_funcionario), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.5, 0.35, "PIS:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.65, 0.35, $rs_fun->pis, 0, 1, 'L');
			
			//-------------------------------------------------------------------------------------------------
			
			/*
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1, 0.35, "BANCO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(4.05, 0.35, pega_banco($rs_fun->id_banco), 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.1, 0.35, "AGÊNCIA:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(2, 0.35, $rs_fun->agencia, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.9, 0.35, "CONTA:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.65, 0.35, $rs_fun->conta, 0, 0, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(0.5, 0.35, "OP:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(1.65, 0.35, $rs_fun->operacao, 0, 1, 'L');
			*/
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 7);
			$pdf->Cell(1.75, 0.35, "ENDEREÇO:", 0, 0, 'L');
			$pdf->SetFont('ARIALNARROW', '', 7);
			$pdf->Cell(12, 0.35, $rs_fun->rua .", ". $rs_fun->numero .". ". $rs_fun->complemento ." . ". $rs_fun->bairro . " / ". pega_cidade($rs_fun->id_cidade), 0, 1, 'L');
			
			$pdf->Cell(0, 0.17, "", 'B', 1, 'L');
			$pdf->Cell(0, 0.17, "", 0, 1, 'L');
						
			$i++;
		}
		$pdf->Ln();
		
		
	}
	
	$pdf->Ln();
	
	$pdf->AliasNbPages(); 
	$pdf->Output("funcionario_bairro_relatorio_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>