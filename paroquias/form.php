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
/*
?>
<div id="temp">
    <strong>id_usuario_sessao:</strong> <?= $_SESSION["id_usuario_sessao"]; ?> <br />
    <strong>tipo_usuario_sessao:</strong> <?= $_SESSION["tipo_usuario_sessao"]; ?> <br />
    <strong>id_posto_sessao:</strong> <?= $_SESSION["id_posto_sessao"]; ?> <br />
    <strong>id_cidade_sessao:</strong> <?= $_SESSION["id_cidade_sessao"]; ?> <br />
    <strong>id_cidade_pref:</strong> <?= $_SESSION["id_cidade_pref"]; ?> <br />
    <strong>id_uf_pref:</strong> <?= $_SESSION["id_uf_pref"]; ?> <br />
    <strong>nome_pessoa_sessao:</strong> <?= $_SESSION["nome_pessoa_sessao"]; ?> <br />
    <strong>permissao:</strong> <?= $_SESSION["permissao"]; ?> <br />
    <strong>id_cbo:</strong> <?= $_SESSION["id_cbo_sessao"]; ?> <br />
    <strong>trocando:</strong> <?= $_SESSION["trocando"]; ?>
</div>
<?
*/

if ($_SESSION["id_cidade_sessao"]!="") $id_cidade_emula= $_SESSION["id_cidade_sessao"];
else $id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

