<? if (@pode("t", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relat�rios de solicita��es p/ TFD</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avan�ada</legend>
		
		<form action="<?= AJAX_FORM; ?>formTfdSolicitacao" method="post" id="formTfdSolicitacao" name="formTfdSolicitacao" onsubmit="return ajaxForm('conteudo', 'formTfdSolicitacao');">
			
            <label for="id_interno">C�digo:</label>
		    <input name="id_interno" id="id_interno" class="tamanho50 espaco_dir" /> <span class="vermelho">(controle interno)</span>
		    <br />
            
			<? /*<label for="id_interno">Protocolo:</label>
            <input type="radio" name="protocolo" id="protocolo_sim" class="tamanho30" value="1" /> <label class="label2" for="protocolo_sim">Sim</label>
            <input type="radio" name="protocolo" id="protocolo_nao" class="tamanho30" value="0" /> <label class="label2" for="protocolo_nao">N�o</label>
            <br /> */ ?>
            
            <label for="situacao_solicitacao">Situa��o:</label>
			<select name="situacao_solicitacao" id="situacao_solicitacao">
				<option value="" selected="selected">--- selecione ---</option>
	            <option value="1" class="cor_sim">Enviado para a regional</option>
                <option value="2">Aceito pela regional</option>
                <option value="3" class="cor_sim">Negado pela regional</option>
                <option value="4">Aceito pela prefeitura</option>
                <option value="5" class="cor_sim">J� viajou</option>
			</select>
			<br />
            
            <label for="nome">Nome:</label>
		    <input name="nome" id="nome" /> <span class="vermelho">(digite o nome parcialmente)</span>
		    <br />
			
            <label for="id_cidade">Cidade/UF:</label>
            <div id="id_cidade_atualiza">
                <select name="id_cidade_tfd" id="id_cidade_tfd">
                  <option value="">TODAS</option>
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
                <option selected="selected" value="">TODAS</option>
                <option value="c" class="cor_sim">Consulta</option>
                <option value="e">Exame</option>
                <option value="i" class="cor_sim">Interna��o</option>
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
            
            <label for="id_interno">Modo:</label>
            <input type="radio" name="tudo" id="tudo_sim" class="tamanho30" value="1" checked="checked" /> <label class="label2" for="tudo_sim">P�gina �nica</label>
            <input type="radio" name="tudo" id="tudo_nao" class="tamanho30" value="0" /> <label class="label2" for="tudo_nao">Pagina��o (30 registros por p�gina)</label>
            <br />
            
			<label for="tipo_trans">Per�odo:</label>
			<span class="flutuar_esquerda">De&nbsp;</span>
			<input name="inicio" id="inicio" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["inicio"]; ?>" title="Data inicial" />
			<span class="flutuar_esquerda">a&nbsp;</span>
			<input name="fim" id="fim" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["fim"]; ?>" title="Data final" />
			<br /><br />
			
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>