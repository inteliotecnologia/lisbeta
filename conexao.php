<?
	
foreach($_GET as $__k => $__v) $$__k = $__v;
foreach($_POST as $__i => $__x) $$__i = $__x;

session_start();

if ($_SERVER['SERVER_NAME']=="localhost") {
	$conexao= @mysql_connect("localhost", "root", "") or die("O servidor está um pouco instável, favor tente novamente!");
	@mysql_select_db("lisbeta_19_2_2008") or die("O servidor está um pouco instável, favor tente novamente!!");
}
else {
	//$conexao= @mysql_connect("webdb.matrix.com.br", "lisbeta", "47li2345") or die("O servidor está um pouco instável, favor tente novamente mais tarde!");
	//@mysql_select_db("lisbeta") or die("O servidor está um pouco instável, favor tente novamente mais tarde!!");
	
	$conexao= @mysql_connect("200.234.202.111", "lisbeta", "lisbetosa") or die("O servidor está um pouco instável, favor tente novamente mais tarde!");
	@mysql_select_db("lisbeta") or die("O servidor está um pouco instável, favor tente novamente mais tarde!!");
}

define("AJAX_LINK", "link.php?");
define("AJAX_FORM", "form.php?");
define("ID_SISTEMA", "1");

//if ($_GET["pagina"]=="")
//	header("location: index2.php?pagina=login");

//se a pagina atual nao for a de login
if (($_GET["pagina"]!="login") && ($_GET["pagina"]!="esqueci_senha")) {
	$retorno= true;
	//se a pagina nao eh a de login, requere que a variavel de sessao id_usuario_sessao e a tipo_usuario_sessao esteja com valor válido
	if ( ($_SESSION["id_usuario_sessao"]!="") && ($_SESSION["tipo_usuario_sessao"]!="") && ($_SESSION["id_sistema"]==ID_SISTEMA) ) {
		//se a variavel de sessao tem valor (fez login normalmente)
		//escolhe o tipo de usuario
		switch($_SESSION["tipo_usuario_sessao"]) {
			case 'p':
						if ($_GET["pagina"]!="login_pos") {
							if ($_SESSION["id_posto_sessao"]=="")
								$retorno= false;
						}
						break;
			case 'c':
						if ($_GET["pagina"]!="login_pos") {
							if (($_SESSION["id_cidade_sessao"]=="") && ($_SESSION["trocando"]=="")) {
								$retorno= false;
							}
						}
						break;
			case 'a': $retorno= true;
						break;
			default: $retorno= false;
						break;
		}
	}
	else
		$retorno= false;
	
	if (!$retorno)
		header("location: index2.php?pagina=login&redireciona");
}

function eh_secretario($permissao) {
	if (strpos($permissao, "s"))
		$retorno= true;
	else
		$retorno= false;
	
	return($retorno);
}

function pode($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ($permissao=="www") $retorno= true;
	
	return($retorno);
}

function enviar_email($email, $titulo, $corpo) {
	$enviado= mail($email, $titulo, $corpo, "From: Lisbeta Saúde <nao-responda@lisbeta.com.br> \nContent-type: text/html\n");
	return($enviado);
}

function pode_todos($areas, $permissao) {
	$tamanho= strlen($areas);
	$retorno= false;
	
	for ($i=0; $i<$tamanho; $i++) {
		if (pode($areas[$i], $permissao))
			$retorno=true;
		else {
			$retorno=false;
			break;
		}
	}

	return($retorno);
}

function pode_algum($areas, $permissao) {
	$tamanho= strlen($areas);
	$retorno= false;
	
	for ($i=0; $i<$tamanho; $i++) {
		if (pode($areas[$i], $permissao)) {
			$retorno=true;
			break;
		}
	}

	return($retorno);
}


