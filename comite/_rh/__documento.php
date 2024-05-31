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
		
		//dados para admiss�o
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
			$pdf->Cell(0, 0.55, "CONTRATO DE PRESTA��O DE SERVI�OS", 0 , 1, 'L');
			
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
			$pdf->Cell(3, 0.55, "OBJETO DA PRESTA��O DE SERVI�OS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, 'SERVI�OS DA PANFLETAGEM E DIVULGA��O ELEITORAL', 0 , 1);
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "VALOR DA PRESTA��O DE SERVI�OS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(0, 0.55, "R$ ". fnum($rs->val_salario), 0 , 1);
			
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 9);
			$pdf->Cell(3, 0.55, "PER�ODO DA PRESTA��O DE SERVI�OS:", 0 , 1);
			
			$pdf->SetFont('ARIAL', '', 9);
			//$pdf->Cell(0, 0.55, " ", 0 , 1);
			$pdf->Ln();
			
			$pdf->MultiCell(0, 0.4, "          As partes acima nominadas e qualificadas firmam o presente CONTRATO DE PRESTA��O DE SERVI�OS, o qual, al�m das normas legais aplic�veis � esp�cie, em especial pelo artigo 100 da Lei 9.504/97, reger-se-� pelas cl�usulas e estipula��es a seguir enunciadas:");
$pdf->Ln();

             $pdf->MultiCell(0, 0.4, "          CL�USULA PRIMEIRA � OBJETO: O presente neg�cio jur�dico tem por objeto a contrata��o para a realiza��o de servi�os em campanha pol�tica do CONTRATANTE, conforme especifica��o no pre�mbulo do presente instrumento contratual.");
              $pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CL�USULA SEGUNDA � PRAZO: O presente contrato � celebrado por prazo determinado, tendo in�cio, a sua vig�ncia, na data denominada no pre�mbulo do presente instrumento contratual e terminando em 07 de Outubro de 2012, data em que ser� considerado automaticamente rescindido, independente de comunica��o judicial ou extrajudicial.");
$pdf->Ln();
            $pdf->MultiCell(0, 0.4, "          CL�USULA TERCEIRA � VALOR E FORMA DE PAGAMENTO: Pelos servi�os contratados acordam as partes que o CONTRATANTE pagar� ao CONTRATADO o valor e nas condi��es especificadas no pre�mbulo deste instrumento contratual, mediante recibo de pagamento a ser emitido pelo CONTRATADO.");
$pdf->Ln();
           $pdf->MultiCell(0, 0.4, "          CL�USULA QUARTA � OBRIGA��ES DO CONTRATANTE: S�o obriga��es do CONTRATANTE: (1) Fornecer as informa��es necess�rias para o desenvolvimento dos SERVI�OS; (2) Realizar os pagamentos na forma estabelecida.");
$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CL�USULA QUINTA � OBRIGA��ES DO CONTRATADO: O CONTRATADO possui as seguintes obriga��es: (1) prestar os servi�os profissionais contratados com zelo e dedica��o; (2) n�o trabalhar para outro candidato, que concorra ao mesmo cargo; (3) trabalhar para candidatos que concorrem a outros cargos, somente com a anu�ncia do CONTRATANTE.
");
$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CL�USULA SEXTA � DANOS: O CONTRATADO assume exclusiva e integral responsabilidade pela repara��o de todos e quaisquer danos e/ou preju�zos que, em virtude das presta��es dos servi�os, por sua culpa ou dolo, forem causados ao CONTRATANTE e/ou a terceiros.");
             $pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          CL�USULA S�TIMA � RESCIS�O: O descumprimento, por qualquer das partes, das obriga��es ora assumidas, dar� motivo � rescis�o do neg�cio jur�dico.");

$pdf->Ln();
             $pdf->MultiCell(0, 0.4, "          Par�grafo �nico: As partes acordam que o presente contrato pode ser rescindido a qualquer momento, desde que ocorra a comunica��o por escrito em (5) cinco dias.");
