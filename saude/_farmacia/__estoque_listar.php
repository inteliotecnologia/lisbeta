<?
if (@pode("f", $_SESSION["permissao"])) {
	if ($_SESSION["id_cidade_sessao"]!="") {
		// ************************** validade
		
		$result_val= mysql_query("select *,
									DATE_FORMAT(data_validade, '%d') as dia,
									DATE_FORMAT(data_validade, '%m') as mes,
									DATE_FORMAT(data_validade, '%Y') as ano,
									DATE_FORMAT(data_validade, '%d/%m/%Y') as data_validade2,
									DATE_FORMAT(data_trans, '%d/%m/%Y') as data_trans
									from almoxarifado_mov
									where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
									and   tipo_trans = 'e'
									and   data_validade <> ''
									order by data_validade asc
									");
		
		$hoje= date("Ymd");
		$var= "";
		$num_v= 0;
				
		while ($rs_val= mysql_fetch_object($result_val)) {
			//1 mes antes de vencer
			$data_validade= date("Ymd", mktime(0, 0, 0, $rs_val->mes, $rs_val->dia-30, $rs_val->ano));
			
			//1 dia a mais que a validade
			$data_aviso= date("Ymd", mktime(0, 0, 0, $rs_val->mes, $rs_val->dia+1, $rs_val->ano));
			
			if (($hoje >= $data_validade) && ($hoje < $data_aviso)) {
				$num_v++;
				$var .= "<li>
							<span class=\"texto_destaque\">". pega_remedio($rs_val->id_remedio) ."</span> <br />
							Lote <strong>".  $rs_val->lote ."</strong> cadastrado em <strong>". $rs_val->data_trans ."</strong> vence em <strong>". $rs_val->data_validade2 ."</strong>
						</li>";
			}
		}
		
		// **************************
		
		if (($_POST["local"]=="") || ($_POST["local"]=="0")) {
			$tit= pega_cidade($_SESSION["id_cidade_sessao"]);
			$sql= "select remedios.*, almoxarifado_atual.qtde_atual, almoxarifado_atual.tipo_apres from almoxarifado_atual, remedios
						where almoxarifado_atual.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
						and   almoxarifado_atual.id_remedio = remedios.id_remedio
						order by remedios.remedio, almoxarifado_atual.tipo_apres asc
						";

		}
		else {
			$tit= pega_posto($_POST["local"]);
			$sql= "select remedios.*, postos_estoque.qtde_atual, postos_estoque.tipo_apres from postos_estoque, remedios
						where postos_estoque.id_posto = '". $_POST["local"] ."'
						and   postos_estoque.id_remedio = remedios.id_remedio
						order by remedios.remedio, postos_estoque.tipo_apres asc
						";
		}

		$tabela= "almoxarifado";
		$campo= "id_cidade";
		$valor_campo= $_SESSION["id_cidade_sessao"];
	}
	else {
		$tit= pega_posto($_SESSION["id_posto_sessao"]);
		$sql= "select remedios.*, postos_estoque.qtde_atual, postos_estoque.tipo_apres from postos_estoque, remedios
					where postos_estoque.id_posto = '". $_SESSION["id_posto_sessao"] ."'
					and   postos_estoque.id_remedio = remedios.id_remedio
					order by remedios.remedio, postos_estoque.tipo_apres asc
					";

		$tabela= "postos";
		$campo= "id_posto";
		$valor_campo= $_SESSION["id_posto_sessao"];

	}
	
	$result= mysql_query($sql) or die(mysql_error());
	
	if ($num_v>0) {
?>

<div id="alerta_vencimentos" class="amarelo_transp">
	<h2 id="tit_alerta_vencimentos">Alerta de validade</h2>
	
	<a href="javascript:void(0);" onclick="fechaDiv('alerta_vencimentos');" class="fechar">x</a>
	<ul>
		<?= $var; ?>
	</ul>
</div>
<? } ?>

<div id="tela_aux_rapida" class="nao_mostra">

</div>

<div id="tela_mensagens">
<? include("__tratamento_msgs.php"); ?>
</div>

<? if ($_SESSION["id_cidade_sessao"]!="") { ?>
<div id="busca">
	<form action="<?= AJAX_FORM; ?>formEstoqueBuscar" method="post" id="formEstoqueBuscar" name="formEstoqueBuscar" onsubmit="return ajaxForm('conteudo', 'formEstoqueBuscar');">
		<label class="tamanho30" for="txt_busca">Local:</label>
	
		<select name="local" id="local" class="tamanho160">
			<option value="0">FARMÁCIA CENTRAL</option>
			<?
			$result_postos= mysql_query("select postos.* from postos, cidades
									where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
									and   postos.id_cidade = cidades.id_cidade
									and   cidades.sistema = '1'
									") or die(mysql_error());
			$i=0;
			while ($rs_postos= mysql_fetch_object($result_postos)) {
			?>
			<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
			<? $i++; } ?>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>
<? } ?>

<h2 class="titulos">Estoque da farmácia - <?= $tit; ?></h2>

<div class="parte_total">

	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="75%" align="left">Remédio</th>
			<th width="25%" align="right">Qtde (apresentação)</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			//if ($rs->qtde_atual!=0) {
				if ($rs->classificacao_remedio=="c")
					$antes= "<img src=\"images/preto.gif\" alt=\"\" />";
				else
					$antes= "";
				
			$result_min= mysql_query("select * from ". $tabela ."_minimo
										where $campo = $valor_campo
										and   id_remedio = '". $rs->id_remedio ."'
										");
			if (mysql_num_rows($result_min)==0)
				$min= "";
			else
				$min= "_azul";
			
			$rs_min= mysql_fetch_object($result_min);
		?>
		<tr class="corzinha">
			<td>

            <a href="javascript:void(0);" class="link_folder<?= $min; ?>" onclick="abreDivSo('tela_aux_rapida'); ajaxLink('tela_aux_rapida', 'carregaPaginaInterna&amp;pagina=_farmacia/estoque_minimo&amp;id_remedio=<?= $rs->id_remedio; ?>');" onmouseover="Tip('Clique para adicionar/atualizar o estoque mínimo deste item.<br />Caso o estoque mínimo seja atingido, um \'!\' aparecerá ao lado da quantidade.');">estoque mínimo</a>
            
            <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/extrato&amp;id_remedio=<?= $rs->id_remedio; ?>&amp;origem=_farmacia/estoque_listar');"onmouseover="Tip('Clique para ver um EXTRATO COMPLETO de todas as operações realizadas com este medicamento.');"><?= $antes ." ". $rs->remedio . " (". pega_tipo_remedio($rs->tipo_remedio) .")"; ?></a>
            
            </td>
			<td align="right">
				<?
				if ($rs_min->qtde_minima>=$rs->qtde_atual)
				echo "<img src=\"images/ico_atencao.gif\" alt=\"\" />&nbsp;";
				?>
				<?= number_format($rs->qtde_atual, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres); ?>
            </td>
		</tr>
		<? } //} ?>
  </table>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>