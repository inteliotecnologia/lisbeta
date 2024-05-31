<? if (@pode("f", $_SESSION["permissao"])) { ?>
<?
if ( (isset($_POST["txt_busca"])) or (isset($_GET["id_consulta"])) ) {
	if (isset($_SESSION["id_posto_sessao"])) {
		$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	}
	if (isset($_SESSION["id_cidade_sessao"])) {
		$id_cidade= $_SESSION["id_cidade_sessao"];
	}
	//else
	//	die();

	if ( ($_POST["lugar"]=="id_consulta") or (isset($_GET["id_consulta"])) ) {
		if ($_POST["lugar"]=="id_consulta")
			$id_consulta_in= $_POST["txt_busca"];
		else
			$id_consulta_in= $_GET["id_consulta"];
		
		$result = mysql_query(" select pessoas.id_pessoa, pessoas.nome, consultas.id_consulta, postos.id_posto, consultas.id_usuario as id_medico,
								DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y') as data_consulta from consultas, postos, pessoas
								where pessoas.id_pessoa = consultas.id_pessoa
								and   consultas.id_posto = postos.id_posto
								and   postos.id_cidade = '". $id_cidade ."'
								and   consultas.id_consulta = '". $id_consulta_in ."' ") or die(mysql_error());
		
		if (mysql_num_rows($result)==0) {
			echo "<p>Sua requisição não retornou resultados!</p>";
			die();
		}
		
		$rs= mysql_fetch_object($result);
		?>
		
		<div id="legenda_dist">
			<fieldset>
				<legend>Legenda</legend>

				<ul>
					<li class="legenda_verde">Disponível</li>
					<li class="legenda_vermelho">Não disponível</li>
					<li class="legenda_amarelo">Já entregue</li>
				</ul>
			</fieldset>
		</div>
		
		<label>Cód.:</label>
		<?= $rs->id_consulta; ?>
		<br />
		
		<label>Data:</label>
		<?= $rs->data_consulta; ?>
		<br />
		
		<label>Posto:</label>
		<?= pega_posto($rs->id_posto); ?>
		<br />
		
		<label>Médico:</label>
		<?= pega_nome_pelo_id_usuario($rs->id_medico); ?>
		<br />
		
		<label>Paciente:</label>
		<?= $rs->nome; ?>
		<br />
		
		<?
		//selecionar os remedios receitados desta consulta
		$result2= mysql_query("select consultas_remedios.*, remedios.remedio from consultas_remedios, remedios
								where consultas_remedios.id_consulta = '$rs->id_consulta'
								and   consultas_remedios.id_remedio = remedios.id_remedio
								") or die(mysql_error());
		
		if (mysql_num_rows($result2)==0)
			echo "<br /><br />Sem remédios receitados!";
		else { ?>
		
		<form id="formDistInserir" name="formDistInserir" method="post" action="<?= AJAX_FORM ?>formDistInserir" onsubmit="return ajaxForm('conteudo', 'formDistInserir');">
			<input name="id_consulta" type="hidden" class="escondido" value="<?= $rs->id_consulta; ?>" />
			<input name="id_pessoa" type="hidden" class="escondido" value="<?= $rs->id_pessoa; ?>" />
			
		<table cellspacing="0">
			<tr>
				<th align="left" width="40%">Remédio</th>
                <th align="left" width="20%">Qtde em estoque</th>
				<th align="left" width="20%">Qtde receitada</th>
				<th align="left" width="20%">Qtde entregue</th>
			</tr>
			<?
			$mostrar= 0;
			$i=0;
			$qtde_sol= 0;
			while ($rs2= mysql_fetch_object($result2)) {
				if (isset($_SESSION["id_posto_sessao"]))
					$qtde_atual= pega_qtde_atual_remedio('p', $_SESSION["id_posto_sessao"], $rs2->id_remedio, $rs2->tipo_apres);
				if (isset($_SESSION["id_cidade_sessao"]))
					$qtde_atual= pega_qtde_atual_remedio('c', $_SESSION["id_cidade_sessao"], $rs2->id_remedio, $rs2->tipo_apres);
				
			
			//se a qtde em estoque for maior ou igual a qtde receitada passa
				if ($qtde_atual>=$rs2->qtde)
					$classe= "verde_transp";
				else
					$classe= "vermelho_transp";
				
				$qtde_pego= pega_qtde_pego($rs2->id_consulta_remedio);
				if ($qtde_pego!="")
					$classe= "amarelo_transp";
			
			?>
			<tr class="<?= $classe; ?>">
				<td><?= $rs2->remedio; ?></td>
                <td><?= $qtde_atual; ?> unid(s)</td>
				<td align="center">
				<?= $rs2->qtde ." ". pega_apresentacao($rs2->tipo_apres); ?>
				</td>
				<td>
				<?
				
				if ($qtde_pego!="")
					echo $qtde_pego ." ";
				else {
					if ($qtde_atual<$rs2->qtde)
						echo "0 ";
					else {
						$mostrar= 1;
						$qtde_sol++;
				?>
					<input type="hidden" name="id_remedio[]" class="escondido" value="<?= $rs2->id_remedio; ?>" />
					<input type="hidden" name="id_consulta_remedio[]" class="escondido" value="<?= $rs2->id_consulta_remedio; ?>" />
					<input type="hidden" name="qtde[]" id="qtde_<?= $i; ?>" class="escondido" value="<?= $rs2->qtde; ?>" />
					<input name="qtde_pego[]" id="qtde_pego_<?= $i; ?>" class="tamanho30" value="<?= $rs2->qtde; ?>" onmouseover="Tip('Coloque o número de unidades que serão entregues.');" />
					<input type="hidden" name="tipo_apres[]" class="escondido" value="<?= $rs2->tipo_apres; ?>" />
				<?  $i++;
					}
				}
				echo pega_apresentacao($rs2->tipo_apres);
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
		<? }
	}//fim if id_consulta
	//if estiver buscando por cpf
	else {
		
		$result_pre= mysql_query("select pessoas.id_pessoa, pessoas.nome, pessoas.cpf from pessoas where cpf= '". $_POST["txt_busca"] . "' ");
		
		if (mysql_num_rows($result_pre)==0) {
			echo "CPF não cadastrado!";
		}
		else {
			$rs_pre= mysql_fetch_object($result_pre);
			
			$result= mysql_query("select consultas.id_consulta, DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y') as data_consulta,
									consultas.id_usuario as id_medico
									from consultas, postos, pessoas
									where pessoas.id_pessoa = consultas.id_pessoa
									and   consultas.id_posto = postos.id_posto
									and   postos.id_cidade = '". $id_cidade ."'
									and   pessoas.id_pessoa = '". $rs_pre->id_pessoa ."'
									") or die(mysql_error());
	
?>
		
		<label>Nome:</label>
		<?= $rs_pre->nome; ?>
		<br />
		
		<label>CPF:</label>
		<?= formata_cpf($rs_pre->cpf); ?>
		<br /><br />
		
		<fieldset>
			<legend>Consultas desta pessoa</legend>
			
			<?
			if (mysql_num_rows($result)==0)
				echo "Sem consultas realizadas para esta pessoa!";
			else {
			?>
			<table cellspacing="0">
				<tr>
					<th width="10%">Cód.</th>
					<th width="30%">Data da consulta</th>
					<th width="60%">Médico</th>
				</tr>
				<?
				while ($rs= mysql_fetch_object($result)) {
				?>
				<tr class="maozinha" onclick="ajaxLink('dist_atualiza', 'carregaPaginaInterna&amp;pagina=_farmacia/dist_inserir_pos&amp;id_consulta=<?= $rs->id_consulta; ?>');">
					<td align="center"><?= $rs->id_consulta; ?></td>
					<td align="center"><?= $rs->data_consulta; ?></td>
					<td align="center"><?= pega_nome_pelo_id_usuario($rs->id_medico); ?></td>
				</tr>
				<? } ?>
			</table>
		<? } ?>
		</fieldset>
<?
		}//fim else
	}
} //txt_busca
else
	echo "Sem dados para busca!";
?>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>