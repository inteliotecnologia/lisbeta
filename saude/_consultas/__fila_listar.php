<? if (@pode("r", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Fila de espera para <?= pega_posto($_SESSION["id_posto_sessao"]); ?></h2>

<div id="tela_mensagens2">
<?
//if (verifica_fila($_SESSION["id_posto_sessao"]))
//	echo "<div class=\"atencao\">A fila de espera foi atualizada!</div>";

include("__tratamento_msgs.php");
?>
</div>

<!--
<div id="busca">
	<form action="<?= AJAX_FORM; ?>formPessoaBuscar" method="post" id="formPessoaBuscar" name="formPessoaBuscar" onsubmit="return ajaxForm('conteudo', 'formPessoaBuscar');">

		<label class="tamanho30" for="busca">Busca:</label>
		
		<input name="txt_busca" id="txt_busca" class="tamanho50" value="<?= $txt_busca; ?>" />

		<select name="lugar" id="lugar" class="tamanho80">
			<option value="todos" selected="selected">Todos abaixo</option>
			<option value="nome">Nome</option>
			<option value="cpf">CPF</option>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>-->

<div class="parte_total">
<?
$result= mysql_query("select pessoas.nome, filas.id_fila, filas.temperatura, filas.pressao1, filas.pressao2,
						DATE_FORMAT(filas.data_fila, '%d/%m/%Y %H:%i:%s') as data_fila
						from  pessoas, filas
						where filas.id_pessoa = pessoas.id_pessoa
						and   filas.atendido = '0'
						and   filas.id_posto = '". $_SESSION["id_posto_sessao"] ."'
						order by filas.id_fila asc
						");
if (mysql_num_rows($result) > 0) {
?>	
	<table cellspacing="0">
		<tr>
			<th width="10%">Cód.</th>
			<th width="40%" align="left">Nome</th>
			<th width="20%">Data/hora</th>
			<th width="10%">Temperatura</th>
			<th width="15%">PA</th>
            <th width="5%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
			
			if (@pode_algum("ecmi", $_SESSION["permissao"])) {
				$acao_onclick= "ajaxLink('conteudo', 'carregaPagina&amp;tipo_consulta_prof=m&amp;pagina=_consultas/consulta_inserir&amp;id_fila=". $rs->id_fila ."')";
				$acao_onmouseover= "Clique para selecionar esta pessoa para a consulta.";
			}
			else {
				$tip= "Pessoa com consulta agendada.";
				$acao_onclick= "alert('$tip');";
			}
		?>
		<tr>
			<td align="center"><?= $rs->id_fila; ?></td>
			<td class="maozinha" onclick="<?= $acao_onclick; ?>" onmouseover="Tip('<?= $acao_onmouseover; ?>');"><?= $rs->nome; ?></td>
			<td align="center"><?= $rs->data_fila; ?></td>
			<td align="center"><?= number_format($rs->temperatura, 1, ',', '.'); ?></td>
			<td align="center"><?= $rs->pressao1 ."x". $rs->pressao2 ?></td>
			<td>
                <a onclick="return confirm('Tem certeza que deseja retirar esta pessoa da fila?\n\nOPERAÇÃO IRREVERSÍVEL!');" href="javascript:ajaxLink('conteudo', 'filaExcluir&amp;id_fila=<?= $rs->id_fila; ?>');" class="link_excluir" title="Excluir" onmouseover="Tip('Clique para excluir este agendamento.');">excluir</a>
            </td>
		</tr>
		<? } ?>
	</table>
<?
}
else
	echo "<center><br />Ninguém na fila para ser atendido!</center>";
?>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>