<?
if (pode_algum("m", $_SESSION["permissao"])) {
	$rs= mysql_fetch_object(mysql_query("select permissao from usuarios_postos
											where id_posto = '". $_GET["id_posto"] ."'
											and   id_usuario = '". $_GET["id_usuario"] ."' "));
	$permissao_usuario= $rs->permissao;
?>
<h2>Editar usu�rio em posto</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioNoPostoEditar" method="post" id="formUsuarioNoPostoEditar" name="formUsuarioNoPostoEditar" onsubmit="return ajaxForm('conteudo', 'formUsuarioNoPostoEditar');">

	<label>Posto:</label>
	<input type="hidden" name="id_posto" id="id_posto" value="<?= $_GET["id_posto"]; ?>" class="escondido" />
	<?= pega_posto($_GET["id_posto"]); ?>
	<br />

	<label>Usu�rio:</label>
	<?= pega_nome_pelo_id_usuario($_GET["id_usuario"]); ?>
    <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $_GET["id_usuario"]; ?>" class="escondido" />
	<br />
    
    <div class="parte50">        
        <label for="familias">Fam�lias:</label>
        <input name="familias" id="familias" type="checkbox" value="z" class="tamanho30" <? if (pode("z", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="missionarios">Mission�rios:</label>
        <input name="missionarios" id="missionarios" type="checkbox" value="m" class="tamanho30" <? if (pode("m", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
	</div>
    <div class="parte50">
        <label for="arrecadacao">Arrecada��o:</label>
        <input name="arrecadacao" id="arrecadacao" type="checkbox" value="r" class="tamanho30" <? if (pode("r", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
    <br /><br />
    
	<label>&nbsp;</label>
	<button type="submit">Editar</button>
</form>
<? } ?>