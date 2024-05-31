<?
session_start();

$conf_host="localhost";
$conf_usuario="root";
//$conf_senha="LXc7115A";
//$conf_db="sige_db";

$conf_senha="";
$conf_db="sige_db";


$conexao= @mysql_connect($conf_host, $conf_usuario, $conf_senha) or die("O servidor está um pouco instável, favor tente novamente! ". mysql_error());
@mysql_select_db($conf_db) or die("O servidor está um pouco instável, favor tente novamente!! ". mysql_error());

define("AJAX_LINK", "link.php?");
define("AJAX_FORM", "form.php?");
define("CAMINHO", "./imgs/");


//if ($_GET["pagina"]=="")
//	header("location: index2.php?pagina=login");

//se a pagina atual nao for a de login

//echo $_GET["pagina"];
?>