function pega_permissao($tipo, $id_regiao, $id_usuario) {
	if ($tipo=="c")
		$regiao= "cidade";
	else
		$regiao= "posto";
	
	$result= mysql_query("select permissao from usuarios_". $regiao ."s
							where id_usuario = '$id_usuario'
							and   id_". $regiao ." = '$id_regiao'
							");
	$rs= mysql_fetch_object($result);
	return($rs->permissao);
}

function grava_acesso($id_usuario, $id_posto, $id_cidade, $tipo, $ip, $ip_reverso) {

	$var[0]= "";
	$var[1]= "";
	
	if ($id_posto!="") {
		$var[0] .= "id_posto, ";
		$var[1] .= "'". $id_posto ."', ";
	}
	if ($id_cidade!="") {
		$var[0] .= "id_cidade, ";
		$var[1] .= "'". $id_cidade ."', ";
	}

	$result_ac= mysql_query("insert into acessos (id_usuario, ". $var[0] ." tipo, data_acesso, ip, ip_reverso)
								values ('". $id_usuario ."', ". $var[1] ." '". $tipo ."', '". date("YmdHis") ."', '$ip', '$ip_reverso' ) ");
	
	return(mysql_insert_id());
}

function pega_modo_cadastro_cpf($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select modo_cadastro_cpf from cidades where id_cidade = '$id_cidade' and sistema= '1' "));
	return($rs->modo_cadastro_cpf);
}

function pega_modo_farmacia($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select modo_farmacia from cidades where id_cidade = '$id_cidade' and sistema= '1' "));
	return($rs->modo_farmacia);
}

function pega_modo_almox($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select modo_almox from cidades where id_cidade = '$id_cidade' and sistema= '1' "));
	return($rs->modo_almox);
}

function pega_tipo_material_db($id_material) {
	$rs= mysql_fetch_object(mysql_query("select tipo_material from materiais where id_material = '$id_material' "));
	return($rs->tipo_material);
}

function pega_cbo_familia($id_cbo) {
	$rs= mysql_fetch_object(mysql_query("select id_ofamilia from ocupacoes where id_cbo = '$id_cbo' "));
	return($rs->id_ofamilia);
}

function pega_nome_cbo($id_cbo) {
	$rs= mysql_fetch_object(mysql_query("select * from ocupacoes where id_cbo = '$id_cbo' "));
	return($rs->ocupacao);
}

function pega_cbo($id_cbo) {
	$rs= mysql_fetch_object(mysql_query("select * from ocupacoes where id_cbo = '$id_cbo' "));
	return($rs->id_ofamilia ."-". $rs->id_ocupacao);
}

function pega_tipo_agendamento_posto($id_posto) {
	$result= mysql_query("select tipo_agendamento from postos
							where id_posto = '$id_posto'
							");
	$rs= mysql_fetch_object($result);
	return($rs->tipo_agendamento);
}

function pega_cbo_usuario($tipo, $id_regiao, $id_usuario) {
	if ($tipo=="c") $regiao= "cidade";
	else $regiao= "posto";
	
	$result= mysql_query("select id_cbo from usuarios_". $regiao ."s
							where id_usuario = '$id_usuario'
							and   id_". $regiao ." = '$id_regiao'
							");
	$rs= mysql_fetch_object($result);
	return($rs->id_cbo);
}

function ultimoDiaMes($data=""){
	if (!$data) {
	   $dia = date("d");
	   $mes = date("m");
	   $ano = date("Y");
	} else {
	   $dia = date("d",$data);
	   $mes = date("m",$data);
	   $ano = date("Y",$data);
	}
	$data = mktime(0, 0, 0, $mes, 1, $ano);
	return date("d",$data-1);
}

function calcula_idade_completa($nascimento) {
	//Data Nascimento
	$nascimento = explode('/', $nascimento);

	$ano = $nascimento[2];
	$mes = $nascimento[1];
	$dia = $nascimento[0];

	$dia1 = $dia;
	$mes1 = $mes;
	$ano1 = $ano;

    $dia2 = date("d");
    $mes2 = date("m");
    $ano2 = date("Y");

    $dif_ano = $ano2 - $ano1;
    $dif_mes = $mes2 - $mes1;
    $dif_dia = $dia2 - $dia1;

    if ( ($dif_mes == 0) and ($dia2 < $dia1) ) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       $dif_mes = 11;
       $dif_ano--;
    } elseif ($dif_mes < 0) {
       $dif_mes = (12 - $mes1) + $mes2;
       $dif_ano--;
       if ($dif_dia<0){
          $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
          $dif_mes--;
       }
    } elseif ($dif_dia < 0) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       if ($dif_mes>0) {
          $dif_mes--;
       }
    }
	/*
    if ($dif_ano>0) {
       $dif_ano = $dif_ano . " ano" . (($dif_ano>1) ? "s ": " ") ;
    } else { $dif_ano = ""; }
    if ($dif_mes>0) {
       $dif_mes = $dif_mes . " mes" . (($dif_mes>1) ? "es ": " ") ;
    } else { $dif_mes = ""; }
    if ($dif_dia>0) {
       $dif_dia = $dif_dia . " dia" . (($dif_dia>1) ? "s ": " ") ;
    } else { $dif_dia = ""; }
	*/
    return $dif_ano . $dif_mes . $dif_dia;

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
		return("<span class=\"vermelho\">Não disponível!</span>");
}

function calcula_meses($nascimento) {
	//Data Nascimento
	$nascimento = explode('/', $nascimento);

	$ano = $nascimento[2];
	$mes = $nascimento[1];
	$dia = $nascimento[0];

	$dia1 = $dia;
	$mes1 = $mes;
	$ano1 = $ano;

    $dia2 = date("d");
    $mes2 = date("m");
    $ano2 = date("Y");

    $dif_ano = $ano2 - $ano1;
    $dif_mes = $mes2 - $mes1;
    $dif_dia = $dia2 - $dia1;

    if ( ($dif_mes == 0) and ($dia2 < $dia1) ) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       $dif_mes = 11;
       $dif_ano--;
    } elseif ($dif_mes < 0) {
       $dif_mes = (12 - $mes1) + $mes2;
       $dif_ano--;
       if ($dif_dia<0){
          $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
          $dif_mes--;
       }
    } elseif ($dif_dia < 0) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       if ($dif_mes>0) {
          $dif_mes--;
       }
    }
	/*
    if ($dif_ano>0) {
       $dif_ano = $dif_ano . " ano" . (($dif_ano>1) ? "s ": " ") ;
    } else { $dif_ano = ""; }
    if ($dif_mes>0) {
       $dif_mes = $dif_mes . " mes" . (($dif_mes>1) ? "es ": " ") ;
    } else { $dif_mes = ""; }
    if ($dif_dia>0) {
       $dif_dia = $dif_dia . " dia" . (($dif_dia>1) ? "s ": " ") ;
    } else { $dif_dia = ""; }
	*/
    return (($dif_ano*12)+$dif_mes);

}

/* ---------------------------------------- BALANÇO ----------------------------------------------- */

