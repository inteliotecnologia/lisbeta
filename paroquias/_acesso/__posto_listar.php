<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<?
$result= mysql_query("select postos.* from postos, cidades
						where cidades.id_cidade = '$id_cidade'
						and   postos.id_cidade = cidades.id_cidade
						and   cidades.sistema = '1'
						") or die(mysql_error());
?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<!--<div id="busca">
	<form action="<?= AJAX_FORM; ?>formExameBuscar" method="post" id="formExameBuscar" name="formExameBuscar" onsubmit="return ajaxForm('conteudo', 'formExameBuscar');">
		<label class="tamanho30" for="txt_busca">Busca:</label>
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $_POST["txt_busca"]; ?>" />
	
		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" selected="selected">Todos abaixo</option>
			<option value="id_exame">Código</option>
			<option value="exame">Exame</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>
-->

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/cidade_listar');">&lt;&lt; voltar para cidades</a>

<?
$cidade= pega_cidade($id_cidade);
?>

<div class="parte_esquerda">
	<h2 class="titulos">Postos de <?= $cidade; ?></h2>

	<p>Foram encontrada(s) <strong><?= mysql_num_rows($result); ?></strong> cidade(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="50%" align="left">Posto</th>
			<th width="30%">Situação</th>
			<th width="10%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_posto; ?></td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/usuariop_listar&amp;id_posto=<?= $rs->id_posto; ?>')"><?= $rs->posto; ?></a></td>
	        </td>
			<td align="center"><?= sim_nao($rs->situacao); ?></td>
			<td>
				<a href="javascript:ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/posto_editar&amp;id_posto=<?= $rs->id_posto; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja ativar/desativar \n o posto \'<?= $rs->posto ." - ". $cidade; ?>\' do sistema?');" href="javascript:ajaxLink('conteudo', 'postoSituacao&amp;id_posto=<?= $rs->id_posto; ?>&amp;situacao=<?= $rs->situacao; ?>');" class="link_excluir" title="Excluir">excluir</a>			</td>
		</tr>
		<? } ?>
	</table>
<br /><br />
	
	<?
	$result= mysql_query("select pessoas.nome, usuarios.id_usuario, usuarios.usuario, usuarios.tipo_usuario
							from pessoas, usuarios, usuarios_cidades
							where usuarios.id_pessoa = pessoas.id_pessoa
							and   usuarios.id_usuario = usuarios_cidades.id_usuario
							and   usuarios_cidades.id_cidade = '$id_cidade'
							order by usuarios.tipo_usuario desc
							") or die(mysql_error());
	?>
	
	<h2 class="titulos">Usuários em <?= pega_cidade($id_cidade); ?></h2>
	
	<p>Aqui está a lista de pessoas que tem acesso ao conteúdo específico da cidade, para adicionar um usuário nesta cidade clique no botão vincular usuário no <em>submenu</em>.</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="45%" align="left">Nome/Usuário</th>
			<th width="33%" align="left">Tipo</th>
			<th width="12%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			if ($rs->dist==1)
				$dist= "<img alt=\"Pode entregar remédios\" src=\"images/ico_troca.gif\" />";
			else
				$dist= "";
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_usuario; ?></td>
			<td><?= $rs->nome ." <span onmouseover=\"Tip('". $rs->senha_sem_enc ."');\">(". $rs->usuario .")</span>"; ?></td>
			<td><?= pega_tipo_usuario($rs->tipo_usuario) . " ". $dist; ?></td>
			<td>
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/usuarioc_editar&amp;id_usuario=<?= $rs->id_usuario; ?>&amp;id_cidade=<?= $id_cidade; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir o usuário\n \'<?= $rs->nome ." (". $rs->usuario .")"; ?>\' de \'<?= pega_cidade($id_cidade); ?>\'?');" href="javascript:ajaxLink('conteudo', 'usuarioDaCidadeExcluir&amp;id_cidade=<?= $id_cidade; ?>&amp;id_usuario=<?= $rs->id_usuario; ?>');" class="link_excluir" title="Excluir">excluir</a>
			</td>
		</tr>
		<? } ?>
	</table>

</div>

<div class="parte_direita2">
	<div id="div_direita">
		<?
		$pagina= "_acesso/posto_inserir";
		include("index2.php");
		?>
	</div>
</div>
<? } ?>