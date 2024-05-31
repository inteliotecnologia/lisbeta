<?
if (pode("r", $_SESSION["permissao"])) {
	
	if ($_POST["acao"]=="1") {
		
		$i=0;
		
		while ($_POST["index"][$i]!="") {
			/*echo "update rh_funcionarios set local_equipe = '". $_POST["local_equipe"][$i] ."'
											where equipe = '". $_POST["equipe"][$i] ."'
											<br><br>
											";
			*/
											
			$result_atualiza= mysql_query("update rh_funcionarios set local_equipe = '". $_POST["local_equipe"][$i] ."'
											where equipe = '". $_POST["equipe"][$i] ."'
											") or die(mysql_error());
											
			$i++;
		}
		
	}
	
	$result= mysql_query("select DISTINCT(rh_funcionarios.equipe) as equipe
										from rh_funcionarios, pessoas, rh_carreiras, rh_enderecos
										WHERE rh_funcionarios.id_funcionario = rh_carreiras.id_funcionario
										AND   rh_carreiras.atual = '1'
										AND   pessoas.id_pessoa = rh_enderecos.id_pessoa
										AND   rh_funcionarios.id_pessoa = pessoas.id_pessoa
										AND   rh_funcionarios.status_funcionario <> '2'
										AND   rh_funcionarios.status_funcionario = '1'
										AND   rh_carreiras.id_departamento = '29'
										". $str ."
										order by rh_funcionarios.equipe asc
										") or die(mysql_error());
$linhas= mysql_num_rows($result);
?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2>Equipes</h2>

<ul class="recuo1">
	<li><a href="./?pagina=rh/funcionario_listar">cadastrar equipes</a></li>
</ul>

<p>Total de <b><?=$linhas;?></b> equipes.</p>

<br />

<form method="post" action="./?pagina=rh/equipe_listar">
	
	<input type="hidden" name="acao" value="1" />
	
	<table cellspacing="0" width="100%">
		<tr>
			<th width="25%" align="left">Equipe</th>
			<th width="75%" align="left">Local</th>
		</tr>
		<?
		$i=1;
		while ($rs= mysql_fetch_object($result)) {
			if (($i%2)==0) $classe= "cor_sim";
			else $classe= "cor_nao";
			
			if ($rs->status_departamento==1) $status= 0;
			else $status= 1;
			
			$result_local_equipe= mysql_query("select * from rh_funcionarios
												where equipe = '". $rs->equipe ."'
												and   local_equipe <> ''
												limit 1
												");
			$rs_local_equipe= mysql_fetch_object($result_local_equipe);
		?>
		<tr class="<?= $classe; ?> corzinha">
			<td valign="top" align="center"><?= $rs->equipe; ?></td>
			<td>
				
				<input type="hidden" name="index[]" id="index_<?=$i;?>" value="<?= $i;?>" />
				
				<input type="hidden" name="equipe[]" id="equipe_<?=$rs->equipe;?>" value="<?= $rs->equipe;?>" />
				
				<textarea name="local_equipe[]" id="local_equipe_<?=$i;?>"><?= $rs_local_equipe->local_equipe;?></textarea>
				
			</td>
		</tr>
		<? $i++; } ?>
	</table>
	<br />
	
	<center>
		<button type="submit">Atualizar</button>
	</center>
	
</form>
<? } ?>