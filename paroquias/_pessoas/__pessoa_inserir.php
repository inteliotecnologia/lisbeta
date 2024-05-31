<?
if ($_SESSION["id_usuario_sessao"]!="") {
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
    
    <h2 class="titulos" id="tit_pessoa_inserir">Cadastro de pessoa</h2>
	
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
        
		<form action="<?= AJAX_FORM; ?>formPessoaInserir" id="formPessoaInserir" name="formPessoaInserir" method="post" onsubmit="return ajaxForm('formulario', 'formPessoaInserir');">
			<?
			if ($_GET["id_responsavel"]!="") {
				$id_responsavel= $_GET["id_responsavel"];
				$responsavel= pega_nome($id_responsavel);
			}
			else $id_responsavel= "0";

			if ($_SESSION["id_posto_sessao"]!="") $id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
			if ($_SESSION["id_cidade_sessao"]!="") $id_cidade= $_SESSION["id_cidade_sessao"];
			?>
            
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
			<input type="radio" name="sexo" id="sexo_m" value="m" class="tamanho15" /> <label class="tamanho50 nao_negrito alinhar_esquerda" for="sexo_m">Masculino</label>
			<input type="radio" name="sexo" id="sexo_f" value="f" class="tamanho15" /> <label class="tamanho50 nao_negrito alinhar_esquerda" for="sexo_f">Feminino</label>
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
			
			<label for="data_nasc">Data de nasc.:</label>
			<input name="data_nasc" id="data_nasc" maxlength="10" onkeyup="formataData(this);" class="tamanho100" />
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
			<textarea name="trabalha" id="trabalha"></textarea>
            <br />

			<div id="localizacao">
				<label for="id_cidade">Cidade/UF:</label>
				<?= pega_cidade($_SESSION["id_cidade_pref"]); ?>
				<input class="escondido" type="hidden" name="id_uf" id="id_uf" value="<?= $_SESSION["id_uf_pref"]; ?>" />
				<input class="escondido" type="hidden" name="id_cidade" id="id_cidade" value="<?= $_SESSION["id_cidade_pref"]; ?>" />
				
				<a class="menor" href="javascript:void(0);" onclick="desabilitaCampo('enviar'); ajaxLink('localizacao', 'carregaPaginaInterna&pagina=localizacao');">alterar cidade</a>
				<br />
			</div>
    
			<label for="telelefone">Telefone:</label>
			<input name="telefone" id="telefone" />
			<br />

            <label for="observacoes">Observações:</label>
			<textarea name="observacoes" id="observacoes"></textarea>
            <br />
			            
			<label for="enviar">&nbsp;</label>
			<button type="submit" id="enviar">Cadastrar &raquo;</button>
			<br />
		</form>		
	</div>
    
    <br /><br />
    
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