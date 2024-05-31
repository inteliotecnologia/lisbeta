<?
if (@pode_algum("ceim", $_SESSION["permissao"])) {
?>

<h2 class="titulos">Acompanhamentos em <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens">
<?
include("__tratamento_msgs.php");
?>
</div>

<div id="tela_aux_rapida" class="nao_mostra">
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formAcompBuscar" method="post" id="formAcompBuscar" name="formAcompBuscar" onsubmit="return ajaxForm('conteudo', 'formAcompBuscar');">
		
        <label for="periodo">Período:</label>
		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$result_per= mysql_query("select distinct(DATE_FORMAT(data_acompanhamento, '%m/%Y')) as data_acompanhamento from acompanhamento order by data_acompanhamento desc ");
			while ($rs_per= mysql_fetch_object($result_per)) {
			?>
			<option value="<?= $rs_per->data_acompanhamento; ?>" <? if ($_POST["periodo"]==$rs_per->data_acompanhamento) echo "selected=\"selected\""; ?>><?= $rs_per->data_acompanhamento; ?></option>
			<? } ?>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<div class="parte_total">
	<br /><br />
    
	<table cellspacing="0">
		<tr>
			<th width="5%">Cód.</th>
            <th width="40%" align="left">Nome</th>
            <th width="10%">Data</th>
			<th width="15%">Tipo</th>
            <th width="20%" align="left">EN</th>
            <th width="10%" align="left">Ações</th>
		</tr>
		<?
		if ($periodo=="") $periodo= date("m/Y");
		else $periodo= $_POST["periodo"];
		
		$result= mysql_query("select *, DATE_FORMAT(data_acompanhamento, '%d/%m/%Y') as data_acompanhamento from acompanhamento
								where id_posto = '". $_SESSION["id_posto_sessao"] ."'
								and   DATE_FORMAT(data_acompanhamento, '%m/%Y') = '$periodo'
								order by data_acompanhamento desc
								") or die(mysql_error());
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
        	<td align="center"><?= $rs->id_acompanhamento; ?></td>
			<td><?= pega_nome($rs->id_pessoa); ?></td>
            <td align="center"><?= $rs->data_acompanhamento; ?></td>
            <td align="center"><?= pega_tipo_acompanhamento($rs->tipo_acompanhamento); ?></td>
            <td align="left">
			<?
            switch($rs->tipo_acompanhamento) {
				case 'c': $en= pega_en_crianca($rs->estado_nutricional); break;
				case 'a': $en= pega_en_adolescente($rs->estado_nutricional); break;
				case 'g':
				case 'd':
					$en= pega_en_gestante_adulto($rs->estado_nutricional); break;
				case 'i': $en= pega_en_idoso($rs->estado_nutricional); break;
			}
			echo $en;
			?>
            </td>
            <td>
            	<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acomp/acomp_editar&amp;id_acompanhamento=<?= $rs->id_acompanhamento; ?>');" class="link_editar" title="Editar">editar</a>
                
                <a onclick="return confirm('Tem certeza que deseja excluir este acompanhamento?');" href="javascript:ajaxLink('conteudo', 'acompExcluir&amp;id_acompanhamento=<?= $rs->id_acompanhamento; ?>');" class="link_excluir" title="Excluir">excluir</a>
            </td>
		</tr>
		<? $i++; } ?>
	</table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>