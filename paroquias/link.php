<?
if (!$conexao) require_once("conexao.php");

header("Content-type: text/html; charset=iso-8859-1", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
		<head>
		<title>Lisbeta</title>
		</head>
		<body>";

//echo "p ". $_SESSION["permissao"];

if ($_SESSION["id_cidade_sessao"]!="") $id_cidade_emula= $_SESSION["id_cidade_sessao"];
else $id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

if (isset($_GET["carregaPagina"])) {
	echo "<script language=\"javascript\">atribuiValor('pagina', '". $pagina ."')</script>";
	@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "abre a tela: ". $pagina, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	require_once("index3.php");
}

if (isset($_GET["carregaPaginaInterna"])) {
	/*echo "<script language=\"javascript\">atribuiValor('pagina', '". $pagina ."')</script>";*/
	@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "abre a tela interna: ". $pagina, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	require_once("index2.php");
}

if (isset($_GET["retornaCidades"])) {
	$retorno= "<select name=\"id_cidade\" id=\"id_cidade\">";
	$retorno.= "<option value=\"\" selected=\"selected\">--- selecione ---</option>";
	$result_cidades= mysql_query("select cidades.id_cidade, cidades.cidade from cidades, ufs
									where cidades.id_uf = ufs.id_uf
									and   ufs.id_uf = '". $_GET["id_uf"] ."' order by cidade");
	$i= 0;
	while($rs_cidades= mysql_fetch_object($result_cidades)) {
		if (($i%2)==0) $classe= "class=\"cor_sim\"";
		else $classe= "";
		$retorno.= "<option ". $classe ." value=\"". $rs_cidades->id_cidade ."\">". $rs_cidades->cidade ."</option>";
		$i++;
	}
	$retorno.= "</select>";
	echo $retorno;
}

if (isset($_GET["retornaPsfs"])) {
	$retorno= "<select name=\"id_psf\" id=\"id_psf\">";
	$retorno.= "<option value=\"\" selected=\"selected\">--- NÃO SEI</option>";
	$result_postos= mysql_query("select postos.id_posto, postos.posto from postos
									where postos.id_cidade = '". $_GET["id_cidade"] ."'
									and   postos.situacao = '1'
									and   postos.psf= '1'
									order by posto ");
	
	if (mysql_num_rows($result_postos)==0)
		echo "<span class=\"vermelho\">Nenhum PSF encontrado!</span>";
	else {
		$i= 0;
		while($rs_postos= mysql_fetch_object($result_postos)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			
			if ($_SESSION["id_posto_sessao"]==$rs_postos->id_posto)
				$selecionavel= " selected=\"selected\" ";
			else
				$selecionavel= "";
			
			$retorno.= "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
			$i++;
		}
		$retorno.= "</select>";
		echo $retorno;
	}
}

if (isset($_GET["retornaMicroareas"])) {
	$retorno= "<select name=\"id_microarea\" id=\"id_microarea\">";
	$retorno.= "<option value=\"\" selected=\"selected\">--- selecione</option>";
	$result_coo= mysql_query("select * from microareas_coord
									where id_posto = '". $_GET["id_posto"] ."'
									order by coordenacao asc ");
	
	if (mysql_num_rows($result_coo)==0)
		echo "<span class=\"vermelho\">Nenhuma quadra encontrada!</span>
				<input type=\"hidden\" name=\"id_microarea\" id=\"id_microarea\" class=\"escondido\" value=\"\" />
				";
	else {
		$i= 0;
		while($rs_coo= mysql_fetch_object($result_coo)) {
			
			$retorno.= "<optgroup label=\"". $rs_coo->coordenacao ." - ". pega_nomes($rs_coo->id_pessoas) ."\">";
			
			$result_ma= mysql_query("select * from microareas
									where id_coord = '". $rs_coo->id_coordenacao ."'
									order by microarea asc ");
			
			while($rs_ma= mysql_fetch_object($result_ma)) {
				if (($i%2)==0) $classe= "class=\"cor_sim\"";
				else $classe= "";
				
				$retorno.= "<option ". $classe ." value=\"". $rs_ma->id_microarea ."\">". $rs_ma->microarea ." - ". pega_nomes($rs_ma->id_pessoas) ."</option>";
				
				$i++;
			}
			
			$retorno.= "</optgroup>";
		}
		$retorno.= "</select>";
		echo $retorno;
	}
}

if ($_SESSION["tipo_usuario_sessao"]=='a') {

	/* ----------------------------------------------- ACESSO --------------------------------------- */
	
	if (isset($_GET["retornaPostos"])) {
		$retorno= "<select name=\"id_posto_em\" id=\"id_posto_em\">";
		$retorno.= "<option value=\"\" selected=\"selected\">--- NENHUM ---</option>";
		$result_postos= mysql_query("select postos.id_posto, postos.posto from postos, cidades
										where cidades.id_cidade = postos.id_cidade
										and   cidades.id_cidade = '". $_GET["id_cidade"] ."'
										and   postos.situacao = '1'
										order by cidade");
		$i= 0;
		while($rs_postos= mysql_fetch_object($result_postos)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			
			if ($_SESSION["id_posto_sessao"]==$rs_postos->id_posto)
				$selecionavel= " selected=\"selected\" ";
			else
				$selecionavel= "";
			
			$retorno.= "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
			$i++;
		}
		$retorno.= "</select>";
		echo $retorno;
	}
	
	if (isset($_GET["retornaComboPostos"])) {
		$retorno= "<label>Destino:</label> <select name=\"id_posto_d\" id=\"id_posto_d\">";
		$retorno.= "<option value=\"\" selected=\"selected\">--- selecione ---</option>";
		$result_postos= mysql_query("select postos.id_posto, postos.posto from postos, cidades
										where cidades.id_cidade = postos.id_cidade
										and   cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										and   postos.situacao = '1'
										order by cidade");
		$i++;
		while($rs_postos= mysql_fetch_object($result_postos)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			$retorno.= "<option ". $classe ." value=\"". $rs_postos->id_posto ."\">". $rs_postos->posto ."</option>";
			$i++;
		}
		$retorno.= "</select><br />";
		echo $retorno;
	}
	
	if (isset($_GET["cidadeExcluir"])) {
		if ($_GET["id_cidade"]!="")
			$result= mysql_query("update cidades set sistema = '0' where id_cidade = '". $_GET["id_cidade"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_acesso/cidade_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["postoSituacao"])) {
		if ( ($_GET["id_posto"]!="") && ($_GET["situacao"]!="") ) {
			if ($_GET["situacao"]==1)
				$situ= 0;
			else
				$situ= 1;
			
			$result= mysql_query("update postos set situacao = '". $situ ."' where id_posto = '". $_GET["id_posto"] ."' ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$id_cidade= pega_id_cidade_do_posto($id_posto);
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}

	if (isset($_GET["verificaUsuario"])) {
		if ($id_usuario==0)
			$result= mysql_query("select usuario from usuarios where usuario= '$usuario' ") or die(mysql_error());
		else
			$result= mysql_query("select usuario from usuarios where usuario= '$usuario' and id_usuario <> '$id_usuario' ") or die(mysql_error());
		
		if (mysql_num_rows($result)==0) {
			$permissao_acesso= 1;
			echo "<span class=\"verde\">Nome de usuário disponível!</span>";
		}
		else {
			$permissao_acesso= 0;
			echo "<span class=\"vermelho\">Nome de usuário não disponível!</span>";
		}
		echo "<input type=\"hidden\" name=\"permissao_acesso\" id=\"permissao_acesso\" value=\"". $permissao_acesso ."\" class=\"escondido\" />";
	}

	if (isset($_GET["usuarioExcluir"])) {
		if ($_GET["id_usuario"]!="") {
			if ($_GET["situacao"]==1)
				$situ= 0;
			else
				$situ= 1;
			
			$result= mysql_query("update usuarios set situacao = '$situ' where id_usuario = '". $_GET["id_usuario"] ."' ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_acesso/usuario_listar";
		require_once("index3.php");
	}

	if (isset($_GET["usuarioDoPostoExcluir"])) {
		if ( ($_GET["id_usuario"]!="") && ($_GET["id_posto"]!="") )
			$result= mysql_query("delete from usuarios_postos
									where id_usuario = '". $_GET["id_usuario"] ."'
									and   id_posto = '". $_GET["id_posto"] ."'
									");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["microareaExcluir"])) {
		if ($_GET["id_microarea"]!="")
			$result= mysql_query("delete from microareas
									where id_microarea = '". $_GET["id_microarea"] ."'
									and   id_posto = '". $_GET["id_posto"] ."'
									");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["usuarioDaCidadeExcluir"])) {
		if ( ($_GET["id_usuario"]!="") && ($_GET["id_cidade"]!="") )
			$result= mysql_query("delete from usuarios_cidades
									where id_usuario = '". $_GET["id_usuario"] ."'
									and   id_cidade = '". $_GET["id_cidade"] ."'
									");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$id_cidade= $_GET["id_cidade"];
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}

}

if (pode("!", $_SESSION["permissao"])) {

	
}//fim remedios

if (pode("@", $_SESSION["permissao"])) {


}//fim exames

/* ----------------------------------------------- PESSOAS --------------------------------------- */

if (isset($_GET["pessoaInserirMostra"])) {
	$pagina= "_pessoas/pessoa_inserir";
	require_once("index2.php");
}

if (isset($_GET["buscaNomeParecido"])) {
	
	if ($_SESSION["id_cidade_sessao"]!="")
		$id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else
		$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

	$result_postos= mysql_query("select id_posto from postos where id_cidade = '$id_cidade_emula' ");
	
	if (mysql_num_rows($result_postos)>0) {
		$i= 0;
		$str2= "and ( ";
		
		while ($rs_postos= mysql_fetch_object($result_postos)) {
			
			if ($i==0)
				$str2 .= " pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
			else
				$str2 .= " or pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
				
			$i++;
		}
		$str2 .= ")";
	}
	
	$result= mysql_query("select nome, cpf, id_responsavel, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc
							from pessoas where nome like '%". $_GET["nome"] ."%'
							and   ((id_cidade = '". $id_cidade_emula ."')
									or
									((pessoas.origem_cadastro = 'c' and pessoas.id_origem_cadastro = '". $id_cidade_emula ."')
											or
											(
											pessoas.origem_cadastro= 'p'
											
												". $str2 ."
	
											)))
											order by nome asc
							");
	if (mysql_num_rows($result)>0) {
		$resultado= "<table width='100%' cellpadding='3' cellspacing='0'>
						<tr>
							<th align='left'>Nome</th>
							<th align='left'>CPF</th>
							<th>Data nasc.</th>
						</tr>";
		while ($rs= mysql_fetch_object($result)) {
			$resultado .= "  <tr>
								<td>". $rs->nome ."</td>
								<td>". mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel) ."</td>
								<td align='center'>". $rs->data_nasc ."</td>
							</tr>";

		}
		$resultado .= "</table>";
		
		echo "<script language='javascript'>abreDivSo('alerta_centro');</script>";
		echo $resultado;
	}
	else
		echo "<script language='javascript'>fechaDiv('alerta_centro');</script>";
}
                

if (isset($_GET["pessoaPesquisar"])) {
	
	if ($_SESSION["id_cidade_sessao"]!="") $id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else $id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

	$result_postos= mysql_query("select id_posto from postos where id_cidade = '$id_cidade_emula' ");
	
	if (mysql_num_rows($result_postos)>0) {
		$i= 0;
		$str2= "and ( ";
		
		while ($rs_postos= mysql_fetch_object($result_postos)) {
			
			if ($i==0) $str2 .= " pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
			else $str2 .= " or pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
			
			$i++;
		}
		$str2 .= ")";
	}
	
	//adm sem nenhuma cidade escolhida
	if (($_SESSION["tipo_usuario_sessao"]=="a") && ($id_cidade_emula==""))
		$sql= "select distinct(pessoas.cpf), pessoas.nome, pessoas.id_pessoa, pessoas.id_responsavel, DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc from pessoas
						where pessoas.nome like '". $_GET["nomeb"] ."%'
						and   pessoas.id_responsavel = '0'
						order by pessoas.nome asc
						";	
	else
		$sql= "select distinct(pessoas.cpf), pessoas.nome, pessoas.id_pessoa, pessoas.id_responsavel, DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc from pessoas". $sql_linha1 ."
						where pessoas.nome like '". $_GET["nomeb"] ."%'
						". $sql_linha2 ."
								and   pessoas.id_responsavel = '0'
								and   ((pessoas.id_cidade = '". $id_cidade_emula ."')
								or
								((pessoas.origem_cadastro = 'c' and pessoas.id_origem_cadastro = '". $id_cidade_emula ."')
										or
										(
										pessoas.origem_cadastro= 'p'
											". $str2 ."
										)))
								order by pessoas.nome asc
						";
	
	//echo $sql;
	
	$result= mysql_query($sql) or die(mysql_error());	
	
	if ($_GET["campo_retorno"]!="")
		$linha2= "preencheDiv('pessoa_buscar_resultado', '');";
	
	//if ($_GET["tipo_consulta"]=="p")
	//	$linha2 .= "usuarioRetornaCpfTfd('".$_GET["campo_retorno"]."');";
	if ($_GET["tipo_consulta"]=="ac") {
		$amais= "_ac";
	//	$linha2 .= "usuarioRetornaCpfAcompanhanteTfd('".$_GET["campo_retorno"]."');";
	}
	
	if (mysql_num_rows($result)==0)
		echo "<li>Nenhum registro encontrado!</li>";
	else {
		echo "<ul class=\"recuo2\">";
		while ($rs= mysql_fetch_object($result)) {
			//se não tem cpf cadastrado (cadastro sem cpf mesmo)
			if ($rs->cpf=="") {
				//$rotina_volta_cpf= "alert('Esta pessoa está cadastrada sem CPF!\\n\\nPeça para trazer o CPF nas próxima visitas!'); abreFechaDiv('pessoa_buscar');";

				if ($_GET["tipo_volta"]==2)
					$rotina_volta_cpf .= "pegaProntuarioSemCpf(". $rs->id_pessoa .");";
				elseif ($_GET["tipo_volta"]==3)
					$rotina_volta_cpf .= "pegaPeriodicoSemCpf(". $rs->id_pessoa .");";
				elseif ($_GET["tipo_volta"]==4)
					$rotina_volta_cpf .= "atribuiValor('cpf_usuario', ''); pegaAcompSemCpf(". $rs->id_pessoa ."); ajaxLink('cpf_usuario_atualiza', 'usuarioRetornaCpfCompleto&id_pessoa=". $rs->id_pessoa ."&local=acomp');";
				else
					$rotina_volta_cpf .= "preencheDiv('cpf_usuario_atualiza". $amais . $_GET["campo_retorno"] ."', '<input id=id_pessoa_dep class=escondido type=hidden value=". $rs->id_pessoa ." name=id_pessoa_dep /><input id=id_pessoa_mesmo class=escondido type=hidden value=". $rs->id_pessoa ." name=id_pessoa />". $rs->nome ."<br /><label>&nbsp;</label><a onclick=cadastraDependente(2); href=javascript:void(0);>cadastrar dependente</a> | <a onclick=editaDadosPessoais(2); href=javascript:void(0);>editar dados</a><br/>');
									abreFechaDiv('pessoa_buscar'); habilitaCampo('botaoInserir'); abreFechaDiv('pessoa_buscar');
									";
			}
			else
				$rotina_volta_cpf= "
					atribuiValor('cpf_usuario". $amais . $_GET["campo_retorno"] ."', '". $rs->cpf ."');
					". $linha2 ."
					abreFechaDiv('pessoa_buscar');
					daFoco('cpf_usuario". $amais . $_GET["campo_retorno"] ."');
					daBlur('cpf_usuario". $amais . $_GET["campo_retorno"] ."');
					";
			
			$var= "<li><a href=\"javascript:void(0);\" onclick=\"". $rotina_volta_cpf ."\" onmouseover=\"Tip('<strong>Nascimento:</strong> ". $rs->data_nasc ." ');\">". $rs->nome ."</a></li>";
																	
			echo $var;
		}
		echo "</ul>";
		@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "pesquisa pessoa na telinha, termo: ". $_GET["nomeb"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	}
}

/* ----------------------------------------------- USUARIOS --------------------------------------- */

if (isset($_GET["usuarioRetornaCpfCompleto"])) {
	if ($_GET["id_pessoa"]!="")
		$result= mysql_query("select id_pessoa, nome, situacao_pessoa, sexo, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc
								from pessoas where id_pessoa= '". $_GET["id_pessoa"] ."'") or die(mysql_error());	
	else
		$result= mysql_query("select id_pessoa, nome, situacao_pessoa, sexo, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc
								from pessoas where cpf= '". $_GET["cpf"] ."'") or die(mysql_error());
	
	if (mysql_num_rows($result)==0) {
		echo "<span class=\"vermelho\">CPF não cadastrado!</span>
				<a href=\"javascript:void(0);\" onclick=\"abreCadastro(2);\">Cadastrar</a>";
	}
	else {
		$rs= mysql_fetch_object($result);
		$idade= calcula_idade($rs->data_nasc);
		if (!is_int($idade) || ($idade<0))
			echo "<script language='javascript'>alert('Clique em \'editar dados\' e corrija a data de nascimento! Consta como \'". $rs->data_nasc ."\' !');</script>";
		
		//viva
		if ($rs->situacao_pessoa!=2) {
			//farmacia
			if ($_GET["local"]=="f")
				$linha2= "onchange=\"atualizaHistorico('2');\";";
			
			//trocar as solicitacoes se for dependente
			if ($_GET["local"]=="t2")
				$linha2= "onchange=\"atualizaSolicitacoesTfd('". $_GET["campon"] ."', this.value);\";";
			
			if ($_GET["campon"]!="")
				$linha3= "[]";
			else
				$linha3= "";
			
			if ($_GET["acompanhante"]==1) {
				$id_campo= "id_pessoa_qqer";
				$nome_campo= "pessoa_qqer";
			}
			else {
				$id_campo= "id_pessoa_mesmo";
				$nome_campo= "id_pessoa";
			}
			
			//se tem dependetes
			if (pega_num_dependentes($rs->id_pessoa)>0) {
				$result_dependentes= mysql_query("select id_pessoa, nome, cpf from pessoas where id_responsavel = '$rs->id_pessoa' order by nome asc ");		
				
				if ($local=="acomp")
					$linha_acomp= "onchange=\"alteraDadosAcompanhamento(this.value);\"";
				
				echo "
				<input type=\"hidden\" name=\"id_pessoa_dep\" id=\"id_pessoa_dep". $_GET["campon"] ."\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />
				<select name=\"". $nome_campo . $linha3 ."\" id=\"". $id_campo . $_GET["campon"] ."\" class=\"tamanhoAuto\" ". $linha2 ." ". $linha_acomp ." >";
				
				echo "<option value=\"". $rs->id_pessoa ."\">". $rs->nome ."</option>";
				
				$i= 0;
				while ($rs_dependentes= mysql_fetch_object($result_dependentes)) {
					if ($_GET["local"]=="t2") {
						$result_dep_per= mysql_query("select * from tfds_solicitacoes
														where id_pessoa= '". $rs_dependentes->id_pessoa ."'
														and   (situacao_solicitacao= '2'
														or situacao_solicitacao= '4')
														");
						if (mysql_num_rows($result_dep_per)==0)
							$linha_inter= "disabled=\"disabled\"";
					}
					else
						$linha_inter= "";
					
					if (($i%2)==0) $cla= "class=\"cor_sim\""; else $cla= "";
					
					echo "<option ". $cla ." value=\"". $rs_dependentes->id_pessoa ."\" ". $linha_inter .">". $rs_dependentes->nome ."</option>";
					$i++;
				}
				echo "</select>";
			}
			//se nao tem dependentes
			else
				echo "
				". $rs->id_pessoa .".
				<input type=\"hidden\" name=\"id_pessoa_dep\" id=\"id_pessoa_dep". $_GET["campon"] ."\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />
				<input type=\"hidden\" name=\"". $nome_campo . $linha3 ."\" id=\"". $id_campo . $_GET["campon"] ."\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />
						". $rs->nome;
			
			if (($_GET["local"]!="t2") && ($_GET["local"]!="ac"))
				echo "<br />
						<label>&nbsp;</label>
						<a href=\"javascript:void(0);\" onclick=\"cadastraDependente(2);\" onmouseover=\"Tip('Clique para cadastrar um dependente para esta pessoa.<br/>Geralmente são filhos ou outros membros da família que não possuem CPF.');\">cadastrar dependente</a> |
						<a href=\"javascript:void(0);\" onclick=\"editaDadosPessoais(2);\" onmouseover=\"Tip('Clique para editar os dados pessoais.');\">editar dados</a> <br />";
			
			
			/* -------------------------------------- acompanhamento --------------------------------------------- */
		
			if ($_GET["local"]=="acomp") {
				if ($idade<=6) $tipo_acompanhamento= "c";
				else {
					if (($idade>=7) && ($idade<20)) $tipo_acompanhamento= "a";
					else {
						if (($idade>=20) && ($idade<60)) $tipo_acompanhamento= "d";
						else $tipo_acompanhamento= "i";
					}
				}
				$meses= calcula_meses($rs->data_nasc);
				
				echo "<div id=\"acompanhamento_dados\"><input type=\"hidden\" class=\"escondido\" id=\"idade_paciente\" name=\"idade_paciente\" value=\"". ($idade+0) ."\" />";
				echo "<input type=\"hidden\" class=\"escondido\" id=\"tipo_acompanhamento\" name=\"tipo_acompanhamento\" value=\"". $tipo_acompanhamento ."\" />";
				echo "<input type=\"hidden\" class=\"escondido\" id=\"sexo\" name=\"sexo\" value=\"". $rs->sexo ."\" />";
				echo "<input type=\"hidden\" class=\"escondido\" id=\"meses_paciente\" name=\"meses_paciente\" value=\"". ($meses+0) ."\" />";
				
				echo "<label>Data nasc.:</label> ". $rs->data_nasc ." <br />";
				echo "<label>Idade:</label> ". $idade ." anos";
				
				if ($idade<7) echo " (". $meses ." meses)";
				echo "<br />";
				
				echo "<label>Sexo:</label> ". pega_sexo($rs->sexo) ." <br />";
				
				echo "<script language=\"javascript\">ajaxLink('acompanhamento_ac', 'carregaPaginaInterna&pagina=_acomp/acomp&sexo=". $rs->sexo ."&tipo_acompanhamento=". $tipo_acompanhamento ."');</script></div>";
			}
			
			/* ------------------------------------- /acompanhamento --------------------------------------------- */
			
			//se for na farmacia, mostra historico de medicamentos
			if ($_GET["local"]=="f") {
				echo "
				<div id=\"almox_direita\">
					<fieldset>
						<legend>Histórico resumido de remédios</legend>
						<div id=\"almox_direita2\">";
							
							$id_pessoa_hist= $rs->id_pessoa;
							$tipo_hist= "v";
							
							$pagina= "_pessoas/historico_meds_resumo";
							include("index2.php");
				
						echo "
						</div>
						<br /><br />
					</fieldset>
				</div>
				";
			}
			if ($_GET["local"]=="a") {
				echo "
				<div id=\"almox_direita\">
					<fieldset>
						<legend>Histórico resumido de materiais</legend>
						<div id=\"almox_direita2\">";
							
							$id_pessoa_hist= $rs->id_pessoa;
							$tipo_hist= "v";
							
							$pagina= "_pessoas/historico_mats_resumo";
							include("index2.php");
				
						echo "
						</div>
						<br /><br />
					</fieldset>
				</div>
				";
			}
			//else
				//echo "<input type=\"hidden\" name=\"xid_pessoa\" id=\"id_pessoa_mesmo\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />";
			//se for tela de TFD
			if ($_GET["local"]=="t2") {
				echo "<div id=\"almox_direita\">";
				
				$result_sol= mysql_query("select *, DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%d/%m/%Y') as data_solicitacao,
											DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%Y') as ano
											from tfds_solicitacoes
											where id_pessoa = '". $rs->id_pessoa ."'
											and   (situacao_solicitacao= '2'
													or situacao_solicitacao= '4')
											");
				
				if (mysql_num_rows($result_sol)>0) {
					echo "
						<label>Solicitação:</label>
						<div id=\"solicitacao_atualiza". $_GET["campon"] ."\">
							<select name=\"id_solicitacao[]\" onchange=\"mostraSolicitacao(this);\">
								<option value=\"\">--- selecione ---</option>";
								$i=0;
								while ($rs_sol= mysql_fetch_object($result_sol)) {
									if (($i%2)==0) $classe= "cor_sim";
									else $classe= "cor_nao";
									echo "<option class=\"". $classe ."\" value=\"". $rs_sol->id_solicitacao ."\">". $rs_sol->id_interno ."/". $rs_sol->ano ." (". $rs_sol->data_solicitacao .")</option>";
								}
						echo "</select>
						</div>
						<br />
						<div id=\"solicitacao_detalhes\">
							<span class=\"vermelho\">Seleciona a solicitação no campo acima!</span>
						</div>
					</div>
					";
				}
				else {
					//se for paciente mostra isso.. (nao mostra nada qdo é carona).
					if ($_GET["tipo"]=="p")
					echo "<input name=\"id_solicitacao[]\ type=\"hidden\" class=\"escondido\" value=\"\" />
						<span class=\"vermelho\">Nenhuma solicitação aceita pendente encontrada!<br /><br /><strong>Esta pessoa não será inserida nesta TFD!</strong></span>";
				}
			}
			if ($_GET["local"]=="ac") {
				echo "<br />";
				echo "<label>&nbsp;</label><button type=\"button\" onclick=\"atualizaValorAcompanhante('". $_GET["campon"] ."', '". $rs->id_pessoa ."');\">adicionar &gt;&gt;</button>";
				//ajaxLink('acompanhantes_atualiza". $_GET["campon"] ."', 'adicionaAcompanhante&id_pessoa=". $rs->id_pessoa ."');
			}
			
			echo "<script language='javascript' type='text/javascript'>void(0); habilitaCampo('botaoInserir');</script>";
		}
		else
			echo "". $rs->nome ." <img src=\"images/cruz.png\" alt=\"+\" />";
	}
}

if (isset($_GET["usuarioRetornaCpf"])) {
	$result= mysql_query("select id_pessoa, nome from pessoas where cpf= '". $_GET["cpf"] ."'") or die(mysql_error());
	
	if (mysql_num_rows($result)==0) {
		echo "<span class=\"vermelho\">CPF não cadastrado.</span>
				<a href=\"javascript:abreDivSo('tela_cadastro');
									atribuiValor('cpf_cadastro', '". $cpf ."');
									\">Cadastrar</a>";
	}
	else {
		$rs= mysql_fetch_object($result);
		
		if ($_GET["tfd"]==1)
			$auxzin= "[]";
		if ($_GET["acompanhante"]==1)
			$auxzin2= $_GET["num"];
		
		echo "<input type=\"hidden\" name=\"id_pessoa". $auxzin ."\" id=\"id_pessoa_form". $auxzin2 ."\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />
				". $rs->nome;
		
		if ($_GET["acompanhante"]==1)
			echo "<button type=\"button\" onclick=\"\">adicionar</button>";
	}
}

if (isset($_GET["usuarioRetornaCpfDisponibilidade"])) {
	$result= mysql_query("select id_pessoa, nome from pessoas where cpf= '". $_GET["cpf"] ."'") or die(mysql_error());
	
	if (mysql_num_rows($result)==0)
		echo "<span class=\"verde\">CPF disponível!</span>
				<input type=\"hidden\" class=\"escondido\" name=\"cpf_disponivel\" id=\"cpf_disponivel\" value=\"1\" />
				<script language=\"javascript\">habilitaCampo('enviar');</script>
				";
	else
		echo "<span class=\"vermelho\">CPF já cadastrado!</span>
				<input type=\"hidden\" class=\"escondido\" name=\"cpf_disponivel\" id=\"cpf_disponivel\" value=\"0\" />
				<script language=\"javascript\">desabilitaCampo('enviar');</script>
				";
}

if (isset($_GET["pegaResponsavel"])) {
	echo "<label>Responsável:</label>". pega_nome_pelo_cpf($_GET["cpf"]) ." (". formata_cpf($_GET["cpf"]) .") <br />";
}

/* --------------------------------------- SOCIAL ---------------------------------------------------------------- */

if (pode_algum("zl", $_SESSION["permissao"])) {
	
	if (isset($_GET["profissaoInserir"])) {
		if ($_GET["profissao"]!="") {
			$result_antes= mysql_query("select profissao from profissoes
											where profissao = '". $_GET["profissao"] ."' ");
	
			if (mysql_num_rows($result_antes)==0) {
				$result= mysql_query("insert into profissoes (profissao, id_usuario)
								values ('". ucfirst($_GET["profissao"]) ."', '". $_SESSION["id_usuario_sessao"] ."') ");
			}
		}
	
		echo "<script language='javascript' type='text/javascript'>;";
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere remédio, ID ". mysql_insert_id() ." | ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			//echo "alert('Profissão cadastrado com sucesso!');";
			echo "atribuiValor('profissao', '');";
			echo "abreFechaDiv('profissao_cadastro');";
			echo "ajaxLink('profissao_atualiza', 'carregaPaginaInterna&pagina=_pessoas/profissao');";
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir remédio, ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Profissão já cadastrada!');";
		}
		
		echo "</script>";
	}
	
	if (isset($_GET["familiaExcluir"])) {
		
		$var= 0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from familias
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									limit 1
									") or die(mysql_error());
		
		if (mysql_num_rows($result_pre)==1) {
			
			$result1= mysql_query("update familias
								  	set status_familia = '0'
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									limit 1
									") or die(mysql_error());
			if (!$result1) $var++;
			
			/*$result1= mysql_query("delete from familias
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									limit 1
									") or die(mysql_error());
			if (!$result1) $var++;

			$result5= mysql_query("delete from familias_pessoas
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result5) $var++;
			
			$result5= mysql_query("delete from arrecadacoes
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									") or die(mysql_error());
			if (!$result5) $var++;
			*/
		} else $var++;
		
		finaliza_transacao($var);
		
		if ($var==0) @logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui família ID ". $_GET["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		else @logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir família ID ". $_GET["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		
		$msg= $var;
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["pegaMembrosFamilia"])) {
		if ($_SESSION["id_posto_sessao"]!="")
			$str_condicao= "and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
		else
			$str_condicao= "and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
		
		$retorno= "<table cellspacing=\"0\" cellpadding=\"2\">
					<tr>
					<th width=\"50%\" align=\"left\">Nome</th>
                    <th width=\"30%\">Parentesco</th>
					<th width=\"20%\" align=\"left\">Ações</th>
                    </tr>";
		
		$result= mysql_query("select familias_pessoas.* from familias_pessoas, familias, microareas, postos, microareas_coord
								where familias.id_familia = '". $_GET["id_familia"] ."'
								and   familias.id_familia = familias_pessoas.id_familia
								and   familias.id_microarea = microareas.id_microarea
								and   microareas.id_coord = microareas_coord.id_coordenacao
								and   microareas_coord.id_posto = postos.id_posto
								$str_condicao
								") or die(mysql_error());
    	
		while ($rs= mysql_fetch_object($result)) {
		
			if ($rs->tipo==1)
				$str_excluir= "<span class=\"vermelho\">N/A</span>";
			else
				$str_excluir= "<a onclick=\"return confirm('Tem certeza que deseja excluir esta pessoa da família?');\" href=\"javascript:ajaxLink('formacao_familia', 'membroExcluirFamilia&amp;id_familia=". $_GET["id_familia"] ."&amp;id_pessoa=". $rs->id_pessoa ."');\" class=\"link_excluir\" title=\"Excluir\">excluir</a>";
		
			$retorno .= "<tr>
							<td>". pega_nome($rs->id_pessoa) ."</td>
							<td align=\"center\">". pega_parentesco($rs->parentesco) ."</td>
							<td>
								<a href=\"javascript:void(0);\" onclick=\"ajaxLink('tela_cadastro', 'carregaPaginaInterna&amp;pagina=_pessoas/pessoa_editar&amp;id_pessoa=". $rs->id_pessoa ."&amp;retorno=conteudo'); abreDivSo('tela_cadastro');\" class=\"link_editar\" title=\"Editar\">editar</a>

								$str_excluir
							</td>
						</tr>
						";
		}
		
		$retorno .= "</table>";
		
		echo $retorno;
	}
	
	if (isset($_GET["cadastraMembroFamilia"])) {
		$result_pre= mysql_query("select * from familias_pessoas 
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_pessoa = '". $_GET["id_pessoa"] ."'
									") or die(mysql_error());
		
		if (mysql_num_rows($result_pre)==0) {
			$result= mysql_query("insert into familias_pessoas (id_familia, id_pessoa, parentesco)
									values
									('". $_GET["id_familia"] ."', '". $_GET["id_pessoa"] ."', '". $_GET["parentesco"] ."')
									") or die(mysql_error());
									
		}
		
		echo "<script language=\"javascript\" type=\"text/javascript\">
				ajaxLink('formacao_familia', 'pegaMembrosFamilia&id_familia=". $_GET["id_familia"] ."');";
		
		if (mysql_num_rows($result_pre)>0)
			echo "alert('Esta pessoa já está nesta família!');";
		
		echo "</script>";
	}

	if (isset($_GET["membroExcluirFamilia"])) {
		$result= mysql_query("delete from familias_pessoas
								where id_familia = '". $_GET["id_familia"] ."'
								and   id_pessoa = '". $_GET["id_pessoa"] ."'
								") or die(mysql_error());
								
		echo "<script language=\"javascript\" type=\"text/javascript\">
				ajaxLink('formacao_familia', 'pegaMembrosFamilia&id_familia=". $_GET["id_familia"] ."');";
		
		if ($result)
			echo "alert('Pessoa excluída com sucesso!');";
		else
			echo "alert('Não foi possível excluir, tente novamente!');";
		
		echo "</script>";
	}
	
}


/* ---------------------------------------------------------------------------------------------------- */

echo "</body></html>";
?>