function pega_estoque_inicial($ano, $periodicidade, $id_cidade, $id_remedio) {
	//pegar estoque atual
	$result= mysql_query("select  qtde_atual from almoxarifado_atual
							where id_cidade = '". $id_cidade ."'
							and   id_remedio = '". $id_remedio ."'
							and   tipo_apres = 'u'
							");
	$rs= mysql_fetch_object($result);
	//echo $rs->qtde_atual ."|";
	
	//7.750
	
	$ano_novo= $ano+1;
	
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

	$result_entradas= mysql_query("select sum(qtde) as entradas from almoxarifado_mov
									where id_cidade = '". $id_cidade ."'
									and   id_remedio = '". $id_remedio ."'
									and   tipo_apres = 'u'
									and   tipo_trans = 'e'
									". $str_periodo ."
									");
	//0
	
	$rs_entradas= mysql_fetch_object($result_entradas);
	//echo $rs_entradas->entradas ."|";
	
	$result_saidas= mysql_query("select sum(qtde) as saidas from almoxarifado_mov
								where id_cidade = '". $id_cidade ."'
								and   id_remedio = '". $id_remedio ."'
								and   tipo_apres = 'u'
								and   (tipo_trans = 'm' or tipo_trans = 's' or tipo_trans = 'd')
								". $str_periodo ."
								");
	//13.000
	$rs_saidas= mysql_fetch_object($result_saidas);
	//echo $rs_saidas->saidas ."|";
	
	return( (($rs->qtde_atual)-($rs_entradas->entradas))+($rs_saidas->saidas) );
	
}

function pega_entradas($ano, $periodicidade, $id_cidade, $id_remedio) {
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
	
	$result= mysql_query("select sum(qtde) as entradas from almoxarifado_mov
							where id_cidade = '". $id_cidade ."'
							and   id_remedio = '". $id_remedio ."'
							and   tipo_apres = 'u'
							and   tipo_trans = 'e'
							and   DATE_FORMAT(data_trans, '%Y') = '$ano'
							". $str_periodo ."
							");

	$rs= mysql_fetch_object($result);
	return($rs->entradas);
	
}


function pega_saidas($ano, $periodicidade, $id_cidade, $id_remedio) {
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
	
	$result= mysql_query("select sum(qtde) as saidas from almoxarifado_mov
							where id_cidade = '". $id_cidade ."'
							and   id_remedio = '". $id_remedio ."'
							and   tipo_apres = 'u'
							and   (tipo_trans = 'm' or tipo_trans = 's' or tipo_trans = 'd')
							and   DATE_FORMAT(data_trans, '%Y') = '$ano'
							". $str_periodo ."
							");

	$rs= mysql_fetch_object($result);
	return($rs->saidas);
	
}

/* ---------------------------------------- RELATÓRIOS ----------------------------------------------- */

function pega_num_postos($id_cidade) {
	$result= mysql_query("select count(id_posto) as num_postos from postos
							where id_cidade = '$id_cidade'
							");
	$rs= mysql_fetch_object($result);
	return($rs->num_postos);
}

function pega_num_remedios($tipo1, $id_tipo1, $tipo2, $periodo_tipo2, $id_remedio) {
	//posto ou cidade, identificador do posto ou cidade, momento, identificador
	return($rs->num_remedios);
}

function traduz_mes($mes) {
	switch($mes) {
		case 1: $retorno= "Janeiro"; break;
		case 2: $retorno= "Fevereiro"; break;
		case 3: $retorno= "Março"; break;
		case 4: $retorno= "Abril"; break;
		case 5: $retorno= "Maio"; break;
		case 6: $retorno= "Junho"; break;
		case 7: $retorno= "Julho"; break;
		case 8: $retorno= "Agosto"; break;
		case 9: $retorno= "Setembro"; break;
		case 10: $retorno= "Outubro"; break;
		case 11: $retorno= "Novembro"; break;
		case 12: $retorno= "Dezembro"; break;
		default: $retorno= "System failure..."; break;
	}
	return($retorno);
}

/* ---------------------------------------- FIM DOS RELATÓRIOS ----------------------------------------------- */

function pega_origem_consulta($var) {
	$origem= explode("@", $var);

	if ($origem[0]=="f")
		return("Pronto atendimento");
	else
		return("Agendamento");
}

function pega_tipo_consulta($tipo) {
	if ($tipo=='c') $retorno= "Consulta normal";
	else $retorno= "Consulta de retorno";
	return($retorno);
}

function pega_local_consulta($tipo) {
	if ($tipo=='p') $retorno= "Posto";
	else $retorno= "Domicílio";
	return($retorno);
}

function pega_vias_aplicacao($tipo) {
	$vetor= array();
	
	$vetor[1]= "via endovenosa";
	$vetor[2]= "via intra-muscular";
	$vetor[3]= "via subcutânea";
	$vetor[4]= "via intra dérmica";
	$vetor[5]= "via sub-lingual";
	$vetor[6]= "uso tópico";
	$vetor[7]= "via otológica";
	$vetor[8]= "via nasal";
	
	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo]);
}


function pega_encaminhamento($tipo) {
	$vetor= array();
	
	$vetor[1]= "residência";
	$vetor[2]= "internação domiciliar";
	$vetor[3]= "atend. especializado";
	$vetor[4]= "internação hospitalar";
	$vetor[5]= "urgência/emergência";
	$vetor[6]= "óbito";
	$vetor[7]= "outros";
	
	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo]);
}


function pega_tipo_tomar($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[0][0]= "c";
	$vetor[0][1]= "comprimido(s)";
	$vetor["c"][1]= $vetor[0][1];

	$vetor[1][0]= "g";
	$vetor[1][1]= "gota(s)";
	$vetor["g"][1]= $vetor[1][1];

	$vetor[2][0]= "m";
	$vetor[2][1]= "ml";
	$vetor["m"][1]= $vetor[2][1];
	
	$vetor[3][0]= "s";
	$vetor[3][1]= "spray";
	$vetor["s"][1]= $vetor[3][1];
	
	$vetor[4][0]= "f";
	$vetor[4][1]= "flaconete";
	$vetor["f"][1]= $vetor[4][1];
	
	$vetor[5][0]= "d";
	$vetor[5][1]= "dose";
	$vetor["d"][1]= $vetor[5][1];

	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}


function pega_tipo_ida($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[0][0]= "c";
	$vetor[0][1]= "Consulta";
	$vetor["c"][1]= $vetor[0][1];

	$vetor[1][0]= "e";
	$vetor[1][1]= "Exame";
	$vetor["e"][1]= $vetor["c"][1];

	$vetor[2][0]= "i";
	$vetor[2][1]= "Internação";
	$vetor["i"][1]= $vetor[2][1];
	
	$vetor[3][0]= "t";
	$vetor[3][1]= "Tratamento";
	$vetor["t"][1]= $vetor[3][1];
	
	$vetor[4][0]= "o";
	$vetor[4][1]= "Erro de digitação";
	$vetor["o"][1]= $vetor[4][1];
	
	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}


function pega_situacao_solicitacao_tfd($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[0][0]= 0;
	$vetor[0][1]= "Aguardando";

	$vetor[1][0]= 1;
	$vetor[1][1]= "Enviado para a regional";

	$vetor[2][0]= 2;
	$vetor[2][1]= "Aceito pela regional";
	
	$vetor[3][0]= 3;
	$vetor[3][1]= "Negado pela regional";
	
	$vetor[4][0]= 4;
	$vetor[4][1]= "Aceito pela prefeitura";

	$vetor[5][0]= 5;
	$vetor[5][1]= "Já viajou";

	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}

function pega_origem_entrada($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[0][0]= "t";
	$vetor[0][1]= "Licitação";
	$vetor["t"][1]= "Licitação";

	$vetor[1][0]= "c";
	$vetor[1][1]= "Compra direta";
	$vetor["c"][1]= "Compra direta";

	$vetor[2][0]= "d";
	$vetor[2][1]= "Doação";
	$vetor["d"][1]= "Doação";
	
	$vetor[3][0]= "o";
	$vetor[3][1]= "Outros";
	$vetor["o"][1]= "Outros";
	
	$vetor[4][0]= "e";
	$vetor[4][1]= "Erro de digitação";
	$vetor["e"][1]= "Erro de digitação";

	$vetor[5][0]= "s";
	$vetor[5][1]= "Estorno de entrega";
	$vetor["s"][1]= "Estorno de entrega";
	
	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}

function pega_origem_saida($tipo) {
	$vetor= array();
	
	$vetor[0][0]= "e";
	$vetor[0][1]= "Estorno";
	$vetor["e"][1]= "Estorno";
	
	$vetor[1][0]= "b";
	$vetor[1][1]= "Distribuição";//temporário
	$vetor["b"][1]= "Distribuição";
	
	$vetor[2][0]= "v";
	$vetor[2][1]= "Validade";
	$vetor["v"][1]= "Validade";
	
	$vetor[3][0]= "o";
	$vetor[3][1]= "Outros";
	$vetor["o"][1]= "Outros";
	
	$vetor[4][0]= "p";
	$vetor[4][1]= "Periódica";
	$vetor["p"][1]= $vetor[4][1];
	
	$vetor[5][0]= "r";
	$vetor[5][1]= "Por receita";
	$vetor["r"][1]= $vetor[5][1];
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo][1]);
}

function pega_tipo_transacao($tipo) {
	$vetor= array();

	$vetor[0][0]= "todos";
	$vetor[0][1]= "Todas as operações";
	$vetor['todos'][1]= "Todas as operações";

	$vetor[1][0]= "e";
	$vetor[1][1]= "Entrada";
	$vetor['e'][1]= "Entrada";
	
	$vetor[2][0]= "s";
	$vetor[2][1]= "Saída";
	$vetor['s'][1]= "Saída";
	
	$vetor[3][0]= "m";
	$vetor[3][1]= "Movimentação";
	$vetor['m'][1]= "Movimentação";

	$vetor[4][0]= "d";
	$vetor[4][1]= "Saída por receita";
	$vetor['d'][1]= "Saída por receita";
	
	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}

