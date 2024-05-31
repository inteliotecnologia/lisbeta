<?
$sql= "select * from apelidos where id_remedio = '$id_remedio' ";
if (isset($_POST["txt_busca"])) {
	switch ($_POST["lugar"]) {
		case 'todos': $sql .= " and (id_apelido like '%". $txt_busca ."%'
								or    apelido like '%". $txt_busca ."%' ) ";
						break;
		case 'id_apelido': $sql .= " and id_apelido like '%". $txt_busca ."%' "; break;
		case 'apelido': $sql .= " and apelido like '%". $txt_busca ."%' "; break;
	}
}
$sql .= " order by id_apelido desc";

$result= mysql_query($sql) or die(mysql_error());
?>
<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'remedioListar');">&lt;&lt; voltar</a>

<div id="tela_mensagens">
<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Apelidos para <?= pega_remedio($id_remedio); ?></h2>
	
<div id="busca">
	<form action="<?= AJAX_FORM; ?>formApelidoBuscar" method="post" id="formApelidoBuscar" name="formApelidoBuscar" onsubmit="return ajaxForm('conteudo', 'formApelidoBuscar');">
	
		<label class="tamanho30" for="busca">Busca:</label>
		
		<input name="id_remedio" id="id_remedio" type="hidden" class="escondido" value="<?= $id_remedio; ?>" />
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $_POST["txt_busca"]; ?>" />
	
		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" selected="selected">Todos abaixo</option>
			<option value="id_apelido">Código</option>
			<option value="apelido">Apelido</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>

<div class="parte_esquerda">
	
	<p>Foram encontrados <b><?= mysql_num_rows($result); ?></b> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="78%" align="left">Apelido</th>
			<th width="12%">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr>
			<td align="center"><?= $rs->id_apelido; ?></td>
			<td><?= $rs->apelido; ?></td>
			<td>
				<a href="javascript:ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_remedios/apelido_editar&amp;id_apelido=<?= $rs->id_apelido; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir o remédio \'<?= $rs->apelido; ?>\' do sistema?');" href="javascript:ajaxLink('conteudo', 'apelidoExcluir&amp;id_apelido=<?= $rs->id_apelido; ?>');" class="link_excluir" title="Excluir">excluir</a>
			</td>
		</tr>
		<? } ?>
	</table>
</div>

<div class="parte_direita2">
	<h2>Cadastro de apelido</h2>
	
	<div id="div_direita">
		<?
		$pagina= "_remedios/apelido_inserir";
		include("index2.php");
		?>
	</div>
</div>
