<?
if (@pode_algum("oecmin", $_SESSION["permissao"])) {
	$sql= "select consultas.*,
			DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
			pessoas.id_pessoa, pessoas.nome, postos.posto
		
			from consultas, pessoas, postos
			where consultas.id_pessoa = pessoas.id_pessoa
			and   consultas.id_posto = postos.id_posto
			and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'
		 ";
//and   postos.id_cidade = '". pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]) ."'
/*
if (@pode("o", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'o' ";
}
if (@pode("e", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'e' ";
}	
if (@pode("c", $_SESSION["permissao"])) {
	$sql .= " and consultas.tipo_consulta_prof = 'c' ";
}
*/

//if ($_POST["id_consulta"]!="") {
//	$sql .= " and consultas.id_consulta = '". $_POST["id_consulta"] ."' ";
//}
if ($_POST["nome"]!="") {
	$sql .= " and pessoas.nome like '%". $nome ."%' ";
}
if ($id_posto!="") {
	$sql .= " and consultas.id_posto = '$id_posto' ";
}

$sql .= "order by consultas.id_consulta desc ";

//echo $sql;

$result= mysql_query($sql) or die(mysql_error());

$num= 50;
$total_linhas = mysql_num_rows($result);
$num_paginas = ceil($total_linhas/$num);
if (!isset($num_pagina))
	$num_pagina = 0;
$inicio = $num_pagina*$num;

$result= mysql_query($sql ." limit $inicio, $num") or die(mysql_error());

//echo $_SESSION["id_posto_sessao"] ." ";
//echo pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
?>


<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Consultas realizadas</h2>

<div class="parte_total">
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0" width="100%">
		<tr>
			<th width="6%">Cód.</th>
		  <th width="10%">Tipo</th>
		  <th width="17%">Data.</th>
		  <th width="27%" align="left">Nome</th>
			<th width="19%" align="left">Posto</th>
          <th width="13%" align="left">Documentos</th>
	      <th width="8%" align="left">A&ccedil;&otilde;es</th>
	  </tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_consulta; ?></td>
			<td align="center"><?= pega_tipo_consulta_prof($rs->tipo_consulta_prof); ?></td>
			<td align="center"><?= $rs->data_consulta; ?></td>
			<td class="maozinha" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_ver&amp;id_consulta=<?= $rs->id_consulta; ?>')"><?= $rs->nome; ?></td>
			<td><?= $rs->posto; ?></td>
            <td>
            	<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_esquema&amp;id_consulta=<?= $rs->id_consulta; ?>');">documentos</a>
            </td>
		    <td>
            <a onclick="return confirm('Tem certeza que deseja editar esta consulta?');" href="javascript:ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_editar&amp;id_consulta=<?= $rs->id_consulta; ?>');" class="link_editar" title="Editar" onmouseover="Tip('Clique para editar esta consulta.');">editar</a>
            <a onclick="return confirm('Tem certeza que deseja excluir esta consulta?\n\nOPERAÇÃO IRREVERSÍVEL!\n\nTODOS OS DADOS SERÃO PERDIDOS!');" href="javascript:ajaxLink('conteudo', 'consultaExcluir&amp;id_consulta=<?= $rs->id_consulta; ?>');" class="link_excluir" title="Excluir" onmouseover="Tip('Clique para excluir esta consulta.');">excluir</a>            </td>
		</tr>
		<? } ?>
	</table>
    
  <?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_consultas/consulta_listar&amp;num_pagina=";
			
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