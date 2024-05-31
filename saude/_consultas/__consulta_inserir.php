<?
if (@pode_algum("oecmin", $_SESSION["permissao"])) {
	if (($_GET["tipo_consulta_prof"]=="o") && (pode_algum("on", $_SESSION["permissao"]))) {
		$titulo= " odontológica";
		$tipo_consulta_prof= "o";
	}
	else {
		if ( ($_GET["tipo_consulta_prof"]=="m") && (pode_algum("ci", $_SESSION["permissao"])) ) {
			$titulo= " médica";
			$tipo_consulta_prof= "m";
		}
		elseif ( ($_GET["tipo_consulta_prof"]=="e") && (pode_algum("em", $_SESSION["permissao"])) ) {
			$titulo= " de enfermagem";
			$tipo_consulta_prof= "e";
		}
		else die("Outro!");
	}
?>
<h2 class="titulos">Nova consulta <?= $titulo; ?></h2>

	<div id="tela_relatorio">
	</div>

	<div id="pre_atendimento" class="parte_esquerda_i"><? /* onsubmit="return ajaxForm('conteudo', 'formConsultaInserir');" */ ?>
		<fieldset>
			<legend>Pré-atendimento:</legend>
			
            <strong>
			<?
				if ($id_fila!="")
					$result_paciente= mysql_query("select filas.*, pessoas.nome,
													DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc,
													pessoas.cpf, pessoas.id_responsavel, pessoas.id_pessoa, pessoas.sexo
													from pessoas, filas
													where filas.id_pessoa = pessoas.id_pessoa
													and   filas.id_fila = '$id_fila'
													");
				else
					$result_paciente= mysql_query("select agenda_consultas.*, pessoas.nome,
													DATE_FORMAT(pessoas.data_nasc, '%d/%m/%Y') as data_nasc,
													pessoas.cpf, pessoas.id_responsavel, pessoas.id_pessoa, pessoas.sexo
													from pessoas, agenda_consultas
													where agenda_consultas.id_pessoa = pessoas.id_pessoa
													and   agenda_consultas.id_agenda = '$id_agenda'
													and   agenda_consultas.atendido = '0'
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
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_consultas/consulta_prontuario&amp;id_pessoa=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver o histórico de consultas.');">consultas</a> |
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_meds_completo&amp;id_pessoa_hist=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver os medicamentos entregues na farmácia para esta pessoa.');">remédios</a> |
            <a href="javascript:void(0);" onclick="abreDivSo('tela_relatorio'); ajaxLink('tela_relatorio', 'carregaPaginaInterna&amp;pagina=_pessoas/historico_acomp_completo&amp;id_pessoa_hist=<?= $rs_paciente->id_pessoa; ?>&amp;tipo_hist=v');" onmouseover="Tip('Clique para ver os acompanhamentos.');">acomp.</a>
			<? } ?>
			<br />
			
            <?
			$idade_meses= calcula_meses($rs_paciente->data_nasc);
			$idade_anos= calcula_idade($rs_paciente->data_nasc);
			
			$meses= $idade_meses;
			$idade= $idade_anos;
			
			$meses_adicionais= ($idade_anos%12);
			
			if ($idade_anos<7)
				echo $idade_anos ." anos e ". $meses_adicionais ." meses";
			else
				echo $idade_anos ." anos";
			
			//echo " (". $meses ." meses)";
			?>
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
            <input id="exame" name="exame" onkeyup="if (event.keyCode==13) exameCadastroOk();" />
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
                
    <form action="<?= AJAX_FORM; ?>formConsultaInserir" method="post" name="formConsultaInserir" id="formConsultaInserir" onsubmit="return ajaxForm('conteudo', 'formConsultaInserir');">
	
	<div class="parte_direita_i">
			
        <!-- Identificacao paciente -->
        <? if ($id_fila!="") { ?>
        <input type="hidden" class="escondido" id="id_fila" name="id_fila" value="<?= $rs_paciente->id_fila; ?>" />
        <? } ?>
        <? if ($id_agenda!="") { ?>
        <input type="hidden" class="escondido" id="id_agenda" name="id_agenda" value="<?= $rs_paciente->id_agenda; ?>" />
        <? } ?>
        
        <input type="hidden" class="escondido" id="id_pessoa" name="id_pessoa" value="<?= $rs_paciente->id_pessoa; ?>" />
        <input type="hidden" class="escondido" id="idade_paciente" name="idade_paciente" value="<?= ($idade+0); ?>" />
        <input type="hidden" class="escondido" id="area_abran" name="area_abran" value="<?= $rs_paciente->area_abran; ?>" />
        <input type="hidden" class="escondido" id="tipo_consulta" name="tipo_consulta" value="<?= $rs_paciente->tipo_consulta; ?>" />
        <input type="hidden" class="escondido" id="tipo_consulta_prof" name="tipo_consulta_prof" value="<?= $tipo_consulta_prof; ?>" />
        
        <input type="hidden" class="escondido" id="local_consulta" name="local_consulta" value="<?= $rs_paciente->local_consulta; ?>" />
        
        <? if ( ($tipo_consulta_prof=="e") || ($tipo_consulta_prof=="m") ) { ?>
        <fieldset>
        	<legend>Dados de acompanhamento</legend>    
            
            <?
			if ($idade<=6) $tipo_acompanhamento= "c";
			else {
				if (($idade>=7) && ($idade<20)) $tipo_acompanhamento= "a";
				else {
					if (($idade>=20) && ($idade<60)) $tipo_acompanhamento= "d";
					else $tipo_acompanhamento= "i";
				}
			}
			?>
            <input type="hidden" class="escondido" id="tipo_acompanhamento" name="tipo_acompanhamento" value="<?= $tipo_acompanhamento; ?>" />
            <input type="hidden" class="escondido" id="sexo" name="sexo" value="<?= $rs_paciente->sexo; ?>" />
            <input type="hidden" class="escondido" id="meses_paciente" name="meses_paciente" value="<?= ($meses+0); ?>" />
            
			<? if ($rs_paciente->sexo=="f") { ?>
            <div class="div_abas" id="aba_acomp">
                <ul class="abas">
                    <li id="aba_acomp_ng" class="atual"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_acomp', 'aba_acomp_ng'); fechaDiv('gestante'); atribuiValor('tipo_acompanhamento', '<?=$tipo_acompanhamento;?>');">N&atilde;o gestante</a></li>
                    <li id="aba_acomp_g"><a href="javascript:void(0);" onclick="atribuiAbaAtual('aba_acomp', 'aba_acomp_g'); abreDiv('gestante'); atribuiValor('tipo_acompanhamento', 'g');">Gestante</a></li>
                </ul>
            </div>

            <? } ?>
            
            <div class="parte50">
                <label for="temperatura">Temperatura:</label>
                <?
                if ($rs_paciente->temperatura!=0) $temperatura= number_format($rs_paciente->temperatura, 1, ',', '.');
                else $temperatura= "";
                ?>
                <input name="temperatura" id="temperatura" class="tamanho40" onkeydown="formataValor(this,event);" value="<?=$temperatura;?>" maxlength="5" /> ºC
                <br />
                
                <label for="pressao1">PA:</label>
                <?
                if (($rs_paciente->pressao1!=0) && ($rs_paciente->pressao2!=0)) {
                    $pressao1= $rs_paciente->pressao1;
					$pressao2= $rs_paciente->pressao2;
				}
                else {
                    $pressao1= "";
					$pressao2= "";
				}
                ?>
                <input name="pressao1" id="pressao1" class="tamanho40" value="<?=$pressao1;?>" maxlength="3" />
                <span class="flutuar_esquerda">x&nbsp;&nbsp;</span>
                <input name="pressao2" id="pressao2" class="tamanho40" value="<?=$pressao2;?>" maxlength="3" />
                <span class="flutuar_esquerda">mmHg</span>
                <br />
                
                <label for="hcg">HGT:</label>
                <?
                if ($rs_paciente->hcg!=0) $hgt= number_format($rs_paciente->hcg, 2, ',', '.');
                else $hgt="";
                ?>
                <input name="hcg" id="hcg" class="tamanho40" maxlength="6" value="<?= $hgt; ?>" onkeydown="formataValor(this,event);" /> mg/dl
                <br />
            </div>
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
                
                <? if (($peso!="") && ($altura!="") && ($tipo_atendimento!="g")) { ?>
                	<script language="javascript" type="text/javascript">
						geraEstadoNutricional();
					</script>
                <? } ?>
                
            </div>
            
            <div id="gestante" class="nao_mostra">
                <div class="parte50">
                    <label for="semana_gestacional">Semana gest.:</label>
                    <input name="semana_gestacional" id="semana_gestacional"  onblur="geraEstadoNutricional();" class="tamanho40" maxlength="5" />
                    <br />
                    
                    <label for="ultima_menstruacao">Última mens.:</label>
                    <input name="ultima_menstruacao" id="ultima_menstruacao" onfocus="displayCalendar(ultima_menstruacao, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" class="tamanho100" />
                    <br />
                    
                    <label for="peso_pregestacional">Peso pré-gest.:</label>
                    <input name="peso_pregestacional" id="peso_pregestacional" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> kg
                    <br />
                </div>
                <div class="parte50">
                    <label for="cintura">Cintura:</label>
                    <input name="cintura" id="cintura" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> cm
                    <br />
                    
                    <label for="quadril">Quadril:</label>
                    <input name="quadril" id="quadril" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> cm
                    <br />
                </div>
            </div>
            
            <br />
            <div id="estado_nutricional">Preencha os dados para gerar o estado nutricional do paciente.</div>
            
        </fieldset>
        <? } ?>
        
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
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pr->id_usuario; ?>"><?= $rs_pr->nome; ?></option>
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
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> <? if ($rs_paciente->id_tipo_atendimento==$i) echo "selected=\"selected\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
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
            <textarea name="anamnese" id="anamnese" class="grandao"></textarea>
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
            <textarea name="exame_clinico" id="exame_clinico" class="grandao"></textarea>
            <br />
            
            <label for="boletim_obs">Obs. boletim:</label>
            <textarea name="boletim_obs" id="boletim_obs" class="grandao"></textarea>
            <br />
            
        </fieldset>
        <?
        }
		if ($tipo_consulta_prof=="e") {
		?>
        <fieldset>
            <legend>SOAP:</legend>
        
            <label for="s">Subjetivo:</label>
            <textarea name="s" id="s" class="grandao"></textarea>
            <br />
            
            <label for="o">Objetivo:</label>
            <textarea name="o" id="o" class="grandao"></textarea>
            <br />
            
            <label for="a">Avaliação:</label>
            <textarea name="a" id="a" class="grandao"></textarea>
            <br />
            
            <label for="p">Prescrição:</label>
            <textarea name="p" id="p" class="grandao"></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Outras observações:</legend>
            
            <label for="obs">Observações:</label>
            <textarea name="obs" id="obs" class="grandao"></textarea>
            <br />
            
        </fieldset>
        <?
        }
        if ($tipo_consulta_prof=="o") {
		?>
        <fieldset>
            <legend>Anamnese:</legend>
        
            <label for="anamnese">Anamnese:</label>
            <textarea name="anamnese" id="anamnese" class="grandao"></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Exame de boca:</legend>
        
            <label for="exame_boca">Exame de boca:</label>
            <textarea name="exame_boca" id="exame_boca" class="grandao"></textarea>
            <br />
            
        </fieldset>
        
        <fieldset>
            <legend>Observações:</legend>
            
            <label for="obs">Observações:</label>
            <textarea name="obs" id="obs" class="grandao"></textarea>
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
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; } ?>
            </select>
            <br />
        </fieldset>
        
        <fieldset>
            <legend>Diagnóstico inicial:</legend>
            
            <label for="dias_atestado">Dias de atestado:</label>
            <input name="dias_atestado" id="dias_atestado" />
            <br />
            
            <input type="hidden" class="escondido" name="diagnostico_inicial" id="diagnostico_inicial" value="" />
            
            <label>CID:</label>
			<div id="diagnostico_ok">
                <span class="vermelho">Nada selecionado até o momento!</span>
            </div>				
        </fieldset>
        
        
        <? if ($tipo_consulta_prof=="m") { ?>
        <fieldset>
            <legend>Remédios receitados:</legend>
            
            <div id="receita_ok">
                <span class="vermelho">Nenhum remédio receitado até o momento!</span>
            </div>				
        </fieldset>
		<? } ?>
        
        <fieldset>
            <legend>Exames solicitados nesta consulta:</legend>
            
            <input type="hidden" class="escondido" name="num_exames" id="num_exames" value="" />
            
            <div id="exames_solicitacao_ok">
                <span class="vermelho">Nenhum exame solicitado até o momento!</span>
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
    	<button onclick="return confirm('Tem certeza que deseja finalizar esta consulta?\n\nTenha certeza de que os dados estão CORRETOS, já que esta é uma operação irreversível!');" type="submit">Confirmar &gt;&gt;</button>
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