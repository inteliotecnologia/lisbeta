<?
$pagina= $_GET["pagina"];
echo $pagina; die();

if (strpos($pagina, "/")) {
	$parte= explode("/", $pagina);
	include($parte[0] ."/". "__". $parte[1] .".php");
}
else
	include("__". $pagina .".php");
?>