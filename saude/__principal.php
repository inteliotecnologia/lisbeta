<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<h2 class="titulos">P�gina principal</h2>

<p>Ol� <strong><?= $nome_pessoa_sessao; ?></strong>, seja bem vindo(a) ao <em><? include("titulo.php"); ?></em>.</p>

<p>Voc� est� designado �s seguintes fun��es:</p>

<ol class="recuo1">
	<? if (pode("s", $_SESSION["permissao"])) { ?>
    <li>Relat�rios do Secret�rio de Sa�de;</li>
	<? } ?>
	<? if (pode("f", $_SESSION["permissao"])) { ?>
    <li>Farm�cia;</li>
	<? } ?>
    <? if (pode("x", $_SESSION["permissao"])) { ?>
    <li>Almoxarifado;</li>
	<? } ?>
    <? if (pode("r", $_SESSION["permissao"])) { ?>
    <li>Atendimento;</li>
	<? } ?>
    <? if (pode("c", $_SESSION["permissao"])) { ?>
    <li>Consulta m�dica;</li>
	<? } ?>
    <? if (pode("i", $_SESSION["permissao"])) { ?>
    <li>Auxiliar m�dico;</li>
	<? } ?>
    <? if (pode("e", $_SESSION["permissao"])) { ?>
    <li>Consulta de enfermagem;</li>
	<? } ?>
    <? if (pode("m", $_SESSION["permissao"])) { ?>
    <li>Auxiliar de enfermagem;</li>
	<? } ?>
    <? if (pode("o", $_SESSION["permissao"])) { ?>
    <li>Consulta odontol�gica;</li>
	<? } ?>
    <? if (pode("n", $_SESSION["permissao"])) { ?>
    <li>Auxiliar odontol�gico;</li>
	<? } ?>
    <? if (pode("p", $_SESSION["permissao"])) { ?>
    <li>Produ��o;</li>
	<? } ?>
    <? if (pode("t", $_SESSION["permissao"])) { ?>
    <li>TFD;</li>
	<? } ?>
    <? if (pode("v", $_SESSION["permissao"])) { ?>
    <li>Vacinas;</li>
	<? } ?>
    <? if (pode("l", $_SESSION["permissao"])) { ?>
    <li>Social;</li>
	<? } ?>
    <? if (pode("!", $_SESSION["permissao"])) { ?>
    <li>Cadastro de rem�dios;</li>
	<? } ?>
    <? if (pode("@", $_SESSION["permissao"])) { ?>
    <li>Cadastro de exames;</li>
	<? } ?>
</ol>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>