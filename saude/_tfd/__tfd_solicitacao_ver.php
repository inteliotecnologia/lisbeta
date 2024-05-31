<? if (@pode("t", $_SESSION["permissao"])) {
	
	if ($_GET["id_solicitacao"]!="")
		$id_solicitacao= $_GET["id_solicitacao"];
	
	$result= mysql_query("select tfds_solicitacoes.*, pessoas.id_responsavel, pessoas.nome, pessoas.cpf,
							DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%d/%m/%Y') as data_solicitacao, tfds_finalidades.*,
							DATE_FORMAT(tfds_solicitacoes.data_operacao, '%d/%m/%Y %H:%i:%s') as data_operacao,
							
							DATE_FORMAT(tfds_solicitacoes.data_atividade, '%d/%m/%Y') as data_atividade,
							DATE_FORMAT(tfds_solicitacoes.data_atividade, '%H:%i') as hora_atividade
							
							from tfds_solicitacoes, tfds_finalidades, pessoas
							where tfds_solicitacoes.id_solicitacao = '$id_solicitacao'
							and   tfds_solicitacoes.id_finalidade = tfds_finalidades.id_finalidade
							and   tfds_solicitacoes.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							and   tfds_solicitacoes.id_pessoa = pessoas.id_pessoa
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<div id="formTfdInserir">
	<? if (!isset($parcial)) { ?>
    <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_solicitacao_listar');" id="botao_voltar">&lt;&lt; voltar para listagem de solicitações</a>
    
    <h2 class="titulos">Visualização da solicitação para TFD nº <?= $rs->id_solicitacao; ?></h2>
    <? } ?>
    <fieldset>
        <legend>Dados da solicitação</legend>
        <div class="parte50">
            <? if (!isset($parcial)) { ?>
            <label>Nome:</label>
	        <?= $rs->nome ." ". mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?>
	        <br />
   			<? } ?>
            
			<? if ($rs->id_interno!="") { ?>
            <label>Cód.interno:</label>
            <?= $rs->id_interno; ?>
            <br />
            <? } else { ?>
            <label>Cód.:</label>
            <?= $rs->id_solicitacao; ?>
            <br />
            <? } ?>
            
            <label>Protocolo:</label>
            <?= sim_nao($rs->protocolo); ?>
            <br />
            
            <label>Nº registro:</label>
            <?
            if ($rs->registro=="")
				echo "<span class=\"vermelho\">Não disponível</span>";
			else
				echo $rs->registro;
			?>
            <br />
            
            <label>Destino:</label>
            <?= pega_cidade($rs->id_cidade_tfd); ?>
            <br />
            
            <label>Finalidade:</label>
            <?= pega_tipo_ida($rs->tipo_ida) ." (". $rs->finalidade .")"; ?>
            <br />

            <label>Data:</label>
            <?= $rs->data_solicitacao; ?>
            <br />
        </div>
        <div class="parte50">        
            <label>Observações:</label>
            <? if ($rs->observacoes=="") echo "<span class=\"vermelho\">Não informado.</span>"; else echo $rs->observacoes; ?>
            <br />
            
            <label>Última atualização:</label>
            <?= $rs->data_operacao; ?>
            <br />
        
            <label>Funcionário:</label>
            <?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
            <br />
            <? if (!isset($parcial)) { ?>
            <? //if ($rs->situacao_solicitacao==1) { ?>
            <form action="<?= AJAX_FORM; ?>formSolicitacaoTfdEditar" method="post" id="formSolicitacaoTfdEditar" name="formSolicitacaoTfdEditar" onsubmit="return ajaxForm('conteudo', 'formSolicitacaoTfdEditar');">
                <input type="hidden" name="id_solicitacao" id="id_solicitacao" class="escondido" value="<?= $rs->id_solicitacao; ?>" />
                <label>Situação:</label>
                <select name="situacao_solicitacao" id="situacao_solicitacao" onchange="mostraRegistroSolicitacaoTfd();">
                    <option value="">--- selecione ---</option>
					<?
					$vetor= pega_situacao_solicitacao_tfd('l');
					
					$i=0; $j=0; $k=0;
					while ($vetor[$i][1]!="") {
					?>
					<option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>" <? if ($rs->situacao_solicitacao==$vetor[$i][0]) echo "selected=\"selected\""; ?>><?= $vetor[$i][1]; ?></option>
					<? $i++; $k++; } ?>
                </select>
                <br />
                
                <div id="registro_atualiza" <? if (($rs->situacao_solicitacao!=2) && ($rs->situacao_solicitacao!=4)) echo "class=\"escondido\""; ?>>
                    <label>Nº registro:</label>
                    <input name="registro" id="registro" value="<?= $rs->registro; ?>" />
                    <br />
                    
                    <label for="id_entidade">Entidade:</label>
                    <select name="id_entidade" id="id_entidade">
                      <option value="">--- selecione ---</option>
                      <?
                        $result_ent= mysql_query("select * from tfds_entidades
                                                    where id_cidade= '$rs->id_cidade_tfd'
                                                    order by entidade ");
                        $i= 0;
                        while ($rs_ent= mysql_fetch_object($result_ent)) {
                      ?>
                      <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_ent->id_entidade; ?>" <? if ($rs->id_entidade==$rs_ent->id_entidade) echo "selected=\"selected\""; ?>><?= $rs_ent->entidade; ?></option>
                      <? $i++; } ?>
                    </select>
                    <br />
                    
                    <?
					if ($rs->data_atividade!="00/00/0000")
						$data_atividade= $rs->data_atividade;
					else
						$data_atividade= "";
					
					if ($rs->hora_atividade!="00:00")
						$hora_atividade= $rs->hora_atividade;
					else
						$hora_atividade= "";
					?>
                    
                    <label for="data_atividade">Data atividade:</label>
			        <input name="data_atividade" id="data_atividade" onkeyup="formataData(this);" value="<?= $data_atividade; ?>" maxlength="10" />
			        <br />
                    
                    <label for="hora_atividade">Hora atividade:</label>
			        <input name="hora_atividade" id="hora_atividade" onkeyup="formataHora(this);" value="<?= $hora_atividade; ?>" maxlength="5" />
			        <br />
                    
                </div>
                <br />
                
                <label>&nbsp;</label>
                <button type="submit">Atualizar</button>
                <br /><br /><br />
                
                <a class="botao tamanho120" onclick="return confirm('Tem certeza que deseja excluir esta solicitação?\n\nTODOS OS DADOS DA SOLICITAÇÃO SERÃO PERDIDOS!');" href="javascript:ajaxLink('conteudo', 'tfdSolicitacaoExcluir&amp;id_solicitacao=<?= $rs->id_solicitacao; ?>');">Excluir solicitação</a>
                <br />
            </form>
            <? /* } else { ?>
            <label>Situação:</label>
            <?
            echo pega_situacao_solicitacao_tfd($rs->situacao_solicitacao);
			
			if ($rs->situacao_solicitacao==5) {
				$result_tfd= mysql_query("select tfds.id_tfd, DATE_FORMAT(tfds.data_partida, '%d/%m/%Y') as data_partida
											from tfds, tfds_pessoas
											where tfds_pessoas.id_tfd = tfds.id_tfd
											and   tfds_pessoas.id_solicitacao = '". $rs->id_solicitacao ."'
											");
				$rs_tfd= mysql_fetch_object($result_tfd);
				
				if ($rs_tfd->id_tfd!="")
				echo "&nbsp;&nbsp;<a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_ver&amp;id_tfd=". $rs_tfd->id_tfd ."');\">TFD nº ". $rs_tfd->id_tfd ." em ". $rs_tfd->data_partida ."</a>";
			}
			?>
            <br />
            <? }*/ } ?>
        </div>
    </fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>