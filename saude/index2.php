<?php

//$pagina=$_GET[pagina];
//extract($_REQUEST,EXTR_SKIP);
//echo 2; die();

//foreach($_GET as $__k => $__v) $$__k = $__v;
//foreach($_POST as $__i => $__x) $$__i = $__x;

if ($pagina=='')
	$pagina= $_GET["pagina"];

if (strpos($pagina, "/")) {
	$parte= explode("/", $pagina);
	include($parte[0] ."/". "__". $parte[1] .".php");
}
else {
	include("__". $pagina .".php");
}
?>