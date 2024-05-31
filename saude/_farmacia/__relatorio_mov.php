<? if (@pode("f", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relatório de movimentação do almoxarifado</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formMovBuscar" method="post" id="formMovBuscar" name="formMovBuscar" onsubmit="return ajaxForm('conteudo', 'formMovBuscar');">

			<? if ($_SESSION["id_cidade_sessao"]!="") { ?>
			<label for="local">Local (origem):</label>
			<select name="local" id="local" class="tamanho200" onchange="verificaDestinoRel();" onmouseover="Tip('Selecione o posto de origem.');">
				<option value="">TODOS</option>
				<?
				$result_postos= mysql_query("select postos.* from postos, cidades
												where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
												and   postos.id_cidade = cidades.id_cidade
												and   cidades.sistema = '1'
												") or die(mysql_error());
				$i=0;
				while ($rs_postos= mysql_fetch_object($result_postos)) {
				?>
				<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
				<? $i++; } ?>
			</select>	
			<br />

			<label for="local_d">Local (destino):</label>
			<select name="local_d" id="local_d" class="tamanho200" onmouseover="Tip('Caso desejar um relatório de movimentação, selecione o posto de destino.');">
				<option value="">TODOS</option>
				<?
				$result_postos= mysql_query("select postos.* from postos, cidades
												where cidades.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
												and   postos.id_cidade = cidades.id_cidade
												and   cidades.sistema = '1'
												") or die(mysql_error());
				$i=0;
				while ($rs_postos= mysql_fetch_object($result_postos)) {
				?>
				<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($_POST["local"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
				<? $i++; } ?>
			</select>	
			<br />
			<? } ?>
			
			<label for="id_remedio">Remédio:</label>
			<select name="id_remedio" id="id_remedio" class="tamanho200" onmouseover="Tip('Caso desejar um relatório de um único medicamento, selecione-o aqui.');">
				<option selected="selected" value="">TODOS</option>
				<?
				$result_rem= mysql_query("select * from remedios order by remedio asc");
				$i=0;
				while ($rs_rem= mysql_fetch_object($result_rem)) {
				?>
				<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_rem->id_remedio; ?>"><?= $rs_rem->remedio ." (". pega_tipo_remedio($rs_rem->tipo_remedio) .")"; ?></option>
				<? $i++; } ?>
			</select>
			<br />
			
			<label for="tipo_trans">Refinar:</label>
            <? if ($_SESSION["id_cidade_sessao"]!="") { ?>
			<select name="tipo_trans" id="tipo_trans" class="tamanho200" onmouseover="Tip('Caso desejar refinar o tipo de operação, selecione-a aqui.');">
				<?
				$vetor= pega_tipo_transacao('l');
				
				$i=0; $j=0; $k=0;
				while ($vetor[$i][$j]) {
				?>
				<option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>" <? if ($tipo_trans == $vetor[$i][0]) echo "selected=\"selected\""; ?>><?= $vetor[$i][1]; ?></option>
				<? $i++; $k++; } ?>
			</select>
			<? } else { ?>
			<select name="tipo_trans" id="tipo_trans" class="tamanho200" onmouseover="Tip('Caso desejar refinar o tipo de operação, selecione-a aqui.');">
				<option class="cor_sim" value="todos">Todas as operações</option>
				<option class="cor_sim" value="s">Saída</option>
				<option value="m">Movimentação</option>
			</select>
			<? } ?>
            <br />
			
            <label for="id_interno">Modo:</label>
            <input type="radio" name="tudo" id="tudo_sim" class="tamanho30" value="1" onmouseover="Tip('Caso desejar exibir todos os resultados em uma única página (mais lento).');" /> <label class="label2" for="tudo_sim" onmouseover="Tip('Caso desejar exibir todos os resultados em uma única página (mais lento).');">Página única</label>
            <input type="radio" name="tudo" id="tudo_nao" class="tamanho30" value="0" checked="checked" onmouseover="Tip('Caso desejar exibir os resultados paginados (mais otimizado).');" /> <label class="label2" for="tudo_nao" onmouseover="Tip('Caso desejar exibir os resultados paginados (mais otimizado).');">Paginação (30 registros por página)</label>
            <br />
            
			<label for="tipo_trans">Período:</label>
			
			<span class="flutuar_esquerda">De&nbsp;</span>
			
			<input name="inicio" id="inicio" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["inicio"]; ?>" title="Data inicial" onmouseover="Tip('Caso desejar exibir o relatório entre um período de tempo,<br />insira a data inicial. Exemplo: 01/10/2007.');" />
			<span class="flutuar_esquerda">a&nbsp;</span>
			<input name="fim" id="fim" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["fim"]; ?>" title="Data final" onmouseover="Tip('Caso desejar exibir o relatório entre um período de tempo,<br />insira a data final. Exemplo: 01/11/2007.');" />
			<br />
			
   			<label for="observacoes">OBS:</label>
			<textarea name="observacoes" id="observacoes" onmouseover="Tip('Caso queira localizar uma observação feira em uma entrega. Digite parte dela neste campo.');"></textarea>
			<br /><br />
            
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>