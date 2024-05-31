<? if (@pode("t", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relatórios de solicitações p/ TFD</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formTfdBuscar" method="post" id="formTfdBuscar" name="formTfdBuscar" onsubmit="return ajaxForm('conteudo', 'formTfdBuscar');">
			
            <label for="id_motorista">Motorista:</label>
            <select name="id_motorista" id="id_motorista" class="tamanho200">
                <option value="">--- selecione ---</option>
                <?
                $result_mot= mysql_query("select pessoas.id_pessoa, pessoas.nome from pessoas, tfds_motoristas
                                                where pessoas.id_pessoa = tfds_motoristas.id_pessoa
                                                and   tfds_motoristas.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
                                                order by pessoas.nome asc
                                                ") or die(mysql_error());
                $i=0;
                while ($rs_mot= mysql_fetch_object($result_mot)) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_mot->id_pessoa; ?>"><?= $rs_mot->nome; ?></option>
                <? $i++; } ?>
            </select>	
            <br />
            
            <label for="id_veiculo">Veículo:</label>
            <select name="id_veiculo" id="id_veiculo" class="tamanho200">
                <option value="">--- selecione ---</option>
                <?
                $result_vei= mysql_query("select * from tfds_veiculos
                                                where tfds_veiculos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
                                                order by veiculo asc
                                                ") or die(mysql_error());
                $i=0;
                while ($rs_vei= mysql_fetch_object($result_vei)) {
                ?>
                <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_vei->id_veiculo; ?>"><?= $rs_vei->veiculo ." - ". $rs_vei->placa; ?></option>
                <? $i++; } ?>
            </select>	
            <br />
            
            <label for="id_cidade">Cidade/UF:</label>
            <div id="id_cidade_atualiza">
                <select name="id_cidade_tfd" id="id_cidade_tfd">
                  <option value="">--- selecione ---</option>
                  <?
                    $result_cid= mysql_query("select id_cidade, cidade, uf from cidades, ufs
                                                where cidades.tfd= '1'
                                                and   cidades.id_uf = ufs.id_uf
                                                order by cidade");
                    $i= 0;
                    while ($rs_cid= mysql_fetch_object($result_cid)) {
                  ?>
                  <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>"><?= $rs_cid->cidade ."/". $rs_cid->uf; ?></option>
                  <? $i++; } ?>
                </select>
            </div>
            <br /><br />
			
			<label>&nbsp;</label>
			<button>Buscar</button>
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