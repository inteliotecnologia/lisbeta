<?
if (@pode("f", $_SESSION["permissao"])) {
	/*$result_em= mysql_query("select * from remedios
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

	$rs_emin= mysql_fetch_object($result_emin);*/
?>


<h2 class="titulos" id="tit_med_inserir">Distribuição de medicamentos</h2>

<a href="javascript:void(0);" onclick="fechaDiv('tela_aux_rapida');" class="fechar">x</a>

<div id="formulario">
	
    <label>Remédio:</label>
    <input id="pesquisa" name="pesquisa" class="tamanho80" onkeyup="if (event.keyCode==13) remedioPesquisar(5, 's');" />
    <button type="button" class="tamanho30" onclick="remedioPesquisar(5, 's');">ok</button>
    <br />
        
    <div id="pesquisa_remedio_atualiza">
    </div>
</div>
        
<script language="javascript" type="text/javascript">daFoco('pesquisa');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>