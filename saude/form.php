<?
if (!$conexao)
	require_once("conexao.php");

header("Content-type: text/html; charset=iso-8859-1", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
		<title>Lisbeta</title>
		</head>
		<body>'; 

if (isset($_GET["formPostoEmular"])) {
	if ( ($_POST["id_posto_em"]!="") || ($_POST["id_cidade_em"]!="") ) {
		if ($_SESSION["tipo_usuario_sessao"]=='a') {
			$_SESSION["id_posto_sessao"]= $_POST["id_posto_em"];
			
			$_SESSION["id_cidade_sessao"]= $_POST["id_cidade_em"];
			$_SESSION["id_uf_sessao"]= pega_id_uf($_POST["id_cidade_em"]);
			
			$id_cidade_pref= $_POST["id_cidade_em"];
			$_SESSION["id_cidade_pref"]= $id_cidade_pref;
			
			$id_uf_pref= $_SESSION["id_uf_sessao"];
			$_SESSION["id_uf_pref"]= $id_uf_pref;

			if ($_POST["id_posto_em"]=="")
				session_unregister("id_posto_sessao");
			else {
				session_unregister("id_cidade_sessao");
				session_unregister("id_uf_sessao");
			}
		}
		//se eh o usuario da cidade
		else {
		
			/*echo "<script language='javascript'>
						alert('0');
						</script>
						";*/
			
			//se está vindo do formulario pra voltar pra cidade
			//e
			//se estiver emulado um posto e a cidade estiver anulada
			if (($_POST["id_posto_em"]=="") && ($_SESSION["id_cidade_sessao"]=="") && ($_SESSION["id_posto_sessao"]!="")) {
				$_SESSION["id_cidade_sessao"]= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
				
				$_SESSION["trocando"]= "";
				$_SESSION["id_posto_sessao"]= "";
			}
			//se a cidade estiver ativa
			//e
			//quiser emular um posto
			if (($_POST["id_posto_em"]!="") && (($_SESSION["id_cidade_sessao"]!="") || ($_SESSION["id_posto_sessao"]!="")) && (($_SESSION["id_posto_sessao"]=="") || ($_SESSION["id_cidade_sessao"]==""))) {
				$_SESSION["id_cidade_sessao"]= "";
				
				$_SESSION["trocando"]= 1;
				$_SESSION["id_posto_sessao"]= $_POST["id_posto_em"];
			}
		}
		echo "<script language=\"javascript\">recarregaPaginaAtual();</script>";
	}
	
	$pagina= "menu";
	require_once("index2.php");
}


/* ----------------------------------------------- PRODUÇÃO --------------------------------------- */

if (isset($_GET["formProducaoPeriodo"])) {
	$pagina= "_producao/producao";
	require_once("index3.php");
}

/* ----------------------------------------------- AJUDA --------------------------------------- */

if (isset($_GET["formContato"])) {
	$enviar_email= @mail("jaisonn@gmail.com, rosivetenie@yahoo.com.br, lisbeta@lisbeta.net", "Lisbeta.Sistema | Contato",
							"O usuário <b>". $_SESSION["nome_usuario_sessao"] ."</b> enviou a seguinte mensagem em <b>". date("H:i:s d/m/Y") ."</b>: <br />
							<br />
							<b>IP:</b> ". $REMOTE_ADDR ." <br />
							<b>Nome:</b> ". $_POST["nome"] ." <br />
							<b>E-mail:</b> ". $_POST["email"] ." <br />
							<b>Telefone:</b> ". $_POST["telefone"] ." <br />
							<b>Cidade:</b> ". $_POST["cidade"] ." <br />
							<b>Tipo de contato:</b> ". $_POST["tipo_contato"] ." <br />
							<b>Área do contato:</b> ". $_POST["area_contato"] ." <br />
							<b>Mensagem:</b> ". nl2br($_POST["mensagem"]) ."
							<br /><br />
							---------------------
							<br /><br />
							Lisbeta.Saúde<br />
							<a href=\"http://www.lisbeta.net\">http://www.lisbeta.net</a>
							",
							"From: ". $_POST["nome"] ." <". $_POST["email"] ."> \nContent-type: text/html\n"); 
	
	$msg= 1;
	if ($enviar_email)
		$msg=0;
	
	$pagina= "_ajuda/contato";
	require_once("index3.php");
}

/* -------------------------------------------- TFDs -------------------------------------------------- */

if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("t", $_SESSION["permissao"])) ) {
	if (isset($_GET["formSolicitacaoTfdInserir"])) {
		if ( ($_POST["id_pessoa"]!="") && ($_POST["id_cidade2"]!="") && ($_POST["id_finalidade"]!="") && ($_POST["protocolo"]!="")) {
			if ($_POST["protocolo"]=="1")
				$situacao_solicitacao= "1";
			else
				$situacao_solicitacao= "4";
			
			$data_solicitacao= formata_data($_POST["data_solicitacao"]);

			$result= mysql_query("insert into tfds_solicitacoes (id_cidade, id_interno, protocolo, id_pessoa, id_cidade_tfd, id_finalidade,
										data_solicitacao, observacoes, situacao_solicitacao, id_usuario, data_operacao)
										values
										('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_interno"] ."', '". $_POST["protocolo"] ."',
										'". $_POST["id_pessoa"] ."', '". $_POST["id_cidade2"] ."', '". $_POST["id_finalidade"] ."',
										'". $data_solicitacao ."', '". $_POST["observacoes"] ."', '$situacao_solicitacao',
										'". $_SESSION["id_usuario_sessao"] ."', '". date("YmdHis") ."' ) ") or die(mysql_error());
		}
		if ($result)
			$msg= 0;
		else
			$msg= 1;
		
		$pagina= "_tfd/tfd_solicitacao_inserir";
		require_once("index3.php");
	}
	if (isset($_GET["formSolicitacaoTfdEditar"])) {
		if ( ($_POST["id_solicitacao"]!="") && ($_POST["situacao_solicitacao"]!="") ) {
			
			$data_atividade= formata_data($_POST["data_atividade"]);
			
			$hora_atividade= $_POST["hora_atividade"];
			if (strlen($hora_atividade)==5)
				$hora_atividade .= ":00";
			
			$hora_atividade= formata_hora($hora_atividade);
			
			//@mail("jaisonn@gmail.com", "teste", $data_atividade . $hora_atividade, "");
			
			$result= mysql_query("update tfds_solicitacoes set situacao_solicitacao= '". $_POST["situacao_solicitacao"] ."',
										registro = '". $_POST["registro"] ."',
										id_entidade= '". $_POST["id_entidade"] ."',
										data_atividade= '". $data_atividade . $hora_atividade ."'
										where id_solicitacao = '". $_POST["id_solicitacao"] ."'
										and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ") or die(mysql_error());
		}
		if ($result)
			$msg= 0;
		else
			$msg= 1;
		
		$pagina= "_tfd/tfd_solicitacao_ver";
		require_once("index3.php");
	}
	if (isset($_GET["formTfdInserir"])) {
		if ( ($_POST["id_motorista"]!="") && ($_POST["id_veiculo"]!="")) {
			
			$var= 0;
			inicia_transacao();
			
			$data_partida= formata_data($_POST["data_partida"]);
			
			$result1= mysql_query("insert into tfds (id_cidade, id_motorista, id_veiculo, id_cidade_tfd, data_partida, data_operacao, id_usuario)
										values ('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_motorista"] ."',
										'". $_POST["id_veiculo"] ."', '". $_POST["id_cidade_tfd"] ."', '". $data_partida ."',
										'". date("YmdHis") ."', '". $_SESSION["id_usuario_sessao"] ."' ) ");
			
			if (!$result1) $var++;
			
			$id_tfd= mysql_insert_id();
			
			$num_pessoas= count($_POST["id_pessoa"]);
			//echo "num_pessoas: ". $num_pessoas;
			
			for ($i=0; $i<$num_pessoas; $i++) {
				//echo "Passando pela $i vez aki! <BR>";
				//se pessoa nao estiver vazio
				//e
				//se for paciente e a solicitacao de tfd for valida
				//
				if (($_POST["id_pessoa"][$i]!="") && ( (($_POST["tipo"][$i]=="p") && ($_POST["id_solicitacao"][$i]!="")) || ($_POST["tipo"][$i]=="c")) ) {
					//echo "Passando pela $i xxx vez aki! <BR>";
					$result2= mysql_query("insert into tfds_pessoas (id_pessoa, id_tfd, tipo, id_solicitacao, ida, volta, obs)
											values ('". $_POST["id_pessoa"][$i] ."','". $id_tfd ."',
													'". $_POST["tipo"][$i] ."', '". $_POST["id_solicitacao"][$i] ."',
													'". $_POST["ida"][$i] ."', '". $_POST["volta"][$i] ."', '". $_POST["obs"][$i] ."' ) ");
					
					$id_tfd_pessoa= mysql_insert_id();
					
					if (!$result2) $var++;
					//se for paciente e o id_solicitacao tiver preenchido
					if (($_POST["tipo"][$i]=="p") && ($_POST["id_solicitacao"][$i]!="")) {
						$result3= mysql_query("update tfds_solicitacoes set situacao_solicitacao = '5'
												where id_solicitacao = '". $_POST["id_solicitacao"][$i] ."'
												and   id_pessoa = '". $_POST["id_pessoa"][$i] ."'
												");
						if (!$result3) $var++;
						
						$pessoas= explode("@", $_POST["acompanhantes"][$i]);

						$num_pessoas_ac= count($pessoas);
						//echo "num_pessoas: ". $num_pessoas_ac;
						
						for ($j=0; $j<$num_pessoas_ac; $j++) {
							$ps= str_replace("@", "", $pessoas[$j]);
							if ($ps!="") {
								$result4= mysql_query("insert into tfds_pessoas_acompanhantes (id_tfd_pessoa, id_pessoa)
														values ('". $id_tfd_pessoa ."','". $ps ."' ) ");
								$ps= "";
								if (!$result4) $var++;
							}
						}
					}
				}
			}
			
			finaliza_transacao($var);
		}
		
		$msg= $var;
		$pagina= "_tfd/tfd_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formTfdSolicitacao"])) {
		$pagina= "_tfd/tfd_solicitacao_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formTfdBuscar"])) {
		$pagina= "_tfd/tfd_listar";
		require_once("index3.php");
	}
	
}

/* -------------------------------------------- PRONTUARIO -------------------------------------------------- */

if ( ($_SESSION["id_posto_sessao"]!="") && (pode("r", $_SESSION["permissao"])) ) {
	if (isset($_GET["formFilaInserir"])) {
		if ($_POST["id_pessoa"]!="") {
			$result_pre= mysql_query("select id_pessoa from filas where id_pessoa = '". $_POST["id_pessoa"] ."' and atendido = '0' ");
			$linhas= mysql_num_rows($result_pre);
			//echo "linhas: $linhas";
			if ($linhas==0)
				$result= mysql_query("insert into filas
										(id_posto, id_pessoa, id_tipo_atendimento, tipo_consulta,
										temperatura, pressao1, pressao2, hcg, peso, altura,
										data_fila, atendido, id_usuario, area_abran, local_consulta)
										
										values ('". $_SESSION["id_posto_sessao"] ."', '". $_POST["id_pessoa"] ."', '". $_POST["id_tipo_atendimento"] ."', '". $_POST["tipo_consulta"] ."', 
										'". formata_valor($_POST["temperatura"]) ."', '". $_POST["pressao1"] ."', '". $_POST["pressao2"] ."',
										'". formata_valor($_POST["hcg"]) ."', '". formata_valor($_POST["peso"]) ."', '". formata_valor($_POST["altura"]) ."',
										'". date("YmdHis") ."', '0', '". $_SESSION["id_usuario_sessao"] ."', '". $_POST["area_abran"] ."', '". $_POST["local_consulta"] ."' ) ") or die(mysql_error());
		}
		
		if ($result) $msg= 1;
		else $msg= 0;
		
		if ($linhas!=0)
			$msg=4;
		$pagina= "_consultas/prontuario";
		require_once("index3.php");
	}
	
	if (isset($_GET["formPreConsulta"])) {
		if ($_POST["id_agenda"]!="") {
			
			$result_pre= mysql_query("select id_agenda from agenda_consultas
										where id_posto = '". $_SESSION["id_posto_sessao"] ."'
										and   id_agenda = '". $_POST["id_agenda"] ."'
										and   atendido = '0' ");
			$linhas= mysql_num_rows($result_pre);
			
			//echo "linhas: $linhas";
			if ($linhas==1)
				$result= mysql_query("update agenda_consultas set
										id_tipo_atendimento = '". $_POST["id_tipo_atendimento"] ."', 
										temperatura= '". formata_valor($_POST["temperatura"]) ."', 
										pressao1= '". $_POST["pressao1"] ."', 
										pressao2= '". $_POST["pressao2"] ."', 
										hcg= '". formata_valor($_POST["hcg"]) ."',
										peso= '". formata_valor($_POST["peso"]) ."',
										altura= '". formata_valor($_POST["altura"]) ."',
										area_abran= '". $_POST["area_abran"] ."',
										pre_atendido= '1'
										where id_agenda = '". $_POST["id_agenda"] ."'
										");
		}
		$id_agenda= $_POST["id_agenda"];
		
		$pagina= "_consultas/agenda_listar";
		require_once("index3.php");
	}

	if (isset($_GET["formAgendaInserir"])) {
		if ( ($_POST["id_pessoa"]!="") && ($_POST["dia_agendamento"]!="") && ($_POST["hora_agendamento"]!="") ) {
			$data= $dia_agendamento;
			$dia_agendamento= formata_data($_POST["dia_agendamento"]);
			
			$hora_agendamento= $_POST["hora_agendamento"];
			if (strlen($hora_agendamento)==5)
				$hora_agendamento .= ":00";
			
			$hora_agendamento= formata_hora($hora_agendamento);
			
			$result= mysql_query("insert into agenda_consultas
										(id_posto, para, local_consulta, id_pessoa, id_tipo_atendimento, tipo_consulta, data_agendada, atendido, id_usuario, data_agendamento, area_abran)
										values
										('". $_SESSION["id_posto_sessao"] ."', '". $_POST["para"] ."', '". $_POST["local_consulta"] ."', '". $_POST["id_pessoa"] ."', '". $_POST["id_tipo_atendimento"] ."',
										'". $_POST["tipo_consulta"] ."', '". $dia_agendamento . $hora_agendamento ."',
										'0', '". $_SESSION["id_usuario_sessao"] ."', '". date("YmdHis") ."', '". $_POST["area_abran"] ."' ) ") or die(mysql_error());
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_consultas/agenda_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formAgendaBuscar"])) {
		$pagina= "_consultas/agenda_listar";
		require_once("index3.php");
	}
}

/* ----------------------------------------------- REMÉDIOS --------------------------------------- */

if (@pode_algum("fx", $_SESSION["permissao"])) {
	
	if (isset($_GET["formRemedioInserir"])) {
		if ($_POST["remedio"]!="") {
			$result_antes= mysql_query("select remedio from remedios
											where remedio = '". $_POST["remedio"] ."'
											and   tipo_remedio = '". $_POST["tipo_remedio"] ."' ");

			if (mysql_num_rows($result_antes)==0) {
				if ($_POST["classificacao_remedio"]=="c")
					$class_rem= "c";
				else
					$class_rem= "n";
				
				$result= mysql_query("insert into remedios (remedio, tipo_remedio, classificacao_remedio)
								values ('". strtoupper($_POST["remedio"]) ."', '". $_POST["tipo_remedio"] ."', '". $class_rem ."') ");
			}
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_remedios/remedio_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formRemedioEditar"])) {
		if ($_POST["remedio"]!="")
			if ($_POST["classificacao_remedio"]=="c")
				$class_rem= "c";
			else
				$class_rem= "n";
			
			$result= mysql_query("update remedios set remedio= '". strip_tags(strtoupper($_POST["remedio"])) ."',
										tipo_remedio= '". $_POST["tipo_remedio"] ."',
										classificacao_remedio= '". $class_rem ."'
										where id_remedio = '". $_POST["id_remedio"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_remedios/remedio_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formRemedioBuscar"])) {
		$pagina= "_remedios/remedio_listar";
		require_once("index3.php");
	}

	//exames, remedios, usuarios, cidades, postos
	
	/* ----------------------------------------------- APELIDOS --------------------------------------- */
	if (isset($_GET["formApelidoInserir"])) {
		if ( ($_POST["apelido"]!="") && ($_POST["id_remedio"]!="") ) {
			$result_pre= mysql_query("select id_apelido from apelidos where id_remedio = '". $_POST["id_remedio"] ."' and apelido = '". $_POST["apelido"] ."' ");
			
			//se o apelido pra esse remedio ainda nao tiver inserido
			if (mysql_num_rows($result_pre)==0)
				$result= mysql_query("insert into apelidos (id_remedio, apelido) values ('". $_POST["id_remedio"] ."', '". strip_tags(strtoupper($_POST["apelido"])) ."') ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_remedios/apelido_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formApelidoEditar"])) {
		if ($_POST["apelido"]!="")
			$result= mysql_query("update apelidos set apelido = '". strip_tags(strtoupper($_POST["apelido"])) ."' where id_apelido = '". $_POST["id_apelido"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$id_remedio= pega_id_remedio_do_apelido($_POST["id_apelido"]);
		$pagina= "_remedios/apelido_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formApelidoBuscar"])) {
		$pagina= "_remedios/apelido_listar";
		require_once("index3.php");
	}

/* ----------------------------------------------- MATERIAIS --------------------------------------- */
	
	if (isset($_GET["formMaterialInserir"])) {
		if ($_POST["material"]!="") {
			$result_antes= mysql_query("select material from materiais where material = '". $_POST["material"] ."' ");	
			if (mysql_num_rows($result_antes)==0)
				$result= mysql_query("insert into materiais (material, tipo_material) values
									('". strip_tags(strtoupper($_POST["material"])) ."', '". $_POST["tipo_material"] ."') ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_materiais/material_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formMaterialEditar"])) {
		if ($_POST["material"]!="")
			$result= mysql_query("update materiais set material= '". strip_tags(strtoupper($_POST["material"])) ."',
										tipo_material= '". $_POST["tipo_material"] ."'
										where id_material = '". $_POST["id_material"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_materiais/material_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formMaterialBuscar"])) {
		$pagina= "_materiais/material_listar";
		require_once("index3.php");
	}
	
	/* ----------------------------------------------- EXAMES --------------------------------------- */
	
	if (isset($_GET["formExameInserir"])) {
		if ($_POST["exame"]!="")
			$result= mysql_query("insert into exames (exame) values ('". strip_tags(strtoupper($_POST["exame"])) ."') ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_exames/exame_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formExameEditar"])) {
		if ($_POST["exame"]!="")
			$result= mysql_query("update exames set exame= '". strip_tags(strtoupper($_POST["exame"])) ."' where id_exame = '". $_POST["id_exame"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_exames/exame_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formExameBuscar"])) {
		$pagina= "_exames/exame_listar";
		require_once("index3.php");
	}

	/* ----------------------------------------------- VEICULOS --------------------------------------- */
	
	if (isset($_GET["formVeiculoInserir"])) {
		if ($_POST["veiculo"]!="")
			$result= mysql_query("insert into tfds_veiculos (id_cidade, veiculo, placa) values ('". strip_tags(strtoupper($_POST["id_cidade"])) ."', '". strip_tags(strtoupper($_POST["veiculo"])) ."', '". strip_tags(strtoupper($_POST["placa"])) ."') ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_veiculos/veiculo_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formVeiculoEditar"])) {
		if ($_POST["veiculo"]!="")
			$result= mysql_query("update tfds_veiculos set
									 veiculo= '". strip_tags(strtoupper($_POST["veiculo"])) ."',
									 placa= '". strip_tags(strtoupper($_POST["placa"])) ."'
									 where id_veiculo = '". $_POST["id_veiculo"] ."' ") or die(mysql_error());
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_veiculos/veiculo_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formVeiculoBuscar"])) {
		$pagina= "_veiculos/veiculo_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formMotoristaInserir"])) {
		if (($_POST["id_pessoa"]!="") && ($_POST["id_cidade"]!="")) {
			
			$result_pre= mysql_query("select id from tfds_motoristas
										where id_pessoa = '". $_POST["id_pessoa"] ."'
										and   id_cidade = '". $_POST["id_cidade"] ."' ");
			
			if (mysql_num_rows($result_pre)==0)
				$result= mysql_query("insert into tfds_motoristas 
										(id_cidade, id_pessoa)
										values
										('". $_POST["id_cidade"]. "', '". $_POST["id_pessoa"]. "')");
		}
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_veiculos/motorista_listar";
		require_once("index3.php");
	}
}

if ($_SESSION["tipo_usuario_sessao"]=='a') {	
	/* -------------------------------------------- CIDADES -------------------------------------------------- */
	
	if (isset($_GET["formCidadeInserir"])) {
		if ($_POST["id_cidade"]!="")
			$result= mysql_query("update cidades set sistema = '1',
									modo_cadastro_cpf= '". $_POST["modo_cadastro_cpf"] ."',
									modo_farmacia= '". $_POST["modo_farmacia"] ."',
									modo_almox= '". $_POST["modo_almox"] ."'
									where id_cidade = '$id_cidade' ");
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_acesso/cidade_listar";
		require_once("index3.php");
	}
	
	/* -------------------------------------------- POSTOS -------------------------------------------------- */
	
	if (isset($_GET["formPostoEditar"])) {
		if (($_POST["posto"]!="") && ($_POST["id_posto"]!=""))
			$result= mysql_query("update postos set posto= '". strip_tags(strtoupper($_POST["posto"])) ."', psf= '". $_POST['psf'] ."', tipo_agendamento= '". $_POST['tipo_agendamento'] ."' where id_posto = '". $_POST["id_posto"] ."' ");
			//echo "update postos set posto= '". strtoupper($_POST["posto"]) ."' where id_posto = '". $_POST["id_posto"] ."' ";
		
		if ($result)
			$msg=0;
		else
			$msg=1;
	
		$id_cidade= pega_id_cidade_do_posto($id_posto);	
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formPostoInserir"])) {
		if (($_POST["posto"]!="") && ($_POST["id_cidade"]!=""))
			$result= mysql_query("insert into postos (id_cidade, posto, psf, tipo_agendamento, situacao) values ('$id_cidade', '". strip_tags(strtoupper($_POST["posto"])) ."', '". $_POST["psf"] ."', '". $_POST["tipo_agendamento"] ."', '1')");
			//echo "insert into postos (id_cidade, posto, situacao) values ('$id_cidade', '". strtoupper($_POST["posto"]) ."', '1')";
		
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	
	/* -------------------------------------------- POSTOS -------------------------------------------------- */
	
	if (isset($_GET["formMicroareaEditar"])) {
		if (($_POST["id_microarea"]!="") && ($_POST["id_posto"]!=""))
			$result= mysql_query("update microareas set microarea= '". strip_tags(strtoupper($_POST["microarea"])) ."',
									id_posto= '". $_POST["id_posto"] ."',
									id_pessoa= '". $_POST["id_pessoa"] ."'
									where id_microarea = '". $_POST["id_microarea"] ."' ");
		
		if ($result) $msg=0;
		else $msg=1;
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formMicroareaInserir"])) {
		if (($_POST["id_posto"]!="") && ($_POST["id_pessoa"]!=""))
			$result= mysql_query("insert into microareas (id_posto, microarea, id_pessoa) values ('". $_POST["id_posto"] ."', '". strip_tags(strtoupper($_POST["microarea"])) ."', '". $_POST["id_pessoa"] ."')");
		
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	/* -------------------------------------------- USUARIOS -------------------------------------------------- */
	
	if (isset($_GET["formUsuarioInserir"])) {
		if (($_POST["id_pessoa"]!="") && ($_POST["usuario"]!="")) {
			$senha= md5($_POST["senha"]);
			
			$result_pre1= mysql_query("select id_usuario from usuarios where usuario = '". $_POST["usuario"] ."' ");
			$result_pre2= mysql_query("select id_usuario from usuarios where id_pessoa = '". $_POST["id_pessoa"] ."' ");
			
			//se nao existe esse nome de usuario E esta pessoa nao tem outro login
			if ( (mysql_num_rows($result_pre1)==0) && (mysql_num_rows($result_pre2)==0) )
				$result= mysql_query("insert into usuarios 
										(id_pessoa, usuario, senha, tipo_usuario, crm, situacao)
										values
										('". $_POST["id_pessoa"]. "', '". strip_tags($_POST["usuario"]) ."', '". $senha ."',
										'". $_POST["tipo_usuario"] ."', '". $_POST["crm"] ."', '1')");
		}
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_acesso/usuario_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioEditar"])) {
		if (($_POST["id_usuario"]!="") && ($_POST["usuario"]!="") && ($_POST["senha"]!="") ) {
			$senha= md5($_POST["senha"]);
			
			$result_pre1= mysql_query("select id_usuario from usuarios where usuario = '". $_POST["usuario"] ."' and id_usuario <> '". $_POST["id_usuario"] ."' ");
			
			//se nao existe esse nome de usuario sem ser essa pessoa
			if (mysql_num_rows($result_pre1)==0)
				$result= mysql_query("update usuarios set
										usuario= '". $_POST["usuario"] ."',
										crm= '". $_POST["crm"] ."',
										tipo_usuario= '". $_POST["tipo_usuario"] ."',
										senha = '". $senha ."'
										where id_usuario = '". $_POST["id_usuario"] ."'
										");
		}
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$pagina= "_acesso/usuario_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioBuscar"])) {
		$pagina= "_acesso/usuario_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNoPostoInserir"])) {
		if (($_POST["id_posto"]!="") && ($_POST["id_usuario"]!="")) {
			$result_pre= mysql_query("select id_usuario from usuarios_postos
										where id_posto = '". $_POST["id_posto"] ."'
										and   id_usuario = '". $_POST["id_usuario"] ."'
										");
			
			if (mysql_num_rows($result_pre)==0) {
				
				$permissao = ".". $_POST["prontuario"] . $_POST["consultar"] . $_POST["odontologia"] . $_POST["farmacia"] . $_POST["almoxarifado"] . $_POST["producao"] .".";
				
				$result= mysql_query("insert into usuarios_postos (id_posto, id_usuario, id_cbo, permissao)
										values
										('". $_POST["id_posto"] ."', '". $_POST["id_usuario"] ."', '". $_POST["id_cbo"] ."', '$permissao') ") or die(mysql_error());
			}
		}
		
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$id_posto= $_POST["id_posto"];
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNoPostoEditar"])) {
		if (($_POST["id_posto"]!="") && ($_POST["id_usuario"]!="")) {
			$permissao = ".". $_POST["prontuario"] . $_POST["consultar"] . $_POST["odontologia"] . $_POST["farmacia"] . $_POST["almoxarifado"] . $_POST["producao"] .".";
			
			$result= mysql_query("update usuarios_postos set permissao = '$permissao', id_cbo= '". $_POST["id_cbo"] ."'
									where id_posto= '". $_POST["id_posto"] ."'
									and   id_usuario= '". $_POST["id_usuario"] ."' ") or die(mysql_error());
		}
		
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$id_posto= $_POST["id_posto"];
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNaCidadeInserir"])) {
		if (($_POST["id_cidade"]!="") && ($_POST["id_usuario"]!="")) {
			$result_pre= mysql_query("select id_usuario from usuarios_cidades
										where id_cidade = '". $_POST["id_cidade"] ."'
										and   id_usuario = '". $_POST["id_usuario"] ."'
										");
			
			if (mysql_num_rows($result_pre)==0) {
				$permissao = ".". $_POST["prontuario"] . $_POST["consultar"] . $_POST["farmacia"] . $_POST["almoxarifado"] . $_POST["secretario"] . $_POST["producao"] . $_POST["tfd"] . $_POST["social"] .".";
				
				$result= mysql_query("insert into usuarios_cidades (id_cidade, id_usuario, id_cbo, permissao)
										values
										('". $_POST["id_cidade"] ."', '". $_POST["id_usuario"] ."', '". $_POST["id_cbo"] ."', '$permissao')");
			}
		}
		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$id_cidade= $_POST["id_cidade"];
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNaCidadeEditar"])) {
		if (($_POST["id_cidade"]!="") && ($_POST["id_usuario"]!="")) {
			$permissao = ".". $_POST["prontuario"] . $_POST["consultar"] . $_POST["farmacia"] . $_POST["almoxarifado"] . $_POST["secretario"] . $_POST["producao"] . $_POST["tfd"] . $_POST["social"] .".";
			
			$result= mysql_query("update usuarios_cidades set permissao = '$permissao'
									where id_cidade= '". $_POST["id_cidade"] ."'
									and   id_usuario= '". $_POST["id_usuario"] ."' ") or die(mysql_error());
		}

		if ($result)
			$msg=0;
		else
			$msg=1;
		
		$id_cidade= $_POST["id_cidade"];
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	
}//fim if adm

/* -------------------------------------------- CONSULTAS -------------------------------------------------- */
if ( ($_SESSION["id_posto_sessao"]!="") && ( (pode("c", $_SESSION["permissao"])) || (pode("r", $_SESSION["permissao"])) ) ) {
	
	if (isset($_GET["formProcInserir"])) {
		if ( ($_POST["qtde"]!="") && ($_POST["data"]!="") ) {
			
			$result= mysql_query("insert into procedimentos
										(id_procedimento, id_posto, data_procedimento, qtde, id_cbo)
										values
										('". $_POST["id_procedimento"] ."', '". $_SESSION["id_posto_sessao"] ."',
										'". formata_data($_POST["data"]) ."', '". $_POST["qtde"] ."', '". $_POST["id_cbo"] ."' ) ") or die(mysql_error());
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_proc/proc_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formResultadoExame"])) {
		$result_pre= mysql_query("select consultas_exames.id_consulta_exame
									from postos, consultas, consultas_exames
									where consultas.id_posto = postos.id_posto
									and   postos.id_cidade = '". pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]) ."'
									and   consultas.id_consulta = consultas_exames.id_consulta
									and   consultas_exames.id_consulta_exame = '". $_POST["id_consulta_exame"] ."'
									");
		//achou o exame
		if (mysql_num_rows($result_pre)==1) {
			$result= mysql_query("update consultas_exames set
									resultado = '". addslashes($_POST["resultado"]) ."',
									id_usuario= '". $_SESSION["id_usuario_sessao"] ."'
									where id_consulta_exame = '". $_POST["id_consulta_exame"] ."'
									") or die(mysql_error());
		}

		if ($result) {
			echo "Dados armazenados com sucesso!";
			echo "<script language='javascript' type='text/javascript'>;";
			echo "preencheDiv('resultado_exame_". $_POST["id_consulta_exame"] ."', '". addslashes($_POST["resultado"]) ."');";
			echo "setTimeout('fechaTelaAuxRapida()', 1000);";
			echo "</script>";
		}
		else
			echo "Não foi possível cadastrar, tecle F5 para tentar novamente!";
	}
	
	if (isset($_GET["formConsultaInserir"])) {
		$var= 0;
		inicia_transacao();
		
		if ($_POST["id_fila"]!="") {
			$pagina_que_vai= "fila_listar";
			$origem_consulta= "f@". $_POST["id_fila"];
			$result0= mysql_query("update filas set atendido = '1' where id_fila = '$id_fila' ") or die("Erro01: ". mysql_error());
		}
		else {
			$pagina_que_vai= "agenda_listar";
			$origem_consulta= "a@". $_POST["id_agenda"];
			$result0= mysql_query("update agenda_consultas set atendido = '1' where id_agenda = '$id_agenda' ") or die("Erro02: ". mysql_error());
		}
		
		if (!$result0) $var++;
		
		$result1= mysql_query("insert into consultas (id_posto, tipo_consulta, tipo_consulta_prof,
								origem_consulta, id_tipo_atendimento, id_pessoa, id_usuario, data_consulta,
								qp, hda, hmp, hf, hps, rs,
								s, o, a, p, obs,
								anamnese, exame_boca,
								encaminhamento, idade_paciente, area_abran, id_cbo)
								values ('". $_SESSION["id_posto_sessao"] ."', '". $_POST["tipo_consulta"] ."',
								'". $_POST["tipo_consulta_prof"] ."', '". $origem_consulta ."', '". $_POST["id_tipo_atendimento"] ."',
								'". $_POST["id_pessoa"] ."', '". $_SESSION["id_usuario_sessao"] ."', '". date("YmdHis") ."',
								'". strip_tags(strtoupper($_POST["qp"])) ."', '". strip_tags(strtoupper($_POST["hda"])) ."',
								'". strip_tags(strtoupper($_POST["hmp"])) ."', '". strip_tags(strtoupper($_POST["hf"])) ."',
								'". strip_tags(strtoupper($_POST["hps"])) ."', '". strip_tags(strtoupper($_POST["rs"])) ."',
								
								'". strip_tags(strtoupper($_POST["s"])) ."', '". strip_tags(strtoupper($_POST["o"])) ."',
								'". strip_tags(strtoupper($_POST["a"])) ."', '". strip_tags(strtoupper($_POST["p"])) ."',
								'". strip_tags(strtoupper($_POST["obs"])) ."',
								
								'". strip_tags(strtoupper($_POST["anamnese"])) ."', '". strip_tags(strtoupper($_POST["exame_boca"])) ."',
								
								'". strip_tags($_POST["encaminhamento"]) ."', '". strip_tags($_POST["idade_paciente"]) ."',
								
								'". $_POST["area_abran"] ."', '". $_SESSION["id_cbo_sessao"] ."'
								) ") or die("Erro1: ". mysql_error());
								
		$id_consulta= mysql_insert_id();
		
		if (!$result1) $var++;
		
		for ($i=0; $i<count($id_exame); $i++) {
			$result2= mysql_query("insert into consultas_exames (id_consulta, id_exame)
								values ('$id_consulta', '". $id_exame[$i] ."' ) ") or die("Erro2: ". mysql_error());
			if (!$result2) $var++;
		}
		
		for ($i=0; $i<count($pos_id_remedio); $i++) {
			$result3= mysql_query("insert into consultas_remedios (id_consulta, id_remedio, qtde, tipo_apres, qtde_tomar, tipo_tomar, tipo_periodicidade, periodicidade, periodo, observacoes)
								values ('$id_consulta', '". $pos_id_remedio[$i] ."', '". $pos_qtde[$i] ."', '". $pos_tipo_apresentacao[$i] ."', '". $pos_qtde_tomar[$i] ."', '". $pos_tipo_tomar[$i] ."',
										'". $pos_tipo_periodicidade[$i] ."', '". $pos_periodicidade[$i] ."', '". $pos_periodo[$i] ."', '". strtoupper($pos_observacoes[$i]) ."') ") or die("Erro2: ". mysql_error());
			if (!$result3) $var++;
		}
	
		for ($i=0; $i<count($id_oprocedimento); $i++) {
			$result4= mysql_query("insert into consultas_odonto_procedimentos (id_consulta, id_oprocedimento)
								values ('$id_consulta', '". $id_oprocedimento[$i] ."' ) ") or die("Erro4: ". mysql_error());
			if (!$result4) $var++;
		}
		
		for ($i=0; $i<count($id_dente); $i++) {
			$result5_pre= mysql_query("delete from odontograma_denticao
										where id_odontograma = '". $_POST["id_odontograma"] ."'
										and   id_pessoa = '". $_POST["id_pessoa"] ."'
										");
			
			if ($problema[$i]=="1") {
				$result5= mysql_query("insert into odontograma_denticao (id_odontograma, id_dente, id_face, problema)
										values ('$id_odontograma', '". $id_dente[$i] ."', '". $id_face[$i] ."', '". $problema[$i] ."' ) ")
										or die("Erro5: ". mysql_error());
				if (!$result5) $var++;
			}
		}
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		if ($_POST["tipo_consulta"]=="m")
			$pagina= "_consultas/consulta_receita_ver";
		else
			$pagina= "_consultas/consulta_listar";
		
		require_once("index3.php");
	}
	
	if (isset($_GET["formProcBuscar"])) {
		$pagina= "_proc/proc_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formConsultaBuscar"])) {
		$pagina= "_consultas/consulta_listar";
		require_once("index3.php");
	}
}//fim if

/* -------------------------------------------- PESSOAS -------------------------------------------------- */

if (isset($_GET["formPessoaRelatorio"])) {
	$pagina= "_pessoas/pessoa_relatorio";
	require_once("index3.php");
}

if (isset($_GET["formPessoaEditar"])) {
	if ( ($_POST["id_cidade"]!="") && ($_POST["nome"]!="") && ($_POST["id_pessoa"]!="") ) {
		$var= 0;
		inicia_transacao();
		
		$permissao= false;
		
		//se esta vindo alguem q era dependente e nao eh mais
		if ( ($_POST["cpf_cadastro"]!="") && ($_POST["id_responsavel"]!="0") ) {
			$result_pre= mysql_query("select id_pessoa from pessoas where cpf= '". $_POST["cpf_cadastro"] ."' ");
			if (mysql_num_rows($result_pre)==0)
				$permissao=true;
			else
				$cpf_existe= true;
		}
		else
			$permissao= true;
		
		if ($permissao) {
			$data_nasc= formata_data($data_nasc);
			
			if (($_POST["cpf_cadastro"]!=""))
				$linha_cpf= "cpf = '". $_POST["cpf_cadastro"] ."', id_responsavel = '0', ";
			
			if ($_SESSION["tipo_usuario_sessao"]=="a")
				$linha_morte= "situacao_pessoa = '". $_POST["situacao_pessoa"] ."', ";
			
			$result1= mysql_query("update pessoas set id_cidade ='". $_POST["id_cidade"] ."',
														id_psf='". $_POST["id_psf"] ."',
														nome='". strip_tags(strtoupper($_POST["nome"])) ."',
														sexo='". $_POST["sexo"] ."',
														raca='". $_POST["raca"] ."',
														". $linha_cpf ."
														nome_mae= '". strtoupper($_POST["nome_mae"]) ."',
														nome_pai= '". strtoupper($_POST["nome_pai"]) ."',
														
														nome_madrasta= '". strtoupper($_POST["nome_madrasta"]) ."',
														nome_padrasto= '". strtoupper($_POST["nome_padrasto"]) ."',
														
														rg='". strip_tags($_POST["rg"]) ."',
														orgao_emissor_rg='". strip_tags($_POST["orgao_emissor_rg"]) ."',
														endereco='". strip_tags(strtoupper($_POST["endereco"])) ."',
														bairro='". strip_tags(strtoupper($_POST["bairro"])) ."',
														complemento='". strip_tags(strtoupper($_POST["complemento"])) ."',
														cep='". strip_tags($_POST["cep"]) ."',
														telefone='". strip_tags($_POST["telefone"]) ."',
														data_nasc='". $data_nasc ."',
														". $linha_morte ."
														cartao_sus='". strip_tags($_POST["cartao_sus"]) ."',
														observacoes='". strip_tags($_POST["observacoes"]) ."'
														where id_pessoa ='". $_POST["id_pessoa"] ."' ") or die("Erro: ". mysql_error());
			if (!$result1) $var++;
			
			//dados sociais tbm
			if ($_POST["dados_sociais"]==1) {
				if (tem_dados_sociais($_POST["id_pessoa"]))
					$result2= mysql_query("update pessoas_se set id_profissao= '". $_POST["id_profissao"] ."',
												 renda= '". formata_valor($_POST["renda"]) ."',
												 local_trabalho= '". strip_tags(strtoupper($_POST["local_trabalho"])) ."',
												 ca= '". $_POST["ca"] ."',
												 desempregado_tempo= '". strip_tags(strtoupper($_POST["desempregado_tempo"])) ."',
												 cidade_nat= '". strip_tags(strtoupper($_POST["cidade_nat"])) ."',
												 tempo_municipio= '". strip_tags(strtoupper($_POST["tempo_municipio"])) ."',
												 id_ec= '". $_POST["id_ec"] ."',
												 id_gi= '". $_POST["id_gi"] ."'
												 where id_pessoa = '". $_POST["id_pessoa"] ."' ") or die("Erro 2: ". mysql_error());
				else
					$result2= mysql_query("insert into pessoas_se (id_pessoa, id_profissao, renda, local_trabalho, ca, desempregado_tempo, cidade_nat, tempo_municipio, id_ec, id_gi)
												values ('". $_POST["id_pessoa"] ."', '". $_POST["id_profissao"] ."', '". formata_valor($_POST["renda"]) ."', '". strip_tags(strtoupper($_POST["local_trabalho"])) ."',
														'". $_POST["ca"] ."', '". strip_tags(strtoupper($_POST["desempregado_tempo"])) ."', '". strip_tags(strtoupper($_POST["cidade_nat"])) ."',
														'". strip_tags(strtoupper($_POST["tempo_municipio"])) ."', '". $_POST["id_ec"] ."', '". $_POST["id_gi"] ."' ) ")
														or die("Erro 2: ". mysql_error());
				if (!$result2) $var++;
			}
		}
		finaliza_transacao($var);
	}
	
	if ($var==0) {
		echo "Dados cadastrais editados com sucesso!";
		echo "<script language='javascript' type='text/javascript'>setTimeout('fechaTelaCadastro()', 1000);";
		if ($_POST["retorno"]!="conteudo")
			echo "
			daFoco('cpf_usuario');
			daBlur('cpf_usuario');
			";
		echo "</script>";
		
	}
	else {
		if ($cpf_existe)
			echo "CPF já cadastrado, o mesmo pertence a(á) <b>". pega_nome_pelo_cpf($_POST["cpf_cadastro"]) ."</b>";
		else
			echo "Não foi possível cadastrar, tecle F5 para tentar novamente!";
	}
}

if (isset($_GET["formPessoaInserir"])) {
	if ( ($_POST["id_cidade"]!="") && ($_POST["nome"]!="") ) {
		$var= 0;
		inicia_transacao();
		
		$data_nasc= formata_data($data_nasc);
		
		if ($_SESSION["id_posto_sessao"]!="") {
			$origem_cadastro= "p";
			$id_origem_cadastro= $_SESSION["id_posto_sessao"];
		}
		if ($_SESSION["id_cidade_sessao"]!="") {
			$origem_cadastro= "c";
			$id_origem_cadastro= $_SESSION["id_cidade_sessao"];
		}
		
		$passa= true;
		
		if ($_POST["cpf"]!="") {
			if ( (strlen($_POST["cpf"])!=11) || (!valida_cpf($_POST["cpf"])) )
				$passa=false;
		}
		
		if ($passa)
			$result1= mysql_query("insert into pessoas (id_responsavel, id_cidade, nome, sexo, raca, cpf, nome_mae, nome_pai, nome_madrasta, nome_padrasto, rg, orgao_emissor_rg, endereco, bairro, complemento, cep, telefone, data_nasc, cartao_sus, data_cadastro, origem_cadastro, id_origem_cadastro, id_psf, observacoes, id_usuario)
									values ('". $_POST["id_responsavel"] ."', '". $_POST["id_cidade"] ."', '". strip_tags(strtoupper($_POST["nome"])) ."', '". $_POST["sexo"] ."', '". $_POST["raca"] ."',
										'". $_POST["cpf_cadastro"] ."', '". strtoupper($_POST["nome_mae"]) ."', '". strtoupper($_POST["nome_pai"]) ."',
										
										'". strtoupper($_POST["nome_madrasta"]) ."', '". strtoupper($_POST["nome_padrasto"]) ."',
										
										'". strip_tags($_POST["rg"]) ."', '". strip_tags($_POST["orgao_emissor_rg"]) ."', '". strip_tags(strtoupper($_POST["endereco"])) ."', '". strip_tags(strtoupper($_POST["bairro"])) ."',
										'". strtoupper($_POST["complemento"]) ."', '". strip_tags($_POST["cep"]) ."', '". strip_tags($_POST["telefone"]) ."', '$data_nasc', '". strip_tags($_POST["cartao_sus"]) ."',
										'". date("YmdHis") ."', '$origem_cadastro', '$id_origem_cadastro', '". $_POST["id_psf"] ."', '". $_POST["observacoes"] ."', '". $_SESSION["id_usuario_sessao"] ."') ")
										or die("Erro: ". mysql_error());
		if (!$result1) $var++;
		
		//dados sociais tbm
		if ($_POST["dados_sociais"]==1) {
			$id_pessoa= mysql_insert_id();
			
			$result2= mysql_query("insert into pessoas_se (id_pessoa, id_profissao, renda, local_trabalho, ca, desempregado_tempo, cidade_nat, tempo_municipio, id_ec, id_gi)
										values ('$id_pessoa', '". $_POST["id_profissao"] ."', '". formata_valor($_POST["renda"]) ."', '". strip_tags(strtoupper($_POST["local_trabalho"])) ."',
												'". $_POST["ca"] ."', '". strip_tags(strtoupper($_POST["desempregado_tempo"])) ."', '". strip_tags(strtoupper($_POST["cidade_nat"])) ."',
												'". strip_tags(strtoupper($_POST["tempo_municipio"])) ."', '". $_POST["id_ec"] ."', '". $_POST["id_gi"] ."' ) ")
												or die("Erro 2: ". mysql_error());
			if (!$result2) $var++;
		}
		
		finaliza_transacao($var);
	}
	
	if ($var==0) {
		echo "Pessoa cadastrada com sucesso!";
		echo "<script language='javascript' type='text/javascript'>;";
		echo "setTimeout('fechaTelaCadastro()', 1000);";
		if ($_POST["retorno"]!="conteudo") {
			echo "
			daFoco('cpf_usuario');
			daBlur('cpf_usuario');
			";
		}
		echo "</script>";
		
	}
	else
		echo "Não foi possível cadastrar, tecle F5 para tentar novamente!";
}

if (isset($_GET["formPessoaBuscar"])) {
	$pagina= "_pessoas/pessoa_listar";
	require_once("index3.php");
}

/* -------------------------------------------- FARMACIA -------------------------------------------------- */

if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("f", $_SESSION["permissao"])) ) {
	
	if (isset($_GET["formConsumoMensalRemedio"])) {
		$pagina= "_farmacia/consumo_mensal_remedio";
		require_once("index3.php");
	}
	if (isset($_GET["formConsumoMensal"])) {
		$pagina= "_farmacia/consumo_mensal";
		require_once("index3.php");
	}
	if (isset($_GET["formBalancoFarmacia"])) {
		$pagina= "_farmacia/balanco_farmacia";
		require_once("index3.php");
	}
	if (isset($_GET["formPessoasMedicamentos"])) {
		$pagina= "_farmacia/relacao_pessoas_remedios";
		require_once("index3.php");
	}
	
	if (isset($_GET["formExtrato"])) {
		$pagina= "_farmacia/mov_listar_real";
		require_once("index3.php");
	}
	if (isset($_GET["formMovBuscar"])) {
		$pagina= "_farmacia/mov_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formEstoqueBuscar"])) {
		$pagina= "_farmacia/estoque_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formDistBuscar"])) {
		$pagina= "_farmacia/dist_inserir_pos";
		require_once("index2.php");
	}
	
	
	if (isset($_GET["formEstoqueMinimo"])) {
		
		if ($_SESSION["id_posto_sessao"]!="") {
			$tabela= "postos";
			$campo= "id_posto";
			$valor_campo= $_SESSION["id_posto_sessao"];
		}
		if ($_SESSION["id_cidade_sessao"]!="") {
			$tabela= "almoxarifado";
			$campo= "id_cidade";
			$valor_campo= $_SESSION["id_cidade_sessao"];
		}
		
		$result_pre= mysql_query("select * from ". $tabela ."_minimo
									where $campo = $valor_campo
									and   id_remedio = '". $_POST["id_remedio"] ."'
									");
		//insere
		if (mysql_num_rows($result_pre)==0) {
			$sql= "insert into ". $tabela ."_minimo
					(". $campo .", id_remedio, qtde_minima)
					values
					('". $valor_campo ."', '". $_POST["id_remedio"] ."', '". $_POST["qtde_minima"] ."')
					";
		}
		//edita
		else {
			$sql= "update ". $tabela ."_minimo set
					qtde_minima = '". $_POST["qtde_minima"] ."'
					where $campo = $valor_campo
					and   id_remedio = '". $_POST["id_remedio"] ."'
					";
		}
		
		$result= mysql_query($sql) or die(mysql_error());
		
		if ($result) {
			echo "Dados armazenados com sucesso!";
			echo "<script language='javascript' type='text/javascript'>;";
			echo "setTimeout('fechaTelaAuxRapida()', 1000);";
			echo "</script>";
		}
		else
			echo "Não foi possível cadastrar, tecle F5 para tentar novamente!";
	}
	
	if (isset($_GET["formDistInserir"])) {
		$var= 0;
		inicia_transacao();
		
		if (isset($_SESSION["id_posto_sessao"])) {
			$str[0]= "id_posto";
			$str[1]= $_SESSION["id_posto_sessao"];
		}
		if (isset($_SESSION["id_cidade_sessao"])) {
			$str[0]= "id_cidade";
			$str[1]= $_SESSION["id_cidade_sessao"];
		}
		$data_trans= date("YmdHis");
		
		for ($i=0; $i<count($id_consulta_remedio); $i++) {
			
			if ( ((int)$_POST["qtde"][$i] >= (int)$_POST["qtde_pego"][$i]) && ((int)$_POST["qtde"][$i]>0) && ((int)$_POST["qtde_pego"][$i]>0) ) {
				$result1= mysql_query("insert into almoxarifado_mov 
										(". $str[0] .", id_remedio, tipo_trans, id_receptor, qtde, tipo_apres, data_trans, id_usuario)
										values
										('". $str[1] ."', '". $_POST["id_remedio"][$i] ."', 'd', '". $_POST["id_pessoa"] ."', '". $_POST["qtde_pego"][$i] ."', '". $_POST["tipo_apres"][$i] ."', '". $data_trans ."', '". $_SESSION["id_usuario_sessao"] ."') ") or die(mysql_error());	
				
				if (!$result1) $var++;
				
				$id_mov= mysql_insert_id();
		
				$result2= mysql_query("update consultas_remedios set id_mov = '$id_mov'
										where id_consulta_remedio = '". $id_consulta_remedio[$i] ."' ") or die("Erro2: ". mysql_error());
				
				if (!$result2) $var++;
				
				
				//dar baixa no estoque
				if (isset($_SESSION["id_posto_sessao"])) {
					$result3= mysql_query("update postos_estoque set
											qtde_atual = qtde_atual - '". $_POST["qtde_pego"][$i] ."'
											where id_posto = '". $_SESSION["id_posto_sessao"] ."'
											and   id_remedio= '". $_POST["id_remedio"][$i] ."'
											and   tipo_apres= '". $_POST["tipo_apres"][$i] ."'
											") or die(mysql_error());
				
				}
				if (isset($_SESSION["id_cidade_sessao"])) {
					$result3= mysql_query("update almoxarifado_atual set
											qtde_atual = qtde_atual - '". $_POST["qtde_pego"][$i] ."'
											where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											and   id_remedio= '". $_POST["id_remedio"][$i] ."'
											and   tipo_apres= '". $_POST["tipo_apres"][$i] ."'
											") or die(mysql_error());
				
				}
				if (!$result3) $var++;
			}
			else
				$var++;
		}
		
		
		//salvar movimentacao
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$txt_busca = $_POST["id_consulta"];
		
		$pagina= "_farmacia/dist_inserir";
		require_once("index3.php");
	}
	
	if (isset($_GET["formPeriInserir"])) {
		$var= 0;
		$ne= 0;
		
		inicia_transacao();
		
		if (isset($_SESSION["id_posto_sessao"])) {
			$str[0]= "id_posto";
			$str[1]= $_SESSION["id_posto_sessao"];
		}
		if (isset($_SESSION["id_cidade_sessao"])) {
			$str[0]= "id_cidade";
			$str[1]= $_SESSION["id_cidade_sessao"];
		}
		$data_trans= date("YmdHis");
		
		for ($i=0; $i<count($id_remedio); $i++) {
			
			if ( ((int)$_POST["qtde_atual"][$i] >= (int)$_POST["qtde_pego"][$i]) && ((int)$_POST["qtde_atual"][$i]>0) && ((int)$_POST["qtde_pego"][$i]>0) ) {
				$result1= mysql_query("insert into almoxarifado_mov 
										(". $str[0] .", id_remedio, tipo_trans, subtipo_trans, id_receptor, qtde, tipo_apres, data_trans, id_usuario)
										values
										('". $str[1] ."', '". $_POST["id_remedio"][$i] ."', 's', 'p', '". $_POST["id_pessoa"] ."', '". $_POST["qtde_pego"][$i] ."', '". $_POST["tipo_apres"][$i] ."', '". $data_trans ."', '". $_SESSION["id_usuario_sessao"] ."') ") or die(mysql_error());	
				
				if (!$result1) $var++;
				
				$id_mov= mysql_insert_id();
		
				//dar baixa no estoque
				if (isset($_SESSION["id_posto_sessao"])) {
					$result3= mysql_query("update postos_estoque set
											qtde_atual = qtde_atual - '". $_POST["qtde_pego"][$i] ."'
											where id_posto = '". $_SESSION["id_posto_sessao"] ."'
											and   id_remedio= '". $_POST["id_remedio"][$i] ."'
											and   tipo_apres= '". $_POST["tipo_apres"][$i] ."'
											") or die(mysql_error());
				
				}
				if (isset($_SESSION["id_cidade_sessao"])) {
					$result3= mysql_query("update almoxarifado_atual set
											qtde_atual = qtde_atual - '". $_POST["qtde_pego"][$i] ."'
											where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											and   id_remedio= '". $_POST["id_remedio"][$i] ."'
											and   tipo_apres= '". $_POST["tipo_apres"][$i] ."'
											") or die(mysql_error());
				
				}
				if (!$result3) $var++;
			}
			else
				$ne++;
		}

		finaliza_transacao($var);
		$msg= $var;
		
		$pagina= "_farmacia/peri";
		require_once("index3.php");
	}
	
	if ($_SESSION["id_cidade_sessao"]!="") {
		
		if (isset($_GET["formAlmoxMovPosto"])) {
			if (($_POST["id_remedio"]!="") && ($_POST["qtde"]!="") && ($_POST["id_posto_d"]!="") ) {
				$var= 0;
				inicia_transacao();
		
				$result1= mysql_query("insert into almoxarifado_mov
										(id_cidade, id_remedio, tipo_trans, id_receptor, qtde, tipo_apres, data_trans, id_usuario, observacoes)
										values
										('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_remedio"] ."', 'm', '". $_POST["id_posto_d"] ."',
										'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."', '". date("YmdHis") ."', '". $_SESSION["id_usuario_sessao"] ."',
										'". strip_tags($_POST["observacoes"]) ."'
										)") or die(mysql_error());
				
				if (!$result1) $var++;
				
				$result1_= mysql_query("update almoxarifado_atual set
										qtde_atual = qtde_atual - '". $_POST["qtde"] ."'
										where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										and   id_remedio= '". $_POST["id_remedio"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
		
				if (!$result1_) $var++;
				
				$result2= mysql_query("select id from postos_estoque
										where id_posto= '". $_POST["id_posto_d"] ."'
										and   id_remedio = '". $_POST["id_remedio"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
				
				if (mysql_num_rows($result2)==0)
					$result3= mysql_query("insert into postos_estoque 
											(id_posto, id_remedio, qtde_atual, tipo_apres)
											values
											('". $_POST["id_posto_d"] ."', '". $_POST["id_remedio"] ."',
											'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."') ") or die(mysql_error());
				else
					$result3= mysql_query("update postos_estoque set
											qtde_atual = qtde_atual + '". $_POST["qtde"] ."'
											where id_posto = '". $_POST["id_posto_d"] ."'
											and   id_remedio= '". $_POST["id_remedio"] ."'
											and   tipo_apres= '". $_POST["tipo_apres"] ."'
											") or die(mysql_error());
				
				if (!$result3) $var++;
				
				finaliza_transacao($var);
			}
			
			$msg= $var;
			$pagina= "_farmacia/movp_inserir";
			require_once("index3.php");
		}
		
		if (isset($_GET["formAlmoxEntrada"])) {
			if (($_POST["id_remedio"]!="") && ($_POST["qtde"]!="")) {
			
				$var= 0;
				inicia_transacao();
				
				$data_validade2= formata_data($_POST["data_validade"]);
				
				$result1= mysql_query("insert into almoxarifado_mov
										(id_cidade, id_remedio, tipo_trans, subtipo_trans, qtde, tipo_apres, data_trans, id_usuario, observacoes, id_fornecedor, lote, data_validade)
										values
										('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_remedio"] ."', 'e', '". $_POST["subtipo_trans"] ."',
										'". $_POST["qtde"] ."', 'u', '". date("YmdHis") ."', '". $_SESSION["id_usuario_sessao"] ."',
										'". strip_tags($_POST["observacoes"]) ."', '". $_POST["id_fornecedor"] ."', '$lote', '$data_validade2' )");
				
				if (!$result1) $var++;
				
				$result2= mysql_query("select id from almoxarifado_atual
										where id_cidade= '". $_SESSION["id_cidade_sessao"] ."'
										and   tipo_apres = 'u'
										and   id_remedio = '". $_POST["id_remedio"] ."'
										");
				
				if (mysql_num_rows($result2)==0)
					$result3= mysql_query("insert into almoxarifado_atual 
											(id_cidade, id_remedio, qtde_atual, tipo_apres)
											values
											('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_remedio"] ."', '". $_POST["qtde"] ."', 'u' ) ");
				else
					$result3= mysql_query("update almoxarifado_atual set
											qtde_atual = qtde_atual + '". $_POST["qtde"] ."'
											where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											and   id_remedio= '". $_POST["id_remedio"] ."'
											and   tipo_apres = 'u'
											");
				
				if (!$result3) $var++;
				
				finaliza_transacao($var);
			}
			
			$msg= $var;
			
			$pagina= "_farmacia/entrada_inserir";
			require_once("index3.php");
		}
	}//fim soh cidade
	
	if (isset($_GET["formAlmoxSaida"])) {
		if (($_POST["id_remedio"]!="") && ($_POST["qtde"]!="")) {
			$var= 0;
			inicia_transacao();
			
			
			if ($_POST["ident_local"]=='p')
				$sql0= "select qtde_atual from postos_estoque
									where id_posto= '". $_SESSION["id_posto_sessao"] ."'
									and   id_remedio = '". $_POST["id_remedio"] ."'
									and   tipo_apres = '". $_POST["tipo_apres"] ."'
									";
			
			if ($_POST["ident_local"]=='c')
				$sql0= "select qtde_atual from almoxarifado_atual
									where id_cidade= '". $_SESSION["id_cidade_sessao"] ."'
									and   id_remedio = '". $_POST["id_remedio"] ."'
									and   tipo_apres = '". $_POST["tipo_apres"] ."'
									";
			
			$result0= mysql_query($sql0);
			
			$rs0= mysql_fetch_object($result0);
	
			//se a qtde atual for maior q a solicitada para saída
			if ($rs0->qtde_atual >= $_POST["qtde"]) {
				
				if ($ident_local=='p') {
					$tabela= "postos_estoque";
					$parametro1= "id_posto";
					$parametro1_v= $_SESSION["id_posto_sessao"];
				}
				if ($ident_local=='c') {
					$tabela= "almoxarifado_atual";
					$parametro1= "id_cidade";
					$parametro1_v= $_SESSION["id_cidade_sessao"];
				}
				
				$result1= mysql_query("insert into almoxarifado_mov
										(". $parametro1 .", id_remedio, tipo_trans, subtipo_trans, qtde, tipo_apres, data_trans, observacoes, id_usuario, id_receptor)
										values
										('". $parametro1_v ."', '". $_POST["id_remedio"] ."', 's', '". $_POST["subtipo_trans"] ."',
										'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."', '". date("YmdHis") ."', '". strip_tags($_POST["observacoes"]) ."', '". $_SESSION["id_usuario_sessao"] ."', '". $_POST["id_pessoa"] ."')");
				
				if (!$result1) $var++;
				
				$result2= mysql_query("update ". $tabela ." set
										qtde_atual = qtde_atual - '". $_POST["qtde"] ."'
										where ". $parametro1 ." = '". $parametro1_v ."'
										and   id_remedio= '". $_POST["id_remedio"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
				
				if (!$result2) $var++;
			
			finaliza_transacao($var);
			}//fim teste js+
			else
				$var= 1;
		}
		
		$msg= $var;
		
		$pagina= "_farmacia/saida_inserir";
		require_once("index3.php");
	}
	
	if (isset($_GET["formEstornoEntrega"])) {
		if ($_POST["id_mov"]!="") {
			$var= 0;
			inicia_transacao();

			if ($_SESSION["id_posto_sessao"]!="") {
				$tabela= "postos_estoque";
				$parametro1= "id_posto";
				$parametro1_v= $_SESSION["id_posto_sessao"];
			}
			if ($_SESSION["id_cidade_sessao"]!="") {
				$tabela= "almoxarifado_atual";
				$parametro1= "id_cidade";
				$parametro1_v= $_SESSION["id_cidade_sessao"];
			}
			
			//seleciona os dados da entrega a ser estornada
			$result_pre= mysql_query("select *, DATE_FORMAT(data_trans, '%d/%m/%Y %H:%i:%s') as data_trans
										from almoxarifado_mov
										where almoxarifado_mov.id_mov = '". $_POST["id_mov"] ."'
										and   almoxarifado_mov.". $parametro1 ." = '". $parametro1_v ."'
										");
			if (!$result_pre) $var++;
			$rs_pre= mysql_fetch_object($result_pre);
			
			//se a mov do alxmo for realmente uma entrega
			if (($rs_pre->tipo_trans=="s") && ($rs_pre->subtipo_trans=="b") && ($rs_pre->situacao_mov!=2)) {
			
				//seleciona a quantidade atual desse remedio no posto ou cidade
				$result0= mysql_query("select qtde_atual from ". $tabela ."
										where ". $parametro1 ." = '". $parametro1_v ."'
										and   id_remedio = '". $rs_pre->id_remedio ."'
										and   tipo_apres = '". $rs_pre->tipo_apres ."'
										");
				if (!$result0) $var++;
				$rs0= mysql_fetch_object($result0);
				
				//insere a entrada (Estorno) do medicamento no posto ou cidade
				$result1= mysql_query("insert into almoxarifado_mov
										(". $parametro1 .", id_remedio, tipo_trans, subtipo_trans, qtde, tipo_apres, data_trans, observacoes, id_usuario)
										values
										('". $parametro1_v ."', '". $rs_pre->id_remedio ."', 'e', 's',
										'". $rs_pre->qtde ."', '". $rs_pre->tipo_apres ."', '". date("YmdHis") ."', '". "**** Estorno da movimentação cód. <b>". $rs_pre->id_mov ."</b> **** ". strip_tags($_POST["observacoes"]) ."', '". $_SESSION["id_usuario_sessao"] ."')");
										//Originalmente entregue <b>". $rs_pre->qtde ." ". pega_apresentacao($rs_pre->tipo_apres) ."</b> para <b>". pega_nome($rs_pre->id_receptor) ."</b> em <b>". $rs_pre->data_trans ."</b>
				if (!$result1) $var++;
				
				//aumenta na quantidade atual do posto ou cidade
				$result2= mysql_query("update ". $tabela ." set
										qtde_atual = qtde_atual + '". $rs_pre->qtde ."'
										where ". $parametro1 ." = '". $parametro1_v ."'
										and   id_remedio= '". $rs_pre->id_remedio ."'
										and   tipo_apres= '". $rs_pre->tipo_apres ."'
										") or die(mysql_error());
				if (!$result2) $var++;
				
				//por fim, aletera a situacao da entrega que está sendo estornada para nao aparecer na listagem de remedios entregues a pessoa (Relatorio)
				$result3= mysql_query("update almoxarifado_mov
										set situacao_mov= '2'
										where id_mov = '". $rs_pre->id_mov ."'
										and   ". $parametro1 ." = '". $parametro1_v ."'
										") or die(mysql_error());
				if (!$result3) $var++;
	
				finaliza_transacao($var);
			}
			else
				$var= 1;
		}
		$msg= $var;
		
		$pagina= "_farmacia/estoque_listar";
		require_once("index3.php");
	}

}//fim if do almoxarifado

/* -------------------------------------------- ALMOXARIFADO MATERIAIS -------------------------------------------------- */

if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("x", $_SESSION["permissao"])) ) {
	
	if (isset($_GET["formConsumoMensalMaterial"])) {
		$pagina= "_almox/consumom_mensal_material";
		require_once("index3.php");
	}
	if (isset($_GET["formConsumoMMensal"])) {
		$pagina= "_almox/consumom_mensal";
		require_once("index3.php");
	}

	
	if (isset($_GET["formMExtrato"])) {
		$pagina= "_almox/movm_listar_real";
		require_once("index3.php");
	}
	if (isset($_GET["formMMovBuscar"])) {
		$pagina= "_almox/movm_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formMEstoqueBuscar"])) {
		$pagina= "_almox/estoquem_listar";
		require_once("index3.php");
	}
	
	if ($_SESSION["id_cidade_sessao"]!="") {
		if (isset($_GET["formMAlmoxMovPosto"])) {
			if (($_POST["id_material"]!="") && ($_POST["qtde"]!="") && ($_POST["id_posto_d"]!="") ) {
				$var= 0;
				inicia_transacao();
		
				$result1= mysql_query("insert into almoxarifadom_mov
										(id_cidade, id_material, tipo_trans, id_receptor, qtde, tipo_apres, data_trans, id_usuario, observacoes)
										values
										('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_material"] ."', 'm', '". $_POST["id_posto_d"] ."',
										'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."', '". date("YmdHis") ."', '". $_SESSION["id_usuario_sessao"] ."',
										'". strip_tags($_POST["observacoes"]) ."'
										)") or die(mysql_error());
				
				if (!$result1) $var++;
				
				$result1_= mysql_query("update almoxarifadom_atual set
										qtde_atual = qtde_atual - '". $_POST["qtde"] ."'
										where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										and   id_material= '". $_POST["id_material"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
		
				if (!$result1_) $var++;
				
				$result2= mysql_query("select id from postosm_estoque
										where id_posto= '". $_POST["id_posto_d"] ."'
										and   id_material = '". $_POST["id_material"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
				
				if (mysql_num_rows($result2)==0)
					$result3= mysql_query("insert into postosm_estoque 
											(id_posto, id_material, qtde_atual, tipo_apres)
											values
											('". $_POST["id_posto_d"] ."', '". $_POST["id_material"] ."',
											'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."') ") or die(mysql_error());
				else
					$result3= mysql_query("update postosm_estoque set
											qtde_atual = qtde_atual + '". $_POST["qtde"] ."'
											where id_posto = '". $_POST["id_posto_d"] ."'
											and   id_material= '". $_POST["id_material"] ."'
											and   tipo_apres= '". $_POST["tipo_apres"] ."'
											") or die(mysql_error());
				
				if (!$result3) $var++;
				
				finaliza_transacao($var);
			}
			
			$msg= $var;
			$pagina= "_almox/movpm_inserir";
			require_once("index3.php");
		}
		
		if (isset($_GET["formMAlmoxEntrada"])) {
			if (($_POST["id_material"]!="") && ($_POST["qtde"]!="")) {
			
				$var= 0;
				inicia_transacao();
				
				$data_validade2= formata_data($_POST["data_validade"]);
				
				$result1= mysql_query("insert into almoxarifadom_mov
										(id_cidade, id_material, tipo_trans, subtipo_trans, qtde, tipo_apres, data_trans, id_usuario, observacoes, id_fornecedor, lote, data_validade)
										values
										('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_material"] ."', 'e', '". $_POST["subtipo_trans"] ."',
										'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."', '". date("YmdHis") ."', '". $_SESSION["id_usuario_sessao"] ."',
										'". strip_tags($_POST["observacoes"]) ."', '". $_POST["id_fornecedor"] ."', '$lote', '$data_validade2' )");
				
				if (!$result1) $var++;
				
				$result2= mysql_query("select id from almoxarifadom_atual
										where id_cidade= '". $_SESSION["id_cidade_sessao"] ."'
										and   tipo_apres = '". $_POST["tipo_apres"] ."'
										and   id_material = '". $_POST["id_material"] ."'
										");
				
				if (mysql_num_rows($result2)==0)
					$result3= mysql_query("insert into almoxarifadom_atual 
											(id_cidade, id_material, qtde_atual, tipo_apres)
											values
											('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_material"] ."', '". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."' ) ");
				else
					$result3= mysql_query("update almoxarifadom_atual set
											qtde_atual = qtde_atual + '". $_POST["qtde"] ."'
											where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
											and   id_material= '". $_POST["id_material"] ."'
											and   tipo_apres = '". $_POST["tipo_apres"] ."'
											");
				
				if (!$result3) $var++;
				
				finaliza_transacao($var);
				
			}
			
			$msg= $var;
			
			$pagina= "_almox/entradam_inserir";
			require_once("index3.php");
		}
	}
	
	if (isset($_GET["formMAlmoxSaida"])) {
		if (($_POST["id_material"]!="") && ($_POST["qtde"]!="")) {
		
			$var= 0;
			inicia_transacao();
			
			if ($_POST["ident_local"]=='p')
				$sql0= "select qtde_atual from postosm_estoque
									where id_posto= '". $_SESSION["id_posto_sessao"] ."'
									and   id_material = '". $_POST["id_material"] ."'
									and   tipo_apres = '". $_POST["tipo_apres"] ."'
									";
			
			if ($_POST["ident_local"]=='c')
				$sql0= "select qtde_atual from almoxarifadom_atual
									where id_cidade= '". $_SESSION["id_cidade_sessao"] ."'
									and   id_material = '". $_POST["id_material"] ."'
									and   tipo_apres = '". $_POST["tipo_apres"] ."'
									";
			
			$result0= mysql_query($sql0);
			
			$rs0= mysql_fetch_object($result0);
	
			//se a qtde atual for maior q a solicitada para saída
			if ($rs0->qtde_atual >= $_POST["qtde"]) {
				
				if ($ident_local=='p') {
					$tabela= "postosm_estoque";
					$parametro1= "id_posto";
					$parametro1_v= $_SESSION["id_posto_sessao"];
				}
				if ($ident_local=='c') {
					$tabela= "almoxarifadom_atual";
					$parametro1= "id_cidade";
					$parametro1_v= $_SESSION["id_cidade_sessao"];
				}
				
				$result1= mysql_query("insert into almoxarifadom_mov
										(". $parametro1 .", id_material, tipo_trans, subtipo_trans, qtde, tipo_apres, data_trans, observacoes, id_usuario, id_receptor)
										values
										('". $parametro1_v ."', '". $_POST["id_material"] ."', 's', '". $_POST["subtipo_trans"] ."',
										'". $_POST["qtde"] ."', '". $_POST["tipo_apres"] ."', '". date("YmdHis") ."', '". $_POST["observacoes"] ."', '". $_SESSION["id_usuario_sessao"] ."', '". $_POST["id_pessoa"] ."')");
				
				if (!$result1) $var++;
				
				$result2= mysql_query("update ". $tabela ." set
										qtde_atual = qtde_atual - '". $_POST["qtde"] ."'
										where ". $parametro1 ." = '". $parametro1_v ."'
										and   id_material= '". $_POST["id_material"] ."'
										and   tipo_apres= '". $_POST["tipo_apres"] ."'
										") or die(mysql_error());
				
				if (!$result2) $var++;
			
			finaliza_transacao($var);
			}//fim teste js+
			else
				$var= 1;
		}
		
		$msg= $var;
		
		$pagina= "_almox/saidam_inserir";
		require_once("index3.php");
	}
}//fim if do almoxarifado de materiais

/* --------------------------------------------- RELATÓRIOS ------------------------------------------------------- */

if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("s", $_SESSION["permissao"])) ) {
	//relatorios
	if (isset($_GET["formRelatorioResumo"])) {
		$pagina= "_relatorios/relatorio_resumo";
		require_once("index3.php");
	}
	if (isset($_GET["formRelatorioResumoMini"])) {
		$pagina= "_relatorios/relatorio_resumo";
		require_once("index3.php");
	}
}

/* --------------------------------------------- PRODUÇÃO ------------------------------------------------------- */

if ( ($_SESSION["id_posto_sessao"]!="") && (pode("p", $_SESSION["permissao"])) ) {

	if (isset($_GET["formSSA2"])) {
		if (($_POST["mes"]!="") && ($_POST["ano"]!="")) {
			$var= 0;
			
			for ($i=0; $i<count($_POST["dado"]); $i++) {
				
				$result_del= mysql_query("delete from ssa2_dados
											where mes = '". $_POST["mes"] ."'
											and   ano = '". $_POST["ano"] ."'
											and   id_linha= '". $_POST["id_linha"][$i] ."'
											and   id_microarea= '". $_POST["id_microarea"][$i] ."'
											");
				
				if (($_POST["dado"][$i]!="") && ($_POST["dado"][$i]!="0")) {
					$result= mysql_query("insert into ssa2_dados (mes, ano, id_linha, id_microarea, dado)
											values
											('". $_POST["mes"] ."', '". $_POST["ano"] ."', '". $_POST["id_linha"][$i] ."', '". $_POST["id_microarea"][$i] ."', '". $_POST["dado"][$i] ."')
											");
					if (!$result)
						$var++;
				}
			}
		}
		$msg= $var;
		
		$pagina= "_producao/ssa2_inserir";
		require_once("index3.php");
	}

	if (isset($_GET["formPMA2"])) {
		if (($_POST["mes"]!="") && ($_POST["ano"]!="")) {
			$var= 0;
			for ($i=0; $i<count($_POST["dado"]); $i++) {
				
				$result_del= mysql_query("delete from pma2_dados
											where id_posto= '". $_SESSION["id_posto_sessao"] ."'
											and   mes = '". $_POST["mes"] ."'
											and   ano = '". $_POST["ano"] ."'
											and   id_linha= '". $_POST["id_linha"][$i] ."'
											");
				
				if (($_POST["dado"][$i]!="") && ($_POST["dado"][$i]!="0")) {
					$result= mysql_query("insert into pma2_dados (id_posto, mes, ano, id_linha, dado)
											values
											('". $_SESSION["id_posto_sessao"] ."', '". $_POST["mes"] ."', '". $_POST["ano"] ."', '". $_POST["id_linha"][$i] ."', '". $_POST["dado"][$i] ."')
											");
					if (!$result)
						$var++;
				}
			}
		}
		$msg= $var;
		
		$pagina= "_producao/pma2_inserir";
		require_once("index3.php");
	}
	
	if (isset($_GET["formBPA"])) {
		if (($_POST["mes"]!="") && ($_POST["ano"]!="")) {
			$var= 0;
			
			$result_del= mysql_query("delete from bpa_dados
										where id_posto= '". $_SESSION["id_posto_sessao"] ."'
										and   mes = '". $_POST["mes"] ."'
										and   ano = '". $_POST["ano"] ."'
										");
			
			if (!$result_del)
				$var++;
			
			for ($i=0; $i<count($_POST["id_linha"]); $i++) {
				
				//echo $_POST["id_linha"][$i] ." / ";
				
				//if (($_POST["dado"][$i]!="") && ($_POST["dado"][$i]!="0")) {
					$result= mysql_query("insert into bpa_dados (id_linha, id_posto, mes, ano, id_cbo, idade, dado)
											values
											('". $_POST["id_linha"][$i] ."', '". $_SESSION["id_posto_sessao"] ."',
											'". $_POST["mes"] ."', '". $_POST["ano"] ."',
											'". $_POST["id_cbo"][$i] ."', '". $_POST["idade"][$i] ."', '". $_POST["dado"][$i] ."')
											");
					if (!$result)
						$var++;
				//}
			}
		}
		$msg= $var;
		
		$pagina= "_producao/bpa_inserir";
		require_once("index3.php");
	}
	
}

/* --------------------------------------------- SOCIAL ------------------------------------------------------- */

if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("l", $_SESSION["permissao"])) ) {

	if (isset($_GET["formFamiliaInserir"])) {
		if (($_POST["id_microarea"]!="") && ($_POST["id_pessoa"]!="")) {

			$var= 0;
			inicia_transacao();
						
			$result1= mysql_query("insert into familias
									(id_microarea, endereco, bairro, complemento,
									 renda, renda_percapita, id_situacaohab, situacaohab_valor, num_comodos,
									 bens_outros,
									 id_localizacao, id_destlixo, id_abagua, id_escsanitario,
									 id_tratagua, org_higiene, tipo_construcao, doencas_familia,
									 medicamentos_utilizados, valor_mensal_meds, vacina, ultimo_exame_prev,
									 metodo_planejamento, valor_beneficio, necessidade_prioritaria, 
									 programas_outros,
									 n_cartao_sus, n_cartao_familia)
									 values
									('". $_POST["id_microarea"] ."', '". $_POST["endereco"] ."', '". $_POST["bairro"] ."', '". $_POST["complemento"] ."',
									
								     '". formata_valor($_POST["renda"]) ."', '". formata_valor($_POST["renda_percapita"]) ."', '". $_POST["id_situacaohab"] ."', '". formata_valor($_POST["situacaohab_valor"]) ."', '". $_POST["num_comodos"] ."', '". $_POST["bens_outros"] ."',
									 
								     '". $_POST["id_localizacao"] ."', '". $_POST["id_destlixo"] ."', '". $_POST["id_abagua"] ."', '". $_POST["id_escsanitario"] ."',
								     '". $_POST["id_tratagua"] ."', '". $_POST["org_higiene"] ."', '". $_POST["tipo_construcao"] ."', '". $_POST["doencas_familia"] ."',
								     '". $_POST["medicamentos_utilizados"] ."', '". formata_valor($_POST["valor_mensal_meds"]) ."', '". $_POST["vacina"] ."', '". formata_data($_POST["ultimo_exame_prev"]) ."',
								     '". $_POST["metodo_planejamento"] ."', '". formata_valor($_POST["valor_beneficio"]) ."', '". $_POST["necessidade_prioritaria"] ."',
								     
								     '". $_POST["programas_outros"] ."',
								     '". $_POST["n_cartao_sus"] ."', '". $_POST["n_cartao_familia"] ."' )
									") or die("erro 1 ".mysql_error());
			if (!$result1) $var++;
			
			$id_familia= mysql_insert_id();
			
			$i= 0;
			while ($_POST["id_bem"][$i]) {
				$result2= mysql_query("insert into familias_bens (id_familia, id_bem)
										values
										('". $id_familia ."', '". $_POST["id_bem"][$i] ."')
										") or die("erro 2 ".mysql_error());
				if (!$result2) $var++;
				$i++;
			}
			//programas sociais
			$i= 0;
			while ($_POST["id_programa"][$i]) {
				$result3= mysql_query("insert into familias_programas (id_familia, id_programa)
										values
										('". $id_familia ."', '". $_POST["id_programa"][$i] ."')
										") or die("erro 3 ".mysql_error());
				if (!$result3) $var++;
				$i++;
			}
			
			
			$result4= mysql_query("insert into familias_pessoas (id_familia, id_pessoa, tipo)
							values
							('". $id_familia ."', '". $_POST["id_pessoa"] ."', '1')
							") or die("erro 2 ".mysql_error());
			if (!$result4) $var++;

			
		}
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}

	if (isset($_GET["formFamiliaEditar"])) {
		if ($_POST["id_microarea"]!="") {

			$var= 0;
			inicia_transacao();
						
			$result1= mysql_query("update familias set
									id_microarea= '". $_POST["id_microarea"] ."',
									endereco= '". $_POST["endereco"] ."',
									bairro= '". $_POST["bairro"] ."',
									complemento= '". $_POST["complemento"] ."',
									renda= '". formata_valor($_POST["renda"]) ."',
									renda_percapita= '". formata_valor($_POST["renda_percapita"]) ."',
									id_situacaohab= '". $_POST["id_situacaohab"] ."',
									situacaohab_valor= '". formata_valor($_POST["situacaohab_valor"]) ."',
									num_comodos= '". $_POST["num_comodos"] ."',
									bens_outros= '". $_POST["bens_outros"] ."',
									id_localizacao= '". $_POST["id_localizacao"] ."',
									id_destlixo= '". $_POST["id_destlixo"] ."',
									id_abagua= '". $_POST["id_abagua"] ."',
									id_escsanitario= '". $_POST["id_escsanitario"] ."',
									id_tratagua= '". $_POST["id_tratagua"] ."',
									org_higiene= '". $_POST["org_higiene"] ."',
									tipo_construcao= '". $_POST["tipo_construcao"] ."',
									doencas_familia= '". $_POST["doencas_familia"] ."',
									medicamentos_utilizados= '". $_POST["medicamentos_utilizados"] ."',
									valor_mensal_meds= '". formata_valor($_POST["valor_mensal_meds"]) ."',
									vacina= '". $_POST["vacina"] ."',
									ultimo_exame_prev= '". formata_data($_POST["ultimo_exame_prev"]) ."',
									metodo_planejamento= '". $_POST["metodo_planejamento"] ."',
									valor_beneficio= '". formata_valor($_POST["valor_beneficio"]) ."',
									necessidade_prioritaria= '". $_POST["necessidade_prioritaria"] ."',
									programas_outros= '". $_POST["programas_outros"] ."',
									
									n_cartao_sus= '". $_POST["n_cartao_sus"] ."',
									n_cartao_familia= '". $_POST["n_cartao_familia"] ."'

									where id_familia = '". $_POST["id_familia"] ."'

									") or die("erro 1 ".mysql_error());
			if (!$result1) $var++;
			
			$result2_pre= mysql_query("delete from familias_bens where id_familia = '". $_POST["id_familia"] ."' ");
			if (!$result2_pre) $var++;
			
			$i= 0;
			while ($_POST["id_bem"][$i]) {
				$result2= mysql_query("insert into familias_bens (id_familia, id_bem)
										values
										('". $id_familia ."', '". $_POST["id_bem"][$i] ."')
										") or die("erro 2 ".mysql_error());
				if (!$result2) $var++;
				$i++;
			}
			
			$result3_pre= mysql_query("delete from familias_programas where id_familia = '". $_POST["id_familia"] ."' ");
			if (!$result3_pre) $var++;

			$i= 0;
			while ($_POST["id_programa"][$i]) {
				$result3= mysql_query("insert into familias_programas (id_familia, id_programa)
										values
										('". $id_familia ."', '". $_POST["id_programa"][$i] ."')
										") or die("erro 3 ".mysql_error());
				if (!$result3) $var++;
				$i++;
			}
			
			
			$result4= mysql_query("update familias_pessoas set
									id_pessoa = '". $_POST["id_pessoa"] ."'
									where id_familia = '". $_POST["id_familia"] ."'
									and   tipo = '1'
									") or die("erro 2 ".mysql_error());
			if (!$result4) $var++;

			
		}
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formFamiliaBuscar"])) {
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formParecerInserir"])) {
		if (($_POST["data_parecer"]!="") && ($_POST["parecer"]!="")) {
						
			$result= mysql_query("insert into familias_pareceres
									(id_cidade, id_familia, data_parecer, parecer, providencias, id_funcionario)
									 values
									('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_familia"] ."', '". formata_data($_POST["data_parecer"]) ."',
									 '". $_POST["parecer"] ."', '". $_POST["providencias"] ."', '". $_SESSION["id_usuario_sessao"] ."' )
									") or die("erro 1 ".mysql_error());			
		}
		
		if ($result) $msg= 0;
		else $msg= 1;		
		
		$pagina= "_social/parecer";
		
		require_once("index3.php");
	}

	if (isset($_GET["formVisitaInserir"])) {
		if (($_POST["data_visita"]!="") && ($_POST["situacao"]!="")) {
						
			$result= mysql_query("insert into familias_visitas
									(id_cidade, id_familia, data_visita, situacao, parecer, id_funcionario)
									 values
									('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_familia"] ."', '". formata_data($_POST["data_visita"]) ."',
									 '". $_POST["situacao"] ."', '". $_POST["parecer"] ."', '". $_SESSION["id_usuario_sessao"] ."' )
									") or die("erro 1 ".mysql_error());			
		}
		
		if ($result) $msg= 0;
		else $msg= 1;		
		
		$pagina= "_social/visitas";
		
		require_once("index3.php");
	}
	
	if (isset($_GET["formAssistenciaInserir"])) {
		if (($_POST["data_assistencia"]!="") && ($_POST["assistencia"]!="")) {
						
			$result= mysql_query("insert into familias_assistencias
									(id_cidade, id_familia, data_assistencia, assistencia, valor, id_funcionario)
									 values
									('". $_SESSION["id_cidade_sessao"] ."', '". $_POST["id_familia"] ."', '". formata_data($_POST["data_assistencia"]) ."',
									 '". $_POST["assistencia"] ."', '". formata_valor($_POST["valor"]) ."', '". $_SESSION["id_usuario_sessao"] ."' )
									") or die("erro 1 ".mysql_error());			
		}
		
		if ($result) $msg= 0;
		else $msg= 1;		
		
		$pagina= "_social/assistencias";
		
		require_once("index3.php");
	}


}


echo '</body></html>';

?>