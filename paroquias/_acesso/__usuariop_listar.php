<?
if (pode_algum("m", $_SESSION["permissao"])) {

if ($_GET["id_posto"]!="") $id_posto= $_GET["id_posto"];
else $id_posto= $_SESSION["id_posto_sessao"];

$result= mysql_query("select pessoas.nome, usuarios.id_usuario, usuarios.usuario, usuarios.tipo_usuario, usuarios_postos.id_cbo, usuarios.senha_sem_enc
						from pessoas, usuarios, usuarios_postos
						where usuarios.id_pessoa = pessoas.id_pessoa
						and   usuarios.id_usuario = usuarios_postos.id_usuario
						and   usuarios_postos.id_posto = '$id_posto'
						order by usuarios.tipo_usuario desc
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

<?
$id_cidade= pega_id_cidade_do_posto($id_posto)
?>

<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_acesso/posto_listar&amp;id_cidade=<?= $id_cidade; ?>');">&lt;&lt; voltar para <?= pega_cidade($id_cidade); ?></a>

<div class="parte_esquerda">
	
    <h2 class="titulos">Usuários em <?= pega_posto($id_posto); ?></h2>
    
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> usuários(s).</p>
	
	<!--<div id="legenda_dist">
		<ul class="recuo1">
			<li><img alt="Acesso à farmácia" src="images/ico_troca.gif" /> = Acesso à farmácia.</li>
			<li><img alt="Acesso à produção" src="images/ico_rel.gif" /> = Acesso à produção.</li>
		</ul>
	</div>-->
	
	<br /><br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="50%" align="left">Nome/Usuário</th>
			<th width="25%" align="left">Tipo</th>
			<th width="15%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			if ($rs->dist==1)
				$dist= "<img alt=\"Acesso à farmácia\" src=\"images/ico_troca.gif\" />";
			else
				$dist= "";
				
			if ($rs->prod==1)
				$prod= "<img alt=\"Acesso à produção\" src=\"images/ico_rel.gif\" />";
			else
				$prod= "";
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_usuario; ?></td>
			<td><?= $rs->nome ." <span onmouseover=\"Tip('". $rs->senha_sem_enc ."');\">(". $rs->usuario .")</span>"; ?></td>

			<td><?= pega_tipo_usuario($rs->tipo_usuario) ." ". $dist ." ". $prod; ?></td>
			<td>
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita', 'carregaPaginaInterna&amp;pagina=_acesso/usuariop_editar&amp;id_usuario=<?= $rs->id_usuario; ?>&amp;id_posto=<?= $id_posto; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir o usuário\n \'<?= $rs->nome ." (". $rs->usuario .")"; ?>\' do \'<?= pega_posto($id_posto); ?>\'?');" href="javascript:ajaxLink('conteudo', 'usuarioDoPostoExcluir&amp;id_posto=<?= $id_posto; ?>&amp;id_usuario=<?= $rs->id_usuario; ?>');" class="link_excluir" title="Excluir">excluir</a>			</td>
		</tr>
		<? } ?>
	</table>
</div>

<div class="parte_direita2" id="div_direita">
	<?
	$pagina= "_acesso/usuariop_inserir";
	include("index2.php");
	?>
</div>

<? } ?>

<div class="parte_esquerda">

<h2 class="titulos">Quadras/coordenadores</h2>

<?
$result_coo= mysql_query("select * from microareas_coord
									where id_posto = '". $id_posto ."'
									order by coordenacao asc ");

while($rs_coo= mysql_fetch_object($result_coo)) {
?>

<fieldset>
	<legend><?= $rs_coo->coordenacao ." - ". pega_nomes($rs_coo->id_pessoas); ?></legend>

	<?
    $result_ma= mysql_query("select * from microareas
									where id_coord = '". $rs_coo->id_coordenacao ."'
									order by microarea asc ");
    ?>
    
	<p><strong><?= mysql_num_rows($result_ma); ?></strong> quadra(s).</p>
    	
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="30%" align="left">Quadra</th>
			<th width="48%" align="left">Assistente responsável</th>
			<th width="12%" align="left">Ações</th>
		</tr>
		<?
		while ($rs_ma= mysql_fetch_object($result_ma)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs_ma->id_microarea; ?></td>
			<td><?= $rs_ma->microarea; ?></td>
			<td><?= pega_nomes($rs_ma->id_pessoas); ?></td>
			<td>
            	<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>
				<a href="javascript:void(0);" onclick="ajaxLink('div_direita2', 'carregaPaginaInterna&amp;pagina=_acesso/microarea_editar&amp;id_microarea=<?= $rs_ma->id_microarea; ?>&amp;id_posto=<?= $id_posto; ?>');" class="link_editar" title="Editar">editar</a>
				<a onclick="return confirm('Tem certeza que deseja excluir esta microárea de \'<?= pega_posto($id_posto); ?>\'?');" href="javascript:ajaxLink('conteudo', 'microareaExcluir&amp;id_microarea=<?= $rs_ma->id_microarea; ?>&amp;id_posto=<?= $id_posto; ?>');" class="link_excluir" title="Excluir">excluir</a>
                <? } ?>
			</td>
		</tr>
		<? } ?>
	</table>
</fieldset>
<? } //fim while 1 ?>
</div>

<? if ($_SESSION["tipo_usuario_sessao"]=="a") { ?>

<div class="parte_direita2" id="div_direita2">
	<?
	$pagina= "_acesso/microarea_inserir";
	include("index2.php");
	?>
</div>
<? } ?>

<? } //pode ?>
