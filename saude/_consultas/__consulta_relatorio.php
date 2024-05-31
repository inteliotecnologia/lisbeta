<? if (@pode("r", $_SESSION["permissao"])) { ?>

<h2 class="titulos">Relatórios de consultas</h2>

<div class="parte50">
	<fieldset>
		<legend>Busca de consultas realizadas</legend>

        <form action="<?= AJAX_FORM; ?>formConsultaBuscar" method="post" id="formConsultaBuscar" name="formConsultaBuscar" onsubmit="return ajaxForm('conteudo', 'formConsultaBuscar');">
        
			<label for="id_consulta">Cód. consulta:</label>
            <input name="id_consulta" id="id_consulta" />
            <br />
        
            <label for="nome">Nome:</label>
            <input name="nome" id="nome" />
            <br />
            
            <? if ($_SESSION["id_cidade_sessao"]!="") { ?>
            
            <label for="id_posto">Posto:</label>
            <select name="id_posto" id="id_posto">
                <option value="">-- TODOS --</option>
                <?
                $result_pos= mysql_query("select postos.id_posto, postos.posto from postos
                                            where postos.id_cidade = '". pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]) ."'
                                            ");
				$i=0;
                while ($rs_pos= mysql_fetch_object($result_pos)) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_pos->id_posto; ?>" <? if ($_POST["id_posto"] == $rs_pos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_pos->posto; ?></option>
                <? $i++; } ?>
            </select>	
        	<br />
            
            <? } ?>
            
            <br />
            
            <label>&nbsp;</label>
            <button type="submit">Buscar</button>
            <br />
        </form>
	</fieldset>
</div>
<div class="parte50">
	<fieldset>
		<legend>Boletim diário de atendimento</legend>

        <form action="<?= AJAX_FORM; ?>formBoletimDiario" method="post" id="formBoletimDiario" name="formBoletimDiario" onsubmit="return ajaxForm('conteudo', 'formBoletimDiario');">

			<label for="data">Data:</label>
            <input name="data" id="data" onfocus="displayCalendar(data, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" value="<?= date("d/m/Y"); ?>" maxlength="10" class="tamanho100" />
            <br />
            
            <label for="tipo_consulta_prof">Tipo:</label>
            <select name="tipo_consulta_prof" id="tipo_consulta_prof" onchange="alteraProfissional(this.value);">
            	<option value="">--- selecione ---</option>
                <option value="m">médica</option>
                <option value="e" class="cor_sim">enfermagem</option>
                <option value="o">odontológica</option>
            </select>
            <br />
            
            <label for="id_usuario">Profissional:</label>
            <div id="profissionais_atualiza">
                <select name="id_usuario" id="id_usuario" class="tamanho300">
                    <option value="">--- selecione ---</option>
                </select>
            </div>
            <br />
            
            <label>&nbsp;</label>
            <button type="submit">Buscar</button>
            <br />
        </form>
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>