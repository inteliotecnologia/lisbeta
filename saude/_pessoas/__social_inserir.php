<? if (@pode("c", $_SESSION["permissao"])) { ?>
	<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
    
    <label for="id_profissao">Profissão:</label>
    <select name="id_profissao" id="id_profissao">
        <option value="" selected="selected">--- selecione ---</option>
        <?
        $vetor= pega_profissao('l');
        
        $i=1; $k=0;
        while ($vetor[$i]) {
        ?>
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
        <? $i++; $k++; } ?>
    </select>
    <br />
    
    <label for="renda">Renda:</label>
    <input name="renda" id="renda" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
    <br />
    
    <label for="local_trabalho">Local trabalho:</label>
    <input name="local_trabalho" id="local_trabalho" />
    <br />
    
    <label for="ca">Carteira assinada:</label>
    <input type="radio" name="ca" id="ca_s" value="s" class="tamanho20" /> <label for="ca_s" class="tamanho30 nao_negrito alinhar_esquerda">Sim</label>
    <input type="radio" name="ca" id="ca_n" value="n" class="tamanho20" /> <label for="ca_n" class="tamanho30 nao_negrito alinhar_esquerda">Não</label>
    <input type="radio" name="ca" id="ca_d" value="d" class="tamanho20" /> <label for="ca_d" class="tamanho50 nao_negrito alinhar_esquerda">Desempregado</label>
    <br />
    
    <label for="desempregado_tempo">Tempo:</label>
    <input name="desempregado_tempo" id="desempregado_tempo" class="tamanho100" /> (desempregado)
    <br />
    
    <label for="cidade_nat">Naturalidade:</label>
    <input name="cidade_nat" id="cidade_nat" />
    <br />
    
    <label for="tempo_municipio">Tempo no município:</label>
    <input name="tempo_municipio" id="tempo_municipio" /> (que reside)
    <br />

    <label for="id_ec">Estado civil:</label>
    <select name="id_ec" id="id_ec">
        <option value="" selected="selected">--- selecione ---</option>
        <?
        $vetor= pega_estado_civil('l');
        
        $i=1; $k=0;
        while ($vetor[$i]) {
        ?>
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
        <? $i++; $k++; } ?>
    </select>
    <br />

    <label for="id_gi">Grau de instrução:</label>
    <select name="id_gi" id="id_gi">
        <option value="" selected="selected">--- selecione ---</option>
        <?
        $vetor= pega_grau_instrucao('l');
        
        $i=1; $k=0;
        while ($vetor[$i]) {
        ?>
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
        <? $i++; $k++; } ?>
    </select>
    <br /><br /><br />

<?
	}
	else {
		$erro_a= 3;
		include("__erro_acesso.php");
	}
}
?>