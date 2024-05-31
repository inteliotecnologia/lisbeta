<? if (@pode_algum("ceim", $_SESSION["permissao"])) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Novo acompanhamento</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formAcompInserir" method="post" id="formAcompInserir" name="formAcompInserir" onsubmit="return ajaxForm('conteudo', 'formAcompInserir');">
	
    <fieldset>
        <legend>Novo acompanhamento</legend>
        
        <div class="parte40">
            
            <label>Local:</label>
            <?
            if ($_SESSION["id_posto_sessao"]!="") {
                $local= pega_posto($_SESSION["id_posto_sessao"]);
                $ident_local= 'p';
            }
            if ($_SESSION["id_cidade_sessao"]!="") {
                $local= pega_cidade($_SESSION["id_cidade_sessao"]);
                $ident_local= 'c';
            }
            
            echo $local;
            ?>
            <br />
            
            <label>CPF:</label>
            <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('acomp');" name="cpf_usuario" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo ao lado.');"/>
            <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('mostratudo', 'acomp'); atribuiValor('tipo_volta', '4');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">busca</button>
            <br/>
            
            <label>&nbsp;</label>
            <div id="cpf_usuario_atualiza">
                <input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
            </div>
            <br />        
            
            <label for="data">Data:</label>
            <input name="data" id="data" value="<?=date("d/m/Y");?>"  onfocus="displayCalendar(data, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" onmouseover="Tip('Digite a data.');" />
            <br /><br />
	     </div>
         <div class="parte60">
         	<fieldset id="fieldset_acomp">
            	<legend>Acompanhamento</legend>
                
                <div id="acompanhamento_ac">
                	Faça a busca da pessoa para completar os dados de acompanhamento.
                </div>
                
            </fieldset>
         </div>
         
         <br /><br /><br />
         
        <center>
            <button id="botaoInserir" type="submit">Inserir</button>
        </center>
        <br />
        
    </fieldset>
    
</form>

<script language="javascript" type="text/javascript">daFoco('cpf_usuario');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>