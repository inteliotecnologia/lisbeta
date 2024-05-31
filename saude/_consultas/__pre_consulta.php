<?
if (@pode("r", $_SESSION["permissao"])) {
	$result_pre= mysql_query("select pessoas.nome, agenda_consultas.* from agenda_consultas, pessoas
								 where agenda_consultas.id_agenda = '". $_GET["id_agenda"] ."'
								 and   agenda_consultas.atendido = '0'
								 and   pessoas.id_pessoa = agenda_consultas.id_pessoa ");
	$rs= mysql_fetch_object($result_pre);
?>
<h2>Dados do paciente</h2>

<a href="javascript:void(0);" onclick="fechaDiv('tela_aux_rapida');" class="fechar">x</a>

<form action="<?= AJAX_FORM ?>formPreConsulta" method="post" name="formPreConsulta" id="formPreConsulta" onsubmit="return ajaxForm('conteudo', 'formPreConsulta');">
    
    <input type="hidden" id="id_agenda" name="id_agenda" class="escondido" value="<?= $rs->id_agenda; ?>" />
    <input type="hidden" id="data_agendada" name="data_agendada" class="escondido" value="<?= $rs->data_agendada; ?>" />
    
    <label>Nome:</label>
    <?= $rs->nome; ?>
    <br />
	
    <label for="id_tipo_atendimento">Tipo de atendimento:</label>
        <select name="id_tipo_atendimento" id="id_tipo_atendimento">
        <?
        $vetor= pega_tipo_atendimento('l');
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
    
    <label for="temperatura">Temperatura:</label>
    <input name="temperatura" id="temperatura" class="tamanho40" onkeydown="formataValor(this,event);" value="<? if ($rs->temperatura!=0) echo number_format($rs->temperatura, 2, ',', '.'); ?>" maxlength="5" /> ºC
    <br />
    
    <label for="pressao1">PA:</label>
    <input name="pressao1" id="pressao1" class="tamanho40" value="<? if ($rs->pressao1!=0) echo $rs->pressao1; ?>" maxlength="3" />
    <span class="flutuar_esquerda">x&nbsp;&nbsp;</span>
    <input name="pressao2" id="pressao2" class="tamanho40" value="<? if ($rs->pressao2!=0) echo $rs->pressao2; ?>" maxlength="3" />
    <span class="flutuar_esquerda">mmHg</span>
    <br />
    
    <label for="hcg">HGT:</label>
    <input name="hcg" id="hcg" class="tamanho40" maxlength="6" value="<? if ($rs->hcg!=0) echo number_format($rs->hcg, 2, ',', '.'); ?>" onkeydown="formataValor(this,event);" /> mg/dl
    <br />
    
    <label for="peso">Peso:</label>
    <input name="peso" id="peso" class="tamanho40" maxlength="6" value="<? if ($rs->peso!=0) echo number_format($rs->peso, 2, ',', '.'); ?>" onkeydown="formataValor(this,event);" /> kg
    <br />
    
    <?
	$result_ac_at= mysql_query("select * from acompanhamento where id_pessoa = '". $rs->id_pessoa ."'
									and altura <> ''
									order by id_acompanhamento desc limit 1 ") or die(mysql_error());
	$rs_ac_at= mysql_fetch_object($result_ac_at);
	
	if ( ($rs->altura!="") && ($rs->altura!="0") && ($rs->altura!="0.00") )
		$altura= $rs->altura;
	else {
		if (mysql_num_rows($result_ac_at)>0)
			$altura= $rs_ac_at->altura;
		else
			$altura= 0;
	}
	?>
    <label for="altura">Altura:</label>
    <input name="altura" id="altura" class="tamanho40" maxlength="6" value="<? if ($altura!=0) echo number_format($altura, 2, ',', '.'); ?>" onkeydown="formataValor(this,event);" /> m
    <br />
    
    <label>Tipo:</label>
    <input type="radio" name="tipo_consulta" id="tipo_consulta_c" class="tamanho20" <? if ($rs->tipo_consulta=="c") echo "checked=\"checked\""; ?> value="c" /> <label for="tipo_consulta_c" class="tamanho50_e">Consulta</label>
    <input type="radio" name="tipo_consulta" id="tipo_consulta_r" class="tamanho20" <? if ($rs->tipo_consulta=="r") echo "checked=\"checked\""; ?> value="r" /> <label for="tipo_consulta_r" class="tamanho50_e">Retorno</label>
    <br />
    
    <label>Residência:</label>
    <input type="radio" name="area_abran" id="area_abran_1" class="tamanho20" <? if ($rs->area_abran=="1") echo "checked=\"checked\""; ?> value="1" /> <label for="area_abran_1" class="tamanho120 nao_negrito alinhar_esquerda">Área de abrangência</label>
    <input type="radio" name="area_abran" id="area_abran_0" class="tamanho20" <? if ($rs->area_abran=="0") echo "checked=\"checked\""; ?> value="0" /> <label for="area_abran_0" class="tamanho80 nao_negrito alinhar_esquerda">Fora da área</label>
    <br />
    
    <label for="enviar">&nbsp;</label>
    <button type="submit">Enviar</button>
</form>
<br />
<script language="javascript" type="text/javascript">
	daFoco("temperatura");
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>