function pega_tipo_material($tipo) {
	$vetor= array();

	$vetor[0][0]= "u";
	$vetor[0][1]= "un.";
	$vetor['u'][1]= "un.";

	$vetor[1][0]= "p";
	$vetor[1][1]= "pct.";
	$vetor['p'][1]= "pct.";
	
	$vetor[2][0]= "c";
	$vetor[2][1]= "cx.";
	$vetor['c'][1]= "cx.";
	
	$vetor[3][0]= "r";
	$vetor[3][1]= "rl.";
	$vetor['r'][1]= "rl.";

	$vetor[4][0]= "t";
	$vetor[4][1]= "lt.";
	$vetor['t'][1]= "lt.";

	$vetor[5][0]= "g";
	$vetor[5][1]= "gl.";
	$vetor['g'][1]= "gl.";

	$vetor[6][0]= "f";
	$vetor[6][1]= "fr.";
	$vetor['f'][1]= "fr.";

	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}

function pega_tipo_remedio($tipo) {
	$vetor= array();

	$vetor[0][0]= "c";
	$vetor[0][1]= "compr.";
	$vetor['c'][1]= "compr.";

	$vetor[1][0]= "i";
	$vetor[1][1]= "inj.";
	$vetor['i'][1]= "inj.";
	
	$vetor[2][0]= "f";
	$vetor[2][1]= "frasco";
	$vetor['f'][1]= "frasco";
	
	$vetor[3][0]= "g";
	$vetor[3][1]= "gotas";
	$vetor['g'][1]= "gotas";

	$vetor[4][0]= "m";
	$vetor[4][1]= "pomada";
	$vetor['m'][1]= "pomada";

	$vetor[5][0]= "a";
	$vetor[5][1]= "caps.";
	$vetor['a'][1]= "caps.";

	$vetor[6][0]= "s";
	$vetor[6][1]= "susp.";
	$vetor['s'][1]= "susp.";

	$vetor[7][0]= "q";
	$vetor[7][1]= "liq.";
	$vetor['q'][1]= "liq.";

	$vetor[8][0]= "x";
	$vetor[8][1]= "xep.";
	$vetor['x'][1]= "xep.";

	$vetor[9][0]= "p";
	$vetor[9][1]= "pcte.";
	$vetor['p'][1]= "pcte.";

	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo][1]);
}

function pega_fornecedor($id_fornecedor) {
	$rs= mysql_fetch_object(mysql_query("select fornecedor from fornecedores where id_fornecedor = '$id_fornecedor' "));
	return($rs->fornecedor);
}

function cidade_esta_ativa($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select sistema from cidades where id_cidade= '$id_cidade' "));
	if ($rs->sistema==1)
		return(true);
	else
		return(false);
}

function posto_esta_ativo($id_posto) {
	$rs= mysql_fetch_object(mysql_query("select situacao from postos where id_posto= '$id_posto' "));
	if ($rs->situacao==1)
		return(true);
	else
		return(false);
}

