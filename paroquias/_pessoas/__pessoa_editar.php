<?
if ($_SESSION["id_usuario_sessao"]!="") {
	$_SESSION["acao"]= md5("asd". rand(1, 30) . time());
	
	//cidade atual (rodando o sistema)
	if ($_SESSION["id_cidade_sessao"]!="") $id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else $id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	
	$result= mysql_query("select pessoas.*, DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc, ufs.id_uf from pessoas, cidades, ufs
								where pessoas.id_pessoa = '". $_GET["id_pessoa"] ."'
								and   pessoas.id_cidade = cidades.id_cidade
								and   cidades.id_uf = ufs.id_uf
								");
	$rs= mysql_fetch_object($result);
	
	if ($rs->data_nasc!="00/00/0000") $data_nasc= $rs->data_nasc;
	
	if ($rs->origem=="c") $id_cidade_cadastro= $rs->id_origem;
	else $id_cidade_cadastro= pega_id_cidade_do_posto($rs->id_origem);
	
?>
	<h2 class="titulos" id="tit_pessoa_editar">Cadastro de pessoa</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('tela_cadastro');" class="fechar">x</a>
    
	<div id="formulario">
    	<div id="profissao_cadastro" class="nao_mostra">
            <a href="javascript:void(0);" onclick="abreFechaDiv('profissao_cadastro');" class="fechar">x</a>
            
            <h2 class="titulos" id="tit_remedio_cadastro">Cadastro de profissão</h2>
            
            <div id="profissao_cadastro3">
            </div>
        
            <label for="profissao">Profissão:</label>
            <input name="profissao" id="profissao" onkeyup="if (event.keyCode==13) profissaoCadastroOk();" />
            <br />
            
            <label>&nbsp;</label>
            <button type="button" onclick="profissaoCadastroOk();">Adicionar &raquo;</button>
        </div>
		
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

			<input name="id_responsavel" id="id_responsavel" type="hidden" value="<?= $id_responsavel; ?>" class="escondido" />
			<input name="id_pessoa" id="id_pessoa" type="hidden" value="<?= $rs->id_pessoa; ?>" class="escondido" />
			<input name="acao" id="acao" type="hidden" value="<?= $acao; ?>" class="escondido" />
			<input name="retorno" id="retorno" type="hidden" value="<?= $_GET["retorno"]; ?>" class="escondido" />
			
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
			<input type="radio" name="sexo" id="sexo_m" value="m" class="tamanho15" <? if ($rs->sexo=='m') echo "checked=\"checked\""; ?> /> <label class="tamanho50 nao_negrito alinhar_esquerda" for="sexo_m">Masculino</label>
			<input type="radio" name="sexo" id="sexo_f" value="f" class="tamanho15" <? if ($rs->sexo=='f') echo "checked=\"checked\""; ?> /> <label class="tamanho50 nao_negrito alinhar_esquerda" for="sexo_f">Feminino</label>
			<br />
	
			<div id="cpf_atualiza">
				<label for="cpf">CPF:</label>
				<?
				if ($rs->cpf!="") {
					echo formata_cpf($rs->cpf);
				?>
				<input type="hidden" class="escondido" name="cpf_cadastro" id="cpf_cadastro" value="<?= $rs->cpf; ?>" />
				<? } else { ?>
				<input name="cpf_cadastro" id="cpf_cadastro" maxlength="11" value="<?= $rs->cpf; ?>" onblur="usuarioRetornaCpfDisponibilidade();" />
				<br />
                
                <label>&nbsp;</label>
                <div id="cpf_disponibilidade">
					<input type="hidden" class="escondido" name="cpf_disponivel" id="cpf_disponivel" value="0" />
				</div>
                <? } ?>
			</div>
			<br />

			<label for="data_nasc">Nascimento:</label>
			<input name="data_nasc" id="data_nasc" maxlength="10" onkeyup="formataData(this);" value="<?= $data_nasc; ?>" class="tamanho100" />
			<br />
			
            <label for="estado_civil">Estado civil:</label>
            <select name="estado_civil" id="estado_civil">
                <option value="">---</option>
				<?
                $vetor= pega_estado_civil('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($rs->estado_civil==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            
            <label for="id_profissao">Profissão:</label>
             <div id="profissao_atualiza" class="flutuar_esquerda">
			<? require_once("_pessoas/__profissao.php"); ?>
            </div>
            
            <a class="menor" href="javascript:void(0);" onclick="abreFechaDiv('profissao_cadastro'); daFoco('profissao');">cadastrar</a>
            <br />
            
            <label for="trabalha">Trabalha:</label>
			<textarea name="trabalha" id="trabalha"><?=$rs->trabalha;?></textarea>
            <br />
            
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
	
			<label for="telelefone">Telefone:</label>
			<input name="telefone" id="telefone" value="<?= $rs->telefone; ?>" />
			<br />
            
            <? //if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
            <label for="situacao_pessoa">Falecido(a):</label>
			<input name="situacao_pessoa" id="situacao_pessoa" type="checkbox" value="2" class="tamanho20" <? if ($rs->situacao_pessoa==2) echo "checked=\"checked\""; ?> />
			<br />
            <? //} ?>
            
            <label for="observacoes">Observações:</label>
			<textarea name="observacoes" id="observacoes"><?= $rs->observacoes; ?></textarea>
            <br />
            
			<label for="enviar">&nbsp;</label>
			<button type="submit" id="enviar">Editar &raquo;</button>
			<br />
		</form>
	</div>
    
    <br /><br />
    
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