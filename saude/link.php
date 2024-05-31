<?
if (!$conexao)
	require_once("conexao.php");

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
	$result_ma= mysql_query("select microareas.* from microareas
									where microareas.id_posto = '". $_GET["id_psf"] ."'
									order by microarea asc ");
	
	if (mysql_num_rows($result_ma)==0)
		echo "<span class=\"vermelho\">Nenhuma microárea encontrada!</span>
				<input type=\"hidden\" name=\"id_microarea\" id=\"id_microarea\" class=\"escondido\" value=\"\" />
				";
	else {
		$i= 0;
		while($rs_ma= mysql_fetch_object($result_ma)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			
			$retorno.= "<option ". $classe ." value=\"". $rs_ma->id_microarea ."\">". $rs_ma->microarea ." - ". pega_nome($rs_ma->id_pessoa) ."</option>";
			$i++;
		}
		$retorno.= "</select>";
		echo $retorno;
	}
}

if (isset($_GET["retornaCBOs"])) {
	$retorno= "<select name=\"id_cbo\" id=\"id_cbo\">";
	$retorno.= "<option value=\"\" selected=\"selected\">--- selecione ---</option>";
	$result_ocu= mysql_query("select * from ocupacoes
									where id_ofamilia = '". $_GET["id_ofamilia"] ."'
									order by ocupacao ");
	$i= 0;
	while($rs_ocu= mysql_fetch_object($result_ocu)) {
		if (($i%2)==0)
			$classe= "class=\"cor_sim\"";
		else
			$classe= "";
		$retorno.= "<option ". $classe ." value=\"". $rs_ocu->id_cbo ."\">". $rs_ocu->id_ofamilia ."-". $rs_ocu->id_ocupacao .". ". $rs_ocu->ocupacao ."</option>";
		$i++;
	}
	$retorno.= "</select>";
	echo $retorno;
}


/* ----------------------------------------------- TFD --------------------------------------- */

if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("t", $_SESSION["permissao"])) ) {

	if (isset($_GET["mostraSolicitacao"])) {
		$result= mysql_query("select tfds_solicitacoes.*, pessoas.nome, pessoas.cpf, tfds_entidades.entidade, tfds_entidades.id_cidade,
								DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%d/%m/%Y') as data_solicitacao, tfds_finalidades.*,
								DATE_FORMAT(tfds_solicitacoes.data_operacao, '%d/%m/%Y %H:%i:%s') as data_operacao
								from tfds_solicitacoes, tfds_entidades, tfds_finalidades, pessoas
								where tfds_solicitacoes.id_solicitacao = '". $_GET["id_solicitacao"] ."'
								and   tfds_solicitacoes.id_finalidade = tfds_finalidades.id_finalidade
								and   tfds_solicitacoes.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   tfds_solicitacoes.id_pessoa = pessoas.id_pessoa
								and   tfds_solicitacoes.id_entidade = tfds_entidades.id_entidade
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		/*if ($rs->id_interno!="")
	        echo "<label>Cód.interno:</label>". $rs->id_interno ."<br />";
        else
	        echo "<label>Cód.:</label>". $rs->id_solicitacao ."<br />";*/
		
		echo "  <label>Destino:</label>
				". $rs->entidade ." - ". pega_cidade($rs->id_cidade) ."
				<br />
			";
		echo "  <label>Finalidade:</label>
				". pega_tipo_ida($rs->tipo_ida) ." (". $rs->finalidade .")
				<br />
			";
	}

	if (isset($_GET["retornaIdSolicitacaoTfd"])) {
		$result= mysql_query("select id_interno from tfds_solicitacoes
										where id_cidade_tfd = '". $_GET["id_cidade"] ."'
										and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										and   DATE_FORMAT(data_solicitacao, '%Y') = '". date("Y") ."'
										order by id_solicitacao desc limit 1 ");
		$rs= mysql_fetch_object($result);
		$id_interno= $rs->id_interno+1;
		
		echo "<input name=\"id_interno\" id=\"id_interno\" value=\"". $id_interno ."\" /> / ". date("Y");
	}

	
	if (isset($_GET["retornaEntidades"])) {
		$retorno= "<select name=\"id_entidade\" id=\"id_entidade\" class=\"espaco_dir\">";
		$retorno.= "<option value=\"\" selected=\"selected\">--- selecione ---</option>";
		
		$result_ent= mysql_query("select * from tfds_entidades
										where id_cidade = '". $_GET["id_cidade"] ."'
										order by entidade");
		$i= 0;
		while($rs_ent= mysql_fetch_object($result_ent)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			$retorno.= "<option ". $classe ." value=\"". $rs_ent->id_entidade ."\">". $rs_ent->entidade ."</option>";
			$i++;
		}
		$retorno.= "</select>";
		echo $retorno;
	}
	
	if (isset($_GET["retornaFinalidades"])) {
		$retorno= "<select name=\"id_finalidade\" id=\"id_finalidade\" class=\"espaco_dir\">";
		$retorno.= "<option value=\"\" selected=\"selected\">--- selecione ---</option>";
		
		$result_fin= mysql_query("select * from tfds_finalidades
										where tipo_ida = '". $_GET["tipo_ida"] ."'
										order by finalidade");
		$i= 0;
		while($rs_fin= mysql_fetch_object($result_fin)) {
			if (($i%2)==0)
				$classe= "class=\"cor_sim\"";
			else
				$classe= "";
			$retorno.= "<option ". $classe ." value=\"". $rs_fin->id_finalidade ."\">". $rs_fin->finalidade ."</option>";
			$i++;
		}
		$retorno.= "</select>";
		echo $retorno;
	}
	
	if (isset($_GET["fazLeituraAcompanhantes"])) {
		$pessoas= explode("@", $_GET["valor"]);

		$num_pessoas= count($pessoas);
		//echo "num_pessoas: ". $num_pessoas;
		
		if ($num_pessoas>0) echo "<ul class=\"recuo1\">";
		
		for ($i=0; $i<$num_pessoas; $i++) {
			$ps= str_replace("@", "", $pessoas[$i]);
			//echo "antes: ". $pessoas[$i] ." | depois:". $ps . "<br />";
			if ($ps!="")
				echo "<li>". pega_nome($ps) ." <a href=\"javascript:void(0);\" onclick=\"removeValorAcompanhante('". $_GET["id_campo"] ."', '". $ps ."');\">exclui</a></li>";
		}
		
		if ($num_pessoas>0) echo "</ul>";
	}
}


/* ----------------------------------------------- PRODUÇÃO --------------------------------------- */

if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("p", $_SESSION["permissao"])) ) {

	if ($_SESSION["id_cidade_sessao"]!="") {
	
		if (isset($_GET["producaoStatusTrocar"])) {
			$mes= date("m");
			$ano= date("Y");
			
			$result_sit= mysql_query("select status_producao from producao_mes
										where mes = '$mes'
										and   ano = '$ano'
										and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
										");
			$linhas_sit= mysql_num_rows($result_sit);
			
			$rs_sit= mysql_fetch_object($result_sit);
			
			if ($rs_sit->status_producao==2)
				$valor= 1;
			else
				$valor= 2;
			
			if ($linhas_sit==0)
				$result2= mysql_query("insert into producao_mes (id_cidade, mes, ano, status_producao)
											values
											('". $_SESSION["id_cidade_sessao"] ."', '$mes', '$ano', '$valor') ");
			else
				$result2= mysql_query("update producao_mes set status_producao= '$valor'
											where mes = '$mes'
											and   ano = '$ano'
											and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."' ");
			
			$pagina= "_producao/producao_status";
			require_once("index3.php");
		}
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

	/* ----------------------------------------------- MATERIAIS --------------------------------------- */
	
	if (isset($_GET["materialExcluir"])) {
		if ($_GET["id_material"]!="") {
			$result_pre= mysql_query("select id_material from almoxarifadom_mov where id_material = '". $_GET["id_material"] ."' ");
			
			if (mysql_num_rows($result_pre)==0)
				$result= mysql_query("delete from materiais where id_material = '". $_GET["id_material"] ."' ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_materiais/material_listar";
		require_once("index3.php");
	}
	
		/* ----------------------------------------------- VEÍCULOS P/ TFD --------------------------------------- */

	if (isset($_GET["veiculoExcluir"])) {
		if ($_GET["id_veiculo"]!="") {
			$result_pre= mysql_query("select id_veiculo from tfds where id_veiculo = '". $_GET["id_veiculo"] ."' ");
			
				if (mysql_num_rows($result_pre)==0)
					$result= mysql_query("delete from tfds_veiculos where id_veiculo = '". $_GET["id_veiculo"] ."' ");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_veiculos/veiculo_listar";
		require_once("index3.php");
	}

	if (isset($_GET["motoristaExcluir"])) {
		if ($_GET["id"]!="") {
			$result_pre= mysql_query("select id_motorista from tfds where id_motorista = '". $_GET["id_motorista"] ."' ");
			
				if (mysql_num_rows($result_pre)==0)
					$result= mysql_query("delete from tfds_motoristas where id = '". $_GET["id"] ."'
											and id_pessoa = '". $_GET["id_motorista"] ."' 
											");
		}
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_veiculos/motorista_listar";
		require_once("index3.php");
	}

}//fim soh administrador

if (pode("!", $_SESSION["permissao"])) {

	/* ----------------------------------------------- REMÉDIOS --------------------------------------- */
	if (isset($_GET["remedioExcluir"])) {
		if ($_GET["id_remedio"]!="") {
			$nome_remedio= pega_remedio($_GET["id_remedio"]);
			
			$result_pre= mysql_query("select id_remedio from apelidos where id_remedio = '". $_GET["id_remedio"] ."' ");
			
			$result_rec= mysql_query("select id_remedio from consultas_remedios where id_remedio = '". $_GET["id_remedio"] ."' ");
			
			$result_alm= mysql_query("select id_remedio from almoxarifado_mov where id_remedio = '". $_GET["id_remedio"] ."' ");
			
			if ( (mysql_num_rows($result_pre)==0) && (mysql_num_rows($result_rec)==0) && (mysql_num_rows($result_alm)==0))
				$result= mysql_query("delete from remedios where id_remedio = '". $_GET["id_remedio"] ."' ");
		}
		
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui remédio, ID ". $_GET["id_remedio"] ." | ". $nome_remedio, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir remédio, ID ". $_GET["id_remedio"] ." | ". $nome_remedio, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_remedios/remedio_listar";
		require_once("index3.php");
	}
	
	/* ----------------------------------------------- APELIDOS --------------------------------------- */
	
	if (isset($_GET["apelidoExcluir"])) {
		$id_remedio= pega_id_remedio_do_apelido($id_apelido);
		if ($_GET["id_apelido"]!="")
			$result= mysql_query("delete from apelidos where id_apelido = '". $_GET["id_apelido"] ."' ");
		
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_remedios/apelido_listar";
		require_once("index3.php");
	}
	
}//fim remedios

if (pode("@", $_SESSION["permissao"])) {

	/* ----------------------------------------------- EXAMES --------------------------------------- */

	if (isset($_GET["exameExcluir"])) {
		if ($_GET["id_exame"]!="") {
			$nome_exame= pega_exame($_GET["id_exame"]);
			$result_exa= mysql_query("select id_exame from consultas_exames where id_exame = '". $_GET["id_exame"] ."' ");
			
			if (mysql_num_rows($result_exa)==0)
				$result= mysql_query("delete from exames where id_exame = '". $_GET["id_exame"] ."' ");
		}
		
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui exame, ID ". $_GET["id_exame"] ." | ". $nome_exame, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir exame, ID ". $_GET["id_exame"] ." | ". $nome_exame, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_exames/exame_listar";
		require_once("index3.php");
	}

}//fim exames

/* ----------------------------------------------- ALMOXARIFADO --------------------------------------- */

if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("x", $_SESSION["permissao"])) ) {

	if (isset($_GET["materialPesquisar"])) {
		//se for saida ou movimentacao... soh mostra oq tem no estoque
		if (($_GET["origem"]=="s") || ($_GET["origem"]=="m")) {
			if ($_SESSION["id_cidade_sessao"]!="")
				$sql= "select materiais.* from materiais, almoxarifadom_atual
									where materiais.material like '%". $_GET["pesquisa"] ."%'
									and   materiais.id_material = almoxarifadom_atual.id_material
									and   almoxarifadom_atual.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
									and   almoxarifadom_atual.qtde_atual > 0
									order by materiais.material asc
									";
			if ($_SESSION["id_posto_sessao"]!="")
				$sql= "select materiais.* from materiais, postosm_estoque
									where materiais.material like '%". $_GET["pesquisa"] ."%'
									and   materiais.id_material = postosm_estoque.id_material
									and   postosm_estoque.id_posto = '". $_SESSION["id_posto_sessao"] ."'
									and   postosm_estoque.qtde_atual > 0
									order by materiais.material asc
									";
									
			$result= mysql_query($sql) or die(mysql_error());	
		}
		//se for entrada, lista todos da listagem
		else
			$result= mysql_query("select materiais.* from materiais
									where materiais.material like '%". $_GET["pesquisa"] ."%'
									order by materiais.material
									 ") or die(mysql_error());
	
		
		if (mysql_num_rows($result)==0)
			echo "<li>Nenhum registro encontrado!</li>";
		else {
			echo "<ul class=\"recuo2\">";
			$i=0;
			while ($rs= mysql_fetch_object($result)) {
				$mostra=true;
				for ($j=0; $j<=$i; $j++) {
					if ($id_material[$j]==$rs->id_material) {
						$mostra=false;
						break;
					}
				}
				$id_material[$i]= $rs->id_material;
				
				if ($mostra) {
					
					if ($_SESSION["id_posto_sessao"]!="") {
						$qtde_atual_c= pega_qtde_atual_material('p', $_SESSION["id_posto_sessao"], $rs->id_material, 'c');
						$qtde_atual_u= pega_qtde_atual_material('p', $_SESSION["id_posto_sessao"], $rs->id_material, 'u');
					}
					if ($_SESSION["id_cidade_sessao"]!="") {
						$qtde_atual_c= pega_qtde_atual_material('c', $_SESSION["id_cidade_sessao"], $rs->id_material, 'c');
						$qtde_atual_u= pega_qtde_atual_material('c', $_SESSION["id_cidade_sessao"], $rs->id_material, 'u');
					}
					
					$var= "<li><a href=\"javascript:void(0);\" onclick=\"
																		atribuiValor('id_material', '". $rs->id_material ."');
																		
																		atribuiValor('tit_material', '". $rs->material ." (". pega_tipo_material($rs->tipo_material) .")');
																		
																		habilitaCampo('qtde');
																		
																		habilitaCampo('observacoes');
																		
																		atribuiValor('tit_qtde_u', '". $qtde_atual_u ."');
																		
																		habilitaCampo('subtipo_trans');
																		\">
																		". $rs->material ." (". pega_tipo_material($rs->tipo_material) .")</a></li>";
																			
					echo $var;
				}
				$i++;
			}
			echo "</ul>";
			
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "pesquisa material na telinha, termo: ". $_GET["pesquisa"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
	}
}

if ( ($_SESSION["id_posto_sessao"]!="") && (pode_algum("on", $_SESSION["permissao"])) ) {
	if (isset($_GET["procedimentoOdontoPesquisar"])) {
		$sql= "select * from odonto_procedimentos
								where procedimento like '%". $_GET["pesquisa"] ."%'
								order by procedimento asc
								";
									
		$result= mysql_query($sql) or die(mysql_error());	

		if (mysql_num_rows($result)==0)
			echo "<li class=\"vermelho\">Nenhum registro encontrado!</li>";
		else {
			echo "<ul class=\"recuo2\">";
			
			while ($rs= mysql_fetch_object($result))
				echo "<li><a href=\"javascript:void(0);\" onclick=\"adicionaOdontoProcedimento('". $rs->id_oprocedimento ."', '". $rs->procedimento ."');\">". $rs->procedimento ."</li>";
		
			echo "</ul>";	
		}
	}
}//fim odonto

//se tiver num posto ou cidade, se for farmacia ou medico receitando
if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode_algum("ceim", $_SESSION["permissao"])) ) {
	if (isset($_GET["pegaEstadoNutricional"])) {
		$peso= formata_valor($_GET["peso"]);
		$altura= formata_valor($_GET["altura"]);
		$imc= ($peso/($altura*$altura));
		
		switch ($_GET["tipo_acompanhamento"]) {
			case "c":
					
					$result= mysql_query("select * from acomp_tabela_criancas
											where meses = '". $_GET["meses"] ."'
											and   sexo = '". $_GET["sexo"] ."'
											");
					$rs= mysql_fetch_object($result);
					
					if ($peso<$rs->p01)
						$en=1;
					elseif (($peso>=$rs->p01) && ($peso<$rs->p3))
						$en=2;
					elseif (($peso>=$rs->p3) && ($peso<$rs->p10))
						$en=3;
					elseif (($peso>=$rs->p10) && ($peso<$rs->p97))
						$en=4;
					elseif ($peso>=$rs->p97)
						$en=5;
					
					$txt_en= pega_en_crianca($en);
					
					break;
			case "a":
					$result= mysql_query("select * from acomp_tabela_adolescentes
											where idade = '". $_GET["idade"] ."'
											and   sexo = '". $_GET["sexo"] ."'
											");
					$rs= mysql_fetch_object($result);
					
					if ($imc<=$rs->p5)
						$en=1;
					elseif (($imc>$rs->p5) && ($imc<$rs->p85))
						$en=2;
					elseif ($peso>=$rs->p85)
						$en=3;
					
					$txt_en= "IMC: ". number_format($imc, 2, ',', '. ') ." | ". pega_en_adolescente($en);
					
					break;
			case "d":
					if ($imc<18.5)
						$en=1;
					elseif (($imc>=18.5) && ($imc<25))
						$en=2;
					elseif (($imc>=25) && ($imc<30))
						$en=3;
					elseif ($imc>=30)
						$en=4;
					
					//$txt_en= pega_en_gestante_adulto($en);
					$txt_en= "IMC: ". number_format($imc, 2, ',', '. ') ." | ". pega_en_gestante_adulto($en);
					//$txt_en= "IMC: ". number_format($imc, 2, ',', '.');
					break;
			case "i":
					if ($imc<=22)
						$en=1;
					elseif (($imc>22) && ($imc<27))
						$en=2;
					elseif ($imc>=27)
						$en=3;
					
					//$txt_en= pega_en_gestante_adulto($en);
					$txt_en= "IMC: ". number_format($imc, 2, ',', '. ') ." | ". pega_en_idoso($en);
					//$txt_en= "IMC: ". number_format($imc, 2, ',', '.');
					
					break;
			case "g":
					$semana_gestacional= $_GET["semana_gestacional"];
					
					$result= mysql_query("select * from acomp_tabela_gestantes
											where semana = '". $semana_gestacional ."'
											");
					$rs= mysql_fetch_object($result);
					
					if ($imc<=$rs->bp)
						$en=1;
					elseif (($imc>$rs->bp) && ($imc<=$rs->a))
						$en=2;
					elseif (($imc>=$rs->a) && ($imc<=$rs->s))
						$en=3;
					elseif ($imc>=$rs->s)
						$en=4;
					
					$txt_en= "IMC: ". number_format($imc, 2, ',', '. ') ." | ". pega_en_gestante_adulto($en);
					//$txt_en= pega_en_gestante_adulto($en);
					
					break;
		}
		
		echo "<span class=\"texto_destaque\">". $txt_en ."</span>";
		
		echo "<input type=\"hidden\" name=\"estado_nutricional\" id=\"estado_nutricional_campo\" class=\"escondido\" value=\"". $en ."\" />";
	}
	
	if (isset($_GET["examePesquisar"])) {
		$sql= "select * from exames
								where (exame like '%". $_GET["pesquisa"] ."%' or apelidos like '%". $_GET["pesquisa"] ."%')
								order by exames.exame asc
								";
									
		$result= mysql_query($sql) or die(mysql_error());	

		if (mysql_num_rows($result)==0)
			echo "<li class=\"vermelho\">Nenhum registro encontrado!</li>";
		else {
			echo "<ul class=\"recuo2\">";
			
			while ($rs= mysql_fetch_object($result)) {
				if ($rs->apelidos!="") $apelidos= "onmouseover=\"Tip('Também conhecido como: ". $rs->apelidos ."');\"";
				else $apelidos= "onmouseover=\"\"";
				
				echo "<li><a $apelidos href=\"javascript:void(0);\" onclick=\"adicionaExame('". $rs->id_exame ."', '". $rs->exame ."');\">". $rs->exame ."</li>";
			}
			echo "</ul>";	
		}
	}
	
	if (isset($_GET["cidPesquisar"])) {
		$sql= "select cid.* from cid
								where (codigo like '%". $_GET["pesquisa"] ."%'
										or descricao like '%". $_GET["pesquisa"] ."%' )
								order by codigo asc
								";
									
		$result= mysql_query($sql) or die(mysql_error());
		
		/*$result2= mysql_query("select id_cid, descricao from cid") or die(mysql_error());	
		
		while ($rs2= mysql_fetch_object($result2)) {
			$result3= mysql_query("update cid
										set descricao= '". trim($rs2->descricao) ."'
										where id_cid = '". $rs2->id_cid ."'
										");
		}*/

		if (mysql_num_rows($result)==0)
			echo "<li class=\"vermelho\">Nenhum registro encontrado!</li>";
		else {
			echo "<ul class=\"recuo2\">";
			
			while ($rs= mysql_fetch_object($result))
				echo "<li><a href=\"javascript:void(0);\" onclick=\"atribuiValor('diagnostico_inicial', '". $rs->id_cid ."'); preencheDiv('diagnostico_ok', '". $rs->codigo ." ". $rs->classificacao ." ". $rs->descricao ." <a href=javascript:void(0); onclick=removeDiagnosticoInicial();>limpar</a>');\">". $rs->codigo ." ". $rs->classificacao ." ". $rs->descricao ."</li>";
		
			echo "</ul>";	
		}
	}

}

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
                

if ( ($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="") ) {

	if (isset($_GET["pegaRemediosPeriodicos"])) {
		$result= mysql_query("select remedios.*, pessoas_remedios.qtde from remedios, pessoas_remedios
								where remedios.id_remedio = pessoas_remedios.id_remedio
								and   pessoas_remedios.id_pessoa = '". $_GET["id_pessoa"] ."'
								order by remedios.remedio asc
								") or die(mysql_error());
    	
		if (mysql_num_rows($result)==0)
			echo "Nenhum medicamento encontrado!";
		else {
			$retorno= "<table cellspacing=\"0\" cellpadding=\"2\">
					<tr>
					<th width=\"65%\" align=\"left\">Nome</th>
					<th width=\"20%\" align=\"center\">Qtde</th>
					<th width=\"15%\" align=\"left\">Ações</th>
                    </tr>";
			
			while ($rs= mysql_fetch_object($result)) {
				$retorno .= "<tr>
								<td>". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</td>
								<td align=\"center\">". $rs->qtde ."</td>
								<td>
								<a onclick=\"return confirm('Tem certeza que deseja excluir este remédio desta pessoa?');\" href=\"javascript:ajaxLink('remedios_periodicos', 'periodicoExcluir&amp;id_remedio=". $rs->id_remedio ."&amp;id_pessoa=". $_GET["id_pessoa"] ."');\" class=\"link_excluir\" title=\"Excluir\">excluir</a>
								</td>
							</tr>
							";
			}
			$retorno .= "</table>";
			echo $retorno;
		}
	}//fim
	
	if (isset($_GET["cadastraPeriodico"])) {
		$result_pre= mysql_query("select * from pessoas_remedios 
									where id_remedio = '". $_GET["id_remedio"] ."'
									and   id_pessoa = '". $_GET["id_pessoa"] ."'
									") or die(mysql_error());
		
		if (mysql_num_rows($result_pre)==0) {
			$result= mysql_query("insert into pessoas_remedios (id_remedio, id_pessoa, qtde)
									values
									('". $_GET["id_remedio"] ."', '". $_GET["id_pessoa"] ."', '". $_GET["qtde"] ."')
									") or die(mysql_error());
			
			echo "<script language=\"javascript\" type=\"text/javascript\">";
			
			if (!$result)
				echo "alert('Não foi possível adicionar, tente novamente!');";
			
			echo "</script>";
		}
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		
		if (mysql_num_rows($result_pre)>0)
			echo "alert('Remédio já está associado à esta pessoa!\n\n(para alterar a quantidade, remova-o por meio do X na listagem)');";
		
		echo "ajaxLink('remedios_periodicos', 'pegaRemediosPeriodicos&id_pessoa=". $_GET["id_pessoa"] ."');
			</script>";
	}
	
	if (isset($_GET["periodicoExcluir"])) {
		$result= mysql_query("delete from pessoas_remedios
								where id_remedio = '". $_GET["id_remedio"] ."'
								and   id_pessoa = '". $_GET["id_pessoa"] ."'
								") or die(mysql_error());
								
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		
		if (!$result)
			echo "alert('Não foi possível excluir, tente novamente!');";
		
		echo "ajaxLink('remedios_periodicos', 'pegaRemediosPeriodicos&id_pessoa=". $_GET["id_pessoa"] ."');
				</script>";
	}
	
	if (isset($_GET["pegaPeriodico"])) {
		
		$result= mysql_query("select id_pessoa, cpf, nome from pessoas where cpf= '". $_GET["cpf"] ."'") or die(mysql_error());
		
		if (mysql_num_rows($result)==0) {
			echo "<span class=\"vermelho\">CPF não cadastrado!</span>
					<a href=\"javascript:abreCadastro(1);\">Cadastrar</a>
					";
		}
		else {
			
			$rs= mysql_fetch_object($result);
			$pagina= "_farmacia/saida_pessoa_inserir";
			require_once("index2.php");
			
			//echo 1;
		}
	}
	
	if (isset($_GET["pegaPeriodicoSemCpf"])) {
		$result= mysql_query("select id_pessoa, cpf, nome from pessoas where id_pessoa= '". $_GET["id_pessoa"] ."'") or die(mysql_error());
		
		if (mysql_num_rows($result)==0)
			echo "<span class=\"vermelho\">Pessoa não encontrada!</span>";
		else {
			$rs= mysql_fetch_object($result);
			$pagina= "_farmacia/saida_pessoa_inserir";
			require_once("index2.php");
		}
	}

	if (isset($_GET["remedioPesquisar"])) {
		//se for saida ou movimentacao... soh mostra oq tem no estoque
		if (($_GET["origem"]=="s") || ($_GET["origem"]=="m")) {
			if ($_SESSION["id_cidade_sessao"]!="")
				$sql= "select remedios.*, almoxarifado_atual.qtde_atual from remedios, almoxarifado_atual
									where (remedios.remedio like '%". $_GET["pesquisa"] ."%'
											or remedios.apelidos like '%". $_GET["pesquisa"] ."%')
									and   remedios.id_remedio = almoxarifado_atual.id_remedio
									and   almoxarifado_atual.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
									and   almoxarifado_atual.qtde_atual > 0
									order by remedios.remedio asc
									";
			if ($_SESSION["id_posto_sessao"]!="")
				$sql= "select remedios.*, postos_estoque.qtde_atual from remedios, postos_estoque
									where (remedios.remedio like '%". $_GET["pesquisa"] ."%'
											or remedios.apelidos like '%". $_GET["pesquisa"] ."%')
									and   remedios.id_remedio = postos_estoque.id_remedio
									and   postos_estoque.id_posto = '". $_SESSION["id_posto_sessao"] ."'
									and   postos_estoque.qtde_atual > 0
									order by remedios.remedio asc
									";
			$result= mysql_query($sql) or die(mysql_error());	
		}
		//se for entrada, lista todos da listagem
		else
			$result= mysql_query("select remedios.* from remedios
									where (remedios.remedio like '%". $_GET["pesquisa"] ."%'
											or remedios.apelidos like '%". $_GET["pesquisa"] ."%')
									order by remedios.remedio asc
									 ") or die(mysql_error());
		
		echo "<ul class=\"recuo2\">";
		$i=0;
		while ($rs= mysql_fetch_object($result)) {
			$mostra=true;
			for ($j=0; $j<=$i; $j++) {
				if ($id_remedio[$j]==$rs->id_remedio) {
					$mostra=false;
					break;
				}
			}
			$id_remedio[$i]= $rs->id_remedio;
			if ($mostra) {
				
				if ($_SESSION["id_posto_sessao"]!="") {
					$qtde_atual_c= pega_qtde_atual_remedio('p', $_SESSION["id_posto_sessao"], $rs->id_remedio, 'c');
					$qtde_atual_u= pega_qtde_atual_remedio('p', $_SESSION["id_posto_sessao"], $rs->id_remedio, 'u');
				}
				if ($_SESSION["id_cidade_sessao"]!="") {
					$qtde_atual_c= pega_qtde_atual_remedio('c', $_SESSION["id_cidade_sessao"], $rs->id_remedio, 'c');
					$qtde_atual_u= pega_qtde_atual_remedio('c', $_SESSION["id_cidade_sessao"], $rs->id_remedio, 'u');
				}
				
				if ($rs->classificacao_remedio=="c")
					$antes= "<img src=\"images/preto.gif\" alt=\"\" />";
				else
					$antes= "";
				
				if ($rs->apelidos!="") $apelidos= "onmouseover=\"Tip('Também conhecido como: ". $rs->apelidos ."');\"";
				else $apelidos= "";
				
				if ($ident==4) //periodico
					echo "<li><a $apelidos href=\"javascript:void(0);\" onclick=\"cadastraPeriodico('". $rs->id_remedio ."');\">". $antes ." ". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</a></li>";
				elseif ($ident==5) //novo medicamento na entrega por pessoa
					echo "<li><a $apelidos href=\"javascript:void(0);\" onclick=\"insereNovoMedicamentoSaidaPessoa('". $rs->id_remedio ."', '". $rs->qtde_atual ."', '". $rs->remedio ."');\">". $antes ." ". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</a></li>";
				elseif ($ident==1)
					echo "<li><a $apelidos href=\"javascript:void(0);\" onclick=\"verificaSeJaTemRemedio('receita_remedio', 'id_remedio_pre', '". $rs->id_remedio ."', 'tit_remedio', '". $rs->remedio ."');\">". $antes ." ". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</a></li>";
				elseif (($ident==2) || ($ident==3)) {
					$var= "<li><a $apelidos href=\"javascript:void(0);\" onclick=\"
																		atribuiValor('id_remedio', '". $rs->id_remedio ."');
																		atribuiValor('classificacao_remedio', '". $rs->classificacao_remedio ."');
																		atribuiValor('tit_remedio', '". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")');
																		habilitaCampo('qtde');
																		habilitaCampo('observacoes');
																		atribuiValor('tit_qtde_u', '". $qtde_atual_u ."');
																		";
																		
																		
																		if ($ident==3)
																			$var .= " habilitaCampo('id_posto_d'); ";
																		else
																			$var .= " habilitaCampo('subtipo_trans'); ";
																		
																		if ($origem=="saida")
																		$var .= "trocaCamposSaida('". $rs->classificacao_remedio ."');";
																		
																		
																		$var .="
																		\">
																		". $antes ." ". $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</a></li>";
																		
					echo $var;
				}//fim elseif
			}
			$i++;
		}
		/*
		$result2= mysql_query("select remedios.* from remedios, apelidos
								where apelidos.id_remedio = remedios.id_remedio
								and   apelidos.apelido like '%". $_GET["pesquisa"] ."%'
								order by remedios.remedio asc
								 ") or die(mysql_error());
		
		while ($rs2= mysql_fetch_object($result2)) {
		
			$mostra=true;
			for ($j=0; $j<=$i; $j++) {
				if ($id_remedio[$j]==$rs2->id_remedio) {
					$mostra=false;
					break;
				}
			}
			$id_remedio[$i]= $rs2->id_remedio;
			
			if ($mostra) {
				if ($rs2->classificacao_remedio=="c")
					$antes= "<img src=\"images/preto.gif\" alt=\"\" />";
				else
					$antes= "";
				
				if ($ident==1)
					echo "<li><a href=\"javascript:void(0);\" onclick=\"verificaSeJaTemRemedio('receita_remedio', 'id_remedio_pre', '". $rs2->id_remedio ."', 'tit_remedio', '". $rs2->remedio ."');\">". $antes ." ". $rs2->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")</a></li>";
				elseif ($ident==2)
					echo "<li><a href=\"javascript:void(0);\" onclick=\"atribuiValor('id_remedio', '". $rs2->id_remedio ."'); atribuiValor('tit_remedio', '". $rs2->remedio ." (". pega_tipo_remedio($rs2->tipo_remedio) .")'); atribuiValor('tit_qtde_c', '". $qtde_atual_c ."'); atribuiValor('tit_qtde_u', '". $qtde_atual_u ."');\">". $antes ." ". $rs2->remedio ." (". pega_tipo_remedio($rs2->tipo_remedio) .")</a></li>";
			}
			$i++;
		}
		if ( (mysql_num_rows($result)==0) && (mysql_num_rows($result2)==0) )
		*/
		if (mysql_num_rows($result)==0)
			echo "<li class=\"vermelho\">Nenhum registro encontrado!</li>";
	
		echo "</ul>";	
		@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "pesquisa remédio na telinha, termo: ". $_GET["pesquisa"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	}
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
	
	if ($_GET["tipo_consulta"]=="p") {
		$sql_linha1= ", tfds_solicitacoes";
		$sql_linha2= "and tfds_solicitacoes.id_pessoa = pessoas.id_pessoa
					  and  (tfds_solicitacoes.situacao_solicitacao= '2' or tfds_solicitacoes.situacao_solicitacao= '4')
						";
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
				$rotina_volta_cpf= "alert('Esta pessoa está cadastrada sem CPF!\\n\\nPeça para trazer o CPF nas próxima visitas!'); abreFechaDiv('pessoa_buscar');";

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
			
			$var= "<li><a href=\"javascript:void(0);\" onclick=\"". $rotina_volta_cpf ."\" onmouseover=\"Tip('<strong>CPF:</strong> ". mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel) ."<br /> <strong>Nascimento:</strong> ". $rs->data_nasc ." ');\">". $rs->nome ."</a></li>";
																	
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

if (isset($_GET["alteraDadosAcompanhamento"])) {
	$result= mysql_query("select id_pessoa, nome, situacao_pessoa, sexo, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc
							from pessoas where id_pessoa= '". $_GET["id_pessoa"] ."'") or die(mysql_error());
	
	$rs= mysql_fetch_object($result);
	$idade= calcula_idade($rs->data_nasc);
	if (!is_int($idade) || ($idade<0))
		echo "<script language='javascript'>alert('Clique em \'editar dados\' e corrija a data de nascimento! Consta como \'". $rs->data_nasc ."\' !');</script>";

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
	
	if ($idade<7) echo " (". $meses ." meses) ";
	echo "<br />";
	
	echo "<label>Sexo:</label> ". pega_sexo($rs->sexo) ." <br />";
	
	echo "<script language=\"javascript\">ajaxLink('acompanhamento_ac', 'carregaPaginaInterna&pagina=_acomp/acomp&sexo=". $rs->sexo ."&tipo_acompanhamento=". $tipo_acompanhamento ."');</script></div>";
				
}
if (isset($_GET["atualizaSolicitacoesTfd"])) {
	$result_sol= mysql_query("select *, DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%d/%m/%Y') as data_solicitacao
								from tfds_solicitacoes
								where id_pessoa = '". $_GET["id_pessoa"] ."'
								and   (situacao_solicitacao= '2'
										or situacao_solicitacao= '4')
								");
	if (mysql_num_rows($result_sol)>0) {
		echo "
			<select name=\"id_solicitacao[]\" onchange=\"mostraSolicitacao(this);\">
				<option value=\"\">--- selecione ---</option>";
				$i=0;
				while ($rs_sol= mysql_fetch_object($result_sol)) {
					if (($i%2)==0) $classe= "cor_sim";
					else $classe= "cor_nao";
					echo "<option class=\"". $classe ."\" value=\"". $rs_sol->id_solicitacao ."\">". $rs_sol->id_solicitacao ." - ". $rs_sol->data_solicitacao ."</option>";
				}
		echo "</select>";
	}
	else {
		//se for paciente mostra isso.. (nao mostra nada qdo é carona).
		if ($_GET["tipo"]=="p")
		echo "<input name=\"id_solicitacao[]\ type=\"hidden\" class=\"escondido\" value=\"\" />
			<span class=\"vermelho\">Nenhuma solicitação aceita pendente encontrada!<br /><br /><strong>Esta pessoa não será inserida nesta TFD!</strong></span>";
	}
}

if (isset($_GET["tfdSolicitacaoExcluir"])) {
	$result_pre= mysql_query("select id_tfd_pessoa from tfds_pessoas, tfds
								where tfds_pessoas.id_solicitacao = '". $_GET["id_solicitacao"] ."'
								and   tfds_pessoas.id_tfd = tfds.id_tfd
								and   tfds.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								");
	
	if (mysql_num_rows($result_pre)==0) {
		$msg= 0;
		
		$result= mysql_query("delete from tfds_solicitacoes
								where id_solicitacao = '". $_GET["id_solicitacao"] ."'
								and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								");
		
		$pagina= "_tfd/tfd_solicitacao_listar";
		require_once("index3.php");
	}
	else {
		$msg= 1;
		$pagina= "_tfd/tfd_solicitacao_ver";
		require_once("index3.php");
	}
}

if (isset($_GET["tfdExcluir"])) {
	$var= 0;
	inicia_transacao();
		
	$result1= mysql_query("delete from tfds
							where id_tfd = '". $_GET["id_tfd"] ."'
							and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							");
	if (!$result1) $var++;
	
	$result2= mysql_query("select * from tfds_pessoas
							where id_tfd = '". $_GET["id_tfd"] ."'
							");
	if (!$result2) $var++;
	
	while ($rs2= mysql_fetch_object($result2)) {
		$result3= mysql_query("delete from tfds_pessoas_acompanhantes
								where id_tfd_pessoa = '". $rs2->id_tfd_pessoa ."'
								");
		if (!$result3) $var++;
		
		$result35= mysql_query("update tfds_solicitacoes
								set   situacao_solicitacao = '0'
								where id_solicitacao = '". $rs2->id_solicitacao ."'
								");
		if (!$result35) $var++;
	}
	
	$result4= mysql_query("delete from tfds_pessoas
							where id_tfd = '". $_GET["id_tfd"] ."'
							");
	if (!$result4) $var++;
	
	finaliza_transacao($var);
	
	$msg= $var;
	
	$pagina= "_tfd/tfd_listar";
	require_once("index3.php");
}


if (isset($_GET["adicionaAcompanhante"])) {
	//echo "oi $id_pessoa !";
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

/* --------------------------------------- PRONTUARIO ---------------------------------------------------------------- */

if ( ($_SESSION["id_posto_sessao"]!="") && (pode_algum("ceim", $_SESSION["permissao"])) ) {
	if (isset($_GET["consultaExcluir"])) {
		
		$var= 0;
		inicia_transacao();
		
		if ($_SESSION["tipo_usuario_sessao"]=="a")
			$result_pre= mysql_query("select * from consultas
										where id_consulta = '". $_GET["id_consulta"] ."'
										") or die(mysql_error());
		else
			$result_pre= mysql_query("select * from consultas
										where id_consulta = '". $_GET["id_consulta"] ."'
										and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
										and   (id_usuario = '". $_SESSION["id_usuario_sessao"] ."'
												or id_usuario_usando = '". $_SESSION["id_usuario_sessao"] ."')

										") or die(mysql_error());
		if (!$result_pre) $var++;
		
		$result_rem= mysql_query("select * from consultas_remedios where id_consulta = '". $_GET["id_consulta"] ."' and id_mov is not NULL ") or die(mysql_error());
		
		if ((mysql_num_rows($result_pre)==1) && (mysql_num_rows($result_rem)==0)) {
			$result1= mysql_query("delete from consultas where id_consulta = '". $_GET["id_consulta"] ."' ") or die(mysql_error());
			if (!$result1) $var++;
			
			$result2= mysql_query("delete from acompanhamento where id_consulta = '". $_GET["id_consulta"] ."' ") or die(mysql_error());
			if (!$result2) $var++;
			
			$result3= mysql_query("delete from consultas_exames where id_consulta = '". $_GET["id_consulta"] ."' ") or die(mysql_error());
			if (!$result3) $var++;
			
			$result4= mysql_query("delete from consultas_remedios where id_consulta = '". $_GET["id_consulta"] ."' ") or die(mysql_error());
			if (!$result4) $var++;
			
			$result5= mysql_query("delete from consultas_odonto_procedimentos where id_consulta = '". $_GET["id_consulta"] ."' ") or die(mysql_error());
			if (!$result5) $var++;
			
			/*
			$rs_pre= mysql_fetch_object($result_pre);
			
			//se consulta veio de agendamento
			if (strstr($rs_pre->origem_consulta, 'a')) {
				$id_agenda= substr($rs_pre->origem_consulta, 2);
				
				$result6= mysql_query("delete from agenda_consultas where id_agenda = '". $_GET["id_agenda"] ."' ") or die(mysql_error());
				if (!$result6) $var++;
			}
			//fila de espera
			else {
				$id_fila= substr($rs_pre->origem_consulta, 2);
				
				$result6= mysql_query("delete from filas where id_fila = '". $_GET["id_fila"] ."' ") or die(mysql_error());
				if (!$result6) $var++;
			}
			*/
		}
		else $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "_consultas/consulta_listar";
		require_once("index3.php");
	}
}

if ( ($_SESSION["id_posto_sessao"]!="") && (pode_algum("ceimr", $_SESSION["permissao"])) ) {
	if (isset($_GET["pegaProntuario"])) {
		$result= mysql_query("select id_pessoa, cpf, nome, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc
								from pessoas where cpf= '". $_GET["cpf"] ."'") or die(mysql_error());
		
		if (mysql_num_rows($result)==0) {
			echo "<span class=\"vermelho\">CPF não cadastrado!</span>
					<a href=\"javascript:abreCadastro(1);\">Cadastrar</a>
					";
					/*
					abreDivSo('tela_cadastro');
										atribuiValor('cpf_cadastro', '". $cpf ."');
										preencheDiv('cpf_mesmo', '". formata_cpf($cpf) ."');
					*/
	//				<a href=\"javascript:void(0);\" onclick=\"window.open('index2.php?pagina=pessoa_inserir&amp;cpf=". $cpf ."', '', 'width=300,height=400,top=10,left=400')\">Cadastrar</a>
		}
		else {
			$rs= mysql_fetch_object($result);
			
			$idade= calcula_idade($rs->data_nasc);
			if (!is_int($idade) || ($idade<0))
				echo "<script language='javascript'>alert('Clique em \'editar dados\' e corrija a data de nascimento! Consta como \'". $rs->data_nasc ."\' !');</script>";
			
			$pagina= "_consultas/fila_inserir";
			require_once("index2.php");
		}
	}
	
	if (isset($_GET["pegaProntuarioSemCpf"])) {
		$result= mysql_query("select id_pessoa, cpf, nome from pessoas where id_pessoa= '". $_GET["id_pessoa"] ."'") or die(mysql_error());
		
		if (mysql_num_rows($result)==0)
			echo "<span class=\"vermelho\">Pessoa não encontrada!</span>";
		else {
			$rs= mysql_fetch_object($result);
			$pagina= "_consultas/fila_inserir";
			require_once("index2.php");
		}
	}
	
	if (isset($_GET["filaExcluir"])) {
		$result= mysql_query("update filas
								set atendido = '3'
								where id_posto = '". $_SESSION["id_posto_sessao"] ."'
								and   id_fila = '". $_GET["id_fila"] ."'
								and   atendido = '0'
								") or die(mysql_error());
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui fila, ID ". $_GET["id_fila"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir fila, ID ". $_GET["id_fila"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_consultas/fila_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["agendaExcluir"])) {
		$result= mysql_query("delete from agenda_consultas
								where id_agenda = '". $_GET["id_agenda"] ."'
								and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
								and   atendido = '0'
								") or die(mysql_error());
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui agenda, ID ". $_GET["id_agenda"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir agenda, ID ". $_GET["id_agenda"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_consultas/agenda_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["procExcluir"])) {
		$result= mysql_query("delete from procedimentos
								where id = '". $_GET["id"] ."'
								and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
								
								") or die(mysql_error());
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui procedimento, ID ". $_GET["id"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir procedimento, ID ". $_GET["id"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_proc/proc_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["acompExcluir"])) {
		$result= mysql_query("delete from acompanhamento
								where id_acompanhamento = '". $_GET["id_acompanhamento"] ."'
								and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
								") or die(mysql_error());
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui acompanhamento, ID ". $_GET["id_acompanhamento"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir acompanhamento, ID ". $_GET["id_acompanhamento"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_acomp/acomp_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["pessoaGrupoExcluir"])) {
		$result= mysql_query("delete from acomp_grupos_pessoas
								where id_pessoa = '". $_GET["id_pessoa"] ."'
								and   id_grupo = '". $_GET["id_grupo"] ."'
								and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
								") or die(mysql_error());
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui pessoa ". pega_nome($_GET["id_pessoa"]) ." do grupo ". $_GET["id_grupo"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 0;
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir pessoa ". pega_nome($_GET["id_pessoa"]) ." do grupo ". $_GET["id_grupo"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$msg= 1;
		}
		
		$pagina= "_acomp/grupo_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["alteraProfissional"])) {
	
		switch($_GET["tipo"]) {
			case "m": $var_tipo="c"; break;
			case "e": $var_tipo="e"; break;
			case "o": $var_tipo="o"; break;
			default: die();
		}
			
		$result_pr= mysql_query("select pessoas.nome, usuarios.id_usuario from pessoas, usuarios, usuarios_postos
					where pessoas.id_pessoa = usuarios.id_pessoa
					and   usuarios.id_usuario = usuarios_postos.id_usuario
					and   usuarios_postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
					and   INSTR(usuarios_postos.permissao, '". $var_tipo ."')<>'0'
					order by pessoas.nome asc
					");
		
		echo "<select name=\"id_usuario\" id=\"id_usuario\" class=\"tamanho300\">";
		$i=0;
		while ($rs_pr= mysql_fetch_object($result_pr)) {
			if (($i%2)==0) $classe= "class=\"cor_sim\"";
			else $classe= "";
			
			echo "<option ". $classe ." value=". $rs_pr->id_usuario .">". $rs_pr->nome ."</option>";
			$i++;
		}
		echo "</select>";
	}
}

if (isset($_GET["pegaResponsavel"])) {
	echo "<label>Responsável:</label>". pega_nome_pelo_cpf($_GET["cpf"]) ." (". formata_cpf($_GET["cpf"]) .") <br />";
}

/* --------------------------------------- CONSULTAS - MÉDICO ---------------------------------------------------------------- */

if (pode_algum("ceimf", $_SESSION["permissao"])) {

	if (isset($_GET["remedioInserir"])) {
		if ($_GET["remedio"]!="") {
			$result_antes= mysql_query("select remedio from remedios
											where remedio = '". $_GET["remedio"] ."'
											and   tipo_remedio = '". $_GET["tipo_remedio"] ."' ");
	
			if (mysql_num_rows($result_antes)==0) {
				if ($_GET["classificacao_remedio"]=="c") $class_rem= "c";
				else $class_rem= "n";
				
				$result= mysql_query("insert into remedios (remedio, tipo_remedio, classificacao_remedio, id_usuario)
								values ('". strtoupper($_GET["remedio"]) ."', '". $_GET["tipo_remedio"] ."', '". $class_rem ."', '". $_SESSION["id_usuario_sessao"] ."') ");
			}
		}
	
		echo "<script language='javascript' type='text/javascript'>;";
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere remédio, ID ". mysql_insert_id() ." | ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Remédio cadastrado com sucesso!');";
			echo "atribuiValor('remedio', '');";
			echo "abreFechaDiv('remedio_cadastro');";
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir remédio, ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Remédio já cadastrado!');";
		}
		
		echo "</script>";
	}

	if (isset($_GET["exameInserir"])) {
		if ($_GET["exame"]!="") {
			$result_pre= mysql_query("select exame from exames where exame = '". strip_tags(strtoupper($_GET["exame"])) ."' ");
			
			if (mysql_num_rows($result_pre)==0)
				$result= mysql_query("insert into exames (exame, tipo_exame, id_usuario)
									values ('". strip_tags(strtoupper($_GET["exame"])) ."', '". $_GET["tipo_exame"] ."', '". $_SESSION["id_usuario_sessao"] ."') ");
		}

		echo "<script language='javascript' type='text/javascript'>;";
		
		if ($result) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere exame, ID ". mysql_insert_id() ." | ". $_POST["exame"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Exame cadastrado com sucesso!');";
			echo "atribuiValor('exame', '');";
			echo "abreFechaDiv('exame_cadastro');";
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir exame ". $_POST["exame"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Exame já cadastrado!');";
		}
		
		echo "</script>";
	}

}

/* --------------------------------------- SOCIAL ---------------------------------------------------------------- */

if (pode_algum("zl", $_SESSION["permissao"])) {
	
	if (isset($_GET["familiaExcluir"])) {
		
		$var= 0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from familias
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									limit 1
									") or die(mysql_error());
		
		if (mysql_num_rows($result_pre)==1) {
			$result1= mysql_query("delete from familias
									where id_familia = '". $_GET["id_familia"] ."'
									and   id_cidade = '". $id_cidade_emula ."'
									limit 1
									") or die(mysql_error());
			if (!$result1) $var++;
			
			$result2= mysql_query("delete from familias_assistencias
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result2) $var++;
			
			$result3= mysql_query("delete from familias_bens
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result3) $var++;
			
			$result4= mysql_query("delete from familias_pareceres
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result4) $var++;
			
			$result5= mysql_query("delete from familias_pessoas
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result5) $var++;
			
			$result6= mysql_query("delete from familias_programas
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result6) $var++;
			
			$result7= mysql_query("delete from familias_visitas
									where id_familia = '". $_GET["id_familia"] ."' limit 1 ") or die(mysql_error());
			if (!$result7) $var++;
		} else $var++;
		
		finaliza_transacao($var);
		
		if ($var==0)
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui família ID ". $_GET["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		else
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao excluir família ID ". $_GET["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		
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
					<th width=\"60%\" align=\"left\">Nome</th>
                    <th width=\"30%\">Parentesco</th>
					<th width=\"10%\" align=\"left\">Ações</th>
                    </tr>";
		
		$result= mysql_query("select familias_pessoas.* from familias_pessoas, familias, microareas, postos
								where familias.id_familia = '". $_GET["id_familia"] ."'
								and   familias.id_familia = familias_pessoas.id_familia
								and   familias.id_microarea = microareas.id_microarea
								and   microareas.id_posto = postos.id_posto
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
	
	if (isset($_GET["parecerExcluir"])) {
		$result= mysql_query("delete from familias_pareceres
								where id_familia = '". $_GET["id_familia"] ."'
								and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   id_parecer = '". $_GET["id_parecer"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_social/parecer";
		require_once("index3.php");
	}

	if (isset($_GET["visitaExcluir"])) {
		$result= mysql_query("delete from familias_visitas
								where id_familia = '". $_GET["id_familia"] ."'
								and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   id_visita = '". $_GET["id_visita"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_social/visitas";
		require_once("index3.php");
	}

	if (isset($_GET["assistenciaExcluir"])) {
		$result= mysql_query("delete from familias_assistencias
								where id_familia = '". $_GET["id_familia"] ."'
								and   id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								and   id_assistencia = '". $_GET["id_assistencia"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg= 1;
		
		$pagina= "_social/assistencias";
		require_once("index3.php");
	}
	

}


/* ---------------------------------------------------------------------------------------------------- */

echo "</body></html>";
?>