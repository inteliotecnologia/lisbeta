<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if (isset($msg)) {
	if ($msg==0)
		echo "<div class=\"atencao\">Operação realizada com sucesso!</div>";
	else
		echo "<div class=\"atencao2\">Não foi possível completar a operação!</div>";
}
?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>