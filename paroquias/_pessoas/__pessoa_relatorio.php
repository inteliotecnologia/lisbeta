<?
if ($_SESSION["id_usuario_sessao"]!="") {

	if ($_SESSION["id_cidade_sessao"]!="")
		$id_cidade_emula= $_SESSION["id_cidade_sessao"];
	else
		$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

	$result_postos= mysql_query("select id_posto from postos where id_cidade = '$id_cidade_emula' ");
	
	if (mysql_num_rows($result_postos)>0) {
		$i= 0;
		$str2= "and ( ";
		
		while ($rs_postos= mysql_fetch_object($result_postos)) {
			
			if ($i==0)
				$str2 .= " pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
			else
				$str2 .= " or pessoas.id_origem_cadastro= '". $rs_postos->id_posto ."' ";
				
			$i++;
		}
		$str2 .= ")";
	}

	$sql= "select *, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc from pessoas where 1=1
			and   ((id_cidade = '". $id_cidade_emula ."')
									or
									((pessoas.origem_cadastro = 'c' and pessoas.id_origem_cadastro = '". $id_cidade_emula ."')
											or
											(
											pessoas.origem_cadastro= 'p'
												". $str2 ."
											)))";
	
	if ($_POST["agrupar"]!="")
		$sql .= " order by ". $_POST["agrupar"] ." asc, pessoas.nome asc";
	else
		$sql .= " order by pessoas.nome asc ";
	
	$result= mysql_query($sql);
?>

<div id="tela_cadastro">
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formPessoaRelatorio" method="post" id="formPessoaRelatorio" name="formPessoaRelatorio" onsubmit="return ajaxForm('conteudo', 'formPessoaRelatorio');">
		
        <label class="tamanho80" for="agrupar">Agrupar por:</label>
		<select name="agrupar" id="agrupar" class="tamanho120">
			<option value="">Nenhum</option>
            <option value="pessoas.bairro">Bairro</option>
            <option value="pessoas.id_psf">PSF</option>
		</select>	
	
		<button>Buscar</button>
	</form>
</div>

<h2 class="titulos">Relatório de cadastros</h2>

<div class="parte_total">
	<? /*
	LISTA
    <ul class="lista_metade">
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
        <li class="parte50_lista"><?= $rs->nome; ?> <? if ($rs->situacao_pessoa==2) echo "<img src=\"images/cruz.png\" alt=\"+\" />"; ?> (<?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?>)</li>
		<? } ?>
	</ul>
    */ ?>
    
    <p>Existem <strong><?= number_format(mysql_num_rows($result), 0, ',', '.'); ?></strong> cadastros no sistema.</p>
    
    <table cellspacing="0">
		<tr>
			<th width="6%">Cód.</th>
			<th width="34%" align="left">Nome</th>
			<th width="15%">Bairro</th>
			<th width="15%">CPF</th>
			<th width="15%">Data de nascimento</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_pessoa; ?></td>
			<td><?= $rs->nome; ?> <? if ($rs->situacao_pessoa==2) echo "<img src=\"images/cruz.png\" alt=\"+\" />"; ?></td>
			<td align="center"><?= $rs->bairro; ?></td>
			<td align="center">
			<?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?>			</td>
			<td align="center">
			<?
            if ($rs->data_nasc!="00/00/0000")
				echo $rs->data_nasc;
			else
				echo "<span class=\"vermelho\">não informada</span>";
			?>            </td>
		</tr>
		<? } ?>
	</table>
  <br />
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>