<?
if (@pode("f", $_SESSION["permissao"])) {
	$result_em= mysql_query("select * from remedios
								where id_remedio = '". $_GET["id_remedio"] ."'
								");
	$rs_em= mysql_fetch_object($result_em);
	
	if ($_SESSION["id_posto_sessao"]!="") {
		$tabela= "postos";
		$campo= "id_posto";
		$valor_campo= $_SESSION["id_posto_sessao"];
	}
	if ($_SESSION["id_cidade_sessao"]!="") {
		$tabela= "almoxarifado";
		$campo= "id_cidade";
		$valor_campo= $_SESSION["id_cidade_sessao"];
	}
	
	$result_emin= mysql_query("select * from ". $tabela ."_minimo
								where $campo = $valor_campo
								and   id_remedio = '". $_GET["id_remedio"] ."'
								");

	$rs_emin= mysql_fetch_object($result_emin);
?>


<h2 class="titulos" id="tit_estoque_minimo">Estoque mínimo</h2>

<a href="javascript:void(0);" onclick="fechaDiv('tela_aux_rapida');" class="fechar">x</a>

<div id="formulario">
    <form action="<?= AJAX_FORM; ?>formEstoqueMinimo" method="post" id="formEstoqueMinimo" name="formEstoqueMinimo" onsubmit="return ajaxForm('formulario', 'formEstoqueMinimo');">
        <input name="id_remedio" id="id_remedio" class="escondido" type="hidden" value="<?= $rs_em->id_remedio; ?>" />
        
        <label for="remedio">Remédio:</label>
        <?= $rs_em->remedio ." (". pega_tipo_remedio($rs_em->tipo_remedio) .")"; ?>
        <br />
        
        <label for="qtde_minima">Qtde mínima:</label>
        <input name="qtde_minima" id="qtde_minima" class="tamanho50" value="<?= $rs_emin->qtde_minima; ?>" />
        <br /><br />
    
        <label>&nbsp;</label>
        <button>Atualizar</button>
    </form>
</div>
        
<script language="javascript" type="text/javascript">daFoco('qtde_minima');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>