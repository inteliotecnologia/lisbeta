
<? if ($_SESSION["id_usuario"]!="") { ?>

<ul id="menu_principal">
    <? if ($_SESSION["tipo_usuario"]=="a") { ?>
    <li id="menu1" class="menu_vertical"><a class="linkzao" href="javascript:void(0);">Administrativo</a>
        <ul id="nav" class="menu">
            <li class="submenu"><a href="javascript:void(0);">Empresas</a>
                <ul>
                    <li class="sem_submenu"><a href="./?pagina=financeiro/pessoa&amp;tipo_pessoa=a&amp;acao=i">Inserir</a></li>
                    <li class="sem_submenu"><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=a">Listar todas</a></li>
                </ul>
            </li>
            <li class="sem_submenu"><a href="./?pagina=acesso/usuario_listar">Usuários</a></li>
            <li class="sem_submenu"><a href="./?pagina=acesso/acessos">Acessos</a></li>
            <li class="sem_submenu"><a href="index2.php?pagina=acesso/backup" target="_blank" onclick="return confirm('Tem certeza que deseja fazer um backup agora?');">Fazer backup</a>
            <? /*
            <li class="submenu">
            	<a href="javascript:void(0);">Backup</a>
            	<ul>
                    <li class="sem_submenu"><a href="index2.php?pagina=acesso/backup" target="_blank" onclick="return confirm('Tem certeza que deseja fazer um backup agora?');">Fazer backup</a>
                    <li class="sem_submenu"><a href="./includes/backup/backups/" target="_blank">Salvar backup</a></li>
                </ul>
            </li>*/ ?>
        </ul>
    </li>
    <? } ?>
   	
   	<?
    if ($_SESSION["id_empresa"]!="") {
		if (pode('rv4w', $_SESSION["permissao"])) {
	?>
    <li id="menu3" class="menu_vertical"><a class="linkzao" href="javascript:void(0);">R. Humanos</a>
        <ul id="nav" class="menu">
        	<? if (pode('rv4', $_SESSION["permissao"])) { ?>
            <li class="submenu"><a href="javascript:void(0);">Funcionários</a>
                <ul>
                    <? if (pode('rv', $_SESSION["permissao"])) { ?>
                    <li class="sem_submenu"><a href="./?pagina=rh/funcionario&amp;acao=i">Inserir</a></li>
                    <? } ?>
                    
						<? if (pode('rv4', $_SESSION["permissao"])) { ?>
                        <li class="submenu"><a href="./?pagina=rh/funcionario_listar&amp;status_funcionario=1">Listar todos</a>
                            <ul>
                                <li class="sem_submenu"><a href="./?pagina=rh/funcionario_listar&amp;status_funcionario=1">Ativos</a>
                                <li class="sem_submenu"><a href="./?pagina=rh/funcionario_listar&amp;status_funcionario=0">Inativos</a></li>
                                <li class="sem_submenu"><a href="./?pagina=rh/funcionario_listar&amp;status_funcionario=-1">Em espera</a></li>
                                <!--<li class="sem_submenu"><a href="./?pagina=rh/funcionario_listar&amp;temp=1">Ilhados</a></li>-->
                            </ul>
                        </li>
                        <? } ?>
                    <? if (pode('r', $_SESSION["permissao"])) { ?>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Histórico interno</a>
                        <ul>
                            <li class="sem_submenu"><a href="./?pagina=rh/historico&amp;acao=i">Inserir</a></li>
							<li class="sem_submenu"><a href="./?pagina=rh/historico_listar">Listar todos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/historico_busca">Buscar</a></li>
                        </ul>
                    </li>
                    
                    <li class="submenu"><a href="javascript:void(0);">Afastamentos</a>
                        <ul>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=a">Atestados</a></li>
							<li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=p">Perícias</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=f">Férias</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=o">Outros abonos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=d">Advertências</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=s">Suspensões</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/afastamento_listar&amp;tipo_afastamento=b">Abandono</a></li>
                        </ul>
                    </li>
                    <? } ?>
                </ul>	
            </li>
            <? } ?>
            <? if (pode('r', $_SESSION["permissao"])) { ?>
            <li class="submenu"><a href="javascript:void(0);">Cadastros</a>
                <ul>
                    <li class="sem_submenu"><a href="./?pagina=rh/departamento_listar">Departamentos</a></li>
                    <li class="sem_submenu"><a href="./?pagina=rh/equipe_listar">Equipes</a></li>
                    <li class="sem_submenu"><a href="./?pagina=rh/turno_listar">Turnos</a></li>
                    <li class="sem_submenu"><a href="./?pagina=rh/cargo_listar">Cargos</a></li>
                    <li class="sem_submenu"><a href="./?pagina=rh/feriado_listar">Feriados</a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);">Treinamentos</a>
                        <ul>
                            <li class="sem_submenu"><a href="./?pagina=rh/treinamento_listar&amp;tipo_treinamento=1">Internos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/treinamento_listar&amp;tipo_treinamento=2">Externos</a></li>
                        </ul>
                        
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">Vale-transporte</a>
                        <ul>
                            <li class="sem_submenu"><a href="./?pagina=rh/vt_linha_listar">Linhas</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">Motivos</a>
                        <ul>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=o">Outros abonos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=s">Suspensões</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=d">Advertências</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=p">Alteração no ponto</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=t">Descontos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/motivo_listar&amp;tipo_motivo=r">Refeições</a></li>
                        </ul>
                    </li>
                </ul>
        	</li>
            <? } ?>
            <? if (pode('rw4', $_SESSION["permissao"])) { ?>
            <li class="submenu"><a href="javascript:void(0);">Relatórios</a>
                <ul>
                    <? if (pode('r', $_SESSION["permissao"])) { ?>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Funcionários</a>
                    	<ul>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_resumido_relatorio">Lista resumida</a></li>
                            <li class="submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_resumido_relatorio2">Lista p/ empresa</a>
                            <ul>
	                            <?
	                            $result= mysql_query("select * from rh_departamentos
							where id_empresa = '". $_SESSION["id_empresa"] ."' 
							order by departamento asc
							");
								while ($rs= mysql_fetch_object($result)) {
	                            ?>
	                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_resumido_relatorio2&amp;id_departamento=<?=$rs->id_departamento;?>"><?=$rs->departamento;?></a></li>
	                            <? } ?>
	                        </ul>
                            </li>
                            
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/contrato_relatorio_voluntarios">Contratos (voluntários)</a></li>
                            <li class="sem_submenu"><a target="_blank" onclick="return perguntaDatas();" href="index2.php?pagina=rh/recibos_relatorio_voluntarios">Recibos (voluntários)</a></li>
                            
                            <li class="sem_submenu"><a href="./?pagina=rh/funcionario_situacao_busca">Ativos/inativos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/idade_busca">Por faixa de idade</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_bairro_relatorio">Por bairro</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_camiseta_relatorio">Por tamanho da camiseta</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/ferias_busca">F&eacute;rias</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/aniversariantes_busca">Aniversariantes</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cpf_relatorio">Chamada (externos)</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cpf_relatorio2">Chamada 2 (ordem alf.)</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cpf_relatorio2&amp;tipo=1">Chamada 3 (por bairro)</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cpf_relatorio3&amp;tipo=1">Chamada 4 (por equipe)</a></li>
                            
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_listar_excel">Chamada 5 (excel)</a></li>
                            
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cpf_relatorio3&amp;tipo=2">Chamada 6 (por equipe voluntário)</a></li>
                            
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=rh/funcionario_cargo_relatorio">Nome/Cargo</a></li>
                        </ul>
                    </li>
                	<li class="sem_submenu"><a href="./?pagina=rh/afastamento_busca">Afastamentos por período</a></li>
                    <li class="sem_submenu"><a href="./?pagina=rh/atestado_busca">Atestados (quantitativo)</a></li>
                    
                    <? } ?>
                    
                    <? if (pode('r', $_SESSION["permissao"])) { ?>
                    <li class="sem_submenu"><a href="./?pagina=rh/vt_busca">Vale-transporte</a></li>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Turnos</a>
                        <ul>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=rh/turno_relatorio">Relação de horários</a></li>
                            <li class="sem_submenu"><a href="./?pagina=rh/turno_resumido_busca">Listagem atual</a></li>
                        </ul>
                    </li>
                    <? } ?>
                </ul>
        	</li>
            <? } ?>
        </ul>
    </li>
    <? } //fim pode rh ?>
    
    <?
    if (pode('2', $_SESSION["permissao"])) {
    ?>
    
    <li id="menu4" class="menu_vertical"><a class="linkzao" href="./?pagina=financeiro/pessoa_listar&tipo_pessoa=x">Base de dados</a>
        <ul id="nav" class="menu">
            <li><a href="./?pagina=financeiro/pessoa&tipo_pessoa=x&acao=i">Inserir</a></li>
            <li><a href="./?pagina=financeiro/pessoa_listar&tipo_pessoa=x">Listar todos</a></li>
        </ul>
    </li>
    
    <? } ?>
    
	<?
	/*
    if ($_SESSION["id_empresa"]!="") {
		if (pode('t)', $_SESSION["permissao"])) {
	?>
    <li id="menu5" class="menu_vertical"><a class="linkzao" href="javascript:void(0);">Contatos</a>
        <ul id="nav" class="menu">
           <? if (pode('t', $_SESSION["permissao"])) { ?>
           <li class="submenu">
               <a href="javascript:void(0);">Agenda de telefones</a>
               <ul>
                    <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=1&amp;letra=a">Fornecedores</a></li>
                    
                    <li class="submenu">
                    	<a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=2&amp;letra=a&amp;status_funcionario=1">Funcionários</a>
                    	<ul>
                            <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=2&amp;letra=a&amp;status_funcionario=1">Ativos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=2&amp;letra=a&amp;status_funcionario=0">Inativos</a></li>
                            <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=2&amp;letra=a&amp;status_funcionario=-2">Outros</a></li>
                        </ul>
                    </li>
                    
                    <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=4&amp;letra=a">Clientes</a></li>	
                    <li class="sem_submenu"><a href="./?pagina=contatos/contato_esquema&amp;tipo_contato=3&amp;letra=a">Outros</a></li>	
                </ul>
           </li>
           <li class="sem_submenu"><a href="./?pagina=contatos/ligacao_listar">Controle de ligações</a></li>
           <li class="submenu">
               <a href="javascript:void(0);">Relatório</a>
               <ul>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Fornecedores</a>
                        <ul>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=1">Geral</a></li>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=1&amp;rel=s">Para supervisores</a></li>	
                        </ul>
                    </li>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Clientes</a>
                        <ul>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=4">Geral</a></li>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=4&amp;rel=s">Para supervisores</a></li>	
                        </ul>
                    </li>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Funcionários</a>
                        <ul>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=2&amp;status_funcionario=1">Ativos</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=2&amp;status_funcionario=0">Inativos</a></li>
                            <li class="sem_submenu"><a target="_blank" href="index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=2&amp;status_funcionario=-2">Outros</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                    	<a href="javascript:void(0);">Outros</a>
                        <ul>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=3">Geral</a></li>
                            <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=3&amp;rel=s">Para supervisores</a></li>	
                        </ul>
                    </li>
                </ul>
           </li>
           <? } ?>
           <? if (pode(')', $_SESSION["permissao"])) { ?>
           <li class="submenu">
               <a href="javascript:void(0);">Relatório</a>
               <ul>
                    <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=1&amp;rel=s">Fornecedores</a></li>	
                    <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=4&amp;rel=s">Clientes</a></li>	
                    <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=2&amp;rel=s">Funcionários</a></li>	
                    <li class="sem_submenu"><a target="_blank" href="./index2.php?pagina=contatos/contato_relatorio&amp;tipo_contato=3&amp;rel=s">Outros</a></li>	
                </ul>
           </li>
           <? } ?>
        </ul>
    </li>
    <? } } */ ?>
    
	
</ul>
<? } ?>

<script language="javascript" type="text/javascript">
	shortcut.add("Alt+Q",function() { window.top.location.href="./index2.php?pagina=logout"; });
</script>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>