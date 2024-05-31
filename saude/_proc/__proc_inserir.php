<? if (@pode("d", $_SESSION["permissao"])) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Inserção de procedimento</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formProcInserir" method="post" id="formProcInserir" name="formProcInserir" onsubmit="return ajaxForm('conteudo', 'formProcInserir');">
	
    <fieldset>
        <legend>Novo procedimento</legend>
        
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
        
        <label for="id_procedimento">Procedimento:</label>
        <select name="id_procedimento" id="id_procedimento" class="tamanho300" onchange="alteraCbosProcedimento();">
        <?
        $vetor= pega_procedimentos('l');
        $i=1;
        
        while ($vetor[$i]) {
            ?>
            <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($rs->id_tipo_atendimento==$i) echo "selected=\"selected\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
            <?
            $i++;
        }
        ?>
        </select>
	    <br />        
        
        <div id="procedimentos_identificar" class="nao_mostra">
            <label>CPF:</label>
            <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo ao lado.');"/>
            <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">busca</button>
            <br/>
            
            <label>&nbsp;</label>
            <div id="cpf_usuario_atualiza">
                <input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
            </div>
            <br />
        </div>
        
        <div id="procedimentos_cbos" class="nao_mostra">
            <label for="id_ofamilia">CBO (Família):</label>
            <select id="id_ofamilia" name="id_ofamilia" onchange="retornaCBOs();">
                <option selected="selected" value="">--- selecione ---</option>
                <?
                $i=0;
                $result_ofam= mysql_query("select * from ocupacoes_familias
                                            where valido = '1'
                                            order by ofamilia
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
        </div>
        
        <label for="qtde">Quantidade:</label>
        <input name="qtde" id="qtde" class="tamanho60" onmouseover="Tip('Digite a quantidade de procedimentos.');" />
        <br />
        
        <label for="data">Data:</label>
        <input name="data" id="data" onfocus="displayCalendar(data, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" onmouseover="Tip('Digite a data.');" />
        <br />
        
        <label for="evolucao">Evolução:</label>
        <textarea class="grandao" name="evolucao" id="evolucao"></textarea>
        <br /><br />
           
<label>&nbsp;</label>
        <button id="botaoInserir" type="submit">Inserir</button>
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