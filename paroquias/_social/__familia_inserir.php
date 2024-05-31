<? if (@pode_algum("z", $_SESSION["permissao"]) ) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Inserção de família</h2>
<br />

<a href="javascript:void(0);" onclick="abreCadastroPessoaFamilia();" id="botao_voltar">cadastrar chefe da família</a>


<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formFamiliaInserir" method="post" id="formFamiliaInserir" name="formFamiliaInserir" onsubmit="return ajaxForm('conteudo', 'formFamiliaInserir');">

    <fieldset>
        <legend>Chefe da família</legend>
        
        <label for="cpf_usuario">CPF:</label>
        <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario"/>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button">busca</button>
        <br />
        
        <label>&nbsp;</label>
        <div id="cpf_usuario_atualiza">
			<input id="id_pessoa_mesmo" class="escondido" type="hidden" value="0" name="id_pessoa"/>
		</div>
        <br />
        
        <fieldset>
	        <legend>Demais membros da família</legend>
        	
            <? for($k=0; $k<10; $k++) { ?>
	        <label for="nome_membro_<?=$k;?>" class="tamanho50">Nome:</label>
	        <input class="" id="nome_membro_<?=$k;?>" name="nome_membro[]" value="" />
	        
            <label for="sexo_<?=$k;?>" class="tamanho50">Sexo:</label>
			<select name="sexo_membro[]" id="sexo_<?=$k;?>" class="tamanho100">
            	<option value="m">Masculino</option>
                <option value="f" class="cor_sim">Feminino</option>
            </select>
			
            <label for="data_nasc_<?=$k;?>">Data de nasc.:</label>
			<input name="data_nasc_membro[]" id="data_nasc_<?=$k;?>" maxlength="10" onkeyup="formataData(this);" class="tamanho100" />
			
            <label for="parentesco_<?=$k;?>" class="tamanho70">Parentesco:</label>
            <select name="parentesco_membro[]" id="parentesco_<?=$k;?>">
                <?
                $vetor= pega_parentesco('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            <? } ?>
            
        </fieldset>
        
    </fieldset>
    
    <fieldset>
        <legend>Endereço</legend>
        
        <div class="partei">
        	
            <label>Posto:</label>
            <?= pega_posto($_SESSION["id_posto_sessao"]); ?>
            <input name="id_posto" id="id_posto" class="escondido" type="hidden" value="<?= $_SESSION["id_posto_sessao"]; ?>" />
            <br />
            
            <label>Quadra:</label>
            <div id="id_microarea_atualiza">
            	<span class="vermelho">Selecione o PSF antes.</span>
                <input type="hidden" name="id_microarea" id="id_microarea" class="escondido" value="" />
            </div>
            <br />
            
            <label for="num_familia">Núm. família:</label>
            <input id="num_familia" name="num_familia" value="<?= $rs->num_familia; ?>" />
            <br />
            
            <label for="id_religiao">Religião:</label>
            <select name="id_religiao" id="id_religiao">
                <?
                $vetor= pega_religiao('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if (9==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            
        </div>
        
        <div class="partei">
            <label for="endereco">Endereço:</label>
            <textarea name="endereco" id="endereco"></textarea>
            <br />
        </div>
    </fieldset>
        
    <label>&nbsp;</label>
    <button id="botaoInserir" type="submit">Inserir</button>
    <br /><br />

</form>

<script language="javascript" type="text/javascript">
	retornaMicroareas();
</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>