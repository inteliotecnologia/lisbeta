<?
if (@pode_algum("zl", $_SESSION["permissao"]) ) {
	if ($_SESSION["id_posto_sessao"]!="") $str_condicao= "and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
	else $str_condicao= "and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
	
	if ($_GET["id_familia"]!="") $id_familia= $_GET["id_familia"];
	if ($_POST["id_familia"]!="") $id_familia= $_POST["id_familia"];
	
	$result= mysql_query("select *
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

<h2 class="titulos">Arrecadações de dízimo</h2>
<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<fieldset>
    <legend>Dados da família</legend>
	
    <div class="partei">
        <label>Código:</label>
        <?= $rs->id_familia; ?>
        <br />
        
        <label>Chefe:</label>
        <?= pega_chefe_familia($rs->id_familia); ?>
        <br />
    </div>
    <div class="partei">
        <label>Quadra:</label>
        <?= $rs->microarea; ?>
        <br />
        
        <label>Missionário(s):</label>
        <?= pega_nomes($rs->id_pessoas); ?>
        <br />
        
        <label>Posto:</label>
        <?= $rs->posto; ?>
        <br />
        
        
    </div>
    
</fieldset>

<fieldset>
    <legend>Arrecadações</legend>
    
    <input type="hidden" name="id_familia_geral" id="id_familia_geral" value="<?= $id_familia; ?>" class="escondido" />
    
    <label for="ano_inicial">Mostrar de:</label>
    <?
	$result_ano_inicial= mysql_query("select ano from arrecadacoes
									 	where id_posto = '". $_SESSION["id_posto_sessao"] ."'
										order by ano asc limit 1
										");
	$rs_ano_inicial= mysql_fetch_object($result_ano_inicial);
	
	if ($rs_ano_inicial->ano!="") $ano_inicial= $rs_ano_inicial->ano;
	else $ano_inicial= 2008;
	
	?>
    <select id="ano_inicial" name="ano_inicial">
    	<? for ($i= $ano_inicial; $i<$ano_inicial+4; $i++) { ?>
        <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?=$i;?>"><?=$i;?></option>
        <? } ?>
    </select>
    
    <div class="flutuar_esquerda espaco_dir">até</div>
    
    <select id="ano_final" name="ano_final">
    	<? for ($i= $ano_inicial+3; $i<$ano_inicial+7; $i++) { ?>
        <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?=$i;?>"><?=$i;?></option>
        <? } ?>
    </select>
    
    <button type="button" onclick="carregaPeriodoArrecadamento();">mostrar</button>
    
    <br /><br />
    
    <div id="anos_periodo">
    	<? require_once("__arrecadacao_form.php"); ?>
    </div>

    
</fieldset>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>