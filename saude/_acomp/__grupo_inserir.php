<? if (@pode_algum("ceim", $_SESSION["permissao"])) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Inserir pessoa em um grupo de acompanhamento</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formGrupoInserir" method="post" id="formGrupoInserir" name="formGrupoInserir" onsubmit="return ajaxForm('conteudo', 'formGrupoInserir');">
	
    <fieldset>
        <legend>Nova pessoa em grupo de acompanhamento</legend>
        
        <div class="parte50">
            
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
            <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo ao lado.');"/>
            <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb'); atribuiValor('mostratudo', 'acomp');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">busca</button>
            <br/>
            
            <label>&nbsp;</label>
            <div id="cpf_usuario_atualiza">
                <input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
            </div>
            <br />        
            
            <label for="id_grupo">Grupo:</label>
            <select name="id_grupo" id="id_grupo">
            <?
			$result_gr= mysql_query("select * from acomp_grupos");
			$i=0;
            while ($rs_gr= mysql_fetch_object($result_gr)) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_gr->id_grupo; ?>"><?= $rs_gr->grupo; ?></option>
                <?
                $i++;
            }
            ?>
            </select>
            <br />
            
            <label for="acompanhar_ate">Acompanhar até:</label>
            <input name="acompanhar_ate" id="acompanhar_ate" onfocus="displayCalendar(acompanhar_ate, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" onmouseover="Tip('Digite a data.');" />
            <br /><br />
            
            <label>&nbsp;</label>
            <button id="enviar" type="submit">Inserir</button>
            <br />
	     </div>
         <div class="parte50">
         	
         </div>
         
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