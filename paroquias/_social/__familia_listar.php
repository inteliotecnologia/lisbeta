<?
if (@pode_algum("zl", $_SESSION["permissao"])) {
	
	if ($_SESSION["id_posto_sessao"]!="") $str_condicao .= " and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
	else $str_condicao .= " and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
	
	if ($_POST["id_familia"]!="")
		$str_condicao .= " and   familias.id_familia = '". $_POST["id_familia"] ."' ";
		
	if ($_POST["num_familia"]!="")
		$str_condicao .= " and   familias.num_familia = '". $_POST["num_familia"] ."' ";
	
	if ( ($_POST["busca"]==1) && ($_POST["nome"]!="") )
		$sql= "select familias.*, postos.posto, microareas.*
				from  familias, microareas, postos, familias_pessoas, pessoas, microareas_coord
				where familias.id_microarea = microareas.id_microarea
				and   microareas.id_coord = microareas_coord.id_coordenacao
				and   microareas_coord.id_posto = postos.id_posto
				and   familias.id_familia = familias_pessoas.id_familia
				and   familias_pessoas.id_pessoa = pessoas.id_pessoa
				and   pessoas.nome like '%". $_POST["nome"] ."%'
				";
	else
		$sql= "select familias.*, postos.posto, microareas.*
				from  familias, microareas, postos, microareas_coord
				where familias.id_microarea = microareas.id_microarea
				and   microareas.id_coord = microareas_coord.id_coordenacao
				and   microareas_coord.id_posto = postos.id_posto
				
				$str_condicao
				 ";
		
	if ($id_microarea!="")
		$sql .= " and microareas.id_microarea = '$id_microarea' ";
	
	$sql .= " and familias.status_familia = '1'
			group by familias.id_familia
			order by familias.id_familia desc ";
	
	$result= mysql_query($sql) or die(mysql_error());
	
	$total_antes= mysql_num_rows($result);
	
	if ($tudo!=1) {
		$num= 40;
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
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s).</p>
	<br />
	
	<table width="100%" cellspacing="0">
		<tr>
			<th width="4%">Cód (novo).</th>
            <th width="5%" align="left">Cód (antigo)</th>
            <th width="19%" align="left">Chefe da família</th>
            <th width="9%">N&ordm; membros</th>
            <th width="7%">Quadra</th>
            <th width="19%" align="left">Missionário</th>
            <th width="18%" align="left">Opções</th>
	      <th width="7%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_familia; ?></td>
			<td><?= $rs->num_familia; ?></td>
			<td><?= pega_chefe_familia($rs->id_familia); ?></td>
			<td align="center"><?= pega_num_membros($rs->id_familia); ?></td>
			<td align="center"><?= $rs->microarea; ?></td>
			<td align="left"><?= pega_nomes($rs->id_pessoas); ?></td>
            <td>
                <? if (pode("r", $_SESSION["permissao"])) { ?>
                <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/arrecadacao&amp;id_familia=<?= $rs->id_familia; ?>');">arrecadação</a>
                |
				<? } ?>
                <a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/membros&amp;id_familia=<?= $rs->id_familia; ?>');">membros</a>
            </td>
		    <td valign="top">
            	<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_social/familia_editar&amp;id_familia=<?= $rs->id_familia; ?>');" class="link_editar">editar</a>
                
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
			/*if ($num_pagina > 0) {
				$menos = $num_pagina - 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $menos ."')\">&laquo; Anterior</a></li>";
			}*/
	
			for ($i=0; $i<$num_paginas; $i++) {
				$link = $i + 1;
				if ($num_pagina==$i)
					$texto_paginacao .= "<li class=\"paginacao_atual\">". $link ."</li>";
				else
					$texto_paginacao .=  "<li><a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $i ."')\">". $link ."</a></li>";
			}
			
			/*
			if ($num_pagina < ($num_paginas - 1)) {
				$mais = $num_pagina + 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $mais ."')\">Pr&oacute;xima &raquo;</a></li>";
			}
			*/
			
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