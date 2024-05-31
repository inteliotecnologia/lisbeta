<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Edição de posto</h2>

<form action="<?= AJAX_FORM; ?>formPostoEditar" method="post" id="formPostoEditar" name="formPostoEditar" onsubmit="return ajaxForm('conteudo', 'formPostoEditar');">
	
	<?
	$result= mysql_query("select * from postos where id_posto = '$id_posto' ");
	$rs= mysql_fetch_object($result);
	?>
	
	<label>Cód:</label>
	<?= $rs->id_posto; ?>
	<input name="id_posto" id="id_posto" type="hidden" class="escondido" value="<?= $rs->id_posto; ?>" />
	<br />
	
	<label for="posto">Posto:</label>
	<input name="posto" id="posto" value="<?= $rs->posto; ?>" />
	<br />
	
    <label>&nbsp;</label>
	<input name="psf" id="psf" type="checkbox" value="1" class="tamanho20" <? if ($rs->psf=='1') echo "checked=\"checked\""; ?> />
	<label for="psf" class="alinhar_esquerda nao_negrito">PSF?</label>
	<br />
    
    <label for="tipo_agendamento">Tipo:</label>
	<select name="tipo_agendamento" id="tipo_agendamento">
    	<option value="1" <? if ($rs->tipo_agendamento=="1") echo "selected=\"selected\""; ?>>Agendamento</option>
        <option value="2" <? if ($rs->tipo_agendamento=="2") echo "selected=\"selected\""; ?> class="cor_sim">Pronto-atendimento</option>
        <option value="3" <? if ($rs->tipo_agendamento=="3") echo "selected=\"selected\""; ?>>Ambos</option>
    </select>
	<br />
    
	<label>&nbsp;</label>
	<button>Editar</button>
</form>
<script language="javascript" type="text/javascript">daFoco("posto");</script>
<? } ?>