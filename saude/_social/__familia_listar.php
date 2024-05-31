<?
if (@pode_algum("zl", $_SESSION["permissao"])) {
	
	if ($_SESSION["id_posto_sessao"]!="")
		$str_condicao= "and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
	else
		$str_condicao= "and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
	
	if ($nome!="")
		$sql= "select familias.*, postos.posto, microareas.*, microareas.id_pessoa as id_agente
				from  familias, microareas, postos, familias_pessoas, pessoas
				where familias.id_microarea = microareas.id_microarea
				and   microareas.id_posto = postos.id_posto
				
				and   familias.id_familia = familias_pessoas.id_familia
				and   familias_pessoas.id_pessoa = pessoas.id_pessoa
				and   pessoas.nome like '%". $nome ."%'
				";
	else
		$sql= "select familias.*, postos.posto, microareas.*, microareas.id_pessoa as id_agente
				from  familias, microareas, postos
				where familias.id_microarea = microareas.id_microarea
				and   microareas.id_posto = postos.id_posto
				$str_condicao
				 ";
		
	if ($id_psf!="")
		$sql .= " and postos.id_posto = '$id_psf' ";
	
	if ($id_microarea!="")
		$sql .= " and microareas.id_microarea = '$id_microarea' ";
	
	$sql .= " group by familias.id_familia
			order by familias.id_familia desc ";
	
	$result= mysql_query($sql) or die(mysql_error());
	
	$total_antes= mysql_num_rows($result);
	
	if ($tudo!=1) {
		$num= 50;
		$total_linhas = mysql_num_rows($result);
		$num_paginas = ceil($total_linhas/$num);
		if (!isset($num_pagina))
			$num_pagina = 0;
		$inicio = $num_pagina*$num;
		
		$result= mysql_query($sql ." limit $inicio, $num") or die(mysql_error());
	}
	//echo $sql;
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Famílias cadastradas</h2>

<div class="parte_total">
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table width="100%" cellspacing="0">
		<tr>
			<th width="4%">Cód.</th>
            <th width="5%" align="left">Num.</th>
            <th width="19%" align="left">Chefe da família</th>
            <th width="9%">N&ordm; membros</th>
            <th width="12%">PSF</th>
			<th width="7%">Microárea</th>
            <th width="19%" align="left">Agente</th>
            <th width="18%" align="left">Ações</th>
	      <th width="7%" align="left">Excluir</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_familia; ?></td>
			<td><?= $rs->num_familia; ?></td>
			<td><?= pega_chefe_familia($rs->id_familia); ?></td>
			<td align="center"><?= pega_num_membros($rs->id_familia); ?></td>
			<td align="center"><?= $rs->posto; ?></td>
			<td align="center"><?= $rs->microarea; ?></td>
			<td align="left"><?= pega_nome($rs->id_agente); ?></td>
            <td>
            	<? if ($_SESSION["id_cidade_sessao"]!="") { ?>
                1) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_editar&amp;id_familia=<?= $rs->id_familia; ?>');">dados sócio-econômicos</a> <br />
                2) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/membros&amp;id_familia=<?= $rs->id_familia; ?>');">membros da família</a> <br />
                3) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/parecer&amp;id_familia=<?= $rs->id_familia; ?>');">parecer técnico</a> <br />
                4) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/visitas&amp;id_familia=<?= $rs->id_familia; ?>');">visitas domiciliares</a> <br />
                5) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/assistencias&amp;id_familia=<?= $rs->id_familia; ?>');">assistências</a><br /><br />
                6) <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_resumo&amp;id_familia=<?= $rs->id_familia; ?>');">resumo</a>
                <? } else { ?>
                <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_editar&amp;id_familia=<?= $rs->id_familia; ?>');">editar</a>
                |
                <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/membros&amp;id_familia=<?= $rs->id_familia; ?>');">membros</a>
                <? } ?>            </td>
		    <td valign="top">
            	<a onclick="return confirm('Tem certeza que deseja excluir esta família?');" href="javascript:ajaxLink('conteudo', 'familiaExcluir&amp;id_familia=<?= $rs->id_familia; ?>');" class="link_excluir" title="Excluir">excluir</a>
            </td>
		</tr>
		<? } ?>
	</table>
<?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_social/familia_listar&amp;id_interno=". $id_interno ."&amp;nome=". $nome ."&amp;id_cidade=". $id_cidade ."&amp;id_entidade=". $id_entidade ."&amp;tipo_ida=". $tipo_ida ."&amp;id_finalidade=". $id_finalidade ."&amp;inicio=". $inicio ."&amp;fim=". $fim ."&amp;num_pagina=";
			
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
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>