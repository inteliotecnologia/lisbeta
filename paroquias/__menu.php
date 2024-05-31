<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<div id="infos_mesmo">
    
	<? if ($_SESSION["tipo_usuario_sessao"]=="abc") { ?>
    <div id="temp">
        <strong>id_usuario_sessao:</strong> <?= $_SESSION["id_usuario_sessao"]; ?> <br />
        <strong>tipo_usuario_sessao:</strong> <?= $_SESSION["tipo_usuario_sessao"]; ?> <br />
        <strong>id_posto_sessao:</strong> <?= $_SESSION["id_posto_sessao"]; ?> <br />
        <strong>id_cidade_sessao:</strong> <?= $_SESSION["id_cidade_sessao"]; ?> <br />
        <strong>id_cidade_pref:</strong> <?= $_SESSION["id_cidade_pref"]; ?> <br />
        <strong>id_uf_pref:</strong> <?= $_SESSION["id_uf_pref"]; ?> <br />
        <strong>nome_pessoa_sessao:</strong> <?= $_SESSION["nome_pessoa_sessao"]; ?> <br />
        <strong>permissao:</strong> <?= $_SESSION["permissao"]; ?> <br />
        <strong>id_cbo:</strong> <?= $_SESSION["id_cbo_sessao"]; ?> <br />
        <strong>trocando:</strong> <?= $_SESSION["trocando"]; ?>
    </div>
	<? } ?>
    
    <? if ($_SESSION["tipo_usuario_sessao"]!="a") echo "<br />"; ?>
    
	<strong>Usuário(a):</strong> <?= $_SESSION["nome_pessoa_sessao"]; ?> <br />
    <? if ($_SESSION["id_cbo_sessao"]!="") { ?>
    <strong>CBO:</strong> <span onmouseover="Tip('<?= pega_nome_cbo($_SESSION["id_cbo_sessao"]); ?>');"><?= pega_cbo($_SESSION["id_cbo_sessao"]); ?></span> <br />
	<? } ?>
	<?
	if ($_SESSION["id_usuario_sessao"]!="") { 
		if ($_SESSION["id_posto_sessao"]!="") {
	?>
	<strong>Posto:</strong> <?= pega_posto($_SESSION["id_posto_sessao"]); ?> <br />
	<? } else {
			if ($_SESSION["id_cidade_sessao"]!="") {
	?>
	<strong>Cidade:</strong> <?= pega_cidade($_SESSION["id_cidade_sessao"]); ?> <br />
	<? } } ?>
    <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
	<strong>Status:</strong> <?= pega_tipo_usuario($_SESSION["tipo_usuario_sessao"]); ?>
	<? } } ?>
</div>

<ul>
	<li id="menu_principal" <? if ($pagina=="principal") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_principal'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=principal');" accesskey="r" title="Principal">Principal</a>
		<div class="menu_dir"></div>
	</li>
	<?
	if ( ($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="") ) {
	?>
	<li id="menu_pessoas" <? if ($pagina=="_pessoas/pessoa_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_pessoas'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar');" accesskey="s" title="Lista de pessoas">Pessoas</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_posto_sessao"]!="") && ((pode_algum("z", $_SESSION["permissao"])) ) ) {
	?>
	<li id="menu_social" <? if ($pagina=="_social/familia_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_social'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_listar');" accesskey="c" title="Famílias">Famílias</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	/*if (pode_algum("!@", $_SESSION["permissao"])) {
	?>
	<li id="menu_cadastros">
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_cadastros'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_remedios/remedio_listar');" accesskey="m" title="Cadastros">Cadastros</a>
		<div class="menu_dir"></div>
	</li>
    <? }*/ ?>
    <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
	<li id="menu_acessos" <? if ($pagina=="_acesso/cidade_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_acessos'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/cidade_listar');" accesskey="a" title="Acesso">Acessos</a>
		<div class="menu_dir"></div>
	</li>
	<?
    }
	else {
		if ( ($_SESSION["id_posto_sessao"]!="") && ((pode_algum("m", $_SESSION["permissao"])) ) ) {
	?>
    <li id="menu_acessos" <? if ($pagina=="_acesso/usuariop_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_acessos'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/usuariop_listar');" accesskey="a" title="Missionários">Missionários</a>
		<div class="menu_dir"></div>
	</li>
    <? } } ?>
	<li id="menu_ajuda">
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_ajuda'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_ajuda/contato');" accesskey="j" title="Ajuda">Ajuda</a>
		<div class="menu_dir"></div>
	</li>
</ul>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>