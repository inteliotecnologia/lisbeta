<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
$_SESSION["acao"]= md5("asd". rand(1, 30) . time());

//cidade atual (rodando o sistema)
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$result= mysql_query("select pessoas.*, DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc, ufs.id_uf from pessoas, cidades, ufs
							where pessoas.id_pessoa = '". $_GET["id_pessoa"] ."'
							and   pessoas.id_cidade = cidades.id_cidade
							and   cidades.id_uf = ufs.id_uf
							");
$rs= mysql_fetch_object($result);

if ($rs->data_nasc!="00/00/0000")
	$data_nasc= $rs->data_nasc;

if ($rs->origem=="c")
	$id_cidade_cadastro= $rs->id_origem;
else
	$id_cidade_cadastro= pega_id_cidade_do_posto($rs->id_origem);


if (($id_cidade_emula!=$rs->id_cidade) && ($id_cidade_cadastro!=$id_cidade_emula))
	$trancar= true;
else
	$trancar= false;

if (tem_dados_sociais($rs->id_pessoa))
	$tem_dados_sociais= 1;
else
	$tem_dados_sociais= 0;
?>
	<h2>Cadastro de pessoa</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('tela_cadastro');" class="fechar">x</a>
	
	<? if (@pode("c", $_SESSION["permissao"])) { ?>
    <a href="javascript:void(0);" onclick="abreFechaCadastroSocial('e', '<?= $rs->id_pessoa; ?>');" class="social">dados sociais</a>
    <? } ?>
    
	<div id="formulario">
		
		<form action="<?= AJAX_FORM; ?>formPessoaEditar" id="formPessoaEditar" name="formPessoaEditar" method="post" onsubmit="return ajaxForm('formulario', 'formPessoaEditar');">
			
			<?
			if ($rs->id_responsavel!="") {
				$id_responsavel= $rs->id_responsavel;
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
			<input name="id_pessoa" id="id_pessoa" type="hidden" value="<?= $rs->id_pessoa; ?>" class="escondido" />
			<input name="acao" id="acao" type="hidden" value="<?= $acao; ?>" class="escondido" />
			<input name="retorno" id="retorno" type="hidden" value="<?= $_GET["retorno"]; ?>" class="escondido" />
            <input name="dados_sociais" id="dados_sociais" type="hidden" value="<?= $tem_dados_sociais; ?>" class="escondido" />
			
			<div id="responsavel_atualiza">
				<?
				if ($id_responsavel!="0") 
					echo "<label>Responsável:</label>". $responsavel ." (". formata_cpf(pega_cpf_pelo_id_pessoa($rs->id_responsavel)) .") <br /><br />";
				?>
			</div>
			
			<label for="nome">Nome:</label>
			<?
			if ($trancar)
				echo $rs->nome ." <input name=\"nome\" id=\"nome\" class=\"escondido\" type=\"hidden\" value=\"". $rs->nome ."\" />";
			else {
			?>
			<input name="nome" id="nome" value="<?= $rs->nome;  ?>" />
			<? } ?>
			<br />
	
			<label for="sexo">Sexo:</label>
			<input type="radio" name="sexo" id="sexo_m" value="m" class="tamanho15" <? if ($rs->sexo=='m') echo "checked=\"checked\""; ?> /> <label class="tamanho50 nao_negrito" for="sexo_m">Masculino</label>
			<input type="radio" name="sexo" id="sexo_f" value="f" class="tamanho15" <? if ($rs->sexo=='f') echo "checked=\"checked\""; ?> /> <label class="tamanho50 nao_negrito" for="sexo_f">Feminino</label>
			<br />

			<label for="raca">Raça:</label>
			<input type="radio" name="raca" id="raca_b" value="b" class="tamanho15" <? if ($rs->raca=='b') echo "checked=\"checked\""; ?>  /> <label class="tamanho30 nao_negrito" for="raca_b">Branco</label>
			<input type="radio" name="raca" id="raca_n" value="n" class="tamanho15" <? if ($rs->raca=='n') echo "checked=\"checked\""; ?>  /> <label class="tamanho30 nao_negrito" for="raca_n">Negro</label>
            <input type="radio" name="raca" id="raca_a" value="a" class="tamanho15" <? if ($rs->raca=='a') echo "checked=\"checked\""; ?>  /> <label class="tamanho30 nao_negrito" for="raca_a">Amarelo</label>
			<br />
	
			<div id="cpf_atualiza">
				<label for="cpf">CPF:</label>
				<?
				if ($rs->cpf!="") {
					echo formata_cpf($rs->cpf);
				?>
				<input type="hidden" class="escondido" name="cpf_cadastro" id="cpf_cadastro" value="<?= $rs->cpf; ?>" />
				<? } else { ?>
				<input name="cpf_cadastro" id="cpf_cadastro" maxlength="11" value="<?= $rs->cpf; ?>" onblur="" />
				<? } ?>
				<br />
				<div id="cpf_alerta">
				</div>
			</div>

            <label for="nome_mae">Nome da mãe:</label>
			<input name="nome_mae" id="nome_mae" value="<?= $rs->nome_mae; ?>" maxlength="255" />
			<br />
			
			<label for="nome_pai">Nome do pai:</label>
			<input name="nome_pai" id="nome_pai" maxlength="255" />
			<br />
			
			<label for="nome_madrasta">Nome da madrasta:</label>
			<input name="nome_madrasta" id="nome_madrasta" value="<?= $rs->nome_madrasta; ?>" maxlength="255" />
			<br />
			
			<label for="nome_padrasto">Nome do padrasto:</label>
			<input name="nome_padrasto" id="nome_padrasto"  value="<?= $rs->nome_padrasto; ?>" maxlength="255" />
			<br />

			<label for="rg">RG:</label>
			<input name="rg" id="rg" value="<?= $rs->rg; ?>" maxlength="15" />
			<br />
			
            <label for="orgao_emissor">Órgão emissor:</label>
			<input name="orgao_emissor_rg" id="orgao_emissor_rg" value="<?= $rs->orgao_emissor_rg; ?>" maxlength="10" class="tamanho100" />
			<br />
            
			<label for="data_nasc">Nascimento:</label>
			<input name="data_nasc" id="data_nasc" maxlength="10" onkeyup="formataData(this);" value="<?= $data_nasc; ?>" class="tamanho100" />
			<br />
			
			<label for="endereco">Endere&ccedil;o:</label>
			<input name="endereco" id="endereco" value="<?= $rs->endereco; ?>" />
			<br />
	
			<label for="bairro">Bairro:</label>
			<input name="bairro" id="bairro" value="<?= $rs->bairro; ?>" />
			<br />
	
			<label for="complemento">Complemento:</label>
			<input name="complemento" id="complemento" value="<?= $rs->complemento; ?>" />
			<br />
	
			<label for="cep">CEP:</label>
			<input name="cep" id="cep" maxlength="8" value="<?= $rs->cep; ?>" class="tamanho100" />
			<br />
			
			<?
			if ($trancar) {
				echo "<label>Cidade/UF:</label>". pega_cidade($rs->id_cidade) ."
						<input name=\"id_cidade\" id=\"id_cidade\" class=\"escondido\" type=\"hidden\" value=\"". $rs->id_cidade ."\" />
						<br />";
						
						if (($rs->id_psf=="") || ($rs->id_psf=="0"))
							$psf= "<span class=\"vermelho\">Desconhecido</span>";
						else
							$psf= pega_posto($rs->id_psf);
						
						echo "<label>PSF:</label>". $psf ."
						<input name=\"id_psf\" id=\"id_psf\" class=\"escondido\" type=\"hidden\" value=\"". $rs->id_psf ."\" />
						";
			}
			else {
			?>
			<label for="id_uf">Estado:</label>
			<select name="id_uf" id="id_uf" onchange="retornaCidades();">
			  <option selected="selected">---</option>
			  <?
					$result_uf= mysql_query("select id_uf, uf from ufs order by uf");
					$i= 0;
					while ($rs_uf= mysql_fetch_object($result_uf)) {
				  ?>
			  <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_uf->id_uf; ?>" <? if ($rs_uf->id_uf == $rs->id_uf) echo "selected=\"selected\""; ?>><?= $rs_uf->uf; ?></option>
			  <? $i++; } ?>
			</select>
			<br />
	
			<label for="id_cidade">Cidade:</label>
			<div id="id_cidade_atualiza">
				<select name="id_cidade" id="id_cidade">
				  <option value="">--- selecione ---</option>
				  <?
					$result_cid= mysql_query("select id_cidade, cidade from cidades where id_uf = '$rs->id_uf' order by cidade");
					$i= 0;
					while ($rs_cid= mysql_fetch_object($result_cid)) {
				  ?>
				  <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>" <? if ($rs_cid->id_cidade == $rs->id_cidade) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade; ?></option>
				  <? $i++; } ?>
				</select>
			</div>
            <br />
            
            <label for="id_psf">PSF:</label>
                <select name="id_psf" id="id_psf">
                    <option value="">--- NÃO SEI</option>
                    <?
                    $result_postos= mysql_query("select postos.id_posto, postos.posto from postos
                                                    where postos.id_cidade = '". $rs->id_cidade ."'
                                                    and   postos.situacao = '1'
                                                    and   postos.psf= '1'
                                                    order by postos.posto");
					if (mysql_num_rows($result_postos)==0)
						echo "<span class=\"vermelho\">Nenhum PSF encontrado!</span>";
					else {
						$i= 0;
						while($rs_postos= mysql_fetch_object($result_postos)) {
							if (($i%2)==0)
								$classe= "class=\"cor_sim\"";
							else
								$classe= "";
							
							if ($rs_postos->id_posto==$rs->id_psf)
								$selecionavel= " selected=\"selected\" ";
							else
								$selecionavel= "";
							
							echo "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
							$i++;
						}
					}
                    ?>
                </select>
			<? } ?>
			<br />
	
			<label for="telelefone">Telefone:</label>
			<input name="telefone" id="telefone" value="<?= $rs->telefone; ?>" />
			<br />
            
            <label for="cartao_sus">Cartão SUS:</label>
			<input name="cartao_sus" id="cartao_sus" value="<?= $rs->cartao_sus; ?>" />
			<br />
            
            <? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
            <label for="situacao_pessoa">Falecido(a):</label>
			<input name="situacao_pessoa" id="situacao_pessoa" type="checkbox" value="2" class="tamanho20" <? if ($rs->situacao_pessoa==2) echo "checked=\"checked\""; ?> />
			<br />
            <? } ?>
    		
            <? if (@pode("c", $_SESSION["permissao"])) { ?>
            <div id="cadastro_social" class="nao_mostra">
            	<fieldset>
                	<legend>Levantamento sócio-econômico</legend>
                    
                    <div id="formulario_social">
                    
                    </div>
                    
                </fieldset>
            </div>
            <? } ?>
            
            <label for="observacoes">Observações:</label>
			<textarea name="observacoes" id="observacoes"><?= $rs->observacoes; ?></textarea>
            <br />
            
			<label for="enviar">&nbsp;</label>
			<button type="submit">Cadastrar >></button>
			<br />
		</form>
	</div>
	
    <? if (@pode("c", $_SESSION["permissao"])) { ?>
	<? if ($tem_dados_sociais==1) { ?>
    <script language="javascript" type="text/javascript">abreFechaCadastroSocial('e', '<?= $rs->id_pessoa; ?>');</script>
    <? } } ?>
    
    
	<? if (!$trancar) { ?>
	<script language="javascript" type="text/javascript">daFoco("nome");</script>
	<? } ?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>