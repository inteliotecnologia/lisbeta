<?
if (isset($_POST["acao"])) {
	//se o sincronize token está correto...
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
		
		//echo $linhas; die();
		
		//se nao retornou nada do sql, redireciona informando a msg de erro
		if ($linhas==0)
			header("location: index2.php?pagina=login&erro=s1");
		//se voltou, vamos tratar as coisas
		else {
			$rs= mysql_fetch_object($result);
			
			session_start();
			
			//grava as 3 variaveis de identificacao
			$_SESSION["id_usuario_sessao"]= $rs->id_usuario;
			$_SESSION["tipo_usuario_sessao"]= $rs->tipo_usuario;
			$_SESSION["nome_pessoa_sessao"]= pega_nome($rs->id_pessoa);
			$_SESSION["id_sistema"]= ID_SISTEMA;
			
			
			//se é medico ou enfermeiro (vinculo a posto)
			if ( ($rs->tipo_usuario=='p') ) {
				//se esta vinculado a mais de um posto, redireciona para a pagina de escolha do posto atual
				if (atende_em_x_postos($rs->id_usuario) > 1)
					header("location: index2.php?pagina=login_pos");
				//se está somente em um posto, salva na var de sessao direto e redireciona pro sistema
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
						
						$_SESSION["id_cbo_sessao"]= pega_cbo_usuario('p', $id_posto, $rs->id_usuario);
						
						grava_acesso($rs->id_usuario, $id_posto, '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						
						header("location: ./");
					}//fim cidade/posto ativo
					else
						header("location: index2.php?pagina=login&erro=s2");
				}
			}
			else {
				if ( ($rs->tipo_usuario=='c') ) {
					//se esta vinculado a mais de uma cidade, redireciona para a pagina de escolha da cidade atual
					if (esta_vinculado_a_x_cidades($rs->id_usuario) > 1)
						header("location: index2.php?pagina=login_pos");
					//se está somente em uma cidade, salvar na var de sessao direto e redireciona pro sistema
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
							
							grava_acesso($rs->id_usuario, '', $id_cidade, 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
							header("location: ./");	
						}//fim cidade ativa
						else
							header("location: index2.php?pagina=login&erro=s2");
					}	
				}
				else {
					if ($rs->tipo_usuario=='a') {
						$_SESSION["permissao"]= "www";
						$_SESSION["id_uf_pref"]= 24;
						
						//echo "u2: ". $_SESSION["id_usuario_sessao"]; die();
						
						grava_acesso($rs->id_usuario, '', '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
						header("location: ./");	
					}
					else
						die("System failure...");
				}
			}
			
		}//fim else linhas
	} else echo "!";
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

<body onLoad="daFoco('usuario');">

	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	<div id="pagina_login">
		<div id="logo_login"> <!--Logo lisbeta --></div>
		<div id="formulario_login">
			<? if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE')) { ?>
			<h2>Atenção</h2>
			<p>Você precisa acessar o sistema com o navegador <a href="http://www.getfirefox.com/" target="_blank">Firefox</a>.</p>
			
			<p>Para isso vá até Iniciar > Todos os programas > Mozilla > Mozilla Firefox</p>
			<? } else { ?>
			<form action="index2.php?pagina=login" method="post" name="formLogin" id="formLogin" onSubmit="return validaLogin(formLogin);">
			<h2>Acesso restrito</h2>
				
				<input type="hidden" name="acao" id="acao" value="<?= $_SESSION["acao"]; ?>" class="escondido" />
				
				<label for="usuario">Usuário:</label>
				<input name="usuario" id="usuario" />
				<br />
		
				<label for="senha">Senha:</label>
				<input  type="password" name="senha" id="senha" />
				<br />
		
				<label for="enviar">&nbsp;</label>
				<button id="enviar" type="submit">Enviar</button>
				<br /><br />
				
				<label>&nbsp;</label><span class="vermelho">&nbsp;
				<?
				if ($erro=="s1") echo "Usuário ou senha inválidos!";
				else {
					if ($erro=="s2") echo "O acesso de sua cidade ao sistema foi temporariamente <strong>cancelado</strong>.<br /> Entre em contato com <strong>Jaison Niehues</strong> no telefone <strong>(11) 8114-9400</strong> para regularizar!";
				}
				?>
			</span>
			</form>
			<!--<a href="index2.php?pagina=esqueci_senha">Esqueci a minha senha</a>-->
			<? } ?>
		</div>
	</div>
	
	<? /*<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	_uacct = "UA-801754-7";
	urchinTracker();
	</script> */ ?>
</body>
</html>
<? } ?>