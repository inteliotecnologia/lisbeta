<? if (@pode("c", $_SESSION["permissao"])) { ?>
	<?
    if ($_SESSION["id_usuario_sessao"]!="") {
        $result_soc= mysql_query("select pessoas_se.* from pessoas, pessoas_se
                                    where pessoas_se.id_pessoa = pessoas.id_pessoa
                                    and   pessoas.id_pessoa = '". $_GET["id_pessoa"] ."'
                                    ");
        $rs_soc= mysql_fetch_object($result_soc);
    ?>
    
    <label for="id_profissao">Profissão:</label>
    <select name="id_profissao" id="id_profissao">
        <option value="" selected="selected">--- selecione ---</option>
        <?
        $vetor= pega_profissao('l');
        
        $i=1; $k=0;
        while ($vetor[$i]) {
        ?>
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($rs_soc->id_profissao==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
        <? $i++; $k++; } ?>
    </select>
    <br />
    
    <label for="renda">Renda:</label>
    <input name="renda" id="renda" onkeydown="formataValor(this,event);" class="tamanho100" value="<?= number_format($rs_soc->renda, 2, ',', '.'); ?>" /> (R$)
    <br />
    
    <label for="local_trabalho">Local trabalho:</label>
    <input name="local_trabalho" id="local_trabalho" value="<?= $rs_soc->local_trabalho; ?>" />
    <br />
    
    <label for="ca">Carteira assinada:</label>
    <input type="radio" name="ca" id="ca_s" value="s" class="tamanho20" <? if ($rs_soc->ca=="s") echo "checked=\"checked\""; ?> /> <label for="ca_s" class="tamanho30 alinhar_esquerda">Sim</label>
    <input type="radio" name="ca" id="ca_n" value="n" class="tamanho20" <? if ($rs_soc->ca=="n") echo "checked=\"checked\""; ?> /> <label for="ca_n" class="tamanho30 alinhar_esquerda">Não</label>
    <input type="radio" name="ca" id="ca_d" value="d" class="tamanho20" <? if ($rs_soc->ca=="d") echo "checked=\"checked\""; ?> /> <label for="ca_d" class="tamanho50 alinhar_esquerda">Desempregado</label>
    <br />
    
    <label for="desempregado_tempo">Tempo:</label>
    <input name="desempregado_tempo" id="desempregado_tempo" class="tamanho100" value="<?= $rs_soc->desempregado_tempo; ?>" /> (desempregado)
    <br />
    
    <label for="cidade_nat">Naturalidade:</label>
    <input name="cidade_nat" id="cidade_nat" value="<?= $rs_soc->cidade_nat; ?>" />
    <br />
    
    <label for="tempo_municipio">Tempo no município:</label>
    <input name="tempo_municipio" id="tempo_municipio" value="<?= $rs_soc->tempo_municipio; ?>" /> (que reside)
    <br />

    <label for="id_ec">Estado civil:</label>
    <select name="id_ec" id="id_ec">
        <option value="" selected="selected">--- selecione ---</option>
        <?
        $vetor= pega_estado_civil('l');
        
        $i=1; $k=0;
        while ($vetor[$i]) {
        ?>
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($rs_soc->id_ec==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
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
        <option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($rs_soc->id_gi==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
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