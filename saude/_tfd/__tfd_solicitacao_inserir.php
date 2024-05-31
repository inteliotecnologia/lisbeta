<? if (($_SESSION["id_cidade_sessao"]!="") && (@pode("t", $_SESSION["permissao"])) ) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Nova solicitação para TFD</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<fieldset>
	<legend>Formulário de inserção</legend>
    
	<form action="<?= AJAX_FORM; ?>formSolicitacaoTfdInserir" method="post" id="formSolicitacaoTfdInserir" name="formSolicitacaoTfdInserir" onsubmit="return ajaxForm('conteudo', 'formSolicitacaoTfdInserir');">
    
    <label for="id_interno">Código:</label>
    <div id="id_atualiza">
	    <input name="id_interno" id="id_interno" value="" />
    	<span class="vermelho">Selecione a cidade</span>
    </div>
    <br />
    
    <label>Protocolo:</label>
    <input type="radio" name="protocolo" id="protocolo_sim" class="tamanho30" value="1" checked="checked" /> <label class="label2" for="protocolo_sim">Regional</label>
    <input type="radio" name="protocolo" id="protocolo_nao" class="tamanho30" value="0" /> <label class="label2" for="protocolo_nao">Prefeitura</label>
    <br />
    
    <label for="cpf_usuario">CPF:</label>
	<input name="cpf" id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('t1');" value="<?= $cpf_busca; ?>" /> <button type="button" onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');">buscar</button>
	<br />
	
	<label>&nbsp;</label>
		<div id="cpf_usuario_atualiza">
			<input type="hidden" name="id_pessoa_mesmo" id="id_pessoa_mesmo" value="" class="escondido" />
		</div>
	<br />
    
    <label for="id_cidade">Cidade/UF:</label>
    <div id="id_cidade_atualiza">
        <select name="id_cidade2" id="id_cidade2" onchange="retornaIdSolicitacaoTfd();">
          <option value="">--- selecione ---</option>
          <?
            $result_cid= mysql_query("select id_cidade, cidade, uf from cidades, ufs
										where cidades.tfd= '1'
										and   cidades.id_uf = ufs.id_uf
										order by cidade");
            $i= 0;
            while ($rs_cid= mysql_fetch_object($result_cid)) {
          ?>
          <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>"><?= $rs_cid->cidade ."/". $rs_cid->uf; ?></option>
          <? $i++; } ?>
        </select>
    </div>
    <br />
    
    <label for="tipo_usuario">Tipo:</label>
	<select name="tipo_ida" id="tipo_ida" onchange="retornaFinalidades();">
		<option selected="selected" value="">--- selecione ---</option>
		<option value="c" class="cor_sim">Consulta</option>
		<option value="e">Exame</option>
		<option value="i" class="cor_sim">Internação</option>
        <option value="t">Tratamento</option>
        <option value="o" class="cor_sim">Outros</option>
	</select>
	<br />
    
    <label for="id_finalidade">Finalidade:</label>
	<div id="id_finalidade_atualiza" class="vermelho">
    	Selecione o tipo!
        <input type="hidden" name="id_finalidade" id="id_finalidade" class="escondido" value="" />
    </div>
	<br />
    
    <label for="data_solicitacao">Data:</label>
    <input name="data_solicitacao" id="data_solicitacao" onkeyup="formataData(this);" maxlength="10" value="<?= date("d/m/Y"); ?>" />
    <br />
    
    <label for="observacoes">Observações:</label>
    <textarea name="observacoes" id="observacoes"></textarea>
    <br />
    
    <label>&nbsp;</label>
    <button id="botaoInserir" type="submit">Inserir</button>
    <br />
    
	</form>
</fieldset>

<script language="javascript" type="text/javascript">daFoco('id_interno');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>