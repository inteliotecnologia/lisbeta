<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<h2 class="titulos">Página principal</h2>

<p>Olá <strong><?= $nome_pessoa_sessao; ?></strong>, seja bem vindo(a) ao <em><? include("titulo.php"); ?></em>.</p>

<p>Você está designado às seguintes funções:</p>

<ol class="recuo1">
    <? if (pode("z", $_SESSION["permissao"])) { ?>
    <li>Cadastro de famílias;</li>
	<? } ?>
    <? if (pode("r", $_SESSION["permissao"])) { ?>
    <li>Arrecadação.</li>
	<? } ?>
</ol>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>