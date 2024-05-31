<?
if ($_SESSION["tipo_usuario_sessao"]=="a") {
	$permissao_aaa= "";
?>
<h2>Inserir usuário em posto</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioNoPostoInserir" method="post" id="formUsuarioNoPostoInserir" name="formUsuarioNoPostoInserir" onsubmit="return ajaxForm('conteudo', 'formUsuarioNoPostoInserir');">

	<label>Posto:</label>
	<input type="hidden" name="id_posto" id="id_posto" value="<?= $id_posto; ?>" class="escondido" />
	<?= pega_posto($id_posto); ?>
	<br />

	<label for="id_usuario">Usuário:</label>
	<select id="id_usuario" name="id_usuario">
		<option selected="selected" value="">--- selecione ---</option>
		<?
		$i=0;
		$result_usu= mysql_query("select pessoas.nome, usuarios.id_usuario, usuarios.usuario from pessoas, usuarios
									where usuarios.id_pessoa = pessoas.id_pessoa
									and   usuarios.tipo_usuario = 'p'
									order by usuarios.id_usuario desc
									");
		while ($rs_usu= mysql_fetch_object($result_usu)) {
		?>
		<option value="<?= $rs_usu->id_usuario; ?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>><?= $rs_usu->nome ." (". $rs_usu->usuario .")"; ?></option>
		<? $i++; } ?>
	</select>
	<br />
	
    <label for="id_ofamilia">CBO (Família):</label>
	<select id="id_ofamilia" name="id_ofamilia" onchange="retornaCBOs();">
		<option selected="selected" value="">--- selecione ---</option>
		<?
		$i=0;
		$result_ofam= mysql_query("select * from ocupacoes_familias
									where valido = '1'
									order by id_ofamilia
									");
		while ($rs_ofam= mysql_fetch_object($result_ofam)) {
		?>
		<option value="<?= $rs_ofam->id_ofamilia; ?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>><?= $rs_ofam->id_ofamilia .". ". $rs_ofam->ofamilia; ?></option>
		<? $i++; } ?>
	</select>
	<br />
    
    <label>CBO:</label>
    <div id="id_cbo_atualiza">
        Selecione a família do CBO.
    </div>
    <br />
    
    <div class="parte50">
        <label for="atendimento">Atendimento:</label>
        <input name="atendimento" id="atendimento" type="checkbox" value="r" class="tamanho30" <? if (pode("r", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="enfermeiro">Enfermeiro:</label>
        <input name="enfermeiro" id="enfermeiro" type="checkbox" value="e" class="tamanho30" <? if (pode("e", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="auxiliar_enfermagem">Aux. enf.:</label>
        <input name="auxiliar_enfermagem" id="auxiliar_enfermagem" type="checkbox" value="m" class="tamanho30" <? if (pode("m", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="consultar">Médico:</label>
        <input name="consultar" id="consultar" type="checkbox" value="c" class="tamanho30" <? if (pode("c", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
    	
        <label for="auxiliar_medico">Aux. médico:</label>
        <input name="auxiliar_medico" id="auxiliar_medico" type="checkbox" value="i" class="tamanho30" <? if (pode("i", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="procedimentos">Procedimentos:</label>
        <input name="procedimentos" id="procedimentos" type="checkbox" value="d" class="tamanho30" <? if (pode("d", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="familias">Famílias:</label>
        <input name="familias" id="familias" type="checkbox" value="z" class="tamanho30" <? if (pode("z", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
	</div>
    <div class="parte50">
        <label for="odontologia">Odontologia:</label>
        <input name="odontologia" id="odontologia" type="checkbox" value="o" class="tamanho30" <? if (pode("o", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="auxiliar_odontologia">Aux. odont.:</label>
        <input name="auxiliar_odontologia" id="auxiliar_odontologia" type="checkbox" value="n" class="tamanho30" <? if (pode("n", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="farmacia">Farmácia:</label>
        <input name="farmacia" id="farmacia" type="checkbox" value="f" class="tamanho30" <? if (pode("f", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="almoxarifado">Almoxarifado:</label>
        <input name="almoxarifado" id="almoxarifado" type="checkbox" value="x" class="tamanho30" <? if (pode("x", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="producao">Produção:</label>
        <input name="producao" id="producao" type="checkbox" value="p" class="tamanho30" <? if (pode("p", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="vacina">Vacina:</label>
        <input name="vacina" id="vacina" type="checkbox" value="v" class="tamanho30" <? if (pode("v", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
    <br /><br />
    
    <div class="parte50">
        <label for="remedios">Remédios:</label>
        <input name="remedios" id="remedios" type="checkbox" value="!" class="tamanho30" <? if (pode("!", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    <div class="parte50">
        <label for="exames">Exames:</label>
        <input name="exames" id="exames" type="checkbox" value="@" class="tamanho30" <? if (pode("@", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    	
	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('id_usuario');</script>
<? } ?>