<? if (@pode("r", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Consultas agendadas de <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens">
<?
include("__tratamento_msgs.php");

if ($data=="")
	$data= date("d/m/Y");
?>
</div>

<div id="tela_aux_rapida" class="nao_mostra">
</div>

<div id="busca" class="minima">
	<form action="<?= AJAX_FORM; ?>formAgendaBuscar" method="post" id="formAgendaBuscar" name="formAgendaBuscar" onsubmit="return ajaxForm('conteudo', 'formAgendaBuscar');">

		<label class="tamanho30" for="busca">Data:</label>
		
		<input name="data" id="data" onfocus="displayCalendar(data, 'dd/mm/yyyy', this);" class="tamanho100" onkeyup="formataData(this);" maxlength="10" value="<?= $data; ?>" />

		<button>Buscar</button>
	
	</form>
</div>

<div class="div_abas" id="aba_consultas">
    <ul class="abas">
        <li id="aba_consultas_med" class="atual"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_med'); abreDiv('agendamento_consultas_medicas'); fechaDiv('agendamento_consultas_odontologicas');">Consultas médicas/enfermagem</a></li>
        <? if (pode_algum("on", $_SESSION["permissao"])) { ?>
        <li id="aba_consultas_odo"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_consultas', 'aba_consultas_odo'); abreDiv('agendamento_consultas_odontologicas'); fechaDiv('agendamento_consultas_medicas');">Consultas odontológicas</a></li>
        <? } ?>
    </ul>
</div>

<div id="agendamento_consultas_medicas">
<?
$result= mysql_query("select pessoas.nome, agenda_consultas.*,
						DATE_FORMAT(agenda_consultas.data_agendada, '%d/%m/%Y %H:%i:%s') as data_agendada,
						DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc2
						from  pessoas, agenda_consultas
						where agenda_consultas.id_pessoa = pessoas.id_pessoa
						and   agenda_consultas.para = 'm'
						and   agenda_consultas.id_posto = '". $_SESSION["id_posto_sessao"] ."'
						and   DATE_FORMAT(agenda_consultas.data_agendada, '%d/%m/%Y') = '". $data ."'
						and   agenda_consultas.atendido = '0'
						order by agenda_consultas.local_consulta desc, agenda_consultas.data_preatendimento asc,
						agenda_consultas.data_agendada asc
						");
if (mysql_num_rows($result) > 0) {
?>	
    <h3 class="titulos">Consultas médicas ou de enfermagem</h3>
    
	<table cellspacing="0">
		<tr>
			<th width="6%">Cód.</th>
            <th width="7%" align="left">Local</th>
			<th width="7%" align="left">Ordem</th>
			<th width="22%" align="left">Nome</th>
			<th width="5%">Idade</th>
			<th width="22%">Profissional</th>
			<th width="22%">Consultar</th>
			<th width="12%">Data marcada</th>
            <th width="12%">Pré atendimento</th>
            <th width="7%" align="left">Ações</th>
		</tr>
		<?
		$k=1;
		while ($rs= mysql_fetch_object($result)) {
		
			if (@pode("e", $_SESSION["permissao"])) $tipo_consulta_prof= "e";
			if (@pode("c", $_SESSION["permissao"])) $tipo_consulta_prof= "m";
			
			if (@pode_algum("ecmi", $_SESSION["permissao"])) {
				//se já estiver sido pre-atendida e for médico o usuario atual
				if ($rs->pre_atendido==1) {
					$acao_onclick= "ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;tipo_consulta_prof=". $tipo_consulta_prof ."&amp;id_agenda=". $rs->id_agenda ."');";
					$tip= "Clique para consultar esta pessoa.";
				}
				else {
					$tip= "Para realizar a consulta, primeiro é necessário realizar o pré-atendimento";
					$acao_onclick= "alert('$tip');";
				}
			}
			else {
				$tip= "Pessoa com consulta agendada.";
				$acao_onclick= "alert('$tip');";
			}
		?>
		<tr <? if ($rs->atendido==0) { ?>class="corzinha"<? } ?>>
			<td align="center"><?= $rs->id_agenda; ?></td>
            <td><?= pega_local_consulta($rs->local_consulta); ?></td>
			<td><?= $k; ?></td>
			<td><?= $rs->nome; ?></td>
			<td align="center">
				<?
                $idade_meses= calcula_meses($rs->data_nasc2);
				$idade_anos= calcula_idade($rs->data_nasc2);
				
				$meses_adicionais= ($idade_anos%12);
				
				if ($idade_anos<7)
					echo $idade_anos ." anos e ". $meses_adicionais ." meses";
				else
					echo $idade_anos ." anos";
				
				//echo " (". $meses ." meses)";
				?>
            </td>
			<td align="center"><?= pega_nome_pelo_id_usuario($rs->id_profissional); ?></td>
			<td align="center">
            	<? if ($rs->atendido==0) { ?>
					<? if (@pode_algum("ci", $_SESSION["permissao"])) { ?>
                    .
                    <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;tipo_consulta_prof=m&amp;id_agenda=<?=$rs->id_agenda;?>');">médica</a>
                    <? } ?>
                    
                    <? if (@pode_algum("em", $_SESSION["permissao"])) { ?>
                    .
                    <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;tipo_consulta_prof=e&amp;id_agenda=<?=$rs->id_agenda;?>');">enfermagem</a>
                    <? } ?>
                    .
                <? } else echo "Consulta realizada"; ?></td>
			<td align="center"><?= $rs->data_agendada; ?></td>
            <td align="center" <? if ($rs->atendido==0) { ?>class="maozinha" onclick="abreDivSo('tela_aux_rapida'); ajaxLink('tela_aux_rapida', 'carregaPaginaInterna&amp;pagina=_consultas/pre_consulta&amp;id_agenda=<?= $rs->id_agenda; ?>');" <? } ?>><?= sim_nao($rs->pre_atendido); ?></td>
            <td>
            	<? if ($rs->atendido==0) { ?>
                <a onclick="return confirm('Tem certeza que deseja excluir este agendamento?\n\nOPERAÇÃO IRREVERSÍVEL!');" href="javascript:ajaxLink('conteudo', 'agendaExcluir&amp;id_agenda=<?= $rs->id_agenda; ?>&amp;data=<?= $data; ?>');" class="link_excluir" title="Excluir">excluir</a>
               	<? } else echo "N/A"; ?>            </td>
		</tr>
		<? $k++; } ?>
	</table>
<?
}
else
	echo "<center><br />Nenhum agendamento para consultas médicas no dia de hoje!</center>";
?>
</div>

<div class="nao_mostra" id="agendamento_consultas_odontologicas">
<?

$result= mysql_query("select pessoas.nome, agenda_consultas.*,
						DATE_FORMAT(agenda_consultas.data_agendada, '%d/%m/%Y %H:%i:%s') as data_agendada
						from  pessoas, agenda_consultas
						where agenda_consultas.id_pessoa = pessoas.id_pessoa
						and   agenda_consultas.para = 'o'
						and   agenda_consultas.id_posto = '". $_SESSION["id_posto_sessao"] ."'
						and   DATE_FORMAT(agenda_consultas.data_agendada, '%d/%m/%Y') = '". $data ."'
						order by agenda_consultas.local_consulta desc, agenda_consultas.data_agendada asc
						");
if (mysql_num_rows($result) > 0) {
?>	
	<h3 class="titulos">Consultas odontológicas</h3>
    
	<table cellspacing="0">
		<tr>
			<th width="6%">Cód.</th>
            <th width="8%" align="left">Local</th>
			<th width="23%" align="left">Nome</th>
			<th width="13%">Consultar</th>
			<th width="30%">Data marcada</th>
            <th width="13%">Consulta realizada</th>
			<th width="10%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			if (pode_algum("on", $_SESSION["permissao"])) {
				$acao_onclick= "ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;tipo_consulta_prof=o&amp;id_agenda=". $rs->id_agenda ."');";
				$tip= "Clique para consultar esta pessoa.";
			}
			else {
				$acao_onclick= "alert('Você não tem permissão para realizar uma consulta odontológica!\\n\\nEntre em contato com os administradores do sistema!');";
				$tip= "Consulta agendada.";
			}
				
		?>
		<tr>
			<td align="center"><?= $rs->id_agenda; ?></td>
            <td><?= pega_local_consulta($rs->local_consulta); ?></td>
			<td class="maozinha" onclick="<?=$acao_onclick;?>"><?= $rs->nome; ?></td>
			<td align="center">
            <? if ($rs->atendido==0) { ?>
				<? if (@pode_algum("on", $_SESSION["permissao"])) { ?>
                <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;tipo_consulta_prof=o&amp;id_agenda=<?=$rs->id_agenda;?>');">odontológica</a>
                <? } else echo "N/A"; ?>
            <? } ?>
            </td>
			<td align="center"><?= $rs->data_agendada; ?></td>
            <td align="center"><?= sim_nao($rs->atendido); ?></td>
			<td>
            	<? if ($rs->atendido==0) { ?>
                <a onclick="return confirm('Tem certeza que deseja excluir este agendamento?\n\nOPERAÇÃO IRREVERSÍVEL!');" href="javascript:ajaxLink('conteudo', 'agendaExcluir&amp;id_agenda=<?= $rs->id_agenda; ?>&amp;data=<?= $data; ?>');" class="link_excluir" title="Excluir">excluir</a>
               	<? } else echo "N/A"; ?>            </td>
		</tr>
		<? } ?>
	</table>
    <br /><br /><br /><br /><br />

<?
}
else
	echo "<center><br />Nenhum agendamento para consultas odontológicas no dia de hoje!</center>";
?>
</div>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>