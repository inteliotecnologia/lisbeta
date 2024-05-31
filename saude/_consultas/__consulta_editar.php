<?
if (@pode_algum("oecmin", $_SESSION["permissao"])) {
	$result= mysql_query("select *, DATE_FORMAT(data_consulta, '%Y-%m-%d %H:%i:%s') as data_consulta2 from consultas
							where id_consulta = '". $_GET["id_consulta"] ."'
							and   id_posto = '". $_SESSION["id_posto_sessao"] ."'
							");
	$rs= mysql_fetch_object($result);
	
	$intervalo= retorna_intervalo($rs->data_consulta2, date("Y-m-d H:i:s"));
	
	//echo $rs->data_consulta2;
	
	if ($intervalo>7200) {
		echo "<h2 class=\"titulos\">Consulta</h2><p>Acesso negado, consulta feita há mais de 2 hora atrás!</p>";
		die();
	}
	else {
		if ( ($_SESSION["id_usuario_sessao"]!=$rs->id_usuario) && ($_SESSION["id_usuario_sessao"]!=$rs->id_usuario_usando) ) {
			echo "<h2 class=\"titulos\">Consulta</h2> <p>Acesso negado, você não pode editar uma consulta que não foi você quem realizou!</p>";
			die();
		}
	}
	
	$tipo_consulta_prof= $rs->tipo_consulta_prof;
?>
<h2 class="titulos">Consulta <?= pega_tipo_consulta_prof($tipo_consulta_prof); ?></h2>

	<div id="tela_relatorio">
	</div>

	<div id="pre_atendimento" class="parte_esquerda_i"><? /* onsubmit="return ajaxForm('conteudo', 'formConsultaInserir');" */ ?>
		<fieldset>
			<legend>Pré-atendimento:</legend>
			
            <strong>
			<?
				$origem_consulta= explode('@', $rs->origem_consulta);
				
				if ($origem_consulta[0]=="f")
					$result_paciente= mysql_query("select filas.*, pessoas.nome,
													DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc,
													pessoas.cpf, pessoas.id_responsavel, pessoas.id_pessoa, pessoas.sexo
													from pessoas, filas
													where filas.id_pessoa = pessoas.id_pessoa
													and   filas.id_fila = '". $origem_consulta[1] ."'
													");
				else
					$result_paciente= mysql_query("select agenda_consultas.*, pessoas.nome,
													DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc,
													pessoas.cpf, pessoas.id_responsavel, pessoas.id_pessoa, pessoas.sexo
													from pessoas, agenda_consultas
													where agenda_consultas.id_pessoa = pessoas.id_pessoa
													and   agenda_consultas.id_agenda = '". $origem_consulta[1] ."'
													and   agenda_consultas.atendido = '1'
													");
				
				if (mysql_num_rows($result_paciente)==0)
					die();
				
				$rs_paciente= mysql_fetch_object($result_paciente);
				
				echo $rs_paciente->nome;
				echo " ". mostra_cpf_ou_responsavel($rs_paciente->cpf, $rs_paciente->id_responsavel);
			?>
            </strong>
			<br />
            
			<a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/pessoa_ver&amp;id_pessoa=<?= $rs_paciente->id_pessoa; ?>&amp;relatorio=1');" onmouseover="Tip('Clique para ver os dados pessoais.');">dados</a>
			<? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?> |
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_consultas_completo&amp;id_pessoa_hist=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver o histórico de consultas.');">consultas</a> |
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver os medicamentos entregues na farmácia para esta pessoa.');">remédios</a> |
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_acomp_completo&amp;id_pessoa_hist=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver os acompanhamentos.');">acomp.</a>
			<? } ?>
			<br />
			
            <?
			$idade= calcula_idade($rs_paciente->data_nasc);
			echo $idade;
			?> anos
            
            <? if ($idade<7) { ?>
            /
            <?
			$meses= calcula_meses($rs_paciente->data_nasc);
			echo $meses;
			?> meses
            <? } ?>
            <br />
            
            <? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?>
			<?= pega_tipo_consulta($rs_paciente->tipo_consulta); ?>
			<br />
			
            <? } ?>
		</fieldset>
		<br />
		
        <? if ($tipo_consulta_prof=="m") { ?>
        <div id="remedio_cadastro" class="nao_mostra">
            <a href="javascript:void(0);" onclick="abreFechaDiv('remedio_cadastro');" class="fechar">x</a>
			
			<h2 class="titulos" id="tit_remedio_cadastro">Cadastro de remédio</h2>
	        
            <div id="remedio_cadastro3">
            </div>

            <label for="remedio">Remédio:</label>
            <input name="remedio" id="remedio" onkeyup="if (event.keyCode==13) remedioCadastroOk();" />
            <br />
            
            <label for="tipo_remedio">Tipo:</label>
            <select name="tipo_remedio" id="tipo_remedio" onkeyup="if (event.keyCode==13) remedioCadastroOk();">
                <option value="" selected="selected">---</option>
                <?
                $vetor= pega_tipo_remedio('l');
                
                $i=0; $j=0;
                while ($vetor[$i][$j]) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>"><?= $vetor[$i][1]; ?></option>
                <? $i++; } ?>
            </select>
            <br />
        
            <label>&nbsp;</label>
            <input name="classificacao_remedio" id="classificacao_remedio" type="checkbox" value="c" class="tamanho20" />
            <label for="classificacao_remedio" class="nao_negrito">Controlado</label>
            <br /><br />

            <label>&nbsp;</label>
            <button type="button" onclick="remedioCadastroOk();">Adicionar &gt;&gt;</button>
        </div>
        
		<fieldset>
			<legend>Medicação prescrita:</legend>
		
			<label>Pesquisar:</label>
			<input id="pesquisa" name="pesquisa" class="tamanho80" onkeyup="if (event.keyCode==13) remedioPesquisar(1);" />
			<button type="button" class="tamanho30" onclick="remedioPesquisar(1);">ok</button>
			<br />
			
			<div id="pesquisa_remedio_atualiza">
			
			</div>
            <button onclick="abreFechaDiv('remedio_cadastro'); daFoco('remedio');" class="tamanho120">cadastrar remédio</button>
		</fieldset>
		<? } ?>
        
        <? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?>
        <div id="exame_cadastro" class="nao_mostra">
            <a href="javascript:void(0);" onclick="abreFechaDiv('exame_cadastro');" class="fechar">x</a>
			
			<h2 class="titulos" id="tit_exame_cadastro">Cadastro de exame</h2>
	        
            <div id="exame_cadastro3">
            </div>
            
            <label>Exame:</label>
            <input id="exame" name="exame" onkeyup="if (event.keyCode==13) exameCadastroOk();"  />
            <br />
            
            <label for="tipo_exame">Tipo:</label>
            <select name="tipo_exame" id="tipo_exame" onkeyup="if (event.keyCode==13) exameCadastroOk();">
                <?
                $vetor= pega_tipo_exame('l');
                
                $i=1;
                while ($vetor[$i]) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; } ?>
            </select>
            <br /><br />
            
            <label>&nbsp;</label>
            <button type="button" onclick="exameCadastroOk();">Adicionar &gt;&gt;</button>
        </div>
        
        <fieldset>
            <legend>Exames solicitados:</legend>

			<label>Pesquisar:</label>
			<input id="pesquisa2" name="pesquisa2" class="tamanho80" onkeyup="if (event.keyCode==13) examePesquisar(1);" />
			<button type="button" class="tamanho30" onclick="examePesquisar(1);">ok</button>
			<br />
			
			<div id="pesquisa_exame_atualiza">
			
			</div>
			<button onclick="abreFechaDiv('exame_cadastro'); daFoco('exame');" class="tamanho120">cadastrar exame</button>
            
        </fieldset>

        <fieldset>
            <legend>Pesquisa CID para diagnóstico inicial:</legend>

			<label>Pesquisar:</label>
			<input id="pesquisa3" name="pesquisa3" class="tamanho80" onkeyup="if (event.keyCode==13) cidPesquisar();" />
			<button type="button" class="tamanho30" onclick="cidPesquisar();">ok</button>
			<br />
			
			<div id="pesquisa_cid_atualiza">
			
			</div>
            
        </fieldset>
		<? } ?>
        
        <? if ($tipo_consulta_prof=="m") { ?>
		<div id="receita_remedio">
			<a href="javascript:void(0);" onclick="fechaDiv('receita_remedio');" class="fechar">x</a>
			<div id="receita_remedio2">
				<h2 class="titulos" id="tit_nova_prescricao">Nova prescrição</h3>
                
                <h3 id="tit_remedio">Remédio</h3>
				
				<input id="id_remedio_pre" name="id_remedio_pre" type="hidden" class="escondido" value="" />
				<input id="tit_remedio_pre" name="tit_remedio_pre" type="hidden" class="escondido" value="" />
				
				<label for="qtde">Quantidade:</label>
				<input id="qtde" name="qtde" class="tamanho30" maxlength="2" />
				
				<?
				/*
				<select name="tipo_apresentacao" id="tipo_apresentacao" class="tamanho100">
					<option value="c">Caixa(s)</option>
					<option value="u" selected="selected">Unidade(s)</option>
				</select>
				*/
				?>
                <input type="hidden" class="escondido" name="tipo_apresentacao" id="tipo_apresentacao" value="u" />
                unid(s)
				<br />
				
				<fieldset>
					<legend>Receita:</legend>
					
					<label for="tipo_acao">Ação:</label>
					<select name="tipo_acao" id="tipo_acao" class="tamanho160" onchange="alteraLocaisAplicacao(this.value);">
						<option value="t" selected="selected">Tomar</option>
						<option value="a" class="cor_sim">Aplicar</option>
                        <option value="n">Nebulizar</option>
					</select>
					<br />
                    
                    <div id="consulta_aplicar" class="nao_mostra">
                        <label for="acao_local">Local:</label>
                        <select name="acao_local" id="acao_local" class="tamanho160">
                        	<option value="">---</option>
                            <?
                            $vetor= pega_vias_aplicacao('l');
                            $i=1;
                            while ($vetor[$i]) {
                            ?>
                            <option value="<?=$i;?>" <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>><?=$vetor[$i];?></option>
                            <? $i++; } ?>
                        </select>
                        <br />
                    </div>
                    <div id="consulta_nebulizar" class="nao_mostra">
	                    <label for="neb_com">NBZ c/:</label>
	                    <textarea name="neb_com" class="tamanho160" id="neb_com"></textarea>
	                    <br />
                    </div>
                    
                    <label for="qtde_tomar">Quantidade:</label>
					<input class="tamanho30" id="qtde_tomar" name="qtde_tomar" /> 
					
					<select name="tipo_tomar" id="tipo_tomar" class="tamanho140">
						<option value="c" selected="selected">comprimido(s)</option>
						<option value="g" class="cor_sim">gota(s)</option>
						<option value="m">ml</option>
                        <option value="s" class="cor_sim">spray</option>
                        <option value="f">flaconete</option>
                        <option value="d" class="cor_sim">dose</option>
					</select>

					<br />
					
					<label for="periodicidade1">Periodicidade:</label>
					<input class="tamanho30" id="periodicidade1" name="periodicidade1" maxlength="2" /> x ao dia 
					<br />
					
					<label>&nbsp;</label>
					<span class="flutuar_esquerda"><strong>ou</strong> a cada&nbsp;</span>
					
					<input class="tamanho30" id="periodicidade2" name="periodicidade2" maxlength="2" />
					
					<span class="flutuar_esquerda">horas</span>
					<br />
					
					<label for="periodo">Período:</label>
					<input class="tamanho30" id="periodo" name="periodo" maxlength="3" /> dias
					<br />
					
					<label for="observacoes">Observações:</label>
					<textarea name="observacoes" id="observacoes"></textarea>
					<br />
				
				</fieldset>
				
				<label>&nbsp;</label>
				<button type="button" onclick="remedioReceitaOk();">Adicionar &gt;&gt;</button>
				<br /><br />
			</div>
		</div>
		<? } ?>
        
        <? if ($tipo_consulta_prof=="o") { ?>
        <fieldset>
            <legend>Tratamentos/procedimentos realizados:</legend>

			<label>Pesquisar:</label>
			<input id="pesquisa3" name="pesquisa3" class="tamanho80" onkeyup="if (event.keyCode==13) procedimentoOdontoPesquisar();" />
			<button type="button" class="tamanho30" onclick="procedimentoOdontoPesquisar();">ok</button>
			<br />
			
			<div id="pesquisa_procedimento_ondonto_atualiza">
			
			</div>
			<? /*<button onclick="abreFechaDiv('exame_cadastro'); daFoco('exame');" class="tamanho120">cadastrar exame</button> */ ?>
            
        </fieldset>
        <? } ?>
	</div>
	
    <div id="tela_aux_rapida" class="nao_mostra">
    
    </div>
                
    <form action="<?= AJAX_FORM; ?>formConsultaEditar" method="post" name="formConsultaEditar" id="formConsultaEditar" onsubmit="return ajaxForm('conteudo', 'formConsultaEditar');">
	
	<div class="parte_direita_i">
			
        <input type="hidden" class="escondido" id="id_consulta" name="id_consulta" value="<?= $rs->id_consulta; ?>" />
		<input type="hidden" class="escondido" id="data_consulta" name="data_consulta" value="<?= $rs->data_consulta; ?>" />
        
        <?
		if (pode_algum("min", $_SESSION["permissao"])) {

			switch($tipo_consulta_prof) {
				case "m": $var_tipo="c";
						break;
				case "e": $var_tipo="e";
						break;
				case "o": $var_tipo="o";
						break;
				default: die();
			}
			
					
			$sql= "select pessoas.nome, usuarios.id_usuario from pessoas, usuarios, usuarios_postos
					where pessoas.id_pessoa = usuarios.id_pessoa
					and   usuarios.id_usuario = usuarios_postos.id_usuario
					and   usuarios_postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
					and   INSTR(usuarios_postos.permissao, '". $var_tipo ."')<>'0'
					order by pessoas.nome asc
					";
			$result_pr= mysql_query($sql);
		?>
        <fieldset>
        	<legend>Profissional que atendeu o paciente</legend>
            
            <label for="id_usuario">Profissional:</label>
            <select name="id_usuario" id="id_usuario" class="tamanho300">
            <?
			$i=0;
            while ($rs_pr= mysql_fetch_object($result_pr)) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($rs->id_usuario==$rs_pr->id_usuario) echo "selected=\"selected\""; ?> value="<?= $rs_pr->id_usuario; ?>"><?= $rs_pr->nome; ?></option>
                <?
                $i++;
            }
            ?>
            </select>
        <br />
        </fieldset>
        <? } ?>
        
		
		<? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?>
        <fieldset>
        	<legend>Tipo de atendimento</legend>
            
            <label for="id_tipo_atendimento">Tipo:</label>
            <select name="id_tipo_atendimento" id="id_tipo_atendimento" class="tamanho300">
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
        </fieldset>
        <? } ?>
        
        <? if ($tipo_consulta_prof=="m") { ?>
        <fieldset>
            <legend>Anamnese e Exame Clínico</legend>
        
            <label for="anamnese">Anamnese:</label>
            <textarea name="anamnese" id="anamnese" class="grandao" ><?= $rs->anamnese; ?></textarea>
            <br />
            <? /*
            <label for="qp">Queixa principal:</label>
            <textarea name="qp" id="qp" class="grandao" onmouseover="Tip('Em poucas palavras, deve ser registrada a queixa principal,<br />o motivo que levou o paciente a procurar o médico.');"></textarea>
            <br />
            
            <label for="hda">Histórico da doença atual:</label>
            <textarea name="hda" id="hda" class="grandao" onmouseover="Tip('No histórico da doença atual deve ser registrado tudo que se relaciona quanto à doença atual,<br />sintomatologia, época de início, história da evolução da doença, entre outros.<br />Em caso de dor, deve-se caracterizá-la por completo.');"></textarea>
            <br />
            
            <label for="hmp">Histórico médica pregressa:</label>
            <textarea name="hmp" id="hmp" class="grandao" onmouseover="Tip('Adquire-se informações sobre toda a história médica do paciente,<br />mesmo das condições que não estejam relacionadas com a doença atual.');"></textarea>
            <br />
            
            <label for="hf">Histórico familiar:</label>
            <textarea name="hf" id="hf" class="grandao" onmouseover="Tip('Neste histórico perguntamos ao paciente sobre sua família e suas condições de trabalho e vida.<br />Procura-se alguma relação de hereditariedade das doenças.');"></textarea>
            <br />
            
            <label for="hps">História pessoal e social:</label>
            <textarea name="hps" id="hps" class="grandao" onmouseover="Tip('Procura-se a informação de onde o paciente trabalha e vive,<br />pois estas informações podem ser muito valiosas para<br />o médico levantar hipóteses de diagnóstico.');"></textarea>
            <br />
            
            <label for="rs">Revisão de sistemas:</label>
            <textarea name="rs" id="rs" class="grandao" onmouseover="Tip('Esta revisão, também conhecida como interrogatório sintomatológico ou anamnese especial,<br />consiste num interrogatório de todos os sistemas do paciente,<br />permitindo ao médico levantar hipóteses de diagnósticos.');"></textarea>
            <br />
			*/ ?>
            
            <label for="exame_clinico">Exame clínico:</label>
            <textarea name="exame_clinico" id="exame_clinico" class="grandao" ><?= $rs->exame_clinico; ?></textarea>
            <br />
            
            
            <label for="boletim_obs">Obs. boletim:</label>
            <textarea name="boletim_obs" id="boletim_obs" class="grandao" ><?= $rs->boletim_obs; ?></textarea>
            <br />
            
        </fieldset>
        <?
        }
		if ($tipo_consulta_prof=="e") {
		?>
        <fieldset>
            <legend>SOAP:</legend>
        
            <label for="s">Subjetivo:</label>
            <textarea name="s" id="s" class="grandao"><?= $rs->s; ?></textarea>
            <br />
            
            <label for="o">Objetivo:</label>
            <textarea name="o" id="o" class="grandao"><?= $rs->o; ?></textarea>
            <br />
            
            <label for="a">Avaliação:</label>
            <textarea name="a" id="a" class="grandao"><?= $rs->a; ?></textarea>
            <br />
            
            <label for="p">Prescrição:</label>
            <textarea name="p" id="p" class="grandao"><?= $rs->p; ?></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Outras observações:</legend>
            
            <label for="obs">Observações:</label>
            <textarea name="obs" id="obs" class="grandao"><?= $rs->obs; ?></textarea>
            <br />
            
        </fieldset>
        <?
        }
        if ($tipo_consulta_prof=="o") {
		?>
        <fieldset>
            <legend>Anamnese:</legend>
        
            <label for="anamnese">Anamnese:</label>
            <textarea name="anamnese" id="anamnese" class="grandao" ><?= $rs->anamnese; ?></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Exame de boca:</legend>
        
            <label for="exame_boca">Exame de boca:</label>
            <textarea name="exame_boca" id="exame_boca" class="grandao"><?= $rs->exame_boca; ?></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Observações:</legend>
            
            <label for="obs">Observações:</label>
            <textarea name="obs" id="obs" class="grandao"><?= $rs->obs; ?></textarea>
            <br />
            
        </fieldset>
        <? } ?>
        
        <? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?>
        <fieldset>
            <legend>Encaminhamento</legend>
			
            <label for="encaminhamento">Encaminhamento</label>
			<select name="encaminhamento" id="encaminhamento" class="tamanho300">
                <option value="" selected="selected">---</option>
                <?
                $vetor= pega_encaminhamento('l');
                
                $i=1;
                while ($vetor[$i]) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($rs->encaminhamento==$i) echo "selected=\"selected\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; } ?>
            </select>
            <br />
        </fieldset>
        
        <fieldset>
            <legend>Diagnóstico inicial:</legend>
            
            <label for="dias_atestado">Dias de atestado:</label>
            <input name="dias_atestado" id="dias_atestado" value="<?= $rs->dias_atestado; ?>" />
            <br />
            
            <input type="hidden" class="escondido" name="diagnostico_inicial" id="diagnostico_inicial" value="<?= $rs->diagnostico_inicial; ?>" />
            
            <label>CID:</label>
            <div id="diagnostico_ok">
            	<? if ( ($rs->diagnostico_inicial=="") || ($rs->diagnostico_inicial=="0")) { ?>
                <span class="vermelho">Nada selecionado até o momento!</span>
                <? } else echo pega_cid($rs->diagnostico_inicial) ." | <a onclick=\"removeDiagnosticoInicial();\" href=\"javascript:void(0);\">limpar</a>"; ?>
            </div>
            <br />
        </fieldset>
        
        
        <? if ($tipo_consulta_prof=="m") { ?>
        <fieldset>
            <legend>Remédios receitados:</legend>
            
            <div id="receita_ok">
                <?
				$result_rec= mysql_query("select * from consultas_remedios
											where id_consulta = '". $rs->id_consulta ."'
											");
				if (mysql_num_rows($result_rec)==0) {
				?>
                <span class="vermelho">Nenhum remédio receitado até o momento!</span>
                <?
                }
				else {
					while ($rs_rec= mysql_fetch_object($result_rec)) {
					?>
                    <div id="receita_<?= $rs_rec->id_remedio; ?>" class="div_receita_ok">
                        <h3 class="flutuar_esquerda"><?= pega_remedio($rs_rec->id_remedio); ?> - <?= $rs_rec->qtde; ?> <?= pega_apresentacao($rs_rec->tipo_apres); ?></h3>
                        <a class="fechar" href="javascript:removeRemedioReceita('<?= $rs_rec->id_remedio; ?>')" title="clique para retirar este remédio da receita">x</a>
                        <span>
                        <br/>
                        <p>
                        <?
						$receita = "";
						
						if ($rs_rec->tipo_acao=='a') $via_string= pega_vias_aplicacao($rs_rec->acao_local);
						
						if ($rs_rec->tipo_acao=='n') {
							$via_string= $rs_rec->neb_com;
							$receita .= pega_tipo_acao($rs_rec->tipo_acao) ." ". pega_remedio($rs_rec->id_remedio) ." (". $rs_rec->qtde_tomar ." ". pega_tipo_tomar($rs_rec->tipo_tomar) .") com ". $via_string;
						}
						else
							$receita .= pega_tipo_acao($rs_rec->tipo_acao) ." ". $via_string ." ". $rs_rec->qtde_tomar ." ". pega_tipo_tomar($rs_rec->tipo_tomar) .", ";
						
						if ($rs_rec->tipo_periodicidade=="d")
							$receita .= " ".$rs_rec->periodicidade ." vez(es) ao dia ";
						elseif ($rs_rec->tipo_periodicidade=="h")
							$receita .= " de ". $rs_rec->periodicidade ." em ". $rs_rec->periodicidade. " hora(s) ";
							
						if ($rs_rec->periodo!="0")
							$receita .= " por ". $rs_rec->periodo ." dia(s)";
						?>
						<?= $receita; $receita = ""; ?>
                        </p>
                        </span>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->id_remedio; ?>" name="pos_id_remedio[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->qtde; ?>" name="pos_qtde[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->tipo_apres; ?>" name="pos_tipo_apresentacao[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->tipo_acao; ?>" name="pos_tipo_acao[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->acao_local; ?>" name="pos_acao_local[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->neb_com; ?>" name="pos_neb_com[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->qtde_tomar; ?>" name="pos_qtde_tomar[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->tipo_tomar; ?>" name="pos_tipo_tomar[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->tipo_periodicidade; ?>" name="pos_tipo_periodicidade[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->periodicidade; ?>" name="pos_periodicidade[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->periodo; ?>" name="pos_periodo[]"/>
                        <input class="escondido" type="hidden" value="<?= $rs_rec->observacoes; ?>" name="pos_observacoes[]"/>
                    </div>
                    <? } ?>
                <? } ?>
            </div>
        </fieldset>
		<? } ?>
        
        <fieldset>
            <legend>Exames solicitados nesta consulta:</legend>
            
            <input type="hidden" class="escondido" name="num_exames" id="num_exames" value="" />
            
            <div id="exames_solicitacao_ok">
            	<?
				$result_exa= mysql_query("select * from consultas_exames
											where id_consulta = '". $rs->id_consulta ."'
											");
				if (mysql_num_rows($result_exa)==0) {
				?>
                <span class="vermelho">Nenhum exame solicitado até o momento!</span>
                <?
                }
				else { ?>
					<ul id="lista_ul" class="recuo1">
                    <?
					while ($rs_exa= mysql_fetch_object($result_exa)) {
					?>
                    <li id="li_exame_<?= $rs_exa->id_exame; ?>">
                        <input id="id_exame<?= $rs_exa->id_exame; ?>" class="escondido" name="id_exame[]" value="<?= $rs_exa->id_exame; ?>"/ >
                        <div class="nomeDeExame"><?= pega_exame($rs_exa->id_exame); ?></div>
                        <a href="javascript:removeExame('<?= $rs_exa->id_exame; ?>')" title="clique para excluir o exame">remover</a>
                    </li>
                    <? } ?>
                    </ul>
                <? } ?>
            </div>				
        </fieldset>
        
        <fieldset>
            <legend>Exames solicitados em consultas anteriores sem resultado:</legend>
            	
				<?
                $result_exa= mysql_query("select consultas_exames.*, exames.exame,
											DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
											consultas.id_usuario as id_medico
											from consultas, consultas_exames, exames
                                            where consultas.id_consulta = consultas_exames.id_consulta
											and   consultas_exames.id_exame = exames.id_exame
                                            and   consultas.id_pessoa = '". $rs_paciente->id_pessoa ."'
											and   consultas.id_consulta <> '". $rs->id_consulta ."'
											order by consultas.id_consulta desc
											");
				
                if (mysql_num_rows($result_exa) > 0) {
                ?>
               	<table cellspacing="0">
                	<tr>
	                    <th width="40%" align="left">Exame</th>
	                    <th width="40%" align="left">Resultado</th>
	                    <th width="20%" align="left">Ações</th>
                    </tr>
                    <? while ($rs_exa= mysql_fetch_object($result_exa)) { ?>
                    <tr>
                    	<td onmouseover="Tip('Exame prescrito por <?= pega_nome_pelo_id_usuario($rs_exa->id_medico); ?> em <?= $rs_exa->data_consulta; ?>');"><?= $rs_exa->exame; ?></td>
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
                        <td><a href="javascript:void(0);" onclick="abreDivSo('tela_aux_rapida'); ajaxLink('tela_aux_rapida', 'carregaPaginaInterna&amp;pagina=_consultas/resultado_exame&amp;id_consulta_exame=<?= $rs_exa->id_consulta_exame; ?>');">cadastrar/atualizar</a></td>                        
                    </tr>
                    <? } ?>
                </table>
                <?
                }
				else
					echo "<span class=\"vermelho\">Nenhum exame prescrito sem resultado!</span>";
				?>
        </fieldset>
	</div>
    <? } //fim ultimo enf ou méd ?>
    
    <? if ($tipo_consulta_prof=="o") { ?>
    <fieldset>
        <legend>Tratamentos ou procedimentos executados</legend>
        
        <input type="hidden" class="escondido" name="num_procedimentos" id="num_procedimentos" value="" />
        
        <div id="tratamentos_ok">
            <span class="vermelho">Nenhum tratamento executado até o momento!</span>
        </div>				
    </fieldset>
	
    <fieldset>
        <legend>Odontogramas</legend>
        
        <br />
        <ul class="abas">
            <li><a href="javascript:ajaxLink('odontogramas', 'carregaPaginaInterna&amp;pagina=_consultas/odontograma&amp;novo=1&amp;id_pessoa=<?=$rs_paciente->id_pessoa;?>');" onclick="return confirm('Tem certeza que deseja criar um novo odontograma para este paciente?\n\nO mesmo ficará permanentemente na ficha do paciente.');">Novo odontograma</a></li>
        </ul>
        
        <fieldset>
        	<legend>Odontogramas anteriores:</legend>
			<?
            $result_odontogramas= mysql_query("select *, DATE_FORMAT(data_odontograma, '%d/%m/%Y') as data_odo from odontogramas
                                                where id_pessoa = '$rs_paciente->id_pessoa'
                                                order by data_odontograma desc
                                                ");
            if (mysql_num_rows($result_odontogramas)==0)
                echo "<span class=\"vermelho\">Nenhum odontograma encontrado.</span>";
            else {
            ?>
            <p>Odontogramas encontrados:</p>
            <ul class="abas">
                <? while ($rso= mysql_fetch_object($result_odontogramas)) { ?>
                <li><a href="javascript:void(0);" onclick="ajaxLink('odontogramas', 'carregaPaginaInterna&amp;pagina=_consultas/odontograma&amp;id_odontograma=<?= $rso->id_odontograma;?>&amp;id_pessoa=<?=$rs_paciente->id_pessoa;?>');"><?= $rso->data_odo; ?></a></li>
                <? } ?>
            </ul>
            <? } ?>
        </fieldset>
        
        <div id="odontogramas">
			
        </div>
    </fieldset>
    <? } ?>
    
    <br /><br />
    
    <center>
    	<button onclick="return confirm('Tem certeza que deseja EDITAR esta consulta?');" type="submit">Confirmar &gt;&gt;</button>
    </center>
    
    <br /><br /><br />
    
	<script language="javascript" type="text/javascript">
		<?
		switch($tipo_consulta_prof) {
			case "m": $campo= "qp"; break;
			case "e": $campo= "s"; break;
			case "o": $campo= "anamnese"; break;
		}
		?>
		daFoco('<?=$campo;?>');
	</script>
    </form>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>