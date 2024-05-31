<? if (@pode("r", $_SESSION["permissao"])) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Agendamento de consultas</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formAgendaInserir" method="post" id="formAgendaInserir" name="formAgendaInserir" onsubmit="return ajaxForm('conteudo', 'formAgendaInserir');">
	
    <fieldset>
        <legend>Novo agendamento</legend>
        
        <label>Consulta:</label>
        <input type="radio" name="para" id="para_m" class="tamanho20" checked="checked" value="m" onclick="abreDiv('consulta_medica_tipo_atendimento');" /> <label for="para_m" class="tamanho120 alinhar_esquerda nao_negrito">Médica/enfermagem</label>
        <input type="radio" name="para" id="para_o" class="tamanho20" value="o" onclick="fechaDiv('consulta_medica_tipo_atendimento');" /> <label for="para_o" class="tamanho50_e nao_negrito">Odontológica</label>
        <br />
        
        <label>CPF:</label>
        <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario" onmouseover="Tip('Digite o CPF completo do paciente ou busque pelo nome no campo ao lado.');"/>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button" onmouseover="Tip('Clique para fazer busca por nome.');">busca</button>
        <br/>
        
        <label>&nbsp;</label>
        <div id="cpf_usuario_atualiza">
            <input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
        </div>
        <br />

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
        ?>
        <input type="radio" name="local_consulta" id="local_consulta_p" class="tamanho20" checked="checked" value="p" /> <label for="local_consulta_p" class="tamanho200 alinhar_esquerda nao_negrito"><?=$local;?></label>
        <input type="radio" name="local_consulta" id="local_consulta_d" class="tamanho20" value="d" /> <label for="local_consulta_d" class="tamanho200 alinhar_esquerda nao_negrito">Domicílio</label>
        <br />
        
        <div id="consulta_medica_tipo_atendimento">
            <label for="id_tipo_atendimento">Tipo de atendimento:</label>
            <select name="id_tipo_atendimento" id="id_tipo_atendimento">
            <?
            $vetor= pega_tipo_atendimento('l');
            $i=1;
            
            while ($vetor[$i]) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($i==9) echo "selected=\"selected\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <?
                $i++;
            }
            ?>
            </select>
            <br />
        
            <label>Tipo:</label>
            <input type="radio" name="tipo_consulta" id="tipo_consulta_c" class="tamanho20" checked="checked" value="c" /> <label for="tipo_consulta_c" class="tamanho50_e nao_negrito">Consulta</label>
            <input type="radio" name="tipo_consulta" id="tipo_consulta_r" class="tamanho20" value="r" /> <label for="tipo_consulta_r" class="tamanho50_e nao_negrito">Retorno</label>
            <br />
            
            <label>Residência:</label>
            <input type="radio" name="area_abran" id="area_abran_1" class="tamanho20" checked="checked" value="1" /> <label for="area_abran_1" class="tamanho160 nao_negrito alinhar_esquerda">Na área de abrangência</label>
            <input type="radio" name="area_abran" id="area_abran_0" class="tamanho20" value="0" /> <label for="area_abran_0" class="tamanho160 nao_negrito alinhar_esquerda">Fora da área de abrangência</label>
            <br />
        </div>
        <br />
        
        <?
		$result_pr= mysql_query("select pessoas.nome, usuarios.id_usuario as id_profissional from pessoas, usuarios, usuarios_postos
								where pessoas.id_pessoa = usuarios.id_pessoa
								and   usuarios.id_usuario = usuarios_postos.id_usuario
								and   usuarios_postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
								/* and   INSTR(usuarios_postos.permissao, '". $var_tipo ."')<>'0' */
								order by pessoas.nome asc
								");
		?>
        
        <label for="id_profissional">Profissional:</label>
        <select name="id_profissional" id="id_profissional" class="tamanho300">
        	<option value="">--- NÃO ESPECIFICAR ---</option>
        <?
        $i=0;
        while ($rs_pr= mysql_fetch_object($result_pr)) {
            ?>
            <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pr->id_profissional; ?>"><?= $rs_pr->nome; ?></option>
            <?
            $i++;
        }
        ?>
        </select>
        <br />
        
        <label for="dia_agendamento">Data:</label>
        <input name="dia_agendamento" id="dia_agendamento" onfocus="displayCalendar(dia_agendamento, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" onmouseover="Tip('Digite a data da consulta. Exemplo: 10/10/2007<br /><em>(as barras (/) são colocadas automaticamente)</em>.');" />
        <br />
        
        <label for="hora_agendamento">Hora:</label>
        <input name="hora_agendamento" id="hora_agendamento" onkeyup="formataHora(this);" maxlength="5" class="tamanho50" onmouseover="Tip('Digite a hora da consulta. Exemplo: 13:00<br /><em>(os dois pontos (:) são colocados automaticamente)</em>.');" />
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