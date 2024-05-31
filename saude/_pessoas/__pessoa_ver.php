<? if ($_SESSION["id_usuario_sessao"]!="") {
	$result= mysql_query("select pessoas.*,
							DATE_FORMAT(pessoas.data_nasc, '%d') as dia_nasc,
							DATE_FORMAT(pessoas.data_nasc, '%m') as mes_nasc,
							DATE_FORMAT(pessoas.data_nasc, '%Y') as ano_nasc,
							DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc,
							DATE_FORMAT(pessoas.data_cadastro, '%d/%m/%Y %H:%i:%s') as data_cadastro,
							cidades.cidade, ufs.uf
							from  pessoas, cidades, ufs
							where pessoas.id_pessoa = '$id_pessoa'
							and   pessoas.id_cidade = cidades.id_cidade
							and   cidades.id_uf = ufs.id_uf
							") or die(mysql_error());
	
	if (mysql_num_rows($result)==0)
		echo "<span class=\"vermelho\">Cadastro não encontrado!</span>";
	else {
		$rs= mysql_fetch_object($result);
?>

<div id="tela_cadastro">
</div>

<div id="tela_aux_rapida" class="nao_mostra">
</div>

<? if ($relatorio=="") { ?>
<div id="busca">
	<form action="<?= AJAX_FORM; ?>formPessoaBuscar" method="post" id="formPessoaBuscar" name="formPessoaBuscar" onsubmit="return ajaxForm('conteudo', 'formPessoaBuscar');">

		<label class="tamanho30" for="busca">Busca:</label>
		
		<input name="txt_busca" id="txt_busca" class="tamanho50" maxlength="11" value="<?= $txt_busca; ?>" />

		<select name="lugar" id="lugar" class="tamanho80">
			<option value="nome" <? if ($lugar=="nome") echo "selected=\"selected\""; ?>>Nome</option>
			<option value="cpf" <? if ($lugar=="cpf") echo "selected=\"selected\""; ?>>CPF</option>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<? if ($txt_busca!="") { ?>
<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar&amp;txt_busca=<?= $txt_busca; ?>&amp;lugar=<?= $lugar; ?>');">&lt;&lt; voltar para busca</a>
<? } else { ?>
<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_listar');">&lt;&lt; voltar para pessoas</a>
<? } ?>

<?
} //fim relatorio
else { ?>
<a href="javascript:void(0);" onclick="fechaDiv('tela_relatorio');" class="fechar">x</a>
<? } ?>

<h2 class="titulos">Visualização de cadastro</h2>

<div class="parte_esquerda com_label_grande">

	<fieldset>
		<legend>Dados pessoais</legend>
		
        <div class="screen">
            <label>&nbsp;</label>
            <button onclick="ajaxLink('tela_cadastro', 'carregaPaginaInterna&amp;pagina=_pessoas/pessoa_editar&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;retorno=conteudo'); abreDivSo('tela_cadastro');" title="Editar">editar</button>
            <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
            <button onclick="ajaxLink('tela_aux_rapida', 'carregaPaginaInterna&amp;pagina=_pessoas/pessoa_excluir&amp;id_pessoa=<?= $rs->id_pessoa; ?>'); abreDivSo('tela_aux_rapida');" title="Editar">deletar</button>
            <? } ?>
            <br /><br />
        </div>
        
		<label>Cód. pessoa:</label>
		<?= $rs->id_pessoa; ?>
		<br />
		
		<label>Nome:</label>
		<?= $rs->nome; ?> <? if ($rs->situacao_pessoa==2) echo "<img src=\"images/cruz.png\" alt=\"+\" />"; ?>
		<br />
        
        <label>Sexo:</label>
		<?= pega_sexo($rs->sexo); ?>
		<br />
		
        <label>Raça:</label>
		<?
        switch($rs->raca) {
			case "b": echo "Branco"; break;
			case "n": echo "Negro"; break;
			case "a": echo "Amarelo"; break;
			default: echo "<span class=\"vermelho\">Não informado</span>"; break;
		}
		?>
		<br />
        
		<label>Dependentes:</label>

		<?
		$result_dependentes= mysql_query("select id_pessoa, nome, cpf from pessoas where id_responsavel = '$rs->id_pessoa' ");
		if (mysql_num_rows($result_dependentes)==0)
			echo "Nenhum dependente";
		else {
			echo "<br /><ul class=\"recuo4\">";
			while ($rs_dependentes= mysql_fetch_object($result_dependentes))
				echo "<li><a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_ver&amp;id_pessoa=". $rs_dependentes->id_pessoa ."')\">". $rs_dependentes->nome ."</a></li>";
			echo "</ul>";
		}
		?>
		<br />
        
        <? if ($relatorio=="") { ?>
        <? if (($rs->id_responsavel=="") || ($rs->id_responsavel=="0")) { ?>
        <div class="screen">
            <label>&nbsp;</label>
            <input type="hidden" name="id_pessoa_dep" id="id_pessoa_dep" value="<?= $rs->id_pessoa; ?>" class="escondido" />
            <a href="javascript:void(0);" onclick="cadastraDependente(2);">cadastrar dependente</a>
            <br />
		</div>
        <? } } ?>
        		
		<label>Cidade:</label>
		<?= $rs->cidade ."/". $rs->uf; ?>
		<br />
		
        <?
		if (($rs->id_psf=="") || ($rs->id_psf=="0"))
			$psf= "<span class=\"vermelho\">Não informado!</span>";
		else
			$psf= pega_posto($rs->id_psf);
		?>
        
        <label>PSF:</label>
		<?= $psf; ?>
		<br />
        
		<label>CPF:</label>
		<?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?>
		<br />
		
		<label>RG:</label>
		<?
		if ($rs->rg=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->rg;
		?>
		<br />
        
        <label>Órgão emissor:</label>
		<?
		if ($rs->orgao_emissor_rg=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->orgao_emissor_rg;
		?>
		<br />
		
		<label>Endereço:</label>
		<?
		if ($rs->endereco=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->endereco;
		?>
		<br />
		
		<label>Bairro:</label>
		<?
		if ($rs->bairro=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->bairro;
		?>
		<br />
		
		<label>Complemento:</label>
		<?
		if ($rs->complemento=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->complemento;
		?>
		<br />
		
		<label>CEP:</label>
		<?
		if ($rs->cep=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->cep;
		?>
		<br />
        
		<label>Telefone:</label>
		<?
		if ($rs->telefone=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->telefone;
		?>
		<br />
		
		<label>Nascimento:</label>
		<?
		if (($rs->data_nasc=="") || ($rs->data_nasc=="00/00/0000"))
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->data_nasc;
		?>
		<br />
		
        <?
		$meses= calcula_meses($rs->data_nasc);
		
		?>
        
		<label>Idade:</label>
		<?
		$idade= calcula_idade($rs->data_nasc);
		echo $idade;
		
		$dif= ($meses%12);
		
		echo " e ". $dif ." meses";
		?>
		<br />
		
        <label>Cartão SUS:</label>
		<?
		if ($rs->cartao_sus=="")
			echo "<span class=\"vermelho\">Não informado!</span>";
		else
			echo $rs->cartao_sus;
		?>
		<br />
        
		<label>Cadastro em:</label>
		<?
		if ($rs->origem_cadastro=="c")
			echo pega_cidade($rs->id_origem_cadastro);
		else
			echo pega_posto($rs->id_origem_cadastro);
		?>
		<br />
		
        <? if ($rs->id_usuario!="") { ?>
        <label>Cadastro por:</label>
		<?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
		<br />
        <? } ?>
        
		<label>Data do cadastro:</label>
		<?= $rs->data_cadastro; ?>
		<br />
	
	</fieldset>
	
	<?
	if ($_SESSION["tipo_usuario_sessao"]=="a") {
		$result_usu= mysql_query("select * from usuarios where id_pessoa = '$rs->id_pessoa' ");
		$rs_usu= mysql_fetch_object($result_usu);
	
	if (mysql_num_rows($result_usu)>0) {
	?>
	
	<fieldset>
		<legend>Dados de acesso</legend>
		
		<label>Cód. usuário:</label>
		<?= $rs_usu->id_usuario; ?>
		<br />
		
		<label>Usuário:</label>
		<?= $rs_usu->usuario; ?>
		<br />
		
		<label>Senha:</label>
		-<? //$rs_usu->senha; ?>
		<br />
		
		<label>Tipo:</label>
		<?= pega_tipo_usuario($rs_usu->tipo_usuario); ?>
		<br />

		<label>Situação:</label>
		<?= sim_nao($rs_usu->situacao); ?>
		<br />
		
		<?
		//se for medico ou enfermeiro lista os postos que tem acesso
		if ($rs_usu->tipo_usuario == "p") {
			
			echo "<label>Postos:</label>";
			
			$result_pos= mysql_query("select postos.posto, cidades.cidade, ufs.uf from postos, cidades, ufs, usuarios_postos
										where usuarios_postos.id_usuario = '$rs_usu->id_usuario'
										and   usuarios_postos.id_posto = postos.id_posto
										and   postos.id_cidade = cidades.id_cidade
										and   cidades.id_uf = ufs.id_uf
										") or die(mysql_error());
			$linhas_pos= mysql_num_rows($result_pos);

			if ($linhas_pos>0)
				echo "<ul>";
			else
				echo "Nenhum!";
			
			while ($rs_pos = mysql_fetch_object($result_pos))
				echo "<li>". $rs_pos->posto ." (". $rs_pos->cidade ."/". $rs_pos->uf .")</li>";

			if ($linhas_pos>0)
				echo "<ul>";
		}
		?>
		
	</fieldset>
	<? 
		}//fim if usuarios
	}
	?>
    
    <? if (@pode("l", $_SESSION["permissao"])) { ?>
    <?
	$result_soc= mysql_query("select pessoas_se.* from pessoas, pessoas_se
								where pessoas_se.id_pessoa = pessoas.id_pessoa
								and   pessoas.id_pessoa = '". $_GET["id_pessoa"] ."'
								");
	if (mysql_num_rows($result_soc)>0) {
		$rs_soc= mysql_fetch_object($result_soc);
	?>
    <fieldset>
    	<legend>Dados sociais</legend>
        
        <label>Profissão:</label>
        <?= pega_profissao($rs_soc->id_profissao); ?>
        <br />
        
        <label>Renda mensal:</label>
        <?= number_format($rs_soc->renda, 2, ',', '.'); ?>
        <br />
        
        <label>Local de trabalho:</label>
        <?= $rs_soc->local_trabalho; ?>
        <br />
        
        <label>Carteira assinada:</label>
        <?
        switch($rs_soc->ca) {
			case 's': echo "Sim"; break;
			case 'n': echo "Não"; break;
			case 'd': echo "Desempregado há ". $rs_soc->desempregado_tempo;
					break;
		}
		?>
        <br />
        
        <label>Naturalidade:</label>
        <?= $rs_soc->cidade_nat; ?>
        <br />
        
        <label>Tempo no município:</label>
        <?= $rs_soc->tempo_municipio; ?>
        <br />
        
        <label>Estado civil:</label>
        <?= pega_estado_civil($rs_soc->id_ec); ?>
        <br />
        
        <label>Grau de instrução:</label>
        <?= pega_grau_instrucao($rs_soc->id_gi); ?>
        <br />
        
    </fieldset>
    <? } } ?>
</div>

<div class="parte_direita sem">
	<fieldset>
		<legend>Observações gerais</legend>
	
		<?
		if ($rs->observacoes=="")
			echo "<span class=\"vermelho\">Não informado</span>";
		else
			echo $rs->observacoes;
        ?>
	</fieldset>
    <br /><br />
    
    <? if ($relatorio=="") { ?>
    <? if (pode_algum("fecim", $_SESSION["permissao"])) { ?>
    
    <fieldset>
		<legend>Medicamentos periódicos</legend>
		
        <div id="remedios_periodicos">
        </div>

        <script language="javascript" type="text/javascript">
		ajaxLink('remedios_periodicos', 'pegaRemediosPeriodicos&id_pessoa=<?= $rs->id_pessoa; ?>');
		//daFoco('id_psf');
		</script>
        
        <br />
        <center>
	        <a href="javascript:void(0);" class="botao" onclick="abreDivSo('tela_aux_rapida'); ajaxLink('tela_aux_rapida', 'carregaPaginaInterna&amp;pagina=_pessoas/peri_assoc&amp;id_pessoa=<?= $rs->id_pessoa; ?>');" onmouseover="Tip('Clique para adicionar um medicamento periódico para esta pessoa.');">associar mais</a>
        </center>
        
	</fieldset>
    <br /><br />
	
    <fieldset>
    	<legend>Histórico</legend>

		<? if ($tipo_hist=="v") { ?>
        <button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">medicamentos</button>
        <? } else { ?>
        <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">medicamentos</button>
        <? } ?>
        
        <? if ($tipo_hist=="v") { ?>
        <button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_consultas_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">consultas</button>
        <? } else { ?>
        <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_consultas_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">consultas</button>
        <? } ?>
    	
        <br /><br />
        
    	<? if ($tipo_hist=="v") { ?>
        <button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_mats_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">materiais</button>
        <? } else { ?>
        <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_mats_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">materiais</button>
        <? } ?>
        
        <? if ($tipo_hist=="v") { ?>
        <button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_acomp_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">acompanhamentos</button>
        <? } else { ?>
        <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_acomp_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">acompanhamentos</button>
        <? } ?>
        
        <? if ($tipo_hist=="v") { ?>
        <button type="button" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_procedimentos_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">evolução</button>
        <? } else { ?>
        <button type="button" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/historico_procedimentos_completo&amp;id_pessoa_hist=<?= $rs->id_pessoa; ?>&amp;tipo_hist=<?= $tipo_hist; ?>');">evolução</button>
        <? } ?>
    </fieldset>
    
    <br /><br />
    
    <?
	/*if ($_SESSION["id_cidade_sessao"]!="")
		$id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else
		$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	
	if (pega_modo_almox($id_cidade_emula)==2) {*/
	?>
    
	<? //} ?>
    
    <?
    }
	if (pode_algum("on", $_SESSION["permissao"])) {
	?>
    
    <fieldset>
        <legend>Odontogramas</legend>
        
        <fieldset>
        	<legend>Odontogramas anteriores:</legend>
			<?
            $result_odontogramas= mysql_query("select *, DATE_FORMAT(data_odontograma, '%d/%m/%Y') as data_odo from odontogramas
                                                where id_pessoa = '$rs_paciente->id_pessoa'
                                                order by data_odontograma desc
                                                ");
            if (mysql_num_rows($result_odontogramas)==0)
                echo "<span class=\"vermelho\">Nenhum odontograma encontrado.</span>";
            else {
            ?>
            <p>Odontogramas encontrados:</p>
            <ul class="abas">
                <? while ($rso= mysql_fetch_object($result_odontogramas)) { ?>
                <li><a href="javascript:void(0);" onclick="ajaxLink('odontogramas', 'carregaPaginaInterna&amp;pagina=_consultas/odontograma&amp;id_odontograma=<?= $rso->id_odontograma;?>&amp;id_pessoa=<?=$rs->id_pessoa;?>&amp;semclique=1');"><?= $rso->data_odo; ?></a></li>
                <? } ?>
            </ul>
            <? } ?>
        </fieldset>
        
        <div id="odontogramas">
			
        </div>
    </fieldset>
    
    <? } ?>
    
    
	
</div>
<?
}//fim relatorio
	}
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>