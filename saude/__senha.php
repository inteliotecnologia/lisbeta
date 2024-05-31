<?
if ($_SESSION["id_usuario_sessao"]!="") {
?>
<h2 class="titulos" id="tit_senha">Alterar e-mail e/ou senha</h2>

<a href="javascript:void(0);" onclick="abreFechaDiv('tela_senha');" class="fechar">x</a>

<div id="formulario">
    <form action="<?= AJAX_FORM; ?>formSenha" method="post" id="formSenha" name="formSenha" onsubmit="return ajaxForm('formulario', 'formSenha');">
        
        <label for="email_s">E-mail:</label>
        <input name="email_s" id="email_s" value="<?= pega_email_pelo_id_usuario($_SESSION["id_usuario_sessao"]); ?>" />
        <br />
        
        <label for="senha_atual">Senha atual:</label>
        <input name="senha_atual" id="senha_atual" type="password" />
        <br />
        
        <label for="senha_nova">Nova senha:</label>
        <input name="senha_nova" id="senha_nova" type="password" />
        <br />
        
        <label for="senha_nova2">Repita:</label>
        <input name="senha_nova2" id="senha_nova2" type="password" />
        <br />
    
        <label>&nbsp;</label>
        <button>Atualizar</button>
    </form>
</div>
        
<script language="javascript" type="text/javascript">daFoco('email_s');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>