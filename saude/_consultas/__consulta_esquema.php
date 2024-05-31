<?
if (@pode("r", $_SESSION["permissao"])) {
?>

<h2 class="titulos screen">Documentos de consulta nº <?= $_GET["id_consulta"]; ?></h2>

<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_editar&amp;id_consulta=<?= $_GET["id_consulta"]; ?>');" id="botao_voltar">&lt;&lt; editar dados da consulta</a>

<div class="div_abas screen" id="aba_consultas">
    <ul class="abas">
        <li id="aba_consultas_receita" class="atual"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_receita'); ajaxLink('conteudo_interno', 'carregaPaginaInterna&amp;pagina=_consultas/consulta_receita_ver&amp;id_consulta=<?= $id_consulta; ?>');">Receita</a></li>
        <li id="aba_consultas_receita_especial"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_receita_especial'); ajaxLink('conteudo_interno', 'carregaPaginaInterna&amp;pagina=_consultas/consulta_receita_especial_ver&amp;id_consulta=<?= $id_consulta; ?>');">Receita especial</a></li>
        <li id="aba_consultas_exames"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_exames'); ajaxLink('conteudo_interno', 'carregaPaginaInterna&amp;pagina=_consultas/consulta_exame_ver&amp;id_consulta=<?= $id_consulta; ?>');">Exames</a></li>
        <li id="aba_consultas_atestado"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_atestado'); ajaxLink('conteudo_interno', 'carregaPaginaInterna&amp;pagina=_consultas/atestado_ver&amp;id_consulta=<?= $id_consulta; ?>');">Atestado</a></li>
        <li id="aba_consultas_comparecimento"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_comparecimento'); ajaxLink('conteudo_interno', 'carregaPaginaInterna&amp;pagina=_consultas/comparecimento_ver&amp;id_consulta=<?= $id_consulta; ?>');">Declaração de comparecimento</a></li>
    </ul>
</div>

<div id="conteudo_interno" class="documentozinho">
	<? require_once("_consultas/__consulta_receita_ver.php"); ?>
</div>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>