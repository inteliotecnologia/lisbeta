<?
require_once("funcoes.php");

if (!$conexao)
	require_once("conexao.php");

header("Content-type: text/html; charset=iso-8859-1", true);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ( (!isset($_GET["livroExcluir"])) && (!isset($_GET["rmAndamentoExcluir"])) && (!isset($_GET["mensagemExcluir"]))
		&& (!isset($_GET["rmAndamentoNotaExcluir"])) && (!isset($_GET["avaliaRM"]))
		&& (!isset($_GET["reclamacaoAndamentoExcluir"])) && (!isset($_GET["reclamacaoAcaoNotaExcluir"]))
		&& (!isset($_GET["avaliaReclamacaoAcao"]) && (!isset($_GET["apagaSeparacaoRemessa"])) )
		)
	echo "<!DOCTYPE>
			<html>
			<head>
			<title>Sige</title>
			</head>
			<body>";

// ############################################### TODOS ###############################################

if (isset($_GET["buscaTempo"])) {
	echo date("d/m/Y") ."<br />". date("H:i:s");
}

if (isset($_GET["carregaPagina"])) {
	require_once("index2.php");
}
if (isset($_GET["carregaPaginaInterna"])) {
	require_once("index2.php");
}

if (isset($_GET["alteraCidade"])) {
	$result= mysql_query("select * from cidades where id_uf = '". $_GET["id_uf"] ."' order by cidade asc ");
	
	$str= "<select name=\"". $_GET["nome_campo"] ."\" id=\"". $_GET["nome_campo"] ."\" title=\"Cidade\">
			<option value=\"\">---</option>";
	
	$i=1;
	while ($rs= mysql_fetch_object($result)) {
		if ($i==1) $classe= " class=\"cor_sim\"";
		else $classe= " ";
		$i++;
		$str .= "<option ". $classe ." value=\"". $rs->id_cidade ."\">". $rs->cidade ."</option>";
		if ($i==2) $i=0;
	}
	
	$str .= "</select>";
	echo $str;
	echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
}

if (isset($_GET["retornaDataFinal"])) {
	$data= explode('/', $_GET["data_inicial"]);
	echo date("d/m/Y", mktime(0, 0, 0, $data[1], $data[0]+($_GET["qtde_dias"]-1), $data[2]));
}

if (isset($_GET["carregaRespostaParaLivro"])) {
	require_once("_com/__livro_form_para.php");
}

// ############################################ LIVRO E RECLAMAÇÕES ###############################################

