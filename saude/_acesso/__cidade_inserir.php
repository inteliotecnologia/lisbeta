<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<h2>Cadastro de cidade</h2>

<form action="<?= AJAX_FORM; ?>formCidadeInserir" method="post" id="formCidadeInserir" name="formCidadeInserir" onsubmit="return ajaxForm('conteudo', 'formCidadeInserir');">

	<label for="id_uf">Estado:</label>
	<select name="id_uf" id="id_uf" onchange="retornaCidades();">
	  <option selected="selected">---</option>
	  <?
		$result_uf= mysql_query("select id_uf, uf from ufs order by uf");
		while ($rs_uf= mysql_fetch_object($result_uf)) {
	  ?>
	  <option value="<?= $rs_uf->id_uf; ?>" <? if ($rs_uf->id_uf == $_SESSION["id_uf_pref"]) echo "selected=\"selected\""; ?>><?= $rs_uf->uf; ?></option>
	  <? } ?>
	</select>
	<br />

	<label for="id_cidade">Cidade:</label>
	<div id="id_cidade_atualiza">
		<select name="id_cidade" id="id_cidade">
		  <option value="">--- selecione ---</option>
		  <?
			$result_cid= mysql_query("select id_cidade, cidade from cidades where id_uf = '". $_SESSION["id_uf_pref"] ."' order by cidade");
			while ($rs_cid= mysql_fetch_object($result_cid)) {
		  ?>
		  <option value="<?= $rs_cid->id_cidade; ?>" <? if ($rs_cid->id_cidade == $_SESSION["id_uf_pref"]) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade; ?></option>
		  <? } ?>
		</select>
	</div>
	<br />
    
    <label for="modo_cadastro_cpf">Cadastro CPF:</label>
		<select name="modo_cadastro_cpf" id="modo_cadastro_cpf">
		  <option value="" class="cor_sim">--- selecione ---</option>
		  <option value="1">Só com CPF</option>
          <option value="2" class="cor_sim">Sem documento</option>
		</select>
	<br />

    <label for="modo_farmacia">Modo farmácia:</label>
		<select name="modo_farmacia" id="modo_farmacia">
		  <option value="" class="cor_sim">--- selecione ---</option>
		  <option value="1">Só controlados</option>
          <option value="2" class="cor_sim">Todos</option>
		</select>
	<br />
    
    <label for="modo_almox">Modo almox:</label>
		<select name="modo_almox" id="modo_almox">
		  <option value="" class="cor_sim">--- selecione ---</option>
		  <option value="1">Não identificar</option>
          <option value="2" class="cor_sim">Identificar</option>
		</select>
	<br />

	<label>&nbsp;</label>
	<button>Inserir</button>
</form>
<? } ?>