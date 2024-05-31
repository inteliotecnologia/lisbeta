<?
if (($_SESSION["id_cidade_sessao"]!="") && (@pode("l", $_SESSION["permissao"])) ) {
	$result= mysql_query("select familias.*, DATE_FORMAT(ultimo_exame_prev, '%d/%m/%Y') as ultimo_exame_prev,
							microareas.*, microareas.id_pessoa as id_agente, postos.id_posto, postos.posto
							from familias, microareas, postos
							where familias.id_familia = '". $_GET["id_familia"] ."'
							and   familias.id_microarea = microareas.id_microarea
							and   microareas.id_posto = postos.id_posto
							and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							");
	$rs= mysql_fetch_object($result);
	
	$id_pessoa= pega_id_chefe_familia($rs->id_familia);
	$id_familia= $rs->id_familia;
	
	$result_pessoa= mysql_query("select * from pessoas
								where id_pessoa = '". $id_pessoa ."'
								limit 1
								");
	$rs_pessoa= mysql_fetch_object($result_pessoa);
	
	$result_pessoa_se= mysql_query("select * from pessoas_se
									where id_pessoa = '". $rs_pessoa->id_pessoa ."'
									limit 1
									");
	$rs_pessoa_se= mysql_fetch_object($result_pessoa_se);
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Levantamento s�cio-econ�mico</h2>
<br />

<div id="tela_cadastro">
</div>

<fieldset>
    <legend>Identifica��o do chefe da fam�lia</legend>
	
    <div class="partei">
        <label>C�digo:</label>
        <?= $rs->id_familia; ?>
        <br />
        
        <label>Agente:</label>
        <?= pega_nome($rs->id_agente); ?>
        <br />
        
        <label>Micro�rea:</label>
        <?= $rs->microarea; ?>
        <br />
        
        <label>PSF:</label>
        <?= $rs->posto; ?>
        <br />
        
        <label>Nome:</label>
        <?= pega_chefe_familia($rs->id_familia); ?>
        <br />
        
        <label>DN:</label>
        <?= desformata_data_hifen($rs_pessoa->data_nasc); ?> (<?= calcula_idade(desformata_data_hifen($rs_pessoa->data_nasc)); ?> ano(s))
        <br />
        
        <label>RG:</label>
        <?= $rs_pessoa->rg; ?>
        <br />
        
        <label>�rg�o emissor:</label>
        <?= $rs_pessoa->orgao_emissor_rg; ?>
        <br />
        
        <label>Nome da m�e:</label>
        <?= $rs_pessoa->nome_mae; ?>
        <br />
        
        <label>Nome do pai:</label>
        <?= $rs_pessoa->nome_pai; ?>
        <br />
        
        <label>Fone:</label>
        <?= $rs_pessoa->telefone; ?>
        <br />
        
        
    </div>
    <div class="partei">
        
        <label>Profiss�o:</label>
        <?= pega_profissao($rs_pessoa_se->id_profissao); ?>
        <br />
        
        <label>Renda:</label>
        <?= formata_valor($rs_pessoa_se->renda); ?>
        <br />
        
        <label>Local de trabalho:</label>
        <?= $rs_pessoa_se->local_trabalho; ?>
        <br />
        
        <label>Carteira assinada:</label>
        <?= sim_nao($rs_pessoa_se->ca); if ($rs_pessoa_se->ca=='d') echo "Tempo:". $rs_pessoa_se->desempregado_tempo; ?>
        <br />
        
        <label>Naturalidade:</label>
        <?= $rs_pessoa_se->cidade_nat; ?>
        <br />
        
        <label>Tempo munic�pio:</label>
        <?= $rs_pessoa_se->tempo_municipio; ?>
        <br />
        
        <label>Estado civil:</label>
        <?= pega_estado_civil($rs_pessoa_se->id_ec); ?>
        <br />
        
        <label>Grau de instru��o:</label>
        <?= pega_grau_instrucao($rs_pessoa_se->id_gi); ?>
        <br />
        
    </div>
</fieldset>


<?php
$result_cj= mysql_query("select * from familias_pessoas
							where id_familia = '". $rs->id_familia ."'
							and   parentesco = '1'
							");
$rs_cj= mysql_fetch_object($result_cj);
$linhas_cj= mysql_num_rows($result_cj);

$result_pessoa= mysql_query("select * from pessoas
								where id_pessoa = '". $rs_cj->id_pessoa ."'
								limit 1
								");
	$rs_pessoa= mysql_fetch_object($result_pessoa);
	
	$result_pessoa_se= mysql_query("select * from pessoas_se
									where id_pessoa = '". $rs_pessoa->id_pessoa ."'
									limit 1
									");
	$rs_pessoa_se= mysql_fetch_object($result_pessoa_se);
	
	if ($linhas_cj>0) {
?>


<fieldset>
    <legend>C�njuge</legend>
	
    <div class="partei">
        
        <label>Nome:</label>
        <?= $rs_pessoa->nome; ?>
        <br />
        
        <label>DN:</label>
        <?= desformata_data_hifen($rs_pessoa->data_nasc); ?> (<?= calcula_idade(desformata_data_hifen($rs_pessoa->data_nasc)); ?> ano(s))
        <br />
        
        <label>RG:</label>
        <?= $rs_pessoa->rg; ?>
        <br />
        
        <label>�rg�o emissor:</label>
        <?= $rs_pessoa->orgao_emissor_rg; ?>
        <br />
        
        <label>Nome da m�e:</label>
        <?= $rs_pessoa->nome_mae; ?>
        <br />
        
        <label>Nome do pai:</label>
        <?= $rs_pessoa->nome_pai; ?>
        <br />
        
        <label>Fone:</label>
        <?= $rs_pessoa->telefone; ?>
        <br />
        
        
    </div>
    <div class="partei">
        
        <label>Profiss�o:</label>
        <?= pega_profissao($rs_pessoa_se->id_profissao); ?>
        <br />
        
        <label>Renda:</label>
        <?= formata_valor($rs_pessoa_se->renda); ?>
        <br />
        
        <label>Local de trabalho:</label>
        <?= $rs_pessoa_se->local_trabalho; ?>
        <br />
        
        <label>Carteira assinada:</label>
        <?= sim_nao($rs_pessoa_se->ca); if ($rs_pessoa_se->ca=='d') echo "Tempo:". $rs_pessoa_se->desempregado_tempo; ?>
        <br />
        
        <label>Naturalidade:</label>
        <?= $rs_pessoa_se->cidade_nat; ?>
        <br />
        
        <label>Tempo munic�pio:</label>
        <?= $rs_pessoa_se->tempo_municipio; ?>
        <br />
        
        <label>Estado civil:</label>
        <?= pega_estado_civil($rs_pessoa_se->id_ec); ?>
        <br />
        
        <label>Grau de instru��o:</label>
        <?= pega_grau_instrucao($rs_pessoa_se->id_gi); ?>
        <br />
        
    </div>
</fieldset>

<? } // fim linhas_cj ?>
    
    <fieldset>
        <legend>Endere�o</legend>
        <div class="partei">
            <label>Endere�o:</label>
            <?= $rs->endereco; ?>
            <br />
        </div>
        
        <div class="partei">
            <label>Bairro:</label>
            <?= $rs->bairro; ?>
            <br />
            
            <label>Complemento:</label>
            <?= $rs->complemento; ?>
            <br />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Membros da fam�lia</legend>
        
        <?
        $retorno= "<table cellspacing=\"0\" cellpadding=\"2\">
					<tr>
					<th width=\"70%\" align=\"left\">Nome</th>
                    <th width=\"30%\">Parentesco</th>
                    </tr>";
		
		$result= mysql_query("select familias_pessoas.* from familias_pessoas, familias, microareas, postos
								where familias.id_familia = '". $rs->id_familia ."'
								and   familias.id_familia = familias_pessoas.id_familia
								and   familias.id_microarea = microareas.id_microarea
								and   microareas.id_posto = postos.id_posto
								and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
								") or die(mysql_error());
    	
		while ($rs= mysql_fetch_object($result)) {
		
			$retorno .= "<tr>
							<td>". pega_nome($rs->id_pessoa) ."</td>
							<td align=\"center\">". pega_parentesco($rs->parentesco) ."</td>
						</tr>
						";
		}
		
		$retorno .= "</table>";
		
		echo $retorno;
        ?>
        <br /><br />
    </fieldset>
    
    
    <fieldset>
        <legend>Renda</legend>
        
        <div class="partei">
            <label>Renda:</label>
            R$ <?= number_format($rs->renda, 2, ',', '.'); ?>
            <br />
        </div>
        <div class="partei">
            <label>Renda per capita:</label>
            R$ <?= number_format($rs->renda_percapita, 2, ',', '.'); ?>
            <br />
        </div>
        
    </fieldset>
    
    <fieldset>
        <legend>Caracter�sticas do domic�lio</legend>
    
        <div class="partei">
            <label>Situa��o habitacional:</label>
            <?= pega_situacao_habitacional($rs->id_situacaohab); ?>
            <br />
            
            <label>Valor:</label>
            R$ <?= number_format($rs->situacaohab_valor, 2, ',', '.'); ?>
            <br />
            
            <label>Num. c�modos:</label>
            <?= $rs->num_comodos; ?>
            <br />
        
            <label>Localiza��o:</label>
            <?= pega_localizacao_domicilio($rs->id_localizacao); ?>
            <br />
            
            <label>Destino do lixo:</label>
            <?= pega_destino_lixo($rs->id_destlixo); ?>
            <br />
            
            <label>Abastecimento de �gua:</label>
            <?= pega_abastecimento_agua($rs->id_abagua); ?>
            <br />
        
        </div>
        <div class="partei">
            <label>Escoamento sanit�rio:</label>
            <?= pega_escoamento_sanitario($rs->id_escsanitario); ?>
            <br />
    
            <label>Tratamento da �gua:</label>
            <?= pega_escoamento_sanitario($rs->id_tratagua); ?>
            <br />
            
            <label>Bens:</label>
				<?
				$result_bens= mysql_query("select id_bem from familias_bens where id_familia = '". $rs->id_familia ."' ");
				while ($rs_bem= mysql_fetch_object($result_bens)) {
					echo pega_bens($rs_bem->id_bem) ."; ";
				}
				?>
            <br />
            
            <label>Organiza��o e higiene:</label>
            <?= $rs->org_higiene; ?>
            <br />
            
            <label>Tipo de constru��o:</label>
            <?= pega_tipo_construcao($rs->tipo_construcao); ?>
            <br />
    
        </div>
        
    </fieldset>
    
    
    <fieldset>
        <legend>Aspectos de sa�de familiar</legend>
        
        <div class="partei">
            <label>Tipos de doen�as na fam�lia:</label>
            <?= $rs->doencas_familia; ?>
            <br />
            
            <label>Medicamentos utilizados:</label>
            <?= $rs->medicamentos_utilizados; ?>
            <br />
            
            <label>Valor mensal:</label>
            R$ <?= number_format($rs->valor_mensal_meds, 2, ',', '.'); ?>
            <br />
    
        </div>
        <div class="partei">
            <label>Vacina em dia:</label>
            <? if ($rs->vacina=="s") echo sim_nao(1); else echo sim_nao(0); ?>
            <br />
            
            <label>�ltimo exame preventivo:</label>
            <?= $rs->ultimo_exame_prev; ?>
            <br />
    
            <label>M�todo planejamento familiar:</label>
            <?= $rs->metodo_planejamento; ?>
            <br />
        </div>
        
    </fieldset>
    
    <fieldset>
        <legend>Inclus�o em programas de prote��o social</legend>
        
        <div class="partei">
            <label>Programas:</label>
				<?
				$result_prog= mysql_query("select id_programa from familias_programas where id_familia = '". $rs->id_familia ."' ");
				while ($rs_prog= mysql_fetch_object($result_prog)) {
					echo pega_programas_sociais($rs_prog->id_programa) ."; ";
				}
				?>
            <br />
    
        </div>
        <div class="partei">
            <label>Valor benef�cio recebido:</label>
            R$ <?= number_format($rs->valor_beneficio, 2, ',', '.'); ?>
            <br />
            
            <label>Necessidade priorit�ria:</label>
            <?= $rs->necessidade_prioritaria; ?>
            <br />
        </div>
        
    </fieldset>

    <fieldset>
        <legend>Confirma��o</legend>
        
        <div class="partei">
	        <p>Declaro para os devidos fins e efeitos legais, que as informa��es acima mencionadas s�o ver�dicas. Sendo esta a express�o da verdade firmo a presente.</p>
        </div>
        
		<div id="assinatura">
	    	<br /><br />
	    	<?= pega_chefe_familia($id_familia); ?>
	    	<br /><br />
		</div>        
    </fieldset>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>