<? if (@pode("t", $_SESSION["permissao"])) {
	
	$sql= "select tfds_solicitacoes.*, pessoas.nome,
			DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%d/%m/%Y') as data_solicitacao,
			DATE_FORMAT(tfds_solicitacoes.data_solicitacao, '%Y') as ano_solicitacao
			
			from tfds_solicitacoes, pessoas, tfds_finalidades
			where tfds_solicitacoes.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
			and   tfds_solicitacoes.id_pessoa = pessoas.id_pessoa
			and   tfds_solicitacoes.id_finalidade = tfds_finalidades.id_finalidade
			 ";
	
	if ($id_interno!="") {
		$sql .= " and tfds_solicitacoes.id_interno = '$id_interno' ";
	}
	if ($protocolo!="") {
		$sql .= " and tfds_solicitacoes.protocolo = '". $protocolo ."' ";
	}
	if ($situacao_solicitacao!="") {
		$sql .= " and tfds_solicitacoes.situacao_solicitacao = '". $situacao_solicitacao ."' ";
	}
	if ($nome!="") {
		$sql .= " and pessoas.nome like '%". $nome ."%' ";
	}
	if ($id_cidade_tfd!="") {
		$sql .= " and tfds_solicitacoes.id_cidade_tfd = '". $id_cidade_tfd ."'";
	}
	if ($id_entidade!="") {
		$sql .= " and tfds_solicitacoes.id_entidade = '". $id_entidade ."'";
	}
	if ($tipo_ida!="") {
		$sql .= " and tfds_finalidades.tipo_ida = '". $tipo_ida ."'";
	}
	if ($id_finalidade!="") {
		$sql .= " and tfds_solicitacoes.id_finalidade = '". $id_finalidade ."'";
	}

	if (($inicio!="") && ($fim!="") ) {
		$data_inicio= desformata_data($inicio);
		$data_fim= desformata_data($fim);
		
		$data_fim= aumenta_dia($data_fim[0] ."-". $data_fim[1] ."-". $data_fim[2]);
		
		$sql .= " and tfds_solicitacoes.data_solicitacao between '". $data_inicio[2] ."-". $data_inicio[1] ."-". $data_inicio[0] ."'
					and '". $data_fim[2] ."-". $data_fim[1] ."-". $data_fim[0] ."' ";
	}

	$sql .= "order by tfds_solicitacoes.id_solicitacao desc ";
	
	$result= mysql_query($sql) or die(mysql_error());
	
	$total_antes= mysql_num_rows($result);
	
	if ($tudo!=1) {
		$num= 30;
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

<h2 class="titulos">Solicitações de TFD's cadastradas</h2>

<div class="parte_total">
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="5%">Cód.</th>
            <th width="10%">Cód. interno</th>
            <th width="5%">Protocolo</th>
            <th width="5%">Registro</th>
			<th width="20%">Nome</th>
            <th width="25%">Destino</th>
			<th width="10%">Data</th>
            <th width="20%">Situação</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="maozinha" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_tfd/tfd_solicitacao_ver&amp;id_solicitacao=<?= $rs->id_solicitacao; ?>')">
			<td align="center"><?= $rs->id_solicitacao; ?></td>
			<td align="center"><?= $rs->id_interno ."/". $rs->ano_solicitacao; ?></td>
            <td align="center"><?= sim_nao($rs->protocolo); ?></td>
			<td align="center">
			<?
            if ($rs->registro=="")
				echo "<span class=\"vermelho\">Não</span>";
			else
				echo $rs->registro;
			?>
            </td>
			<td align="center"><?= $rs->nome; ?></td>
			<td align="center"><?= pega_cidade($rs->id_cidade_tfd); ?></td>
			<td align="center"><?= $rs->data_solicitacao; ?></td>
            <td align="center"><?= pega_situacao_solicitacao_tfd($rs->situacao_solicitacao); ?></td>
		</tr>
		<? } ?>
	</table>
<?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_tfd/tfd_solicitacao_listar&amp;id_interno=". $id_interno ."&amp;nome=". $nome ."&amp;id_cidade=". $id_cidade ."&amp;id_entidade=". $id_entidade ."&amp;tipo_ida=". $tipo_ida ."&amp;id_finalidade=". $id_finalidade ."&amp;inicio=". $inicio ."&amp;fim=". $fim ."&amp;num_pagina=";
			
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