if ($_SESSION["id_usuario_sessao"]!="") {
	if (isset($_GET["formSenha"])) {
		if ($_POST["senha_atual"]!="") {
			$var= 0;
			inicia_transacao();
			
			$result_antes= mysql_query("select id_pessoa from usuarios
											where id_usuario = '". $_SESSION["id_usuario_sessao"] ."'
											and   senha = '". md5($_POST["senha_atual"]) ."' ");
			if (!$result_antes) $var++;
			
			if (mysql_num_rows($result_antes)==1) {
				$rs_antes= mysql_fetch_object($result_antes);
				
				if ($_POST["email_s"]!="") {
					$result1_pre= mysql_query("select id_pessoa from pessoas
												where email= '". $_POST["email_s"] ."'
												and   id_pessoa <> '". $rs_antes->id_pessoa ."'
												") or die("1 ". mysql_error());
					if (mysql_num_rows($result1_pre)==0) {
						$result1= mysql_query("update pessoas set email= '". $_POST["email_s"] ."'
												where id_pessoa = '". $rs_antes->id_pessoa ."'
												") or die("1 ". mysql_error());
						if (!$result1) $var++;
						$mensagem= "E-mail atualizado com sucesso!\\n\\n";
						@logs($_SESSION["id_acesso"],$_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "atualiza e-mail", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					}
					else {
						$mensagem= "O e-mail informado já está em uso por outro usuário!\\n\\n";
						@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "tenta atualizar e-mail, porém já estava em uso", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					}
				}
				
				if (($_POST["senha_nova"]!="") && ($_POST["senha_nova"]==$_POST["senha_nova2"])) {
					$result2= mysql_query("update usuarios
											set senha= '". md5($_POST["senha_nova"]) ."',
											senha_sem_enc= '". $_POST["senha_nova"] ."'
											where id_usuario = '". $_SESSION["id_usuario_sessao"] ."'
											") or die("2 ". mysql_error());
					if (!$result2) $var++;
					
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "atualiza a senha", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					$mensagem .= "Senha atualizada com sucesso!\\n\\n";
				}
				
				if ($mensagem=="") {
					$mensagem .= "Você não digitou nem e-mail, nem nova senha.!\\nNada foi atualizado!";
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "não digitou senha, nem e-mail, por isso nada foi atualizado", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
				}
			}
			else {
				$mensagem= "Senha atual incorreta!";
				@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "tentou atualizar, mas digitou senha atual incorreta", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			}
		}
		
		finaliza_transacao($var);
		
		echo "<script language='javascript' type='text/javascript'>;";
		if ($var==0) {
			echo "alert('". $mensagem ."');";
			echo "abreFechaDiv('tela_senha');";
		}
		else
			echo "alert('Não foi possível atualizar seus dados!\\n\\nTente novamente ou entre em contato para solucionar o problema!');";
		
		echo "</script>";
	}

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
	
				if ($_POST["id_posto_em"]=="") {
					session_unregister("id_posto_sessao");
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "emula a cidade ". pega_cidade($_SESSION["id_cidade_sessao"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
				}
				else {
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "emula o posto ". pega_posto($_SESSION["id_posto_sessao"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					@session_unregister("id_cidade_sessao");
					@session_unregister("id_uf_sessao");
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
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "emula a cidade ". pega_cidade($_POST["id_cidade_sessao"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
				}
				//se a cidade estiver ativa
				//e
				//quiser emular um posto
				if (($_POST["id_posto_em"]!="") && (($_SESSION["id_cidade_sessao"]!="") || ($_SESSION["id_posto_sessao"]!="")) && (($_SESSION["id_posto_sessao"]=="") || ($_SESSION["id_cidade_sessao"]==""))) {
					$_SESSION["id_cidade_sessao"]= "";
					
					$_SESSION["trocando"]= 1;
					$_SESSION["id_posto_sessao"]= $_POST["id_posto_em"];
					
					@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "emula o posto ". pega_posto($_POST["id_posto_sessao"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
				}
			}
			echo "<script language=\"javascript\">recarregaPaginaAtual(); </script>";
		}
		
		$pagina= "menu";
		require_once("index2.php");
	}
}//fim id_usuario_sessao

/* ----------------------------------------------- AJUDA --------------------------------------- */

if (isset($_GET["formContato"])) {
	$enviar_email= @mail("jaisonn@gmail.com, jeancarlosn@gmail.com, rosivetenie@yahoo.com.br", "LISBETA SAÚDE | CONTATO",
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
	if ($enviar_email) $msg=0;
	
	@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "entra em contato", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	
	$pagina= "_ajuda/contato";
	require_once("index3.php");
}

/* -------------------------------------------- TFDs -------------------------------------------------- */


	/* -------------------------------------------- CIDADES -------------------------------------------------- */
	
	if (isset($_GET["formCidadeInserir"])) {
		if ($_POST["id_cidade"]!="")
			$result= mysql_query("update cidades set sistema = '1'
									where id_cidade = '$id_cidade' ");
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere cidade, ID ". $_POST["id_cidade"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir cidade, ID ". $_POST["id_cidade"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$pagina= "_acesso/cidade_listar";
		require_once("index3.php");
	}
	
	/* -------------------------------------------- POSTOS -------------------------------------------------- */
	
	if (isset($_GET["formPostoEditar"])) {
		if (($_POST["posto"]!="") && ($_POST["id_posto"]!=""))
			$result= mysql_query("update postos set posto= '". strip_tags(strtoupper($_POST["posto"])) ."'
									where id_posto = '". $_POST["id_posto"] ."' ");
			//echo "update postos set posto= '". strtoupper($_POST["posto"]) ."' where id_posto = '". $_POST["id_posto"] ."' ";
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "edita posto, ID ". $_POST["id_posto"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao editar posto, ID ". $_POST["id_posto"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
	
		$id_cidade= pega_id_cidade_do_posto($id_posto);	
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formPostoInserir"])) {
		if (($_POST["posto"]!="") && ($_POST["id_cidade"]!=""))
			$result= mysql_query("insert into postos (id_cidade, posto, situacao)
								values ('$id_cidade', '". strip_tags(strtoupper($_POST["posto"])) ."', '1')");
			//echo "insert into postos (id_cidade, posto, situacao) values ('$id_cidade', '". strtoupper($_POST["posto"]) ."', '1')";
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere posto, ID ". mysql_insert_id() ." | ". $_POST["posto"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir posto", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
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
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "edita microárea, ID ". $_POST["id_microarea"] ." | ". $_POST["microarea"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao editar microárea, ID ". $_POST["id_microarea"] ." | ". $_POST["microarea"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	if (isset($_GET["formMicroareaInserir"])) {
		if (($_POST["id_posto"]!="") && ($_POST["id_pessoa"]!=""))
			$result= mysql_query("insert into microareas (id_posto, microarea, id_pessoa) values ('". $_POST["id_posto"] ."', '". strip_tags(strtoupper($_POST["microarea"])) ."', '". $_POST["id_pessoa"] ."')");
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere microárea, ID ". mysql_insert_id() ." | ". $_POST["microarea"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir microárea | ". $_POST["microarea"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
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
										(id_pessoa, usuario, senha, tipo_usuario, crm, situacao, senha_sem_enc)
										values
										('". $_POST["id_pessoa"]. "', '". strip_tags($_POST["usuario"]) ."', '". $senha ."',
										'". $_POST["tipo_usuario"] ."', '". $_POST["crm"] ."', '1', '". $_POST["senha"] ."')");
		}
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "inserir usuário, ID ". mysql_insert_id() ." | ". $_POST["usuario"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir usuário | ". $_POST["usuario"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
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
										senha = '". $senha ."',
										senha_sem_enc = '". $_POST["senha"] ."'
										where id_usuario = '". $_POST["id_usuario"] ."'
										");
		}
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "editar usuário, ID ". $_POST["id_usuario"] ." | ". $_POST["usuario"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao editar usuário | ". $_POST["id_usuario"]." | ". $_POST["usuario"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$pagina= "_acesso/usuario_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioBuscar"])) {
		@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "busca usuário | ". $_POST["txt_busca"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
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
				$permissao_txt = ".". $_POST["familias"] . $_POST["missionarios"] . $_POST["arrecadacao"] .".";
				
				$result= mysql_query("insert into usuarios_postos (id_posto, id_usuario, id_cbo, permissao)
										values
										('". $_POST["id_posto"] ."', '". $_POST["id_usuario"] ."', '". $_POST["id_cbo"] ."', '$permissao_txt') ") or die(mysql_error());
			}
		}
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere usuário ". pega_usuario($_POST["id_usuario"]) ." no posto ". pega_posto($_POST["id_posto"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir usuário ". pega_usuario($_POST["id_usuario"]) ." no posto ". pega_posto($_POST["id_posto"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$id_posto= $_POST["id_posto"];
		
		$pagina= "_acesso/usuariop_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNoPostoEditar"])) {
		if (($_POST["id_posto"]!="") && ($_POST["id_usuario"]!="")) {
			$permissao_txt = ".". $_POST["familias"] . $_POST["missionarios"] . $_POST["arrecadacao"] .".";
			
			$result= mysql_query("update usuarios_postos set permissao = '$permissao_txt', id_cbo= '". $_POST["id_cbo"] ."'
									where id_posto= '". $_POST["id_posto"] ."'
									and   id_usuario= '". $_POST["id_usuario"] ."' ") or die(mysql_error());
		}
		
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "edita usuário ". pega_usuario($_POST["id_usuario"]) ." no posto ". pega_posto($_POST["id_posto"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao editar usuário ". pega_usuario($_POST["id_usuario"]) ." no posto ". pega_posto($_POST["id_posto"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
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
				$permissao_txt = ".". $_POST["familias"] . $_POST["arrecadacao"] .".";
				
				$result= mysql_query("insert into usuarios_cidades (id_cidade, id_usuario, id_cbo, permissao)
										values
										('". $_POST["id_cidade"] ."', '". $_POST["id_usuario"] ."', '". $_POST["id_cbo"] ."', '$permissao_txt')");
			}
		}
		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere usuário ". pega_usuario($_POST["id_usuario"]) ." na cidade ". pega_cidade($_POST["id_cidade"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir usuário ". pega_usuario($_POST["id_usuario"]) ." na cidade ". pega_cidade($_POST["id_cidade"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$id_cidade= $_POST["id_cidade"];
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formUsuarioNaCidadeEditar"])) {
		if (($_POST["id_cidade"]!="") && ($_POST["id_usuario"]!="")) {
			$permissao_txt = ".". $_POST["prontuario"] . $_POST["consultar"] . $_POST["farmacia"] . $_POST["almoxarifado"] . $_POST["secretario"] . $_POST["producao"] . $_POST["tfd"] . $_POST["social"] . $_POST["remedios"] . $_POST["exames"] .".";
			
			$result= mysql_query("update usuarios_cidades set permissao = '$permissao_txt'
									where id_cidade= '". $_POST["id_cidade"] ."'
									and   id_usuario= '". $_POST["id_usuario"] ."' ") or die(mysql_error());
		}

		if ($result) {
			$msg= 0;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "edita usuário ". pega_usuario($_POST["id_usuario"]) ." na cidade ". pega_cidade($_POST["id_cidade"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		else {
			$msg= 1;
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao editar usuário ". pega_usuario($_POST["id_usuario"]) ." na cidade ". pega_cidade($_POST["id_cidade"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		}
		
		$id_cidade= $_POST["id_cidade"];
		$pagina= "_acesso/posto_listar";
		require_once("index3.php");
	}

/* -------------------------------------------- PESSOAS -------------------------------------------------- */

if (isset($_GET["formPessoaRelatorio"])) {
	$pagina= "_pessoas/pessoa_relatorio";
	require_once("index3.php");
}

if (isset($_GET["formPessoaExcluir"])) {
	if (($_POST["id_pessoa_excluir"]!="") && ($_POST["acao"]==$_SESSION["auth_temp"])) {
		session_unregister("auth_temp");
		
		$var= 0;
		inicia_transacao();
		
		if ($_POST["id_pessoa"]!="") {
			$result_fam= mysql_query("update  familias_pessoas set id_pessoa = '". $_POST["id_pessoa"] ."' where id_pessoa = '". $_POST["id_pessoa_excluir"] ."' ") or die("7:". mysql_error()); if (!$result_fam) $var++;
			$result_usu= mysql_query("update  usuarios set id_pessoa = '". $_POST["id_pessoa"] ."' where id_pessoa = '". $_POST["id_pessoa_excluir"] ."' ") or die("14:". mysql_error()); if (!$result_usu) $var++;
			$result_dep= mysql_query("update  pessoas set id_responsavel = '". $_POST["id_pessoa"] ."' where id_responsavel = '". $_POST["id_pessoa_excluir"] ."' ") or die("15:". mysql_error()); if (!$result_dep) $var++;
			//$result_res= mysql_query("update  microareas_pessoas set id_pessoa = '". $_POST["id_pessoa"] ."' where id_pessoa = '". $_POST["id_pessoa_excluir"] ."' ") or die("16:". mysql_error()); if (!$result_dep) $var++;
		}
		if (($_POST["id_pessoa"]!="") || (($_POST["id_pessoa"]=="") && (!$_POST["tem_dados"]))) {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "exclui a pessoa ". $_POST["id_pessoa_excluir"] ."/". pega_nome($_POST["id_pessoa_excluir"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			
			$result_pes= mysql_query("delete from pessoas where id_pessoa = '". $_POST["id_pessoa_excluir"] ."' ") or die("17:". mysql_error());
			if (!$result_pes) $var++;
			
		}
		else {
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "não pôde excluir a pessoa ". $_POST["id_pessoa_excluir"] ."/". pega_nome($_POST["id_pessoa_excluir"]) .", já que tinha dados e não foi informado sbustituto", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			$var++;
		}
		
		finaliza_transacao($var);
	}
	else echo "Faltam dados!";
	
	$msg= $var;
	
	$pagina= "_pessoas/pessoa_listar";
	require_once("index3.php");
}

if (isset($_GET["formPessoaEditar"])) {
	if ( ($_POST["id_cidade"]!="") && ($_POST["nome"]!="") && ($_POST["id_pessoa"]!="") ) {
		$var= 0;
		inicia_transacao();
		
		$permissao_acesso= false;
		
		//se esta vindo alguem q era dependente e nao eh mais
		if ( ($_POST["cpf_cadastro"]!="") && ($_POST["id_responsavel"]!="0") ) {
			$result_pre= mysql_query("select id_pessoa from pessoas where cpf= '". $_POST["cpf_cadastro"] ."' ");
			if (mysql_num_rows($result_pre)==0) {
				$permissao_acesso=true;
				@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "atualiza CPF/responsável de ID ". $_POST["id_pessoa"] ." | ". pega_nome($_POST["id_pessoa"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			}
			else $cpf_existe= true;
		}
		else $permissao_acesso= true;
		
		if ($permissao_acesso) {
			$data_nasc= formata_data($data_nasc);
			
			if ($_POST["cpf_cadastro"]!="") {
				$linha_cpf= "cpf = '". $_POST["cpf_cadastro"] ."', id_responsavel = '0', ";
			}
			
			//if ($_SESSION["tipo_usuario_sessao"]=="a")
			if ($_POST["situacao_pessoa"]==2)
				@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "marcou ID ". $_POST["id_pessoa"] ." | ". pega_nome($_POST["id_pessoa"]) ." como falecido(a)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);

			$linha_morte= "situacao_pessoa = '". $_POST["situacao_pessoa"] ."', ";
			
			$result1= mysql_query("update pessoas set id_cidade ='". $_POST["id_cidade"] ."',
														nome='". strip_tags(strtoupper($_POST["nome"])) ."',
														sexo='". $_POST["sexo"] ."',
														". $linha_cpf ."
														nome_mae= '". strtoupper($_POST["nome_mae"]) ."',
														rg='". strip_tags($_POST["rg"]) ."',
														
														id_profissao='". strip_tags($_POST["id_profissao"]) ."',
														estado_civil='". strip_tags($_POST["estado_civil"]) ."',
														trabalha='". strip_tags($_POST["trabalha"]) ."',
														
														orgao_emissor_rg='". strip_tags($_POST["orgao_emissor_rg"]) ."',
														endereco='". strip_tags(strtoupper($_POST["endereco"])) ."',
														bairro='". strip_tags(strtoupper($_POST["bairro"])) ."',
														complemento='". strip_tags(strtoupper($_POST["complemento"])) ."',
														cep='". strip_tags($_POST["cep"]) ."',
														telefone='". strip_tags($_POST["telefone"]) ."',
														data_nasc='". $data_nasc ."',
														". $linha_morte ."
														observacoes='". strip_tags($_POST["observacoes"]) ."'
														where id_pessoa ='". $_POST["id_pessoa"] ."' ") or die("Erro: ". mysql_error());
			if (!$result1) $var++;
			else @logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "atualiza o cadastro de ID ". $_POST["id_pessoa"] ." | ". pega_nome($_POST["id_pessoa"]), $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			
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
			echo "CPF já cadastrado, o mesmo pertence a(à) <b>". pega_nome_pelo_cpf($_POST["cpf_cadastro"]) ."</b>";
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
			$result1= mysql_query("insert into pessoas (id_responsavel, id_cidade, nome, sexo, cpf, nome_mae, rg, orgao_emissor_rg, id_profissao, estado_civil, trabalha, endereco, bairro, complemento, cep, telefone, data_nasc, cartao_sus, data_cadastro, origem_cadastro, id_origem_cadastro, id_psf, observacoes, id_usuario)
									values ('". $_POST["id_responsavel"] ."', '". $_POST["id_cidade"] ."', '". strip_tags(strtoupper($_POST["nome"])) ."', '". $_POST["sexo"] ."',
										'". $_POST["cpf_cadastro"] ."', '". strtoupper($_POST["nome_mae"]) ."', '". strip_tags($_POST["rg"]) ."', '". strip_tags($_POST["orgao_emissor_rg"]) ."',
										
										'". strip_tags(strtoupper($_POST["id_profissao"])) ."', '". strip_tags(strtoupper($_POST["estado_civil"])) ."', '". strip_tags(strtoupper($_POST["trabalha"])) ."',
										
										'". strip_tags(strtoupper($_POST["endereco"])) ."', '". strip_tags(strtoupper($_POST["bairro"])) ."',
										'". strtoupper($_POST["complemento"]) ."', '". strip_tags($_POST["cep"]) ."', '". strip_tags($_POST["telefone"]) ."', '$data_nasc', '". strip_tags($_POST["cartao_sus"]) ."',
										'". date("YmdHis") ."', '$origem_cadastro', '$id_origem_cadastro', '". $_POST["id_psf"] ."', '". $_POST["observacoes"] ."', '". $_SESSION["id_usuario_sessao"] ."') ")
										or die("Erro: ". mysql_error());
		if (!$result1) $var++;
		else {
			$id_pessoa= mysql_insert_id();
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "cadastra pessoa ID ". $id_pessoa ." | ". $_POST["nome"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
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
		
		if ($_POST["retorno"]=="familia_inserir") {
			echo "preencheDiv('cpf_usuario_atualiza". $amais . $_GET["campo_retorno"] ."', '<input id=id_pessoa_dep class=escondido type=hidden value=". $id_pessoa ." name=id_pessoa_dep /><input id=id_pessoa_mesmo class=escondido type=hidden value=". $id_pessoa ." name=id_pessoa />". strip_tags(strtoupper($_POST["nome"])) ."<br /><label>&nbsp;</label><a onclick=cadastraDependente(2); href=javascript:void(0);>cadastrar dependente</a> | <a onclick=editaDadosPessoais(2); href=javascript:void(0);>editar dados</a><br/>');
									";	
		}
		
		echo "</script>";
		
	}
	else
		echo "Não foi possível cadastrar, tecle F5 para tentar novamente!";
}

if (isset($_GET["formPessoaBuscar"])) {
	@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "busca pessoa | ". $_POST["txt_busca"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	$pagina= "_pessoas/pessoa_listar";
	require_once("index3.php");
}

/* --------------------------------------------- SOCIAL ------------------------------------------------------- */

if (pode_algum("z", $_SESSION["permissao"])) {

	if (isset($_GET["formFamiliaInserir"])) {
		if (($_POST["id_microarea"]!="") && ($_POST["id_pessoa"]!="")) {

			$var= 0;
			inicia_transacao();
						
			$result1= mysql_query("insert into familias
									(id_cidade, num_familia, id_microarea, id_religiao, endereco, status_familia, id_usuario)
									 values
									('$id_cidade_emula', '". $_POST["num_familia"] ."', '". $_POST["id_microarea"] ."',
										'". $_POST["id_religiao"] ."',
									'". strtoupper($_POST["endereco"]) ."', '1', '". $_SESSION["id_usuario_sessao"] ."'
								     )
									") or die("erro 1 ".mysql_error());
			if (!$result1) $var++;
			
			$id_familia= mysql_insert_id();
			
			$result4= mysql_query("insert into familias_pessoas (id_familia, id_pessoa, parentesco, tipo)
							values
							('". $id_familia ."', '". $_POST["id_pessoa"] ."', '1', '1')
							") or die("erro 2 ".mysql_error());
			if (!$result4) $var++;
			
			$i= 0;
			while ($_POST["nome_membro"][$i]!="") {
				$result5= mysql_query("insert into pessoas (id_responsavel, id_cidade, nome, sexo,
															data_nasc, data_cadastro, origem_cadastro, id_origem_cadastro,
															situacao_pessoa, id_usuario)
															values
															('". $_POST["id_pessoa"] ."', '". $id_cidade_emula ."',
															'". strtoupper($_POST["nome_membro"][$i]) ."', '". $_POST["sexo_membro"][$i] ."',
															
															'". formata_data($_POST["data_nasc_membro"][$i]) ."', '". date("YmdHis") ."',
															'p', '". $_SESSION["id_posto_sessao"] ."', '1', '". $_SESSION["id_usuario_sessao"] ."'
															)
															") or die("erro 2 ".mysql_error());
				$id_pessoa_aqui= mysql_insert_id();
				
				if (!$result5) $var++;
				else {
					$result6= mysql_query("insert into familias_pessoas (id_familia, id_pessoa, parentesco)
											values
											('". $id_familia ."', '". $id_pessoa_aqui ."', '". $_POST["parentesco_membro"][$i] ."')
											") or die("erro 2 ".mysql_error());
					if (!$result6) $var++;
				}
				
				$i++;
			}
		}
		
		finaliza_transacao($var);
		
		if ($var==0) @logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere nova família ID ". $id_familia, $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		
		$msg= $var;
		
		$pagina= "_social/arrecadacao";
		require_once("index3.php");
	}

	if (isset($_GET["formFamiliaEditar"])) {
		if ($_POST["id_microarea"]!="") {

			$var= 0;
			inicia_transacao();
						
			$result1= mysql_query("update familias set
									num_familia= '". $_POST["num_familia"] ."',
									id_microarea= '". $_POST["id_microarea"] ."',
									id_religiao= '". $_POST["id_religiao"] ."',
									endereco= '". strtoupper($_POST["endereco"]) ."'
									where id_familia = '". $_POST["id_familia"] ."'

									") or die("erro 1 ".mysql_error());
			if (!$result1) $var++;
			
			$result4= mysql_query("update familias_pessoas set
									id_pessoa = '". $_POST["id_pessoa"] ."'
									where id_familia = '". $_POST["id_familia"] ."'
									and   tipo = '1'
									") or die("erro 2 ".mysql_error());
			if (!$result4) $var++;

			
		}
		
		finaliza_transacao($var);
		
		if ($var==0) @logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "edita família ". $_POST["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		
		$msg= $var;
		
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formFamiliaBuscar"])) {
		@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "busca família | ". $_POST["nome"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		$pagina= "_social/familia_listar";
		require_once("index3.php");
	}
	
	if (isset($_GET["formArrecadacaoMensal"])) {
		@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "busca relatório de arredadacao | ". $_POST["periodo"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		$pagina= "_social/arrecadacao_relatorio";
		require_once("index3.php");
	}
	
}

if (pode_algum("r", $_SESSION["permissao"])) {
	if (isset($_GET["formArrecadacao"])) {
		if ($_POST["id_familia"]!="") {
			$var= 0;
			
			for ($i=0; $i<count($_POST["valor"]); $i++) {
				
				$result_del= mysql_query("delete from arrecadacoes
											where id_familia = '". $_POST["id_familia"] ."'
											and   ano = '". $_POST["ano"][$i] ."'
											and   mes= '". $_POST["mes"][$i] ."'
											");
				
				if (($_POST["valor"][$i]!="") && ($_POST["valor"][$i]!="0,00")) {
					$result= mysql_query("insert into arrecadacoes (id_posto, id_cidade, id_familia, mes, ano, valor, id_usuario)
											values
											('". $_SESSION["id_posto_sessao"] ."', '". $id_cidade_emula ."',
											'". $_POST["id_familia"] ."', '". $_POST["mes"][$i] ."', '". $_POST["ano"][$i] ."',
											'". formata_valor($_POST["valor"][$i]) ."', '". $_SESSION["id_usuario_sessao"] ."')
											");
					if (!$result) $var++;
				}
			}
		}
		$msg= $var;
		
		if ($var==0)
			@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "salva dados de arrecadação da família ". $_POST["id_familia"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
				
		$pagina= "_social/arrecadacao";
		require_once("index3.php");
	}
}

echo '</body></html>';

?>