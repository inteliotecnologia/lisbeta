<?
if (pode_algum("m", $_SESSION["permissao"])) {
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
    
    <div class="parte50">        
        <label for="familias">Famílias:</label>
        <input name="familias" id="familias" type="checkbox" value="z" class="tamanho30" <? if (pode("z", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="missionarios">Missionários:</label>
        <input name="missionarios" id="missionarios" type="checkbox" value="m" class="tamanho30" <? if (pode("m", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
	</div>
    <div class="parte50">
        <label for="arrecadacao">Arrecadação:</label>
        <input name="arrecadacao" id="arrecadacao" type="checkbox" value="r" class="tamanho30" <? if (pode("r", $permissao_aaa)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
    <br /><br />
    	
	<label>&nbsp;</label>
	<button type="submit">Inserir</button>
    <br />
    
</form>
<script language="javascript" type="text/javascript">daFoco('id_usuario');</script>
<? } ?>