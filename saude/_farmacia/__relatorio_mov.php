<? if (@pode("f", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relat�rio de movimenta��o do almoxarifado</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avan�ada</legend>
		
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
			<select name="local_d" id="local_d" class="tamanho200" onmouseover="Tip('Caso desejar um relat�rio de movimenta��o, selecione o posto de destino.');">
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
			
			<label for="id_remedio">Rem�dio:</label>
			<select name="id_remedio" id="id_remedio" class="tamanho200" onmouseover="Tip('Caso desejar um relat�rio de um �nico medicamento, selecione-o aqui.');">
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
			<select name="tipo_trans" id="tipo_trans" class="tamanho200" onmouseover="Tip('Caso desejar refinar o tipo de opera��o, selecione-a aqui.');">
				<?
				$vetor= pega_tipo_transacao('l');
				
				$i=0; $j=0; $k=0;
				while ($vetor[$i][$j]) {
				?>
				<option <? if (($k%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $vetor[$i][0]; ?>" <? if ($tipo_trans == $vetor[$i][0]) echo "selected=\"selected\""; ?>><?= $vetor[$i][1]; ?></option>
				<? $i++; $k++; } ?>
			</select>
			<? } else { ?>
			<select name="tipo_trans" id="tipo_trans" class="tamanho200" onmouseover="Tip('Caso desejar refinar o tipo de opera��o, selecione-a aqui.');">
				<option class="cor_sim" value="todos">Todas as opera��es</option>
				<option class="cor_sim" value="s">Sa�da</option>
				<option value="m">Movimenta��o</option>
			</select>
			<? } ?>
            <br />
			
            <label for="id_interno">Modo:</label>
            <input type="radio" name="tudo" id="tudo_sim" class="tamanho30" value="1" onmouseover="Tip('Caso desejar exibir todos os resultados em uma �nica p�gina (mais lento).');" /> <label class="label2" for="tudo_sim" onmouseover="Tip('Caso desejar exibir todos os resultados em uma �nica p�gina (mais lento).');">P�gina �nica</label>
            <input type="radio" name="tudo" id="tudo_nao" class="tamanho30" value="0" checked="checked" onmouseover="Tip('Caso desejar exibir os resultados paginados (mais otimizado).');" /> <label class="label2" for="tudo_nao" onmouseover="Tip('Caso desejar exibir os resultados paginados (mais otimizado).');">Pagina��o (30 registros por p�gina)</label>
            <br />
            
			<label for="tipo_trans">Per�odo:</label>
			
			<span class="flutuar_esquerda">De&nbsp;</span>
			
			<input name="inicio" id="inicio" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["inicio"]; ?>" title="Data inicial" onmouseover="Tip('Caso desejar exibir o relat�rio entre um per�odo de tempo,<br />insira a data inicial. Exemplo: 01/10/2007.');" />
			<span class="flutuar_esquerda">a&nbsp;</span>
			<input name="fim" id="fim" class="tamanho70" maxlength="10" onkeyup="formataData(this);" value="<?= $_POST["fim"]; ?>" title="Data final" onmouseover="Tip('Caso desejar exibir o relat�rio entre um per�odo de tempo,<br />insira a data final. Exemplo: 01/11/2007.');" />
			<br />
			
   			<label for="observacoes">OBS:</label>
			<textarea name="observacoes" id="observacoes" onmouseover="Tip('Caso queira localizar uma observa��o feira em uma entrega. Digite parte dela neste campo.');"></textarea>
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