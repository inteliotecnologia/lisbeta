<?
if (pode("@", $_SESSION["permissao"])) {
	$result= mysql_query("select * from exames where id_exame = '$id_exame' ");
	$rs= mysql_fetch_object($result);
?>

<h2>Edição de exame</h2>

<form action="<?= AJAX_FORM; ?>formExameEditar" method="post" id="formExameEditar" name="formExameEditar" onsubmit="return ajaxForm('conteudo', 'formExameEditar');">
	<input name="id_exame" id="id_exame" type="hidden" class="escondido" value="<?= $rs->id_exame; ?>" />
	
	<label class="tamanho50">Cód:</label>
	<?= $rs->id_exame; ?>
	<br />
	
    <label class="tamanho50" for="tipo_exame">Tipo:</label>
	<select name="tipo_exame" id="tipo_exame">
	<?
	$vetor= pega_tipo_exame('l');
	
	$i=1;
	while ($vetor[$i]) {
	?>
	<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>" <? if ($i==$rs->tipo_exame) echo "selected=\"selected\""; ?>><?= $vetor[$i]; ?></option>
	<? $i++; } ?>
	</select>
	<br />
    
	<label class="tamanho50" for="exame">Exame:</label>
	<input name="exame" id="exame" value="<?= $rs->exame; ?>" />
	<br />
	
    <label class="tamanho50" for="apelidos">Apelidos:</label>
	<textarea name="apelidos" id="apelidos"><?= $rs->apelidos; ?></textarea>
	<br />
    
	<label class="tamanho50">&nbsp;</label>
	<button>Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco('exame');</script>

<? } ?>