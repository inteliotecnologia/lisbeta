<?
extract($_REQUEST,EXTR_SKIP);
//die("Entre em contato (48) 9985-3850 para regularizar a situaчуo do sistema.");
if (strpos($pagina, "/")) {
	$parte= explode("/", $pagina);
	include($parte[0] ."/". "__". $parte[1] .".php");
}
else
	include("__". $pagina .".php");
?>