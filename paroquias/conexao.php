<?
session_start();
extract($_REQUEST,EXTR_SKIP);

if ($_SERVER['SERVER_NAME']=="localhost") {
	$conexao= @mysql_connect("localhost", "root", "") or die("O servidor está um pouco instável, favor tente novamente!");
	@mysql_select_db("lisbeta_paroquias") or die("O servidor está um pouco instável, favor tente novamente!!");
}
else {
	//$conexao= @mysql_connect("webdb.matrix.com.br", "lisbeta", "47li2345") or die("O servidor está um pouco instável, favor tente novamente mais tarde!");
	//@mysql_select_db("lisbeta") or die("O servidor está um pouco instável, favor tente novamente mais tarde!!");
	
	$conexao= mysql_connect("localhost", "mlisbetapar", "egejupy4y") or die("O servidor está um pouco instável, favor tente novamente mais tarde!");
	mysql_select_db("zadmin_lisbetaparoquias") or die("O servidor está um pouco instável, favor tente novamente mais tarde!!");
}

define("AJAX_LINK", "link.php?");
define("AJAX_FORM", "form.php?");
define("ID_SISTEMA", "2");

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

function pode($area, $permissao) {
	if (strpos($permissao, $area)) $retorno= true;
	else $retorno= false;
	
	if ($permissao=="www") $retorno= true;
	
	return($retorno);
}

function fnum($numero) {
	return(number_format($numero, 2, ',', '.'));
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

function pega_num_postos($id_cidade) {
	$result= mysql_query("select count(id_posto) as num_postos from postos
							where id_cidade = '$id_cidade'
							");
	$rs= mysql_fetch_object($result);
	return($rs->num_postos);
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
		case 13: $retorno= "13º"; break;
		
		default: $retorno= "System failure..."; break;
	}
	return($retorno);
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

function mostra_cpf_ou_responsavel($cpf, $id_responsavel) {
	
	if ($cpf=="") {
		if (($id_responsavel!="") && ($id_responsavel!="0")) {
			$rs_dependente= mysql_fetch_object(mysql_query("select nome, cpf from pessoas where id_pessoa = '$id_responsavel' "));
			$retorno .= " <span class=vermelho>dep.</span> ". $rs_dependente->nome ." (". formata_cpf($rs_dependente->cpf) .") ";
		}
		else
			echo " <span class='menor vermelho'>sem CPF</span>";
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

function primeira_palavra($frase) {
	$retorno= explode(" ", $frase);
	return($retorno[0]);
}

function pega_nomes($id_pessoas) {
	$id_pessoa= explode("@", $id_pessoas);
	
	for($i=0; $i<count($id_pessoa); $i++) {
		$pessoas.= pega_nome($id_pessoa[$i]) .", ";
	}
	return($pessoas);
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
		return("<span class='menor vermelho'>(sem CPF)</span>");
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


function desformata_data($var) {
	if (($var!="") && ($var!="0000-00-00")) {
		//2006-10-12
		$var= explode("-", $var, 3);
		
		//10/10/2007
		$var= $var[2] .'/'. $var[1] .'/'. $var[0];
		return($var);
	}
	else
		return("<span class='menor vermelho'>não informado</span>");
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

function sim_nao($var) {
	if ($var==0)
		$retorno= "<span class=vermelho>NÃO</span>";
	else
		$retorno= "<span class=verde>SIM</span>";
	return($retorno);
}

// ----------- SOCIAL -------------------------------------------------------
// ----------- SOCIAL -------------------------------------------------------
// ----------- SOCIAL -------------------------------------------------------

function pega_profissao($id_profissao) {
	$rs= mysql_fetch_object(mysql_query("select * from profissoes
											where id_profissao = '$id_profissao'
											"));
	return($rs->profissao);
}

function pega_estado_civil($i) {
	$vetor= array();
	
	$vetor[1]= "Amasiado(a)";
	$vetor[2]= "Casado só civil";
	$vetor[3]= "Casado civil e religioso";
	$vetor[4]= "Divorciado(a)";
	$vetor[5]= "Separado(a)";
	$vetor[6]= "Viúvo(a)";
	$vetor[7]= "Solteiro(a)";
	$vetor[8]= "Casado só religioso";
	
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
	$vetor[16]= "Outro";
	
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

function pega_religiao($i) {
	$vetor= array();
	
	$vetor[1]= "Adventista da Promessa";
	$vetor[2]= "Adventista do Sétimo Dia";
    $vetor[3]= "Batuque";
    $vetor[4]= "Bramanismo";
    $vetor[5]= "Budismo";
    $vetor[6]= "Catarismo";
    $vetor[7]= "Candomblé";
    $vetor[8]= "Cientologia";
    $vetor[9]= "Catolicismo";
    $vetor[10]= "Discordianismo";
    $vetor[11]= "Espiritismo";
    $vetor[12]= "Fé Bahá'í";
    $vetor[13]= "Hinduísmo";
    $vetor[14]= "Igrejas do Daime";
    $vetor[15]= "Islão";
    $vetor[16]= "Jainismo";
    $vetor[17]= "Janelismo";
    $vetor[18]= "Judaísmo";
    $vetor[19]= "Messiânica";
    $vetor[20]= "Movimento Hare Krishna";
    $vetor[21]= "Movimento Sannyasin de Osho® Rajneesh";
    $vetor[22]= "Quakers";
    $vetor[23]= "Religião de Deus (LBV)";
    $vetor[24]= "Religiões Nativas Americanas (Inclusive Mórmons)";
    $vetor[25]= "Santo Daime";
    $vetor[26]= "Satanismo";
    $vetor[27]= "Seicho-No-Ie";
    $vetor[28]= "Sikhismo";
    $vetor[29]= "Taoísmo";
    $vetor[30]= "Teosofia";
    $vetor[31]= "Testemunhas de Jeová";
    $vetor[32]= "Umbanda";
    $vetor[33]= "Wicca";
    $vetor[34]= "Xamanismo";
    $vetor[35]= "Xintoísmo";
    $vetor[36]= "Zoroastrismo";
	$vetor[37]= "Ateu";
	$vetor[38]= "Outra";
		
	if ($i=="l") return($vetor);
	else return($vetor[$i]);
}

function pega_chefe_familia($id_familia) {
	$rs= mysql_fetch_object(mysql_query("select pessoas.nome, pessoas.cpf from pessoas, familias_pessoas
											where familias_pessoas.id_pessoa = pessoas.id_pessoa
											and   familias_pessoas.id_familia = '$id_familia'
											and   familias_pessoas.tipo = '1'
											"));
	return($rs->nome ." ". formata_cpf($rs->cpf));
}

function pega_num_membros($id_familia) {
	$result= mysql_query("select id from familias_pessoas
											where id_familia = '$id_familia'
											");
	return(mysql_num_rows($result));
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