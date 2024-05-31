<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
$_SESSION["acao"]= md5("asd". rand(1, 30) . time());
?>
    <div id="alerta_centro" class="escondido">
    	<a href="javascript:void(0);" onclick="fechaDiv('alerta_centro');" class="fechar">x</a>
        
        <h2 class="titulos">Atenção</h2>
        
    	<p>No sistema já existe(m) pessoa(s) com nome parecido:</p>
        
        <div id="pessoas_repetidas_resultado">
        </div>
        
        <br /><br />
        <center>Confira para não criar cadastros repetidos!</center>
    </div>
    
    <h2>Cadastro de pessoa</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('tela_cadastro');" class="fechar">x</a>
	
    <? if (@pode("c", $_SESSION["permissao"])) { ?>
    <a href="javascript:void(0);" onclick="abreFechaCadastroSocial('i', '');" class="social">dados sociais</a>
    <? } ?>
    
	<div id="formulario">
	
		<form action="<?= AJAX_FORM; ?>formPessoaInserir" id="formPessoaInserir" name="formPessoaInserir" method="post" onsubmit="return ajaxForm('formulario', 'formPessoaInserir');">
			<?
			if ($_GET["id_responsavel"]!="") {
				$id_responsavel= $_GET["id_responsavel"];
				$responsavel= pega_nome($id_responsavel);
			}
			else
				$id_responsavel= "0";

			if ($_SESSION["id_posto_sessao"]!="")
				$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
			if ($_SESSION["id_cidade_sessao"]!="")
				$id_cidade= $_SESSION["id_cidade_sessao"];
			?>
            
            <input name="modo_cadastro_cpf" id="modo_cadastro_cpf" type="hidden" value="<?= pega_modo_cadastro_cpf($id_cidade); ?>" class="escondido" />
            <input name="id_responsavel" id="id_responsavel" type="hidden" value="<?= $id_responsavel; ?>" class="escondido" />
			<input name="acao" id="acao" type="hidden" value="<?= $acao; ?>" class="escondido" />
			<input name="retorno" id="retorno" type="hidden" value="<?= $_GET["retorno"]; ?>" class="escondido" />
            <input name="dados_sociais" id="dados_sociais" type="hidden" value="0" class="escondido" />
			
			<div id="responsavel_atualiza">
				<? 
				if ($id_responsavel!="0") {
					echo "<label>Responsável:</label>
							". $responsavel ." (". formata_cpf(pega_cpf_pelo_id_pessoa($id_responsavel)) .")
							<br /><br />
							";
					echo "<script language='javascript' type='text/javascript'>
							preencheDiv('cpf_mesmo', 'não necessário');
							</script>";
					}
				?>
			</div>
			
			<label for="nome">Nome:</label>
			<input name="nome" id="nome" onblur="buscaNomeParecido();" />
			<br />
			
			<label for="sexo">Sexo:</label>
			<input type="radio" name="sexo" id="sexo_m" value="m" class="tamanho15" /> <label class="tamanho50 nao_negrito" for="sexo_m">Masculino</label>
			<input type="radio" name="sexo" id="sexo_f" value="f" class="tamanho15" /> <label class="tamanho50 nao_negrito" for="sexo_f">Feminino</label>
			<br />

			<label for="raca">Raça:</label>
			<input type="radio" name="raca" id="raca_b" value="b" class="tamanho15" /> <label class="tamanho30 nao_negrito" for="raca_b">Branco</label>
			<input type="radio" name="raca" id="raca_n" value="n" class="tamanho15" /> <label class="tamanho30 nao_negrito" for="raca_n">Negro</label>
            <input type="radio" name="raca" id="raca_a" value="a" class="tamanho15" /> <label class="tamanho30 nao_negrito" for="raca_a">Amarelo</label>
			<br />
	
			<div id="cpf_atualiza">
				<label for="cpf">CPF:</label>
				<div id="cpf_mesmo">
				</div>
				<?
				if ($_GET["cpf"]!="") {
					echo formata_cpf($_GET["cpf"]);
					echo "<input name=\"cpf_cadastro\" id=\"cpf_cadastro\" type=\"hidden\" value=\"". $_GET["cpf"] ."\" class=\"escondido\" />";
				}
				elseif ($id_responsavel=="0") {
					echo "<input name=\"cpf_cadastro\" id=\"cpf_cadastro\" maxlength=\"11\" onblur=\"usuarioRetornaCpfDisponibilidade();\" />";
				?>
				
				<br />
				<label>&nbsp;</label>
				<div id="cpf_disponibilidade">
					<input type="hidden" class="escondido" name="cpf_disponivel" id="cpf_disponivel" value="0" />
				</div>
                <? } ?>
				<br />
			</div>
			
            <label for="nome_mae">Nome da mãe:</label>
			<input name="nome_mae" id="nome_mae" maxlength="255" />
			<br />
			
			<label for="nome_pai">Nome do pai:</label>
			<input name="nome_pai" id="nome_pai" maxlength="255" />
			<br />
            
            <label for="nome_madrasta">Nome da madrasta:</label>
			<input name="nome_madrasta" id="nome_madrasta" value="<?= $rs->nome_madrasta; ?>" maxlength="255" />
			<br />
			
			<label for="nome_padrasto">Nome do padastro:</label>
			<input name="nome_padrasto" id="nome_padrasto" maxlength="255" />
			<br />
            
			<label for="rg">RG:</label>
			<input name="rg" id="rg" maxlength="15" />
			<br />
			
            <label for="orgao_emissor_rg">Órgão emissor:</label>
			<input name="orgao_emissor_rg" id="orgao_emissor_rg" maxlength="10" class="tamanho100" />
			<br />
            
			<label for="data_nasc">Data de nasc.:</label>
			<input name="data_nasc" id="data_nasc" maxlength="10" onkeyup="formataData(this);" class="tamanho100" />
			<br />

			<label for="endereco">Endere&ccedil;o:</label>
			<input name="endereco" id="endereco" />
			<br />
	
			<label for="bairro">Bairro:</label>
			<input name="bairro" id="bairro" />
			<br />
	
			<label for="complemento">Complemento:</label>
			<input name="complemento" id="complemento" />
			<br />
	
			<label for="cep">CEP:</label>
			<input name="cep" id="cep" maxlength="8" class="tamanho100" />
			<br />
			
			<div id="localizacao">
				<label for="id_cidade">Cidade/UF:</label>
				<?= pega_cidade($_SESSION["id_cidade_pref"]); ?>
				<input class="escondido" type="hidden" name="id_uf" id="id_uf" value="<?= $_SESSION["id_uf_pref"]; ?>" />
				<input class="escondido" type="hidden" name="id_cidade" id="id_cidade" value="<?= $_SESSION["id_cidade_pref"]; ?>" />
				
				<br />
				<label>&nbsp;</label>
				<a href="javascript:void(0);" onclick="desabilitaCampo('enviar'); ajaxLink('localizacao', 'carregaPaginaInterna&pagina=localizacao');">alterar cidade</a>
				<br />
                
                <label for="id_psf">PSF:</label>
                <select name="id_psf" id="id_psf">
                    <option value="">--- NÃO SEI</option>
                    <?
                    $result_postos= mysql_query("select postos.id_posto, postos.posto from postos
                                                    where postos.id_cidade = '". $_SESSION["id_cidade_pref"] ."'
                                                    and   postos.situacao = '1'
                                                    and   postos.psf= '1'
                                                    order by postos.posto");
                    $i= 0;
                    while($rs_postos= mysql_fetch_object($result_postos)) {
                        if (($i%2)==0)
                            $classe= "class=\"cor_sim\"";
                        else
                            $classe= "";
                        
						if ($rs_postos->id_posto==$_SESSION["id_posto_sessao"])
							$selecionavel= " selected=\"selected\" ";
						else
							$selecionavel= "";
						
                        echo "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
                        $i++;
                    }
                    ?>
                </select>
                <br />
			</div>
    
			<label for="telelefone">Telefone:</label>
			<input name="telefone" id="telefone" />
			<br />
            
            <label for="cartao_sus">Cartão SUS:</label>
			<input name="cartao_sus" id="cartao_sus" />
			<br />

            <label for="observacoes">Observações:</label>
			<textarea name="observacoes" id="observacoes"></textarea>
            <br />
			
            <? if (@pode("c", $_SESSION["permissao"])) { ?>
            <div id="cadastro_social" class="nao_mostra">
            	<fieldset>
                	<legend>Levantamento sócio-econômico</legend>
                    
                    <div id="formulario_social">
                    
                    </div>
                    
                </fieldset>
            </div>
            <? } ?>
            
			<label for="enviar">&nbsp;</label>
			<button type="submit" id="enviar">Cadastrar >></button>
			<br />
		</form>		
	</div>
    <script language="javascript">
		daFoco("nome");
	</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>