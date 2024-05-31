<?
if (pode_algum("aprz12", $_SESSION["permissao"])) {
	
	if ($_GET["tipo_pessoa"]!="") $tipo_pessoa= $_GET["tipo_pessoa"];
	if ($_POST["tipo_pessoa"]!="") $tipo_pessoa= $_POST["tipo_pessoa"];
	
	if ($status_pessoa=="") {
		if ($_GET["status_pessoa"]!="") $status_pessoa= $_GET["status_pessoa"];
		if ($_POST["status_pessoa"]!="") $status_pessoa= $_POST["status_pessoa"];
	}
	
	if ($_GET["id_contrato"]!="") $id_contrato= $_GET["id_contrato"];
	if ($_POST["id_contrato"]!="") $id_contrato= $_POST["id_contrato"];
	
	if ($_GET["esquema"]!="") $esquema= $_GET["esquema"];
	if ($_POST["esquema"]!="") $esquema= $_POST["esquema"];
	
	if ( (pode("pi2", $_SESSION["permissao"])) && ($tipo_pessoa=="c") ) {
		$tit= " clientes";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
	}
	elseif ( (pode("i2", $_SESSION["permissao"])) && ($tipo_pessoa=="f") ) {
		$tit= " fornecedores";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
	}
	elseif ( (pode("i2", $_SESSION["permissao"])) && ($tipo_pessoa=="x") ) {
		$tit= " base de dados";
		$str= "and   pessoas.id_empresa = '". $_SESSION["id_empresa"] ."'";
	}
	elseif (pode("a2", $_SESSION["permissao"])) {
		$tit= " empresas com acesso ao sistema";
	}
	else $tit= "Empresas";
	
	//if ($status_pessoa=="") $status_pessoa= 1;
	
	if ($status_pessoa=="1") $tit_situacao= "ativos";
	elseif ($status_pessoa=="0") $tit_situacao= "inativos";
	else $tit_situacao= "todos";
	
	//só vindo da busca, edicao e insercao nao entram aqui
	if (isset($_POST["geral"])) {
		if ($_POST["tipo"]!="") $str .= " and  pessoas.tipo = '". $_POST["tipo"] ."' ";
		if ($_POST["cpf_cnpj"]!="") $str .= " and  pessoas.cpf_cnpj like '". $_POST["cpf_cnpj"] ."%' ";
		if ($_POST["nome_rz"]!="") $str .= " and  pessoas.nome_rz like '%". $_POST["nome_rz"] ."%' ";
		if ($_POST["id_empresa_atendente"]!="") $str .= " and  pessoas.id_empresa_atendente = '". $_POST["id_empresa_atendente"] ."' ";
	}
	
	if ($id_contrato!="") $str .= " and  pessoas.id_contrato = '". $id_contrato ."' ";
	if ($status_pessoa!="") $str .= " and   pessoas.status_pessoa = '". $status_pessoa ."' ";
	
	$result= mysql_query("select * from pessoas, pessoas_tipos, rh_enderecos
							where pessoas.id_pessoa = pessoas_tipos.id_pessoa
							and   pessoas_tipos.tipo_pessoa = '$tipo_pessoa'
							and   pessoas.id_pessoa = rh_enderecos.id_pessoa
							". $str ."
							order by pessoas.id_pessoa asc
							") or die(mysql_error());
	$linhas= mysql_num_rows($result);
	
?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2>Lista de <?= $tit; ?> (<?= $tit_situacao;?>) <? if ($id_contrato!="") echo "- ". pega_contrato($id_contrato); ?></h2>

<?
if ($tipo_pessoa=='x') {
	$nome1="pendentes";
	$nome2="atendidos";
} else { 
	$nome1="ativos";
	$nome2="inativos";
}
?>

<ul class="recuo1">
	<li class="flutuar_esquerda tamanho80"><a href="./?pagina=financeiro/pessoa&amp;acao=i&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=<?= $status_pessoa; ?>">inserir</a></li>
    <li class="flutuar_esquerda tamanho130 <? if ($status_pessoa=="1") echo "negrito"; ?>"><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=1">listar <?=$nome1;?></a></li>
    <li class="flutuar_esquerda tamanho120 <? if ($status_pessoa=="0") echo "negrito"; ?>"><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=0">listar <?=$nome2;?></a></li>
    <li class="flutuar_esquerda tamanho120 <? if ($status_pessoa=="") echo "negrito"; ?>"><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>">listar tudo</a></li>
    <!--<li class="flutuar_esquerda tamanho120"><a href="./?pagina=financeiro/pessoa_listar&amp;tipo_pessoa=<?=$tipo_pessoa;?>&amp;status_pessoa=3&amp;id_contrato=<?=$id_contrato;?>">listar em vista</a></li>-->
</ul>
<br /><br />

<p>Total de <b><?=$linhas;?></b> registros.</p>

<br />

<? if ($tipo_pessoa!='x') { ?>

<table cellspacing="0" width="100%" class="sortable" id="tabela">
	<tr>
		<th width="6%">Mat.</th>
        <? if ($tipo_pessoa=="c") { ?>
		<th width="6%" align="left">C&oacute;d.</th>
        <? } ?>
		<th width="11%" align="left">Tipo</th>
		<th width="28%" align="left">Raz&atilde;o Social</th>
		<th width="19%" align="left">Nome fantasia</th>
		<th width="17%"><? if ($status_pessoa==3) echo "Primeiro/último contato"; else echo "CPF/CNPJ"; ?></th>
		<th width="13%">Ações</th>
	</tr>
	<?
	$i=0;
	while ($rs= mysql_fetch_object($result)) {
		if (($i%2)==0) $classe= "odd";
		else $classe= "even";
		
		if ($rs->status_pessoa==1) $status= 0;
		else $status= 1;
	?>
	<tr class="<?= $classe; ?> corzinha">
		<td align="center" valign="top"><? if ($rs->tipo_pessoa=="a") echo pega_id_empresa_da_pessoa($rs->id_pessoa); else echo $rs->num_pessoa; ?></td>
		<? if ($tipo_pessoa=="c") { ?>
    		<td valign="top"><?= $rs->codigo; ?></td>
        <? } ?>
    	<td valign="top" class="menor">
			<?= pega_tipo($rs->tipo); ?>
            
            <? if ($tipo_pessoa=="c") echo "<br /><strong>". pega_cliente_tipo($rs->id_cliente_tipo) ."</strong>"; ?>
            
        </td>
		<td valign="top">
		<?
		if ($tipo_pessoa=='c') {
			echo "<a href=\"./?pagina=financeiro/cliente_esquema&amp;id_cliente=". $rs->id_pessoa ."&amp;tipo_pessoa=". $rs->tipo_pessoa ."\">". $rs->apelido_fantasia ."</a>";
			echo "<br /><span class=\"menor\">CONTRATO <strong>". pega_contrato($rs->id_contrato) ."</strong><span>";
		}
		else echo $rs->nome_rz;
		?>
</td>
	  <td valign="top"><?= $rs->apelido_fantasia; ?></td>
	  <td align="center" valign="top">
	  <?
      if ($status_pessoa==3) {
		  
			$result_historico1= mysql_query("select * from com_livro
											where id_empresa = '". $_SESSION["id_empresa"] ."'
											and   reclamacao_id_cliente = '". $rs->id_pessoa ."'
											order by data_livro asc, hora_livro asc
											limit 1
											") or die(mysql_error());
			$rs_historico1= mysql_fetch_object($result_historico1);
			
			$result_historico2= mysql_query("select * from com_livro
											where id_empresa = '". $_SESSION["id_empresa"] ."'
											and   reclamacao_id_cliente = '". $rs->id_pessoa ."'
											order by data_livro desc, hora_livro desc
											limit 1
											") or die(mysql_error());
			$rs_historico2= mysql_fetch_object($result_historico2);
		  
		  echo desformata_data($rs_historico1->data_livro) ." -> ". desformata_data($rs_historico2->data_livro);
	  }
	  else echo $rs->cpf_cnpj;
	  ?>
      </td>
		<td align="center" valign="top">
			<div class="acoes">
				<a href="./?pagina=financeiro/pessoa&amp;acao=e&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>">
					<img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
				<? if ($status_cliente!=3) { ?>
	            |
				<a href="javascript:ajaxLink('conteudo', 'pessoaStatus&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;status_pessoa=<?= $status; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>');">
					<img border="0" src="images/ico_<?= $status; ?>.png" alt="Status" /></a>
	            <? } ?>
		        |
		        <a href="javascript:ajaxLink('conteudo', 'pessoaExcluir&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>');" onclick="return confirm('Tem certeza que deseja excluir?');">
					<img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
			</div>
        </td>
	</tr>
	<? $i++; } ?>
</table>

<? } else { ?>

<table cellspacing="0" width="100%" class="sortable" id="tabela">
	<tr>
		<th width="5%" align="left">C&oacute;d.</th>
		<th width="22%" align="left">Nome</th>
		<th width="12%" align="left">Telefone</th>
		<th width="34%" align="left">Endereço</th>
		<th width="22%" align="left">OBS</th>
		<th width="13%">Ações</th>
	</tr>
	<?
	$i=0;
	while ($rs= mysql_fetch_object($result)) {
		if (($i%2)==0) $classe= "odd";
		else $classe= "even";
		
		if ($rs->status_pessoa==1) $status= 0;
		else $status= 1;
	?>
	<tr class="<?= $classe; ?> corzinha">
   		<td valign="top"><?= $rs->id_pessoa; ?></td>
    	<td valign="top">
			<?= $rs->nome_rz; ?>
        </td>
		<td valign="top">
		<?= $rs->telefone; ?></td>
	  <td valign="top"><?= $rs->rua ." / ". $rs->bairro ." / ". $rs->cep; ?></td>
	  <td valign="top"><?= $rs->obs_gerais; ?></td>
		<td align="center" valign="top">
			<div class="acoes">
				<a href="./?pagina=financeiro/pessoa&amp;acao=e&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>">
					<img border="0" src="images/ico_lapis.png" alt="Edita" /></a>
				<? if ($status_cliente!=3) { ?>
	            |
				<a href="javascript:ajaxLink('conteudo', 'pessoaStatus&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;status_pessoa=<?= $status; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>');" >
					<img border="0" src="images/ico_<?= $status; ?>.png" alt="Status" /></a>
	            <? } ?>
		        |
		        <a href="javascript:ajaxLink('conteudo', 'pessoaExcluir&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;tipo_pessoa=<?= $rs->tipo_pessoa; ?>');" onclick="return confirm('Tem certeza que deseja excluir?');">
					<img border="0" src="images/ico_lixeira.png" alt="Status" /></a>
			</div>
        </td>
	</tr>
	<? $i++; } ?>
</table>

<? } ?>

<? } ?>