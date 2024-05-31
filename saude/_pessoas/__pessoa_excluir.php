<?
if ($_SESSION["id_usuario_sessao"]!="") {
	if ($_SESSION["id_cidade_sessao"]!="") $id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else $id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	
	$result= mysql_query("select pessoas.*, DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc, ufs.id_uf from pessoas, cidades, ufs
								where pessoas.id_pessoa = '". $_GET["id_pessoa"] ."'
								and   pessoas.id_cidade = cidades.id_cidade
								and   cidades.id_uf = ufs.id_uf
								");
	$rs= mysql_fetch_object($result);
	/*
	if ($rs->origem=="c") $id_cidade_cadastro= $rs->id_origem;
	else $id_cidade_cadastro= pega_id_cidade_do_posto($rs->id_origem);
	
	if (($id_cidade_emula!=$rs->id_cidade) && ($id_cidade_cadastro!=$id_cidade_emula)) $trancar= true;
	else $trancar= false;
	
	if ($trancar) die("Permissão negada!");*/
	
	$_SESSION["auth_temp"]= gera_auth();
?>
    
    <div id="pessoa_buscar" class="escondido">
		<?
        include("_pessoas/__pessoa_buscar.php");
        ?>
    </div>
    
    <h2 class="titulos" id="tit_pessoa_excluir">Exclusão de cadastro</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('tela_aux_rapida');" class="fechar">x</a>
	
	<div id="formulario">
		
		<form action="<?= AJAX_FORM; ?>formPessoaExcluir" id="formPessoaExcluir" name="formPessoaExcluir" method="post" onsubmit="return ajaxForm('conteudo', 'formPessoaExcluir');">
			
			<input name="id_pessoa_excluir" id="id_pessoa_excluir" type="hidden" value="<?= $rs->id_pessoa; ?>" class="escondido" />
			<input name="acao" id="acao" type="hidden" value="<?= $_SESSION["auth_temp"]; ?>" class="escondido" />
			
			<label>ID:</label>
			<?= $rs->id_pessoa; ?>
            <br />
            
            <label>Nome:</label>
			<?= $rs->nome; ?>
            <br />
            
            <label>CPF:</label>
			<?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?>
            <br />
            
            <?
			$result_gru= mysql_query("select id from acomp_grupos_pessoas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_gru= mysql_num_rows($result_gru);
			if ($linhas_gru>0) $tem_dados=1;
			$status .= "Grupos de acompanhamento: ". sim_nao($linhas_gru) ." <br /> ";
			
			$result_acomp= mysql_query("select id_acompanhamento from acompanhamento where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_acomp= mysql_num_rows($result_acomp);
			if ($linhas_acomp>0) $tem_dados=1;
			$status .= "Acompanhamento: ". sim_nao($linhas_acomp) ." <br /> ";
			
			$result_age= mysql_query("select id_agenda from agenda_consultas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_age= mysql_num_rows($result_age);
			if ($linhas_age>0) $tem_dados=1;
			$status .= "Agendamento: ". sim_nao($linhas_age) ." <br /> ";
			
			$result_med= mysql_query("select id_mov from almoxarifado_mov where id_receptor = '". $rs->id_pessoa ."' ");
			$linhas_med= mysql_num_rows($result_med);
			if ($linhas_med>0) $tem_dados=1;
			$status .= "Histórico de remédios: ". sim_nao($linhas_med) ." <br /> ";
			
			$result_mat= mysql_query("select id_mov from almoxarifadom_mov where id_receptor = '". $rs->id_pessoa ."' ");
			$linhas_mat= mysql_num_rows($result_mat);
			if ($linhas_mat>0) $tem_dados=1;
			$status .= "Histórico de materiais: ". sim_nao($linhas_mat) ." <br /> ";
			
			$result_con= mysql_query("select id_consulta from consultas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_con= mysql_num_rows($result_con);
			if ($linhas_con>0) $tem_dados=1;
			$status .= "Histórico de consultas: ". sim_nao($linhas_con) ." <br /> ";
			
			$result_fam= mysql_query("select id_pessoa from familias_pessoas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_fam= mysql_num_rows($result_fam);
			if ($linhas_fam>0) $tem_dados=1;
			$status .= "Cadastro em família: ". sim_nao($linhas_fam) ." <br /> ";

			$result_fil= mysql_query("select id_pessoa from filas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_fil= mysql_num_rows($result_fil);
			if ($linhas_fil>0) $tem_dados=1;
			$status .= "Fila de espera: ". sim_nao($linhas_fil) ." <br /> ";
			
			$result_soc= mysql_query("select id_pessoa from pessoas_se where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_soc= mysql_num_rows($result_soc);
			if ($linhas_soc>0) $tem_dados=1;
			$status .= "Cadastro social: ". sim_nao($linhas_soc) ." <br /> ";
			
			$result_per= mysql_query("select id_pessoa from pessoas_remedios where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_per= mysql_num_rows($result_per);
			if ($linhas_per>0) $tem_dados=1;
			$status .= "Medicamentos periódicos: ". sim_nao($linhas_per) ." <br /> ";
			
			$result_pro= mysql_query("select id_pessoa from procedimentos where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_pro= mysql_num_rows($result_pro);
			if ($linhas_pro>0) $tem_dados=1;
			$status .= "Procedimento: ". sim_nao($linhas_pro) ." <br /> ";
			
			$result_tfd= mysql_query("select id_pessoa from tfds_pessoas where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_tfd= mysql_num_rows($result_tfd);
			if ($linhas_tfd>0) $tem_dados=1;
			$status .= "Em TFD: ". sim_nao($linhas_tfd) ." <br /> ";
			
			$result_tfds= mysql_query("select id_pessoa from tfds_solicitacoes where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_tfds= mysql_num_rows($result_tfds);
			if ($linhas_tfds>0) $tem_dados=1;
			$status .= "Em solicitação de TFD: ". sim_nao($linhas_tfds) ." <br /> ";
			
			$result_tfda= mysql_query("select id_pessoa from tfds_pessoas_acompanhantes where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_tfda= mysql_num_rows($result_tfda);
			if ($linhas_tfda>0) $tem_dados=1;
			$status .= "Acompanhante/carona de TFD: ". sim_nao($linhas_tfda) ." <br /> ";
			
			$result_usu= mysql_query("select id_pessoa from usuarios where id_pessoa = '". $rs->id_pessoa ."' ");
			$linhas_usu= mysql_num_rows($result_usu);
			if ($linhas_usu>0) $tem_dados=1;
			$status .= "Usuário do sistema: ". sim_nao($linhas_usu) ." <br /> ";
			
			$result_dep= mysql_query("select id_pessoa from pessoas where id_responsavel = '". $rs->id_pessoa ."' ");
			$linhas_dep= mysql_num_rows($result_dep);
			if ($linhas_dep>0) $tem_dados=1;
			$status .= "Dependentes: ". sim_nao($linhas_dep) ." <br /> ";
			?>
            
            <input name="tem_dados" id="tem_dados" type="hidden" value="<?= $tem_dados; ?>" class="escondido" />
            
            <label>Situação:</label>
			<a href="javascript:void(0);" onmouseover="Tip('<?= $status; ?>');">passe o mouse</a>
            <br />
            
            <fieldset>
            	<legend>Colocar os dados atuais no cadastro de: </legend>
            
                <label>CPF:</label>
                <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo ao lado.');"/>
                <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">busca</button>
                <br />
                
                <label>&nbsp;</label>
                <div id="cpf_usuario_atualiza">
                    <input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
                </div>
                <br />
			</fieldset>
                        
            <br /><br />
            
			<label for="enviar">&nbsp;</label>
			<button type="submit" id="enviar">Excluir >></button>
			<br />
		</form>
	</div>	
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>