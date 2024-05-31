<?

require_once("conexao.php");
require_once("funcoes.php");

if (pode_algum("rhv4", $_SESSION["permissao"])) {
	
	
	// definimos o tipo de arquivo
	header("Content-type: application/msexcel");
	// Como será gravado o arquivo
	header("Content-Disposition: attachment; filename=colaboradores_relatorio_excel_". date("d-m-Y_H:i:s") .".xls");
	
	if ($_GET["ordenacao"]=="") $ordenacao= "rh_carreiras.id_departamento asc, rh_carreiras.id_turno asc, pessoas.nome_rz asc";
	else $ordenacao= $_GET["ordenacao"];
	
	if ($_GET["ordem"]=="") $ordem= "";
	else $ordem= $_GET["ordem"];
	
	if ($ordem=="asc") $ordem_inversa= "desc";
	else $ordem_inversa= "asc";
	
	if ($_POST["nome_rz"]!="") $nome_rz= $_POST["nome_rz"];
	if ($_GET["nome_rz"]!="") $nome_rz= $_GET["nome_rz"];
	if ($nome_rz!="") $str2= " and   pessoas.nome_rz like '%". $nome_rz ."%' ";
	
	if ($_GET["oficial"]!="") $str2 .= "and   rh_funcionarios.oficial = '". $_GET["oficial"] ."' ";
	if ($_GET["id_departamento"]!="") $str2 .= "and   rh_carreiras.id_departamento = '". $_GET["id_departamento"] ."' ";
	
	if ($_GET["status_funcionario"]!="")
		$str2 .= "and   rh_funcionarios.status_funcionario = '". $_GET["status_funcionario"] ."' ";
	
	$str2 .= "and   rh_carreiras.id_departamento = '29' ";
	
	for ($i=1; $i<3; $i++) {
	
	$result= mysql_query("select *
							from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras, rh_departamentos, rh_turnos
							where pessoas.id_pessoa = rh_funcionarios.id_pessoa
							and   pessoas.tipo = 'f'
							and   rh_enderecos.id_pessoa = pessoas.id_pessoa
							and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
							and   rh_carreiras.atual = '1'
							and   rh_carreiras.id_departamento = rh_departamentos.id_departamento
							and   rh_carreiras.id_turno = rh_turnos.id_turno
							and   rh_departamentos.id_empresa = '". $_SESSION["id_empresa"] ."'
							and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
							and   rh_funcionarios.status_funcionario = '1'
							and   rh_funcionarios.oficial = '". $i ."'
							". $str2 ."
							order by ". $ordenacao ." ". $ordem ."
							") or die(mysql_error());
?>

<h2>Colaboradores externos <? if ($i==1) echo "oficiais"; else echo "voluntários"; ?></h2>

<table cellspacing="0" width="100%">
  <tr>
		<th width="6%">Cód.</th>
	    <th width="28%" align="left">Nome</th>
	    <th width="10%" align="left">Admiss&atilde;o</th>
	    <th width="15%" align="left">CPF</th>
	    <th width="15%" align="left">Equipe</th>
  </tr>
	<?
	$j=0;
	while ($rs= mysql_fetch_object($result)) {
		
		if ($rs->status_funcionario==1) $status= 0;
		else $status= 1;
	?>
	<tr>
		<td align="center"><?= $rs->id_funcionario; ?></td>
        <td><?= $rs->nome_rz; ?> <? if ($rs->afastado==1) echo "<span class=\"vermelho menor\">(afastado)</span>"; ?>
        </td>
        <td><span class="escondido"><?= pega_data_admissao($rs->id_funcionario); ?></td>
        
		<td><?= $rs->cpf_cnpj; ?></td>
		<td><?= $rs->equipe; ?></td>
	</tr>
	<? $j++; } ?>
</table>
<br />
<br />


<? } } ?>