<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<h2 class="titulos">P�gina principal</h2>

<p>Ol� <strong><?= $nome_pessoa_sessao; ?></strong>, seja bem vindo(a) ao <em><? include("titulo.php"); ?></em>.</p>

<p>Voc� est� designado �s seguintes fun��es:</p>

<ol class="recuo1">
    <? if (pode("z", $_SESSION["permissao"])) { ?>
    <li>Cadastro de fam�lias;</li>
	<? } ?>
    <? if (pode("r", $_SESSION["permissao"])) { ?>
    <li>Arrecada��o.</li>
	<? } ?>
</ol>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>