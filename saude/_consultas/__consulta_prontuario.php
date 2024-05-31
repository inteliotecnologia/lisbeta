<?
if (@pode("r", $_SESSION["permissao"])) {

if ($tipo_hist=="v") {
?>
<a href="javascript:void(0);" onclick="fechaDiv('tela_relatorio');" class="fechar">x</a>

<h2 class="titulos">Histórico de consultas</h2>

<?
}

$result= mysql_query("select pessoas.id_pessoa, pessoas.nome, pessoas.cpf, pessoas.id_responsavel,
					consultas.*, DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta2,
					consultas.id_usuario as id_med
					from  pessoas, consultas
					where consultas.id_pessoa = '". $_GET["id_pessoa"] ."'
					and   consultas.id_pessoa = pessoas.id_pessoa
					order by consultas.data_consulta desc
					") or die(mysql_error());
if (mysql_num_rows($result)==0)
	echo "<p>Esta é a primeira consulta desta pessoa no sistema.</p>";
else {
	while ($rs= mysql_fetch_object($result)) {
?>

<h2 class="titulos">Consulta nº <?= $rs->id_consulta; ?></h2>

<div class="parte50">
    <fieldset>
        <legend>Pré-atendimento</legend>
        
        <label>Paciente:</label>
        <?= $rs->nome; ?>
        <br />
        
        <?
        if ($rs->tipo_consulta_prof!="o") {
			$origem= explode("@", $rs->origem_consulta);
			if ($origem[0]=="f") {
				$result_fila= mysql_query("select *, DATE_FORMAT(data_fila, '%d/%m/%Y %H:%i:%s') as data_fila from filas where id_fila = '". $origem[1] ."' ");
				$rs_fila= mysql_fetch_object($result_fila);
        ?>
            <label>Atendente:</label>
            <?= pega_nome_pelo_id_usuario($rs_fila->id_usuario); ?>
            <br />
        
            <label>Temperatura:</label>
            <?= number_format($rs_fila->temperatura, 1, ',', '.') ." ºC"; ?>
            <br />
        
            <label>PA:</label>
            <?= $rs_fila->pressao1 ."x". $rs_fila->pressao2 ." mmHg"; ?>
            <br />
        
            <label>HCG:</label>
            <?= number_format($rs_fila->hcg, 2, ',', '.') ." mg/dl"; ?>
            <br />
            
            <label>Peso:</label>
            <?= number_format($rs_fila->peso, 2, ',', '.') ." kg"; ?>
            <br />
            
            <label>Altura:</label>
            <?= number_format($rs_fila->altura, 2, ',', '.') ." m"; ?>
            <br />
        
            <label>Atendido:</label>
            <?= $rs_fila->data_fila; ?>
            <br />
        
        <?
        }
        else {
            $result_ag= mysql_query("select *, DATE_FORMAT(data_agendamento, '%d/%m/%Y %H:%i:%s') as data_agendamento,
                                        DATE_FORMAT(data_agendada, '%d/%m/%Y %H:%i:%s') as data_agendada
                                        from agenda_consultas where id_agenda = '". $origem[1] ."' ");
            $rs_ag= mysql_fetch_object($result_ag);
        ?>
            <label>Atendente:</label>
            <?= pega_nome_pelo_id_usuario($rs_ag->id_usuario); ?>
            <br />
            
            <label>Data marcada:</label>
            <?= $rs_ag->data_agendada; ?>
            <br />
        
            <label>Temperatura:</label>
            <?= number_format($rs_ag->temperatura, 1, ',', '.') ." ºC"; ?>
            <br />
        
            <label>PA:</label>
            <?= $rs_ag->pressao1 ."x". $rs_ag->pressao2 ." mmHg"; ?>
            <br />
        
            <label>HGT:</label>
            <?= number_format($rs_ag->hcg, 2, ',', '.') ." mg/dl"; ?>
            <br />
            
            <label>Peso:</label>
            <?= number_format($rs_ag->peso, 2, ',', '.') ." kg"; ?>
            <br />
            
            <label>Altura:</label>
            <?= number_format($rs_ag->altura, 2, ',', '.') ." m"; ?>
            <br />
    
        <? } } //fim não odonto ?>
        
    </fieldset>
    
    <fieldset>
        <legend>Consulta <?= pega_tipo_consulta_prof($rs->tipo_consulta_prof); ?></legend>
        
        <label>Atendido por:</label>
        <?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
        <br />
        
        <? if ($rs->id_usuario_usando!="") { ?>
        <label>Cadastrado por:</label>
        <?= pega_nome_pelo_id_usuario($rs->id_usuario_usando); ?>
        <br />
        <? } ?>
        
		<? if ($rs->tipo_consulta_prof!="o") { ?>
        <label>Atendimento:</label>
        <?= pega_tipo_atendimento($rs->id_tipo_atendimento); ?>
        <br />
        <? } ?>
        
        <label>Posto:</label>
        <?= pega_posto($rs->id_posto); ?>
        <br />
        
        <label>Data da consulta:</label>
        <?= $rs->data_consulta2; ?>
        <br />
    	
        <? if ($rs->tipo_consulta_prof!="o") { ?>
        <label>Encamin.:</label>
        <?= pega_encaminhamento($rs->encaminhamento); ?>
        <br />
    	<? } ?>
    </fieldset>
</div>
<div class="parte50">
	<? if ($rs->tipo_consulta_prof=="m") { ?>
    <fieldset>
        <legend>Anamnese:</legend>
        
        <? /*
        <label>Queixa principal:</label>
        <? if ($rs->qp!="") echo $rs->qp; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Hist&oacute;rico da doen&ccedil;a atual:</label>
        <? if ($rs->hda!="") echo $rs->hda; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Hist&oacute;rico m&eacute;dica pregressa:</label>
        <? if ($rs->hmp!="") echo $rs->hmp; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Hist&oacute;rico familiar:</label>
        <? if ($rs->hf!="") echo $rs->hf; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Hist&oacute;ria pessoal e social:</label>
        <? if ($rs->hps!="") echo $rs->hps; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Revis&atilde;o de sistemas:</label>
        <? if ($rs->rs!="") echo $rs->rs; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
		
		*/ ?>
        
        <label>Anamnese:</label>
        <? if ($rs->anamnese!="") echo $rs->anamnese; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Exame clínico:</label>
        <? if ($rs->exame_clinico!="") echo $rs->exame_clinico; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
    </fieldset>
    <?
    }//fim medico
	if ($rs->tipo_consulta_prof=="e") {
	?>
    <fieldset>
        <legend>SOAP</legend>
        
        <label>Subjetivo:</label>
        <? if ($rs->s!="") echo $rs->s; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Objetivo:</label>
        <? if ($rs->o!="") echo $rs->o; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Avaliação:</label>
        <? if ($rs->a!="") echo $rs->a; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Prescrição:</label>
        <? if ($rs->p!="") echo $rs->p; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Observações:</label>
        <? if ($rs->obs!="") echo $rs->obs; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
    </fieldset>
    <?
    }
	if ($rs->tipo_consulta_prof=="o") {
	?>
    <fieldset>
        <legend>Anamnese</legend>
        
        <label>Anamnese:</label>
        <? if ($rs->anamnese!="") echo $rs->anamnese; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Exame de boca:</label>
        <? if ($rs->exame_boca!="") echo $rs->exame_boca; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
        
        <label>Observações:</label>
        <? if ($rs->obs!="") echo $rs->obs; else echo "<span class=\"vermelho\">não informado</span>"; ?>
        <br />
    </fieldset>
    
    <fieldset>
        <legend>Tratamentos/procedimentos executados:</legend>
     
        <?
        $result_pr= mysql_query("select consultas_odonto_procedimentos.*, odonto_procedimentos.procedimento from consultas_odonto_procedimentos, odonto_procedimentos
                                    where consultas_odonto_procedimentos.id_oprocedimento = odonto_procedimentos.id_oprocedimento
                                    and   consultas_odonto_procedimentos.id_consulta = '$rs->id_consulta' ") or die(mysql_error());
        if (mysql_num_rows($result_pr) > 0) {
        ?>
        <table cellspacing="0">
            <tr>
                <th width="10%" align="left">&nbsp;</th>
                <th width="90%" align="left">Tratamento/procedimento</th>
            </tr>
            <? while ($rs_pr= mysql_fetch_object($result_pr)) { ?>
            <tr>
                <td>&nbsp;</td>
                <td><?= $rs_pr->procedimento; ?></td>
            </tr>
            <? } ?>
        </table>
        <?
        }
        else
            echo "<span class=\"vermelho\">Nenhum procedimento executado!</span>";
        ?>
        <br />
    
    </fieldset>
    
    <? } ?>
    
    <? if ($rs->tipo_consulta_prof=="m") { ?>
    <fieldset>
        <legend>Exames solicitados:</legend>
     
        <?
        $result_exa= mysql_query("select consultas_exames.*, exames.exame from consultas_exames, exames
                                    where consultas_exames.id_exame = exames.id_exame
                                    and   consultas_exames.id_consulta = '$rs->id_consulta' ");
        if (mysql_num_rows($result_exa) > 0) {
        ?>
        <table cellspacing="0">
            <tr>
                <th width="40%" align="left">Exame</th>
                <th width="60%" align="left">Resultado</th>
            </tr>
            <? while ($rs_exa= mysql_fetch_object($result_exa)) { ?>
            <tr>
                <td><?= $rs_exa->exame; ?></td>
                <td>
                    <div id="resultado_exame_<?= $rs_exa->id_consulta_exame; ?>">
                        <?
                        if ($rs_exa->resultado=="") {
                        ?>
                            <span class="vermelho">Resultado ainda não cadastrado!</span>
                        <?
                        }
                        else
                            echo $rs_exa->resultado;
                        ?>
                    </div>
                </td>
            </tr>
            <? } ?>
        </table>
        <?
        }
        else
            echo "<span class=\"vermelho\">Nenhum exame solicitado!</span>";
        ?>
        <br />
    
    </fieldset>
    
    <fieldset>
        <legend>Medicamentos receitados:</legend>
        
        <?
        $result_rec= mysql_query("select * from consultas_remedios where id_consulta = '$rs->id_consulta' ");
        if (mysql_num_rows($result_rec) > 0) {
        ?>
    
        <table cellspacing="0">
            <tr>
                <th width="20%" align="left">Medicamento</th>
                <th width="10%" align="left">Quantidade</th>
                <th width="50%" align="left">Receita</th>
                <th width="20%" align="left">OBS</th>
            </tr>
            <? while ($rs_rec= mysql_fetch_object($result_rec)) { ?>
            <tr>
                <td><?= pega_remedio($rs_rec->id_remedio); ?></td>
                <td><?= $rs_rec->qtde ." ". pega_apresentacao($rs_rec->tipo_apres); ?></td>
                <?
				$receita = "";
				
				if ($rs_rec->tipo_acao=='a') $via_string= pega_vias_aplicacao($rs_rec->acao_local);
				if ($rs_rec->tipo_acao=='n') $via_string= $rs_rec->neb_com;
				
				$receita .= pega_tipo_acao($rs_rec->tipo_acao) ." ". $via_string ." ". $rs_rec->qtde_tomar ." ". pega_tipo_tomar($rs_rec->tipo_tomar) .", ";
				
				if ($rs_rec->tipo_periodicidade=="d")
					$receita .= $rs_rec->periodicidade ." vez(es) ao dia ";
				elseif ($rs_rec->tipo_periodicidade=="h")
					$receita .= " de ". $rs_rec->periodicidade ." em ". $rs_rec->periodicidade. " hora(s) ";
					
				if ($rs_rec->periodo!="0")
					$receita .= " por ". $rs_rec->periodo ." dia(s)";
				?>
                <td><?= $receita; $receita = ""; ?></td>
                <td><em><?= $rs_rec->observacoes; ?></em></td>
            </tr>
            <? } ?>
        </table>
        
        <? /*<ul class="recuo3">
            <?
            while ($rs_rec= mysql_fetch_object($result_rec)) {
            ?>
            <li>
            <b>Remédio:</b> <?= pega_remedio($rs_rec->id_remedio); ?> <br />
            <b>Quantidade:</b> <?= $rs_rec->qtde ." ". pega_apresentacao($rs_rec->tipo_apres); ?> <br />
            <?
            $receita = "";
            
            $receita .= "Tomar ". $rs_rec->qtde_tomar ." unidade(s) ";
            
            if ($rs_rec->tipo_periodicidade=="d")
                $receita .= $rs_rec->periodicidade ." vez(es) ao dia ";
            elseif ($rs_rec->tipo_periodicidade=="h")
                $receita .= " de ". $rs_rec->periodicidade ." em ". $rs_rec->periodicidade. " hora(s) ";
                
            $receita .= " durante ". $rs_rec->periodo ." dia(s).";
    
            ?>
            <b>Receita:</b> <?= $receita; $receita = ""; ?> <br />
            <em><?= $rs_rec->observacoes; ?></em>
            <br />
            </li>
            <? } ?>
        </ul>*/ ?>
        
        <? } else { ?>
        <span class="vermelho">Nenhum remédio receitado!</span>
        <? } ?>
    </fieldset>
    <? } ?>
</div>
<br /><br />
<? } } ?>


<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>