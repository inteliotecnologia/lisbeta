<?
if (@pode("d", $_SESSION["permissao"])) {
	if ($periodo=="")
		$periodo= date("m/Y");
?>

<h2 class="titulos">Boletim de atos não médicos - <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens">
	<? include("__tratamento_msgs.php"); ?>
</div>

<div class="parte_total">
	<fieldset>
		<legend>Busca de consultas realizadas</legend>
        
        <form action="<?= AJAX_FORM; ?>formBoletimDiarioProc" method="post" id="formBoletimDiarioProc" name="formBoletimDiarioProc" onsubmit="return ajaxForm('conteudo', 'formBoletimDiarioProc');">
            
            <label for="id_procedimento">Procedimento:</label>
            <select name="id_procedimento" id="id_procedimento" class="tamanho300">
            <?
            $vetor= pega_procedimentos('l');
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
            
            <label for="periodo">Período:</label>
            <select name="periodo" id="periodo" class="tamanho80">
                <?
                $result_per= mysql_query("select distinct(DATE_FORMAT(data_procedimento, '%m/%Y')) as data_procedimento2 from procedimentos order by data_procedimento desc");
                while ($rs_per= mysql_fetch_object($result_per)) {
                ?>
                <option value="<?= $rs_per->data_procedimento2; ?>" <? if ($_POST["periodo"]==$periodo) echo "selected=\"selected\""; ?>><?= $rs_per->data_procedimento2; ?></option>
                <? } ?>
            </select>	
    		<br />
            
            <label>&nbsp;</label>
            <button type="submit">Buscar</button>
            <br /><br /><br />
        
        </form>
    </fieldset>
    <br /><br />
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>