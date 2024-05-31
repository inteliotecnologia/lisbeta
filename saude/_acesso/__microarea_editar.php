<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Edição de microárea</h2>

<form action="<?= AJAX_FORM; ?>formMicroareaEditar" method="post" id="formMicroareaEditar" name="formMicroareaEditar" onsubmit="return ajaxForm('conteudo', 'formMicroareaEditar');">
	
	<?
	$result= mysql_query("select * from microareas where id_microarea = '$id_microarea' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_microarea; ?>
	<input name="id_microarea" id="id_microarea" type="hidden" class="escondido" value="<?= $rs->id_microarea; ?>" />
	<br />
	
    <label for="id_posto">Posto:</label>
    <select name="id_posto" id="id_posto">
        <?
        $result_postos= mysql_query("select * from postos
	                                    where id_cidade = '". pega_id_cidade_do_posto($rs->id_posto) ."'
	                                    and   postos.situacao = '1'
	                                    order by posto");
        while($rs_postos= mysql_fetch_object($result_postos)) {
            if (($i%2)==0)
                $classe= "class=\"cor_sim\"";
            else
                $classe= "";
        ?>
        <option <?= $classe; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($rs->id_posto==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
        <? $i++; } ?>
    </select>
	<br />
    
	<label for="microarea">Microárea:</label>
	<input name="microarea" id="microarea" value="<?= $rs->microarea; ?>" />
	<br />

	<label for="cpf_usuario">Assist. CPF:</label>
	<input name="cpf" id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpf();" value="<?= $cpf_busca; ?>" />
    <a href="javascript:void(0);" onclick="abreFechaDiv('pessoa_buscar');">buscar</a>
	<br />
	
	<label>&nbsp;</label>
		<div id="cpf_usuario_atualiza">
			<?= pega_nome($rs->id_pessoa); ?>
			<input type="hidden" name="id_pessoa_form" id="id_pessoa_form" value="<?= $rs->id_pessoa; ?>" class="escondido" />
		</div>
	<br />

	<label>&nbsp;</label>
	<button>Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco("microarea");</script>
<? } ?>