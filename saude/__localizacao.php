<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<label for="id_uf">Estado:</label>
<select name="id_uf" id="id_uf" onchange="retornaCidades();">
  <option selected="selected">---</option>
  <?
	$result_uf= mysql_query("select id_uf, uf from ufs order by uf");
	$i= 0;
	while ($rs_uf= mysql_fetch_object($result_uf)) {
  ?>
  <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_uf->id_uf; ?>" <? if ($rs_uf->id_uf == $_SESSION["id_uf_pref"]) echo "selected=\"selected\""; ?>><?= $rs_uf->uf; ?></option>
  <? $i++; } ?>
</select>
<br />

<label for="id_cidade">Cidade:</label>
<div id="id_cidade_atualiza">
	<select name="id_cidade" id="id_cidade" onchange="retornaPsfs();">
	  <option value="">--- selecione ---</option>
	  <?
		$result_cid= mysql_query("select id_cidade, cidade from cidades where id_uf = '$id_uf_pref' order by cidade");
		$i= 0;
		while ($rs_cid= mysql_fetch_object($result_cid)) {
	  ?>
	  <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>" <? if ($rs_cid->id_cidade == $_SESSION["id_cidade_pref"]) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade; ?></option>
	  <? $i++; } ?>
	</select>
</div>
<br />

<label for="id_psf">PSF:</label>
<div id="id_origem_atualiza">
    <select name="id_psf" id="id_psf">
        <option value="">--- NÃO SEI</option>
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
</div>
<br />

<script language="javascript" type="text/javascript">
	habilitaCampo('enviar');
</script>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>