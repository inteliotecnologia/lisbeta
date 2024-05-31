<?
if ($_SESSION["tipo_usuario_sessao"]=="a") {
	$sql= "select * from materiais ";
	
	if (isset($txt_busca)) {
		switch ($lugar) {
			case 'todos': $sql .= " where (id_material like '%". $txt_busca ."%'
									or    material like '%". $txt_busca ."%' ) ";
							break;
			case 'id_material': $sql .= " where id_material like '%". $txt_busca ."%' "; break;
			case 'material': $sql .= " where material like '%". $txt_busca ."%' "; break;
		}
	}
	$sql .= " order by material ";
	$result= mysql_query($sql) or die(mysql_error());
	
	$total_antes= mysql_num_rows($result);
	
	if (!isset($tudo)) {
		$num= 20;
		$total_linhas = mysql_num_rows($result);
		$num_paginas = ceil($total_linhas/$num);
		if (!isset($num_pagina))
			$num_pagina = 0;
		$inicio = $num_pagina*$num;
		
		$result= mysql_query($sql ." limit $inicio, $num") or die(mysql_error());
	}
?>

<div id="tela_mensagens">
<? include("__tratamento_msgs.php"); ?>
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formMaterialBuscar" method="post" id="formMaterialBuscar" name="formMaterialBuscar" onsubmit="return ajaxForm('conteudo', 'formMaterialBuscar');">
	
		<label class="tamanho30" for="busca">Busca:</label>
		
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $_POST["txt_busca"]; ?>" />
	
		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" selected="selected">Todos abaixo</option>
			<option value="id_material">Código</option>
			<option value="material">Material</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>

<h2 class="titulos">Materiais</h2>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_materiais/material_listar&amp;tudo');">listagem completa</a>

<div class="parte_esquerda">

	<p>Foram encontrados <strong><?= $total_antes; ?></strong> registro(s), mostrando <strong><?= $num; ?></strong> registros, de <strong><?= $inicio; ?></strong> até <strong><?= ($inicio+$num); ?></strong>. </p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th align="left" width="45%">Material</th>
			<th width="23%">Tipo</th>
			<th width="12%" align="left">
            	<div id="rel_acoes">Ações</div>
                <div id="rel_qtde">Quantidade</div>
            </th>
		</tr>
		<?
		$i= 0;
		$j= 0;
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_material; ?></td>
			<td>
			<?= $rs->material; ?>
			</td>
			<td align="center">
			<?= pega_tipo_material($rs->tipo_material); ?>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_materiais/material_editar&amp;id_material=<?= $rs->id_material; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir o remédio \'<?= $rs->material; ?>\' do sistema?');" href="javascript:ajaxLink('conteudo', 'materialExcluir&amp;id_material=<?= $rs->id_material; ?>');" class="link_excluir" title="Excluir">excluir</a>
			</td>
		</tr>
		<? $i++; } ?>
	</table>
    
<?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_materiais/material_listar&amp;txt_busca=". $txt_busca ."&amp;lugar=". $lugar ."&amp;num_pagina=";
			
			$texto_paginacao .= "<div id=\"paginacao\">
					<ul>";
			if ($num_pagina > 0) {
				$menos = $num_pagina - 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $menos ."')\">&laquo; Anterior</a></li>";
			}
	
			for ($i=0; $i<$num_paginas; $i++) {
				$link = $i + 1;
				if ($num_pagina==$i)
					$texto_paginacao .= "<li class=\"paginacao_atual\">". $link ."</li>";
				else
					$texto_paginacao .=  "<li><a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $i ."')\">". $link ."</a></li>";
			}
		
			if ($num_pagina < ($num_paginas - 1)) {
				$mais = $num_pagina + 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $mais ."')\">Pr&oacute;xima &raquo;</a></li>";
			}
			$texto_paginacao .=  "</ul>
				</div>";
	
			echo $texto_paginacao;
		}
	}
	?>
</div>

<div class="parte_direita2" id="div_direita">
	<?
	$pagina= "_materiais/material_inserir";
	include("index2.php");
	?>
</div>

<? } ?>