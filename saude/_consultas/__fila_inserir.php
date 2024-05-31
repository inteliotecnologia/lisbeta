<?
if (@pode("r", $_SESSION["permissao"])) {
	if ($id_pessoa=="")
		$result_pre= mysql_query("select pessoas.id_pessoa, filas.id_posto from filas, pessoas
									 where pessoas.cpf = '". $cpf ."'
									 and   filas.atendido = '0'
									 and   pessoas.id_pessoa = filas.id_pessoa ");
	else
		$result_pre= mysql_query("select pessoas.id_pessoa, filas.id_posto from filas, pessoas
									 where pessoas.id_pessoa = '". $id_pessoa ."'
									 and   filas.atendido = '0'
									 and   pessoas.id_pessoa = filas.id_pessoa ");
	$linhas= mysql_num_rows($result_pre);
	
	//com dependentes 
	//	Mostra tudo
	
	//sem dependentes 
	//	Faz teste
	$id_pessoa= $rs->id_pessoa;
	
	//0
	//esta na fila - entra 2
	
	//0
	//nao esta na fila - entra 1
	
	//2 
	//esta na fila
	
	//2
	//nao esta na fila
	
	
?>

<fieldset>
	<legend>Fila de espera para atendimento</legend>
	<?
    //if ( (pega_num_dependentes($rs->id_pessoa)>0) || ($linhas==0)) {
	if ($linhas==0) {
	?>
	<form action="<?= AJAX_FORM ?>formFilaInserir" method="post" name="formFilaInserir" id="formFilaInserir" onsubmit="return ajaxForm('conteudo', 'formFilaInserir');">
		
		<label>Nome:</label>
		<?
		$result_dependentes= mysql_query("select id_pessoa, nome, cpf from pessoas where id_responsavel = '$rs->id_pessoa' order by nome asc ");		
		
		if (mysql_num_rows($result_dependentes)==0) {
		?>
			<input type="hidden" name="id_pessoa_dep" id="id_pessoa_dep" value="<?= $rs->id_pessoa; ?>" class="escondido" />
			<input type="hidden" name="id_pessoa" id="id_pessoa_mesmo" value="<?= $rs->id_pessoa ?>" class="escondido" />
			<?= $rs->nome ." (". formata_cpf($rs->cpf) .")";
		} else {
			echo "
			<input type=\"hidden\" name=\"id_pessoa_dep\" id=\"id_pessoa_dep\" value=\"". $rs->id_pessoa ."\" class=\"escondido\" />
			<select name=\"id_pessoa\" id=\"id_pessoa_mesmo\" class=\"tamanhoAuto\" onchange=\"atualizaHistorico(1);\">";
			
			if (esta_na_fila($rs->id_pessoa))
				$opt_str= "disabled=\"disabled\"";
			else
				$opt_str= "";
				
			echo "<option ". $opt_str ." value=\"". $rs->id_pessoa ."\">". $rs->nome ." (". formata_cpf($rs->cpf) .")</option>";
			
			while ($rs_dependentes= mysql_fetch_object($result_dependentes)) {
				//verificar se algum dos dependentes jah esta na fila

				if (esta_na_fila($rs_dependentes->id_pessoa))
					$opt_str= "disabled=\"disabled\"";
				else
					$opt_str= "";
				
				echo "<option ". $opt_str ." value=\"". $rs_dependentes->id_pessoa ."\">". $rs_dependentes->nome ."</option>";
			}
			echo "</select>";
		} ?>
		<br />

		<label>&nbsp;</label>
		<a href="javascript:void(0);" onclick="cadastraDependente(1);" onmouseover="Tip('Clique para cadastrar um dependente para esta pessoa.<br/>Geralmente são filhos ou outros membros da família que não possuem CPF.');">cadastrar dependente</a> |
		<a href="javascript:void(0);" onclick="editaDadosPessoais(1);" onmouseover="Tip('Clique para editar os dados pessoais.');">editar dados</a> <br />
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
        
		<label for="temperatura">Temperatura:</label>
		<input name="temperatura" id="temperatura" onkeydown="formataValor(this,event);" class="tamanho40" maxlength="5" /> ºC
		<br />
		
		<label for="pressao1">PA:</label>
		<input name="pressao1" id="pressao1" class="tamanho40" maxlength="3" />
		<span class="flutuar_esquerda">x&nbsp;&nbsp;</span>
		<input name="pressao2" id="pressao2" class="tamanho40" maxlength="3" />
		<span class="flutuar_esquerda">mmHg</span>
		<br />
		
        <label for="temperatura">HGT:</label>
		<input name="hcg" id="hcg" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> mg/dl
		<br />
        
        <label for="peso">Peso:</label>
		<input name="peso" id="peso" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> kg
		<br />
        
        <label for="altura">Altura:</label>
		<input name="altura" id="altura" class="tamanho40" maxlength="6" onkeydown="formataValor(this,event);" /> m
		<br />
        
		<label>Tipo:</label>
		<input type="radio" name="tipo_consulta" id="tipo_consulta_c" class="tamanho20" checked="checked" value="c" /> <label for="tipo_consulta_c" class="tamanho50 nao_negrito alinhar_esquerda">Consulta</label>
		<input type="radio" name="tipo_consulta" id="tipo_consulta_r" class="tamanho20" value="r" /> <label for="tipo_consulta_r" class="tamanho50 nao_negrito alinhar_esquerda">Retorno</label>
		<br />
        
        <label>Residência:</label>
		<input type="radio" name="area_abran" id="area_abran_1" class="tamanho20" checked="checked" value="1" /> <label for="area_abran_1" class="tamanho160 nao_negrito alinhar_esquerda">Na área de abrangência</label>
		<input type="radio" name="area_abran" id="area_abran_0" class="tamanho20" value="0" /> <label for="area_abran_0" class="tamanho160 nao_negrito alinhar_esquerda">Fora da área de abrangência</label>
		<br />
		
		<label for="enviar">&nbsp;</label>
		<button type="submit">Adicionar a fila</button>
		<br /><br />
	</form>
	<? } else {
		$rs_pre= mysql_fetch_object($result_pre);
		echo $rs->nome ." (". formata_cpf($rs->cpf) .")";
	?>
	<br /><br />
	
	<span class="vermelho">Esta pessoa já esta na fila de espera em <?= pega_posto($rs_pre->id_posto) ?>!</span>
	
	<? } ?>
</fieldset>

<fieldset>
	<legend>Histórico de consultas</legend>
	<div id="historico_resumo">
	<?
	$tipo_hist= "v";
	$id_pessoa_hist= $rs->id_pessoa;
	
	$pagina= "_pessoas/historico_consultas_resumo";
	include("index2.php");
	?>
	</div>
</fieldset>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>