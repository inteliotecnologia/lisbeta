<?
require_once("conexao.php");
//isso tudo é pra segurança, caso algum engraçadinho queira manipular os valores pela url! :P

//testar se realmente é pra ele tar nessa pagina, soh pode estar se o tipo é compativel com a restricao
//de estar em mais de uma cidade ou de um posto
if ( ($_SESSION["tipo_usuario_sessao"]=='p') ) {
	if (!atende_em_x_postos($_SESSION["id_usuario_sessao"]) > 1)
		die("Morreu no m e e!");
}
else {
	if ( ($_SESSION["tipo_usuario_sessao"]=='c') ) {
		if (!esta_vinculado_a_x_cidades($_SESSION["id_usuario_sessao"]) > 1)
			die("Morreu no s e c!");
	}
	else
		die("Acesso não autorizado!");
}

if ( (isset($_GET["id_posto"])) && (($_SESSION["tipo_usuario_sessao"]=='p'))) {
	if (atende_neste_posto($_SESSION["id_usuario_sessao"], $_GET["id_posto"])) {
		$_SESSION["id_posto_sessao"]= $_GET["id_posto"];
		
		$id_cidade_pref= pega_id_cidade_do_posto($id_posto);
		$_SESSION["id_cidade_pref"]= $id_cidade_pref;
		$id_uf_pref= pega_id_uf($id_cidade_pref);
		$_SESSION["id_uf_pref"]= $id_uf_pref;
		
		$_SESSION["permissao"]= pega_permissao('p', $_GET["id_posto"], $_SESSION["id_usuario_sessao"]);
		$_SESSION["id_cbo_sessao"]= pega_cbo_usuario('p', $_GET["id_posto"], $_SESSION["id_usuario_sessao"]);
		
		$_SESSION["id_acesso"]= grava_acesso($_SESSION["id_usuario_sessao"], $_GET["id_posto"], '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "loga-se no posto através da segunda tela", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
		
		header("location: ./");
	}
	else
		die("Acesso não autorizado para este posto!");
}
else {
	if ((isset($_GET["id_cidade"])) && (($_SESSION["tipo_usuario_sessao"]=='c'))) {
		if (vinculado_a_esta_cidade($_SESSION["id_usuario_sessao"], $_GET["id_cidade"])) {
			$_SESSION["id_cidade_sessao"]= $_GET["id_cidade"];
			$_SESSION["id_cidade_pref"]= $_GET["id_cidade"];
			
			$id_uf_pref= pega_id_uf($_GET["id_cidade"]);
			$_SESSION["id_uf_pref"]= $id_uf_pref;
			
			$_SESSION["permissao"]= pega_permissao("c", $_GET["id_cidade"], $_SESSION["id_usuario_sessao"]);
			$_SESSION["id_cbo_sessao"]= pega_cbo_usuario('c', $_GET["id_cidade"], $_SESSION["id_usuario_sessao"]);
			
			$_SESSION["id_acesso"]= grava_acesso($_SESSION["id_usuario_sessao"], $_GET["id_posto"], '', 'e', $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "loga-se na cidade através da segunda tela", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			
			header("location: ./");
		}
		else
			die("Acesso não autorizado para esta cidade!");
	}
	else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><? include("titulo.php"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilo.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<noscript>
	  <meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>
	
	<div id="pagina_login">
	
		<div id="logo_login"> <!--Logo lisbeta --></div>
		<div id="formulario_login">
			<h2>Escolha o local onde está agora:</h2>
			
			<ul class="recuo1">
				<?
				if ( ($_SESSION["tipo_usuario_sessao"]=='p') ) {
					@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], "está na tela para escolher o posto", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					$result= mysql_query("select postos.id_posto, postos.posto, cidades.cidade, ufs.uf
											from  usuarios, usuarios_postos, postos, cidades, ufs
											where usuarios.id_usuario = '". $_SESSION["id_usuario_sessao"] ."'
											and   usuarios.id_usuario = usuarios_postos.id_usuario
											and   postos.id_posto = usuarios_postos.id_posto
											and   postos.id_cidade = cidades.id_cidade
											and   cidades.id_uf = ufs.id_uf
											") or die(mysql_error());

					while($rs= mysql_fetch_object($result)) {
					?>
					<li><a href="index2.php?pagina=login_pos&amp;id_posto=<?= $rs->id_posto ?>"><?= $rs->posto ." (". $rs->cidade ."/". $rs->uf .")"; ?></a></li>
					<?
					}//fim while
				}//fim if
				if ( ($_SESSION["tipo_usuario_sessao"]=='c') ) {
					@logs($_SESSION["id_acesso"], $rs->id_usuario, $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], "está na tela para escolher a cidade", $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
					$result= mysql_query("select cidades.id_cidade, cidades.cidade, ufs.uf
											from  usuarios, usuarios_cidades, cidades, ufs
											where usuarios.id_usuario = '". $_SESSION["id_usuario_sessao"] ."'
											and   usuarios.id_usuario = usuarios_cidades.id_usuario
											and   usuarios_cidades.id_cidade = cidades.id_cidade
											and   cidades.id_uf = ufs.id_uf
											") or die(mysql_error());

					while($rs= mysql_fetch_object($result)) {
					?>
					<li><a href="index2.php?pagina=login_pos&amp;id_cidade=<?= $rs->id_cidade ?>"><?= $rs->cidade ."/". $rs->uf; ?></a></li>
					<?
					}//fim while
				}//fim if
				?>
			</ul>
			
			<a href="javascript:void(0);" onClick="javascript:history.back(-1);">&lt;&lt; voltar</a>
		</div>
	</div>
</body>
</html>
<? } } ?>