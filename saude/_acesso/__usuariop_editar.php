<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Editar usuário em posto</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioNoPostoEditar" method="post" id="formUsuarioNoPostoEditar" name="formUsuarioNoPostoEditar" onsubmit="return ajaxForm('conteudo', 'formUsuarioNoPostoEditar');">

	<label>Posto:</label>
	<input type="hidden" name="id_posto" id="id_posto" value="<?= $_GET["id_posto"]; ?>" class="escondido" />
	<?= pega_posto($_GET["id_posto"]); ?>
	<br />

	<label>Usuário:</label>
	<?= pega_nome_pelo_id_usuario($_GET["id_usuario"]); ?>
    <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $_GET["id_usuario"]; ?>" class="escondido" />
	<br />
    
    <?
	$rs= mysql_fetch_object(mysql_query("select permissao, id_cbo from usuarios_postos
											where id_posto = '". $_GET["id_posto"] ."'
											and   id_usuario = '". $_GET["id_usuario"] ."' "));
	$permissao_usuario= $rs->permissao;
	$id_ofamilia= pega_cbo_familia($rs->id_cbo);
	?>
	
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
		<option value="<?= $rs_ofam->id_ofamilia; ?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>  <? if ($id_ofamilia==$rs_ofam->id_ofamilia) echo "selected=\"selected\""; ?>><?= $rs_ofam->id_ofamilia .". ". $rs_ofam->ofamilia; ?></option>
		<? $i++; } ?>
	</select>
	<br />
    
    <label>CBO:</label>
    <div id="id_cbo_atualiza">
        <select id="id_cbo" name="id_cbo">
            <option selected="selected" value="">--- selecione ---</option>
            <?
            $i=0;
            $result_cbo= mysql_query("select * from ocupacoes
                                        where id_ofamilia= '$id_ofamilia'
                                        order by ocupacao asc
                                        ");
            while ($rs_cbo= mysql_fetch_object($result_cbo)) {
            ?>
            <option value="<?= $rs_cbo->id_cbo; ?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($rs_cbo->id_cbo==$rs->id_cbo) echo "selected=\"selected\""; ?>><?= $rs_cbo->id_ofamilia ."-". $rs_cbo->id_ocupacao .". ". $rs_cbo->ocupacao; ?></option>
            <? $i++; } ?>
        </select>
    </div>
    <br />
    
    <div class="parte50">
        <label for="atendimento">Atendimento:</label>
        <input name="atendimento" id="atendimento" type="checkbox" value="r" class="tamanho30" <? if (pode("r", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="enfermeiro">Enfermeiro:</label>
        <input name="enfermeiro" id="enfermeiro" type="checkbox" value="e" class="tamanho30" <? if (pode("e", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="auxiliar_enfermagem">Aux. enf.:</label>
        <input name="auxiliar_enfermagem" id="auxiliar_enfermagem" type="checkbox" value="m" class="tamanho30" <? if (pode("m", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="consultar">Médico:</label>
        <input name="consultar" id="consultar" type="checkbox" value="c" class="tamanho30" <? if (pode("c", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    	
        <label for="auxiliar_medico">Aux. médico:</label>
        <input name="auxiliar_medico" id="auxiliar_medico" type="checkbox" value="i" class="tamanho30" <? if (pode("i", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="procedimentos">Procedimentos:</label>
        <input name="procedimentos" id="procedimentos" type="checkbox" value="d" class="tamanho30" <? if (pode("d", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="familias">Famílias:</label>
        <input name="familias" id="familias" type="checkbox" value="z" class="tamanho30" <? if (pode("z", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
	</div>
    <div class="parte50">
        <label for="odontologia">Odontologia:</label>
        <input name="odontologia" id="odontologia" type="checkbox" value="o" class="tamanho30" <? if (pode("o", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="auxiliar_odontologia">Aux. odont.:</label>
        <input name="auxiliar_odontologia" id="auxiliar_odontologia" type="checkbox" value="n" class="tamanho30" <? if (pode("n", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="farmacia">Farmácia:</label>
        <input name="farmacia" id="farmacia" type="checkbox" value="f" class="tamanho30" <? if (pode("f", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="almoxarifado">Almoxarifado:</label>
        <input name="almoxarifado" id="almoxarifado" type="checkbox" value="x" class="tamanho30" <? if (pode("x", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="producao">Produção:</label>
        <input name="producao" id="producao" type="checkbox" value="p" class="tamanho30" <? if (pode("p", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="vacina">Vacina:</label>
        <input name="vacina" id="vacina" type="checkbox" value="v" class="tamanho30" <? if (pode("v", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
    <br /><br />
    
    <div class="parte50">
        <label for="remedios">Remédios:</label>
        <input name="remedios" id="remedios" type="checkbox" value="!" class="tamanho30" <? if (pode("!", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    <div class="parte50">
        <label for="exames">Exames:</label>
        <input name="exames" id="exames" type="checkbox" value="@" class="tamanho30" <? if (pode("@", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
    <br /><br />
    
	<label>&nbsp;</label>
	<button type="submit">Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco('id_usuario');</script>
<? } ?>