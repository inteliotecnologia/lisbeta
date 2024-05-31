<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
<?
$sql= "select pessoas.id_pessoa, pessoas.nome, usuarios.id_usuario, usuarios.usuario, usuarios.tipo_usuario, usuarios.situacao, usuarios.senha_sem_enc
		from  usuarios, pessoas
		where usuarios.id_pessoa = pessoas.id_pessoa ";

if (isset($txt_busca)) {
	switch ($lugar) {
		case 'todos': $sql .= " and
								(pessoas.nome like '%". $txt_busca ."%'
									or usuarios.usuario like '%". $txt_busca ."%' 
									or  pessoas.cpf like '%". $txt_busca ."%' )";
						break;
		case 'nome': $sql .= " and pessoas.nome like '%". $txt_busca ."%' "; break;
		case 'usuario': $sql .= " and usuarios.usuario like '%". $txt_busca ."%' "; break;
	}
}
$sql .= " order by usuarios.id_usuario desc";

$result= mysql_query($sql) or die(mysql_error());
?>
<div id="tela_mensagens2">
<?
if (isset($msg)) {
	if ($msg==1)
		echo "<div class=\"atencao2\">Não foi possível completar a operação!</div>";
	if ($msg==0)
		echo "<div class=\"atencao\">Operação realizada com sucesso!</div>";
	if ($msg==2)
		echo "<div class=\"atencao2\">Não foi possível cadastrar esta pessoa!</div>";
	if ($msg==3)
		echo "<div class=\"atencao\">Cadastro realizado com sucesso!</div>";
}
?>
</div>

<!--<div id="busca">
	<form action="<?= AJAX_FORM; ?>formUsuarioBuscar" method="post" id="formUsuarioBuscar" name="formUsuarioBuscar" onsubmit="return ajaxForm('conteudo', 'formUsuarioBuscar');">
		<label class="tamanho30" for="txt_busca">Busca:</label>
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $_POST["txt_busca"]; ?>" />
	
		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" selected="selected">Todos abaixo</option>
			<option value="nome">Nome</option>
			<option value="usuario">Usuário</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>-->

<h2 class="titulos">Usuários</h2>

<div class="parte_esquerda">
	
	<p>Foram encontrados <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="42%" align="left">Nome/Usuário</th>
			<th width="15%">Tipo</th>
			<th width="18%">Situação</th>
			<th width="13%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_usuario; ?></td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_ver&amp;id_pessoa=<?= $rs->id_pessoa; ?>')"><?= $rs->nome ." <span onmouseover=\"Tip('". $rs->senha_sem_enc ."');\">(". $rs->usuario .")</span>"; ?></a></td>
			<td align="center"><?= pega_tipo_usuario($rs->tipo_usuario); ?></td>
			<td align="center"><?= sim_nao($rs->situacao); ?></td>
			<td align="center">
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/usuario_editar&amp;id_usuario=<?= $rs->id_usuario; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja alterar o status do usuário \'<?= $rs->nome ." (". $rs->usuario .")"; ?>\'?');" href="javascript:ajaxLink('conteudo', 'usuarioExcluir&amp;id_usuario=<?= $rs->id_usuario; ?>&amp;situacao=<?= $rs->situacao; ?>');" class="link_trocar" title="Habilitar/Desabilitar">excluir</a>
			</td>
		</tr>
		<? } ?>
	</table>
</div>

<div class="parte_direita" id="div_direita">
	<?
	$pagina= "_acesso/usuario_inserir";
	include("index2.php");
	?>
</div>
<? } ?>