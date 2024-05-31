<?
session_start();

$conf_host="localhost";
$conf_usuario="mcomite55";

$conf_senha="une8e4ate";
$conf_db="zadmin_comite55";

$conexao= @mysql_connect($conf_host, $conf_usuario, $conf_senha) or die("O servidor est‡ um pouco instável, favor tente novamente! ". mysql_error());
@mysql_select_db($conf_db) or die("O servidor está um pouco instável, favor tente novamente!! ". mysql_error());

define("AJAX_LINK", "link.php?");
define("AJAX_FORM", "form.php?");
define("CAMINHO", "uploads/");
define("VERSAO", "Todos os direitos reservados");

setlocale(LC_CTYPE, "pt_BR");

//if ($_GET["pagina"]=="")
//	header("location: index2.php?pagina=login");

//se a pagina atual nao for a de login

//echo $_GET["pagina"];

if (($_GET["pagina"]!="login") && ($_GET["pagina"]!="login_turno") && ($_GET["pagina"]!="ad_ativa") && ($_GET["pagina"]!="ponto/padrao") && ($_GET["pagina"]!="ponto/abre")) {
	$retorno= true;
	if ($_SESSION["id_usuario"]=="")
		$retorno= false;
	
	if (!$retorno)
		header("location: index2.php?pagina=login&redireciona");
}
?>