<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<?
if ($_SESSION["tipo_usuario_sessao"]=="a") {
	$sql= "select *, DATE_FORMAT(data_acesso, '%d/%m/%Y %H:%i:%s') as data_acesso
							from acessos order by id_acesso desc";
	
	$result= mysql_query($sql) or die(mysql_error());
	
	$total_antes= mysql_num_rows($result);
	
	if (!isset($tudo)) {
		$num= 100;
		$total_linhas = mysql_num_rows($result);
		$num_paginas = ceil($total_linhas/$num);
		if (!isset($num_pagina))
			$num_pagina = 0;
		$inicio = $num_pagina*$num;
		
		$result= mysql_query($sql ." limit $inicio, $num") or die(mysql_error());
	}
?>
<h2 class="titulos">Entradas/Saídas dos usuários</h2>

<p>Foram encontrados <strong><?= $total_antes; ?></strong> registro(s), mostrando <strong><?= $num; ?></strong> registros, de <strong><?= $inicio; ?></strong> até <strong><?= ($inicio+$num); ?></strong>. </p>

<table cellspacing="0">
	<tr>
		<th width="5%">Cód.</th>
		<th align="left" width="20%">Usuário</th>
		<th align="left" width="20%">Local</th>
		<th width="34%">IP</th>
		<th width="12%">Data/Hora</th>
		<th width="8%">Ação</th>
	</tr>
	<?
	while ($rs= mysql_fetch_object($result)) {
		if ($rs->tipo=="e") {
			$tipo= "entrada";
			$cor= "azul";
		}
		else {
			$tipo= "saída";
			$cor= "vermelho";
		}
		
		if ($rs->id_posto!="")
			$local= pega_posto($rs->id_posto);
		else {
			if ($rs->id_cidade!="")
				$local= pega_cidade($rs->id_cidade);
			else
				$local= "globalmente";
		}
		
	?>
	<tr class="corzinha <?= $cor; ?>">
		<td align="center"><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/logs&amp;id_acesso=<?= $rs->id_acesso; ?>');" onmouseover="Tip('Ver apenas ações deste acesso.');"><?= $rs->id_acesso; ?></a></td>
		<td>
			<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/logs&amp;id_usuario=<?= $rs->id_usuario; ?>');" onmouseover="Tip('Ver apenas ações deste usuário.');"><?= pega_nome_pelo_id_usuario($rs->id_usuario); ?></a>
        </td>
		<td><?= $local; ?></td>
		<td align="center">
		<?
        if ($rs->ip!="") {
			echo $rs->ip;
			if ($rs->ip!=$rs->ip_reverso)
				echo " (". $rs->ip_reverso .")";
		}
		else
			echo "anônimo";
		?>
        </td>
		<td align="center"><?= $rs->data_acesso ?></td>
		<td align="center"><?= $tipo; ?></td>
	</tr>
	<? } ?>
</table>
<br />
<?
if ($total_linhas>0) {
	if ($num_paginas > 1) {
		$texto_url= "carregaPagina&amp;pagina=_acesso/entradas&amp;num_pagina=";
		
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
<br /><br /><br /><br /><br />
<? } ?>
<? } ?>