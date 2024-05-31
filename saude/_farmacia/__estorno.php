<? if (@pode("f", $_SESSION["permissao"])) { ?>
<?
	
if ($_SESSION["id_posto_sessao"]!="")
	$str= "id_posto = '". $_SESSION["id_posto_sessao"] ."'";
else
	$str= "id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";

$result= mysql_query("select almoxarifado_mov.*, DATE_FORMAT(almoxarifado_mov.data_trans, '%d/%m/%Y') as data_trans2, 
						DATE_FORMAT(almoxarifado_mov.data_trans, '%d') as dia, 
						DATE_FORMAT(almoxarifado_mov.data_trans, '%m') as mes, 
						DATE_FORMAT(almoxarifado_mov.data_trans, '%Y') as ano
						from almoxarifado_mov
						where almoxarifado_mov.id_mov = '$id_mov'
						and   almoxarifado_mov.situacao_mov is NULL
						and   almoxarifado_mov.". $str ."
						") or die(mysql_error());
if (mysql_num_rows($result)==0)
	die();
else
	$rs= mysql_fetch_object($result);

if ($origem!="") {
	$origem= str_replace("|", "&amp;", $origem);
?>
	<a id="botao_voltar" href="javascript:void(0);" onclick="ajaxLink('conteudo', 'carregaPagina&amp;pagina=<?= $origem; ?>');">&lt;&lt; voltar para relatório</a>
<? } ?>

<h2 class="titulos">Estorno de entrega de medicamento</h2>

<div class="parte_direita_i">
	<p>Esta ação irá devolver o medicamento entregue (detalhes ao lado) para o estoque, maiores detalhes abaixo:</p>
	
    <?
		if ($_SESSION["id_posto_sessao"]!="") {
			$tabela= "postos_estoque";
			$parametro1= "id_posto";
			$parametro1_v= $_SESSION["id_posto_sessao"];
		}
		if ($_SESSION["id_cidade_sessao"]!="") {
			$tabela= "almoxarifado_atual";
			$parametro1= "id_cidade";
			$parametro1_v= $_SESSION["id_cidade_sessao"];
		}
		//seleciona a quantidade atual desse remedio no posto ou cidade
		$result0= mysql_query("select * from ". $tabela ."
								where ". $parametro1 ." = '". $parametro1_v ."'
								and   id_remedio = '". $rs->id_remedio ."'
								and   tipo_apres = '". $rs->tipo_apres ."'
								");
		$rs0= mysql_fetch_object($result0);

	?>
    
    <label>Qtde atual:</label>
    <?= number_format($rs0->qtde_atual, 0, ',', '.') ." ". pega_apresentacao($rs0->tipo_apres); ?>
    <br />
    
    <label>Qtde estornada:</label>
    <?= number_format($rs->qtde, 0, ',', '.') ." ". pega_apresentacao($rs0->tipo_apres); ?>
    <br />
    
    <label>Saldo pós:</label>
    <?= number_format(($rs0->qtde_atual+$rs->qtde), 0, ',', '.') ." ". pega_apresentacao($rs0->tipo_apres); ?>
    <br />
    
    <form action="<?= AJAX_FORM; ?>formEstornoEntrega" method="post" id="formEstornoEntrega" name="formEstornoEntrega" onsubmit="return ajaxForm('conteudo', 'formEstornoEntrega');">
    	<input type="hidden" class="escondido" name="id_mov" id="id_mov" value="<?= $rs->id_mov; ?>" />
        <br />
        
        <label for="observacoes">Motivo:</label>
        <textarea name="observacoes" id="observacoes"></textarea>
        <br />
        
        <label>&nbsp;</label>
        <button type="submit">Enviar</button>
    </form>
    
</div>
<div class="parte_esquerda_i">
	<fieldset>
        <legend>Visualização da movimentação</legend>
        
        <label>Cód.:</label>
        <?= $rs->id_mov; ?>
        <br />
        
        <label>Tipo:</label>
        <?= pega_tipo_transacao($rs->tipo_trans); ?>
        <br />
        
        <label>Remédio:</label>
        <?= pega_remedio($rs->id_remedio); ?>
        <br />
        
        <label>Quantidade:</label>
        <?= number_format($rs->qtde, 0, ',', '.') ." ". pega_apresentacao($rs->tipo_apres) ; ?>
        <br />
        
        <?
        if ( ($rs->tipo_trans=='e') || ($rs->tipo_trans=='s') ) {
            
            if ($rs->tipo_trans=='e') {
                $subtipo= pega_origem_entrada($rs->subtipo_trans);
                ?>
        <label>Fornecedor:</label>
        <?
        if ($rs->id_fornecedor!="")
            echo pega_fornecedor($rs->id_fornecedor);
        else
            echo "Não informado.";
        ?>
        <br />
            <?
            }
            else
                $subtipo= pega_origem_saida($rs->subtipo_trans);
                
        ?>
        <label>Subtipo:</label>
        <?= $subtipo; ?>
        <br />
        <?
        }
        else {
            if ($rs->tipo_trans=='m')
                $destino= pega_posto($rs->id_receptor);
            else
                $destino= pega_nome($rs->id_receptor);
        
        if ($rs->id_posto!="")
            $origem= pega_posto($rs->id_posto);
        if ($rs->id_cidade!="")
            $origem= "FARMÁCIA CENTRAL - ". pega_cidade($rs->id_cidade);
        ?>
        
        <label>Origem:</label>
        <?= $origem; ?>
        <br />
        
        <? if ($destino!="") { ?>
        <label>Destino:</label>
        <?= $destino; ?>
        <br />
        <? } ?>
        
        <? } ?>
        
        <? if (($rs->tipo_trans=='s') && ($rs->id_receptor!='')) { ?>
        <label>Destino:</label>
        <?= pega_nome($rs->id_receptor); ?>
        <br />
        <? } ?>
        
        <label>Data:</label>
        <?= $rs->data_trans2; ?>
        <br />
    
        <label>Funcionário:</label>
        <?= pega_nome_pelo_id_usuario($rs->id_usuario); ?>
        <br />
        
        <label>Observações:</label>
        <?
        if ($rs->observacoes!="")
            echo $rs->observacoes;
        else
            echo "<span class=\"vermelho\">Não informado!</span>";
        ?>
        <br />
	</fieldset>
</div>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>