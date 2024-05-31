<?
if (($_GET["sexo"]=="f") || ($rs_paciente->sexo=='f') ) {

if ($rs_paciente->tipo_acompanhamento!="")
	$tipo_acompanhamento= $rs_paciente->tipo_acompanhamento;
else
	$tipo_acompanhamento= $_GET["tipo_acompanhamento"];

?>
<div class="div_abas" id="aba_acomp">
    <ul class="abas">
        <li id="aba_acomp_ng" class="atual"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_acomp', 'aba_acomp_ng'); fechaDiv('gestante'); atribuiValor('tipo_acompanhamento', '<?=$tipo_acompanhamento;?>');">N&atilde;o gestante</a></li>
        <li id="aba_acomp_g"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_acomp', 'aba_acomp_g'); abreDiv('gestante'); atribuiValor('tipo_acompanhamento', 'g');">Gestante</a></li>
    </ul>
</div>
<? } ?>

<div class="parte50">
    <label for="peso">Peso:</label>
    <?
    if ($rs_paciente->peso!=0) $peso= number_format($rs_paciente->peso, 2, ',', '.');
    else $peso= "";
    ?>
    <input name="peso" id="peso" class="tamanho40" maxlength="6" value="<?= $peso; ?>" onblur="geraEstadoNutricional();" onkeydown="formataValor(this,event);" /> kg
    <br />
    
    <label for="altura">Altura:</label>
    <?
    if ($rs_paciente->altura!=0) $altura= number_format($rs_paciente->altura, 2, ',', '.');
    else $altura= "";
    ?>
    <input name="altura" id="altura" class="tamanho40" maxlength="6" value="<?=$altura;?>"  onblur="geraEstadoNutricional();" onkeydown="formataValor(this,event);" /> m
    <br />
    
    <? if (($peso!="") && ($altura!="")) { ?>
        <script language="javascript" type="text/javascript">
            geraEstadoNutricional();
        </script>
    <? } ?>
    
</div>
<div class="parte50">
    <label for="pressao1">PA:</label>
    <input name="pressao1" id="pressao1" class="tamanho40" value="<?=$pressao1;?>" maxlength="3" />
    <span class="flutuar_esquerda">x&nbsp;&nbsp;</span>
    <input name="pressao2" id="pressao2" class="tamanho40" value="<?=$pressao2;?>" maxlength="3" />
    <span class="flutuar_esquerda">mmHg</span>
    <br />
    
    <label for="hcg">HGT:</label>
	<?
    if ($rs_paciente->hcg!=0) $hgt= number_format($rs_paciente->hcg, 2, ',', '.');
    else {
		if ($rs_paciente->hgt!=0) $hgt= number_format($rs_paciente->hgt, 2, ',', '.');
		else $hgt="";
	}
    ?>
    <input name="hcg" id="hcg" class="tamanho40" maxlength="6" value="<?= $hgt; ?>" onkeydown="formataValor(this,event);" /> mg/dl
    <br />
</div>

<div id="gestante" class="nao_mostra">
	<br />
    <div class="parte50">
        <label for="semana_gestacional">Semana gest.:</label>
        <input name="semana_gestacional" id="semana_gestacional"  onblur="geraEstadoNutricional();" value="<?= $rs_paciente->semana_gestacional; ?>" class="tamanho40" maxlength="5" />
        <br />
        
        <?
		if ($rs_paciente->ultima_menstruacao!="00/00/0000") $ultima_menstruacao= $rs_paciente->ultima_menstruacao;
		else $ultima_menstruacao="";
		?>
        <label for="ultima_menstruacao">Última mens.:</label>
        <input name="ultima_menstruacao" id="ultima_menstruacao" value="<?= $ultima_menstruacao; ?>" onfocus="displayCalendar(ultima_menstruacao, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" />
        <br />
        
        <?
		if ($rs_paciente->peso_pregestacional!=0) $peso_pregestacional= number_format($rs_paciente->peso_pregestacional, 2, ',', '.');
		else $peso_pregestacional="";
		?>
        <label for="peso_pregestacional">Peso pré-gest.:</label>
        <input name="peso_pregestacional" id="peso_pregestacional" value="<?= $peso_pregestacional; ?>" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> kg
        <br />
    </div>
    <div class="parte50">
    	<?
		if ($rs_paciente->cintura!=0) $cintura= number_format($rs_paciente->cintura, 2, ',', '.');
		else $cintura="";
		?>
        <label for="cintura">Cintura:</label>
        <input name="cintura" id="cintura" value="<?= $cintura; ?>" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> cm
        <br />
        
        <?
		if ($rs_paciente->quadril!=0) $quadril= number_format($rs_paciente->quadril, 2, ',', '.');
		else $quadril="";
		?>
        <label for="quadril">Quadril:</label>
        <input name="quadril" id="quadril" value="<?= $quadril; ?>" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> cm
        <br />
    </div>
</div>

<br />
<div id="estado_nutricional">
	<input type="hidden" name="estado_nutricional" id="estado_nutricional_campo" value="" class="escondido" />
    Preencha os dados para gerar o estado nutricional do paciente.
</div>