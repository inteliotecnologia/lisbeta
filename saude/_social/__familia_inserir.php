<? if (($_SESSION["id_cidade_sessao"]!="") && (@pode("l", $_SESSION["permissao"])) ) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Inser��o de fam�lia - Dados s�cio-econ�micos</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formFamiliaInserir" method="post" id="formFamiliaInserir" name="formFamiliaInserir" onsubmit="return ajaxForm('conteudo', 'formFamiliaInserir');">

    <fieldset>
        <legend>Chefe da fam�lia</legend>
        
        <label>CPF:</label>
        <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario"/>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button">busca</button>
        <br/>
        
        <label>&nbsp;</label>
        <div id="cpf_usuario_atualiza">
			<input id="id_pessoa_mesmo" class="escondido" type="hidden" value="0" name="id_pessoa"/>
		</div>
        
    </fieldset>
    
    <fieldset>
        <legend>Endere�o</legend>
        
        <div class="partei">
            <label for="id_psf">PSF:</label>
            <select name="id_psf" id="id_psf" onchange="retornaMicroareas();">
                <option value="">--- selecione</option>
                <?
                $result_postos= mysql_query("select postos.id_posto, postos.posto from postos
                                                where postos.id_cidade = '". $_SESSION["id_cidade_pref"] ."'
                                                and   postos.situacao = '1'
                                                and   postos.psf= '1'
                                                order by postos.posto");
                $i= 0;
                while($rs_postos= mysql_fetch_object($result_postos)) {
                    if (($i%2)==0)
                        $classe= "class=\"cor_sim\"";
                    else
                        $classe= "";
                    
                    if ($rs_postos->id_posto==$_SESSION["id_posto_sessao"])
                        $selecionavel= " selected=\"selected\" ";
                    else
                        $selecionavel= "";
                    
                    echo "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
                    $i++;
                }
                ?>
            </select>
            
            <label>Micro�rea:</label>
            <div id="id_microarea_atualiza">
            	<span class="vermelho">Selecione o PSF antes.</span>
                <input type="hidden" name="id_microarea" id="id_microarea" class="escondido" value="" />
            </div>
            
            <br />
            
            <label for="endereco">Endere�o:</label>
            <textarea name="endereco" id="endereco"></textarea>
            <br />
        </div>
        
        <div class="partei">
            <label for="bairro">Bairro:</label>
            <input name="bairro" id="bairro" />
            <br />
            
            <label for="complemento">Complemento:</label>
            <input name="complemento" id="complemento" />
            <br />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Renda</legend>
        
        <div class="partei">
            <label for="renda">Renda:</label>
            <input name="renda" id="renda" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
            <br />
        </div>
        <div class="partei">
            <label for="renda_percapita">Renda per capita:</label>
            <input name="renda_percapita" id="renda_percapita" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
            <br />
        </div>
        
    </fieldset>
    
    <fieldset>
        <legend>Caracter�sticas do domic�lio</legend>
    
        <div class="partei">
            <label for="id_situacaohab">Situa��o habitacional:</label>
            <select name="id_situacaohab" id="id_situacaohab">
                <?
                $vetor= pega_situacao_habitacional('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            
            <div id="div_situacaohab">
            	<label for="situacaohab_valor">Valor:</label>
	            <input name="situacaohab_valor" id="situacaohab_valor" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
            </div>
            <br />
            
            <label for="num_comodos">Num. c�modos:</label>
            <input name="num_comodos" id="num_comodos" class="tamanho80" />
            <br />
        
            <label for="id_localizacao">Localiza��o:</label>
            <select name="id_localizacao" id="id_localizacao">
                <?
                $vetor= pega_localizacao_domicilio('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            
            <label for="id_destlixo">Destino do lixo:</label>
            <select name="id_destlixo" id="id_destlixo">
                <?
                $vetor= pega_destino_lixo('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            
            <label for="id_abagua">Abastecimento de �gua:</label>
            <select name="id_abagua" id="id_abagua">
                <?
                $vetor= pega_abastecimento_agua('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
        
        </div>
        <div class="partei">
            <label for="id_escsanitario">Escoamento sanit�rio:</label>
            <select name="id_escsanitario" id="id_escsanitario">
                <?
                $vetor= pega_escoamento_sanitario('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
    
            <label for="id_tratagua">Tratamento da �gua:</label>
            <select name="id_tratagua" id="id_tratagua">
                <?
                $vetor= pega_tratamento_agua('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
            
            <label>Bens:</label>
                <?
                $vetor= pega_bens('l');
                $i=1;
                while ($vetor[$i]) {
                ?>
                <input type="checkbox" class="tamanho20" name="id_bem[]" id="id_bem<?= $i; ?>" value="<?= $i; ?>" />
                <label class="label_check" for="id_bem<?= $i; ?>"><?= $vetor[$i]; ?></label>
                <? $i++; $j++; } ?>
            <br />
            
            <label for="bens_outros">Bens (outros):</label>
            <input name="bens_outros" id="bens_outros" class="tamanho80" value="<?= $rs->bens_outros; ?>" />
            <br />
            
            <label for="org_higiene">Organiza��o e higiene:</label>
            <textarea name="org_higiene" id="org_higiene"></textarea>
            <br />
            
            <label for="tipo_construcao">Tipo de constru��o:</label>
            <select name="tipo_construcao" id="tipo_construcao">
                <?
                $vetor= pega_tipo_construcao('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
    
        </div>
        
    </fieldset>
    
    
    <fieldset>
        <legend>Aspectos de sa�de familiar</legend>
        
        <div class="partei">
            <label for="doencas_familia">Tipos de doen�as na fam�lia:</label>
            <textarea name="doencas_familia" id="doencas_familia"></textarea>
            <br />
            
            <label for="medicamentos_utilizados">Medicamentos utilizados:</label>
            <textarea name="medicamentos_utilizados" id="medicamentos_utilizados"></textarea>
            <br />
            
            <label for="valor_mensal_meds">Valor mensal:</label>
            <input name="valor_mensal_meds" id="valor_mensal_meds" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
            <br />
            
            <label>Vacina em dia:</label>
            <input type="radio" name="vacina" id="vacina_s" value="s" class="tamanho20" /> <label class="label_check" for="vacina_s">Sim</label>
            <input type="radio" name="vacina" id="vacina_n" value="n" class="tamanho20" /> <label class="label_check" for="vacina_n">N�o</label>
            <br />
    
        </div>
        <div class="partei">
            
            
            <label for="ultimo_exame_prev">�ltimo exame preventivo:</label>
            <input name="ultimo_exame_prev" id="ultimo_exame_prev" class="tamanho70" maxlength="10" onkeyup="formataData(this);" />
            <br />
    
            <label for="metodo_planejamento">M�todo planejamento familiar:</label>
            <textarea name="metodo_planejamento" id="metodo_planejamento"></textarea>
            <br />
    
            <label for="n_cartao_sus">N. cart�o SUS:</label>
            <input name="n_cartao_sus" id="n_cartao_sus" class="tamanho80" value="<?= $rs->n_cartao_sus; ?>" />
            <br />
            
            <label for="n_cartao_familia">N. cart�o fam�lia:</label>
            <input name="n_cartao_familia" id="n_cartao_familia" class="tamanho80" value="<?= $rs->n_cartao_familia; ?>" />
            <br />
            
        </div>
        
    </fieldset>
    
    <fieldset>
        <legend>Inclus�o em programas de prote��o social</legend>
        
        <div class="partei">
            <label>Programas:</label> <br />
                <?
                $vetor= pega_programas_sociais('l');
                $i=1;
                while ($vetor[$i]) {
                ?>
                <input type="checkbox" class="tamanho20" name="id_programa[]" id="id_programa<?= $i; ?>" value="<?= $i; ?>" />
                <label class="label_check" for="id_programa<?= $i; ?>"><?= $vetor[$i]; ?></label>
                <? $i++; $j++; } ?>
            <br />
            
            <label for="programas_outros">Programas (outros):</label>
            <input name="programas_outros" id="programas_outros" class="tamanho80" value="<?= $rs->programas_outros; ?>" />
            <br />
            
        </div>
        <div class="partei">
            <label for="valor_beneficio">Valor benef�cio recebido:</label>
            <input name="valor_beneficio" id="valor_beneficio" onkeydown="formataValor(this,event);" class="tamanho100" /> (R$)
            <br />
            
            <label for="necessidade_prioritaria">Necessidade priorit�ria:</label>
            <textarea name="necessidade_prioritaria" id="necessidade_prioritaria"></textarea>
            <br />
        </div>
        
    </fieldset>
    
    <label>&nbsp;</label>
    <button id="botaoInserir" type="submit">Inserir</button>
    <br /><br />

</form>

<script language="javascript" type="text/javascript">daFoco('id_psf');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>