<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Editar usuário em cidade</h2>

<form action="<?= AJAX_FORM; ?>formUsuarioNaCidadeEditar" method="post" id="formUsuarioNaCidadeEditar" name="formUsuarioNaCidadeEditar" onsubmit="return ajaxForm('conteudo', 'formUsuarioNaCidadeEditar');">

	<label>Cidade:</label>
	<input type="hidden" name="id_cidade" id="id_cidade" value="<?= $_GET["id_cidade"]; ?>" class="escondido" />
	<?= pega_cidade($_GET["id_cidade"]); ?>
	<br />

	<label for="id_usuario">Usuário:</label>
	<?= pega_nome_pelo_id_usuario($_GET["id_usuario"]); ?>
    <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $_GET["id_usuario"]; ?>" class="escondido" />
	<br />
	
    <?
	$rs= mysql_fetch_object(mysql_query("select permissao from usuarios_cidades
											where id_cidade = '". $_GET["id_cidade"] ."'
											and   id_usuario = '". $_GET["id_usuario"] ."'"));
	$permissao_usuario= $rs->permissao;
	?>

	<div class="parte50">
    
        <label for="farmacia">Farmácia?</label>
        <input name="farmacia" id="farmacia" type="checkbox" value="f" class="tamanho30" <? if (pode("f", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="almoxarifado">Almoxarifado?</label>
        <input name="almoxarifado" id="almoxarifado" type="checkbox" value="x" class="tamanho30" <? if (pode("x", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="secretario">Secretário?</label>
        <input name="secretario" id="secretario" type="checkbox" value="s" class="tamanho30" <? if (pode("s", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
    </div>
    <div class="parte50">
        <label for="producao">Produção?</label>
        <input name="producao" id="producao" type="checkbox" value="p" class="tamanho30" <? if (pode("p", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="tfd">TFD?</label>
        <input name="tfd" id="tfd" type="checkbox" value="t" class="tamanho30" <? if (pode("t", $permissao_usuario)) echo "checked=\"checked\""; ?> />
        <br />
        
        <label for="social">Social?</label>
        <input name="social" id="social" type="checkbox" value="l" class="tamanho30" <? if (pode("l", $permissao_usuario)) echo "checked=\"checked\""; ?> />
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
    
	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('id_usuario');</script>
<? } ?>