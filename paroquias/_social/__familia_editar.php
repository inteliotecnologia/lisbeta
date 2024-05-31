<?
if (@pode_algum("zl", $_SESSION["permissao"]) ) {
	if ($_SESSION["id_posto_sessao"]!="") $str_condicao= "and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
	else $str_condicao= "and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
	
	if ($_GET["id_familia"]!="") $id_familia= $_GET["id_familia"];
	if ($_POST["id_familia"]!="") $id_familia= $_POST["id_familia"];
	
	$result= mysql_query("select familias.*
							from familias, microareas, microareas_coord, postos
							where familias.id_familia = '". $id_familia ."'
							and   familias.id_microarea = microareas.id_microarea
							and   microareas.id_coord = microareas_coord.id_coordenacao
							and   microareas_coord.id_posto = postos.id_posto
							$str_condicao
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Edição de família</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<form action="<?= AJAX_FORM; ?>formFamiliaEditar" method="post" id="formFamiliaEditar" name="formFamiliaEditar" onsubmit="return ajaxForm('conteudo', 'formFamiliaEditar');">
	
    <input id="id_familia" name="id_familia" type="hidden" class="escondido" value="<?= $rs->id_familia; ?>" />
    
    <fieldset>
        <legend>Chefe da família</legend>
        
        <label>CPF:</label>
        <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario"/>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button">busca</button>
        <br/>
        
        <label>&nbsp;</label>
        <div id="cpf_usuario_atualiza">
        	<?
            $result_ch= mysql_query("select * from familias_pessoas
									where id_familia = '". $rs->id_familia ."'
									and   tipo = '1'
									");
			$rs_ch= mysql_fetch_object($result_ch);
			
			echo pega_nome($rs_ch->id_pessoa);
			?>
			<input id="id_pessoa_mesmo" class="escondido" type="hidden" value="<?= $rs_ch->id_pessoa; ?>" name="id_pessoa"/>
		</div>
        
    </fieldset>
    
    <fieldset>
        <legend>Endereço</legend>
        
        <div class="partei">
            <label>Posto:</label>
            <?= pega_posto($_SESSION["id_posto_sessao"]); ?>
            <input name="id_posto" id="id_posto" class="escondido" type="hidden" value="<?= $_SESSION["id_posto_sessao"]; ?>" />
            <br />
            
            <label for="num_familia">Núm. família:</label>
            <input id="num_familia" name="num_familia" value="<?= $rs->num_familia; ?>" />
            <br />
            
            <label>Quadra:</label>
            <?
			$retorno= "<select name=\"id_microarea\" id=\"id_microarea\">";
			$retorno.= "<option value=\"\" selected=\"selected\">--- selecione</option>";
			$result_coo= mysql_query("select * from microareas_coord
											where id_posto = '". $_SESSION["id_posto_sessao"] ."'
											order by coordenacao asc ");
			
			if (mysql_num_rows($result_coo)==0)
				echo "<span class=\"vermelho\">Nenhuma quadra encontrada!</span>
						<input type=\"hidden\" name=\"id_microarea\" id=\"id_microarea\" class=\"escondido\" value=\"\" />
						";
			else {
				$i= 0;
				while($rs_coo= mysql_fetch_object($result_coo)) {
					
					$retorno.= "<optgroup label=\"". $rs_coo->coordenacao ." - ". pega_nomes($rs_coo->id_pessoas) ."\">";
					
					$result_ma= mysql_query("select * from microareas
											where id_coord = '". $rs_coo->id_coordenacao ."'
											order by microarea asc ");
					
					while($rs_ma= mysql_fetch_object($result_ma)) {
						
						if ($rs_ma->id_microarea==$rs->id_microarea) $selected= "selected=\"selected\"";
						else $selected= "";
						
						if (($i%2)==0) $classe= "class=\"cor_sim\"";
						else $classe= "";
						
						$retorno.= "<option ". $classe ." ". $selected ." value=\"". $rs_ma->id_microarea ."\">". $rs_ma->microarea ." - ". pega_nomes($rs_ma->id_pessoas) ."</option>";
						
						$i++;
					}
					
					$retorno.= "</optgroup>";
				}
				$retorno.= "</select>";
				echo $retorno;
			}
			?>
            <br />
            
            <label for="id_religiao">Religião:</label>
            <select name="id_religiao" id="id_religiao">
                <?
                $vetor= pega_religiao('l');
                
                $i=1; $j=0;
                while ($vetor[$i]) {
                ?>
                <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($rs->id_religiao==$i) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
                <? $i++; $j++; } ?>
            </select>
            <br />
        </div>
        
        <div class="partei">
            <label for="endereco">Endereço:</label>
            <textarea name="endereco" id="endereco"><?= $rs->endereco; ?></textarea>
            <br />
        </div>
    </fieldset>
        
    <label>&nbsp;</label>
    <button id="botaoInserir" type="submit">Editar</button>
    <br /><br />

</form>

<script language="javascript" type="text/javascript">daFoco('id_posto');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>