<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Cadastro de posto</h2>

<form action="<?= AJAX_FORM; ?>formPostoInserir" method="post" id="formPostoInserir" name="formPostoInserir" onsubmit="return ajaxForm('conteudo', 'formPostoInserir');">

	<label>Cidade:</label>
	<input type="hidden" name="id_cidade" id="id_cidade" value="<?= $id_cidade; ?>" class="escondido" />
	<?= pega_cidade($id_cidade); ?>
	<br />

	<label for="posto">Posto:</label>
	<input name="posto" id="posto" />
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
	<button>Inserir</button>
</form>
<script language="javascript" type="text/javascript">daFoco('posto');</script>
<? } ?>