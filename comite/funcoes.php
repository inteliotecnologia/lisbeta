<?

/*
function cria_checa_dir_nota($id_pedido) {
	$result= mysql_query("select *, DATE_FORMAT(data_pedido, '%m') as mes,
							DATE_FORMAT(data_pedido, '%Y') as ano
							from op_pedidos
							where id_pedido = '". $id_pedido ."'
							");
	
	$rs= mysql_fetch_object($result);
	
	$base_dir= "./notas/";
	$base_dir_ano= $base_dir . $rs->ano ."/";
	$base_dir_mes= $base_dir_ano . $rs->mes ."/";
	
	if (!is_dir($base_dir)) {
		mkdir($base_dir, 777);
	}
		
	if (!is_dir($base_dir_ano)) {
		mkdir($base_dir_ano, 777);
		
	}
	
	--if (!is_dir($base_dir_mes)) {
		mkdir($base_dir_mes, 777);
	}--
	
	return($base_dir_mes);
}
*/

function insere_recado_livro_sistema($mensagem_aqui, $id_motivo, $id_departamentos) {
	
	if (($id_motivo==35) || ($id_motivo=0) || ($id_motivo==""))
		$str= "and   id_motivo <> '34'
				and   id_motivo <> '37'
				and   id_motivo <> '41'
				and   id_motivo <> '42'";
	else
		$str= "and   id_motivo = '$id_motivo'";

	if ($mensagem_aqui!="") {
		$result_num_livro= mysql_query("select * from com_livro
										where id_empresa = '". $_SESSION["id_empresa"] ."'
										$str
										and   DATE_FORMAT(data_livro, '%Y') = '". date("Y") ."'
										order by num_livro desc limit 1
										");
		$rs_num_livro= mysql_fetch_object($result_num_livro);
		$num_livro= $rs_num_livro->num_livro+1;
		
		$result_livro= mysql_query("insert into com_livro
										(id_empresa, num_livro, tipo_de, de, id_outro_departamento, mensagem, 
										 data_livro, hora_livro, tipo_resposta, resposta, resposta_id_livro,
										 
										 id_motivo, resposta_requerida, id_departamento_principal, prioridade_dias,
										 reclamacao_original, reclamacao_original_id_livro, reclamacao_id_cliente
										 ) values
										('". $_SESSION["id_empresa"] ."', '". $num_livro ."', 'f', '0', '0', '". addslashes($mensagem_aqui) ."',
										'". date("Ymd") ."', '". date("His") ."', '0', '0', '0',
										
										 '0', '0', '0', '0',
										 '0', '', '0'
										 ) ") or die(mysql_error());
		$id_livro_inserido= mysql_insert_id();
		
		
		$i=0;
		while ($id_departamentos[$i]!="") {
			$result_permissao= mysql_query("insert into com_livro_permissoes
												 (id_empresa, id_livro, id_departamento)
												 values 
												 ('". $_SESSION["id_empresa"] ."', '". $id_livro_inserido ."',
												 '". $id_departamentos[$i] ."' )
												 ") or die(mysql_error());
			$i++;
		}
			
		
		return($id_livro_inserido);
	}
	
}


function pega_banco($id_banco) {
	$result= mysql_query("select banco from rh_bancos
							where id_banco = '$id_banco'
							");
	$rs=mysql_fetch_object($result);
	
	return($rs->banco);
}

function pega_turno_pelo_horario($data) {
	
	// 2011-01-01 01:01:01
	//$data= "2011-02-21 06:19:10";
	
	$ano= substr($data, 0, 4);
	$mes= substr($data, 5, 2);
	$dia= substr($data, 8, 2);
	$hora= substr($data, 11, 2);
	$minuto= substr($data, 14, 2);
	$segundo= substr($data, 17, 2);
	
	$calculo_data= mktime($hora, $minuto, $segundo, $mes, $dia, $ano);
	
	$id_dia= date("w", $calculo_data);
	
	$manha=58;
	$tarde=40;
	$noite=42;
	$madrugada=41;
	$plantao_sabado=-1;
	$plantao_domingo=-2;
	
	if ( (($id_dia==0) && ($hora>=6)) || (($id_dia==1) && ($hora<=6)) ) {
		if ( (($id_dia==0) && ($hora>6)) || (($id_dia==1) && ($hora<6)) ) {
			$id_turno= $plantao_domingo;
			if ($mostra) echo 1;
		}
		elseif (($id_dia==0) && ($hora==6)) {
			if ($minuto<16) $id_turno= $plantao_sabado;
			else $id_turno= $plantao_domingo;
			if ($mostra) echo  2;
		}
		elseif ( ($id_dia==1) && ($hora==6) ) {
			if ($minuto<16) $id_turno= $plantao_domingo;
			else $id_turno= $manha;
			if ($mostra) echo  3;
		}
	}
	elseif ( (($id_dia==1) && ($hora>6)) || ($id_dia==2) || ($id_dia==3) || ($id_dia==4) || ($id_dia==5) || (($id_dia==6) && ($hora<6)) ) {
		if ($hora==6) {
			if ($minuto<16) $id_turno= $madrugada;
			else $id_turno= $manha;
			if ($mostra) echo 4;
		}
		elseif (($hora>6) && ($hora<12)) {
			$id_turno= $manha;
			if ($mostra) echo 5;
		}
		elseif ($hora==12) {
			if ($minuto<16) $id_turno= $manha;
			else $id_turno= $tarde;
			if ($mostra) echo 6;
		}
		elseif (($hora>12) && ($hora<18)) {
			$id_turno= $tarde;
			if ($mostra) echo 7;
		}
		elseif ($hora==18) {
			if ($minuto<16) $id_turno= $tarde;
			else $id_turno= $noite;
			if ($mostra) echo 8;
		}
		elseif (($hora>18) && ($hora<=23)) {
			$id_turno= $noite;
			if ($mostra) echo 9;
		}
		elseif ($hora==0) {
			if ($minuto<16) $id_turno= $noite;
			else $id_turno= $madrugada;
			if ($mostra) echo "*". 10;
		}
		elseif (($hora>0) && ($hora<6)) {
			$id_turno= $madrugada;
			if ($mostra) echo "*". 11;
		}
	}
	if ( (($id_dia==6) && ($hora>=6)) || (($id_dia==0) && ($hora<=6)) ) {
		if ( (($id_dia==6) && ($hora>6)) || (($id_dia==0) && ($hora<6)) ) {
			$id_turno= $plantao_sabado;
			if ($mostra) echo "*". 12;
		}
		elseif (($id_dia==6) && ($hora==6)) {
			if ($minuto<16) $id_turno= $madrugada;
			else $id_turno= $plantao_sabado;
			if ($mostra) echo "*". 13;
		}
		elseif ( ($id_dia==0) && ($hora==6) ) {
			if ($minuto<16) $id_turno= $plantao_sabado;
			else $id_turno= $plantao_domingo;
			if ($mostra) echo "*". 14;
		}
	}
	
	return($id_turno);
}

function insere_recado_livro_normal($de, $mensagem_aqui, $id_motivo, $id_departamentos, $id_departamento_principal) {
	
	if (($id_motivo==35) || ($id_motivo=0) || ($id_motivo==""))
		$str= "and   id_motivo <> '34'
				and   id_motivo <> '37'
				and   id_motivo <> '41'
				and   id_motivo <> '42'";
	else
		$str= "and   id_motivo = '$id_motivo'";

	if ($mensagem_aqui!="") {
		$result_num_livro= mysql_query("select * from com_livro
										where id_empresa = '". $_SESSION["id_empresa"] ."'
										$str
										and   DATE_FORMAT(data_livro, '%Y') = '". date("Y") ."'
										order by num_livro desc limit 1
										");
		$rs_num_livro= mysql_fetch_object($result_num_livro);
		$num_livro= $rs_num_livro->num_livro+1;
		
		$result_livro= mysql_query("insert into com_livro
										(id_empresa, num_livro, tipo_de, de, id_outro_departamento,
										 mensagem, data_livro, hora_livro,
										 
										 tipo_resposta, resposta, resposta_id_livro,
										 id_motivo, resposta_requerida, id_departamento_principal,
										 
										 prioridade_dias, reclamacao_original,
										 reclamacao_original_id_livro, reclamacao_id_cliente
										 ) values
										('". $_SESSION["id_empresa"] ."', '". $num_livro ."', 'f', '$de', '0',
										 '". addslashes($mensagem_aqui) ."', '". date("Ymd") ."', '". date("His") ."',
										 '0', '0', '0',
										 '$id_motivo', '0', '$id_departamento_principal',
										 
										 '0', '0', '', '0'
										 ) ") or die(mysql_error());
		$id_livro_inserido= mysql_insert_id();
		
		
		$i=0;
		while ($id_departamentos[$i]!="") {
			$result_permissao= mysql_query("insert into com_livro_permissoes
												 (id_empresa, id_livro, id_departamento)
												 values 
												 ('". $_SESSION["id_empresa"] ."', '". $id_livro_inserido ."',
												 '". $id_departamentos[$i] ."' )
												 ") or die(mysql_error());
			$i++;
		}
			
		
		return($id_livro_inserido);
	}
	
}

function pega_qtde_padrao_item_cedido($tipo_item_cedido, $id_cliente, $periodo) {
	$periodo2= explode("/", $periodo);
	$data= $periodo2[1] ."-". $periodo2[0] ."-01";
	
	$result= mysql_query("select * from fi_clientes_itens_cedidos_padrao
							where id_empresa = '". $_SESSION["id_empresa"] ."'
							and   tipo_item_cedido = '". $tipo_item_cedido ."'
							and   id_cliente = '". $id_cliente ."'
							and   data_qtde_padrao >= '". $data ."'
							order by data_qtde_padrao asc, id_item_cedido_padrao desc limit 1
							");
	$linhas= mysql_num_rows($result);
	
	/*if ($linhas==0) {
		$result= mysql_query("select * from fi_clientes_itens_cedidos_padrao
							where id_empresa = '". $_SESSION["id_empresa"] ."'
							and   tipo_item_cedido = '". $tipo_item_cedido ."'
							and   id_cliente = '". $id_cliente ."'
							and   data_qtde_padrao >= '". $data ."'
							order by data_qtde_padrao asc, id_item_cedido_padrao desc limit 1
							");
	}*/
	
	$rs= mysql_fetch_object($result);
	
	return($rs->qtde_padrao);
}

function inverte_0_1($valor) {
	if ($valor==1) $valor=0;
	else $valor=1;
	
	return($valor);
}

function pega_tipo_contrato2($valor) {
	if ($valor=="1") $retorno="<span class=\"verde\">Oficial</span>";
	else $retorno="<span class=\"vermelho\">Volunt�rio</span>";
	
	return($retorno);
}

function retira_acentos($texto) {
  $array1 = array(   "&", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�"
                     , "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" );
  $array2 = array(   "e", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
  return str_replace( $array1, $array2, $texto );
}

function string_maior_que($string, $tamanho) {
	if (strlen($string)>$tamanho) $var= $string ."...";
	else $var= $string;
	
	return($var);
}

function pega_tipo_treinamento($tipo) {
	if ($tipo==1) return("interno");
	else return("externo");
}

function pega_tipo_pessoa($i) {
	switch ($i) {
		case 'f': $tipo= "Fornecedor"; break;
		case 'c': $tipo= "Cliente"; break;
		case 'u': $tipo= "Funcion�rio"; break;
		case 'a': $tipo= "Empresa com sistema"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function ajusta_dados_rh($id_empresa, $id_funcionario, $data, $ht, $ht_diurnas, $ht_noturnas, $faltas, $faltas_diurnas, $faltas_noturnas,
						$he, $he_diurnas, $he_noturnas, $he_diurnas_faixa1, $he_diurnas_faixa2, $he_noturnas_faixa1, $he_noturnas_faixa2,
						$falta_justificada, $falta_nao_justificada, $suspensao, $id_usuario) {
	
	$hoje= date("Y-m-d");
	$data_mk= faz_mk_data($data);
	$hoje_mk= faz_mk_data($hoje);
	
	if ($hoje_mk>=$data_mk) {
		inicia_transacao();
		$var=0;
		
		$result_pre= mysql_query("delete from rh_consolidado
									where id_funcionario = '$id_funcionario'
									and   data = '$data'
									");
		if (!$result_pre) $var++;
		
		$result= mysql_query("insert into rh_consolidado
								(id_empresa, id_funcionario, data, ht, ht_diurnas, ht_noturnas, faltas, faltas_diurnas, faltas_noturnas,
								he, he_diurnas, he_noturnas, he_diurnas_faixa1, he_diurnas_faixa2, he_noturnas_faixa1, he_noturnas_faixa2,
								falta_justificada, falta_nao_justificada, suspensao, id_usuario)
								values
								('$id_empresa', '$id_funcionario', '$data', '$ht', '$ht_diurnas', '$ht_noturnas', '$faltas', '$faltas_diurnas', '$faltas_noturnas',
								'$he', '$he_diurnas', '$he_noturnas', '$he_diurnas_faixa1', '$he_diurnas_faixa2', '$he_noturnas_faixa1', '$he_noturnas_faixa2', 
								'$falta_justificada', '$falta_nao_justificada', '$suspensao', '$id_usuario')
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
	}
	//else echo "aqui n�o conta...";
	
}

function pega_media_diaria_trabalho($id_departamento, $id_turno) {
	
	if ($id_departamento!="0") $str.= " and   id_departamento = '". $id_departamento ."' ";
	if ($id_turno!="0") $str.= " and   id_turno = '". $id_turno ."' ";
	
	$result_turno= mysql_query("select * from rh_turnos
							   	where status_turno = '1'
								$str
								order by id_turno asc
								limit 1
								");
	$rs_turno= mysql_fetch_object($result_turno);
	$id_turno= $rs_turno->id_turno;
	
	switch($rs_turno->id_regime) {
		//integral
		case 1:
			$i_inicio=0;
			break;
		//6/12 ou 6/6
		case 2:
		case 3:
			$i_inicio=1;
			break;
			
	}
	
	$total_semana=0;
	
	for ($i=$i_inicio; $i<=6; $i++) {
		$result_dia= mysql_query("select * from rh_turnos_horarios
									where id_turno = '". $id_turno ."'
									and   id_dia = '$i'
									");
		$rs_dia= mysql_fetch_object($result_dia);
		
		$jornada_entrada_hora= explode(':', $rs_dia->entrada);
		$jornada_saida_hora= explode(':', $rs_dia->saida);
		
		$a_jornada= 2008;
		$m_jornada= 10;
		
		$d_jornada_entrada= 10;
		$d_jornada_saida= 10;
		
		if ($jornada_entrada_hora[0]>$jornada_saida_hora[0]) $d_jornada_saida++;
		
		$jornada_entrada[$i]= @mktime($jornada_entrada_hora[0], $jornada_entrada_hora[1], $jornada_entrada_hora[2], $m_jornada, $d_jornada_entrada, $a_jornada);
		$jornada_saida[$i]= @mktime($jornada_saida_hora[0], $jornada_saida_hora[1], $jornada_saida_hora[2], $m_jornada, $d_jornada_saida, $a_jornada);
		
		$horas_trabalhadas[$i]= $jornada_saida[$i]-$jornada_entrada[$i];
		
		$horas_trabalhadas_string= calcula_total_horas_ss($horas_trabalhadas[$i]);
		
		$horas_trabalhadas_int= explode(":", $horas_trabalhadas_string);
		
		$total_semana+=$horas_trabalhadas_int[0];
		
		//$jornada_entrada_hora2[$i]= @mktime($jornada_entrada_hora[0], $jornada_entrada_hora[1], $jornada_entrada_hora[2], 0, 0, 0);
		//$jornada_saida_hora2[$i]= @mktime($jornada_saida_hora[0], $jornada_saida_hora[1], $jornada_saida_hora[2], 0, 0, 0);
	}
	
	if ($rs_turno->dias_trabalhados_semana>0) return($total_semana/$rs_turno->dias_trabalhados_semana);
	else return(0);
}

function pega_tipo_pessoa_plural($i) {
	switch ($i) {
		case 'f': $tipo= "Fornecedores"; break;
		case 'c': $tipo= "Clientes"; break;
		case 'u': $tipo= "Funcion�rios"; break;
		case 'a': $tipo= "Empresas com sistema"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_tipo($i) {
	switch ($i) {
		case 'f': $tipo= "F�sica"; break;
		case 'j': $tipo= "Jur�dica"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_item_cedido($i) {
	$vetor= array();
	
	$vetor[1]= "Hampers";
	$vetor[2]= "Formul�rios de coleta";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_denominacao_extra($i) {
	$vetor= array();
	
	$vetor[1]= "EXTRA";
	$vetor[2]= "COMPLEMENTAR";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_turno_padrao($i) {
	$vetor= array();
	
	if ($i!='l') {
		$vetor[-3]= "Costura";
		$vetor[-2]= "Plant�o Domingo";
		$vetor[-1]= "Plant�o S�bado";
	}
	
	$vetor[1]= "Manh�";
	$vetor[2]= "Tarde";
	$vetor[3]= "Noite";
	$vetor[4]= "Madrugada";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_tipo_contrato($i) {
	$vetor= array();
	
	$vetor[0]= "Direto com cliente";
	$vetor[1]= "Outro";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_tipo_tecnico($i) {
	$vetor= array();
	
	$vetor[1]= "Funcion�rio";
	$vetor[2]= "Terceirizado";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_coleta_entrega($i) {
	$vetor= array();
	
	$vetor[1]= "Coleta";
	$vetor[2]= "Entrega";
	$vetor[3]= "Outros";
	$vetor[4]= "Coleta (extra)";
	$vetor[5]= "Entrega (extra)";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_finalidade_rm($i) {
	switch ($i) {
		case 'p': $tipo= "Preventiva"; break;
		case 'c': $tipo= "Corretiva"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_local_os($i) {
	switch ($i) {
		case '1': $tipo= "Interna"; break;
		case '2': $tipo= "Externa"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}


function pega_tipo_rm($i) {
	switch ($i) {
		case 'e': $tipo= "Equipamento"; break;
		case 'p': $tipo= "Predial"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_prioridade_rm($dias) {
	if ($dias==0) return("Imediato");
	elseif ($dias==1) return($dias ." dia");
	else return($dias ." dias");
}

function pega_manutencao_num_tecnico($id_tecnico) {
	$result= mysql_query("select * from man_tecnicos
							where id_tecnico= '$id_tecnico'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->num_tecnico);
}

function pega_manutencao_tecnico($id_tecnico) {
	$result= mysql_query("select * from man_tecnicos
							where id_tecnico= '$id_tecnico'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	if ($rs->id_funcionario==0) $nome_tecnico= $rs->nome_tecnico;
	else $nome_tecnico= pega_funcionario($rs->id_funcionario);
	
	return($nome_tecnico);
}

function pega_livro($id_livro) {
	$result= mysql_query("select * from com_livro
							where id_livro= '$id_livro'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->mensagem);
}

function pega_campo_livro($id_livro, $campo) {
	$result= mysql_query("select $campo as campo from com_livro
							where id_livro= '$id_livro'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->campo);
}

function pega_deposito($id_deposito	) {
	$result= mysql_query("select * from fi_depositos
							where id_deposito= '$id_deposito'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->deposito);
}

function pega_situacao_atual_rm($id_rm) {
	$result= mysql_query("select * from man_rms_andamento
							where id_rm= '$id_rm'
							order by id_rm_andamento desc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->id_situacao);
}

function pega_nota_situacao_atual_rm($id_rm) {
	$result= mysql_query("select nota from man_rms_andamento
							where id_rm= '$id_rm'
							and   id_situacao = '5'
							order by id_rm_andamento desc limit 1
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->nota);
}

function pega_situacao_atual_reclamacao($id_livro) {
	$result= mysql_query("select * from qual_reclamacoes_andamento
							where id_livro= '$id_livro'
							order by id_reclamacao_andamento desc limit 1
							") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
	if ($linhas>0) {
		$rs= mysql_fetch_object($result);
		return($rs->id_situacao);
	}
	else return(0);
}

function pega_passo_percurso($i) {
	switch ($i) {
		case 1: $tipo= "Sa�da da empresa"; break;
		case 2: $tipo= "Passagem em cliente"; break;
		case 3: $tipo= "Retorno � empresa"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_passo_percurso_resumido($i) {
	switch ($i) {
		case 1:
		case 2:
			$tipo= "Em rota"; break;
		case 3:
			$tipo= "Conclu�do"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_sexo($sexo) {
	if ($sexo=="m") return("Masculino");
	else return("Feminino");
}

function pega_trajeto($trajeto) {
	if ($trajeto==1) return("Ida");
	else return("Volta");
}

function pega_tipo_detalhamento_relatorio($tipo) {
	if ($tipo==1) return("Sint�tico");
	else return("Anal�tico");
}

function fnum($numero) {
	$quebra= explode(".", $numero);
	$tamanho= strlen($quebra[1]);
	
	return(number_format($numero, 2, ',', '.'));
}

function fnum2($numero) {
	$quebra= explode(".", $numero);
	$tamanho= strlen($quebra[1]);
	
	return(number_format($numero, $tamanho, ',', '.'));
}

function fnumi($numero) {
	$numero= (float) $numero;
	return(number_format($numero, 0, ',', '.'));
}

function fnumf($numero) {
	if ($numero!="") {
		$decimal= substr($numero, -2, 2);
		if ((strpos($numero, ".")) && ($decimal!="00")) return(fnum($numero));
		else return(fnumi($numero));
	} else return(0);
}

function fnumf_naozero($numero) {
	if (($numero!=0) && ($numero!="")) {
		$decimal= substr($numero, -2, 2);
		if ((strpos($numero, ".")) && ($decimal!="00")) return(fnum($numero));
		else return(fnumi($numero));
	}
}

function pega_numero_semana($ano, $mes, $dia) {
   return ceil(($dia + date("w", mktime(0, 0, 0, $mes, 1, $ano)))/7);   
} 

function eh_decimal($numero) {
	$decimal= substr($numero, -2, 2);
	if ($decimal!="00") return(true);
	else return(false);
}

function primeira_palavra($frase) {
	$retorno= explode(" ", $frase);
	return($retorno[0]);
}

function formata_saida($valor, $tamanho_saida) {
	//3, 5
	$tamanho= strlen($valor);
	
	for ($i=$tamanho; $i<$tamanho_saida; $i++)
		$saida .= '0';
		
	return($saida . $valor);
}

function calcula_idade($data_nasc) {
	$var= explode("/", $data_nasc, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
	
	$dia=$var[0];
	$mes=$var[1];
	$ano=$var[2];

	if (($data_nasc!="") && ($data_nasc!="00/00/0000") && ($ano<=date("Y"))) {
		
		$idade= date("Y")-$ano;
		if ($mes>date("m"))
			$idade--;
		if (($mes==date("m")) && ($dia>date("d")) )
			$idade--;
		return($idade);	}
	else
		return("<span class=\"vermelho\">N�o dispon�vel!</span>");
}

// ------------------------------- funcoes do ponto -------------------------------------------


function pega_tipo_batida($tipo) {
	if ($tipo==1) return("Entrada");
	else return("Sa�da");
}

function bate_ponto($id_funcionario, $data_batida, $hora, $tipo, $id_supervisor, $hl, $vale_dia, $turnante) {
	
	$arruma= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	//$arruma= mktime(4, 12, 44, 11, 03, 2010);
	
	$result_ponto= mysql_query("insert into rh_ponto (id_funcionario, data_batida, hora, data_hora_batida, tipo, id_supervisor, hl, vale_dia, turnante)
													values
													('$id_funcionario', '$data_batida', '$hora', '". $data_batida ." ". $hora ."', '$tipo', '$id_supervisor', '$hl', '$vale_dia', '$turnante')
													") or die(mysql_error());
	
	if ($result_ponto) {
		
		$data_aniversario= pega_aniversario($id_funcionario);
		
		if ($data_aniversario==date("d/m", $arruma)) {
			$mostra .= "
			<div id=\"informacao_batida_aniversario\"><blink>FELIZ ANIVERS�RIO!!!</blink></div><br /><br />";
			
		}
		
		if ($turnante==1) $li_turnante= "<li><strong>TURNANTE</strong></li>";
		
		$mostra .= "<div id=\"informacao_batida\" class=\"batida". $tipo ."\">". pega_tipo_batida($tipo) ."</div>";
		
		$mostra .= "<div id=\"nome_funcionario\">
			<ul>
				<li><strong>". pega_funcionario($id_funcionario) ."</strong></li>
				<li><strong>Departamento:</strong> ". pega_departamento(pega_dado_carreira("id_departamento", $id_funcionario)) ."</li>
				<li><strong>Turno:</strong> ". pega_turno(pega_dado_carreira("id_turno", $id_funcionario)) ."</li>
				<li><strong>Data de trabalho:</strong> ". desformata_data($vale_dia)."</li>
				<li><strong>Hor�rio registrado:</strong> ". $hora ."</li>
				$li_turnante
			</ul>
		</div>
		
		";
		$id_pessoa= pega_dado_carreira("id_pessoa", $id_funcionario);
		
		$foto= CAMINHO ."/pessoa_". $id_pessoa .".jpg";
		
		if (file_exists($foto))
			$mostra .= "<div id=\"foto\">
							<img src=\"includes/phpthumb/phpThumb.php?src=pessoa_". $id_pessoa .".jpg&amp;w=200&amp;zc=1&amp;far=T\" width=\"200\" />
							</div>";
		
		$mostra .=" <script type=\"text/javascript\" language=\"javascript\">
						var temporizador= setTimeout('resetaTelaPonto()', 7000);
					</script> ";
		
		echo $mostra;
		
		$retorno= mysql_insert_id();
	}
	else $retorno= false;
	
	return($retorno);
}

function pega_funcionarios_trabalhando_manual($id_empresa, $id_departamento, $id_turno_index, $vale_dia) {
	//plantao
	if ($id_turno_index==0) {
		$horas_divisor=11;
		$horas_multiplicador=1;
	}
	else {
		$horas_divisor=6;	
		$horas_multiplicador=1;
	}
	
	$result_des= mysql_query("select * from op_limpa_producao_funcionarios
								where id_empresa = '". $id_empresa ."'
								and   id_departamento = '". $id_departamento ."'
								and   data = '". $vale_dia ."'
								and   turno = '". $id_turno_index ."'
								");
	$total=0;
	
	while ($rs_des= mysql_fetch_object($result_des)) {
		$total+= ($rs_des->qtde_funcionarios*($rs_des->qtde_horas/$horas_divisor))*$horas_multiplicador;
	}
	
	return($total);
}

function pega_funcionarios_trabalhando_retroativo($id_empresa, $id_departamento, $id_turno_index, $vale_dia, $data_hora_entrada, $data_hora_saida, $identificar, $horas) {
	//echo soma_data_hora($data_hora, 0, 0, 0, 2, 0, 0) ."<br />";
	//echo $data_hora_saida; die();
	//die();
	
	if ($id_departamento=="2") $h= $id_turno_index;
	
	// 06:00:00
	// 12:00:00
	
	$num= array();
	
	$entrada_mk= faz_mk_data_completa($data_hora_entrada);
	$saida_mk= faz_mk_data_completa($data_hora_saida);
	
	for ($i=0; $i<$horas; $i++) {
		$num[$i]= 0;
		
		if ($id_departamento=="1") {
			if ($id_turno_index==4) $soma_periodo= 86400;
			else $soma_periodo= 0;
		}
		
		$min2_hora_base= date("H", $entrada_mk+$soma_periodo+($i*3600)+900);
		
		$result_del= mysql_query("delete from rh_ponto_producao_funcionarios
									where vale_dia = '". $vale_dia ."'
									and   hora_base = '". $min2_hora_base ."'
									and   original = '1'
									");
		
		$min1= date("Y-m-d H:i:s", $entrada_mk+$soma_periodo+($i*3600)-600); // 13:50
		$min2= date("Y-m-d H:i:s", $entrada_mk+$soma_periodo+($i*3600)+900); // 14:15
		
		$min2f= date("d/m/Y H:i:s", $entrada_mk+$soma_periodo+($i*3600)+900);
		
		$max1= date("Y-m-d H:i:s", $entrada_mk+$soma_periodo+($i*3600)+3600-600); // 14:50
		$max2= date("Y-m-d H:i:s", $entrada_mk+$soma_periodo+($i*3600)+3600+600); // 15:10
		
		$max1f= date("d/m/Y H:i:s", $entrada_mk+$soma_periodo+($i*3600)+3600-600);
		
		$result= mysql_query("select distinct(rh_ponto_virtual.id_funcionario), rh_carreiras.id_cargo from rh_ponto_virtual, rh_carreiras
								where rh_ponto_virtual.id_funcionario = rh_carreiras.id_funcionario
								and   rh_carreiras.id_departamento = '". $id_departamento ."'
								and   rh_carreiras.atual = '1'
								and   rh_ponto_virtual.vale_dia = '". $vale_dia ."'
								and   rh_ponto_virtual.tipo = '1'
								and   rh_ponto_virtual.data_hora_batida <= '$min2'
								/* and   rh_carreiras.id_cargo <> '10' */
								
								and   rh_ponto_virtual.id_funcionario NOT IN
								(
								select rh_ponto_virtual.id_funcionario from rh_ponto_virtual
								where rh_ponto_virtual.vale_dia = '". $vale_dia ."'
								and   rh_ponto_virtual.tipo = '0'
								and   rh_ponto_virtual.data_hora_batida <= '$max1'
								
								/*and   rh_ponto_virtual.data_hora_batida > '$min1'*/
								
								and   (
										( rh_ponto_virtual.data_hora_batida > '$min1' and   rh_ponto_virtual.tipo_aux = '2' )
										or
										( rh_ponto_virtual.tipo_aux = '0' )
									) 
								)
								") or die(mysql_error());
											
		$linhas= mysql_num_rows($result);
		
		//$num[$i]= $linhas;
		
		if ($identificar=="1") echo "<br /> Funcion�rios trabalhando entre <strong>$min2f</strong> e <strong>$max1f</strong> : <br />";
		
		$funcionarios_validos=0;
		
		while ($rs= mysql_fetch_object($result)) {
			
			if (($rs->id_cargo=="10") || ($rs->id_funcionario=="14")) {
				$trabalhou="0";
				$pre= "<strong>SUPERVISOR - N�O CONSIDERANDO... </strong>";
			}
			else {
				$trabalhou="1";
				$pre= "";
				
				$funcionarios_validos++;
			}
			
			if ($identificar=="1") echo $pre ."". pega_funcionario($rs->id_funcionario) ."<br />";
			
			$result2_pre= mysql_query("select * from rh_ponto_producao_funcionarios
												where id_funcionario = '". $rs->id_funcionario ."'
												and   id_departamento = '". $id_departamento ."'
												and   vale_dia = '". $vale_dia ."'
												and   hora_base = '". $min2_hora_base ."'
												") or die(mysql_error());
			$linhas2_pre= mysql_num_rows($result2_pre);
			
			if ($linhas2_pre==0)
				$result2_hora= mysql_query("insert into rh_ponto_producao_funcionarios
											(id_empresa, id_departamento, vale_dia, id_funcionario, id_turno_index, hora_base, trabalhou, original)
											values
											('". $id_empresa ."', '". $id_departamento ."', '". $vale_dia ."', '". $rs->id_funcionario ."',
											'". $id_turno_index ."', '". $min2_hora_base ."', '". $trabalhou ."', '1')
											");
			
			/*$result2= mysql_query("select rh_ponto.id_funcionario from rh_ponto
									where rh_ponto.vale_dia = '". $vale_dia ."'
									and   rh_ponto.tipo = '0'
									and   rh_ponto.data_hora_batida <= '". $max1 ."'
									and   rh_ponto.id_funcionario = '". $rs->id_funcionario ."'
									");
			$linhas2= mysql_num_rows($result2);
			*/
		}
		
		$num[$i]= $funcionarios_validos;
		
		//echo "<br /><br /><br />";
	}
	
	if ($identificar=="1") {
		echo "<br />-----------------------------------------------------------------------------------------------------------------------------------------<br />";
	}
	
	//die();
	
	$soma= 0;
	
	for ($i=0; $i<$horas; $i++) {
		$soma+= $num[$i];
	}
	
	$media= $soma/$horas;
	
	return($media);
}

function pega_funcionarios_trabalhando_retroativo_plantao($id_departamento, $vale_dia) {
	//echo soma_data_hora($data_hora, 0, 0, 0, 2, 0, 0) ."<br />";
	//die();
	
	$result= mysql_query("select distinct(rh_ponto.id_funcionario) from rh_ponto, rh_carreiras
							where rh_ponto.id_funcionario = rh_carreiras.id_funcionario
							and   rh_carreiras.atual = '1'
							and   rh_carreiras.id_cargo <> '10'
							and   rh_ponto.id_funcionario IN
							(
							select rh_funcionarios.id_funcionario from rh_funcionarios, rh_carreiras
							where  rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
							and    rh_carreiras.id_departamento = '". $id_departamento ."'
							and    rh_carreiras.atual = '1'
							)
							and   rh_ponto.vale_dia = '". $vale_dia ."'
							") or die(mysql_error());
							
	return(mysql_num_rows($result));
}

function pega_funcionarios_trabalhando($id_departamento) {
	
	$arruma= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	//$arruma= mktime(4, 12, 44, 11, 03, 2010);
	
	if (date("H", $arruma)<6) $dia=1;
	else $dia=0;
	
	$data= date("Y-m-d", mktime(0, 0, 0, date("m", $arruma), date("d", $arruma)-$dia, date("Y", $arruma)));
	
	//echo $data;
	
	$result= mysql_query("select rh_funcionarios.id_funcionario from rh_ponto, rh_funcionarios, rh_carreiras
							where rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
							and   rh_carreiras.id_departamento = '". $id_departamento ."'
							and   rh_ponto.id_funcionario = rh_funcionarios.id_funcionario
							and   rh_funcionarios.status_funcionario = '1'
							and   rh_carreiras.atual = '1'
							and   rh_ponto.vale_dia = '". $data ."'
							");
	$total_funcionarios=0;
	while ($rs= mysql_fetch_object($result)) {
		
		$result2= mysql_query("select * from rh_ponto
								where id_funcionario = '". $rs->id_funcionario ."'
								and   vale_dia = '". $data ."'
								order by data_batida desc, hora desc
								limit 1
								");
								
								/*echo "select * from rh_ponto
								where id_funcionario = '". $rs->id_funcionario ."'
								and   vale_dia = '". $data ."'
								order by data_batida desc, hora desc
								limit 1
								";*/
		$rs2= mysql_fetch_object($result2);
		
		if ($rs2->tipo==1) { $total_funcionarios++; }
	}
	
	return($total_funcionarios);
}

function retorna_intervalo($datac1, $datac2) {
	//echo $datac1 ."/". $datac2;
	
	$anterior= explode(" ", $datac1);
	$hora1= explode(":", $anterior[1]);
	$data1= explode("-", $anterior[0]);
	$completa1= @mktime($hora1[0], $hora1[1], $hora1[2], $data1[1], $data1[2], $data1[0]);
	
	$proxima= explode(" ", $datac2);
	$hora2= explode(":", $proxima[1]);
	$data2= explode("-", $proxima[0]);
	$completa2= @mktime($hora2[0], $hora2[1], $hora2[2], $data2[1], $data2[2], $data2[0]);
	
	$diferenca= $completa2-$completa1;
	
	return($diferenca);
}

function pode_bater_ou_menor($horario_ponto) {
	$arruma= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	//$arruma= mktime(4, 12, 44, 11, 03, 2010);
	
	$horario_atual= date("Y-m-d H:i:s", $arruma);
	//echo $horario_ponto;
	
	$diferenca= retorna_intervalo($horario_atual, $horario_ponto);
	//echo calcula_total_horas($diferenca);
	
	//echo "------------------<br>". $horario_atual ."<br>";
	//echo $horario_ponto;
	
	//600 segundos = 10 minutinhos
	if ($diferenca>-600) return(true);
	else return(false);
}

function pode_bater($horario_ponto) {
	$arruma= mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
	//$arruma= mktime(4, 12, 44, 11, 03, 2010);
	
	$horario_atual= date("Y-m-d H:i:s", $arruma);
	//echo $horario_ponto;
	
	$diferenca= retorna_intervalo($horario_atual, $horario_ponto);
	//echo $diferenca;
	//600 segundos = 10 minutinhos
	if ( ($diferenca>-600) && ($diferenca<600) )
		return(true);
	else
		return(false);
}

function pega_jornada_diaria($id_turno, $id_dia) {
	
	$result= mysql_query("select * from rh_turnos_horarios
								where id_turno = '$id_turno'
								and   id_dia = '$id_dia'
								");
	$rs= mysql_fetch_object($result);
	
	$horae= explode(':', $rs->entrada);
	$horas= explode(':', $rs->saida);
	
	$dia= 10; $mes= 10; $ano= 2008;
	
	$entrada= @date("Y-m-d H:i:s", mktime($horae[0], $horae[1], $horae[2], $mes, $dia, $ano));
	//se a hora da saida for menor que a entrada (sai no outro dia)
	if ($horae[0]>$horas[0]) $dia= 11;
	
	$saida= @date("Y-m-d H:i:s", mktime($horas[0], $horas[1], $horas[2], $mes, $dia, $ano));
	
	/*if ($id_dia==0) {
		echo $entrada;
		
		die();
	}*/
	
	//intervalo total entra o inicio e o fim da jornada di�ria
	$jornada_diaria= retorna_intervalo($entrada, $saida);
	return($jornada_diaria);
}

function tem_escala_mes($data, $id_departamento) {
	$data= explode('-', $data);
	
	$dia= $data[2]; $mes= $data[1]; $ano= $data[0];
	
	$data1= date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
	
	$dias_mes= date("t", mktime(0, 0, 0, $mes, 1, $ano));
	$data2= date("Y-m-d", mktime(0, 0, 0, $mes, $dias_mes, $ano));
		
	$result= mysql_query("select rh_escala.* from rh_escala, rh_funcionarios, rh_carreiras
							where rh_escala.id_funcionario = rh_funcionarios.id_funcionario
							and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
							and   rh_carreiras.atual = '1'
							and   rh_carreiras.id_departamento = '$id_departamento'
							and   (rh_escala.data_escala >= '$data1'
									and
									rh_escala.data_escala <= '$data2')
							");
	
	if (mysql_num_rows($result)>0) return (true);
	else return(false);
}

function pega_duracao_intervalo_dia($id_turno, $id_dia) {
	$result_intervalo= mysql_query("select * from rh_turnos_intervalos, rh_turnos_intervalos_horarios
										where rh_turnos_intervalos.id_turno = '$id_turno'
										and   rh_turnos_intervalos.id_intervalo = rh_turnos_intervalos_horarios.id_intervalo
										and   rh_turnos_intervalos_horarios.id_dia = '$id_dia'
										and   rh_turnos_intervalos_horarios.automatico = '0'
										");
	
	if (mysql_num_rows($result_intervalo)>0) {
		//echo $id_dia .': '. $jornada_diaria .'<br>';
		
		$rs_intervalo= mysql_fetch_object($result_intervalo);
		$duracao_intervalo= explode(':', $rs_intervalo->intervalo_duracao);
		$intervalo= date("H:i:s", mktime($duracao_intevalo[0]+$duracao_intevalo[0], $duracao_intevalo[1], $duracao_intevalo[2], 0, 0, 0));
		
		$intervalo= retorna_intervalo("2008-10-10 ". $intervalo, "2008-10-10 ". $rs_intervalo->intervalo_duracao);
		//jornada di�ria, descontando o intervalo, descontando 4 x 10min
		//$jornada_diaria= (($jornada_diaria-$intervalo));
		$retorno= $intervalo;
		//echo $intervalo .'<br>';
	}
	else $retorno=0;
	
	return($retorno);
}

function tem_intervalo_no_dia($id_intervalo, $id_dia) {
	$result= mysql_query("select * from rh_turnos_intervalos_horarios
								where id_intervalo = '$id_intervalo'
								and   id_dia = '$id_dia'
								and   automatico = '0'
								");
	if (mysql_num_rows($result)>0) return(true);
	else return(false);
}

function pega_id_pedido($id_empresa, $id_cliente, $data, $id_usuario) {
	
	$result_pre= mysql_query("select * from op_pedidos
						 		where id_empresa = '". $id_empresa ."'
								and   id_cliente = '". $id_cliente ."'
								and   data_pedido = '". formata_data($data) ."'
								");
								
	$linhas_pre= mysql_num_rows($result_pre);
	
	//ainda nao gerou o pedido
	if ($linhas_pre==0) {
		$result= mysql_query("insert into op_pedidos
							 		(id_empresa, id_cliente, num_pedido, data_pedido, id_usuario)
									values
									('$id_empresa', '$id_cliente', '$num_pedido', '". formata_data($data) ."', '$id_usuario')
									");
		$id_pedido= mysql_insert_id();
	}
	else {
		$rs_pre= mysql_fetch_object($result_pre);
		$id_pedido= $rs_pre->id_pedido;
	}
	
	return($id_pedido);
}

function pega_cliente_tipo($id_cliente_tipo) {
	$result= mysql_query("select * from fi_clientes_tipos
						 		where id_cliente_tipo = '$id_cliente_tipo'
								");
	$rs= mysql_fetch_object($result);
	return($rs->cliente_tipo);
}

function pega_basear_nota_data($id_cliente) {
	$result= mysql_query("select * from pessoas
						 		where id_pessoa = '$id_cliente'
								");
	$rs= mysql_fetch_object($result);
	return($rs->basear_nota_data);
}

function pega_tipo_pedido($id_cliente) {
	$result= mysql_query("select * from pessoas
						 		where id_pessoa = '$id_cliente'
								");
	$rs= mysql_fetch_object($result);
	return($rs->tipo_pedido);
}

function pega_id_contrato($id_cliente) {
	$result= mysql_query("select * from pessoas
						 		where id_pessoa = '$id_cliente'
								");
	$rs= mysql_fetch_object($result);
	return($rs->id_contrato);
}

function pega_pesagem_cliente_contrato($id_contrato) {
	$result= mysql_query("select pesagem_cliente from fi_contratos
						 		where id_contrato = '$id_contrato'
								");
	$rs= mysql_fetch_object($result);
	return($rs->pesagem_cliente);
}

function pega_contrato($id_contrato) {
	$result= mysql_query("select * from fi_contratos
						 		where id_contrato = '$id_contrato'
								");
	$rs= mysql_fetch_object($result);
	return($rs->contrato);
}

function pega_qtde_padrao_pacote($id_peca) {
	$result= mysql_query("select qtde_padrao_pacote from op_limpa_pecas
						 		where id_peca = '$id_peca'
								");
	$rs= mysql_fetch_object($result);
	return($rs->qtde_padrao_pacote);
}

function pega_num_ultima_pessoa($id_empresa, $tipo_pessoa) {
	$result= mysql_query("select * from pessoas_tipos
						 		where id_empresa = '$id_empresa'
								and   tipo_pessoa = '$tipo_pessoa'
								order by id_pessoa desc limit 1
								");
	$rs= mysql_fetch_object($result);
	return($rs->num_pessoa);
}

function pega_num_ultima_rm($id_empresa) {
	$result= mysql_query("select num_rm from man_rms
						 		where id_empresa = '$id_empresa'
								order by id_rm desc limit 1
								");
	$rs= mysql_fetch_object($result);
	return($rs->num_rm);
}

function pega_num_ultima_os($id_empresa) {
	$result= mysql_query("select num_os from man_oss
						 		where id_empresa = '$id_empresa'
								order by id_os desc limit 1
								");
	$rs= mysql_fetch_object($result);
	return($rs->num_os);
}


function pega_num_filhos($id_funcionario) {
	$result= mysql_query("select * from rh_funcionarios_filhos
								where id_funcionario = '$id_funcionario'
								");
	$linhas= mysql_num_rows($result);
	return($linhas);
}

function tem_hl($id_funcionario, $id_dia) {
	$rs= mysql_fetch_object(mysql_query("select * from rh_funcionarios, rh_carreiras, rh_turnos, rh_turnos_horarios
												where rh_funcionarios.id_funcionario = '$id_funcionario'
												and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
												and   rh_carreiras.atual = '1'
												and   rh_carreiras.id_turno = rh_turnos.id_turno
												and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
												and   rh_turnos_horarios.id_dia = '$id_dia' "));
	if ($rs->hl==1) return (true);
	else return (false);
}

function pega_nome_ad($id_ad) {
	$rs= mysql_fetch_object(mysql_query("select nome from tr_clientes_ad
												where id_ad = '$id_ad'
												"));
	return ($rs->nome);
}

function pega_aniversario($id_funcionario) {
	$rs= mysql_fetch_object(mysql_query("select DATE_FORMAT(data, '%d/%m') as data2 from rh_funcionarios, pessoas
												where rh_funcionarios.id_pessoa = pessoas.id_pessoa
												and   rh_funcionarios.id_funcionario = '$id_funcionario'
												"));
	return ($rs->data2);
}

// ------------------------------- funcoes do ponto -------------------------------------------

function alerta_aniversariantes($id_empresa) {
	inicia_transacao();
	$var=0;
	
	$result= mysql_query("select *,
								DATE_FORMAT(pessoas.data, '%d') as dia,
								DATE_FORMAT(pessoas.data, '%m') as mes,
								DATE_FORMAT(pessoas.data, '%Y') as ano,
								DATE_FORMAT(pessoas.data, '%d/%m/%Y') as data2
								from  pessoas, rh_funcionarios
								where pessoas.id_empresa = '$id_empresa'
								and   pessoas.id_pessoa = rh_funcionarios.id_pessoa
								and   rh_funcionarios.status_funcionario = '1'
								") or die(mysql_error());
	while ($rs= mysql_fetch_object($result)) {
		
		$ano= date("Y");
		if (($rs->mes==01) && (date("m")==12)) $ano_teste= $ano+1;
		else $ano_teste= $ano;
		
		$data_aniversario= mktime(0, 0, 0, $rs->mes, $rs->dia, $ano_teste);
		$hoje= mktime(0, 0, 0, date("m"), date("d"), $ano);
		$diferenca= ($data_aniversario-$hoje);
		
		$dias= round(($diferenca/60/60/24));
		
		if (($dias==0) || ($dias==2)) {
			
			$result_teste= mysql_query("select * from rh_funcionarios_aniversarios_alertas
										where id_empresa= '". $_SESSION["id_empresa"] ."'
										and   id_funcionario = '". $rs->id_funcionario ."'
										and   ano = '$ano_teste'
										and   antecedencia = '$dias'
										");
			$linhas_teste= mysql_num_rows($result_teste);
			
			if ($linhas_teste==0) {
				$result_destinatarios= mysql_query("select * from pessoas, rh_funcionarios, rh_carreiras, rh_departamentos, usuarios
													where rh_carreiras.atual = '1'
													and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
													and   rh_carreiras.id_departamento = rh_departamentos.id_departamento
													and   rh_departamentos.alerta_aniversariantes = '1'
													and   pessoas.id_pessoa = rh_funcionarios.id_pessoa
													and   usuarios.id_funcionario = rh_funcionarios.id_funcionario
													and   (rh_funcionarios.status_funcionario = '1' or rh_funcionarios.status_funcionario = '-1')
													") or die(mysql_error());
				
				//se tem alguem pra mandar
				if (mysql_num_rows($result_destinatarios)>0) {
					$result_fixa= mysql_query("insert into rh_funcionarios_aniversarios_alertas
														(id_empresa, id_funcionario, ano, antecedencia)
														values
														('". $_SESSION["id_empresa"] ."', '". $rs->id_funcionario ."',
														'$ano_teste', '$dias'
														)
														") or die(mysql_query());
					if (!$result_fixa) $var++;
					
					while ($rs_destinatarios= mysql_fetch_object($result_destinatarios)) {
						if ($dias==0) $periodo= "(hoje)";
						else $periodo= "(daqui 2 dias)";
						
						$id_depto_carreira= pega_dado_carreira("id_departamento", $rs->id_funcionario);
						$id_turno_carreira= pega_dado_carreira("id_turno", $rs->id_funcionario);
						
						$corpo_mensagem= "Ol� <strong>". $rs_destinatarios->nome_rz ."</strong>,
											s� para avisar que o funcion�rio <strong>". $rs->nome_rz ."</strong> do setor <strong>". pega_departamento($id_depto_carreira) ."</strong>, turno <strong>". pega_turno($id_turno_carreira) ."</strong>
											est� fazendo anivers�rio no dia <strong>". $rs->dia ."/". $rs->mes ."</strong>.<br /><br />";
						
						$retorno= enviar_mensagem($_SESSION["id_empresa"], 0, $rs_destinatarios->id_pessoa, "Anivers�rio de funcion�rio - <strong>". $rs->nome_rz ."</strong> em <strong>". $rs->dia ."/". $rs->mes ."</strong> ". $periodo, $corpo_mensagem);
						if (!$retorno) $var++;
						
						//echo "Anivers�rio de <strong>". $rs->nome_rz ."</strong> em <strong>". $rs->data2 ."</strong> ". $periodo;
					}
				}
			}
		}
	}
	
	finaliza_transacao($var);
}

function alerta_aniversariantes_clientes($id_empresa) {
	inicia_transacao();
	$var=0;
	
	$result= mysql_query("select *, DATE_FORMAT(tr_clientes_ad.data_nasc, '%d') as dia,
								DATE_FORMAT(tr_clientes_ad.data_nasc, '%m') as mes,
								DATE_FORMAT(tr_clientes_ad.data_nasc, '%Y') as ano,
								DATE_FORMAT(tr_clientes_ad.data_nasc, '%d/%m/%Y') as data2
								from  tr_clientes_ad, pessoas, pessoas_tipos
								where tr_clientes_ad.id_empresa = '$id_empresa'
								and   tr_clientes_ad.id_cliente = pessoas.id_pessoa
								and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
								") or die(mysql_error());
	while ($rs= mysql_fetch_object($result)) {
		
		$ano= date("Y");
		if (($rs->mes==01) && (date("m")==12)) $ano_teste= $ano+1;
		else $ano_teste= $ano;
		
		$data_aniversario= mktime(0, 0, 0, $rs->mes, $rs->dia, $ano_teste);
		$hoje= mktime(0, 0, 0, date("m"), date("d"), $ano);
		$diferenca= ($data_aniversario-$hoje);
		
		$dias= round(($diferenca/60/60/24));
		
		if (($dias==0) || ($dias==2)) {
			
			$result_teste= mysql_query("select * from tr_clientes_aniversarios_alertas
										where id_empresa= '". $_SESSION["id_empresa"] ."'
										and   id_ad = '". $rs->id_ad."'
										and   ano = '$ano_teste'
										and   antecedencia = '$dias'
										");
			$linhas_teste= mysql_num_rows($result_teste);
			
			if ($linhas_teste==0) {
				$result_destinatarios= mysql_query("select * from pessoas, rh_funcionarios, rh_carreiras, rh_departamentos, usuarios
													where rh_carreiras.atual = '1'
													and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
													and   rh_carreiras.id_departamento = rh_departamentos.id_departamento
													and   rh_departamentos.alerta_aniversariantes_clientes = '1'
													and   pessoas.id_pessoa = rh_funcionarios.id_pessoa
													and   usuarios.id_funcionario = rh_funcionarios.id_funcionario
													and   rh_funcionarios.status_funcionario = '1'
													") or die(mysql_error());
				
				//se tem alguem pra mandar
				if (mysql_num_rows($result_destinatarios)>0) {
					$result_fixa= mysql_query("insert into tr_clientes_aniversarios_alertas
														(id_empresa, id_ad, ano, antecedencia)
														values
														('". $_SESSION["id_empresa"] ."', '". $rs->id_ad ."',
														'$ano_teste', '$dias'
														)
														") or die(mysql_query());
					if (!$result_fixa) $var++;
					
					while ($rs_destinatarios= mysql_fetch_object($result_destinatarios)) {
						if ($dias==0) $periodo= "(hoje)";
						else $periodo= "(daqui 2 dias)";
						
						$corpo_mensagem= "Ol� <strong>". $rs_destinatarios->nome_rz ."</strong>,
											s� para avisar que <strong>". $rs->nome ."</strong> (". pega_tipo_pessoa($rs->tipo_pessoa) ." &raquo; <strong>". $rs->nome_rz ."</strong> &raquo; Cargo <strong>". $rs->cargo ."</strong> do setor <strong>". $rs->setor ."</strong>)
											est� fazendo anivers�rio no dia <strong>". $rs->dia ."/". $rs->mes ."</strong>.<br /><br />";
						
						$retorno= enviar_mensagem($_SESSION["id_empresa"], 0, $rs_destinatarios->id_pessoa, "Anivers�rio de ". pega_tipo_pessoa($rs->tipo_pessoa) ." - <strong>". $rs->nome ."</strong> em <strong>". $rs->dia ."/". $rs->mes ."</strong> ". $periodo, $corpo_mensagem);
						if (!$retorno) $var++;
						
						//echo "Anivers�rio de <strong>". $rs->nome_rz ."</strong> em <strong>". $rs->data2 ."</strong> ". $periodo;
					}
				}
			}
		}
	}
	
	finaliza_transacao($var);
}

function alerta_documentos($id_empresa) {
	$result_dc= mysql_query("select *,
								DATE_FORMAT(data_vencimento, '%d') as dia,
								DATE_FORMAT(data_vencimento, '%m') as mes,
								DATE_FORMAT(data_vencimento, '%Y') as ano,
								DATE_FORMAT(data_vencimento, '%d/%m/%Y') as data_vencimento2
								from  dc_documentos
								where id_empresa = '$id_empresa'
								and   alerta_dias <> ''
								and   alerta_dias <> '0'
								and   data_vencimento >= '". date("Ymd") ."'
								and   id_mensagem = '0'
								") or die(mysql_error());
	
	while ($rs_dc= mysql_fetch_object($result_dc)) {
		
		$data_vencimento= mktime(0, 0, 0, $rs_dc->mes, $rs_dc->dia, $rs_dc->ano);
		$hoje= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$diferenca= ($data_vencimento-$hoje);
		
		$dias= round(($diferenca/60/60/24));
		
		if ($dias<=$rs_dc->alerta_dias) {
			$id_pessoa= pega_id_pessoa_do_usuario($rs_dc->id_usuario);
			$corpo_mensagem= "Ol� ". pega_pessoa($id_pessoa) .", este � um lembrete enviado pelo sistema solicitado por voc�.<br /><br />
				O documento <strong>". $rs_dc->documento ."</strong> est� vencendo no dia <strong>". $rs_dc->data_vencimento2 ."</strong>, faltam <strong>". $rs_dc->alerta_dias ."</strong> dia(s) ou menos para esta data.<br /><br />
				
				<ul class=\"recuo1\">
					<li>Para acessar a pasta em que este documento se encontra, <a href=\"./?pagina=dc/documento_listar&id_pasta=". $rs_dc->id_pasta ."\">clique aqui</a>.</li>
					<li>Para acessar diretamente este documento, <a href=\"./?pagina=dc/documento&acao=e&id_documento=". $rs_dc->id_documento ."\">clique aqui</a>.</li>
				</ul>
				";
			
			$retorno= enviar_mensagem($_SESSION["id_empresa"], 0, $id_pessoa, "Aviso de vencimento de documento c�d. ". $rs_dc->id_documento, $corpo_mensagem);
			
			if ($retorno!=0) mysql_query("update dc_documentos set id_mensagem= '$retorno'
										 	where id_documento = '". $rs_dc->id_documento ."'
											limit 1
											");
		}
	}
	
}

function enviar_mensagem($id_empresa, $de, $para, $titulo, $mensagem) {
	$result= mysql_query("insert into com_mensagens
						 	(id_empresa, de, para, titulo, mensagem, data_mensagem, hora_mensagem,
							 lida, situacao_de, situacao_para, auth)
							values
							('$id_empresa', '$de', '$para', '$titulo', '$mensagem', '". date("Ymd") ."', '". date("His") ."',
							 '0', '1', '1', '". gera_auth() ."')
							");
	if ($result) return(mysql_insert_id());
	else return(0);
}

function mensagem_nova($id_usuario) {
	$id_pessoa= pega_id_pessoa_do_usuario($id_usuario);
	
	$result= mysql_query("select id_mensagem from com_mensagens
								 	where situacao_para='1'
									and para= '". $id_pessoa ."'
									and   lida= '0' ") or die(mysql_error());
	
	if (mysql_num_rows($result)>0) return(true);
	else return(false);
}

function pega_tipo_separacao($tipo) {
	if ($tipo=="1") return("In�cio");
	else return("Fim");
}

function verifica_backup() {
	//$data= date("Y-m-d");
	//$result_pre= mysql_query("select * from backups where data_backup = '". $data ."' ");
	
	//if (mysql_num_rows($result_pre)==0)
		header("location: includes/backup/backup.php");
		
	//else echo "Backup j� feito no dia de hoje!";
		
}

function pega_dado_carreira($dado, $id_funcionario) {
	$result= mysql_query("select ". $dado ." as dado from rh_funcionarios, rh_carreiras
												where rh_funcionarios.id_funcionario = '$id_funcionario'
												and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
												and   rh_carreiras.atual = '1' ");
    $rs= mysql_fetch_object($result);
    
    return($rs->dado);
}

function pega_setor_cliente($id_setor) {
	$result= mysql_query("select setor from fi_clientes_setores
							where id_cliente_setor = '$id_setor'
							");
    $rs= mysql_fetch_object($result);
    
    return($rs->setor);
}

function pega_dado_remessa($dado, $id_remessa) {
	$result= mysql_query("select ". $dado ." as dado from op_suja_remessas
												where id_remessa = '". $id_remessa ."'
												");
    $rs= mysql_fetch_object($result);
    
    return($rs->dado);
}

function pega_data_admissao($id_funcionario) {
	$result= mysql_query("select DATE_FORMAT(rh_carreiras.data, '%d/%m/%Y') as data_carreira from rh_carreiras
												where id_funcionario = '$id_funcionario'
												and   rh_carreiras.id_acao_carreira = '1' ");
    $rs= mysql_fetch_object($result);
    
    return($rs->data_carreira);
}

function pega_percurso($id_percurso) {
	if (($id_percurso!="") && ($id_percurso!="0")) {
		$result= mysql_query("select * from tr_percursos, tr_percursos_passos
								where tr_percursos.id_percurso = '$id_percurso'
								and   tr_percursos_passos.id_percurso = tr_percursos.id_percurso
								and   tr_percursos_passos.passo = '1'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		return(primeira_palavra(pega_funcionario($rs->id_motorista)) ." | ". desformata_data($rs->data_percurso) ." ". substr($rs->hora_percurso, 0, 5));
	} else return("-");
}

function pega_id_percurso_da_remessa($id_remessa) {
	$result= mysql_query("select id_percurso from op_suja_remessas
							where id_remessa = '$id_remessa'
							");
    $rs= mysql_fetch_object($result);
    return($rs->id_percurso);
}

function pega_data_demissao($id_funcionario) {
	$result= mysql_query("select DATE_FORMAT(rh_carreiras.data, '%d/%m/%Y') as data_carreira from rh_carreiras
												where id_funcionario = '$id_funcionario'
												and   rh_carreiras.id_acao_carreira = '2' ");
    $rs= mysql_fetch_object($result);
    
    return($rs->data_carreira);
}

function soma_data($data, $dias, $meses, $anos) {
	if (strpos($data, "-")) {
		$dia_controle= explode('-', $data);
		$data= date("Y-m-d", mktime(0, 0, 0, $dia_controle[1]+$meses, $dia_controle[2]+($dias), $dia_controle[0]+$anos));
	}
	elseif (strpos($data, "/")) {
		$dia_controle= explode('/', $data);
		$data= date("d/m/Y", mktime(0, 0, 0, $dia_controle[1]+$meses, $dia_controle[0]+($dias), $dia_controle[2]+$anos));
	}
	else {
		//$dia_controle= explode('/', $data);
		$data= date("Ymd", mktime(0, 0, 0, substr($data, 4, 2)+$meses, substr($data, 6, 2)+$dias, substr($data, 0, 4)+$anos));
	}
    
    return($data);
}

function soma_data_hora($data_hora, $dias, $meses, $anos, $horas, $minutos, $segundos) {
	
	//2009-10-10 10:11:12
	if (strpos($data_hora, "-")) {
		$ano= substr($data_hora, 0, 4);
		$mes= substr($data_hora, 5, 2);
		$dia= substr($data_hora, 8, 2);
		$hora= substr($data_hora, 11, 2);
		$minuto= substr($data_hora, 14, 2);
		$segundo= substr($data_hora, 17, 2);
		
		$data= date("Y-m-d H:i:s", mktime($hora+$horas, $minuto+$minutos, $segundo+$segundos, $mes+$meses, $dia+$dias, $ano+$anos));
	}
	//10/10/2009 10:11:12
	elseif (strpos($data_hora, "/")) {
		$ano= substr($data_hora, 6, 4);
		$mes= substr($data_hora, 3, 2);
		$dia= substr($data_hora, 0, 2);
		$hora= substr($data_hora, 11, 2);
		$minuto= substr($data_hora, 14, 2);
		$segundo= substr($data_hora, 17, 2);
		
		$data= date("Y-m-d H:i:s", mktime($hora+$horas, $minuto+$minutos, $segundo+$segundos, $mes+$meses, $dia+$dias, $ano+$anos));
	}
    
    return($data);
}

function pega_id_empresa_da_pessoa($id_pessoa) {
	$rs= mysql_fetch_object(mysql_query("select id_empresa from empresas
											where id_pessoa = '$id_pessoa' "));
	return($rs->id_empresa);
}

function pega_empresa_atendente($id_pessoa) {
	$rs= mysql_fetch_object(mysql_query("select id_empresa_atendente from pessoas
											where id_pessoa = '$id_pessoa' "));
	return($rs->id_empresa_atendente);
}

function pega_empresa_rel($id_funcionario) {
	$rs= mysql_fetch_object(mysql_query("select id_empresa_rel from rh_funcionarios
											where id_funcionario = '$id_funcionario' "));
	if ($rs->id_empresa_rel!="") return($rs->id_empresa_rel);
	else return(4);
}


function pega_pessoa($id_pessoa) {
	if ($id_pessoa==0) return(pega_empresa($_SESSION["id_empresa"]));
	else {
		$result= mysql_query("select * from pessoas, pessoas_tipos
												where pessoas.id_pessoa = '$id_pessoa'
												and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
												") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		if ($rs->tipo_pessoa=="c") $nome= $rs->apelido_fantasia;
		else $nome= $rs->nome_rz;
		
		return($nome);
	}
}

function pega_nome_pelo_id_usuario($id_usuario) {
	$rs_pre= mysql_fetch_object(mysql_query("select id_funcionario, id_empresa, id_departamento from usuarios
												where id_usuario = '$id_usuario' "));
	
	if (($rs_pre->id_funcionario!="") && ($rs_pre->id_funcionario!="0"))
		$nome= pega_funcionario($rs_pre->id_funcionario);
	elseif (($rs_pre->id_departamento!="") && ($rs_pre->id_departamento!="0"))
		$nome= pega_departamento($rs_pre->id_departamento);
	else
		$nome= pega_empresa($rs_pre->id_empresa);
	
	return($nome);
}

function pega_veiculo($id_veiculo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_veiculos
												where id_veiculo = '$id_veiculo' "));
	return($rs->veiculo ." ". $rs->placa);
}

function pega_placa_veiculo($id_veiculo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_veiculos
												where id_veiculo = '$id_veiculo' "));
	return($rs->placa);
}

function pega_equipamento($id_equipamento) {
	$rs= mysql_fetch_object(mysql_query("select * from op_equipamentos
												where id_equipamento = '$id_equipamento' "));
	return($rs->equipamento);
}

function pega_processo($id_processo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_equipamentos_processos
												where id_processo = '$id_processo' "));
	return($rs->codigo);
}

function pega_processo_nome($id_processo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_equipamentos_processos
												where id_processo = '$id_processo' "));
	return($rs->processo);
}

function pega_processo_tempo($id_processo) {
	$rs= mysql_fetch_object(mysql_query("select tempo from op_equipamentos_processos
												where id_processo = '$id_processo' "));
	return($rs->tempo);
}

function pega_codigo_do_veiculo($id_veiculo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_veiculos
												where id_veiculo = '$id_veiculo' "));
	return($rs->codigo);
}

function pega_codigo_do_cliente($id_cliente) {
	$rs= mysql_fetch_object(mysql_query("select * from pessoas
												where id_pessoa = '$id_cliente' "));
	return($rs->codigo);
}

function pega_id_cliente_pelo_codigo($codigo) {
	$rs= mysql_fetch_object(mysql_query("select id_pessoa from pessoas
												where codigo = '$codigo' "));
	return($rs->id_pessoa);
}

function pega_codigo_do_equipamento($id_equipamento) {
	$rs= mysql_fetch_object(mysql_query("select * from op_equipamentos
												where id_equipamento = '$id_equipamento' "));
	return($rs->codigo);
}

function pega_codigo_do_processo($id_processo) {
	$rs= mysql_fetch_object(mysql_query("select * from op_equipamentos_processos
												where id_processo = '$id_processo' "));
	return($rs->codigo);
}

function pega_funcionario($id_funcionario) {
	//if ($id_funcionario==0) return("Sistema SiGE");
	//else {
		$rs= mysql_fetch_object(mysql_query("select pessoas.nome_rz from pessoas, rh_funcionarios
													where pessoas.id_pessoa = rh_funcionarios.id_pessoa 
													and   rh_funcionarios.id_funcionario = '$id_funcionario' "));
		return($rs->nome_rz);
	//}
}

function pega_status_funcionario($id_funcionario) {
	$rs= mysql_fetch_object(mysql_query("select status_funcionario from rh_funcionarios
												where id_funcionario = '$id_funcionario' "));
	return($rs->status_funcionario);
}

function pega_tamanho_uniforme($tipo) {
	$vetor= array();
	
	$vetor[1]= "PP";
	$vetor[2]= "P";
	$vetor[3]= "M";
	$vetor[4]= "G";
	$vetor[5]= "GG";
	$vetor[6]= "EG";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_periodo_turno($tipo) {
	$vetor= array();
	
	$vetor[1]= "Manh�";
	$vetor[2]= "Tarde";
	$vetor[3]= "Noite";
	$vetor[4]= "Madrugada";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_escolaridade($tipo) {
	$vetor= array();
	
	$vetor[1]= "Ensino fundamental incompleto";
	$vetor[2]= "Ensino fundamental completo";
	$vetor[3]= "Ensino m�dio incompleto";
	$vetor[4]= "Ensino m�dio completo";
	$vetor[5]= "Curso superior incompleto";
	$vetor[6]= "Curso superior completo";
	$vetor[7]= "Mestrado";
	$vetor[8]= "Doutorado";
	$vetor[9]= "N�vel t�cnico incompleto";
	$vetor[10]= "N�vel t�cnico completo";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function xpega_acompanhamento_atividades($tipo) {
	$vetor= array();
	
	$vetor[1]= "Presen�a dos funcion�rios";
	$vetor[2]= "Leitura do livro eletr�nico";
	$vetor[3]= "Visualiza��o do peso de roupa suja";
	$vetor[4]= "Visualiza��o do peso de roupa limpa";
	$vetor[5]= "Montagem de kits de uniformes";
	$vetor[6]= "Controle de consumo";
	$vetor[7]= "Registro de costura";
	$vetor[8]= "Verifica��o da press�o de vapor";
	$vetor[9]= "Manuten��o da organiza��o da lavanderia";
	$vetor[10]= "Gin�stica laboral";
	$vetor[11]= "Agendamento das visitas";
	$vetor[12]= "Passagem de plant�o";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_manutencao_checklist_categorias($tipo) {
	$vetor= array();
	
	$vetor[1]= "Aerador";
	$vetor[2]= "Calandra";
	$vetor[3]= "Caldeira";
	$vetor[4]= "Carrinhos";
	$vetor[5]= "Casa de m�quinas";
	$vetor[6]= "Compressor";
	$vetor[7]= "Lavadoras";
	$vetor[8]= "Manuten��o predial";
	$vetor[9]= "Secadora";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_manutencao_checklist_itens($tipo, $id_categoria) {
	$vetor= array();
	
	$vetor[1]= "Aerador";
	$vetor[2]= "Calandra";
	$vetor[3]= "Caldeira";
	$vetor[4]= "Carrinhos";
	$vetor[5]= "Casa de m�quinas";
	$vetor[6]= "Compressor";
	$vetor[7]= "Lavadoras";
	$vetor[8]= "Manuten��o predial";
	$vetor[9]= "Secadora";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function traduz_periodicidade($p) {
	
	switch ($p[1]) {
		case "d": $periodo= "dia"; break;
		case "m": $periodo= "m�s"; break;
		case "a": $periodo= "ano"; break;
	}
	
	return($p[0] ."x/". $periodo);
}

function pega_data_abertura_rm($id_rm) {
	$result= mysql_query("select * from  man_rms_andamento
							where id_rm = '". $id_rm ."'
							and   id_situacao = '1'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);

	return($rs->data_rm_andamento);
}

function pega_descricao_nota($nota) {
	switch($nota) {
		case -1:
			$bg= "bg_nada";
			$txt= "N/A";
			break;
		case 0:
		case 1:
		case 2:
		case 3:
			$bg= "bg_vermelho";
			$txt= "Cr�tico";
			break;
		case 4:
		case 5:
			$bg= "bg_amarelo";
			$txt= "Baixo";
			break;
		break;
		case 6:
		case 7:
			$bg= "bg_cinza";
			$txt= "M�dio";
			break;
		break;
		case 8:
		case 9:
			$bg= "bg_verde";
			$txt= "Bom";
			break;
		break;
		case 10:
			$bg= "bg_azul";
			$txt= "Ideal";
			break;
		break;
	}
	
	return($txt."@".$bg);
}

function pega_descricao_nota2($nota) {
	switch($nota) {
		case -1:
			$bg= "bg_nada";
			$txt= "N/A";
			break;
		case 0:
		case 1:
		case 2:
		case 3:
			$bg= "bg_vermelho";
			$txt= "Cr�tico";
			break;
		case 4:
		case 5:
		case 6:
			$bg= "bg_amarelo";
			$txt= "Ruim";
			break;
		break;
		case 7:
		case 8:
			$bg= "bg_verde";
			$txt= "Bom";
			break;
		break;
		case 9:
		case 10:
			$bg= "bg_azul";
			$txt= "Ideal";
			break;
		break;
	}
	
	return($txt."@".$bg);
}

function pega_situacao_rm($tipo) {
	$vetor= array();
	
	$vetor[1]= "Manuten��o solicitada";
	$vetor[2]= "Em an�lise";
	$vetor[3]= "Em andamento";
	$vetor[4]= "Aguardando pe�as";
	$vetor[5]= "Finalizada";
	$vetor[6]= "Reabertura de RM";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_situacao_reclamacao($tipo) {
	$vetor= array();
	
	$vetor[1]= "A��o corretiva";
	$vetor[2]= "A��o preventiva";
	$vetor[3]= "Melhoria de processo";
	$vetor[4]= "Sugest�o";
	$vetor[5]= "Investiga��o";
	$vetor[6]= "Finalizada";//5 //update qual_reclamacoes_andamento set id_situacao= '6' where id_situacao = '5'
	$vetor[7]= "Reclama��o reaberta";//6 //update qual_reclamacoes_andamento set id_situacao= '7' where id_situacao = '6'
	$vetor[8]= "N�o solucionada";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_metodo_documento_assinado($tipo) {
	$vetor= array();
	
	$vetor[1]= "Departamentos separados";
	$vetor[2]= "Departamentos juntos";
	$vetor[3]= "Lista de presen�a";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_documento_emissao($tipo, $tipo_documento) {
	$vetor= array();
	
	$vetor[1]= "Comunica��o interna";
	$vetor[2]= "Memorando";
	$vetor[3]= "CI";
	$vetor[4]= "Of�cio";
	$vetor[5]= "Proposta";
	$vetor[6]= "Fax";
	$vetor[7]= "Doc. assinado";
	$vetor[8]= "Pauta de reuni�o";
	$vetor[9]= "Protocolo";
	
	if ($tipo_documento==1) $retorno_tipo= " (ADM)";
	elseif ($tipo_documento==2) $retorno_tipo= " (OP)";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo] . $retorno_tipo);
}

function pega_quimico($tipo) {
	$vetor= array();

	$vetor[1]= "Detergent";
	$vetor[2]= "Builder 300";
	$vetor[3]= "Destainer";
	$vetor[4]= "Oxybrite 50";
	$vetor[5]= "Antichlor";
	$vetor[6]= "Sirilon";
	$vetor[7]= "Silex-P";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_almoco($tipo) {
	$vetor= array();

	$vetor[1]= "Livre";
	$vetor[2]= "Quilo";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_opcao_almoco($tipo) {
	$vetor= array();

	$vetor[1]= "Com bebida";
	$vetor[2]= "Sem bebida";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_combustivel($tipo) {
	$vetor= array();

	$vetor[1]= "Gasolina comum";
	$vetor[2]= "Gasolina aditivada";
	$vetor[3]= "Diesel";
	$vetor[4]= "�lcool";
	$vetor[5]= "G�s natural";
	$vetor[6]= "�leo para motor";
	$vetor[7]= "�leo hidr�ulico";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_reclamacao_causa($id_causa) {
	$result= mysql_query("select * from qual_reclamacoes_causas
							where id_causa= '". $id_causa."'
							");
	$rs= mysql_fetch_object($result);
	return($rs->causa);
}

function pega_tipo_equipamento2($id_equipamento_tipo) {
	$vetor= array();

	$result= mysql_query("select equipamento_tipo from op_equipamentos_tipos
							where id_equipamento_tipo = '". $id_equipamento_tipo ."'
							");
	$rs= mysql_fetch_object($result);
	
	return($rs->equipamento_tipo);

	/*
	$vetor[1]= "Lavadora";
	$vetor[2]= "Secadora";
	$vetor[3]= "Calandra";
	$vetor[4]= "Balan�a";
	$vetor[5]= "Compressor";
	$vetor[6]= "Caldeira";
	$vetor[7]= "Aerador";
	$vetor[8]= "Seladora";
	$vetor[9]= "C�mera";
	$vetor[10]= "Dosador";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
	*/
}

function pega_tipo_roupa($tipo) {
	$vetor= array();

	$vetor[1]= "Cobertor"; // 21
	$vetor[2]= "Colcha"; // 8
	$vetor[3]= "Compressa"; // 23
	$vetor[4]= "Fronha"; // 9
	$vetor[5]= "Len�ol"; // 12
	$vetor[6]= "Roupa"; // 1
	$vetor[7]= "Roupa CC"; // 2
	$vetor[8]= "Variadas"; // 25
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_grupo_roupa($tipo) {
	$vetor= array();

	$vetor[1]= "Outro";
	$vetor[2]= "Roupa";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_motivo_costura($tipo) {
	$vetor= array();

	$vetor[1]= "Dano qu�mico";
	$vetor[2]= "Dano mec�nico";
	$vetor[3]= "Desgaste natural";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_id_grupo_da_peca($id_peca) {
	
	$result= mysql_query("select * from op_limpa_pecas
							where id_peca = '". $id_peca ."'
							");
	$rs= mysql_fetch_object($result);
	
	return($rs->id_grupo);
}

function pega_pecas_roupa($id_peca) {
	
	$result= mysql_query("select * from op_limpa_pecas
							where id_peca = '". $id_peca ."'
							limit 1
							");
	$rs= mysql_fetch_object($result);
	
	return($rs->peca);
	
	/*$vetor= array();

	$vetor[1]= "Avental";
	$vetor[2]= "Avental cir�rgico";
	$vetor[3]= "Cal�a";
	$vetor[4]= "Camisola";
	$vetor[5]= "Campo cir�rgico grande";
	$vetor[6]= "Campo cir�rgico pequeno";
	$vetor[7]= "Campo fenestrado";
	$vetor[8]= "Colcha";
	$vetor[9]= "Fronha";
	$vetor[10]= "Hamper";
	$vetor[11]= "Camisa";
	$vetor[12]= "Len�ol";
	$vetor[13]= "M�scara";
	$vetor[14]= "Prop�";
	$vetor[15]= "Roupa infantil";
	$vetor[16]= "Roup�o";
	$vetor[17]= "Toalha de banho";
	$vetor[18]= "Toalha de rosto";
	$vetor[19]= "Toalha pequena de m�o";
	$vetor[20]= "Touca";
	$vetor[21]= "Cobertor";
	$vetor[22]= "Cobertor infantil";
	$vetor[23]= "Compressa";
	$vetor[24]= "Coeiro";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);*/
}


function pega_itens_devolucao($tipo) {
	$vetor= array();

	$vetor[1]= "Res. biol�gicos";
	$vetor[2]= "Bacia";
	$vetor[3]= "Inst. cir�rgico";
	$vetor[4]= "Papagaio";
	$vetor[5]= "Comadre";
	$vetor[6]= "Cubeta";
	$vetor[7]= "Colar cervical";
	$vetor[8]= "Acess. pessoais";
	$vetor[9]= "Chaves";
	$vetor[10]= "Colch�o";
	$vetor[11]= "Travesseiros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_contato($tipo) {
	$vetor= array();

	$vetor[1]= "Fornecedores";
	$vetor[2]= "Funcion�rios";
	$vetor[3]= "Outros";
	$vetor[4]= "Clientes";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function valor_extenso($valor=0) {

	$singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o");
	$plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es", "quatrilh�es");
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis","sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : "") . $r;
	}

	return($rt ? $rt : "zero");
}

function pega_tipo_telefone($tipo) {
	$vetor= array();

	$vetor[1]= "Residencial";
	$vetor[2]= "Comercial";
	$vetor[3]= "Celular";
	$vetor[4]= "Fax";
	$vetor[5]= "Outros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_acao_carreira($tipo) {
	$vetor= array();

	$vetor[1]= "Admiss�o";
	$vetor[2]= "Desligamento";
	$vetor[3]= "Mudan�a de departamento/cargo";
	$vetor[4]= "Mudan�a de turno";
	/*$vetor[4]= "Contrato de experi�ncia (30 dias)";
	$vetor[5]= "Contrato de experi�ncia (mais 60 dias)";
	$vetor[6]= "Efetivado(a)";*/
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_detalhe_carreira_desligamento($tipo) {
	$vetor= array();

	$vetor[1]= "Demitido(a)";
	$vetor[2]= "Pedido de demiss�o";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_acao_carreira2($tipo) {
	$vetor= array();

	$vetor[1]= "Admiss�o";
	$vetor[2]= "Demiss�o";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_checklist_admissao($tipo) {
	$vetor= array();

	$vetor[1]= "ASO";
	$vetor[2]= "Contrato de trabalho";
	$vetor[3]= "Contrato de experi�ncia";
	$vetor[4]= "Registro funcional";
	$vetor[5]= "Vale transporte";
	$vetor[6]= "Cart�o de identifica��o";
	$vetor[7]= "Uniforme";
	$vetor[8]= "Ordem de servi�o";
	$vetor[9]= "Arm�rio";
	$vetor[10]= "EPI";
	$vetor[11]= "Teste admissional";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_checklist_demissao($tipo) {
	$vetor= array();

	$vetor[1]= "Comprovante de pagamento de rescis�o";
	$vetor[2]= "Rescis�o (5 vias)";
	$vetor[3]= "Pedido/aviso de demiss�o";
	$vetor[4]= "Extrato do FGTS";
	$vetor[5]= "Atestado de sa�de demissional";
	$vetor[6]= "Comprova��o de f�rias";
	$vetor[7]= "Registro de empregado";
	$vetor[8]= "Carteira de trabalho e previd�ncia social";
	$vetor[9]= "Aviso pr�vio";
	$vetor[10]= "Todos os documentos acima";
	$vetor[11]= "Guia do seguro desemprego";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_indice_contatos($id_empresa) {
	$result_ver= mysql_query("select * from tel_contatos_versoes
							 	where id_empresa = '". $id_empresa ."'
								and   tipo = 'i'
								order by data desc
								limit 1
								");
	$rs_ver= mysql_fetch_object($result_ver);
	
	return($rs_ver->indice);
}

function checa_versao_contatos($tipo, $id_empresa, $id_usuario) {
	$result_ver= mysql_query("select * from tel_contatos_versoes
							 	where id_empresa = '". $id_empresa ."'
								order by data desc
								limit 1
								");
	$rs_ver= mysql_fetch_object($result_ver);
	
	$linhas= mysql_num_rows($result_ver);
	
	if ($tipo=='i') {
		$indice= pega_indice_contatos($id_empresa);
		$indice++;
	}
	else $indice= 0;
	
	//se o ultimo registro encontrado nao for o que est� fazendo, insere o que est� fazendo e incrementa a vers�o
	if (($rs_ver->tipo!=$tipo) || ($linhas==0)) {
		$result_ver_insere= mysql_query("insert into tel_contatos_versoes
											(id_empresa, indice, data, tipo, id_usuario)
											values
											('". $id_empresa ."', '$indice', '". date("YmdHis") ."',
											'". $tipo ."', '". $id_usuario ."')
											");
		$id_versao= $indice;
	}
	else $id_versao= $rs_ver->indice;
	
	return($id_versao);
}

function pega_empresa($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.nome_rz, pessoas.apelido_fantasia from pessoas, empresas
											where pessoas.id_pessoa = empresas.id_pessoa
											and   empresas.id_empresa = '$id_empresa'
											"));
	return($rs->apelido_fantasia);
}

function pega_cnpj($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.cpf_cnpj from pessoas, empresas
											where pessoas.id_pessoa = empresas.id_pessoa
											and   empresas.id_empresa = '$id_empresa'
											"));
	return($rs->cpf_cnpj);
}

function pega_sigla_pessoa($id_pessoa) {
	if ($id_pessoa==0) return(pega_sigla_pessoa(pega_id_pessoa_da_empresa($_SESSION["id_empresa"])));
	else {
		$rs= mysql_fetch_object(mysql_query("select sigla from pessoas
												where id_pessoa = '$id_pessoa'
												"));
		return($rs->sigla);
	}
}


function pega_motivo($id_motivo) {
	$rs= mysql_fetch_object(mysql_query("select motivo from rh_motivos
											where id_motivo = '$id_motivo'
											"));
	return($rs->motivo);
}


function pega_departamento($id_departamento) {
	$rs= mysql_fetch_object(mysql_query("select departamento from rh_departamentos
											where id_departamento = '$id_departamento'
											"));
	return($rs->departamento);
}

function pega_departamento_pasta($id_pasta) {
	$rs= mysql_fetch_object(mysql_query("select id_departamento from dc_documentos_pastas
											where id_pasta = '$id_pasta'
											"));
	return($rs->id_departamento);
}

function pega_pasta($id_pasta) {
	$rs= mysql_fetch_object(mysql_query("select nome_pasta from dc_documentos_pastas
											where id_pasta = '$id_pasta'
											"));
	return($rs->nome_pasta);
}

function pega_id_cargo_atual($id_funcionario) {
	$rs= mysql_fetch_object(mysql_query("select id_cargo from rh_carreiras
											where id_funcionario = '$id_funcionario'
											and   atual = '1'
											"));
	return($rs->id_cargo);
}


function pega_cargo($id_cargo) {
	$rs= mysql_fetch_object(mysql_query("select cargo from rh_cargos
											where id_cargo = '$id_cargo'
											"));
	return($rs->cargo);
}

function pega_horarios_turno($id_turno, $id_dia) {
	$rs= mysql_fetch_object(mysql_query("select * from rh_turnos, rh_turnos_horarios
											where rh_turnos.id_turno = '$id_turno'
											and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
											and   rh_turnos_horarios.id_dia = '$id_dia'
											"));
	//return($rs->departamento ." - ". $rs->turno);
	$horario[0]= $rs->entrada;
	$horario[1]= $rs->saida;
	
	return($horario);
}

function tem_hl_turno($id_turno, $id_dia) {
	$rs= mysql_fetch_object(mysql_query("select * from rh_turnos, rh_turnos_horarios
											where rh_turnos.id_turno = '$id_turno'
											and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
											and   rh_turnos_horarios.id_dia = '$id_dia'
											"));
	if ($rs->hl==1) return(true);
	else return(false);
}

function pega_turno_padrao_pelo_id_turno($id_turno) {
	if (($id_turno==-1) || ($id_turno==-2) || ($id_turno==-3)) return($id_turno);
	else {
		$result= mysql_query("select id_turno_index from rh_turnos
								where id_turno = '$id_turno'
								");
		$rs= mysql_fetch_object($result);
		return($rs->id_turno_index);
	}
}

function pega_turno($id_turno) {
	if ($id_turno==-1) return("PLANT�O S�BADO");
	elseif ($id_turno==-2) return("PLANT�O DOMINGO");
	elseif ($id_turno==-3) return("COSTURA");
	else {
		$rs= mysql_fetch_object(mysql_query("select rh_departamentos.departamento, rh_turnos.turno from rh_departamentos, rh_turnos
												where rh_turnos.id_departamento = rh_departamentos.id_departamento
												and   rh_turnos.id_turno = '$id_turno'
												"));
		//return($rs->departamento ." - ". $rs->turno);
		return($rs->turno);
	}
}

function pega_intervalos($id_turno) {
	$result= mysql_query("select * from rh_turnos_intervalos
									where id_turno = '$id_turno'
									order by intervalo asc
									");
	
	if (mysql_num_rows($result)==0)
		$retorno= "Nenhum";
	else
		while ($rs= mysql_fetch_object($result))
			$retorno .= $rs->intervalo ." ";
	
	return($retorno);
}

function calcula_horario_intervalo($tipo, $id_intervalo, $id_dia, $data) {
	$result= mysql_query("select * from rh_turnos_horarios, rh_turnos_intervalos, rh_turnos_intervalos_horarios
									where rh_turnos_horarios.id_turno = rh_turnos_intervalos.id_turno
									and   rh_turnos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos.id_intervalo = rh_turnos_intervalos_horarios.id_intervalo
									and   rh_turnos_intervalos.id_intervalo = '$id_intervalo'
									and   rh_turnos_intervalos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos_horarios.automatico = '0'
									");
	
	$rs= mysql_fetch_object($result);

	$inicio= explode(':', $rs->entrada);
	$intervalo= explode(':', $rs->intervalo_apos);
	$duracao= explode(':', $rs->intervalo_duracao);
	$data= explode('-', $data);
	
	$horario_inicio = date("Y-m-d H:i:s", mktime($inicio[0]+$intervalo[0], $inicio[1]+$intervalo[1], $inicio[2]+$intervalo[2], $data[1], $data[2], $data[0]));
	$horario_fim = date("Y-m-d H:i:s", mktime($inicio[0]+$intervalo[0]+$duracao[0], $inicio[1]+$intervalo[1]+$duracao[1], $inicio[2]+$intervalo[2]+$duracao[2], $data[1], $data[2], $data[0]));
	
	$retorno .= "". $horario_inicio ." �s ". $horario_fim ." ";
	
	if ($tipo=='i') return($horario_inicio);
	else return($horario_fim);
}


function pega_detalhes_intervalo($id_intervalo, $id_dia, $automatico) {
	
	$result= mysql_query("select * from rh_turnos, rh_turnos_horarios, rh_turnos_intervalos, rh_turnos_intervalos_horarios
									where rh_turnos_horarios.id_turno = rh_turnos_intervalos.id_turno
									and   rh_turnos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos.id_intervalo = rh_turnos_intervalos_horarios.id_intervalo
									and   rh_turnos_intervalos.id_intervalo = '$id_intervalo'
									and   rh_turnos_intervalos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos_horarios.automatico = '$automatico'
									and   rh_turnos_intervalos.id_turno = rh_turnos.id_turno
									");
	
	if (mysql_num_rows($result)==0) {
		$result_intervalo= mysql_query("select * from rh_turnos, rh_turnos_intervalos
										where rh_turnos.id_turno = rh_turnos_intervalos.id_turno
										and   rh_turnos_intervalos.id_intervalo = '$id_intervalo'
										");
		if (mysql_num_rows($result_intervalo)==0) {
			$retorno= "15 min";
		}
		else {
			$rs_intervalo= mysql_fetch_object($result_intervalo);
			
			if (($rs_intervalo->id_regime==2) || ($rs_intervalo->id_regime==3))
				$retorno= "15 min";
			else
				$retorno= "";
		}
	}
	else
		while ($rs= mysql_fetch_object($result)) {
			$inicio= explode(':', $rs->entrada);
			$intervalo= explode(':', $rs->intervalo_apos);
			$duracao= explode(':', $rs->intervalo_duracao);
			
			$horario_inicio = date("H:i", mktime($inicio[0]+$intervalo[0], $inicio[1]+$intervalo[1], $inicio[2]+$intervalo[2], 0, 0, 0));
			$horario_fim = date("H:i", mktime($inicio[0]+$intervalo[0]+$duracao[0], $inicio[1]+$intervalo[1]+$duracao[1], $inicio[2]+$intervalo[2]+$duracao[2], 0, 0, 0));
			
			$retorno .= "". $horario_inicio ." �s ". $horario_fim ."";
		}
	
	return($retorno);
}

function retorna_intervalo_mk($id_intervalo, $id_dia, $automatico, $data) {
	
	$data= explode('-', $data);
	
	$result= mysql_query("select * from rh_turnos_horarios, rh_turnos_intervalos, rh_turnos_intervalos_horarios
									where rh_turnos_horarios.id_turno = rh_turnos_intervalos.id_turno
									and   rh_turnos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos.id_intervalo = rh_turnos_intervalos_horarios.id_intervalo
									and   rh_turnos_intervalos.id_intervalo = '$id_intervalo'
									and   rh_turnos_intervalos_horarios.id_dia = '$id_dia'
									and   rh_turnos_intervalos_horarios.automatico = '$automatico'
									");
	
	if (mysql_num_rows($result)==0) {
		$retorno[0]= "";
		$retorno[1]= "";
	}
	else {
		while ($rs= mysql_fetch_object($result)) {
			$inicio= explode(':', $rs->entrada);
			$intervalo= explode(':', $rs->intervalo_apos);
			$duracao= explode(':', $rs->intervalo_duracao);
			
			$horario_inicio = mktime($inicio[0]+$intervalo[0], $inicio[1]+$intervalo[1], $inicio[2]+$intervalo[2], $data[1], $data[2], $data[0]);
			$horario_fim = mktime($inicio[0]+$intervalo[0]+$duracao[0], $inicio[1]+$intervalo[1]+$duracao[1], $inicio[2]+$intervalo[2]+$duracao[2], $data[1], $data[2], $data[0]);
		}
		$retorno[0]= $horario_inicio;
		$retorno[1]= $horario_fim;
	}
	//echo date("d/m/Y H:i:s", $horario_inicio);
	
	
	return($retorno);
}

function retorna_intervalo_automatico($horario_saida_mk, $horario_entrada_mk) {
	
	$inicio_ano= date("Y", $horario_saida_mk);
	$inicio_mes= date("m", $horario_saida_mk);
	$inicio_dia= date("d", $horario_saida_mk);
	$inicio_hora= date("H", $horario_saida_mk);
	$inicio_min= date("i", $horario_saida_mk);
	$inicio_seg= date("s", $horario_saida_mk);
	
	$fim_ano= date("Y", $horario_entrada_mk);
	$fim_mes= date("m", $horario_entrada_mk);
	$fim_dia= date("d", $horario_entrada_mk);
	$fim_hora= date("H", $horario_entrada_mk);
	$fim_min= date("i", $horario_entrada_mk);
	$fim_seg= date("s", $horario_entrada_mk);
	
	if ($inicio_min>=50) $soma=4;
	else $soma= 3;
	
	$intervalo_automatico[0]= mktime($inicio_hora+$soma, 0, 0, $inicio_mes, $inicio_dia, $inicio_ano);
	$intervalo_automatico[1]= mktime($inicio_hora+$soma, 15, 0, $inicio_mes, $inicio_dia, $inicio_ano);
	
	if ($intervalo_automatico[1]>=$horario_entrada_mk) {
		$intervalo_automatico[0]= mktime($inicio_hora+($soma-2), 0, 0, $inicio_mes, $inicio_dia, $inicio_ano);
		$intervalo_automatico[1]= mktime($inicio_hora+($soma-2), 15, 0, $inicio_mes, $inicio_dia, $inicio_ano);
	}
	
	return($intervalo_automatico);
}

function formata_hora($var) {
	//transformando em segundos
	$var= explode(":", $var, 3);
	
	$total_horas= $var[0]*3600;
	$total_minutos= $var[1]*60;
	$total_segundos= $var[2];
	
	$var= $total_horas+$total_minutos+$total_segundos;
	
	return($var);
}


function calcula_total_horas($total_horas) {
	if ($total_horas>=86400) {
		$total_dias= floor($total_horas/86400);
		$total_sobra= ($total_horas%86400);
		
		$h= date("H", mktime(0, 0, $total_sobra, 0, 0, 0));
		$m= date("i", mktime(0, 0, $total_sobra, 0, 0, 0));
		$s= date("s", mktime(0, 0, $total_sobra, 0, 0, 0));
		$t= (24*$total_dias)+$h;
		$total= $t .':'. $m .':'. $s;
	}
	elseif ($total_horas<0) {
		$total= "-". @date("H:i:s", mktime(0, 0, abs($total_horas), 0, 0, 0));
	}
	else
		$total= @date("H:i:s", mktime(0, 0, $total_horas, 0, 0, 0));
	
	return($total);
}

function calcula_total_horas_ss($total_horas) {
	$novo= calcula_total_horas($total_horas);
	
	$parte= explode(":", $novo);
	
	return($parte[0] .":". $parte[1]);
}


function calcula_dias_pelas_horas($horas) {
	if (strstr($horas, ":")) {
		$horas2= explode(":", $horas, 1);
		$horas= $horas2[0];
		
		//echo $horas;
	}
	
	$dias= floor($horas/24);
	
	return($dias);
}

function transforma_hora_em_decimal($hora) {
	$hora= calcula_total_horas($hora);
	
	$partes= explode(":", $hora);
	
	$minutos= ($partes[1][0] .".". $partes[1][1]);
	
	$minutos2= (($minutos*100)/60);
	
	return($partes[0] .'.'. str_replace(".", "", $minutos2));
}

function eh_diurno($hora) {
	if
		(
			//se for 4:50 ~ 4:59
			//(($hora[0]==4) && ($hora[1]>=50))
			//||
			//se for 5:00 ~ 21:59
			(($hora>=5) && ($hora<22))
			//||
			//se for 22:00 ~ 22:10
			//(($hora[0]==22) && ($hora[1]<=10))
		) {
			//if (
				//se for 21:50
				//(($hora[0]==21) && ($hora[1]>=50))
				//||
				//se for 5:10
				//(($hora[0]==5) && ($hora[1]<=10))
				//}
				//return(false);
			//else
			return(true);
	}
	else
		return(false);
}

function eh_noturno($hora) {
	if
		(
			//(($hora[0]==21) && ($hora[1]>=50))
			//||
			(($hora>=22) || ($hora<5))
			//||
			//(($hora[0]==22) && ($hora[1]<=10))
		)
		return(true);
	else
		return(false);
}

function retorna_intervalo_diurno_noturno($datac1, $datac2, $datac1_anterior, $datac2_anterior) {
	//echo $datac1 .' - '. $datac2 .'<br><br>';
	
	$anterior= explode(" ", $datac1);
	$hora1= explode(":", $anterior[1]);
	$data1= explode("-", $anterior[0]);
	$completa1= mktime($hora1[0], $hora1[1], $hora1[2], $data1[1], $data1[2], $data1[0]);
	
	$proxima= explode(" ", $datac2);
	$hora2= explode(":", $proxima[1]);
	$data2= explode("-", $proxima[0]);
	$completa2= mktime($hora2[0], $hora2[1], $hora2[2], $data2[1], $data2[2], $data2[0]);
	
	//---------------------------------------------------------------
	if (($datac1_anterior!="") && ($datac2_anterior!="")) {
		$anterior_anterior= explode(" ", $datac1_anterior);
		$hora1_anterior= explode(":", $anterior_anterior[1]);
		$data1_anterior= explode("-", $anterior_anterior[0]);
		$completa1_anterior= mktime($hora1_anterior[0], $hora1_anterior[1], $hora1_anterior[2], $data1_anterior[1], $data1_anterior[2], $data1_anterior[0]);
		
		$proxima_anterior= explode(" ", $datac2_anterior);
		$hora2_anterior= explode(":", $proxima_anterior[1]);
		$data2_anterior= explode("-", $proxima_anterior[0]);
		$completa2_anterior= mktime($hora2_anterior[0], $hora2_anterior[1], $hora2_anterior[2], $data2_anterior[1], $data2_anterior[2], $data2_anterior[0]);
	}
	//---------------------------------------------------------------
	
	$completa22= mktime(22, 0, 0, $data1[1], $data1[2], $data1[0]);
	
	//se entrou e saiu no hor�rio diurno
	if ( (eh_diurno($hora1[0])) && (eh_diurno($hora2[0])) && ($hora1[0]<=$hora2[0]) ) {
		//se tem mais de uma operacao no dia, se as operacoes anteriores forem v�lidas...
		if (($datac1_anterior!="") && ($datac2_anterior!="")) {
			//se a hora que entrou anteriormente � noturno e...
			//a diferenca entre a entrada atual e a sa�da anterior for menor ou igual a 30min
			//... � uma continuacao de hora extra
			if ( (eh_noturno($hora1_anterior[0])) && (($completa1-$completa2_anterior)<=1800) ) {
				$diurno= 0;
				$noturno= $completa2-$completa1;
			}
			else {
				$diurno= $completa2-$completa1;
				$noturno= 0;
			}
		}
		else {
			$diurno= $completa2-$completa1;
			$noturno= 0;
			//echo 1; echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
		}
	}
	elseif ( (eh_diurno($hora1[0])) && (eh_diurno($hora2[0])) && ($hora1[0]>$hora2[0]) ) {
		//$diurno= 0;
		//$noturno= $completa2-$completa1;
		
		$diurno= $completa22-$completa1;
		$noturno= $completa2-$completa22;
		
		//echo 1; echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
	}
	else {
		//se entrou no diurno e saiu no noturno
		if ( ((eh_diurno($hora1[0])) && (eh_noturno($hora2[0]))) || ((eh_diurno($hora1[0])) && ( ($hora2[0]==4) || ($hora2[0]==5) || ($hora2[0]==6) ) ) ) {
			$diurno= $completa22-$completa1;
			$noturno= $completa2-$completa22;
			//echo 2; echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
		}
		else {
			//se entrou no noturno e saiu no diurno
			if ( (eh_noturno($hora1[0])) && (eh_diurno($hora2[0])) ) {
				$diurno= 0;
				$noturno= $completa2-$completa1;
				//echo 3; echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
			}
			else {
				//se entrou e saiu no hor�rio noturno
				if ( (eh_noturno($hora1[0])) && (eh_noturno($hora2[0])) ) {
					$diurno= 0;
					$noturno= $completa2-$completa1;
					//echo 4;	echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
				}
				else {
					//echo 5; echo ' | '. $hora1[0] .':'. $hora1[1] .':'. $hora1[2] .' | '. $hora2[0] .':'. $hora2[1] .':'. $hora2[2] .'<br>';
				}
				
			}
		}
	}
	
	$retorno[0]= $diurno;
	$retorno[1]= $noturno;
	
	return($retorno);
}

function calcula_diurno_noturno($entrada, $saida) {
	//echo $saida[0]; die();
	$total_diurno= 0;
	$total_noturno= 0;
	
	for ($i=0; $i<4; $i++) {
		if ( ($entrada[$i]!="") && ($saida[$i]!="") ) {
			$total= retorna_intervalo_diurno_noturno($entrada[$i], $saida[$i], $entrada[$i-1], $saida[$i-1]);
			
			$total_diurno += $total[0];
			$total_noturno += $total[1];
			
			//echo "<strong>entrada $i</strong>: ". $entrada[$i] ." | <strong>sa�da $i</strong>: ". $saida[$i] ."<br>";
			//echo date("d/m/Y H:i:s", mktime(0, 0, $total_jornada, 0, 0, 0)); die();
		}//fim if
	}
	
	$retorno[0]= $total_diurno;
	$retorno[1]= $total_noturno;
	
	return($retorno);
}

function pega_operacao_debito($operacao, $operacao_debito) {
	if ($operacao==1) return("Horas adicionadas");
	elseif ($operacao_debito==0) return("Mudan�a de hor�rio/folga");
	else return("Pagamento de hora extra");
	
}

function pega_intervalo($id_intervalo) {
	$result= mysql_query("select * from rh_turnos_intervalos
							where id_intervalo = '$id_intervalo' ");
	
	if(mysql_num_rows($result)==0)
		$retorno= "Sem intervalo";
	else {
		$rs= mysql_fetch_object($result);
		$retorno= $rs->intervalo;
	}
	return($retorno);
}

function pega_id_turno_do_id_intervalo($id_intervalo) {
	$rs= mysql_fetch_object(mysql_query("select id_turno from rh_turnos_intervalos
											where id_intervalo = '$id_intervalo'
											"));
	return($rs->id_turno);
}

function pega_id_departamento_do_id_turno($id_turno) {
	$rs= mysql_fetch_object(mysql_query("select id_departamento from rh_turnos
											where id_turno = '$id_turno'
											"));
	return($rs->id_departamento);
}

function pega_id_pessoa_do_funcionario($id_funcionario) {
	$rs= mysql_fetch_object(mysql_query("select id_pessoa from rh_funcionarios
											where id_funcionario = '$id_funcionario'
											"));
	return($rs->id_pessoa);
}

function pega_id_pessoa_da_empresa($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select id_pessoa from empresas
											where id_empresa = '$id_empresa'
											"));
	return($rs->id_pessoa);
}

function pega_id_funcionario_do_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select id_funcionario from usuarios
											where id_usuario = '$id_usuario'
											"));
	return($rs->id_funcionario);
}

function pega_id_pessoa_do_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select rh_funcionarios.id_pessoa from rh_funcionarios, usuarios
											where rh_funcionarios.id_funcionario = usuarios.id_funcionario
											and   usuarios.id_usuario = '$id_usuario'
											"));
	return($rs->id_pessoa);
}

function pega_id_lavagem_pelo_id_equipamento($id_equipamento) {
	$rs= mysql_fetch_object(mysql_query("select id_lavagem from op_suja_lavagem
											where id_equipamento = '$id_equipamento'
											and   data_fim_lavagem is NULL
											and   hora_fim_lavagem is NULL
											order by id_lavagem desc limit 1
											"));
	return($rs->id_lavagem);
}

function pode_um($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ($permissao=="www") $retorno= true;
	
	return($retorno);
}

/*
function pode($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ($permissao=="www") $retorno= true;
	
	return($retorno);
}
*/

function pode($areas, $permissao) {
	$tamanho= strlen($areas);
	$retorno= false;
	
	for ($i=0; $i<$tamanho; $i++) {
		if (pode_um($areas[$i], $permissao)) {
			$retorno=true;
			break;
		}
	}

	return($retorno);
}

function pode_algum($areas, $permissao) {
	$tamanho= strlen($areas);
	$retorno= false;
	
	for ($i=0; $i<$tamanho; $i++) {
		if (pode_um($areas[$i], $permissao)) {
			$retorno=true;
			break;
		}
	}

	return($retorno);
}

function grava_acesso($id_usuario, $id_empresa, $tipo, $ip, $ip_reverso) {
	$result= mysql_query("insert into acessos (id_usuario, id_empresa, tipo, data_acesso, ip, ip_reverso)
								values ('". $id_usuario ."', '". $id_empresa ."', '". $tipo ."', '". date("YmdHis") ."', '$ip', '$ip_reverso' ) ");
	return(mysql_insert_id());
}

function pega_acao_log($i) {
	$vetor= array();
	
	$vetor[1]= "Insere/altera peso de cliente";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function logs($id_acesso, $id_usuario, $id_empresa, $var, $id_referencia, $texto, $id_acao_log, $ip) {
	$result= mysql_query("insert into logs (id_acesso, id_usuario, id_empresa, var, id_referencia, texto, id_acao_log, data, ip)
							values
							('$id_acesso', '$id_usuario', '$id_empresa', '$var', '$id_referencia', '$texto', '$id_acao_log', '". date("YmdHis") ."', '$ip')
							") or die(mysql_error());
}

function log_ponto($id_empresa, $num_cartao, $id_funcionario, $data_log, $hora_log, $turnante, $id_supervisor, $msg, $tipo, $ip, $parte_script, $id_horario) {
	$result= mysql_query("insert into rh_ponto_logs (id_empresa, num_cartao, id_funcionario, data_log, hora_log, turnante, id_supervisor, msg, tipo, ip, parte_script, id_horario)
							values
							('$id_empresa', '$num_cartao', '$id_funcionario', '$data_log', '$hora_log',
							 '$turnante', '$id_supervisor',
							 '$msg', '$tipo', '$ip', '$parte_script', '$id_horario')
							") or die(mysql_error());
}

function pega_id_motivo_pelo_id_afastamento($id_afastamento) {
	$rs= mysql_fetch_object(mysql_query("select id_motivo from rh_afastamentos
													where id_afastamento = '$id_afastamento'
													"));
	return($rs->id_motivo);
}

function pega_tipo_afastamento_pelo_id_afastamento($id_afastamento) {
	$rs= mysql_fetch_object(mysql_query("select tipo_afastamento from rh_afastamentos
													where id_afastamento = '$id_afastamento'
													"));
	return($rs->tipo_afastamento);
}

function pega_data_inicial_afastamento($tipo_afastamento, $id_afastamento) {
	$rs= mysql_fetch_object(mysql_query("select DATE_FORMAT(data, '%d/%m/%Y') as data2 from rh_afastamentos_dias
													where tipo_afastamento = '$tipo_afastamento'
													and   id_afastamento = '$id_afastamento'
													order by data asc limit 1
													"));
	return($rs->data2);
}

function pega_cartao_do_funcionario($id_funcionario) {
	$result= mysql_query("select numero_cartao from rh_cartoes where id_funcionario = '$id_funcionario' ");
	
	if (mysql_num_rows($result)==0)
		return("-");
	else {
		$rs= mysql_fetch_object($result);
		return($rs->numero_cartao);
	}
}

function pega_num_ultimo_funcionario($id_empresa) {
	$result= mysql_query("select num_func from rh_funcionarios
							where id_empresa = '". $id_empresa ."'
							order by id_funcionario desc limit 1
							");
	$rs= mysql_fetch_object($result);
	return($rs->num_func);
}

function pega_num_remessa($data_remessa, $id_empresa) {
	$result= mysql_query("select * from op_suja_remessas
						 	where data_remessa= '". $data_remessa ."'
							and   id_empresa = '". $id_empresa ."'
							");
	$linhas= mysql_num_rows($result);
	$linhas++;
	
	return($linhas);
}

function pega_tipo_afastamento($i) {
	
	switch ($i) {
		case 'a': $tipo= "Atestado"; break;
		case 'p': $tipo= "Per�cia"; break;
		case 'o': $tipo= "Outros abonos"; break;
		case 'f': $tipo= "F�rias"; break;
		case 'd': $tipo= "Advert�ncia"; break;
		case 's': $tipo= "Suspens�o"; break;
		case 'b': $tipo= "Abandono"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_tipo_motivo($i) {
	switch ($i) {
		case 'o': $tipo= "Outros abonos"; break;
		case 'd': $tipo= "Advert�ncia"; break;
		case 's': $tipo= "Suspens�o"; break;
		case 'p': $tipo= "Altera��o no ponto"; break;
		case 't': $tipo= "Descontos"; break;
		case 'r': $tipo= "Refei��es"; break;
		case 'q': $tipo= "Sa�da no estoque"; break;
		case 'l': $tipo= "Livro"; break;
		default: $tipo=""; break;
	}
	return($tipo);
}

function pega_tipo_cartao($i) {
	$vetor= array();
	
	$vetor[1]= "Normal";
	$vetor[2]= "Supervisor";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_regime_turno($i) {
	$vetor= array();
	
	$vetor[1]= "Integral";
	$vetor[2]= "6h/12h";
	$vetor[3]= "6h/6h";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_estado_civil($i) {
	$vetor= array();
	
	$vetor[1]= "Uni�o est�vel";
	$vetor[2]= "Casado(a)";
	$vetor[3]= "Divorciado(a)";
	$vetor[4]= "Separado(a)";
	$vetor[5]= "Vi�vo(a)";
	$vetor[6]= "Solteiro(a)";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function traduz_mes($mes) {
	switch($mes) {
		case 1: $retorno= "Janeiro"; break;
		case 2: $retorno= "Fevereiro"; break;
		case 3: $retorno= "Mar�o"; break;
		case 4: $retorno= "Abril"; break;
		case 5: $retorno= "Maio"; break;
		case 6: $retorno= "Junho"; break;
		case 7: $retorno= "Julho"; break;
		case 8: $retorno= "Agosto"; break;
		case 9: $retorno= "Setembro"; break;
		case 10: $retorno= "Outubro"; break;
		case 11: $retorno= "Novembro"; break;
		case 12: $retorno= "Dezembro"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_tipo_nota($id_nota) {
	$result= mysql_query("select tipo_nota
								from fi_notas
								where id_nota = '". $id_nota ."'
								") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	return($rs->tipo_nota);
}

function pega_id_cedente_nota($id_nota) {
	$result= mysql_query("select id_cedente
								from fi_notas
								where id_nota = '". $id_nota ."'
								") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	return($rs->id_cedente);
}


function pega_num_parcelas_nota($id_nota) {
	$result= mysql_query("select id_nota
								from fi_notas_parcelas
								where fi_notas_parcelas.id_nota = '". $id_nota ."'
								") or die(mysql_error());
	
	$linhas= mysql_num_rows($result);
	
	return($linhas);
}

function pega_num_parcela_nota($id_nota, $id_parcela) {
	$result_total= mysql_query("select *
								from fi_notas_parcelas
								where id_nota = '". $id_nota ."'
								") or die(mysql_error());
	
	$i=1;
	while ($rs_total= mysql_fetch_object($result_total)) {
		
		if ($rs_total->id_parcela==$id_parcela) {
			$num_nota= $i;
			break;
		}
		
		$i++;
	}
	
	return($num_nota);
}

function pega_valor_parcelas($id_nota) {
	$result= mysql_query("select sum(valor) as valor_total from fi_notas_parcelas
							where id_nota = '". $id_nota ."'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->valor_total);
}

function pega_valor_itens($id_nota) {
	$result= mysql_query("select sum(valor_total) as valor_total from fi_notas_itens
							where id_nota = '". $id_nota ."'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
	return($rs->valor_total);
}

function pega_primeiro_vencimento_nota($id_nota) {
	$result= mysql_query("select data_vencimento
								from fi_notas_parcelas
								where fi_notas_parcelas.id_nota = '". $id_nota ."'
								order by fi_notas_parcelas.data_vencimento asc
								limit 1
								") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	return($rs->data_vencimento);
}

function pega_valor_total_nota($id_nota) {
	$result= mysql_query("select sum(fi_notas_parcelas.valor) as total
								from fi_notas, fi_notas_parcelas
								where fi_notas.id_nota = fi_notas_parcelas.id_nota
								and   fi_notas.id_nota = '". $id_nota ."'
								") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	return($rs->total);
}

function pega_valor_total_pagamento_nota($id_nota) {

	/*if (($data1_pagamento!="") && ($data2_pagamento!=""))
		$str= "and data_pagamento >= '$data1_pagamento'
			   and data_pagamento <= '$data2_pagamento'";
	*/
	$result= mysql_query("select sum(fi_notas_parcelas_pagamentos.valor_pago) as total
								from fi_notas, fi_notas_parcelas, fi_notas_parcelas_pagamentos
								where fi_notas.id_nota = fi_notas_parcelas.id_nota
								and   fi_notas_parcelas.id_parcela = fi_notas_parcelas_pagamentos.id_parcela
								and   fi_notas.id_nota = '". $id_nota ."'
								$str
								") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	
	return($rs->total);
}

/* ==========================================================================================================
   ==========================================================================================================
   ==========================================================================================================
   ========================================================================================================== */
   
function pega_analise_agua_tratada($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();

	$vetor[1][0]= "Cor aparente";
	$vetor[1][1]= "Incolor";
	$vetor[2][0]= "Turva��o";
	$vetor[2][1]= "Ausente";
	$vetor[3][0]= "Sabor";
	$vetor[3][1]= "Ins�pido";
	$vetor[4][0]= "Odor";
	$vetor[4][1]= "Inodoro";
	$vetor[5][0]= "Cloro residual livre";
	$vetor[5][1]= "0,5 mg/l";
	$vetor[6][0]= "pH";
	$vetor[6][1]= "6,5 a 9,5";
	$vetor[7][0]= "Cloro livre (ap�s carv�o)";
	$vetor[7][1]= "0,1 ppm";
	$vetor[8][0]= "Temperatura";
	$vetor[8][1]= "38 �C";
	$vetor[9][0]= "Dureza";
	$vetor[9][1]= "Incolor <= 0";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo][1]);
}

function pega_equipamento_local($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();

	$vetor[1]= "�rea de O.R e pr�-tratamento";
	$vetor[2]= "Elementos do sistema";
	$vetor[3]= "Quadro el�trico";
	$vetor[4]= "Bomba(s) de alimenta��o";
	$vetor[5]= "Filtro(s) de areia";
	$vetor[6]= "Abrandador(es)";
	$vetor[7]= "Filtro(s) de carv�o";
	$vetor[8]= "Osmose reversa";
	$vetor[9]= "Bomba(s) de recircula��o";
	$vetor[10]= "Tanque(s) e sistema de distribui��o";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_cor_aparente($tipo) {
	$vetor= array();

	$vetor[1]= "Incolor";
	$vetor[2]= "Com cor";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_turvacao($tipo) {
	$vetor= array();

	$vetor[1]= "Ausente";
	$vetor[2]= "Presente";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_sabor($tipo) {
	$vetor= array();

	$vetor[1]= "Ins�pido";
	$vetor[2]= "S�pido";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_odor($tipo) {
	$vetor= array();

	$vetor[1]= "Inodoro";
	$vetor[2]= "...";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_cloro_residual_livre($tipo) {
	$vetor= array();

	$vetor[1]= "< 0,2 mg/l";
	$vetor[2]= "entre 0,2 e 0,5 mg/l";
	$vetor[3]= "0,5 mg/l";
	$vetor[4]= "> 0,5 mg/l";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ph($tipo) {
	$vetor= array();

	$vetor[1]= "entre 0 e 1";
	$vetor[2]= "entre 1 e 2";
	$vetor[3]= "entre 2 e 3";
	$vetor[4]= "entre 3 e 4";
	$vetor[5]= "entre 4 e 5";
	$vetor[6]= "entre 5 e 6";
	$vetor[7]= "entre 6 e 7";
	$vetor[8]= "entre 7 e 8";
	$vetor[9]= "entre 8 e 9";
	$vetor[10]= "entre 9 e 10";
	$vetor[11]= "entre 10 e 11";
	$vetor[12]= "entre 11 e 12";
	$vetor[13]= "entre 12 e 13";
	$vetor[14]= "entre 13 e 14";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_cloro_livre($tipo) {
	$vetor= array();

	$vetor[1]= "< 0,1 ppm";
	$vetor[2]= "0,1 ppm";
	$vetor[3]= "> 0,1 ppm";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_temperatura($tipo) {
	$vetor= array();

	$vetor[1]= "< 38� C";
	$vetor[2]= "38� C";
	$vetor[3]= "> 38� C";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_dureza($tipo) {
	$vetor= array();

	$vetor[1]= "<= 0";
	$vetor[2]= "> 0";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_condicoes($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();

	$vetor[1]= "�timo";
	$vetor[2]= "bom";
	$vetor[3]= "razo�vel";
	$vetor[4]= "ruim";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_qe_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Indicadores luminosos";
	$vetor[2]= "Dispositivos de acionamento";
	$vetor[3]= "Dispositivos internos";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ba_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Componentes hidr�ulicos";
	$vetor[2]= "Componentes el�tricos";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ba_itens($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o (PSI)";
	$vetor[2]= "Outros";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fa_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Sistema hidr�ulico";
	$vetor[2]= "Sistema el�trico";
	$vetor[3]= "Programa��o e retrolavagem";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fa_itens1($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o (PSI)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fa_itens2($tipo) {
	$vetor= array();

	$vetor[1]= "Identifica��o";
	$vetor[2]= "pH";
	$vetor[3]= "Cloro livre (ppm)";
	$vetor[4]= "TDS (ppm)";
	$vetor[5]= "Ferro total (ppm)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ab_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Sistema hidr�ulico";
	$vetor[2]= "Sistema el�trico";
	$vetor[3]= "Programa��o e retrolavagem";
	$vetor[4]= "N�vel de NaCl no tanque";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ab_itens1($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o (PSI)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ab_itens2($tipo) {
	$vetor= array();

	$vetor[1]= "Identifica��o";
	$vetor[2]= "Dureza (ppmCaCO3)";
	$vetor[3]= "Ferro total (ppm)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fc_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Sistema hidr�ulico";
	$vetor[2]= "Sistema el�trico";
	$vetor[3]= "Programa��o e retrolavagem";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fc_itens1($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o (PSI)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fc_itens2($tipo) {
	$vetor= array();

	$vetor[1]= "Identifica��o";
	$vetor[2]= "Cloro total (ppm)";
	$vetor[3]= "Cloro livre (ppm)";
	$vetor[4]= "Cloro residual (ppm) (cloraminas)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_or_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Sistema hidr�ulico";
	$vetor[2]= "Sistema el�trico";
	$vetor[3]= "Programa��o";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_or_itens1($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o de entrada do pr�-filtro (PSI)";
	$vetor[2]= "Press�o de sa�da do pr�-filtro (PSI)";
	$vetor[3]= "Press�o da bomba da OR (PSI)";
	$vetor[4]= "Vaz�o da �gua tratada (gpm)";
	$vetor[5]= "Vaz�o da �gua rejeitada (gpm)";
	$vetor[6]= "Recupera��o (%)";
	$vetor[7]= "Rejei��o salina (%)";
	$vetor[8]= "Temperatura (�C)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_or_itens2($tipo) {
	$vetor= array();

	$vetor[1]= "Identifica��o";
	$vetor[2]= "TDS (ppm NaCl)";
	$vetor[3]= "pH";
	$vetor[4]= "S�lica (ppm SiO2)";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_br_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Componentes hidr�ulico";
	$vetor[2]= "Componentes el�trico";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_br_itens1($tipo) {
	$vetor= array();

	$vetor[1]= "Identifica��o";
	$vetor[2]= "Press�o (PSI)";
	$vetor[3]= "Outros";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ts_comp($tipo) {
	$vetor= array();

	$vetor[1]= "Componentes hidr�ulico";
	$vetor[2]= "Componentes el�trico";
	$vetor[3]= "Regulador de n�vel do tanque";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_condicoes_operacionais($tipo) {
	$vetor= array();

	$vetor[1]= "Press�o de entrada do pr�-filtro (PSI)";
	$vetor[2]= "Press�o de sa�da do pr�-filtro (PSI)";
	$vetor[3]= "Press�o da bomba de OR (bar)";
	$vetor[4]= "Press�o do concentrado (bar)";
	$vetor[5]= "Vaz�o do produto/permeado (GPM)";
	$vetor[6]= "TDS - quantidade s�lidos dissolvidos (ppm)";
	$vetor[7]= "Press�o da bomba de alimenta��o (PSI)";
	$vetor[8]= "Temperatura (�C)";
	$vetor[9]= "Recupera��o";
	$vetor[10]= "Rejei��o salina";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_cor_solucao($tipo) {
	$vetor= array();

	$vetor[1]= "muito escura";
	$vetor[2]= "escurecimento m�dio";
	$vetor[3]= "pouco escuro";
	$vetor[4]= "incolor";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_vt_equipamentos($tipo) {
	$vetor= array();

	$vetor[1]= "Higiene";
	$vetor[2]= "Quadro el�trico";
	$vetor[3]= "Bomba(s) de alimenta��o";
	$vetor[4]= "Filtro(s) de areia";
	$vetor[5]= "Abrandador(es)";
	$vetor[6]= "Filtro(s) de carv�o";
	$vetor[7]= "Osmose reversa";
	$vetor[8]= "Bomba(s) de recircula��o";
	$vetor[9]= "Tanque de armazenamento";
	$vetor[10]= "Sistema de distribui��o";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_filtro($tipo) {
	$vetor= array();

	$vetor[1]= "1�";
	$vetor[2]= "5�";
	$vetor[3]= "10�";
	$vetor[4]= "25�";
	$vetor[5]= "50�";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_ident_equipamento($tipo) {
	$vetor= array();

	$vetor[1]= "Osmose Reversa";
	$vetor[2]= "Cabine biol�gica";
	$vetor[3]= "Fluxo laminar";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_modelo_equipamento($tipo) {
	$vetor= array();

	$vetor[1]= "Horizontal";
	$vetor[2]= "Vertical";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_classe_equipamento($tipo) {
	$vetor= array();

	$vetor[1]= "I";
	$vetor[2]= "II";
	$vetor[3]= "III";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_equipamento($tipo) {
	$vetor= array();

	$vetor[1]= "A1";
	$vetor[2]= "A2";
	$vetor[3]= "B1";
	$vetor[4]= "B2";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_eficiencia_filtro($tipo) {
	$vetor= array();

	$vetor[1]= "Grosso - G0";
	$vetor[2]= "Grosso - G1";
	$vetor[3]= "Grosso - G2";
	$vetor[4]= "Grosso - G3";
	$vetor[5]= "Finos - F0";
	$vetor[6]= "Finos - F1";
	$vetor[7]= "Finos - F2";
	$vetor[8]= "Finos - F3";
	$vetor[9]= "Absolutos - A1";
	$vetor[10]= "Absolutos - A2";
	$vetor[11]= "Absolutos - A3";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fl_itens($tipo) {
	$vetor= array();

	$vetor[1]= "Gabinete";
	$vetor[2]= "Cavaletes/suporte";
	$vetor[3]= "�rea de trabalho";
	$vetor[4]= "Janela acr�lica";
	$vetor[5]= "Bandeja para coleta de res�duos";
	$vetor[6]= "Cabo externo";
	$vetor[7]= "Painel eletr�nico";
	$vetor[8]= "Lumin�rias";
	$vetor[9]= "L�mpada fluorescente";
	$vetor[10]= "L�mpada germicida";
	$vetor[11]= "Dreno";
	$vetor[12]= "Porta de acesso aos filtros";
	$vetor[13]= "Pr�-filtro";
	$vetor[14]= "Prote��o do filtro";
	$vetor[15]= "Filtro absoluto de insuflamento";
	$vetor[16]= "Filtro absoluto de exaust�o";
	$vetor[17]= "Tomada auxiliar";
	$vetor[18]= "Quadro el�trico";
	$vetor[19]= "Conj. motor de ventilador";
	$vetor[20]= "Corrente do motor";
	$vetor[21]= "Capacitor";
	$vetor[22]= "Variador de velocidade";
	$vetor[23]= "Contador";
	$vetor[24]= "Rele t�rmico";
	$vetor[25]= "Reator";
	$vetor[26]= "Start";
	$vetor[27]= "Fus�veis";
	$vetor[28]= "Botoeira";
	$vetor[29]= "Polias";
	$vetor[30]= "Correias";
	$vetor[31]= "Registro para g�s";
	$vetor[32]= "Registro para ar";
	$vetor[33]= "Registro para �gua";
	$vetor[34]= "Man�metro";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_fl_parametros($tipo) {
	$vetor= array();

	$vetor[1][0]= "Classifica��o da �rea";
	$vetor[1][1]= "5";
	
	$vetor[2][0]= "Designa��es da classe (part�culas/m�)";
	$vetor[2][1]= "1 - 100";
	
	$vetor[3][0]= "Medidas utilizadas";
	$vetor[3][1]= "medidores";
	
	$vetor[4][0]= "Tempo de amostra";
	$vetor[4][1]= "1 minuto minucioso para todas as part�culas";
	
	$vetor[5][0]= "Fator de convers�o";
	$vetor[5][1]= "(se medido em ft3 = 35,2)";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo][0]);
}

function pega_teste_vel($tipo) {
	$vetor= array();

	$vetor[1]= "Limites projeto";
	$vetor[2]= "M�dia encontrada";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_itens_avaliacao_final($tipo) {
	$vetor= array();

	$vetor[1]= "Filtro HEPA reparado";
	$vetor[2]= "Gaxeta";
	$vetor[3]= "Requer reparos estruturais";
	$vetor[4]= "Componentes el�tricos";
	$vetor[5]= "Requer troca de filtros";
	$vetor[6]= "Outros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_teste_fuga_vazamentos($tipo) {
	$vetor= array();

	$vetor[1]= "Meio filtrante";
	$vetor[2]= "Gaxeta";
	$vetor[3]= "Carca�a";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function entrada_saida($tipo) {
	if ($tipo==1) return("<span class=\"verde\">Entrada</span>");
	else return("<span class=\"vermelho\">Sa�da</span>");
}

function entrada_saida_erro($tipo) {
	if ($tipo==1) return("<span class=\"verde\">Entrada</span>");
	elseif ($tipo==0) return("<span class=\"verde\">Sa�da</span>");
	//elseif ($tipo==-1) return("<span class=\"vermelho\">ERRO</span>");
}

function inverte($num) {
	if ($num==1) return(0);
	else return(1);
}

function excluido_ou_nao($var) {
	if ($var==0) $retorno_msg= "Exclu�do com sucesso!";
	else $retorno_msg= "N�o foi poss�vel excluir!";
	
	return("<script language=\"javascript\">alert('". $retorno_msg ."');</script>");
}

function sim_nao($situacao) {
	if (($situacao==0) || ($situacao==2)) return("<span class=\"vermelho\">N�O</span>");
	else return("<span class=\"verde\">SIM</span>");
}

function ativo_inativo($situacao) {
	if ($situacao==1) return("<span class=\"verde\">ATIVO</span>");
	elseif ($situacao==-1) return("<span class=\"vermelho\">EM ESPERA</span>");
	else return("<span class=\"vermelho\">INATIVO</span>");
}

function sim_nao_pdf($situacao) {
	if ($situacao==1) return("SIM");
	else return("");
}

function pega_cidade($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select cidades.cidade, ufs.uf from cidades, ufs
											where cidades.id_uf = ufs.id_uf
											and   cidades.id_cidade = '$id_cidade'
											"));
	return($rs->cidade ."/". $rs->uf);
}

function pega_uf($id_uf) {
	$rs= mysql_fetch_object(mysql_query("select uf from ufs where id_uf = '$id_uf' "));
	return($rs->uf);
}

function pega_id_uf($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select id_uf from cidades
											where id_cidade = '$id_cidade'
											"));
	return($rs->id_uf);
}

function otimiza_foto($foto, $l) {
	$qualidade = 90;
/*
	$originalimage = imagecreatefromjpeg($foto);
	$l_original= imagesx($originalimage);
	$a_original= imagesy($originalimage);
	
	if ($l_original>$l) {
		$a= floor(($l*$a_original)/$l_original);
	}
	else {
		$l= $l_original;
		$a= $a_original;
	}
	//cria um quadrado preto com as dimensoes especificadas
	$thumbnail = imagecreatetruecolor($l, $a);
	//poe a imagem resultante no quadrado preto acima
	imagecopyresampled($thumbnail, $originalimage, 0, 0, 0, 0, $l+1, $a+1, 
	imagesx($originalimage), imagesy($originalimage)); 
	imagejpeg($thumbnail,$foto,$qualidade); 
	imagedestroy($thumbnail); 
	//echo "aki";
	*/
}

function inicia_transacao() {
	mysql_query("set autocommit=0;");
	mysql_query("start transaction;");
}

function finaliza_transacao($var) {
	if ($var==0) mysql_query("commit;");
	else mysql_query("rollback;");
}

function gera_auth() {
	return(substr(strtoupper(md5(uniqid(rand(), true))), 0, 24));
}

function tira_caracteres($char) {
	return(str_replace("'", "xxx", str_replace('"', 'xxx', str_replace('/', '', str_replace('.', '', str_replace('-', '', $char))))));
}

function formata_cpf($cpf) {
	$cpfn= substr($cpf, 0, 3) .".". substr($cpf, 3, 3) .".". substr($cpf, 6, 3) ."-". substr($cpf, 9, 2);
	return($cpfn);
}

function pega_horario($horario, $tipo) {
	
	switch($tipo) {
		case 'h': $retorno= substr($horario, 0, 2); break;
		case 'm': $retorno= substr($horario, 3, 2); break;
		case 's': $retorno= substr($horario, 5, 2); break;
	}
	
	return($retorno);
}

function formata_cnpj($cnpj) {
	//99.999.999/9999-99
	//99 999 999 9999 99
	$cnpj= substr($cnpj, 0, 2) .".". substr($cnpj, 2, 3) .".". substr($cnpj, 5, 3) ."/". substr($cnpj, 8, 4) ."-". substr($cnpj, 12, 2);
	return($cnpj);
}

function formata_data($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function formata_data_timestamp($var) {
	$var= explode(" ", $var, 2);
	
	return(desformata_data($var[0]) . " ". $var[1]);
	
}


function formata_data_hifen($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] .'-'. $var[1] .'-'. $var[0];
	return($var);
}


function faz_mk_data($var) {
	if (strpos($var, "-")) {
		$var= explode("-", $var, 3);
		$mk= mktime(14, 0, 0, $var[1], $var[2], $var[0]);
		return($mk);
	}
	elseif (strpos($var, "/")) {
		$var= explode("/", $var, 3);
		$mk= mktime(14, 0, 0, $var[1], $var[0], $var[2]);
		return($mk);
	}
	else {
		$var= explode("/", $var, 3);
		$mk= mktime(14, 0, 0, substr($var, 4, 2), substr($var, 6, 2), substr($var, 0, 4));
		return($mk);
	}
}

function faz_mk_data2($var) {
	if (strpos($var, "-")) {
		$var= explode("-", $var, 3);
		$mk= mktime(23, 59, 59, $var[1], $var[2], $var[0]);
		return($mk);
	}
	elseif (strpos($var, "/")) {
		$var= explode("/", $var, 3);
		$mk= mktime(23, 59, 59, $var[1], $var[0], $var[2]);
		return($mk);
	}
	else {
		$var= explode("/", $var, 3);
		$mk= mktime(23, 59, 59, substr($var, 4, 2), substr($var, 6, 2), substr($var, 0, 4));
		return($mk);
	}
}

function faz_mk_hora($var) {
	$var= explode(":", $var, 3);
	$mk= mktime($var[0], $var[1], $var[2], 0, 0, 0);
	return($mk);
}

function faz_mk_hora_simples($var) {
	$var= explode(":", $var, 3);
	$mk= (($var[0]*3600)+($var[1]*60)+$var[2]);
	return($mk);
}

function faz_mk_data_completa($var) {
	
	if (strpos($var, "-")) {
		//2008-07-31 13:25:05 
		$data_completa= explode(" ", $var, 2);
		
		$data= explode("-", $data_completa[0], 3);
		$hora= explode(":", $data_completa[1], 3);
		
		$mk= mktime($hora[0], $hora[1], $hora[2], $data[1], $data[2], $data[0]);
	}
	elseif (strpos($var, "/")) {
		//31/07/2008 13:25:05 
		$data_completa= explode(" ", $var, 2);
		
		$data= explode("/", $data_completa[0], 3);
		$hora= explode(":", $data_completa[1], 3);
		
		$mk= mktime($hora[0], $hora[1], $hora[2], $data[1], $data[0], $data[2]);
	}
	
	return($mk);
}

function desformata_data($var) {
	if (($var!="") && ($var!="0000-00-00")) {
		//2006-10-12
		if (strpos($var, "-")) {
			$var= explode("-", $var, 3);
			
			//10/10/2007
			$var= $var[2] .'/'. $var[1] .'/'. $var[0];
			
		}
		//20061012
		else {
			//10/10/2007
			$var= substr($var, 6, 2) .'/'. substr($var, 4, 2) .'/'. substr($var, 0, 4);
		}
		
		return($var);
	}
	//else
	//	return("<span class='menor vermelho'>n�o informado</span>");
}

function desformata_data_completa($var) {
	if (($var!="") && ($var!="0000-00-00 00:00:00")) {
		
		$parte= explode(" ", $var, 2);
		
		//2006-10-12
		if (strpos($var, "-")) {
			$data= desformata_data($parte[0]);
		}
		else $data= $parte[0];
		
		$retorno= substr($data, 0, 5) ." ". substr($parte[1], 0, 5);
		
		return($retorno);
	}
	//else
	//	return("<span class='menor vermelho'>n�o informado</span>");
}


function pega_dia($var) {
	return(substr($var, 6, 2));
}

function pega_mes($var) {
	return(substr($var, 4, 2));
}

function pega_ano($var) {
	return(substr($var, 0, 4));
}

function aumenta_dia($var) {
	//22-10-2007
	$var= explode("-", $var, 3);
	
	$data_ano= date("Y", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	$data_mes= date("m", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	$data_dia= date("d", mktime(0, 0, 0, $var[1], $var[0]+1, $var[2]));
	
	$var[0]= $data_dia;
	$var[1]= $data_mes;
	$var[2]= $data_ano;
	
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function soma_mes($var, $valor) {
	
	if (strpos($var, "-")) {
		//2008-07-31
		$data_completa= explode(" ", $var, 2);
		$data= explode("-", $data_completa[0], 3);
		
		$mk= mktime(0, 0, 0, $data[1]+($valor), $data[2], $data[0]);
	}
	elseif (strpos($var, "/")) {
		//31/07/2008
		$data_completa= explode(" ", $var, 2);
		$data= explode("/", $data_completa[0], 3);
		
		$mk= mktime(0, 0, 0, $data[1]+($valor), $data[0], $data[2]);
	}
	
	$var= date("Y-m-d", $mk);
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function formata_valor($var) {
	$var= str_replace(',', '.', str_replace('.', '', $var));
	return($var);
}

function envia_email_senha($id_empresa) {
	$rs= mysql_fetch_object(mysql_query("select * from empresas where id_empresa= '$id_empresa' "));
	
	$corpo= "<b>". data_extenso() ."</b>
					<br /><br />
					Ol� <b>". $rs->contato ."</b>, a empresa <b>". $rs->nome_fantasia ."</b> foi adicionada no sistema online da Prospital.
					<br /><br />
					Para cadastrar uma senha de acesso entre no link abaixo:
					<br /><br />
					<a href=\"http://www.prospital.com/site/?pagina=senha&amp;auth=". $rs->auth ."\" target=\"_blank\">http://www.prospital.com/site/?pagina=senha&amp;auth=". $rs->auth ."</a>
					<br /><br />
					Esta confirma��o � importante para que a senha se torne privada, ou seja, somente voc� ou o respons�vel legal por sua entidade conhecer�o a senha de acesso ao sistema!
					Logo ap�s o cadastro da senha n�o � poss�vel recuper�-la, somente cadastrar uma nova.
					<br /><br />
					------ <br />
					Atenciosamente,
					<br /><br />
					Prospital.com<br />
					<a href=\"http://www.prospital.com\">http://www.prospital.com</a>
					";
	
	enviar_email("prospital@prospital.com", "Prospital.com | Confirma��o de senha", $corpo);
	//enviar_email($rs->email, "Prospital.com | Confirma��o de senha", $corpo);
}

function data_extenso() {
	/*switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Ter�a-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="S�bado"; break;
	}
	$data_extenso .= ", ";
	*/
	$data_extenso .= date('d');
	$data_extenso .= " de ";
	
	switch(date('n')) {
		case 1: $data_extenso .= "Janeiro"; break;
		case 2: $data_extenso .= "Fevereiro"; break;
		case 3: $data_extenso .= "Mar�o"; break;
		case 4: $data_extenso .= "Abril"; break;
		case 5: $data_extenso .= "Maio"; break;
		case 6: $data_extenso .= "Junho"; break;
		case 7: $data_extenso .= "Julho"; break;
		case 8: $data_extenso .= "Agosto"; break;
		case 9: $data_extenso .= "Setembro"; break;
		case 10: $data_extenso .= "Outubro"; break;
		case 11: $data_extenso .= "Novembro"; break;
		case 12: $data_extenso .= "Dezembro"; break;
	}
	$data_extenso .= " de ";
	$data_extenso .= date('Y');
	return($data_extenso);
}

function data_extenso_param($data) {
	$data= explode('-', $data);
	
	/*switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Ter�a-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="S�bado"; break;
	}
	$data_extenso .= ", ";
	*/
	$data_extenso .= $data[2];
	$data_extenso .= " de ";
	
	switch($data[1]) {
		case 1: $data_extenso .= "Janeiro"; break;
		case 2: $data_extenso .= "Fevereiro"; break;
		case 3: $data_extenso .= "Mar�o"; break;
		case 4: $data_extenso .= "Abril"; break;
		case 5: $data_extenso .= "Maio"; break;
		case 6: $data_extenso .= "Junho"; break;
		case 7: $data_extenso .= "Julho"; break;
		case 8: $data_extenso .= "Agosto"; break;
		case 9: $data_extenso .= "Setembro"; break;
		case 10: $data_extenso .= "Outubro"; break;
		case 11: $data_extenso .= "Novembro"; break;
		case 12: $data_extenso .= "Dezembro"; break;
	}
	$data_extenso .= " de ";
	$data_extenso .= $data[0];
	return($data_extenso);
}


function enviar_email($email, $titulo, $corpo) {
	$enviado= @mail($email, $titulo, $corpo, "From: Prospital.com <prospital@prospital.com> \nContent-type: text/html\n");
}

function pega_tipo_usuario($tipo) {
	switch($tipo) {
		case "a": $retorno= "Administrador"; break;
		case "e": $retorno= "Usu�rio"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_tipo_coleta($tipo) {
	switch($tipo) {
		case "m": $retorno= "Exame microbiol�gico"; break;
		case "f": $retorno= "Exame f�sico-qu�mico"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_prioridade($tipo) {
	switch($tipo) {
		case "0": $retorno= "Baixa"; break;
		case "1": $retorno= "M�dia"; break;
		case "2": $retorno= "Alta"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_tipo_atendimento($tipo_atendimento) {
	switch($tipo_atendimento) {
		case "i": $retorno= "Instala��o"; break;
		case "m": $retorno= "Manuten��o"; break;
		case "c": $retorno= "Checklist"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_status_os($status_os) {
	switch($status_os) {
		case "":
		case "0":
			$retorno= "Aguardando"; break;
		case "1": $retorno= "Em andamento"; break;
		case "2": $retorno= "Finalizada"; break;
		default: $retorno= ""; break;
	}
	return($retorno);
}

function pega_tecnico($id_tecnico) {
	$rs= mysql_fetch_object(mysql_query("select tecnico from tecnicos where id_tecnico = '$id_tecnico' "));
	return($rs->tecnico);
}

function traduz_dia($dia) {
	switch($dia) {
		case 0: $retorno= "Domingo"; break;
		case 1: $retorno= "Segunda"; break;
		case 2: $retorno= "Ter�a"; break;
		case 3: $retorno= "Quarta"; break;
		case 4: $retorno= "Quinta"; break;
		case 5: $retorno= "Sexta"; break;
		case 6: $retorno= "S�bado"; break;
	}
	return($retorno);
}

function traduz_dia_resumido($dia) {
	switch($dia) {
		case 0: $retorno= "dom"; break;
		case 1: $retorno= "seg"; break;
		case 2: $retorno= "ter"; break;
		case 3: $retorno= "qua"; break;
		case 4: $retorno= "qui"; break;
		case 5: $retorno= "sex"; break;
		case 6: $retorno= "s�b"; break;
	}
	return($retorno);
}

/* ##################################### ESTOQUE ################################# */

/* ---------------------------------------- BALAN�O ----------------------------------------------- */

function pega_periodicidade_anual($periodicidade) {
	
	switch($periodicidade) {
		case 1: $titulo= "1� trimestre de ";
				break;
		case 2: $titulo= "2� trimestre de ";
				break;
		case 3: $titulo= "3� trimestre de ";
				break;
		case 4: $titulo= "4� trimestre de ";
				break;
		default: $titulo= "anual de ";
					break;
	}
	return($titulo);
}

function pega_estoque_inicial($periodo, $ano, $periodicidade, $id_empresa, $id_item) {
	//pegar estoque atual
	$result= mysql_query("select  qtde_atual from fi_estoque
							where id_empresa = '". $id_empresa ."'
							and   id_item = '". $id_item ."'
							");
							
	$rs= mysql_fetch_object($result);
	//echo $rs->qtde_atual ."|";
	
	//7.750
	
	$ano_novo= $ano+1;
	
	if ($periodo!="") {
		$periodo2= explode("/", $_POST["periodo"]);
		
		$str_periodo= "and   DATE_FORMAT(data_trans, '%Y-%m') = '". $periodo2[1] ."-". $periodo2[0] ."'";
		
		$ano= $periodo2[1];
	}
	else {
		switch($periodicidade) {
			case 1: $str_periodo= "and   DATE_FORMAT(data_trans, '%Y-%m') >= '". $ano ."-01'
									";
					break;
			case 2: $str_periodo= "and   DATE_FORMAT(data_trans, '%Y-%m') >= '". $ano ."-04'
									";
					break;
			case 3: $str_periodo= "and   DATE_FORMAT(data_trans, '%Y-%m') >= '". $ano ."-07'
									";
					break;
			case 4: $str_periodo= "and   DATE_FORMAT(data_trans, '%Y-%m') >= '". $ano ."-10'
									";
					break;
			case 'a': $str_periodo= "and   DATE_FORMAT(data_trans, '%Y') >= '". $ano ."'
									";
						break;
		}
	}

	$result_entradas= mysql_query("select sum(qtde) as entradas from fi_estoque_mov
									where id_empresa = '". $id_empresa ."'
									and   id_item = '". $id_item ."'
									and   tipo_trans = 'e'
									". $str_periodo ."
									and   DATE_FORMAT(data_trans, '%Y') = '". $ano ."'
									");
	
	$rs_entradas= mysql_fetch_object($result_entradas);
	//echo $rs_entradas->entradas ."|";
	
	$result_saidas= mysql_query("select sum(qtde) as saidas from fi_estoque_mov
								where id_empresa = '". $id_empresa ."'
								and   id_item = '". $id_item ."'
								and   (tipo_trans = 'm' or tipo_trans = 's' or tipo_trans = 'd')
								". $str_periodo ."
								and   DATE_FORMAT(data_trans, '%Y') = '". $ano ."'
								");
								
								//die();
	//13.000
	$rs_saidas= mysql_fetch_object($result_saidas);
	//echo $rs_saidas->saidas ."|";
	
	return( (($rs->qtde_atual)-($rs_entradas->entradas))+($rs_saidas->saidas) );
	
}

function pega_entradas($periodo, $ano, $periodicidade, $id_empresa, $id_item) {
	if ($periodo!="") {
		$periodo2= explode("/", $_POST["periodo"]);
		
		$str_periodo= "and   DATE_FORMAT(data_trans, '%m') = '". $periodo2[0] ."' ";
		$ano= $periodo2[1];
	}
	else {
		switch($periodicidade) {
			case 1: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '1'
										  or DATE_FORMAT(data_trans, '%c') = '2'
										  or DATE_FORMAT(data_trans, '%c') = '3')
									";
					break;
			case 2: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '4'
										  or DATE_FORMAT(data_trans, '%c') = '5'
										  or DATE_FORMAT(data_trans, '%c') = '6')
									";
					break;
			case 3: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '7'
										  or DATE_FORMAT(data_trans, '%c') = '8'
										  or DATE_FORMAT(data_trans, '%c') = '9')
									";
					break;
			case 4: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '10'
										  or DATE_FORMAT(data_trans, '%c') = '11'
										  or DATE_FORMAT(data_trans, '%c') = '12')
									";
					break;
			case "a": $str_periodo= "";
						break;
		}
	}
	
	$result= mysql_query("select sum(qtde) as entradas from fi_estoque_mov
							where id_empresa = '". $id_empresa ."'
							and   id_item = '". $id_item ."'
							and   tipo_trans = 'e'
							and   DATE_FORMAT(data_trans, '%Y') = '$ano'
							". $str_periodo ."
							");

	$rs= mysql_fetch_object($result);
	return($rs->entradas);
	
}


function pega_saidas($periodo, $ano, $periodicidade, $id_empresa, $id_item) {
	if ($periodo!="") {
		$periodo2= explode("/", $_POST["periodo"]);
		
		$str_periodo= "and   DATE_FORMAT(data_trans, '%m') = '". $periodo2[0] ."'";
		$ano= $periodo2[1];
	}
	else {
		switch($periodicidade) {
			case 1: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '1'
										  or DATE_FORMAT(data_trans, '%c') = '2'
										  or DATE_FORMAT(data_trans, '%c') = '3')
									";
					break;
			case 2: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '4'
										  or DATE_FORMAT(data_trans, '%c') = '5'
										  or DATE_FORMAT(data_trans, '%c') = '6')
									";
					break;
			case 3: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '7'
										  or DATE_FORMAT(data_trans, '%c') = '8'
										  or DATE_FORMAT(data_trans, '%c') = '9')
									";
					break;
			case 4: $str_periodo= "and   (DATE_FORMAT(data_trans, '%c') = '10'
										  or DATE_FORMAT(data_trans, '%c') = '11'
										  or DATE_FORMAT(data_trans, '%c') = '12')
									";
					break;
			case "a": $str_periodo= "";
						break;
		}
	}
	
	$result= mysql_query("select sum(qtde) as saidas from fi_estoque_mov
							where id_empresa = '". $id_empresa ."'
							and   id_item = '". $id_item ."'
							and   (tipo_trans = 'm' or tipo_trans = 's' or tipo_trans = 'd')
							and   DATE_FORMAT(data_trans, '%Y') = '$ano'
							". $str_periodo ."
							");

	$rs= mysql_fetch_object($result);
	return($rs->saidas);
	
}


function pega_tipo_apres($tipo) {
	$vetor= array();

	$vetor[0][0]= "u";
	$vetor[0][1]= "un(s)";
	$vetor['u'][1]= $vetor[0][1];

	$vetor[1][0]= "c";
	$vetor[1][1]= "cx(s)";
	$vetor['c'][1]= $vetor[1][1];
	
	$vetor[2][0]= "t";
	$vetor[2][1]= "lt(s)";
	$vetor['t'][1]= $vetor[2][1];
	
	$vetor[3][0]= "r";
	$vetor[3][1]= "rl(s)";
	$vetor['r'][1]= $vetor[3][1];
	
	$vetor[4][0]= "m";
	$vetor[4][1]= "m(s)";
	$vetor['m'][1]= $vetor[4][1];
	
	$vetor[5][0]= "k";
	$vetor[5][1]= "kg(s)";
	$vetor['k'][1]= $vetor[5][1];
	
	$vetor[6][0]= "p";
	$vetor[6][1]= "pct(s)";
	$vetor['p'][1]= $vetor[6][1];

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo][1]);
}

function pega_id_centro_custo_tipo_do_item($id_item) {
	$result= mysql_query("select id_centro_custo_tipo from fi_itens
									where id_item = '$id_item'
									");
	$rs= mysql_fetch_object($result);
	
	return($rs->id_centro_custo_tipo);
}

function pega_qtde_atual_item($id_empresa, $id_item) {
	$result= mysql_query("select qtde_atual from fi_estoque
									where id_empresa = '$id_empresa'
									and   id_item = '$id_item'
									");
	$rs= mysql_fetch_object($result);
	
	if ($rs->qtde_atual=="") $qtde_atual= 0;
	else $qtde_atual= $rs->qtde_atual;
	return($qtde_atual);
}

function pega_qtde_atual_item_deposito($id_deposito, $id_item) {
	$result= mysql_query("select qtde_atual from fi_estoque_deposito
									where id_deposito = '$id_deposito'
									and   id_item = '$id_item'
									");
	$rs= mysql_fetch_object($result);
	
	if ($rs->qtde_atual=="") $qtde_atual= 0;
	else $qtde_atual= $rs->qtde_atual;
	return($qtde_atual);
}


function pega_id_centro_custo_tipo_pelo_id_item($id_item) {
	$rs= mysql_fetch_object(mysql_query("select id_centro_custo_tipo from fi_itens
											where id_item = '$id_item'
											"));
	return($rs->id_centro_custo_tipo);
}

function pega_item($id_item) {
	$rs= mysql_fetch_object(mysql_query("select item from fi_itens
											where id_item = '$id_item'
											"));
	return($rs->item);
}

function pega_tipo_transacao($tipo) {
	$vetor= array();

	$vetor[0][0]= "todos";
	$vetor[0][1]= "Todas as opera��es";
	$vetor['todos'][1]= "Todas as opera��es";

	$vetor[1][0]= "e";
	$vetor[1][1]= "Entrada";
	$vetor['e'][1]= "Entrada";
	
	$vetor[2][0]= "s";
	$vetor[2][1]= "Sa�da";
	$vetor['s'][1]= "Sa�da";
	
	$vetor[3][0]= "m";
	$vetor[3][1]= "Movimenta��o";
	$vetor['m'][1]= "Movimenta��o";

	$vetor[4][0]= "d";
	$vetor[4][1]= "Sa�da por receita";
	$vetor['d'][1]= "Sa�da por receita";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo][1]);
}

function pega_centro_custo_tipo($id_centro_custo_tipo) {
	$rs= mysql_fetch_object(mysql_query("select centro_custo_tipo from fi_centro_custos_tipos
											where id_centro_custo_tipo = '$id_centro_custo_tipo' "));
	return($rs->centro_custo_tipo);
}

?>