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
	if ((pode_algum("zl", $_SESSION["permissao"])) ) {
		if (pode("z", $_SESSION["permissao"]))
			$titulo_menu= "Famílias";
		else
			$titulo_menu= "Social";
	?>
	<li id="menu_social" <? if ($pagina=="_social/familia_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_social'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_listar');" accesskey="c" title="<?= $titulo_menu; ?>"><?= $titulo_menu; ?></a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_posto_sessao"]!="") && (pode("r", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_consulta" <? if ($pagina=="_consultas/fila_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_consulta'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/agenda_listar');" accesskey="c" title="Consultas">Consultas</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_posto_sessao"]!="") && (pode_algum("ceim", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_acomp" <? if ($pagina=="_acomp/teste") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_acomp'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acomp/acomp_listar');" accesskey="a" title="Acompanhamento">Acompanhamento</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_posto_sessao"]!="") && (pode("d", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_procedimentos" <? if ($pagina=="_proc/proc_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_procedimentos'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_proc/proc_listar');" accesskey="p" title="Procedimentos">Procedimentos</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("x", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_almoxarifadom" <? if ($pagina=="_almox/estoquem_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_almoxarifadom'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_almox/estoquem_listar');" accesskey="x" title="Controle do almoxarifado de materiais">Almoxarifado</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("f", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_almoxarifado" <? if ($pagina=="_farmacia/estoque_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_almoxarifado'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/estoque_listar');" accesskey="x" title="Controle da farmácia">Farmácia</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("t", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_tfd" <? if ($pagina=="_tfd/tfd_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_tfd'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_listar');" accesskey="t" title="Transporte fora de domicílio">TFD's</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( (($_SESSION["id_posto_sessao"]!="") || ($_SESSION["id_cidade_sessao"]!="")) && (pode("p", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_producao" <? if ($pagina=="_producao/producao") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_producao'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/producao');" accesskey="p" title="Produção">Produção</a>
		<div class="menu_dir"></div>
	</li>
	<?
	}
	if ( ($_SESSION["id_cidade_sessao"]!="") && (pode("s", $_SESSION["permissao"])) ) {
	?>
	<li id="menu_relatorios" <? if ($pagina=="_relatorios/relatorio_resumo") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_relatorios'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_relatorios/relatorio_resumo');" accesskey="t" title="Relatórios">Relatórios</a>
		<div class="menu_dir"></div>
	</li>
	<?
    }
	if (pode_algum("!@", $_SESSION["permissao"])) {
	?>
	<li id="menu_cadastros">
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_cadastros'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_remedios/remedio_listar');" accesskey="m" title="Cadastros">Cadastros</a>
		<div class="menu_dir"></div>
	</li>
    <? } ?>
    <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
	<li id="menu_acessos" <? if ($pagina=="_acesso/cidade_listar") echo "class=\"atual\""; ?>>
		<div class="menu_esq"></div>
		<a href="javascript:void(0);" onclick="atribuiAtual('menu_acessos'); ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/cidade_listar');" accesskey="a" title="Acesso">Acessos</a>
		<div class="menu_dir"></div>
	</li>
	<? } ?>
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