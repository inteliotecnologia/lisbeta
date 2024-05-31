<?
require_once("conexao.php");
require_once("funcoes.php");

if ($_GET[id_funcionario]!="") $id_funcionario= $_GET[id_funcionario];
if ($_POST[id_funcionario]!="") $id_funcionario= $_POST[id_funcionario];

if ($id_funcionario!="") $_SESSION["id_empresa_atendente2"]= pega_empresa_rel($id_funcionario);
else $_SESSION["id_empresa_atendente2"]= 4;

if (pode_algum("mrhv4", $_SESSION["permissao"])) {
	define('FPDF_FONTPATH','includes/fpdf/font/');
	require("includes/fpdf/fpdf.php");
	require("includes/fpdf/modelo_retrato_rh.php");

	$result_empresa= mysql_query("select * from  pessoas, rh_enderecos, empresas, cidades, ufs
									where empresas.id_empresa = '". $_SESSION["id_empresa_atendente2"] ."'
									and   empresas.id_pessoa = pessoas.id_pessoa
									and   pessoas.id_pessoa = rh_enderecos.id_pessoa
									and   rh_enderecos.id_cidade = cidades.id_cidade
									and   cidades.id_uf = ufs.id_uf
									") or die(mysql_error());
	$rs_empresa= mysql_fetch_object($result_empresa);
		
	$pdf=new PDF("P", "cm", "A4");
	$pdf->SetMargins(2, 2, 2);
	$pdf->SetAutoPageBreak(true, 2.5);
	$pdf->SetFillColor(230,230,230);
	//$pdf->AddFont('ARIALNARROW');
	$pdf->AddFont('ARIAL_N_NEGRITO');
	$pdf->AddFont('ARIAL_N_ITALICO');
	//$pdf->AddFont('ARIAL_N_NEGRITO_ITALICO');
	//$pdf->SetFont('ARIALNARROW');
	
	$i=0;
	
	//echo $_GET["tipo"];
	
	switch($_GET["tipo"]) {
		
		//dados para admissão
		case 14:
			$result= mysql_query("select * from  pessoas, rh_funcionarios, rh_carreiras, rh_turnos, rh_cargos, rh_enderecos
								where pessoas.id_pessoa = rh_funcionarios.id_pessoa
								and   rh_carreiras.id_carreira =  '". $_GET["id_carreira"] ."'
								and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
								and   rh_carreiras.id_turno = rh_turnos.id_turno
								and   rh_carreiras.id_cargo = rh_cargos.id_cargo
								and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
								and   rh_funcionarios.id_pessoa = rh_enderecos.id_pessoa
								order by pessoas.nome_rz asc
								") or die(mysql_error());
			$rs= mysql_fetch_object($result);
			
			$pdf->AddPage();
			
			$pdf->SetXY(2,1.75);
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 14);
			$pdf->Cell(0, 0.55, "CONTRATO DE PRESTAÇÃO DE SERVIÇOS", 0 , 1, 'L');
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
			$pdf->Cell(0, 0.55, "", 0 , 1, 'R');
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', '', 9);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "CONTRATADO:", 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "NOME:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs->nome_rz, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "CPF:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs->cpf_cnpj, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "BAIRRO:", 0, 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(8.5, 0.55, $rs->bairro, 0, 0);
						
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(1.25, 0.55, "CEP:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs->cep, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "TELEFONE:", 0, 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(8.5, 0.55, $rs->telefone, 0, 0);
						
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(1.25, 0.55, "E-MAIL:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs->email, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "CIDADE:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, pega_cidade($rs->id_cidade), 0 , 1);
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', '', 9);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "CONTRATANTE:", 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "NOME:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs_empresa->nome_rz, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "CNPJ:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs_empresa->cpf_cnpj, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "BAIRRO:", 0, 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(8.5, 0.55, $rs_empresa->bairro, 0, 0);
						
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(1.25, 0.55, "CEP:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs_empresa->cep, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "TELEFONE:", 0, 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(8.5, 0.55, $rs_empresa->telefone, 0, 0);
						
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(1.25, 0.55, "E-MAIL:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, $rs_empresa->email, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(2.5, 0.55, "CIDADE:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, pega_cidade($rs_empresa->id_cidade), 0 , 1);
			
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "OBJETO DA PRESTAÇÃO DE SERVIÇOS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, 'SERVIÇOS DA PANFLETAGEM E DIVULGAÇÃO ELEITORAL', 0 , 1);
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "VALOR DA PRESTAÇÃO DE SERVIÇOS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, "R$ ". fnum($rs->val_salario), 0 , 1);
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "PERÍODO DA PRESTAÇÃO DE SERVIÇOS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			//$pdf->Cell(0, 0.55, " ", 0 , 1);
			$pdf->Ln();
			
			$pdf->MultiCell(0, 0.4, "          As partes acima nominadas e qualificadas firmam o presente CONTRATO DE PRESTAÇÃO DE SERVIÇOS, o qual, além das normas legais aplicáveis à espécie, em especial pelo artigo 100 da Lei 9.504/97, reger-se-á pelas cláusulas e estipulações a seguir enunciadas:");
$pdf->Ln();

             $pdf->MultiCell(0, 0.4, "          CLÁUSULA PRIMEIRA – OBJETO: O presente negócio jurídico tem por objeto a contratação para a realização de serviços em campanha política do CONTRATANTE, conforme especificação no preâmbulo do presente instrumento contratual.");
              $pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CLÁUSULA SEGUNDA – PRAZO: O presente contrato é celebrado por prazo determinado, tendo início, a sua vigência, na data denominada no preâmbulo do presente instrumento contratual e terminando em 07 de Outubro de 2012, data em que será considerado automaticamente rescindido, independente de comunicação judicial ou extrajudicial.");
$pdf->Ln();
            $pdf->MultiCell(0, 0.4, "          CLÁUSULA TERCEIRA – VALOR E FORMA DE PAGAMENTO: Pelos serviços contratados acordam as partes que o CONTRATANTE pagará ao CONTRATADO o valor e nas condições especificadas no preâmbulo deste instrumento contratual, mediante recibo de pagamento a ser emitido pelo CONTRATADO.");
$pdf->Ln();
           $pdf->MultiCell(0, 0.4, "          CLÁUSULA QUARTA – OBRIGAÇÕES DO CONTRATANTE: São obrigações do CONTRATANTE: (1) Fornecer as informações necessárias para o desenvolvimento dos SERVIÇOS; (2) Realizar os pagamentos na forma estabelecida.");
$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CLÁUSULA QUINTA – OBRIGAÇÕES DO CONTRATADO: O CONTRATADO possui as seguintes obrigações: (1) prestar os serviços profissionais contratados com zelo e dedicação; (2) não trabalhar para outro candidato, que concorra ao mesmo cargo; (3) trabalhar para candidatos que concorrem a outros cargos, somente com a anuência do CONTRATANTE.
");
$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CLÁUSULA SEXTA – DANOS: O CONTRATADO assume exclusiva e integral responsabilidade pela reparação de todos e quaisquer danos e/ou prejuízos que, em virtude das prestações dos serviços, por sua culpa ou dolo, forem causados ao CONTRATANTE e/ou a terceiros.");
             $pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CLÁUSULA SÉTIMA – RESCISÃO: O descumprimento, por qualquer das partes, das obrigações ora assumidas, dará motivo à rescisão do negócio jurídico.");

$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          Parágrafo único: As partes acordam que o presente contrato pode ser rescindido a qualquer momento, desde que ocorra a comunicação por escrito em (5) cinco dias.");
$pdf->Ln();

             $pdf->MultiCell(0, 0.4, "          CLÁUSULA OITAVA – LEI ELEITORAL: O CONTRATADO declara conhecer as regras que regem o pleito eleitoral de 2012 e que auxiliará no cumprimento das mesmas. Declara, ainda, que força do artigo 100 da Lei 9.504/97, sabe não haver vínculo empregatício neste presente contrato de prestação de serviços.");
$pdf->Ln();
             
             $pdf->MultiCell(0, 0.4, "          CLÁUSULA NONA – FORO: Para solução de eventuais litígios deste CONTRATO, as partes elegem o Foro da Comarca de ". pega_cidade($rs_empresa->id_cidade) .", com renúncia expressa a qualquer outro, por mais privilegiado que seja.");

$pdf->Ln();

             $pdf->MultiCell(0, 0.4, "          E assim estando justas e acordadas, as partes firmam o presente instrumento em 02 (duas) vias de igual teor e forma, juntamente com as testemunhas abaixo designadas.");

			
			$pdf->Ln();
			
			$pdf->MultiCell(0, 0.6, "               ". ucwords(strtolower($rs_empresa->cidade)) .", ". data_extenso_param(date("Y-m-d")) .".", 0 , 1);
			
			
			$pdf->Ln();$pdf->Ln();$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 0);
			$pdf->Cell(8.5, 0.6, "_________________________________________", 0 , 1, 'R');
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(9, 0.6, "CONTRATANTE", 0 , 0);
			$pdf->Cell(0, 0.6, "CONTRATADO", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(9, 0.6, $rs_empresa->nome_rz, 0 , 0);
			$pdf->Cell(0, 0.6, $rs->nome_rz, 0 , 0);
			
			$pdf->Ln();$pdf->Ln();
			
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
			
			$pdf->Ln();
			
			/*
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "SALÁRIO (EXP.):", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(2.5, 0.55, "R$ ". fnum($rs->val_salario_experiencia), 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(2, 0.55, "Nº PIS:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(0, 0.55, $rs->pis, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "RG:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(2.5, 0.55, $rs->rg_ie, 0 , 0);
			
			
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "CTPS:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(2.5, 0.55, $rs->ctps, 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(2, 0.55, "SÉRIE:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(0, 0.55, $rs->serie_ctps, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "ESTADO CIVIL:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(2.5, 0.55, pega_estado_civil($rs->estado_civil), 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(2.75, 0.55, "ESCOLARIDADE:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(4.75, 0.55, pega_escolaridade($rs->escolaridade), 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(1.75, 0.55, "Nº FILHOS:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(0, 0.55, pega_num_filhos($rs->id_funcionario), 0, 1);
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(4, 0.5, "HORÁRIOS", 0 , 1);
			$pdf->Cell(4, 0.5, "DE TRABALHO:", 0 , 0);
			
			$pdf->SetXY(5, 9);
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 9);
			$pdf->Cell(2.5, 0.4, "DIA DA SEMANA", 1 , 0, "C", 1);
			$pdf->Cell(1.6, 0.4, "ENTRADA", 1 , 0, "C", 1);
			$pdf->Cell(1.8, 0.4, "INTERVALO", 1 , 0, "C", 1);
			$pdf->Cell(1.6, 0.4, "SAÍDA", 1 , 1, "C", 1);
			
			for ($i=0; $i<=6; $i++) {
				$result_dia= mysql_query("select * from rh_turnos_horarios
											where id_turno = '". $rs->id_turno ."'
											and   id_dia = '$i'
											");
				$rs_dia= mysql_fetch_object($result_dia);
				
				if (($i%2)==0) $fill= 1;
				else $fill= 0;
				
				$pdf->SetX(5);
				$pdf->SetFont('ARIAL_N_NEGRITO', '', 8);
				$pdf->Cell(2.5, 0.4, strtoupper(traduz_dia($i)), 1 , 0, "C", $fill);
				$pdf->SetFont('ARIAL', '', 8);
				$pdf->Cell(1.6, 0.4, substr($rs_dia->entrada, 0, 5), 1 , 0, "C", $fill);
				$pdf->Cell(1.8, 0.4, pega_detalhes_intervalo($rs->id_intervalo, $i, 0), 1 , 0, "C", $fill);
				$pdf->Cell(1.6, 0.4, substr($rs_dia->saida, 0, 5), 1 , 1, "C", $fill);
				
			}
			
			$pdf->SetXY(2, 13);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "ENDEREÇO:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(11, 0.55, $rs->rua, 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(0.5, 0.55, "Nº:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(1, 0.55, $rs->numero, 0 , 1);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.55, "COMPLEMENTO:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(0, 0.55, $rs->complemento, 0, 1);
			
			
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(2, 0.55, "CIDADE/UF:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(3, 0.55, pega_cidade($rs->id_cidade), 0 , 1);
			$pdf->Ln();
			
			$result_tel= mysql_query("select tel_contatos_telefones.* from tel_contatos, tel_contatos_telefones
										where tel_contatos.id_pessoa = '". $rs->id_pessoa ."'
										and   tel_contatos.id_contato = tel_contatos_telefones.id_contato
										");
			while ($rs_tel= mysql_fetch_object($result_tel)) {
				$pdf->SetFont('ARIAL', 'B', 10);
				$pdf->Cell(3, 0.55, "TEL. ". strtoupper(pega_tipo_telefone($rs_tel->tipo)) .":", 0 , 0);
				$pdf->SetFont('ARIAL', '', 10);
				$pdf->Cell(1, 0.55, $rs_tel->telefone, 0 , 1);
			}
			
			$pdf->Ln();
			
			$result_vt= mysql_query("select * from rh_vt, rh_vt_linhas
										where rh_vt.id_empresa = '". $_SESSION["id_empresa"] ."'
										and   rh_vt.id_linha = rh_vt_linhas.id_linha
										and   rh_vt.id_funcionario = '". $rs->id_funcionario ."'
										order by  rh_vt.trajeto desc, rh_vt_linhas.id_linha asc
										") or die(mysql_error());
			$linhas_vt= mysql_num_rows($result_vt);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.5, "VALE", 0 , 0);
			
			if ($linhas_vt>0) $usa_vt= "SIM"; else $usa_vt= "NÃO";
			
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(6, 0.5, $usa_vt, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.5, "TRANSPORTE:", 0 , 0);
			
			$pdf->Ln();$pdf->Ln();$pdf->Ln();
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
			$pdf->Cell(3, 0.8, "DOCUMENTOS NECESSÁRIOS PARA ADMISSÃO:", 0, 1);
			
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(3, 0.5, "    1. UMA FOTO 3X4;", 0, 1);
			$pdf->Cell(3, 0.5, "    2. CARTEIRA PROFISSIONAL (CTPS);", 0, 1);
			$pdf->Cell(3, 0.5, "    3. XEROX RG (IDENTIDADE) E DO CPF;", 0, 1);
			$pdf->Cell(3, 0.5, "    4. XEROX TITULO ELEITORAL;", 0, 1);
			$pdf->Cell(3, 0.5, "    5. XEROX DA CARTEIRA DE MOTORISTA;", 0, 1);
			$pdf->Cell(3, 0.5, "    6. COMPROVANTE DE RESIDÊNCIA (COM END. COMPLETO);", 0, 1);
			$pdf->Cell(3, 0.5, "    7. CERTIFICADO DE RESERVISTA;", 0, 1);
			$pdf->Cell(3, 0.5, "    8. ATESTADO MÉDICO ADMISSIONAL;", 0, 1);
			$pdf->Cell(3, 0.5, "    9. CERTIDÃO DE NASCIMENTO DOS FILHOS MENORES DE 14 ANOS;", 0, 1);
			*/
			
		break;
		
	}
	
	
	$pdf->AliasNbPages();
	$pdf->Output("documento_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>	
