<?
if (@pode("f", $_SESSION["permissao"])) {
	$sql= "select distinct(remedios.id_remedio), pessoas.nome, pessoas.id_responsavel, pessoas.cpf, remedios.*
							from almoxarifado_mov, remedios, pessoas
							where almoxarifado_mov.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							and   almoxarifado_mov.id_remedio = remedios.id_remedio
							and   remedios.classificacao_remedio = 'c'
							and   almoxarifado_mov.tipo_trans = 's'
							and   almoxarifado_mov.subtipo_trans = 'b'
							and   almoxarifado_mov.id_receptor = pessoas.id_pessoa
							
							order by pessoas.nome asc
							";
		
	$result= mysql_query($sql) or die(mysql_error());

	/*$num= 20;
	$total_linhas = mysql_num_rows($result);
	$num_paginas = ceil($total_linhas/$num);
	if (!isset($num_pagina))
		$num_pagina = 0;
	$comeco = $num_pagina*$num;
	
	$result= mysql_query($sql ." limit $comeco, $num") or die(mysql_error());

*/
?>

<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_farmacia/relatorio_stats');">&lt;&lt; voltar</a>

<h2 class="titulos">Relação de pessoas/remédios psicotrópicos</h2>

<div class="parte_total">
	<table cellspacing="0">
    	<tr>
        	<th width="35%" align="left">Nome</th>
            <th width="25%">CPF</th>
            <th align="left" width="40%">Remédio</th>
    	</tr>
		<?
		while($rs= mysql_fetch_object($result)) {
			if ($rs->classificacao_remedio=="c")
				$antes= "<img src='images/preto.gif' alt='' /> ";
			else
				$antes= "";
		?>
        <tr class="corzinha">
        	<td><?= $rs->nome; ?></td>
            <td align="center"><?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?></td>
            <td><?= $antes . $rs->remedio ." (". pega_tipo_remedio($rs->tipo_remedio) .")"; ?></td>
        </tr>
        <? } ?>
    </table>
    
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>