if (pode("i12o", $_SESSION["permissao"])) {	
	
	if (isset($_GET["clientePecaDobraExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		/*$result_pre= mysql_query("select * from pessoas
									where id_contrato= '". $_GET["id_contrato"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas= mysql_num_rows($result_pre);
		
		if ($linhas==0) {*/
			$result= mysql_query("delete from fi_clientes_pecas_dobra
									where id_cliente = '". $_GET["id_cliente"] ."'
									and   id_cliente_peca_dobra = '". $_GET["id_cliente_peca_dobra"] ."'
									limit 1
									") or die(mysql_error());
			if (!$result) $var++;
			
			@unlink(CAMINHO . "cliente_peca_dobra_". $_GET["id_cliente_peca_dobra"] .".jpg");
		//} else $var++;
		
		finaliza_transacao($var);
		//$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//$pagina= "financeiro/ad_listar";
		//require_once("index2.php");
	}
	
	if (isset($_GET["clienteHistoricoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from com_livro
								where id_livro = '". $_GET["id_livro"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								and   restrito = '1'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		echo excluido_ou_nao($var);
	}
	
	if (isset($_GET["clienteSetorExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		/*$result_pre= mysql_query("select * from pessoas
									where id_contrato= '". $_GET["id_contrato"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas= mysql_num_rows($result_pre);
		
		if ($linhas==0) {*/
			$result= mysql_query("delete from fi_clientes_setores
									where id_cliente = '". $_GET["id_cliente"] ."'
									and   id_cliente_setor = '". $_GET["id_cliente_setor"] ."'
									limit 1
									");
			if (!$result) $var++;
		//} else $var++;
		
		finaliza_transacao($var);
		//$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//$pagina= "financeiro/ad_listar";
		//require_once("index2.php");
	}
	
	if (isset($_GET["clienteItemCedidoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		/*$result_pre= mysql_query("select * from pessoas
									where id_contrato= '". $_GET["id_contrato"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas= mysql_num_rows($result_pre);
		
		if ($linhas==0) {*/
			$result= mysql_query("delete from fi_clientes_itens_cedidos
									where id_cliente = '". $_GET["id_cliente"] ."'
									and   id_item_cedido = '". $_GET["id_item_cedido"] ."'
									limit 1
									");
			if (!$result) $var++;
		//} else $var++;
		
		finaliza_transacao($var);
		//$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//$pagina= "financeiro/ad_listar";
		//require_once("index2.php");
	}
	
	if (isset($_GET["reclamacaoAcaoNotaExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("update qual_reclamacoes_andamento
								set nota= NULL
								where id_livro = '". $_GET["id_livro"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_reclamacao_andamento = '". $_GET["id_reclamacao_andamento"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
	if (isset($_GET["reclamacaoAndamentoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from qual_reclamacoes_andamento
								where id_livro = '". $_GET["id_livro"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_reclamacao_andamento = '". $_GET["id_reclamacao_andamento"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
	if (isset($_GET["avaliaReclamacaoAcao"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("update qual_reclamacoes_andamento set
								nota = '". $_GET["nota"] ."'
								where id_empresa= '". $_SESSION["id_empresa"] ."'
								and   id_reclamacao_andamento = '". $_GET["id_reclamacao_andamento"] ."'
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
	
	
	if (isset($_GET["pesquisaItemExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select id_pesquisa_item from qual_pesquisa_notas
								 	where id_pesquisa_item = '". $_GET["id_pesquisa_item"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas_pre= mysql_num_rows($result_pre);
		
		if ($linhas_pre==0) {
			$result1= mysql_query("delete from qual_pesquisa_itens
									where id_pesquisa_item = '". $_GET["id_pesquisa_item"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									limit 1
									");
			if (!$result1) $var++;
		}
		else $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
	if (isset($_GET["pesquisaCategoriaExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select qual_pesquisa_notas.id_pesquisa_item from qual_pesquisa_notas, qual_pesquisa_itens
								 	where qual_pesquisa_itens.id_pesquisa_categoria = '". $_GET["id_pesquisa_categoria"] ."'
									and   qual_pesquisa_notas.id_pesquisa_item = qual_pesquisa_itens.id_pesquisa_item
									and   qual_pesquisa_notas.id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas_pre= mysql_num_rows($result_pre);
		
		if ($linhas_pre==0) {
			$result1= mysql_query("delete from qual_pesquisa_itens
									where id_pesquisa_categoria = '". $_GET["id_pesquisa_categoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
			if (!$result1) $var++;
			
			$result2= mysql_query("delete from qual_pesquisa_categorias
									where id_pesquisa_categoria = '". $_GET["id_pesquisa_categoria"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
			if (!$result2) $var++;
			
		}
		else $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
	if (isset($_GET["clientePesquisaExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		/*$result_pre= mysql_query("select qual_pesquisa_notas.id_pesquisa_item from qual_pesquisa_notas, qual_pesquisa_itens
								 	where qual_pesquisa_itens.id_pesquisa_categoria = '". $_GET["id_pesquisa_categoria"] ."'
									and   qual_pesquisa_notas.id_pesquisa_item = qual_pesquisa_itens.id_pesquisa_item
									and   qual_pesquisa_notas.id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas_pre= mysql_num_rows($result_pre);
		
		if ($linhas_pre==0) {*/
			$result1= mysql_query("update qual_pesquisa
									set  status_pesquisa = '2'
									where id_pesquisa = '". $_GET["id_pesquisa"] ."'
									and   id_cliente = '". $_GET["id_cliente"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									") or die(mysql_error());
			if (!$result1) $var++;
			
			/*$result2= mysql_query("delete from qual_pesquisa_notas
									where id_pesquisa = '". $_GET["id_pesquisa"] ."'
									and   id_cliente = '". $_GET["id_cliente"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									") or die(mysql_error());
			if (!$result2) $var++;*/
			
			/*
		}
		else $var++;*/
		
		finaliza_transacao($var);
		$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//header("location: ./?pagina=qualidade/reclamacao&acao=e&id_livro=". $_GET["id_livro"] ."&msg=". $msg ."#situacao");
	}
	
}
// ############################################### ADMIN GERAL ###############################################

if ($_SESSION["tipo_usuario"]=="a") {
	if (isset($_GET["usuarioStatus"])) {
		$result= mysql_query("update usuarios set status_usuario= '". $_GET["status"] ."'
								where id_usuario= '". $_GET["id_usuario"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "acesso/usuario_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["usuarioExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update usuarios
							  	set situacao = '0'
								where id_usuario = '". $_GET["id_usuario"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "acesso/usuario_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["adExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update tr_clientes_ad
							  	set situacao = '0'
								where id_ad = '". $_GET["id_ad"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		
		//$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//$pagina= "financeiro/ad_listar";
		//require_once("index2.php");
	}
	
	if (isset($_GET["contratoStatus"])) {
		
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("update fi_contratos
							  	set status_contrato = '". $_GET["status"] ."'
								where id_contrato = '". $_GET["id_contrato"] ."'
								");
		if (!$result) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		//echo excluido_ou_nao($var);
		
		$pagina= "financeiro/contrato_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["contratoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from pessoas
									where id_contrato= '". $_GET["id_contrato"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		$linhas= mysql_num_rows($result_pre);
		
		if ($linhas==0) {
			$result= mysql_query("delete from fi_contratos
									where id_contrato = '". $_GET["id_contrato"] ."'
									limit 1
									");
			if (!$result) $var++;
		} else $var++;
		
		finaliza_transacao($var);
		//$msg= $var;
		
		echo excluido_ou_nao($var);
		
		//$pagina= "financeiro/ad_listar";
		//require_once("index2.php");
	}
	
	if (isset($_GET["livroExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from com_livro
								where id_livro = '". $_GET["id_livro"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from com_livro_permissoes
								where id_livro = '". $_GET["id_livro"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result2) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		if ($_GET["retorno"]=="r") header("location: ./?pagina=qualidade/reclamacao_listar&msg=". $msg);
		else header("location: ./?pagina=com/livro&data=". $_GET["data"] ."&msg=". $msg);
	}
	
}//fim empresa admin

if (pode("api2", $_SESSION["permissao"])) {	
	if (isset($_GET["verificaCnpj"])) {
		$cnpj= $_GET["cnpj"];
		$sql= "select pessoas.id_pessoa from pessoas
						where pessoas.cpf_cnpj = '". $cnpj ."'
						and   pessoas.tipo = 'j'
						and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'
						";
		
		if ($_GET["id_pessoa"]!="")
			$sql .= " and pessoas.id_pessoa <> '". $_GET["id_pessoa"] ."' " ;
		
		$result= mysql_query($sql) or die(mysql_error());
		
		$campo[0]= "<input type=\"hidden\" name=\"passa_cnpj\" id=\"passa_cnpj\" value=\"\" class=\"escondido\" />";
		$campo[1]= "<input type=\"hidden\" name=\"passa_cnpj\" id=\"passa_cnpj\" value=\"1\" class=\"escondido\" />";
	
		if (mysql_num_rows($result)==0) {
			echo $campo[1] ."<span id=\"span_cnpj_testa\" class=\"verde\">CNPJ disponível!</span>";
			echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
		}
		else {
			$rs= mysql_fetch_object($result);
			
			$result2= mysql_query("select * from pessoas, pessoas_tipos
								 	where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas.id_pessoa = '". $rs->id_pessoa ."'
									");
			$linhas2= mysql_num_rows($result2);
			
			$como= " como ";
			$pode_duplicar= true;
			
			$i=1;
			while ($rs2= mysql_fetch_object($result2)) {
				if ($rs2->tipo_pessoa=='c') $pode_duplicar= false;
				
				$como .= "<strong>". pega_tipo_pessoa($rs2->tipo_pessoa) ."</strong>";
				
				if ($i!=$linhas2) $como .= ", ";
				
				$i++;
			}
			
			echo $campo[1] ."<span id=\"span_cnpj_testa\" class=\"vermelho\">CNPJ já cadastrado ". $como ."!</span>";
			
			if ($pode_duplicar)
				echo "<br /><label>&nbsp;</label><a class=\"menor\" href=\"javascript:void(0);\" onclick=\"cadastraNovoTipoPessoa('". $rs->id_pessoa ."', '". $_GET["tipo_pessoa"] ."');\">&raquo; cadastrar como <strong>". pega_tipo_pessoa($_GET["tipo_pessoa"]) ."</strong></a>";
			
			echo "<br /><label>&nbsp;</label> <span class=\"menor\">ou prossiga para cadastrar com o mesmo CNPJ</span>";
		}
	}
	
	if (isset($_GET["alteraTipoPessoa"])) {
		if ($_GET["tipo_pessoa"]=='f') require_once("_financeiro/__pessoaf.php");
		else require_once("_financeiro/__pessoaj.php");
	}

	if (isset($_GET["pessoaStatus"])) {
		$result= mysql_query("update pessoas set status_pessoa = '". $_GET["status_pessoa"] ."'
								where id_pessoa= '". $_GET["id_pessoa"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
		
		$status_pessoa= inverte_0_1($_GET["status_pessoa"]);
		
		$pagina= "financeiro/pessoa_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["pessoaExcluir"])) {
		$var=0;
		inicia_transacao();
		
		if ($_GET["tipo_pessoa"]!='a') {
			$result_pre= mysql_query("select * from pessoas, pessoas_tipos
										where pessoas.id_pessoa= '". $_GET["id_pessoa"] ."'
										and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
										");
			$linhas= mysql_num_rows($result_pre);
			
			if ($linhas==1) {
				$rs_pre= mysql_fetch_object($result_pre);
				
				$result2= mysql_query("delete from pessoas_tipos where id_pessoa = '". $_GET["id_pessoa"] ."' limit 1 ");
				if (!$result2) $var++;
			}
			elseif ($linhas>1) {
				$result2= mysql_query("delete from pessoas_tipos
										where id_pessoa = '". $_GET["id_pessoa"] ."'
										and   tipo_pessoa = '". $_GET["tipo_pessoa"] ."'
										limit 1 ");
				if (!$result2) $var++;
				
			} else $var++;
		
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "financeiro/pessoa_listar";
		require_once("index2.php");
	}
}


// ######################################### EMISSÃO DE DOCUMENTOS #########################################

if (pode("c3", $_SESSION["permissao"])) {

	if (isset($_GET["documentoEmissaoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from dc_documentos_emissoes
								where id_documento_emissao = '". $_GET["id_documento_emissao"] ."'
								and   tipo = '". $_GET["tipo"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "dc/documento_emissao_listar";
		require_once("index2.php");
	}
}

// ######################################### ARQUIVO DE DOCUMENTOS #########################################

if (pode("d", $_SESSION["permissao"])) {

	if (isset($_GET["documentoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from dc_documentos
								where id_documento = '". $_GET["id_documento"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "dc/documento_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["carregaPastasdoDepto"])) {
		if ($_GET[id_departamento]!="") {
		?>
        <select name="id_pasta" id="id_pasta" title="Pasta">
            <option value="">-</option>
			<?
            $result_cc2= mysql_query("select *
                                        from  dc_documentos_pastas
                                        where id_departamento = '". $_GET["id_departamento"] ."'
                                        order by pasta asc
                                        ") or die(mysql_error());
            $i=0;
            while ($rs_cc2= mysql_fetch_object($result_cc2)) {
            ?>
            <option <? if ($i%2==0) echo "class=\"cor_sim\""; else echo "class=\"cor_nao\""; ?> value="<?= $rs_cc2->id_pasta; ?>" <? if ($_GET["pasta"]==$rs_cc2->pasta) echo "selected=\"selected\""; ?>><?= $rs_cc2->pasta ." - ". $rs_cc2->nome_pasta ." (". ativo_inativo($rs_cc2->status_pasta) .")"; ?></option>
            <? $i++; } ?>
        </select>
        <?
        }
        else {
        ?>
        <select name="id_pasta" id="id_pasta" title="Pasta">
            <option value="">-</option>
            <?
            $result_emp= mysql_query("select * from pessoas, pessoas_tipos, empresas
                                        where pessoas.id_pessoa = empresas.id_pessoa
                                        and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
                                        and   pessoas_tipos.tipo_pessoa = 'a'
                                        order by 
                                        pessoas.nome_rz asc");
            while ($rs_emp= mysql_fetch_object($result_emp)) {
            ?>
            <optgroup class="opt1" label="<?= $rs_emp->apelido_fantasia; ?>">
                <?
                $result_cc= mysql_query("select *
                                            from  rh_departamentos
                                            where id_empresa = '". $rs_emp->id_empresa ."'
                                            order by departamento asc
                                            ") or die(mysql_error());
                while ($rs_cc= mysql_fetch_object($result_cc)) {
                ?>
                <optgroup class="opt2" label="<?= $rs_cc->departamento; ?>">
                    <?
                    $result_cc2= mysql_query("select *
                                                from  dc_documentos_pastas
                                                where /* id_empresa = '". $_SESSION["id_empresa"] ."'
                                                and   */ id_departamento = '". $rs_cc->id_departamento ."'
                                                order by pasta asc
                                                ") or die(mysql_error());
                    $i=0;
                    while ($rs_cc2= mysql_fetch_object($result_cc2)) {
                    ?>
                    <option <? if ($i%2==0) echo "class=\"cor_sim\""; else echo "class=\"cor_nao\""; ?> value="<?= $rs_cc2->id_pasta; ?>" <? if (($rs_cc2->id_pasta==$rs->id_pasta) || ($rs_cc2->id_pasta==$_GET["id_pasta"])) echo "selected=\"selected\""; ?>><?= $rs_cc2->pasta ." - ". $rs_cc2->nome_pasta ." (". ativo_inativo($rs_cc2->status_pasta) .")"; ?></option>
                    <? $i++; } ?>
                </optgroup>
                <? } ?>
            </optgroup>
            <? } ?>
        </select>
        <?
        }
	}
	
	if (isset($_GET["documentoPastaExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from dc_documentos
										where id_pasta = '". $_GET["id_pasta"] ."'
										");

		if (mysql_num_rows($result_pre)==0) {
			$result1= mysql_query("delete from dc_documentos_pastas
									where id_pasta = '". $_GET["id_pasta"] ."'
									limit 1
									");
			if (!$result1) $var++;
		} else $var++;
		
		finaliza_transacao($var);
		
		echo excluido_ou_nao($var);
	}
	
	if (isset($_GET["documentoPastaStatus"])) {
		$result= mysql_query("update dc_documentos_pastas set status_pasta= '". $_GET["status"] ."'
								where id_pasta = '". $_GET["id_pasta"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "dc/documento_pasta_listar";
		require_once("index2.php");
	}
}

// ############################################### AUTORIZAÇÕES ###############################################

if (pode("u", $_SESSION["permissao"])) {

	if (isset($_GET["abastecimentoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from fi_abastecimentos
								where id_abastecimento= '". $_GET["id_abastecimento"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "financeiro/abastecimento_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["refeicaoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from fi_refeicoes
								where id_refeicao= '". $_GET["id_refeicao"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "financeiro/refeicao_listar";
		require_once("index2.php");
	}
}

// ############################################### TELEFONE ###############################################

if (pode("t", $_SESSION["permissao"])) {

	if (isset($_GET["contatoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from tel_contatos
								where id_contato= '". $_GET["id_contato"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from tel_contatos_telefones
								where id_contato= '". $_GET["id_contato"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result2) $var++;
		
		$letra= substr($_GET["nome"], 0, 1);
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "contatos/contato_esquema";
		require_once("index2.php");
	}
	
	if (isset($_GET["ligacaoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from tel_contatos_ligacoes
								where id_ligacao= '". $_GET["id_ligacao"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result1) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "contatos/ligacao_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["verificaTelefone"])) {
		$var=0;
		
		$result_pre= mysql_query("select * from tel_contatos_telefones
										where telefone = '". $_GET["telefone"] ."'
										");

		if (mysql_num_rows($result_pre)>0) {
			$rs_pre= mysql_fetch_object($result_pre);
			
			$result= mysql_query("select * from tel_contatos
										where id_contato = '". $rs_pre->id_contato ."'
										");
			$rs= mysql_fetch_object($result);
		}
		
		echo "<input title=\"Para\" name=\"para\" id=\"para\" value=\"". $rs->nome ."\" />";
	}
}

// ############################################### QUALIDADE ###############################################

if (pode("12(", $_SESSION["permissao"])) {
	
	if (isset($_GET["costuraConsertoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from op_limpa_costura_consertos
								where id_costura_conserto= '". $_GET["id_costura_conserto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from op_limpa_costura_consertos_pecas
								where id_costura_conserto= '". $_GET["id_costura_conserto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if (!$result2) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "qualidade/costura_conserto_listar";
		require_once("index2.php");
	}
	
}

	
	if (isset($_GET["pegaEquipamento"])) {
		$result= mysql_query("select * from op_equipamentos
								where codigo= '". $_GET["codigo"] ."'
								and   tipo_equipamento= '". $_GET["tipo_equipamento"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		
		if (mysql_num_rows($result)>0) {
			$rs= mysql_fetch_object($result);
			
			echo $rs->equipamento;
			
			if (($rs->ocupado==1) && ($_GET["acao"]=='i'))
				echo " (<span class=\"vermelho\">OCUPADA</span>) <input id=\"id_equipamento\" name=\"id_equipamento\" value=\"\" title=\"Máquina\" class=\"escondido\" />";
			else
				echo "<input id=\"id_equipamento\" name=\"id_equipamento\" value=\"". $rs->id_equipamento ."\" title=\"Máquina\" class=\"escondido\" />";
		}
		else echo "Não localizado! <input id=\"id_equipamento\" name=\"id_equipamento\" value=\"\" title=\"Máquina\" class=\"escondido\" />";
	}


// ############################################### FINANCEIRO ###############################################

if (pode("i", $_SESSION["permissao"])) {
	
	if (isset($_GET["notaPagamentoExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from fi_notas, fi_notas_parcelas, fi_notas_parcelas_pagamentos
									where fi_notas.id_empresa = '". $_SESSION["id_empresa"] ."'
									and   fi_notas.id_nota = fi_notas_parcelas.id_nota
									and   fi_notas_parcelas.id_parcela = fi_notas_parcelas_pagamentos.id_parcela
									and   fi_notas_parcelas_pagamentos.id_parcela_pagamento = '". $_GET["id_parcela_pagamento"] ."'
									") or die(mysql_error());
		
		$rs_pre= mysql_fetch_object($result_pre);
		
		//echo $rs_pre->id_nota;
		
		//se esta parcela já estava paga/recebida
		if ($rs_pre->status_parcela==1) {
			$result_parcela= mysql_query("update fi_notas_parcelas
										 	set   status_parcela = '0'
											where id_parcela = '". $rs_pre->id_parcela ."'
											limit 1
											");
			if (!$result_parcela) $var++;
		}
		
		//se esta nota já estava paga/recebida
		if ($rs_pre->status_parcela==1) {
			$result_nota= mysql_query("update fi_notas
										 	set   status_nota = '0'
											where id_nota = '". $rs_pre->id_nota ."'
											limit 1
											");
			if (!$result_nota) $var++;
		}
			
		$result_pagamento= mysql_query("delete from fi_notas_parcelas_pagamentos
									   	where id_parcela_pagamento = '". $rs_pre->id_parcela_pagamento ."'
										limit 1
										");
		if (!$result_pagamento) $var++;
			
		/*$result1= mysql_query("delete from fi_notas
								where id_nota= '". $_GET["id_nota"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from fi_notas_parcelas
								where id_nota= '". $_GET["id_nota"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if (!$result2) $var++;
		*/
		
		finaliza_transacao($var);
		$msg= $var;
		
		$pagina_inclui= "_financeiro/__nota_pagamento.php";
		$pagina= "financeiro/nota_esquema";
		require_once("index2.php");
	}
	
	if (isset($_GET["notaExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result1= mysql_query("delete from fi_notas
								where id_nota= '". $_GET["id_nota"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								") or die(mysql_error());
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from fi_notas_parcelas
								where id_nota= '". $_GET["id_nota"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if (!$result2) $var++;
		
		$result3= mysql_query("delete from fi_notas_parcelas_pagamentos
								where id_nota= '". $_GET["id_nota"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if (!$result3) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
		
		$pagina= "financeiro/nota_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["depositoExcluir"])) {
		$result= mysql_query("update fi_depositos set status_deposito= '2'
								where id_deposito= '". $_GET["id_deposito"] ."'
								and   id_empresa = '".$_SESSION["id_empresa"]  ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "financeiro/deposito_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["clienteTipoExcluir"])) {
		$result= mysql_query("update fi_clientes_tipos set status_cliente_tipo= '2'
								where id_cliente_tipo= '". $_GET["id_cliente_tipo"] ."'
								and   id_empresa = '".$_SESSION["id_empresa"]  ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "financeiro/cliente_tipo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["centroCustoStatus"])) {
		$result= mysql_query("update fi_centro_custos set status_centro_custo= '". $_GET["status"] ."'
								where id_centro_custo= '". $_GET["id_centro_custo"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "financeiro/centro_custo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["centroCustoExcluir"])) {
		$result_pre= mysql_query("select * from fi_cc_ct
									where id_centro_custo= '". $_GET["id_centro_custo"] ."'
									") or die(mysql_error());
		
		if (mysql_num_rows($result_pre)==0)
			$result= mysql_query("delete from fi_centro_custos
									where id_centro_custo= '". $_GET["id_centro_custo"] ."'
									limit 1
									") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "financeiro/centro_custo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["tipoCentroCustoExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from fi_centro_custos_tipos
								where id_centro_custo_tipo= '". $_GET["id_centro_custo_tipo"] ."'
								limit 1
								") or die(mysql_error());
		if (!$result) $var++;
		
		$result2= mysql_query("delete from fi_cc_ct
								where id_centro_custo_tipo= '". $_GET["id_centro_custo_tipo"] ."'
								") or die(mysql_error());
		if (!$result2) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "financeiro/centro_custo_tipo_listar";
		require_once("index2.php");
	}

}

// ############################################### RH ###############################################

if (pode("rhviwcd", $_SESSION["permissao"])) {
	
	if (isset($_GET["afastamentoManual"])) {
		$var=0;
		
		$result= mysql_query("update  rh_funcionarios
								set   afastado = '". $_GET["afastado"] ."'
								where id_empresa = '". $_SESSION["id_empresa"] ."'
								and   id_funcionario = '". $_GET["id_funcionario"] ."'
								") or die(mysql_error());
		$rs= @mysql_fetch_object($result);
		
		$pagina= "rh/carreira";
		require_once("index2.php");
	}
	
	
	if (isset($_GET["verificaCartao"])) {
		$var=0;
		
		$result= mysql_query("select * from rh_cartoes
										where id_empresa = '". $_SESSION["id_empresa"] ."'
										and   numero_cartao = '". $_GET["numero_cartao"] ."'
										");
		$linhas_cartao= mysql_num_rows($result);
		
		if ($linhas_cartao==0)
			echo "<span class=\"verde\">Cartão não está em uso!</span>";
		else {
			$rs= mysql_fetch_object($result);
			echo "<span class=\"vermelho\">Cartão em uso por <strong>". pega_funcionario($rs->id_funcionario) ."</strong>!</span>";
		}
	}
	
	if (isset($_GET["verificaCrm"])) {
		$var=0;
		
		$result= mysql_query("select * from rh_afastamentos
										where id_empresa = '". $_SESSION["id_empresa"] ."'
										and   crm = '". $_GET["crm"] ."'
										");
		$rs= mysql_fetch_object($result);
		
		echo "<input title=\"Nome do médico\" name=\"nome_medico\" id=\"nome_medico\" value=\"". $rs->nome_medico ."\" />";
	}
	
	if (isset($_GET["batidaIntervaloFind"])) {
		$var=0;
		inicia_transacao();
		
		/*$result_horario= mysql_query("select * from rh_funcionarios, rh_carreiras, rh_turnos, rh_turnos_horarios
										where rh_funcionarios.id_funcionario = '". $_GET["id_funcionario"] ."'
										and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										and   rh_carreiras.atual = '1'
										and   rh_carreiras.id_turno = rh_turnos.id_turno
										and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
										and   rh_turnos_horarios.id_dia = '". $_GET["id_dia"] ."' ");
		
		$rs_horario= mysql_fetch_object($result_horario);
		*/
		
		//0-> tipo
		//1-> data
		//2-> hora
		//3-> hl
		//4-> vale_dia
		
		$result_ponto= mysql_query("select * from rh_ponto
								   	where id_funcionario = '". $_GET["id_funcionario"] ."'
									and   data_batida = '". formata_data($_GET["data"]) ."'
									and   vale_dia = '". formata_data($_GET["data"]) ."'
									and   tipo = '1'
									order by hora asc limit 1
									");
		
		//echo $rs_ponto->data_batida ." ". $rs_ponto->hora;
		
		$linhas_ponto= mysql_num_rows($result_ponto);
		$rs_ponto= mysql_fetch_object($result_ponto);
		
		for ($j=0; $j<2; $j++) {
			
			$minutos_rand1= rand(-9, 9);
			$minutos_rand2= rand(-9, 9);
			
			$segundos_rand1= rand(-30, 30);
			$segundos_rand2= rand(-30, 30);
			
			switch($j) {
				case 0:
					$data_hora_aqui= soma_data_hora($rs_ponto->data_batida ." ". $rs_ponto->hora, 0, 0, 0, 6, $minutos_rand1, $segundos_rand1);
					break;
				case 1:
					$data_hora_aqui= soma_data_hora($rs_ponto->data_batida ." ". $rs_ponto->hora, 0, 0, 0, 7, $minutos_rand2, $segundos_rand2);
					break;
			}
			
			$pedacos= explode(" ", $data_hora_aqui);
			
			/*echo "operação: ". $j;
			echo "<br>data: ". $pedacos[0];
			echo "<br>hora: ". $pedacos[1];
			echo "<br>vale_dia: ". formata_data($_GET["data"]) ."<br><br><br>";
			*/
			
			$result_ponto= mysql_query("insert into rh_ponto (id_funcionario, data_batida, hora, data_hora_batida, tipo, hl, vale_dia, id_usuario)
										values
										('". $_GET["id_funcionario"] ."', '". $pedacos[0] ."', '". $pedacos[1] ."', '". $pedacos[0] ." ". $pedacos[1] ."',
										'". $j ."', '0', '". formata_data($_GET["data"]) ."', '". $_SESSION["id_usuario"] ."')
										");
		}
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "rh/espelho";
		require_once("index2.php");
		
		
	}
	
	if (isset($_GET["batidaIntervaloNormal"])) {
		$var=0;
		inicia_transacao();
		
		/*$result_horario= mysql_query("select * from rh_funcionarios, rh_carreiras, rh_turnos, rh_turnos_horarios
										where rh_funcionarios.id_funcionario = '". $_GET["id_funcionario"] ."'
										and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										and   rh_carreiras.atual = '1'
										and   rh_carreiras.id_turno = rh_turnos.id_turno
										and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
										and   rh_turnos_horarios.id_dia = '". $_GET["id_dia"] ."' ");
		
		$rs_horario= mysql_fetch_object($result_horario);
		*/
		
		//0-> tipo
		//1-> data
		//2-> hora
		//3-> hl
		//4-> vale_dia
		
		$result_ponto= mysql_query("select * from rh_ponto
								   	where id_funcionario = '". $_GET["id_funcionario"] ."'
									and   vale_dia = '". formata_data($_GET["data"]) ."'
									and   tipo = '1'
									order by hora asc limit 1
									");
		
		
		
		$linhas_ponto= mysql_num_rows($result_ponto);
		$rs_ponto= mysql_fetch_object($result_ponto);
		/*
		echo $rs_ponto->data_batida ." ". $rs_ponto->hora;
		
		echo " | ". soma_data_hora($rs_ponto->data_batida ." ". $rs_ponto->hora, 0, 0, 0, 6, $minutos_rand1, $segundos_rand1);
		
		die("<br />
<br />
morreu!");
		*/
		
		for ($j=0; $j<2; $j++) {
			
			$minutos_rand1= rand(-9, 9);
			$minutos_rand2= rand(-9, 9);
			
			$segundos_rand1= rand(-30, 30);
			$segundos_rand2= rand(-30, 30);
			
			
			
			switch($j) {
				case 0:
					$data_hora_aqui= soma_data_hora($rs_ponto->data_batida ." ". $rs_ponto->hora, 0, 0, 0, 6, $minutos_rand1, $segundos_rand1);
					break;
				case 1:
					$data_hora_aqui= soma_data_hora($rs_ponto->data_batida ." ". $rs_ponto->hora, 0, 0, 0, 7, $minutos_rand2, $segundos_rand2);
					break;
			}
			
			$pedacos= explode(" ", $data_hora_aqui);
			
			//echo "operação: ". $operacao[$j][0];
			//echo "<br>data: ". $operacao[$j][1];
			//echo "<br>hora: ". $operacao[$j][2];
			//echo "<br>hl: ". $operacao[$j][3];
			//echo "<br>vale_dia: ". $operacao[$j][4] ."<br><br><br>";
			
			$result_ponto= mysql_query("insert into rh_ponto (id_funcionario, data_batida, hora, data_hora_batida, tipo, hl, vale_dia, id_usuario)
										values
										('". $_GET["id_funcionario"] ."', '". $pedacos[0] ."', '". $pedacos[1] ."', '". $pedacos[0] ." ". $pedacos[1] ."',
										'". $j ."', '0', '". formata_data($_GET["data"]) ."', '". $_SESSION["id_usuario"] ."')
										");
		}
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "rh/espelho";
		require_once("index2.php");
	}
	
	if (isset($_GET["batidaAutomatica"])) {
		$var=0;
		inicia_transacao();
		
		$result_horario= mysql_query("select * from rh_funcionarios, rh_carreiras, rh_turnos, rh_turnos_horarios
										where rh_funcionarios.id_funcionario = '". $_GET["id_funcionario"] ."'
										and   rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										and   rh_carreiras.atual = '1'
										and   rh_carreiras.id_turno = rh_turnos.id_turno
										and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
										and   rh_turnos_horarios.id_dia = '". $_GET["id_dia"] ."' ");
		
		$rs_horario= mysql_fetch_object($result_horario);
		
		//0-> tipo
		//1-> data
		//2-> hora
		//3-> hl
		//4-> vale_dia
		
		if ($rs_horario->hl==1) $soma=1; else $soma=0;
		$dataex= explode('/', $_GET["data"]);
		//$data= date("Y-m-d", mktime(0, 0, 0, ));
		
		$i=0;
		$entrada_auto= explode(":", $rs_horario->entrada);
		$entrada_completa= explode(" ", date("Y-m-d H:i:s", mktime($entrada_auto[0], $entrada_auto[1], $entrada_auto[2], $dataex[1], $dataex[0]+$soma, $dataex[2])));
		
		$operacao[$i][0]= 1;
		$operacao[$i][1]= $entrada_completa[0];
		$operacao[$i][2]= $entrada_completa[1];
		$operacao[$i][3]= $rs_horario->hl;
		$operacao[$i][4]= formata_data_hifen($_GET["data"]);
		$i++;
		
		//se tem intervalo
		if (tem_intervalo_no_dia($rs_horario->id_intervalo, $_GET["id_dia"])) {
			$intervalo_inicio= explode(' ', calcula_horario_intervalo('i', $rs_horario->id_intervalo, $_GET["id_dia"], formata_data_hifen($_GET["data"])));
			$intervalo_fim= explode(' ', calcula_horario_intervalo('f', $rs_horario->id_intervalo, $_GET["id_dia"], formata_data_hifen($_GET["data"])));
			
			//saida para intervalo
			$operacao[$i][0]= 0;
			$operacao[$i][1]= $intervalo_inicio[0];
			$operacao[$i][2]= $intervalo_inicio[1];
			$operacao[$i][3]= $rs_horario->hl;
			$operacao[$i][4]= formata_data_hifen($_GET["data"]);
			$i++;
			
			//saida para intervalo
			$operacao[$i][0]= 1;
			$operacao[$i][1]= $intervalo_fim[0];
			$operacao[$i][2]= $intervalo_fim[1];
			$operacao[$i][3]= $rs_horario->hl;
			$operacao[$i][4]= formata_data_hifen($_GET["data"]);
			$i++;
		}
		
		$saida_auto= explode(":", $rs_horario->saida);
		
		//vira o dia
		if (intval($entrada_auto[0])>intval($saida_auto[0])) $soma++;
		
		$saida_completa= explode(" ", date("Y-m-d H:i:s", mktime($saida_auto[0], $saida_auto[1], $saida_auto[2], $dataex[1], $dataex[0]+$soma, $dataex[2])));
		
		//saida
		$operacao[$i][0]= 0;
		$operacao[$i][1]= $saida_completa[0];
		$operacao[$i][2]= $saida_completa[1];
		$operacao[$i][3]= $rs_horario->hl;
		$operacao[$i][4]= formata_data_hifen($_GET["data"]);
		$i++;
		
		for ($j=0; $j<$i; $j++) {
			/*echo "operação: ". $operacao[$j][0];
			echo "<br>data: ". $operacao[$j][1];
			echo "<br>hora: ". $operacao[$j][2];
			echo "<br>hl: ". $operacao[$j][3];
			echo "<br>vale_dia: ". $operacao[$j][4] ."<br><br><br>";*/
			
			$result_ponto= mysql_query("insert into rh_ponto (id_funcionario, data_batida, hora, data_hora_batida, tipo, hl, vale_dia, id_usuario)
										values
										('". $_GET["id_funcionario"] ."', '". $operacao[$j][1] ."', '". $operacao[$j][2] ."', '". $operacao[$j][1] ." ". $operacao[$j][2] ."',
										'". $operacao[$j][0] ."', '". $operacao[$j][3] ."', '". $operacao[$j][4] ."', '". $_SESSION["id_usuario"] ."')
										");
		}
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "rh/espelho";
		require_once("index2.php");
		
		
	}
	
	if (isset($_GET["arquivoExcluir"])) {
		$apagar= @unlink(CAMINHO . $_GET["arquivo"]);
		
		if ($apagar) echo "Arquivo excluído com sucesso!";
		else echo "Não foi possível excluir o arquivo!";
	}
	
	if (isset($_GET["cadastraNovoTipoPessoa"])) {
		$result= mysql_query("insert into pessoas_tipos
								(id_pessoa, tipo_pessoa, id_empresa)
								values
								('". $_GET["id_pessoa"] ."', '". $_GET["tipo_pessoa"] ."', '". $_SESSION["id_empresa"] ."')
								");
		
		$pagina= "financeiro/pessoa_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["verificaCpf"])) {
		$cpf= $_GET["cpf"];
		$sql= "select pessoas.id_pessoa from pessoas, rh_funcionarios
					where pessoas.cpf_cnpj = '". $cpf ."'
					and   pessoas.tipo = 'f'
					and   pessoas.id_pessoa = rh_funcionarios.id_funcionario
					and   rh_funcionarios.status_funcionario <> '2'
					";
		
		if ($_GET["id_pessoa"]!="")
			$sql .= " and pessoas.id_pessoa <> '". $_GET["id_pessoa"] ."' " ;
		
		$result= mysql_query($sql) or die(mysql_error());
		
		$campo[0]= "<input type=\"hidden\" name=\"passa_cpf\" id=\"passa_cpf\" value=\"\" class=\"escondido\" />";
		$campo[1]= "<input type=\"hidden\" name=\"passa_cpf\" id=\"passa_cpf\" value=\"1\" class=\"escondido\" />";
	
		if (mysql_num_rows($result)==0) {
			echo $campo[1] ."<span id=\"span_cpf_testa\" class=\"verde\">CPF disponível!</span>";
			echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
		}
		else {
			$rs= mysql_fetch_object($result);
			
			$result2= mysql_query("select * from pessoas, pessoas_tipos
								 	where pessoas.id_pessoa = pessoas_tipos.id_pessoa
									and   pessoas.id_pessoa = '". $rs->id_pessoa ."'
									");
			$linhas2= mysql_num_rows($result2);
			$como= " como ";
			
			$i=1;
			while ($rs2= mysql_fetch_object($result2)) {
				
				$como .= "<strong>". pega_tipo_pessoa($rs2->tipo_pessoa) ."</strong>";
				
				if ($i!=$linhas2) $como .= ", ";
				
				$i++;
			}
			
			echo $campo[0] ."<span id=\"span_cpf_testa\" class=\"vermelho\">CPF já cadastrado ". $como ."!</span>";
			
			if ($_GET["tipo_pessoa"]!='u') {
				echo "<br /><label>&nbsp;</label><a class=\"menor\" href=\"javascript:void(0);\" onclick=\"cadastraNovoTipoPessoa('". $rs->id_pessoa ."', '". $_GET["tipo_pessoa"] ."');\">&raquo; cadastrar como <strong>". pega_tipo_pessoa($_GET["tipo_pessoa"]) ."</strong></a>";	
			}
		}
	}
	
	if (isset($_GET["retornaCid"])) {
		$result= mysql_query("select CID10, DESCR from rh_cid10 where CID10 = '". $_GET["pesquisa_cid"] ."'");
		
		if (mysql_num_rows($result)==0) {
			echo "Doença não encontrada. Verifique veracidade!";
			echo "<input type=\"hidden\" name=\"cid10\" id=\"cid10\" value=\"\" class=\"escondido\" />";
		}
		else {
			$rs= mysql_fetch_object($result);
			echo strtoupper($rs->DESCR);
			echo "<input type=\"hidden\" name=\"cid10\" id=\"cid10\" value=\"". $rs->CID10 ."\" class=\"escondido\" />";
		}
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}

	
	if (isset($_GET["funcionarioStatus"])) {
		$result= mysql_query("update rh_funcionarios set status_funcionario= '". $_GET["status"] ."'
								where id_funcionario= '". $_GET["id_funcionario"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/funcionario_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["departamentoStatus"])) {
		$result= mysql_query("update rh_departamentos set status_departamento= '". $_GET["status"] ."'
								where id_departamento= '". $_GET["id_departamento"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/departamento_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["departamentoExcluir"])) {
		$result= mysql_query("delete from rh_departamentos
								where id_departamento= '". $_GET["id_departamento"] ."'
								limit 1
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/departamento_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["motivoExcluir"])) {
		$result= mysql_query("delete from rh_motivos
								where id_motivo= '". $_GET["id_motivo"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/motivo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["cargoStatus"])) {
		$result= mysql_query("update rh_cargos set status_cargo= '". $_GET["status"] ."'
								where id_cargo= '". $_GET["id_cargo"] ."'
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/cargo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["cargoExcluir"])) {
		$result_pre= mysql_query("select * from rh_carreiras
									where id_cargo= '". $_GET["id_cargo"] ."'
									");
		
		if (mysql_num_rows($result_pre)==0)
			$result= mysql_query("delete from rh_cargos
									where id_cargo= '". $_GET["id_cargo"] ."'
									");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/cargo_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["cartaoExcluir"])) {
		$result= mysql_query("delete from rh_cartoes
								where id_cartao= '". $_GET["id_cartao"] ."'
								and   id_empresa= '". $_SESSION["id_empresa"] ."'
								") or die(mysql_error());
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/cartao_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["feriadoExcluir"])) {
		$result= mysql_query("delete from rh_feriados
								where id_feriado= '". $_GET["id_feriado"] ."'
								and   id_empresa= '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if ($result) $msg= 0;
		else $msg=1;
		
		echo excluido_ou_nao($msg);
	}
	
	if (isset($_GET["VTLinhaExcluir"])) {
		$result= mysql_query("delete from rh_vt_linhas
								where id_linha= '". $_GET["id_linha"] ."'
								and   id_empresa= '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/vt_linha_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["turnoStatus"])) {
		$result= mysql_query("update rh_turnos set status_turno= '". $_GET["status"] ."'
								where id_turno= '". $_GET["id_turno"] ."'
								limit 1
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/turno_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["historicoExcluir"])) {
		$result= mysql_query("delete from rh_historico
								where id_historico= '". $_GET["id_historico"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if ($result) $msg= 0;
		else $msg=1;
			
		$pagina= "rh/historico_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["escalaTrocaExcluir"])) {
		
		inicia_transacao();
		$var=0;
		
		$result_pre= mysql_query("select * from rh_escala_troca
								 	where id_empresa= '". $_SESSION["id_empresa"] ."'
									and   id_escala_troca= '". $_GET["id_escala_troca"] ."'
									");
		$rs_pre= mysql_fetch_object($result_pre);
		
		$result1= mysql_query("update rh_escala
							  	set trabalha = '1'
								where id_funcionario= '". $rs_pre->id_funcionario_solicitante ."'
								and   data_escala= '". $rs_pre->data_escala_troca ."'
								and   trabalha= '0'
								limit 1
								");
		if (!$result1) $var++;
		
		$result2= mysql_query("update rh_escala
							  	set trabalha = '0'
								where id_funcionario= '". $rs_pre->id_funcionario_assume ."'
								and   data_escala= '". $rs_pre->data_escala_troca ."'
								and   trabalha= '1'
								limit 1
								");
		if (!$result2) $var++;
		
		$result3= mysql_query("delete from rh_escala_troca
								where id_escala_troca= '". $_GET["id_escala_troca"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result3) $var++;
		
		finaliza_transacao($var);
		$msg= $var;
			
		$pagina= "rh/escala_troca_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["carreiraExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from rh_carreiras
									where id_carreira= '". $_GET["id_carreira"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		
		if (!$result_pre) $var++;
		
		if (mysql_num_rows($result_pre)==1) {
			//pega os dados da carreira que quer excluir
			$rs_pre= mysql_fetch_object($result_pre);
			
			//se estiver excluindo a carreira atual, a anterior vira atual
			if ($rs_pre->atual==1) {
				$result1= mysql_query("select * from rh_carreiras
										where data < '". $rs_pre->data ."'
										and   id_funcionario = '". $rs_pre->id_funcionario ."'
										order by data desc limit 1
										");
				if (!$result1) $var++;
				$rs1= mysql_fetch_object($result1);
				
				$result2= mysql_query("update rh_carreiras set atual = '1'
										where id_carreira= '". $rs1->id_carreira ."' ");
				if (!$result2) $var++;
			}
			
			//se estiver excluindo uma demissao... precisa ativar denovo
			if ($rs_pre->id_acao_carreira==2) {
				$result3= mysql_query("update rh_funcionarios set status_funcionario = '1'
										where id_funcionario= '". $rs_pre->id_funcionario ."'
										and   id_empresa = '". $_SESSION["id_empresa"] ."'
										");
				if (!$result3) $var++;
			}
			
			$result4= mysql_query("delete from rh_carreiras where id_carreira= '". $rs_pre->id_carreira ."' ");
			if (!$result4) $var++;
						
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$id_funcionario= $rs_pre->id_funcionario;
		$pagina= "rh/carreira";
		require_once("index2.php");
	}
	
	if (isset($_GET["VTDescontoExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from rh_vt_descontos
							 	where id_vt_desconto= '". $_GET["id_vt_desconto"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "rh/vt";
		require_once("index2.php");
	}
	
	if (isset($_GET["HEAutorizacaoExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from rh_he_autorizacao
							 	where id_he_autorizacao= '". $_GET["id_he_autorizacao"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "rh/he_autorizacao";
		require_once("index2.php");
	}
	
	if (isset($_GET["substituicaoFuncaoExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from rh_substituicao_funcao
							 	where id_substituicao_funcao= '". $_GET["id_substituicao_funcao"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "rh/substituicao_funcao";
		require_once("index2.php");
	}
	
	if (isset($_GET["VTExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from rh_vt
							 	where id_vt= '". $_GET["id_vt"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$pagina= "rh/vt";
		require_once("index2.php");
	}
	
	if (isset($_GET["insalubridadeExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result= mysql_query("delete from rh_insalubridade
							 	where id_insalubridade= '". $_GET["id_insalubridade"] ."'
								and   id_empresa = '". $_SESSION["id_empresa"] ."'
								limit 1
								");
		if (!$result) $var++;
	
		finaliza_transacao($var);
		
		$msg= $var;
		
		$id_funcionario= $rs_pre->id_funcionario;
		$pagina= "rh/insalubridade";
		require_once("index2.php");
	}
	
	if (isset($_GET["bancoHorasExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from rh_ponto_banco
									where id_banco = '". $_GET["id_banco"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		
		if (!$result_pre) $var++;
		
		if (mysql_num_rows($result_pre)==1) {
			//pega os dados da carreira que quer excluir
			$rs_pre= mysql_fetch_object($result_pre);
			
			$result1= mysql_query("update rh_ponto_banco_atual set
									he = he - '". $rs_pre->he ."'
									where id_funcionario = '". $rs_pre->id_funcionario ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									and   tipo_he = '". $rs_pre->tipo_he ."' ") or die(mysql_error());
			if (!$result1) $var++;
				
			$result2= mysql_query("delete from rh_ponto_banco
									where id_banco = '". $_GET["id_banco"] ."'
									and   id_empresa = '". $_SESSION["id_empresa"] ."' ");
			if (!$result2) $var++;
						
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$id_funcionario= $rs_pre->id_funcionario;
		
		$pagina= "rh/banco";
		require_once("index2.php");
	}
	
	if (isset($_GET["turnoExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from rh_carreiras
									where id_turno= '". $_GET["id_turno"] ."' ");
		
		if (!$result_pre) $var++;
		
		if (mysql_num_rows($result_pre)==0) {
			$result1= mysql_query("delete from rh_turnos where id_turno= '". $_GET["id_turno"] ."'	");
			if (!$result1) $var++;
			
			$result2= mysql_query("delete from rh_turnos_horarios where id_turno= '". $_GET["id_turno"] ."' ");
			if (!$result2) $var++;
			
			$result3_pre= mysql_query("select * from rh_turnos_intervalos where id_turno= '". $_GET["id_turno"] ."' ");
			if (!$result3_pre) $var++;
			
			$i=0;
			while ($rs3_pre= mysql_fetch_object($result3_pre)) {
				$result3[$i]= mysql_query("delete from rh_turnos_intervalos_horarios where id_intervalo= '". $rs3_pre->id_intervalo ."' ");
				if (!$result3[$i]) $var++;
				$i++;
			}
			
			$result4= mysql_query("delete from rh_turnos_intervalos where id_turno= '". $_GET["id_turno"] ."' ");
			if (!$result4) $var++;
			
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$pagina= "rh/turno_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["intervaloExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from rh_carreiras
									where id_intervalo= '". $_GET["id_intervalo"] ."' ");
		
		if (!$result_pre) $var++;
		
		if (mysql_num_rows($result_pre)==0) {
						
			$result1= mysql_query("delete from rh_turnos_intervalos where id_intervalo= '". $_GET["id_intervalo"] ."' ");
			if (!$result1) $var++;
			
			$result2= mysql_query("delete from rh_turnos_intervalos_horarios where id_intervalo= '". $_GET["id_intervalo"] ."' ");
			if (!$result2) $var++;

			
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
			
		$acao= 'i';
		$pagina= "rh/turno_intervalo";
		require_once("index2.php");
	}
	
	if (isset($_GET["intervaloHorarioExcluir"])) {
		$var=0;
		inicia_transacao();
		
		$result2= mysql_query("delete from rh_turnos_intervalos_horarios
								where id_intervalo_horario = '". $_GET["id_intervalo_horario"] ."'
								and   id_intervalo = '". $_GET["id_intervalo"] ."' limit 1
								");
	
		if (!$result2) $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		//echo $msg;
		$acao= 'i';
		$pagina= "rh/turno_intervalo_horarios";
		require_once("index2.php");
	}
	
	if (isset($_GET["pegaHorario"])) {
		$rs= mysql_fetch_object(mysql_query("select ". $_GET["tipo"] ." as horario from rh_turnos_horarios
												where id_turno= '". $_GET["id_turno"] ."'
												and   id_dia = '". $_GET["id_dia"] ."'
												"));
		echo $rs->horario;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["horarioExcluir"])) {
		$id_funcionario= pega_id_funcionario_do_id_horario($_GET["id_horario"]);
		
		$rs= mysql_fetch_object(mysql_query("select ". $_GET["tipo"] ." as horario from rh_turnos_horarios
												where id_turno= '". $_GET["id_turno"] ."'
												and   id_dia = '". $_GET["id_dia"] ."'
												"));
		echo $rs->horario;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["afastamentoExcluir"])) {
		$var=0;
		inicia_transacao();
					
		$result1= mysql_query("delete from rh_afastamentos
								where id_afastamento= '". $_GET["id_afastamento"] ."'
								");
		if (!$result1) $var++;
		
		$result2= mysql_query("delete from rh_afastamentos_dias
								where id_afastamento= '". $_GET["id_afastamento"] ."'
								/* tipo_afastamento = '". $_GET["tipo_afastamento"] ."' and   */
								");
		if (!$result2) $var++;

		finaliza_transacao($var);
		
		$msg=$var;			
		$pagina= "rh/afastamento_listar";
		require_once("index2.php");
	}
	
	if (isset($_GET["alteraDepartamentos"])) {
		$result= mysql_query("select * from rh_departamentos
							 		where id_empresa = '". $_GET["id_empresa"] ."'
									order by departamento asc ");
		
		if ($_GET["condicao"]==1)
			$str_mais= "onchange=\"alteraTurnos(); alteraCargos();\"";
		if ($_GET["condicao"]==3)
			$str_mais= "onchange=\"alteraPastas();\"";
		
		$str= "<select name=\"id_departamento\" id=\"id_departamento\" title=\"Departamento\" ". $str_mais .">
				<option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_departamento ."\">". $rs->departamento ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraPastas"])) {
		$result= mysql_query("select * from dc_documentos_pastas
								where dc_documentos_pastas.id_departamento= '". $_GET["id_departamento"] ."' 
								". $str ."
								order by dc_documentos_pastas.pasta asc");
		
		$str= "<select name=\"id_pasta\" id=\"id_pasta\" title=\"Pasta\">
				<option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_pasta ."\">". $rs->pasta ." - ". $rs->nome_pasta ." (". ativo_inativo($rs->status_pasta) .")" ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	
	if (isset($_GET["alteraPessoas"])) {
		$result= mysql_query("select pessoas.id_pessoa, pessoas.nome_rz
									from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras
									where pessoas.id_pessoa = rh_funcionarios.id_pessoa
									and   pessoas.tipo = 'f'
									and   rh_enderecos.id_pessoa = pessoas.id_pessoa
									and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
									and   rh_carreiras.id_acao_carreira = '1'
									and   id_empresa = '". $_GET["id_empresa"] ."'
									order by pessoas.nome_rz asc
									") or die(mysql_error());
		
		$str= "<select name=\"id_pessoa\" id=\"id_pessoa\" title=\"Pessoa\">
				<option value=\"\">- NENHUM (CRIAR EM NOME DA EMPRESA) -</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_pessoa ."\">". $rs->nome_rz ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraFuncionariosAtivosInativos"])) {
		$result= mysql_query("select * from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras
									where pessoas.id_pessoa = rh_funcionarios.id_pessoa
									and   pessoas.tipo = 'f'
									and   rh_enderecos.id_pessoa = pessoas.id_pessoa
									and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
									and   rh_carreiras.id_acao_carreira = '1'
									and   (rh_funcionarios.status_funcionario = '". $_GET["status_funcionario"] ."')
									and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
									order by pessoas.nome_rz asc
									") or die(mysql_error());
		
		$str= "<select name=\"id_funcionario\" id=\"id_funcionario\" title=\"Funcionário\">
				<option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_funcionario ."\">". $rs->nome_rz ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraFuncionarios"])) {
		$result= mysql_query("select *
									from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras
									where pessoas.id_pessoa = rh_funcionarios.id_pessoa
									and   pessoas.tipo = 'f'
									and   rh_enderecos.id_pessoa = pessoas.id_pessoa
									and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
									and   rh_carreiras.id_acao_carreira = '1'
									and   (rh_funcionarios.status_funcionario = '1' or rh_funcionarios.status_funcionario = '-1')
									and   rh_funcionarios.id_empresa = '". $_GET["id_empresa"] ."'
									and   rh_funcionarios.id_funcionario not in
									(select id_funcionario from usuarios)
									order by pessoas.nome_rz asc
									") or die(mysql_error());
		
		$str= "<select name=\"id_funcionario\" id=\"id_funcionario\" title=\"Funcionário\">
				<option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_funcionario ."\">". $rs->nome_rz ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraCargos"])) {
		$result= mysql_query("select * from rh_cargos where id_departamento = '". $_GET["id_departamento"] ."'
									order by cargo asc ");
		
		$str= "<select name=\"id_cargo\" id=\"id_cargo\" title=\"Cargo\" \">
				<option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_cargo ."\">". $rs->cargo ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraTurnos"])) {
		$result= mysql_query("select * from rh_turnos
							 	where id_departamento = '". $_GET["id_departamento"] ."'
								and   status_turno = '1'
								order by turno asc ");
		
		$str= "<select name=\"id_turno\" id=\"id_turno\" title=\"Turno\"";
		if ($_GET["soh"]!=1) $str .=" onchange=\"alteraIntervalos(); ";
		$str .=" \"> <option value=\"\">---</option>";
		
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if ($i==1) $classe= " class=\"cor_sim\"";
			else $classe= " ";
			$i++;
			$str .= "<option ". $classe ." value=\"". $rs->id_turno ."\">". $rs->turno ."</option>";
			if ($i==2) $i=0;
		}
		
		$str .= "</select>";
		echo $str;
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["alteraIntervalos"])) {
		$result= mysql_query("select * from rh_turnos_intervalos
									where id_turno = '". $_GET["id_turno"] ."'
									order by intervalo asc ");
		
		if (mysql_num_rows($result)==0) {
			echo "<input type=\"hidden\" class=\"escondido\" name=\"id_intervalo\" id=\"id_intervalo\" value=\"0\" >";
			echo "Turno sem intervalo.";
		}
		else {
			$str= "<select name=\"id_intervalo\" id=\"id_intervalo\" title=\"Intervalo\">
					<option value=\"\">---</option>";
			
			$i=1;
			while ($rs= mysql_fetch_object($result)) {
				if ($i==1) $classe= " class=\"cor_sim\"";
				else $classe= " ";
				$i++;
				$str .= "<option ". $classe ." value=\"". $rs->id_intervalo ."\">". $rs->intervalo ."</option>";
				if ($i==2) $i=0;
			}
			
			$str .= "</select>";
			echo $str;
		}
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["atualizaHorarioTurno"])) {
		$result_pre= mysql_query("select rh_departamentos.id_empresa from rh_departamentos, rh_turnos, rh_turnos_horarios
									where rh_turnos_horarios.id_turno_horario = '". $_GET["id_turno_horario"] ."'
									and   rh_turnos.id_departamento = rh_departamentos.id_departamento
									and   rh_turnos.id_turno = rh_turnos_horarios.id_turno
									and   rh_departamentos.id_empresa = '". $_SESSION["id_empresa"] ."'
									");
		if (mysql_num_rows($result_pre)==1) {
			$result= mysql_query("update rh_turnos_horarios
									set ". $_GET["tipo"] ." = '". $_GET["horario"] ."'
									where id_turno_horario = '". $_GET["id_turno_horario"] ."'
									") or die(mysql_error());
			
			if (!$result)
				echo "<script language=\"javascript\">alert('Não foi possível atualizar o horário, tente novamente!');</script>";
		}
		echo "<script language=\"javascript\">habilitaCampo('enviar');</script>";
	}
	
	if (isset($_GET["pegaDadosTurnodoFuncionario"])) {
		$result= mysql_query("select * from rh_carreiras
									where atual = '1'
									and   id_empresa = '". $_SESSION["id_empresa"] ."'
									and   id_funcionario = '". $_GET["id_funcionario"] ."'
									") or die(mysql_error());
		$rs= mysql_fetch_object($result);
		
		$data= explode("/", $_GET["data_escala_troca"]);
		$id_dia= date("w", mktime(0, 0, 0, $data[1], $data[0], $data[2]));
		
		//echo traduz_dia($id_dia);
		
		$horarios= pega_horarios_turno($rs->id_turno, $id_dia);
		echo "<strong>Horário de trabalho:</strong> ". $horarios[0] ." até ". $horarios[1];
		
		$result2= mysql_query("select * from rh_escala
									where id_funcionario = '". $_GET["id_funcionario"] ."'
									and   data_escala= '". formata_data($_GET["data_escala_troca"]) ."'
									and   trabalha= '1'
									") or die(mysql_error());
		$rs2= mysql_fetch_object($result2);
		
		if ($rs2->trabalha==1) $classe= "verde";
		else $classe= "vermelho";
		
		echo "<br /><label>&nbsp;</label><strong>Trabalha neste dia:</strong> <span class=\"". $classe ."\">". sim_nao($rs2->trabalha) ."</span>"; 
		
	}
}

/* ---------- ESTOQUE ------------------------------------------------------------------- */

if (pode("iq|", $_SESSION["permissao"])) {

	if (isset($_GET["precoConsulta"])) {
		$pagina= "financeiro/estoque_preco_consulta";
		require_once("index2.php");
	}
	
	if (isset($_GET["itemPesquisar"])) {
		
		switch ($_GET["origem"]) {
			//se for saida... soh mostra oq tem no estoque
			case "s":
					$sql= "select * from fi_itens, fi_estoque
										where (fi_itens.item like '%". $_GET["pesquisa"] ."%'
												or fi_itens.apelidos like '%". $_GET["pesquisa"] ."%')
										and   fi_itens.id_item = fi_estoque.id_item
										and   fi_estoque.id_empresa = '". $_SESSION["id_empresa"] ."'
										and   fi_estoque.qtde_atual > '0'
										order by fi_itens.item asc
										";
					break;
			//entrada
			case "e":
			//pesquisa de preço
			case "p":
					$sql= "select * from fi_itens
								where (fi_itens.item like '%". $_GET["pesquisa"] ."%'
										or fi_itens.apelidos like '%". $_GET["pesquisa"] ."%')
								order by fi_itens.item asc
								";
					break;
		}
		//echo $sql;
		$result= mysql_query($sql) or die("1: ". mysql_error());	
		
		//volta as solicitacoes em select
		if ($_GET["modo"]=="select") {
			echo "<select name=\"id_item[]\" id=\"id_item_". $_GET["cont"] ."\" onchange=\"processaDecimal('". $_GET["cont"] ."'); alteraTipoEstoqueCC('". $_GET["cont"] ."', this.value);\">";
			
			if (mysql_num_rows($result)==0)
				echo "<option value=\"\">Nenhum registro encontrado!</li>";
			else {
				echo "<option value=\"\">---</li>";
				$i=0;
				while ($rs= mysql_fetch_object($result)) {
					$qtde_atual= pega_qtde_atual_item($_SESSION["id_empresa"], $rs->id_item);
					$qtde_atual= fnumf($qtde_atual);
					
					if (($i%2)==1) $classe= "cor_sim";
					else $classe= "cor_nao";
					
					$var= "<option class=\"". $classe ."\" value=\"". $rs->id_item ."\">". $rs->item ." - ". $qtde_atual ." ". pega_tipo_apres($rs->tipo_apres) ."</option>";
																				
					echo $var;
					$i++;
				}
			}
			echo "</select>";
		}
		//volta em link da lista
		else {
			
			if (mysql_num_rows($result)==0)
				echo "<li class=\"espacamento vermelho\">Nenhum registro encontrado!</li>";
			else {
				echo "<ul class=\"recuo2\">";
				$i=0;
				while ($rs= mysql_fetch_object($result)) {
					$qtde_atual= pega_qtde_atual_item($_SESSION["id_empresa"], $rs->id_item, $rs->tipo_apres);
						
					//if ($rs->apelidos!="") $apelidos= "onmouseover=\"Tip('Também conhecido como: ". $rs->apelidos ."');\"";
					//else $apelidos= "";
					
					if ($rs->tipo_apres=="t") $str_acao= "habilitaFormatacaoDecimal(1, 'qtde');";
					else $str_acao= "habilitaFormatacaoDecimal(0, 'qtde');";
					
					switch ($_GET["origem"]) {
						case "s":
								$result_cct= mysql_query("select distinct(id_centro_custo_tipo) from fi_estoque_mov
															where tipo_trans = 'e'
															and   id_item = '". $rs->id_item ."'
															and   id_centro_custo_tipo <> ''
															and   id_centro_custo_tipo <> '0'
															");
								$id_ccts= "@";
								$ccts= "";
								
								while ($rs_cct= mysql_fetch_object($result_cct)) {
									$id_ccts .= $rs_cct->id_centro_custo_tipo ."@";
									$ccts .= pega_centro_custo_tipo($rs_cct->id_centro_custo_tipo) .";<br />";
								}
								
								$str_acao .= "
												atribuiValor('id_item', '". $rs->id_item ."');
												atribuiValor('tit_item', '". $rs->item ."');
												habilitaCampo('qtde');
												habilitaCampo('enviar');
												habilitaCampo('id_deposito');
												atribuiValor('id_ccts', '". $id_ccts ."');
												habilitaCampo('id_motivo');
												atribuiValor('tit_qtde', '". fnumf($qtde_atual) ."');
												atribuiValor('tit_apres', '". pega_tipo_apres($rs->tipo_apres) ."');
											";
						break;
						case "e":
							$str_acao .= "
											atribuiValor('id_item', '". $rs->id_item ."');
											atribuiValor('tit_item', '". $rs->item ."');
											habilitaCampo('qtde');
											habilitaCampo('enviar');
											habilitaCampo('valor_unitario');
											atribuiValor('tit_qtde', '". fnumf($qtde_atual) ."');
											atribuiValor('tit_apres', '". pega_tipo_apres($rs->tipo_apres) ."');
										";
						break;
						case "p":
							$str_acao= "
											ajaxLink('preco_atualiza', 'precoConsulta&id_item=". $rs->id_item ."');
									";
						break;
					}
					
					if ($ccts!="") $ccts_tip= "onmouseover=\"Tip('". $ccts ."');\"";
					else $ccts_tip= "";
					
					$var= "<li><a $ccts_tip href=\"javascript:void(0);\" onclick=\"
																			". $str_acao ."
																			\">
																			". addslashes($rs->item) ."</a></li>";
																				
					echo $var;
					$i++;
				}
				echo "</ul>";
			}
		}//fim modo ul
		
		//@logs($_SESSION["id_acesso"], $_SESSION["id_usuario"], $_SESSION["id_empresa"], 1, "pesquisa produto na telinha, termo: ". $_GET["pesquisa"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	}
	
	if (isset($_GET["itemDepositoPesquisar"])) {
		
		switch ($_GET["origem"]) {
			//se for saida... soh mostra oq tem no estoque
			case "s":
					$sql= "select * from fi_itens, fi_estoque_deposito
										where (fi_itens.item like '%". $_GET["pesquisa"] ."%'
												or fi_itens.apelidos like '%". $_GET["pesquisa"] ."%')
										and   fi_itens.id_item = fi_estoque_deposito.id_item
										and   fi_estoque_deposito.id_empresa = '". $_SESSION["id_empresa"] ."'
										and   fi_estoque_deposito.id_deposito = '". $_GET["id_deposito"] ."'
										and   fi_estoque_deposito.qtde_atual > '0'
										order by fi_itens.item asc
										";
					break;
		}
		//echo $sql;
		$result= mysql_query($sql) or die("1: ". mysql_error());	
		
		//volta as solicitacoes em select
		if ($_GET["modo"]=="select") {
			echo "<select name=\"id_item[]\" id=\"id_item_". $_GET["cont"] ."\" onchange=\"processaDecimal('". $_GET["cont"] ."'); alteraTipoEstoqueCC('". $_GET["cont"] ."', this.value);\">";
			
			if (mysql_num_rows($result)==0)
				echo "<option value=\"\">Nenhum registro encontrado!</li>";
			else {
				echo "<option value=\"\">---</li>";
				$i=0;
				while ($rs= mysql_fetch_object($result)) {
					$qtde_atual= pega_qtde_atual_item($_SESSION["id_empresa"], $rs->id_item);
					$qtde_atual= fnumf($qtde_atual);
					
					if (($i%2)==1) $classe= "cor_sim";
					else $classe= "cor_nao";
					
					$var= "<option class=\"". $classe ."\" value=\"". $rs->id_item ."\">". $rs->item ." - ". $qtde_atual ." ". pega_tipo_apres($rs->tipo_apres) ."</option>";
																				
					echo $var;
					$i++;
				}
			}
			echo "</select>";
		}
		//volta em link da lista
		else {
			if (mysql_num_rows($result)==0)
				echo "<li class=\"vermelho\">Nenhum registro encontrado!</li>";
			else {
				echo "<ul class=\"recuo2\">";
				$i=0;
				while ($rs= mysql_fetch_object($result)) {
					$qtde_atual= pega_qtde_atual_item_deposito($_GET["id_deposito"], $rs->id_item, $rs->tipo_apres);
						
					if ($rs->tipo_apres=="t") $str_acao= "habilitaFormatacaoDecimal(1, 'qtde');";
					else $str_acao= "habilitaFormatacaoDecimal(0, 'qtde');";
					
					switch ($_GET["origem"]) {
						case "s":
								$str_acao .= "
												atribuiValor('id_item', '". $rs->id_item ."');
												atribuiValor('tit_item', '". $rs->item ."');
												habilitaCampo('qtde');
												habilitaCampo('enviar');
												habilitaCampo('observacoes');
												habilitaCampo('id_motivo');
												atribuiValor('tit_qtde', '". fnumf($qtde_atual) ."');
												atribuiValor('tit_apres', '". pega_tipo_apres($rs->tipo_apres) ."');
											";
						break;
					}
					
					if ($ccts!="") $ccts_tip= "onmouseover=\"Tip('". $ccts ."');\"";
					else $ccts_tip= "";
					
					$var= "<li><a $ccts_tip href=\"javascript:void(0);\" onclick=\"
																			". $str_acao ."
																			\">
																			". $rs->item ."</a></li>";
																				
					echo $var;
					$i++;
				}
				echo "</ul>";
			}
		}//fim modo ul
		
		//@logs($_SESSION["id_acesso"], $_SESSION["id_usuario"], $_SESSION["id_empresa"], 1, "pesquisa produto na telinha, termo: ". $_GET["pesquisa"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
	}
	
	if (isset($_GET["itemExcluir"])) {
		
		$var=0;
		inicia_transacao();
		
		$result_pre= mysql_query("select * from fi_estoque_mov
										where id_item = '". $_GET["id_item"] ."'
										");

		if (mysql_num_rows($result_pre)==0) {
			$rs= mysql_fetch_object(mysql_query("select * from fi_itens where id_item= '". $_GET["id_item"] ."' "));
			
			$result1= mysql_query("delete from fi_itens
									where id_item = '". $_GET["id_item"] ."'
									limit 1
									");
			if (!$result1) $var++;
		} else $var++;
		
		finaliza_transacao($var);
		
		$msg= $var;
		
		$letra= strtolower(substr($rs->item, 0, 1));
		$pagina= "financeiro/item_esquema";
		require_once("index2.php");
	}
	
	if (isset($_GET["itemInserir"])) {
		if ($_GET["item"]!="") {
			$result_antes= mysql_query("select item from fi_itens
											where item = '". $_GET["item"] ."'
											and   tipo_apres = '". $_GET["tipo_apres"] ."'
											");
	
			if (mysql_num_rows($result_antes)==0) {
				$result= mysql_query("insert into fi_itens (item, tipo_apres, id_centro_custo_tipo, id_usuario)
								values ('". strtoupper($_GET["item"]) ."', '". $_GET["tipo_apres"] ."',
										'". $_GET["id_centro_custo_tipo"] ."', '". $_SESSION["id_usuario"] ."') ");
			}
		}
	
		echo "<script language='javascript' type='text/javascript'>;";
		if ($result) {
			//@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 1, "insere remédio, ID ". mysql_insert_id() ." | ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "atribuiValor('item', '');";
			echo "fechaDiv('item_cadastro');";
			echo "alert('Produto cadastrado com sucesso!');";
		}
		else {
			//@logs($_SESSION["id_acesso"], $_SESSION["id_usuario_sessao"], $_SESSION["id_cidade_sessao"], $_SESSION["id_posto_sessao"], 0, "falha ao inserir remédio, ". $_POST["remedio"], $_SERVER["REMOTE_ADDR"].":".$_SERVER["REMOTE_PORT"], gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$_SERVER["REMOTE_PORT"]);
			echo "alert('Item já cadastrado!');";
		}
		echo "</script>";
	}
	
	if (isset($_GET["alteraNotaCentroCusto"])) {
		
		$result_nota_item= mysql_query("select * from fi_notas_itens
									   		where id_nota = '". $_GET["id_nota"] ."'
											and   id_nota_item = '". $_GET["id_nota_item"] ."'
											");
		$rs_nota_item= mysql_fetch_object($result_nota_item);
		
		$id_pessoa_nota= pega_id_cedente_nota($_GET["id_nota"]);
		
		if ($_GET["id_centro_custo_tipo"]!="")
			$id_centro_custo_tipo= $_GET["id_centro_custo_tipo"];
		else
			$id_centro_custo_tipo= pega_id_centro_custo_tipo_pelo_id_item($_GET["id_item"]);
		
		if ($id_pessoa_nota!="") {
			$colc= "[]";
			$str_add= "and   id_centro_custo IN
						(
						select fi_cc_ct.id_centro_custo
						from   fi_centro_custos_tipos, fi_cc_ct, fi_pessoas_cc_tipos
						where fi_centro_custos_tipos.id_empresa = '". $_SESSION["id_empresa"] ."'
						and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_cc_ct.id_centro_custo_tipo
						and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_pessoas_cc_tipos.id_centro_custo_tipo
						and   fi_pessoas_cc_tipos.id_pessoa = '$id_pessoa_nota'
						 )";
		}
		else $colc= "";
		
		?>
        
        <label>Centro de custo:</label>
        <select name="id_centro_custo<?=$colc;?>" id="id_centro_custo" title="Centro de custo">
			<? /*<option selected="selected" value="">-</option>*/ ?>
			<?
            $result_cc= mysql_query("select * from  fi_centro_custos
                                        where id_empresa = '". $_SESSION["id_empresa"] ."'
										$str_add
                                        order by centro_custo asc
                                        ") or die(mysql_error());
            while ($rs_cc= mysql_fetch_object($result_cc)) {
            ?>
            <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cc->id_centro_custo; ?>" <? if ($rs_cc->id_centro_custo==$rs_nota_item->id_centro_custo) echo "selected=\"selected\""; ?>><?= $rs_cc->centro_custo; ?></option>
            <? $i++; } ?>
        </select>
        <br />

        <label>Tipo:</label>
        <select name="id_centro_custo_tipo<?=$colc;?>" id="id_centro_custo_tipo" title="Centro de custo">
			<?
            /*$result_cc= mysql_query("select *
                                        from  fi_centro_custos
                                        where id_empresa = '". $_SESSION["id_empresa"] ."'
										$str_add
                                        order by centro_custo asc
                                        ") or die(mysql_error());
            while ($rs_cc= mysql_fetch_object($result_cc)) {
            ?>
            <optgroup label="<?= $rs_cc->centro_custo; ?>">
                <?
				*/
                //if ($id_pessoa_nota!="") {
					/*$sql_cc2= "select distinct(fi_centro_custos_tipos.id_centro_custo_tipo)
                                            from  fi_centro_custos_tipos, fi_pessoas_cc_tipos, fi_cc_ct
                                            where fi_centro_custos_tipos.id_empresa = '". $_SESSION["id_empresa"] ."'
											and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_pessoas_cc_tipos.id_centro_custo_tipo
											and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_cc_ct.id_centro_custo_tipo
											
											$xstr_add
											
											and   fi_cc_ct.id_centro_custo = '". $rs_cc->id_centro_custo ."'
											
											and   fi_pessoas_cc_tipos.id_pessoa = '$id_pessoa_nota'
                                            order by fi_centro_custos_tipos.centro_custo_tipo asc
                                            ";*/
				//}
				//else
					$sql_cc2= "select distinct(fi_centro_custos_tipos.id_centro_custo_tipo)
                                            from  fi_centro_custos_tipos, fi_cc_ct
                                            where fi_centro_custos_tipos.id_empresa = '". $_SESSION["id_empresa"] ."'
											
                                            and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_cc_ct.id_centro_custo_tipo
											/* and   fi_cc_ct.id_centro_custo = '". $rs_cc->id_centro_custo ."' */
                                            order by fi_centro_custos_tipos.centro_custo_tipo asc
                                            ";
				
				$result_cc2= mysql_query($sql_cc2) or die(mysql_error());
				
                $i=0;
                while ($rs_cc2= mysql_fetch_object($result_cc2)) {
                    /*if ($acao=='e') {
                        $result_cc3= mysql_query("select * from fi_pessoas_cc_tipos
                                                    where id_pessoa = '". $rs->id_pessoa ."'
                                                    and   id_centro_custo_tipo = '". $rs_cc2->id_centro_custo_tipo ."'
                                                    ");
                        $linhas_cc3= mysql_num_rows($result_cc3);
                    }*/
                ?>
                <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cc2->id_centro_custo_tipo; ?>"<? if ($rs_cc2->id_centro_custo_tipo==$id_centro_custo_tipo) echo "selected=\"selected\""; ?>><?= pega_centro_custo_tipo($rs_cc2->id_centro_custo_tipo); ?></option>
                <? $i++; } ?>
            <? /*</optgroup>
            <? } */ ?>
        </select>
        <br />
        
        <label for="descricao_<?=$k;?>">Descrição:</label>
        <input title="Descrição" name="descricao[]" id="descricao_<?=$k;?>" value="<?= $rs_nota_item->descricao;?>" />
        <br />
        <?
	}
	
	if (isset($_GET["alteraSaidaCentroCustoTipo2"])) {
		if (($_GET["id_ccts"]!="") && ($_GET["id_ccts"]!="@")) {
			$id_ccts= substr($_GET["id_ccts"], 1);
			$id_ccts= substr($_GET["id_ccts"], 1, -1);
			
			$id_cct_vetor= explode("@", $id_ccts);
			
			$str_add_parte= "";
			$i=0;
			while ($id_cct_vetor[$i]) {
				$j= $i+1;
				
				$str_add_parte .= "fi_cc_ct.id_centro_custo_tipo = '". $id_cct_vetor[$i] ."' ";
				
				if ($j!=sizeof($id_cct_vetor)) $str_add_parte .=" or ";
				
				$i++;
			}
		}
		else $str_add_parte= "1=1";
		?>
        <label>Tipo:</label>
        <select name="id_centro_custo_tipo" id="id_centro_custo_tipo" title="Tipo">
			<?
            $result_cc= mysql_query("select *
                                        from  fi_centro_custos_tipos, fi_cc_ct
                                        where fi_centro_custos_tipos.id_empresa = '". $_SESSION["id_empresa"] ."'
										and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_cc_ct.id_centro_custo_tipo
										and   fi_cc_ct.id_centro_custo = '". $_GET["id_centro_custo"] ."'
										/* and   ( $str_add_parte ) */
                                        order by fi_centro_custos_tipos.centro_custo_tipo asc
                                        ") or die(mysql_error());
            $i=0;
			while ($rs_cc= mysql_fetch_object($result_cc)) {
            ?>
            <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cc->id_centro_custo_tipo; ?>"><?= $rs_cc->centro_custo_tipo; ?></option>
            <? $i++; } ?>
        </select>
        <?
	}
	
	if (isset($_GET["alteraSaidaCentroCusto2"])) {
		if (($_GET["id_ccts"]!="") && ($_GET["id_ccts"]!="@")) {
			$id_ccts= substr($_GET["id_ccts"], 1);
			$id_ccts= substr($_GET["id_ccts"], 1, -1);
			
			$id_cct_vetor= explode("@", $id_ccts);
			
			$str_add_parte= "";
			$i=0;
			while ($id_cct_vetor[$i]) {
				$j= $i+1;
				
				$str_add_parte .= "fi_cc_ct.id_centro_custo_tipo = '". $id_cct_vetor[$i] ."' ";
				
				if ($j!=sizeof($id_cct_vetor)) $str_add_parte .=" or ";
				
				$i++;
			}
			
			$str_add= "and   id_centro_custo IN
						(
						select fi_cc_ct.id_centro_custo
						from   fi_centro_custos_tipos, fi_cc_ct
						where fi_centro_custos_tipos.id_empresa = '". $_SESSION["id_empresa"] ."'
						and   fi_centro_custos_tipos.id_centro_custo_tipo = fi_cc_ct.id_centro_custo_tipo
						/* and   ( $str_add_parte ) */
						 )";
		}
		//echo "xx". $_GET["id_ccts"];
		
		$result_cc= mysql_query("select *
                                        from  fi_centro_custos
                                        where id_empresa = '". $_SESSION["id_empresa"] ."'
										$str_add
                                        order by centro_custo asc
                                        ") or die(mysql_error());
		
		?>
        <label>Centro de custo:</label>
        <select name="id_centro_custo" id="id_centro_custo" title="Centro de custo" onchange="pegaCCTipos(this.value);">
			<? if (mysql_num_rows($result_cc)>1) { ?>
            <option value="">---</option>
            <? } ?>
			<?
			$i=0;
			while ($rs_cc= mysql_fetch_object($result_cc)) {
				$id_centro_custo_aqui= $rs_cc->id_centro_custo;
            ?>
            <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cc->id_centro_custo; ?>"><?= $rs_cc->centro_custo; ?></option>
            <? $i++; } ?>
        </select>
        
        <? if (mysql_num_rows($result_cc)==1) { ?>
        <script language="javascript">
			pegaCCTipos('<?=$id_centro_custo_aqui;?>');
		</script>
        <? } ?>
    <?
	}
	
}//fim pode estoque

/* ---------------------------------------------------------------------------------------------------- */

echo "</body></html>";

/* <div id="temp">
	<strong>id_usuario:</strong> <?= $_SESSION["id_usuario"]; ?> <br />
	<strong>tipo_usuario:</strong> <?= $_SESSION["tipo_usuario"]; ?> <br />
	<strong>id_empresa:</strong> <?= $_SESSION["id_empresa"]; ?> <br />
	<strong>nome:</strong> <?= $_SESSION["nome"]; ?> <br />
	<strong>permissao:</strong> <?= $_SESSION["permissao"]; ?> <br />
	<strong>trocando:</strong> <?= $_SESSION["trocando"]; ?>
</div>
*/
            
?>