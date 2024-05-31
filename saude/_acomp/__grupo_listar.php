<? if (@pode_algum("ceim", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Grupos de acompanhamento</h2>

	<?
	$result_gr= mysql_query("select * from acomp_grupos");
	
	while ($rs_gr= mysql_fetch_object($result_gr)) {
	?>
	<fieldset>
		<legend><?= $rs_gr->id_grupo .". ". $rs_gr->grupo; ?></legend>
        
		<table cellspacing="0">
	        <tr>
			    <th width="15%" align="left">Cód.</th>
			    <th width="40%" align="left">Nome</th>
		        <th width="25%">Acompanhamento até</th>
                <th width="10%" align="left">Ações</th>
	        </tr>
			<?
			$result= mysql_query("select pessoas.id_pessoa, pessoas.nome, acomp_grupos_pessoas.*,
										DATE_FORMAT(acompanhar_ate, '%d/%m/%Y') as acompanhar_ate2
										from  acomp_grupos_pessoas, pessoas
										where pessoas.id_pessoa = acomp_grupos_pessoas.id_pessoa
										and   acomp_grupos_pessoas.id_grupo= '". $rs_gr->id_grupo ."'
										and   acomp_grupos_pessoas.id_posto= '". $_SESSION["id_posto_sessao"] ."'
										order by pessoas.nome ");
			
			while ($rs= mysql_fetch_object($result)) {
			?>
            <tr <? if (($rs->acompanhar_ate!="0000-00-00") && ($rs->acompanhar_ate<=date("Y-m-d"))) echo "class=\"warning\""; ?>>
	          <td align="left"><?= $rs->id_pessoa; ?></td>
	          <td align="left"><?= $rs->nome; ?></td>
	          <td align="center">
			  <?
	              if ($rs->acompanhar_ate2=="00/00/0000") echo "-";
				  else echo $rs->acompanhar_ate2;
			  ?>
              </td>
	          <td>
              	<a onclick="return confirm('Tem certeza que deseja excluir \'<?= $rs->nome; ?>\' do grupo de \'<?= $rs_gr->grupo; ?>\'?');" href="javascript:ajaxLink('conteudo', 'pessoaGrupoExcluir&amp;id_pessoa=<?= $rs->id_pessoa; ?>&amp;id_grupo=<?= $rs_gr->id_grupo; ?>');" class="link_excluir" title="Excluir" onmouseover="Tip('Clique para excluir esta pessoa deste grupo.');">excluir</a>
              </td>
          </tr>
            <? } ?>
		</table>
</fieldset>
      <? } ?>
	<br />
    
<br />

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>