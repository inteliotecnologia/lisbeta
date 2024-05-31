<?
if ($_SESSION["tipo_usuario_sessao"]=="a") {
	$permissao= "";
?>
<h2>Inserir usuário em cidade</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioNaCidadeInserir" method="post" id="formUsuarioNaCidadeInserir" name="formUsuarioNaCidadeInserir" onsubmit="return ajaxForm('conteudo', 'formUsuarioNaCidadeInserir');">

	<label>Cidade:</label>
	<input type="hidden" name="id_cidade" id="id_cidade" value="<?= $id_cidade; ?>" class="escondido" />
	<?= pega_cidade($id_cidade); ?>
	<br />

	<label for="id_usuario">Usuário:</label>
	<select id="id_usuario" name="id_usuario">
		<option selected="selected" value="">--- selecione ---</option>
		<?
		$i=0;
		$result_usu= mysql_query("select pessoas.nome, usuarios.id_usuario, usuarios.usuario from pessoas, usuarios
									where usuarios.id_pessoa = pessoas.id_pessoa
									and   usuarios.tipo_usuario = 'c'
									order by id_usuario desc
									");
		while ($rs_usu= mysql_fetch_object($result_usu)) {
		?>
		<option value="<?= $rs_usu->id_usuario; ?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>><?= $rs_usu->nome ." (". $rs_usu->usuario .")"; ?></option>
		<? $i++; } ?>
	</select>
	<br />

    <div class="parte50">
    
        <label for="farmacia">Farmácia?</label>
        <input name="farmacia" id="farmacia" type="checkbox" value="f" class="tamanho30" <? if (pode("f", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="almoxarifado">Almoxarifado?</label>
        <input name="almoxarifado" id="almoxarifado" type="checkbox" value="x" class="tamanho30" <? if (pode("x", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="secretario">Secretário?</label>
        <input name="secretario" id="secretario" type="checkbox" value="s" class="tamanho30" <? if (pode("s", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    <div class="parte50">
        <label for="producao">Produção?</label>
        <input name="producao" id="producao" type="checkbox" value="p" class="tamanho30" <? if (pode("p", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="tfd">TFD?</label>
        <input name="tfd" id="tfd" type="checkbox" value="t" class="tamanho30" <? if (pode("t", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="social">Social?</label>
        <input name="social" id="social" type="checkbox" value="l" class="tamanho30" <? if (pode("l", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
	</div>
    
    <br /><br />
    
    <div class="parte50">
        <label for="remedios">Remédios:</label>
        <input name="remedios" id="remedios" type="checkbox" value="!" class="tamanho30" <? if (pode("!", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    <div class="parte50">
        <label for="exames">Exames:</label>
        <input name="exames" id="exames" type="checkbox" value="@" class="tamanho30" <? if (pode("@", $permissao)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    
	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('id_usuario');</script>
<? } ?>