<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if (isset($msg)) {
	if ($msg==0)
		echo "<div class=\"atencao\">Opera��o realizada com sucesso!</div>";
	else
		echo "<div class=\"atencao2\">N�o foi poss�vel completar a opera��o!</div>";
}
?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>