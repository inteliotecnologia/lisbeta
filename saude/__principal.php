<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<h2 class="titulos">Página principal</h2>

<p>Olá <strong><?= $nome_pessoa_sessao; ?></strong>, seja bem vindo(a) ao <em><? include("titulo.php"); ?></em>.</p>

<p>Você está designado às seguintes funções:</p>

<ol class="recuo1">
	<? if (pode("s", $_SESSION["permissao"])) { ?>
    <li>Relatórios do Secretário de Saúde;</li>
	<? } ?>
	<? if (pode("f", $_SESSION["permissao"])) { ?>
    <li>Farmácia;</li>
	<? } ?>
    <? if (pode("x", $_SESSION["permissao"])) { ?>
    <li>Almoxarifado;</li>
	<? } ?>
    <? if (pode("r", $_SESSION["permissao"])) { ?>
    <li>Atendimento;</li>
	<? } ?>
    <? if (pode("c", $_SESSION["permissao"])) { ?>
    <li>Consulta médica;</li>
	<? } ?>
    <? if (pode("i", $_SESSION["permissao"])) { ?>
    <li>Auxiliar médico;</li>
	<? } ?>
    <? if (pode("e", $_SESSION["permissao"])) { ?>
    <li>Consulta de enfermagem;</li>
	<? } ?>
    <? if (pode("m", $_SESSION["permissao"])) { ?>
    <li>Auxiliar de enfermagem;</li>
	<? } ?>
    <? if (pode("o", $_SESSION["permissao"])) { ?>
    <li>Consulta odontológica;</li>
	<? } ?>
    <? if (pode("n", $_SESSION["permissao"])) { ?>
    <li>Auxiliar odontológico;</li>
	<? } ?>
    <? if (pode("p", $_SESSION["permissao"])) { ?>
    <li>Produção;</li>
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
    <li>Cadastro de remédios;</li>
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