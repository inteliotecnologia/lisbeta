<?
if (@pode("l", $_SESSION["permissao"])) {
	$result_fam= mysql_query("select familias.*, microareas.*, microareas.id_pessoa as id_agente, postos.posto
							from familias, microareas, postos
							where familias.id_familia = '". $id_familia ."'
							and   familias.id_microarea = microareas.id_microarea
							and   microareas.id_posto = postos.id_posto
							and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							");
	$rs_fam= mysql_fetch_object($result_fam);
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Visitas domiciliares</h2>

<fieldset>
    <legend>Dados da família</legend>
	
    <div class="partei">
        <label>Código:</label>
        <?= $rs_fam->id_familia; ?>
        <br />
        
        <label>Chefe:</label>
        <?= pega_chefe_familia($rs_fam->id_familia); ?>
        <br />
    </div>
    <div class="partei">
        <label>Agente:</label>
        <?= pega_nome($rs_fam->id_agente); ?>
        <br />
        
        <label>Microárea:</label>
        <?= $rs_fam->microarea; ?>
        <br />
        
        <label>PSF:</label>
        <?= $rs_fam->posto; ?>
        <br />
    </div>
</fieldset>

<fieldset class="screen">
    <legend>Novo parecer técnico</legend>
	
    <form action="<?= AJAX_FORM; ?>formVisitaInserir" method="post" id="formVisitaInserir" name="formVisitaInserir" onsubmit="return ajaxForm('conteudo', 'formVisitaInserir');">
	    <input name="id_familia" id="id_familia" type="hidden" class="escondido" value="<?= $rs_fam->id_familia; ?>" />

        <div class="partei">
            <label for="data_visita">Data:</label>
            <input name="data_visita" id="data_visita"  maxlength="10" onkeyup="formataData(this);" value="<?= date("d/m/Y"); ?>" />
            <br />
            
            <label for="situacao">Situação:</label>
            <textarea name="situacao" id="situacao"></textarea>
            <br />
        </div>
        <div class="partei">
            <label for="parecer">Parecer:</label>
            <textarea name="parecer" id="parecer"></textarea>
            <br />
            
            <label>&nbsp;</label>
            <button id="botaoInserir" type="submit">Inserir</button>
            <br /><br />
        </div>
    
    </form>
</fieldset>

<fieldset>
    <legend>Visitas domiciliares cadastradas para esta família</legend>
    
	<?
	$result= mysql_query("select *, DATE_FORMAT(data_visita, '%d/%m/%Y') as data_visita2 from familias_visitas
							where id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
							and   id_familia = '". $rs_fam->id_familia ."'
							order by data_visita desc, id_visita desc
							");
	?>
    
    <p>Foram encontrado(s) <strong><?= mysql_num_rows($result); ?></strong> registro(s)</p>
	<br />
	
	<table cellspacing="0">
		<tr>
			<th width="5%">Cód.</th>
            <th width="10%">Data</th>
            <th width="35%" align="left">Situação</th>
            <th width="35%" align="left">Parecer</th>
            <th width="10%" align="left">Ações</th>
		</tr>
		<?
		while ($rs= mysql_fetch_object($result)) {
		?>
		<tr class="corzinha">
			<td align="center"><?= $rs->id_visita; ?></td>
			<td align="center"><?= $rs->data_visita2; ?></td>
			<td><?= $rs->situacao; ?></td>
			<td><?= $rs->parecer; ?></td>
            <td>
                <a onclick="return confirm('Tem certeza que deseja excluir esta visita?\n\nOPERAÇÃO IRREVERSÍVEL!');" href="javascript:ajaxLink('conteudo', 'visitaExcluir&amp;id_familia=<?= $rs->id_familia; ?>&amp;id_visita=<?= $rs->id_visita; ?>');" class="link_excluir" title="Excluir">excluir</a>
            </td>
		</tr>
		<? } ?>
	</table>
<?
	if ($total_linhas>0) {
		if ($num_paginas > 1) {
			$texto_url= "carregaPagina&amp;pagina=_social/visitas&amp;id_familia=". $rs_fam->id_familia ."&amp;inicio=". $inicio ."&amp;fim=". $fim ."&amp;num_pagina=";
			
			$texto_paginacao .= "<div id=\"paginacao\">
					<ul>";
			if ($num_pagina > 0) {
				$menos = $num_pagina - 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $menos ."')\">&laquo; Anterior</a></li>";
			}
	
			for ($i=0; $i<$num_paginas; $i++) {
				$link = $i + 1;
				if ($num_pagina==$i)
					$texto_paginacao .= "<li class=\"paginacao_atual\">". $link ."</li>";
				else
					$texto_paginacao .=  "<li><a href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $i ."')\">". $link ."</a></li>";
			}
		
			if ($num_pagina < ($num_paginas - 1)) {
				$mais = $num_pagina + 1;
				$texto_paginacao .=  "<li><a class=\"maior\" href=\"javascript:void(0);\" onclick=\"ajaxLink('conteudo', '". $texto_url . $mais ."')\">Pr&oacute;xima &raquo;</a></li>";
			}
			$texto_paginacao .=  "</ul>
				</div>";
	
			echo $texto_paginacao;
		}
	}
	?>

</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>