<?
if (pode("@", $_SESSION["permissao"])) {
	$sql= "select * from exames ";
	if (isset($txt_busca)) {
		switch ($lugar) {
			case 'todos': $sql .= "
									where
									(id_exame like '%". $txt_busca ."%'
									or exame like '%". $txt_busca ."%'
									or apelidos like '%". $txt_busca ."%'
									)";
							break;
			case 'id_exame': $sql .= "where id_exame like '%". $txt_busca ."%' "; break;
			case 'tipo': $sql .= "where tipo_exame ='". $txt_busca ."' "; break;
			case 'exame': $sql .= "where (exame like '%". $txt_busca ."%' or apelidos like '%". $txt_busca ."%') "; break;
		}
	}
	$sql .= " order by exame";
	//echo $sql;
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
	<form action="<?= AJAX_FORM; ?>formExameBuscar" method="post" id="formExameBuscar" name="formExameBuscar" onsubmit="return ajaxForm('conteudo', 'formExameBuscar');">
		<label class="tamanho30" for="txt_busca">Busca:</label>
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $_POST["txt_busca"]; ?>" />
	
		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" <? if ($lugar=="todos") echo "selected=\"selected\""; ?>>Todos abaixo</option>
			<option class="cor_sim" value="id_exame" <? if ($lugar=="id_exame") echo "selected=\"selected\""; ?>>Código</option>
			<option value="exame" <? if ($lugar=="exame") echo "selected=\"selected\""; ?>>Exame</option>
            <option class="cor_sim" value="tipo" <? if ($lugar=="tipo") echo "selected=\"selected\""; ?>>Tipo</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>

<h2 class="titulos">Exames</h2>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_exames/exame_listar&amp;tudo');">listagem completa</a>

<div class="parte_esquerda">

	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s).</p>
	<br />
	
	<table width="100%" cellspacing="0">
<tr>
			<th width="10%">Cód.</th>
			<th width="30%" align="left">Exame</th>
	  <th width="18%" align="left">Tipo</th>
	  <th width="22%" align="left">Apelidos</th>
	  <th width="11%" align="left">Por</th>
	  <th width="9%" align="left">Ações</th>
	  </tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center" valign="top"><?= $rs->id_exame; ?></td>
			<td valign="top"><?= $rs->exame; ?></td>
			<td valign="top"><?= pega_tipo_exame($rs->tipo_exame); ?></td>
			<td valign="top"><?= $rs->apelidos; ?></td>
			<td valign="top">
			<?
			if ($rs->id_usuario!="") echo pega_usuario($rs->id_usuario);
			else echo "-";
			?>
            </td>
			<td valign="top">
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_exames/exame_editar&amp;id_exame=<?= $rs->id_exame; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir o exame \'<?= $rs->exame; ?>\' do sistema?');" href="javascript:ajaxLink('conteudo', 'exameExcluir&amp;id_exame=<?= $rs->id_exame; ?>');" class="link_excluir" title="Excluir">excluir</a>			</td>
		</tr>
		<? } ?>
	</table>
    
  <?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_exames/exame_listar&amp;txt_busca=". $txt_busca ."&amp;lugar=". $lugar ."&amp;num_pagina=";
			
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
	$pagina= "_exames/exame_inserir";
	include("index2.php");
	?>
</div>

<? } ?>