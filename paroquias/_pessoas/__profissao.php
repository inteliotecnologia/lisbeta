<?
if ($_SESSION["id_usuario_sessao"]!="") {
?>
    <select name="id_profissao" id="id_profissao">
      <option selected="selected">---</option>
      <?
        $result_prof= mysql_query("select * from profissoes order by profissao asc");
        $i= 0;
        while ($rs_prof= mysql_fetch_object($result_prof)) {
      ?>
      <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_prof->id_profissao; ?>" <? if ($rs_prof->id_profissao == $rs->id_profissao) echo "selected=\"selected\""; ?>><?= $rs_prof->profissao; ?></option>
      <? $i++; } ?>
    </select>
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>