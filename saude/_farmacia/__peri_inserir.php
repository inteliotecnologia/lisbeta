<?
if (@pode("f", $_SESSION["permissao"])) {

	if (isset($_SESSION["id_posto_sessao"]))
		$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	if (isset($_SESSION["id_cidade_sessao"]))
		$id_cidade= $_SESSION["id_cidade_sessao"];

?>
	<fieldset>
    	<legend>Entrega de medicação</legend>
        
		<div id="legenda_dist">
			<fieldset>
				<legend>Legenda</legend>

				<ul>
					<li class="legenda_verde">Disponível</li>
					<li class="legenda_vermelho">Não disponível</li>
				</ul>
			</fieldset>
		</div>
		
        <br />
		<label>Nome:</label>
		<?= $rs->nome; ?>
		<br /><br /><br />
		
		<?
		//selecionar os remedios receitados desta consulta
		$result2= mysql_query("select * from pessoas_remedios, remedios
								where pessoas_remedios.id_pessoa = '". $rs->id_pessoa ."'
								and   pessoas_remedios.id_remedio = remedios.id_remedio
								") or die(mysql_error());
		
		if (mysql_num_rows($result2)==0)
			echo "<center><span class=\"vermelho\">Não existem remédios associados à esta pessoa!</span></center><br /><br />";
		else {
		?>
		
		<form id="formPeriInserir" name="formPeriInserir" method="post" action="<?= AJAX_FORM ?>formPeriInserir" onsubmit="return ajaxForm('conteudo', 'formPeriInserir');">
			<input name="id_pessoa" type="hidden" class="escondido" value="<?= $rs->id_pessoa; ?>" />
			
		<table cellspacing="0">
			<tr>
				<th align="left" width="40%">Remédio</th>
                <th align="left" width="20%">Qtde em estoque</th>
				<th align="left" width="20%">Qtde regular</th>
				<th align="left" width="20%">Qtde a ser entregue</th>
			</tr>
			<?
			$mostrar= 0;
			$i=0;
			$qtde_sol= 0;
			$tipo_apres= 'u';
			
			while ($rs2= mysql_fetch_object($result2)) {
				
				if (isset($_SESSION["id_posto_sessao"]))
					$qtde_atual= pega_qtde_atual_remedio('p', $_SESSION["id_posto_sessao"], $rs2->id_remedio, $tipo_apres);
				if (isset($_SESSION["id_cidade_sessao"]))
					$qtde_atual= pega_qtde_atual_remedio('c', $_SESSION["id_cidade_sessao"], $rs2->id_remedio, $tipo_apres);
			
			//se a qtde em estoque for maior ou igual a qtde receitada passa
				if ($qtde_atual>=$rs2->qtde)
					$classe= "verde_transp";
				else
					$classe= "vermelho_transp";
			?>
			<tr class="<?= $classe; ?>">
				<td><?= $rs2->remedio; ?></td>
                <td><?= $qtde_atual; ?> unid(s)</td>
				<td>
				<?= $rs2->qtde ." ". pega_apresentacao($tipo_apres); ?>
				</td>
				<td>
				<?
				if ($qtde_atual==0)
					echo "0 ";
				else {
					$mostrar= 1;
					$qtde_sol++;
					
					if ($qtde_atual<$rs2->qtde)
						$qtde_pegar= $qtde_atual;
					else
						$qtde_pegar= $rs2->qtde;
				?>
					<input type="hidden" name="id_remedio[]" class="escondido" value="<?= $rs2->id_remedio; ?>" />
					<input type="hidden" name="qtde_atual[]" id="qtde_atual_<?= $i; ?>" class="escondido" value="<?= $qtde_atual; ?>" />
                    <input type="hidden" name="qtde[]" id="qtde_<?= $i; ?>" class="escondido" value="<?= $rs2->qtde; ?>" />
					<input name="qtde_pego[]" id="qtde_pego_<?= $i; ?>" class="tamanho30" value="<?= $qtde_pegar; ?>" onmouseover="Tip('Coloque o número de unidades que serão entregues.');" />
					<input type="hidden" name="tipo_apres[]" class="escondido" value="<?= $tipo_apres; ?>" />
				<?  $i++;
				}
				echo pega_apresentacao($tipo_apres);
				?>
				</td>
			</tr>
			<? } ?>
		</table>
		
		<br />

		<!--<label>Observações:</label>
		<textarea name="observacoes"></textarea>
		<br /><br />-->
		
		<input name="qtde_sol" id="qtde_sol" type="hidden" class="escondido" value="<?= $qtde_sol; ?>" />
		
		<?
		if ($mostrar==1) { ?>
		<center><button onclick="return confirm('Só prossiga se tiver certeza dos dados corretos e da existência do remédio em estoque.\n Tem certeza que deseja continuar?');">Confirmar &gt;&gt;</button></center>
		<? } ?>
		</form>
        </fieldset>
		<? }
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>