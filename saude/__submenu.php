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
        
        <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_relatorio');">relatório de cadastros</a></li>
        <? } ?>
        
        <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar&amp;todos=1');">listar pessoas</a></li>
        <? } ?>
		<?
			break;
			case 'consulta_listar':
			case 'fila_listar':
			case 'consulta_inserir': 
			case 'consulta_ver': 
			case 'consulta_receita_ver': 
			case 'consulta_relatorio': 
			
			case 'agenda_listar': 
			case 'agenda_inserir': 
			
			case 'prontuario':
				$pasta= "_consultas";
		?>
        <? if (!pode("o", $_SESSION["permissao"])) { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/prontuario');">pronto-atendimento</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/fila_listar');">listar fila de espera</a></li>
        <? } ?>
        
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/agenda_inserir');">marcar consulta</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/agenda_listar');">listar agendamentos</a></li>
        
        <!--<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/consulta_listar');">listar todas</a></li>-->
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/consulta_relatorio');">relatório</a></li>
		<?
			break;
			case 'proc_inserir':
			case 'proc_listar':
			case 'proc_relatorio': 
				$pasta= "_proc";
		?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/proc_inserir');">inserir procedimento(s)</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/proc_listar');">listar procedimentos</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/proc_relatorio');">relatório</a></li>
		<?
			break;
			case 'remedio_listar':
			case 'apelido_listar':
			case 'exame_listar':
			case 'material_listar':
			case 'veiculo_listar':
			case 'motorista_listar':
				$acesso= true;
		?>
		<? /*<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_remedios/remedio_inserir');">inserir</a></li> */ ?>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_remedios/remedio_listar');">remédios</a></li>
		<? /*<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_exames/exame_inserir');">inserir</a></li> */ ?>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_exames/exame_listar');">exames</a></li>
		<? /*<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_materiais/material_inserir');">inserir</a></li> */ ?>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_materiais/material_listar');">materiais</a></li>
		<? /*<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_veiculos/veiculo_inserir');">inserir</a></li> */ ?>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_veiculos/veiculo_listar');">veículos</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_veiculos/motorista_listar');">motoristas</a></li>
		<?
			break;
			case 'producao':
			case 'producao_status':
			case 'ssa2_inserir':
			case 'pma2_inserir':
			case 'bpa_inserir':
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/producao');">produção do mês</a></li>
		<? if ($_SESSION["id_posto_sessao"]!="") { ?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/ssa2_inserir');">SSA2</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/pma2_inserir');">PMA2</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/bpa_inserir');">BPA</a></li>
		<? } ?>
		<? if (($_SESSION["id_cidade_sessao"]!="") && (date("d")>=20)) { ?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_producao/producao_status');">alterar situação</a></li>
		<? } ?>
		<?
			break;
			case 'posto_listar':
			?>
			<li><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/usuarioc_inserir&amp;id_cidade=<?= $id_cidade; ?>');">vincular usuário</a></li>
			<?
			case 'entradas':
			case 'cidade_listar':
			case 'usuariop_listar':
			case 'usuario_listar':
				$pasta= "_acesso";
				$acesso= true;
		?>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/entradas');">entradas/saídas</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/cidade_listar');">listar cidades</a></li>
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=<?= $pasta; ?>/usuario_inserir');">inserir usuário</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/usuario_listar');">listar usuários</a></li>
		<?
		break;
		case 'entrada_inserir':
		case 'saida_inserir':
		case 'movp_inserir':
		case 'dist_inserir':
		case 'movc_listar':
		case 'movp_listar':
		case 'peri':
		case 'estoque_listar':
		case 'mov_extrado':
		case 'mov_listar':
		case 'extrato':
		case 'estorno':
		case 'relatorio_mov':
		case 'relatorio_mov_real':
		
		case 'relatorio_stats':
		case 'consumo_mensal':
		case 'consumo_mensal_remedio':
		case 'balanco_farmacia':
		case 'relacao_pessoas_remedios':
		
			$pasta= "_farmacia";
		if (!eh_secretario($_SESSION["permissao"])) {
			if ($_SESSION["id_cidade_sessao"]!="") {
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/entrada_inserir');">entrada</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/movp_inserir');">movimentação</a></li>
		<? } ?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/saida_inserir');">saída</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/dist_inserir');">receita</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/peri');">saída por pessoa</a></li>
		<? } ?>
		<!--<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'distInserir');">distribuição p/ pessoas</a></li>-->
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/estoque_listar');">estoque</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_mov');">relatório de movimentação</a></li>
        <? if ($_SESSION["id_cidade_sessao"]!="") { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_stats');">relatórios diversos</a></li>
        <? } ?>
		<?
		break;
		case 'entradam_inserir':
		case 'saidam_inserir':
		case 'movpm_inserir':
		case 'distm_inserir':
		case 'movcm_listar':
		case 'movm_listar':
		case 'extratom':
		case 'movpm_listar':
		case 'estoquem_listar':
		case 'relatoriom_mov':
		
		case 'relatoriom_stats':
		case 'consumom_mensal':
		case 'consumom_mensal_material':
		
			$pasta= "_almox";
		if (!eh_secretario($_SESSION["permissao"])) {
			if ($_SESSION["id_cidade_sessao"]!="") {
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/entradam_inserir');">dar entrada</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/movpm_inserir');">movimentação p/ postos</a></li>
        <? } ?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/saidam_inserir');">dar saida</a></li>
		<? } ?>
		<!--<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'distInserir');">distribuição p/ pessoas</a></li>-->
		<li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/estoquem_listar');">listar estoque</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatoriom_mov');">relatório de movimentação</a></li>
        <? if ($_SESSION["id_cidade_sessao"]!="") { ?>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatoriom_stats');">estatísticas</a></li>
        <? } ?>
		<?
			break;
			case 'tfd_solicitacao_listar':
			case 'tfd_solicitacao_inserir':
			case 'tfd_solicitacao_ver':
			case 'tfd_listar':
			case 'tfd_inserir':
			case 'tfd_ver':
			case 'tfd_resumo_ver':
			case 'relatorio_tfd_solicitacao':
			case 'relatorio_tfd':
				$pasta= "_tfd";
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/tfd_solicitacao_inserir');">nova solicitação</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/tfd_solicitacao_listar');">listar solicitações</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_tfd_solicitacao');">relatório de solicitações</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/tfd_inserir');">nova tfd</a></li>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/tfd_listar');">listar tfd's</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_tfd');">relatório de tfd's</a></li>
		<?
		break;
			case 'familia_listar':
			case 'familia_inserir':
			case 'familia_editar':
			case 'familia_resumo':
			
			case 'membros':
			case 'parecer':
			case 'visitas':
			case 'assistencias':
			
			case 'relatorio_familias':
			
				$pasta= "_social";
		?>
		<li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/familia_inserir');">cadastrar família</a></li>
        <li class="espaco_dir"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/familia_listar');">listar famílias</a></li>
        <li><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $pasta; ?>/relatorio_familias');">busca de família</a></li>
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