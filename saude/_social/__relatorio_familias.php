<? if (@pode_algum("zl", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relatórios de famílias</h2>

<div class="parte_total com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formFamiliaBuscar" method="post" id="formFamiliaBuscar" name="formFamiliaBuscar" onsubmit="return ajaxForm('conteudo', 'formFamiliaBuscar');">
		
        <? if (@pode("l", $_SESSION["permissao"]) ) { ?>
        <label for="id_psf">PSF:</label>
        <select name="id_psf" id="id_psf" onchange="retornaMicroareas();">
            <option value="">--- selecione</option>
            <?
            $result_postos= mysql_query("select postos.id_posto, postos.posto from postos
                                            where postos.id_cidade = '". $_SESSION["id_cidade_pref"] ."'
                                            and   postos.situacao = '1'
                                            and   postos.psf= '1'
                                            order by postos.posto");
            $i= 0;
            while($rs_postos= mysql_fetch_object($result_postos)) {
                if (($i%2)==0)
                    $classe= "class=\"cor_sim\"";
                else
                    $classe= "";
                
                if ($rs_postos->id_posto==$_SESSION["id_posto_sessao"])
                    $selecionavel= " selected=\"selected\" ";
                else
                    $selecionavel= "";
                
                echo "<option ". $classe ." value=\"". $rs_postos->id_posto ."\" ". $selecionavel .">". $rs_postos->posto ."</option>";
                $i++;
            }
            ?>
        </select>
		<? } else { ?>
        <input name="id_psf" id="id_psf" class="escondido" type="hidden" value="<?= $_SESSION["id_posto_sessao"]; ?>" />
        <script language="javascript">
            retornaMicroareas();
        </script>
        <? } ?>
            
            <label>Microárea:</label>
            <div id="id_microarea_atualiza">
            	<span class="vermelho">Selecione o PSF antes.</span>
                <input type="hidden" name="id_microarea" id="id_microarea" class="escondido" value="" />
            </div>
            <br />
            
            <label for="nome">Nome:</label>
            <input name="nome" id="nome" />
            <br /><br />
            
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>