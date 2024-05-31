<? if (pode("@", $_SESSION["permissao"])) { ?>

<h2>Cadastro de exame</h2>

<form action="<?= AJAX_FORM; ?>formExameInserir" method="post" id="formExameInserir" name="formExameInserir" onsubmit="return ajaxForm('conteudo', 'formExameInserir');">
	
    <label class="tamanho50" for="exame">Exame:</label>
	<input name="exame" id="exame" />
	<br />
	
    <label class="tamanho50" for="tipo_exame">Tipo:</label>
	<select name="tipo_exame" id="tipo_exame">
	<?
	$vetor= pega_tipo_exame('l');
	$i=1;
	while ($vetor[$i]) {
	?>
	<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
	<? $i++; } ?>
	</select>
	<br />
    
    <label class="tamanho50" for="apelidos">Apelidos:</label>
	<textarea name="apelidos" id="apelidos"><?= $rs->apelidos; ?></textarea>
	<br />
    
	<label class="tamanho50">&nbsp;</label>
	<button type="submit">Inserir</button>
    <br />
    
</form>
<script language="javascript" type="text/javascript">daFoco('exame');</script>

<? } ?>