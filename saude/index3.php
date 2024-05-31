<?
extract($_REQUEST,EXTR_SKIP);
include("__submenu.php");

if (isset($_GET["pagina"]))
	$paginar= $_GET["pagina"];
else
	$paginar= $pagina;
	
if (strpos($paginar, "/")) {
	$parte= explode("/", $paginar);
	include($parte[0] ."/". "__". $parte[1] .".php");
}
else
	include("__". $paginar .".php");
?>