function pode_entregar($id_usuario, $id_posto) {
	$rs= mysql_fetch_object(mysql_query("select dist from usuarios_postos 
											where id_usuario = '$id_usuario'
											and   id_posto = '$id_posto' "));
	return($rs->dist);
}

function pode_producao($id_usuario, $id_posto) {
	$rs= mysql_fetch_object(mysql_query("select prod from usuarios_postos 
											where id_usuario = '$id_usuario'
											and   id_posto = '$id_posto' "));
	return($rs->prod);
}

function pode_materiais($id_usuario, $id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select mat from usuarios_cidades
											where id_usuario = '$id_usuario'
											and   id_cidade = '$id_cidade' "));
	return($rs->mat);
}


function mostra_cpf_ou_responsavel($cpf, $id_responsavel) {
	
	if ($cpf=="") {
		if (($id_responsavel!="") && ($id_responsavel!="0")) {
			$rs_dependente= mysql_fetch_object(mysql_query("select nome, cpf from pessoas where id_pessoa = '$id_responsavel' "));
			$retorno .= " <span class=vermelho>dep.</span> ". $rs_dependente->nome ." (". formata_cpf($rs_dependente->cpf) .") ";
		}
		else
			echo " <span class=vermelho>sem CPF cadastrado!</span>";
	}
	else
		$retorno .= formata_cpf($cpf);
	
	return($retorno);
}

function atende_no_posto($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select postos.id_posto from usuarios, usuarios_postos, postos
											where usuarios.id_usuario = '$id_usuario'
											and   usuarios.id_usuario = usuarios_postos.id_usuario
											and   postos.id_posto = usuarios_postos.id_posto
											"));
	return($rs->id_posto);
}

function atende_neste_posto($id_usuario, $id_posto) {
	$result= mysql_query("select postos.id_posto from usuarios, postos, usuarios_postos
											where usuarios.id_usuario = '$id_usuario'
											and   postos.id_posto = '$id_posto'
											and   usuarios.id_usuario = usuarios_postos.id_usuario
											and   postos.id_posto = usuarios_postos.id_posto
											");
	if (mysql_num_rows($result)>0)
		return(true);
	else
		return(false);
}


function atende_em_x_postos($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select count(usuarios.id_usuario) as num from usuarios, usuarios_postos 
											where usuarios.id_usuario = '$id_usuario'
											and   usuarios.id_usuario = usuarios_postos.id_usuario"));
	return($rs->num);
}

function esta_vinculado_a_x_cidades($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select count(usuarios.id_usuario) as num from usuarios, usuarios_cidades 
											where usuarios.id_usuario = '$id_usuario'
											and   usuarios.id_usuario = usuarios_cidades.id_usuario"));
	return($rs->num);
}

function esta_vinculado_a_cidade($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select usuarios_cidades.id_cidade from usuarios, usuarios_cidades
											where usuarios.id_usuario = '$id_usuario'
											and   usuarios.id_usuario = usuarios_cidades.id_usuario
											"));
	return($rs->id_cidade);
}

function vinculado_a_esta_cidade($id_usuario, $id_cidade) {
	$result= mysql_query("select usuarios_cidades.id_cidade from usuarios, usuarios_cidades
											where usuarios.id_usuario = '$id_usuario'
											and   usuarios.id_usuario = usuarios_cidades.id_usuario
											and   usuarios_cidades.id_cidade = '$id_cidade'
											");
	if (mysql_num_rows($result)==0)
		return(false);
	else
		return(true);
}


function pega_tipo_acao($acao) {
	switch($acao) {
		case 't': $acaoc= "Tomar "; break;
		case 'a': $acaoc= "Aplicar "; break;
		case 'n': $acaoc= "Nebulizar "; break;
	}
	return($acaoc);
}



function pega_exame($id_exame) {
	$rs= mysql_fetch_object(mysql_query("select exame from exames
											where id_exame = '$id_exame'
											"));
	return($rs->exame);
}

function pega_material($id_material) {
	$rs= mysql_fetch_object(mysql_query("select material from materiais
											where id_material = '$id_material'
											"));
	return($rs->material);
}


function pega_remedio($id_remedio) {
	$rs= mysql_fetch_object(mysql_query("select classificacao_remedio, remedio from remedios
											where id_remedio = '$id_remedio'
											"));
	if ($rs->classificacao_remedio=="c")
		$antes= "<img src=images/preto.gif alt=med /> ";
	else
		$antes= "";
	
	$remedio= $antes . $rs->remedio;
	return($remedio);
}

function pega_apelidos($id_remedio) {
	$result= mysql_query("select apelido from apelidos where id_remedio = '$id_remedio' ");
	
	if (mysql_num_rows($result)==0)
		$apelidos= "Sem apelidos cadastrados.";
	while ($rs= mysql_fetch_object($result))
		$apelidos .= $rs->apelido ."; ";
	return ($apelidos);
}

function pega_id_remedio_do_apelido($id_apelido) {
	$rs= mysql_fetch_object(mysql_query("select id_remedio from apelidos where id_apelido = '$id_apelido' "));
	return($rs->id_remedio);
}

function pega_qtde_pego($id_consulta_remedio) {
	$rs= mysql_fetch_object(mysql_query("select almoxarifado_mov.qtde as qtde_pego
											from  consultas_remedios, almoxarifado_mov
											where consultas_remedios.id_consulta_remedio = '$id_consulta_remedio'
											and   consultas_remedios.id_mov = almoxarifado_mov.id_mov
											and   almoxarifado_mov.tipo_trans = 'd'
											"));
	return($rs->qtde_pego);
}

function pega_apresentacao($apres) {
	switch ($apres) {
		case 'c': $retorno= "cx(s)"; break;
		case 'u': $retorno= "unid(s)"; break;
	}
	return ($retorno);
}

function pega_qtde_atual_remedio($local, $id_local, $id_remedio, $tipo_apres) {
	if ($local=='c')
		$result= mysql_query("select qtde_atual from almoxarifado_atual
									where id_cidade = '$id_local'
									and   id_remedio = '$id_remedio'
									and   tipo_apres = 'u'
									");
	else
		$result= mysql_query("select qtde_atual from postos_estoque
									where id_posto = '$id_local'
									and   id_remedio = '$id_remedio'
									and   tipo_apres = 'u'
									");
	$rs= mysql_fetch_object($result);
	
	if ($rs->qtde_atual=="")
		$qtde_atual= 0;
	else
		$qtde_atual= $rs->qtde_atual;
	
	return($qtde_atual);
}

function pega_qtde_atual_material($local, $id_local, $id_material, $tipo_apres) {
	if ($local=='c')
		$result= mysql_query("select qtde_atual from almoxarifadom_atual
									where id_cidade = '$id_local'
									and   id_material = '$id_material'
									and   tipo_apres = '$tipo_apres'
									");
	else
		$result= mysql_query("select qtde_atual from postosm_estoque
									where id_posto = '$id_local'
									and   id_material = '$id_material'
									and   tipo_apres = '$tipo_apres'
									");
	$rs= mysql_fetch_object($result);
	
	if ($rs->qtde_atual=="")
		$qtde_atual= 0;
	else
		$qtde_atual= $rs->qtde_atual;
	
	return($qtde_atual);
}


function pega_id_cidade_do_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select cidades.id_cidade from usuarios, pessoas, cidades
											where usuarios.id_usuario = '$id_usuario'
											and   pessoas.id_pessoa = usuarios.id_pessoa
											and   pessoas.id_cidade = cidades.id_cidade
											"));
	return($rs->id_cidade);
}


function pega_id_cidade_do_posto($id_posto) {
	$rs= mysql_fetch_object(mysql_query("select id_cidade from postos where id_posto = '$id_posto' "));
	return($rs->id_cidade);
}

function pega_psf($id_posto) {
	$rs= mysql_fetch_object(mysql_query("select postos.posto, cidades.cidade, ufs.uf from postos, cidades, ufs
											where postos.id_cidade = cidades.id_cidade
											and   cidades.id_uf = ufs.id_uf
											and   postos.id_posto = '$id_posto'
											"));
	return($rs->posto);
}

function pega_posto($id_posto) {
	$rs= mysql_fetch_object(mysql_query("select postos.posto, cidades.cidade, ufs.uf from postos, cidades, ufs
											where postos.id_cidade = cidades.id_cidade
											and   cidades.id_uf = ufs.id_uf
											and   postos.id_posto = '$id_posto'
											"));
	return($rs->posto ." (". $rs->cidade ."/". $rs->uf .")");
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

function pega_cid($id_cid) {
	$rs= mysql_fetch_object(mysql_query("select * from cid where id_cid = '$id_cid' "));
	return($rs->classificacao . $rs->descricao);
}

function pega_cod_cid($id_cid) {
	$rs= mysql_fetch_object(mysql_query("select * from cid where id_cid = '$id_cid' "));
	return($rs->codigo);
}

function retorna_intervalo($datac1, $datac2) {
	$anterior= explode(" ", $datac1);
	$hora1= explode(":", $anterior[1]);
	$data1= explode("-", $anterior[0]);
	$completa1= mktime($hora1[0], $hora1[1], $hora1[2], $data1[1], $data1[2], $data1[0]);
	
	$proxima= explode(" ", $datac2);
	$hora2= explode(":", $proxima[1]);
	$data2= explode("-", $proxima[0]);
	$completa2= mktime($hora2[0], $hora2[1], $hora2[2], $data2[1], $data2[2], $data2[0]);
	
	$diferenca= $completa2-$completa1;
	
	return($diferenca);
}

function pega_id_uf($id_cidade) {
	$rs= mysql_fetch_object(mysql_query("select id_uf from cidades
											where id_cidade = '$id_cidade'
											"));
	return($rs->id_uf);
}


function valida_cpf($cpf){
	for ($i=0; $i<10; $i++) {
		if ($cpf == str_repeat($i, 11) or !preg_match("@^[0-9]{11}$@", $cpf) or $cpf == "12345678909")
			return false;        
		if ($i<9)
			$soma[] = $cpf{$i} * (10-$i);
		$soma2[]= $cpf{$i} * (11-$i);            
	}
	if(((array_sum($soma)% 11) < 2 ? 0 : 11 - ( array_sum($soma)  % 11 )) != $cpf{9}) return false;
	return ((( array_sum($soma2)% 11 ) < 2 ? 0 : 11 - ( array_sum($soma2) % 11 )) != $cpf{10}) ? false : true;
}

function inicia_transacao() {
	mysql_query("set autocommit=0;");
	mysql_query("start transaction;");
}

function finaliza_transacao($var) {
	//numero de querys que retornaram erro
	if ($var==0)
		mysql_query("commit;");
	else
		mysql_query("rollback;");
}

function pega_nome($id_pessoa) {
	$rs= mysql_fetch_object(mysql_query("select nome, situacao_pessoa from pessoas
											where id_pessoa = '$id_pessoa'
											"));
	if ($rs->situacao_pessoa==2)
		$morte= "<img src=images/cruz.png alt=+ />";
	return($rs->nome . $morte);
}

function pega_cpf_pelo_id_pessoa($id_pessoa) {
	$rs= mysql_fetch_object(mysql_query("select cpf from pessoas
											where id_pessoa = '$id_pessoa'
											"));
	return($rs->cpf);
}

function verifica_fila($id_posto) {
	$hoje= date("Y") .'-'. date("m") .'-'. date("d");
	$result_fila= mysql_query("select id_fila from filas
								 where id_posto = '$id_posto'
								 and   atendido = '0'
								 and   data_fila < '". $hoje ."'
								 ") or die(mysql_error());

	while ($rs_fila= mysql_fetch_object($result_fila))
		$result_expira= mysql_query("update filas set atendido = '2' where id_fila= '". $rs_fila->id_fila ."' ");
	
	if (mysql_num_rows($result_fila)>0)
		return(1);
	else
		return(0);
}

function esta_na_fila($id_pessoa) {
	$result_fila= mysql_query("select id_fila from filas
								 where atendido = '0'
								 and   id_pessoa = '$id_pessoa' ");
	if (mysql_num_rows($result_fila)>0)
		return (true);
	else
		return (false);
}

function pega_num_dependentes($id_pessoa) {
	$result= mysql_query("select id_pessoa from pessoas where id_responsavel = '$id_pessoa' ");
	return(mysql_num_rows($result));
}

function pega_sexo($sexo) {
	if ($sexo=="m") return("Masculino"); else return("Feminino");
}

function pega_nome_pelo_cpf($cpf) {
	$rs= mysql_fetch_object(mysql_query("select nome from pessoas
											where cpf = '$cpf'
											"));
	return($rs->nome);
}

function pega_nome_pelo_id_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.nome from pessoas, usuarios
											where pessoas.id_pessoa = usuarios.id_pessoa 
											and   usuarios.id_usuario = '$id_usuario'
											"));
	return($rs->nome);
}

function pega_email_pelo_id_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.email from pessoas, usuarios
											where pessoas.id_pessoa = usuarios.id_pessoa 
											and   usuarios.id_usuario = '$id_usuario'
											"));
	return($rs->email);
}

function pega_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select usuario from usuarios
											where id_usuario = '$id_usuario'
											"));
	return($rs->usuario);
}

function pega_crm_pelo_id_usuario($id_usuario) {
	$rs= mysql_fetch_object(mysql_query("select crm from usuarios
											where id_usuario = '$id_usuario'
											"));
	return($rs->crm);
}

function pega_tipo_usuario($tipo) {
	switch($tipo) {
		case 'a': $tipoc= "Administrador(a)"; break;
		default: $tipoc= "Usuário"; break;
	}
	return($tipoc);
}

function formata_cpf($cpf) {
	if ($cpf!="") {
		$cpfn= substr($cpf, 0, 3) .".". substr($cpf, 3, 3) .".". substr($cpf, 6, 3) ."-". substr($cpf, 9, 2);
		return($cpfn);
	}
	else
		return("<span class=vermelho>sem CPF cadastrado!</span>");
}

function formata_data($var) {
	$var= explode("/", $var, 3);
	
	if (strlen($var[2])==2)
		$var[2]= "20". $var[2];
		
	$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function formata_hora($var) {
	$var= explode(":", $var, 3);
	$var= $var[0] . $var[1] . $var[2];
	return($var);
}

function desformata_data_hifen($var) {
	//10/10/2007
	$var= explode("-", $var, 3);
	
	//2006-10-12
	$var= $var[2] ."/". $var[1] ."/". $var[0];
	return($var);
}

function desformata_data($var) {
	//10/10/2007
	$var= explode("/", $var, 3);
	
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
}

function desformata_hora($var) {
	//10/10/2007
	$var= explode("/", $var, 3);
	
	//2006-10-12
	//$var= $var[2] . $var[1] . $var[0];
	return($var);
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


function formata_valor($var) {
	$var= str_replace(',', '.', str_replace('.', '', $var));
	return($var);
}

// ---------------- PRODUCAO -------------------------

function pega_status_producao_mes($id_cidade, $mes, $ano) {
	$result_sit= mysql_query("select status_producao from producao_mes
								where id_cidade = '$id_cidade'
								and   mes = '$mes'
								and   ano = '$ano'
								");
	$rs_sit= mysql_fetch_object($result_sit);
	
	if ($rs_sit->status_producao==2)
		$retorno= 0;
	else
		$retorno= 1;
	
	return($retorno);

}

function pega_resultado_producao($relatorio, $mes, $ano, $id_posto) {
	
	switch($relatorio) {
		case "ssa2":
					$result= mysql_query("select * from ssa2_dados, microareas
											where microareas.id_microarea = ssa2_dados.id_microarea
											and   microareas.id_posto = '$id_posto'
											and   ssa2_dados.mes = '$mes'
											and   ssa2_dados.ano = '$ano'
											");
					if (mysql_num_rows($result)>0) $retorno= 1;
					else $retorno= 0;
					break;
		case "pma2":
					$result= mysql_query("select * from pma2_dados
											where pma2_dados.id_posto = '$id_posto'
											and   pma2_dados.mes = '$mes'
											and   pma2_dados.ano = '$ano'
											") or die(mysql_error());
					if (mysql_num_rows($result)>0) $retorno= 1;
					else $retorno= 0;
					break;
		case "bpa":
					$result= mysql_query("select * from bpa_dados
											where bpa_dados.id_posto = '$id_posto'
											and   bpa_dados.mes = '$mes'
											and   bpa_dados.ano = '$ano'
											") or die(mysql_error());
					if (mysql_num_rows($result)>0) $retorno= 1;
					else $retorno= 0;
					break;
		default: $retorno= 0;
	}
	return($retorno);
}

function sim_nao($var) {
	if ( ($var==0) || ($var=='') || ($var=="n") )
		$retorno= "<span class=vermelho>NÃO</span>";
	elseif ( ($var==1) || ($var=="s") )
		$retorno= "<span class=verde>SIM</span>";
	else $retorno= "Desempregado";
	return($retorno);
}

// ----------- SOCIAL -------------------------------------------------------
// ----------- SOCIAL -------------------------------------------------------
// ----------- SOCIAL -------------------------------------------------------

function pega_profissao($i) {
	$vetor= array();
	
	$vetor[1]= "Administrador";
	$vetor[2]= "Advogado";
	$vetor[3]= "Agricultor";
	$vetor[4]= "Arquiteto";
	$vetor[5]= "Artista Plástico";
	$vetor[6]= "Consultor";
	$vetor[7]= "Contador";
	$vetor[8]= "Dentista";
	$vetor[9]= "Desenhista";
	$vetor[10]= "Designer";
	$vetor[11]= "Empresário";
	$vetor[12]= "Engenheiro";
	$vetor[13]= "Estudante";
	$vetor[14]= "Fotógrafo";
	$vetor[15]= "Jornalista";
	$vetor[16]= "Médico";
	$vetor[17]= "Músico";
	$vetor[18]= "Operário";
	$vetor[19]= "Pesquisador";
	$vetor[20]= "Professor";
	$vetor[21]= "Profissional da Saúde";
	$vetor[22]= "Profissional de Cinema/TV/Teatro";
	$vetor[23]= "Profissional de Moda";
	$vetor[24]= "Profissional do Governo";
	$vetor[25]= "Profissional Executivo";
	$vetor[26]= "Profissional Liberal";
	$vetor[27]= "Publicitário";
	$vetor[28]= "Trainer";
	$vetor[29]= "Outro";
	$vetor[30]= "Do lar";
	$vetor[31]= "Serviços gerais";
	$vetor[32]= "Aposentado";
	$vetor[33]= "Pensionista";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_estado_civil($i) {
	$vetor= array();
	
	$vetor[1]= "União estável";
	$vetor[2]= "Casado(a)";
	$vetor[3]= "Divorciado(a)";
	$vetor[4]= "Separado(a)";
	$vetor[5]= "Viúvo(a)";
	$vetor[6]= "Solteiro(a)";
	
	if ($i=="l")
		return($vetor);
	else
		return($vetor[$i]);
}

function pega_grau_instrucao($i) {
	$vetor= array();
	
	$vetor[1]= "Analfabeto";
	$vetor[2]= "Semi-analfabeto";
	$vetor[3]= "Ensino fundamental incompleto";
	$vetor[4]= "Ensino fundamental completo";
	$vetor[5]= "Ensino médio incompleto";
	$vetor[6]= "Ensino médio completo";
	$vetor[7]= "Ensino superior incompleto";
	$vetor[8]= "Ensino superior completo";
	
	if ($i=="l")
		return($vetor);
	else
		return($vetor[$i]);
}

function pega_parentesco($i) {
	$vetor= array();
	
	$vetor[1]= "Cônjuge";
	$vetor[2]= "Pai/Mãe";
	$vetor[3]= "Filho(a)";
	$vetor[4]= "Enteado(a)";
	$vetor[5]= "Avô(ó)";
	$vetor[6]= "Tio(a)";
	$vetor[7]= "Irmão(a)";
	$vetor[8]= "Neto(a)";
	$vetor[9]= "Cunhado(a)";
	$vetor[10]= "Genro(Nora)";
	$vetor[11]= "Sogro(a)";
	$vetor[12]= "Amigo(a)";
	$vetor[13]= "Primo(a)";
	$vetor[14]= "Bisavô(a)";
	$vetor[15]= "Bisneto(a)";
	$vetor[16]= "Madrasta";
	$vetor[17]= "Padrasto";
	
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_situacao_habitacional($i) {
	$vetor= array();
	
	$vetor[1]= "Própria";
	$vetor[2]= "Alugada";
	$vetor[3]= "Arrendada";
	$vetor[4]= "Cedida";
	$vetor[5]= "Financiada";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_localizacao_domicilio($i) {
	$vetor= array();
	
	$vetor[1]= "Rural";
	$vetor[2]= "Urbana";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_destino_lixo($i) {
	$vetor= array();
	
	$vetor[1]= "Coletado";
	$vetor[2]= "Queimado";
	$vetor[3]= "Céu aberto";
	$vetor[4]= "Reciclagem";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_abastecimento_agua($i) {
	$vetor= array();
	
	$vetor[1]= "Rede pública";
	$vetor[2]= "Poço";
	$vetor[3]= "Nascente";
	$vetor[4]= "Outro";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_escoamento_sanitario($i) {
	$vetor= array();
	
	$vetor[1]= "Rede pública";
	$vetor[2]= "Fossa séptica";
	$vetor[3]= "Céu aberto";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_tratamento_agua($i) {
	$vetor= array();
	
	$vetor[1]= "Cloração";
	$vetor[2]= "Fervura";
	$vetor[3]= "Filtração";
	$vetor[4]= "Sem tratamento";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_bens($i) {
	$vetor= array();
	
	$vetor[1]= "Automóvel";
	$vetor[2]= "Caminhão";
	$vetor[3]= "Trator";
	$vetor[4]= "Lote";
	$vetor[5]= "Gado";
	$vetor[6]= "Outros";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_tipo_construcao($i) {
	$vetor= array();
	
	$vetor[1]= "Madeira";
	$vetor[2]= "Alvenaria";
	$vetor[3]= "Mista";
	$vetor[4]= "Outro";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_programas_sociais($i) {
	$vetor= array();
	
	$vetor[1]= "Bolsa família";
	$vetor[2]= "Auxílio medicamentos";
	$vetor[3]= "Auxílio habitacional";
	$vetor[4]= "Auxílio cesta básica";
	$vetor[5]= "Outros";
	$vetor[6]= "PETI";
	$vetor[7]= "CEC";
	$vetor[8]= "Exames/internações";
	$vetor[9]= "CAD Único";
	$vetor[10]= "BPC";
	$vetor[11]= "Nenhum";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function tem_dados_sociais($id_pessoa) {
	$result= mysql_query("select id_pse from pessoas_se where id_pessoa= '$id_pessoa' ");
	if (mysql_num_rows($result)==1)
		return(true);
	else
		return(false);
}

function pega_chefe_familia($id_familia) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.nome, pessoas.cpf from pessoas, familias_pessoas
											where familias_pessoas.id_pessoa = pessoas.id_pessoa
											and   familias_pessoas.id_familia = '$id_familia'
											and   familias_pessoas.tipo = '1'
											"));
	return($rs->nome ." ". formata_cpf($rs->cpf));
}

function pega_id_chefe_familia($id_familia) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.id_pessoa, pessoas.cpf from pessoas, familias_pessoas
											where familias_pessoas.id_pessoa = pessoas.id_pessoa
											and   familias_pessoas.id_familia = '$id_familia'
											and   familias_pessoas.tipo = '1'
											"));
	return($rs->id_pessoa);
}

function pega_num_membros($id_familia) {
	$result= mysql_query("select id from familias_pessoas
											where id_familia = '$id_familia'
											");
	return(mysql_num_rows($result));
}

function familia_tem_bem($id_familia, $id_bem) {
	$result= mysql_query("select id_fb from familias_bens
							where id_familia = '$id_familia'
							and   id_bem = '$id_bem'
							");
	if (mysql_num_rows($result)>0)
		return(true);
	else
		return(false);
}

function familia_tem_programa_social($id_familia, $id_programa) {
	$result= mysql_query("select id_fp from familias_programas
							where id_familia = '$id_familia'
							and   id_programa = '$id_programa'
							");
	if (mysql_num_rows($result)>0)
		return(true);
	else
		return(false);
}

function pega_tipo_atendimento($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Puericultura";
	$vetor[2]= "Pré-natal";
	$vetor[3]= "Prevenção do câncer cérvico-uterino";
	$vetor[4]= "DST/AIDS";
	$vetor[5]= "Diabetes";
	$vetor[6]= "Hipertensão arterial";
	$vetor[7]= "Hanseníase";
	$vetor[8]= "Tuberculose";
	$vetor[9]= "Outros";
	$vetor[10]= "Puerperal";

	if ($tipo=='l')
		return($vetor);
	else
		return($vetor[$tipo]);
}

function pega_tipo_consulta_prof($tipo) {
	if ($tipo=="m")	return("médica");
	elseif ($tipo=="e")	return("de enfermagem");
	else return("odontológica");
}

function pega_procedimentos($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Atendimento específico para AT";
	$vetor[2]= "Visita de inspeção sanitária";
	//$vetor[3]= "Atendimento individual (enfermeiro)";
	//$vetor[4]= "Atendimento individual (outros prof. de nível superior)";
	$vetor[3]= "Curativos";
	$vetor[4]= "Inalações";
	$vetor[5]= "Injeções";
	$vetor[6]= "Retirada de pontos";
	$vetor[7]= "Terapia de reidratação oral";
	$vetor[8]= "Sutura";
	$vetor[9]= "Atendimento em grupo (educação em saúde)";
	$vetor[10]= "Procedimentos coletivos I (PC I)";
	$vetor[11]= "Reuniões";
	$vetor[12]= "Glicemia capilar";
	$vetor[13]= "Verificação de P.A.";
	$vetor[14]= "Coleta de material p/ exame laboratorial";
	$vetor[15]= "Excisão e/ou sut. simples de lesões na pele";
	$vetor[16]= "Drenagem de abcesso";
	$vetor[17]= "Retirada de corpo estranho da cavidade auditiva ou nasal";
	$vetor[18]= "Atend. urgência em atensão básica c/ observação até 8h";
	$vetor[19]= "Assistência domiciliar por nível médio";

	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_procedimentos_odontologicos($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "1ª consulta odontológica programática";
	$vetor[2]= "Aplicação de cariostático (por dente)";
	$vetor[3]= "Aplicação de selante (por dente)";
	$vetor[4]= "Evidenciação de placa bacteriana";
	$vetor[5]= "Capeamento pulpar";
	$vetor[6]= "Pulpotomia dentária";
	$vetor[7]= "Restauração dente decíduo";
	$vetor[8]= "Restauração dente permanente anterior";
	$vetor[9]= "Restauração dente permanente posterior";
	$vetor[10]= "Exodontia de dente decíduo";
	$vetor[11]= "Exodontia de dente permanente";
	$vetor[12]= "Tratamento alveolite";
	$vetor[13]= "Tratamento cirúrgico de hemorragia buco-dental";
	$vetor[14]= "Acesso a polpa dentária e medicação por dente";
	$vetor[15]= "Ulotomia/ulectomia";
	$vetor[16]= "Glossorrafia";
	$vetor[17]= "Rasp. alisamento e polimento supragengivais (por sextante)";
	$vetor[18]= "Aplicação tópica de fluor (individual por sessão)";
	$vetor[19]= "Emergência";
	$vetor[20]= "RX Odontológico";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_acompanhamento($tipo) {
	switch($tipo) {
		case 'c': $t= "Criança"; break;
		case 'a': $t= "Adolescente"; break;
		case 'g': $t= "Gestante"; break;
		case 'd': $t= "Adulto"; break;
		case 'i': $t= "Idoso"; break;
	}
	return($t);
}

function pega_en_crianca($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Peso muito baixo para idade";
	$vetor[2]= "Peso baixo para idade";
	$vetor[3]= "Risco nutricional";
	$vetor[4]= "Peso adequado (eutrofia)";
	$vetor[5]= "Risco de sobrepeso";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_en_adolescente($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Baixo peso";
	$vetor[2]= "Peso adequado (eutrofia)";
	$vetor[3]= "Sobrepeso";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_en_gestante_adulto($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Baixo peso";
	$vetor[2]= "Peso adequado";
	$vetor[3]= "Sobrepeso";
	$vetor[4]= "Obesidade";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function pega_tipo_exame($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Patologia clínica";
	$vetor[2]= "Radiodiagnóstico";
	$vetor[3]= "Citopatológico cérvico-vaginal";
	$vetor[4]= "Ultrassonografia obstétrica";
	$vetor[5]= "Outros";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}


function pega_en_idoso($tipo) {
	// l - listar, devolve todas, pois esta sendo chamado dentro de uma estrutura de repeticao
	// se nao for l devolve o registro, pois eh visualizar
	$vetor= array();
	
	$vetor[1]= "Baixo peso";
	$vetor[2]= "Peso adequado";
	$vetor[3]= "Sobrepeso";
	
	if ($tipo=='l') return($vetor);
	else return($vetor[$tipo]);
}

function data_extenso() {
	/*switch(date('D')) {
		case 'Sun': $data_extenso="Domingo"; break;
		case 'Mon': $data_extenso="Segunda-feira"; break;
		case 'Tue': $data_extenso="Terça-feira"; break;
		case 'Wed': $data_extenso="Quarta-feira"; break;
		case 'Thu': $data_extenso="Quinta-feira"; break;
		case 'Fri': $data_extenso="Sexta-feira"; break;
		case 'Sat': $data_extenso="Sábado"; break;
	}
	$data_extenso .= ", ";
	*/
	$data_extenso .= date('d');
	$data_extenso .= " de ";
	
	switch(date('n')) {
		case 1: $data_extenso .= "Janeiro"; break;
		case 2: $data_extenso .= "Fevereiro"; break;
		case 3: $data_extenso .= "Março"; break;
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

function gera_auth() {
	return(substr(strtoupper(md5(uniqid(rand(), true))), 0, 24));
}

function logs($id_acesso, $id_usuario, $id_cidade, $id_posto, $situacao, $acao, $ip, $ip_reverso) {
	$result= mysql_query("insert into logs (id_acesso, id_usuario, id_cidade, id_posto, acao, data, ip, ip_reverso, situacao)
							values
							('$id_acesso', '$id_usuario', '$id_cidade', '$id_posto', '$acao', '". date("YmdHis") ."', '$ip', '$ip_reverso', '$situacao')
							") or die(mysql_error());
}

?>