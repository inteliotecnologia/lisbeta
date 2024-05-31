<? if (@pode_algum("zl", $_SESSION["permissao"])) { ?>
<h2 class="titulos">Relatórios de famílias</h2>

<div class="parte50 com_label_grande">
	<fieldset>
		<legend>Busca avançada</legend>
		
		<form action="<?= AJAX_FORM; ?>formFamiliaBuscar" method="post" id="formFamiliaBuscar" name="formFamiliaBuscar" onsubmit="return ajaxForm('conteudo', 'formFamiliaBuscar');">
		
        <? if (@pode("l", $_SESSION["permissao"]) ) { ?>
        <label for="id_posto">Posto:</label>
        <select name="id_posto" id="id_posto" onchange="retornaMicroareas();">
            <option value="">--- selecione</option>
            <?
            $result_postos= mysql_query("select postos.id_posto, postos.posto from postos
                                            where postos.id_cidade = '". $_SESSION["id_cidade_pref"] ."'
                                            and   postos.situacao = '1'
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
        <input name="id_posto" id="id_posto" class="escondido" type="hidden" value="<?= $_SESSION["id_posto_sessao"]; ?>" />
        <? } ?>
            <br />
            
            <input name="busca" id="busca" class="escondido" type="hidden" value="1" />
            
            <label>Região:</label>
            <div id="id_microarea_atualiza">
            	<span class="vermelho">Selecione o PSF antes.</span>
                <input type="hidden" name="id_microarea" id="id_microarea" class="escondido" value="" />
            </div>
            <br />
            
            <label for="nome">Chefe:</label>
            <input name="nome" id="nome" />
            <br />
            
            <label for="id_familia">Cód (novo):</label>
            <input name="id_familia" id="id_familia" />
            <br />
            
            <label for="num_familia">Cód (antigo):</label>
            <input name="num_familia" id="num_familia" />
            <br /><br />
            
			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>

<div class="parte50">
	<fieldset>
		<legend>Consumo mensal geral</legend>
		
		<form action="<?= AJAX_FORM; ?>formArrecadacaoMensal" method="post" id="formArrecadacaoMensal" name="formConsumoMensal" onsubmit="return ajaxForm('conteudo', 'formArrecadacaoMensal');">
			
            <label for="periodo">Período:</label>
            <select name="periodo" id="periodo" title="Período">	  		
				<?
                $i=0;
                
				$result_per= mysql_query("select distinct(ano)
											from arrecadacoes where id_posto = '". $_SESSION["id_posto_sessao"] ."'
											group by ano desc
											");
                
                while ($rs_per= mysql_fetch_object($result_per)) {
					//for ($i=1; $i<14; $i++) {
				?>
                <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_per->ano; ?>"><?= $rs_per->ano; ?></option>
                <? $i++; } //} ?>
            </select>
            
			<br /><br />

			<label>&nbsp;</label>
			<button>Buscar</button>
		</form>
			
	</fieldset>
</div>

<script language="javascript">
	retornaMicroareas();
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>