<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<?

$result= mysql_query("select * from cidades, ufs
						where cidades.id_uf = ufs.id_uf
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

<div class="parte_esquerda">
	<h2 class="titulos">Cidades com sistema</h2>
	<p>Foram encontrada(s) <strong><?= mysql_num_rows($result); ?></strong> cidade(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th align="left" width="78%">Cidade</th>
			<th width="12%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_cidade; ?></td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/posto_listar&amp;id_cidade=<?= $rs->id_cidade; ?>')"><?= $rs->cidade ."/". $rs->uf; ?></a></td>
			<td>
				<a onclick="return confirm('Tem certeza que deseja desativar a cidade \'<?= $rs->cidade ."/". $rs->uf; ?>\' do sistema?\n\nOs dados serão mantidos!');" href="javascript:ajaxLink('conteudo', 'cidadeExcluir&amp;id_cidade=<?= $rs->id_cidade; ?>');" class="link_excluir" title="Excluir">excluir</a>
			</td>
		</tr>
		<? } ?>
	</table>
</div>

<div class="parte_direita2" id="div_direita">
	<?
	$pagina= "_acesso/cidade_inserir";
	include("index2.php");
	?>
</div>
<? } ?>