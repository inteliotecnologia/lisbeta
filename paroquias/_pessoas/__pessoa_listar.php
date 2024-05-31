<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<?
if (($_GET["todos"]==1) && ($_SESSION["tipo_usuario_sessao"]=="a")) {
	$sql= "select * from pessoas order by id_pessoa asc";
}
else {
	if ( (isset($txt_busca)) && (strlen($txt_busca)>2) ) {
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
	
		$sql= "select * from pessoas ";
		switch ($lugar) {
			/*case 'todos': $sql .= "
									where nome like '%". $txt_busca ."%'
									or    cpf like '%". $txt_busca ."%'
									and   ((pessoas.origem = 'c' and pessoas.id_origem = '". $id_cidade_emula ."')
											or
											(
											pessoas.origem= 'p' and
												(
												". $str2 ."
												)
											))
									";
							break;*/
			case 'id_pessoa': $sql .= "where id_pessoa = '". $txt_busca ."'
									   and   ((id_cidade = '". $id_cidade_emula ."')
									or
									((pessoas.origem_cadastro = 'c' and pessoas.id_origem_cadastro = '". $id_cidade_emula ."')
											or
											(
											pessoas.origem_cadastro= 'p'
											
												". $str2 ."
	
											)))
										"; break;
			case 'nome': $sql .= "where nome like '%". $txt_busca ."%'
									and   ((id_cidade = '". $id_cidade_emula ."')
									or
									((pessoas.origem_cadastro = 'c' and pessoas.id_origem_cadastro = '". $id_cidade_emula ."')
											or
											(
											pessoas.origem_cadastro= 'p'
											
												". $str2 ."
	
											)))
									"; break;
			case 'cpf': $sql .= "where cpf = '". $txt_busca ."' "; break;
			default: die("Consulta não autorizada");
		}
		$sql .= " order by nome asc ";
	}
}//fim else
$result= mysql_query($sql);
//echo $sql;
?>

<div id="tela_cadastro">
</div>

<h2 class="titulos">Cadastros no sistema</h2>

<div id="tela_mensagens">
	<? include("__tratamento_msgs.php"); ?>
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formPessoaBuscar" method="post" id="formPessoaBuscar" name="formPessoaBuscar" onsubmit="return ajaxForm('conteudo', 'formPessoaBuscar');">

		<label class="tamanho30" for="busca">Busca:</label>
		
		<input name="txt_busca" id="txt_busca" class="tamanho50" maxlength="11" value="<?= $txt_busca; ?>" />

		<select name="lugar" id="lugar" class="tamanho80">
			<option value="nome" <? if ($lugar=="nome") echo "selected=\"selected\""; ?>>Nome</option>
			<option value="id_pessoa" <? if ($lugar=="id_pessoa") echo "selected=\"selected\""; ?>>Código</option>
            <option value="cpf" <? if ($lugar=="cpf") echo "selected=\"selected\""; ?>>CPF</option>
		</select>	

		<button>Buscar</button>
	
	</form>
</div>

<div class="parte_total">
	<? if (( (isset($txt_busca)) && (strlen($txt_busca)>2) ) || (($_GET["todos"]==1) && ($_SESSION["tipo_usuario_sessao"]=="a"))) { ?>
	<p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s).</p>
	<br />
	
	<? if (mysql_num_rows($result)>0) { ?>
	<table cellspacing="0">
		<tr>
			<th width="8%">Código</th>
			<th width="37%" align="left">Nome</th>
			<th width="35%">CPF</th>
			<th width="15%">Data nasc.</th>
			<th width="5%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_pessoa; ?></td>
			<td><a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=_pessoas/pessoa_ver&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;txt_busca=<?= $txt_busca; ?>&amp;lugar=<?= $lugar; ?>')"><?= $rs->nome; ?></a> <? if ($rs->situacao_pessoa==2) echo "<img src=\"images/cruz.png\" alt=\"+\" />"; ?>
            <?
			/*if ($_SESSION["tipo_usuario_sessao"]=='a') {
				$idade_meses= calcula_meses($rs->data_nasc);
				$idade_anos= calcula_idade($rs->data_nasc);
				
				echo $idade_anos ."/". $idade_meses;
			}*/
			?>
            </td>
			<td align="center">
			<?= mostra_cpf_ou_responsavel($rs->cpf, $rs->id_responsavel); ?></td>
			<td align="center"><?= desformata_data($rs->data_nasc); ?></td>
			<td>
			<a href="javascript:void(0);" onclick="ajaxLink('tela_cadastro', 'carregaPaginaInterna&amp;pagina=_pessoas/pessoa_editar&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;retorno=conteudo'); abreDivSo('tela_cadastro');" class="link_editar" title="Editar">editar</a>			</td>
		</tr>
		<? } ?>
	</table>
  <? } } else
			echo "Faça sua busca no formulário acima!";
	?>
</div>
<script language="javascript" type="text/javascript">daFoco("txt_busca");</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>