$pdf->Ln();

             $pdf->MultiCell(0, 0.4, "          CL�USULA OITAVA � LEI ELEITORAL: O CONTRATADO declara conhecer as regras que regem o pleito eleitoral de 2012 e que auxiliar� no cumprimento das mesmas. Declara, ainda, que for�a do artigo 100 da Lei 9.504/97, sabe n�o haver v�nculo empregat�cio neste presente contrato de presta��o de servi�os.");
$pdf->Ln();
             
             $pdf->MultiCell(0, 0.4, "          CL�USULA NONA � FORO: Para solu��o de eventuais lit�gios deste CONTRATO, as partes elegem o Foro da Comarca de ". pega_cidade($rs_empresa->id_cidade) .", com ren�ncia expressa a qualquer outro, por mais privilegiado que seja.");

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
			$pdf->Cell(3, 0.55, "SAL�RIO (EXP.):", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(2.5, 0.55, "R$ ". fnum($rs->val_salario_experiencia), 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(2, 0.55, "N� PIS:", 0 , 0);
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
			$pdf->Cell(2, 0.55, "S�RIE:", 0 , 0);
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
			$pdf->Cell(1.75, 0.55, "N� FILHOS:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(0, 0.55, pega_num_filhos($rs->id_funcionario), 0, 1);
			$pdf->Ln();
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(4, 0.5, "HOR�RIOS", 0 , 1);
			$pdf->Cell(4, 0.5, "DE TRABALHO:", 0 , 0);
			
			$pdf->SetXY(5, 9);
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 9);
			$pdf->Cell(2.5, 0.4, "DIA DA SEMANA", 1 , 0, "C", 1);
			$pdf->Cell(1.6, 0.4, "ENTRADA", 1 , 0, "C", 1);
			$pdf->Cell(1.8, 0.4, "INTERVALO", 1 , 0, "C", 1);
			$pdf->Cell(1.6, 0.4, "SA�DA", 1 , 1, "C", 1);
			
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
			$pdf->Cell(3, 0.55, "ENDERE�O:", 0 , 0);
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(11, 0.55, $rs->rua, 0 , 0);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(0.5, 0.55, "N�:", 0 , 0);
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
			
			if ($linhas_vt>0) $usa_vt= "SIM"; else $usa_vt= "N�O";
			
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(6, 0.5, $usa_vt, 0, 1);
			
			$pdf->SetFont('ARIAL', 'B', 10);
			$pdf->Cell(3, 0.5, "TRANSPORTE:", 0 , 0);
			
			$pdf->Ln();$pdf->Ln();$pdf->Ln();
			
			$pdf->SetFont('ARIAL_N_NEGRITO', '', 12);
			$pdf->Cell(3, 0.8, "DOCUMENTOS NECESS�RIOS PARA ADMISS�O:", 0, 1);
			
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(3, 0.5, "    1. UMA FOTO 3X4;", 0, 1);
			$pdf->Cell(3, 0.5, "    2. CARTEIRA PROFISSIONAL (CTPS);", 0, 1);
			$pdf->Cell(3, 0.5, "    3. XEROX RG (IDENTIDADE) E DO CPF;", 0, 1);
			$pdf->Cell(3, 0.5, "    4. XEROX TITULO ELEITORAL;", 0, 1);
			$pdf->Cell(3, 0.5, "    5. XEROX DA CARTEIRA DE MOTORISTA;", 0, 1);
			$pdf->Cell(3, 0.5, "    6. COMPROVANTE DE RESID�NCIA (COM END. COMPLETO);", 0, 1);
			$pdf->Cell(3, 0.5, "    7. CERTIFICADO DE RESERVISTA;", 0, 1);
			$pdf->Cell(3, 0.5, "    8. ATESTADO M�DICO ADMISSIONAL;", 0, 1);
			$pdf->Cell(3, 0.5, "    9. CERTID�O DE NASCIMENTO DOS FILHOS MENORES DE 14 ANOS;", 0, 1);
			*/
			
		break;
		
	}
	
	
	$pdf->AliasNbPages();
	$pdf->Output("documento_". date("d-m-Y_H:i:s") .".pdf", "I");
	
	$_SESSION["id_empresa_atendente2"]= "";
}
?>	
