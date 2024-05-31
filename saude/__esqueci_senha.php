<?
if (isset($_POST["acao"])) {
	//se o sincronize token está correto...
	if ( ($_SESSION["acao"] == $_SESSION["acao"]) ) {
		require_once("conexao.php");
		$email= addslashes(str_replace("'", "x", str_replace('"', 'x', $_POST["email"])));

		$result= mysql_query("select  id_pessoa, nome
								from  pessoas
								where email= '$email'
								") or die("Erro no login: ". mysql_error());
		
		//email nao encontrado
		if (mysql_num_rows($result)==0)
			header("location: index2.php?pagina=esqueci_senha&erro=s1");
		else {
			$rs= mysql_fetch_object($result);
			
			$result2= mysql_query("select * from usuarios
									where id_pessoa = '". $rs->id_pessoa ."'
									");
			if (mysql_num_rows($result2)==1) {
				$rs2= mysql_fetch_object($result2);
				
				if ($rs2->senha_sem_enc!="") {
					$corpo= "<strong>". data_extenso() ."</strong>
						<br /><br />
						Olá <b>". $rs->nome ."</b>, você ou alguém com o IP <strong>". $_SERVER['REMOTE_ADDR'] ."</strong> solicitou a recuperação de senha no Lisbeta Saúde.
						<br /><br />
						Sua senha no sistema é: <strong>". $rs2->senha_sem_enc ."</strong>
						<br /><br />
						Acesse em: <a href=\"http://www.lisbeta.com.br/saude/\" target=\"_blank\">http://www.lisbeta.com.br/saude</a>.
						<br /><br />
						------ <br />
						Atenciosamente,
						<br /><br />
						Lisbeta Saúde<br />
						<a href=\"http://www.lisbeta.com.br/saude\">http://www.lisbeta.com.br/saude</a>
						";
					//echo $corpo;
					$enviar= @enviar_email($email, "Lisbeta Saúde | Recuperação de senha", $corpo);
					@logs(0, $rs2->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "esqueceu a senha (senha atual enviada)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					//senha atual enviada por email					
					if ($enviar) header("location: index2.php?pagina=esqueci_senha&erro=n1");
					else  header("location: index2.php?pagina=esqueci_senha&erro=n1&naofoi");
					
				}
				else {
					$senha_criada= strtolower(substr(gera_auth(), 0, 6));
					
					$result2= mysql_query("update usuarios
											set senha= '". md5($senha_criada) ."',
											senha_sem_enc= '". $senha_criada ."'
											where id_usuario = '". $rs2->id_usuario ."'
											") or die("3 ". mysql_error());
					
					$corpo= "<strong>". data_extenso() ."</strong>
						<br /><br />
						Olá <b>". $rs->nome ."</b>, você ou alguém com o IP <strong>". $_SERVER['REMOTE_ADDR'] ."</strong> solicitou a recuperação de senha no Lisbeta Saúde.
						<br /><br />
						Sua <strong>nova</strong> senha no sistema é: <strong>". $senha_criada ."</strong> (para alterá-la, faça o login e clique no botão \"Trocar senha\").
						<br /><br />
						Acesse em: <a href=\"http://www.lisbeta.com.br/saude/\" target=\"_blank\">http://www.lisbeta.com.br/saude</a>.
						<br /><br />
						------ <br />
						Atenciosamente,
						<br /><br />
						Lisbeta Saúde<br />
						<a href=\"http://www.lisbeta.com.br/saude\">http://www.lisbeta.com.br/saude</a>
						";
					
					$enviar= @enviar_email($email, "Lisbeta Saúde | Nova senha", $corpo);
					@logs(0, $rs2->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "esqueceu a senha (nova senha criada e enviada)", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					//criada nova senha e enviada por email
					if ($enviar) header("location: index2.php?pagina=esqueci_senha&erro=n2");
					else header("location: index2.php?pagina=esqueci_senha&erro=n2&naofoi");
				}
			}
			//usuario nao encontrado (bugzera)
			else
				header("location: index2.php?pagina=esqueci_senha&erro=s2");
		}
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

<body onLoad="daFoco('email');">

	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	<div id="pagina_login">
		<div id="logo_login"> <!--Logo lisbeta --></div>
		<div id="formulario_login">
			<? if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE')) { ?>
			<h2>Atenção</h2>
			<p>Você precisa acessar o sistema com o navegador <a href="http://www.getfirefox.com/" target="_blank">Firefox</a>.</p>
			
			<p>Se já estiver instalado, vá até Iniciar > Todos os programas > Mozilla > Mozilla Firefox.</p>
            
            <p>Caso não, baixe e instale o Firefox:</p>
            
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
			<h2>Recuperação de senha</h2>
            
            <form action="index2.php?pagina=esqueci_senha" method="post" name="formRecupera" id="formRecupera" onSubmit="return validaForm('formRecupera');">
				
				<input type="hidden" name="acao" id="acao" value="<?= $_SESSION["acao"]; ?>" class="escondido" />
				
				<label for="email">E-mail:</label>
				<input name="email" id="email" />
				<br />
		
				<label for="enviar">&nbsp;</label>
				<button id="enviar" type="submit">Enviar</button>
				<br /><br /><br /><br />
				
			</form>
            
            <br /><br /><br />
            <label>&nbsp;</label>
			<a href="index2.php?pagina=login">« voltar para o login</a>
			<? } ?>
            
		</div>
	</div>
<?
if ($_GET["erro"]!="") {
	switch ($_GET["erro"]) {
		case "s1": $msg= "E-mail não encontrado no sistema!"; break;
		case "s2": $msg= "Usuário não encontrado no sistema!"; break;
		case "n1": $msg= "Sua senha foi enviada para seu e-mail!"; break;
		case "n2": $msg= "Foi criada uma <strong>nova</strong> senha e enviada por e-mail!"; break;
	}
?>
<script language="javascript" type="text/javascript">alert('<?= $msg; ?>');</script>
<? } ?>

</body>
</html>
<? } ?>