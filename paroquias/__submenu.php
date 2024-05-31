<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<div id="submenu">
	<?
	//if ( ($pagina=="pessoa_listar") || () )
	if (isset($_GET["pagina"]))
		$paginar= $_GET["pagina"];
	else
		$paginar= $pagina;
		
	if (strstr($paginar, "/")) {
		$parte= explode("/", $paginar);
		$pagex= $parte[1];
	}
	else
		$pagex= $pagina;
	?>

	<ul>
		<?
		switch ($pagex) {
			case 'pessoa_listar':
			case 'pessoa_ver':
			case 'pessoa_relatorio':
				$pessoas= true;
		?>
		<li><a href="javascript:void(0);" onclick="abreCadastroSo();">cadastrar pessoa</a></li>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar');">buscar pessoas</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_relatorio');">relatório de cadastros</a></li>
        <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar&amp;todos=1');">listar pessoas</a></li>
        <? } ?>
		<?
			break;
			case 'posto_listar':
			?>
			<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/usuarioc_inserir&amp;id_cidade=<?= $id_cidade; ?>');">vincular usuário</a></li>
			<?
			case 'entradas':
			case 'logs':
			case 'cidade_listar':
			case 'usuariop_listar':
			case 'usuario_listar':
				$pasta= "_acesso";
				$acesso= true;
				
			if ($_SESSION["tipo_usuario_sessao"]=="a") {
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/logs');">log</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/entradas');">entradas/saídas</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/cidade_listar');">listar cidades</a></li>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=<?= $pasta; ?>/usuario_inserir');">inserir usuário</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/usuario_listar');">listar usuários</a></li>
		<?
			}
		break;
		case 'familia_listar':
		case 'familia_inserir':
		case 'familia_editar':
		case 'familia_resumo':
		
		case 'membros':
		case 'arrecadacao':
		
		case 'arrecadacao_relatorio':
		case 'relatorio_familias':
		
			$pasta= "_social";
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/familia_inserir');">cadastrar família</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/familia_listar');">listar famílias</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_familias');">busca</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_familias');">relatório</a></li>
		<?
		break;
		case 'manual':
		case 'sobre':
		case 'contato':
			$pasta= "_ajuda";
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/manual');">manual do sistema</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/sobre');">sobre</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/contato');">entre em contato</a></li>
		<?
		break;
		} ?>
	</ul>
</div>

<div id="tela_relatorio">
</div>

<? if ($pessoas) { ?>
<div id="tela_cadastro">
</div>
<? } ?>

<? if ($acesso) { ?>
<div id="pessoa_buscar" class="escondido">
<?
include("_pessoas/__pessoa_buscar.php");
?>
</div>
<? } ?>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>