<?
if (isset($_POST["acao"])) {
	//se o sincronize token est� correto...
	if ( ($_SESSION["acao"] == $_SESSION["acao"]) ) {
		require_once("conexao.php");
		$usuario= addslashes(str_replace("'", "x", str_replace('"', 'x', $_POST["usuario"])));
		$senha= addslashes(str_replace("'", "x", str_replace('"', 'x', $_POST["senha"])));

		$result= mysql_query("select  id_usuario, id_pessoa, tipo_usuario
								from  usuarios
								where usuario= '$usuario'
								and   senha= '". md5($senha) ."'
								and   situacao = '1'
								") or die("Erro no login: ". mysql_error());
								
		$linhas= mysql_num_rows($result);
		
		//se nao retornou nada do sql, redireciona informando a msg de erro
		if ($linhas==0)
			header("location: index2.php?pagina=login&erro=s1");
		//se voltou, vamos tratar as coisas
		else {
			$rs= mysql_fetch_object($result);

			//grava as 3 variaveis de identificacao
			$_SESSION["id_usuario_sessao"]= $rs->id_usuario;
			$_SESSION["tipo_usuario_sessao"]= $rs->tipo_usuario;
			$_SESSION["nome_pessoa_sessao"]= pega_nome($rs->id_pessoa);
			$_SESSION["id_sistema"]= ID_SISTEMA;
			
			//se � medico ou enfermeiro (vinculo a posto)
			if ( ($rs->tipo_usuario=='p') ) {
				//se esta vinculado a mais de um posto, redireciona para a pagina de escolha do posto atual
				if (atende_em_x_postos($rs->id_usuario) > 1)
					header("location: index2.php?pagina=login_pos");
				//se est� somente em um posto, salva na var de sessao direto e redireciona pro sistema
				else {
					$id_posto= atende_no_posto($rs->id_usuario);
					$id_cidade= pega_id_cidade_do_posto($id_posto);
					
					//se estao ativas a cidade e o posto
					if ( (cidade_esta_ativa($id_cidade)) && (posto_esta_ativo($id_posto)) ) {
						$_SESSION["id_posto_sessao"]= $id_posto;
						
						$id_cidade_pref= $id_cidade;
						$_SESSION["id_cidade_pref"]= $id_cidade_pref;
						
						$id_uf_pref= pega_id_uf($id_cidade_pref);
						$_SESSION["id_uf_pref"]= $id_uf_pref;
						
						$_SESSION["permissao"]= pega_permissao('p', $id_posto, $rs->id_usuario);
						
						$_SESSION["id_acesso"]= grava_acesso($rs->id_usuario, $id_posto, '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						
						@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "logou-se (atende em um s� posto)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						
						header("location: ./");
					}//fim cidade/posto ativo
					else
						header("location: index2.php?pagina=login&erro=s1");
				}
			}
			else {
				if ( ($rs->tipo_usuario=='c') ) {
					//se esta vinculado a mais de uma cidade, redireciona para a pagina de escolha da cidade atual
					if (esta_vinculado_a_x_cidades($rs->id_usuario) > 1)
						header("location: index2.php?pagina=login_pos");
					//se est� somente em uma cidade, salvar na var de sessao direto e redireciona pro sistema
					else {
						$id_cidade= esta_vinculado_a_cidade($rs->id_usuario);
						
						if (cidade_esta_ativa($id_cidade)) {
							$_SESSION["id_cidade_sessao"]= $id_cidade;
							
							$id_cidade_pref= $id_cidade;
							$_SESSION["id_cidade_pref"]= $id_cidade_pref;
						
							$id_uf_pref= pega_id_uf($id_cidade_pref);
							$_SESSION["id_uf_pref"]= $id_uf_pref;
							
							$permissao= pega_permissao("c", $id_cidade, $rs->id_usuario);
							
							$_SESSION["permissao"]= $permissao;
							$_SESSION["id_cbo_sessao"]= pega_cbo_usuario('c', $id_cidade, $rs->id_usuario);
							
							$_SESSION["id_acesso"]= grava_acesso($rs->id_usuario, '', $id_cidade, 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
							@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "logou-se (est� em uma s� cidade)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
							
							header("location: ./");	
						}//fim cidade ativa
						else
							header("location: index2.php?pagina=login&erro=s1");
					}	
				}
				else {
					if ($rs->tipo_usuario=='a') {
						$_SESSION["permissao"]= "www";
						$_SESSION["id_uf_pref"]= 24;
						
						$_SESSION["id_acesso"]= grava_acesso($rs->id_usuario, '', '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "logou-se (administrador)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						header("location: ./");	
					}
					else
						die("System failure... System failure... System failure... System failure... System failure... System failure...");
				}
			}
			
		}//fim else linhas
	}
}
else {
	session_start();
	
	@session_unregister("id_usuario_sessao");
	@session_unregister("tipo_usuario_sessao");
	@session_unregister("nome_pessoa_sessao");
	@session_unregister("id_posto_sessao");
	@session_unregister("id_cidade_sessao");
	@session_unregister("id_uf_sessao");
	@session_unregister("permissao");
	@session_unregister("trocando");
	@session_unregister("id_sistema");
	
	$_SESSION["acao"]= md5("asd". rand(1, 30) . time());
	
	if (isset($redireciona))
		echo
		"
		<script language='javascript' type='text/javascript'>
			window.top.location.href='index2.php?pagina=login';
		</script>
		";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><? include("titulo.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="estilo.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="images/icone.png" />

<script language="javascript" type="text/javascript" src="js/validacoes.js"></script>

</head>

<body>

	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	<div id="pagina_login">
		<div id="logo_login"> <!--Logo lisbeta --></div>
		<div id="formulario_login">
			<? if ( (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) && (strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE')) || ($_GET["teste"]==1) ) { ?>
			<h2>Aten��o</h2>
			<p>Voc� precisa acessar o sistema com o navegador <a href="http://www.getfirefox.com/" target="_blank">Firefox</a>.</p>
			
			<p>Se j� estiver instalado, v� at� Iniciar > Todos os programas > Mozilla > Mozilla Firefox.</p>
            
            <p>Caso n�o, baixe e instale o Firefox:</p>
            
            <center>
				<script type="text/javascript"><!--
                    google_ad_client = "pub-2620894510514104";
                    google_ad_width = 120;
                    google_ad_height = 60;
                    google_ad_format = "120x60_as_rimg";
                    google_cpa_choice = "CAAQr6_8zwEaCMbSCvtHdc5MKKm-93MwAA";
                    //-->
                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>
            </center>
            
			<? } else { ?>
            <h2>Identifique-se para acessar</h2>
            
			<form action="index2.php?pagina=login" method="post" name="formLogin" id="formLogin" onSubmit="return validaLogin(formLogin);">
				
				<input type="hidden" name="acao" id="acao" value="<?= $_SESSION["acao"]; ?>" class="escondido" />
				
				<label for="usuario">Usu�rio:</label>
				<input name="usuario" id="usuario" />
				<br />
		
				<label for="senha">Senha:</label>
				<input  type="password" name="senha" id="senha" />
				<br />
		
				<label for="enviar">&nbsp;</label>
				<button id="enviar" type="submit">Enviar</button>
				<br /><br />
				
			</form>
            <br /><br /><br />
            <label>&nbsp;</label>
			Esqueceu a senha? <a href="index2.php?pagina=esqueci_senha">Clique aqui</a> para recuper�-la.
			<? } ?>
            
		</div>
	</div>
	
	<? /*<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-801754-7";
	urchinTracker();
	</script> */ ?>
<?
if ($_GET["erro"]!="") {
	switch ($_GET["erro"]) {
		case "s1": $msg= "Usu�rio ou senha inv�lidos!"; break;
	}
?>
<script language="javascript" type="text/javascript">alert('<?= $msg; ?>');</script>
<? } ?>
</body>
</html>
<? } ?>