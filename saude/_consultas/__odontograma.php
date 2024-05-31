<?
if (@pode_algum("on", $_SESSION["permissao"])) {
	if (($_GET["novo"]==1) && ($_GET["id_pessoa"]!="")) {
		$result_io= mysql_query("insert into odontogramas (id_pessoa, data_odontograma, id_usuario)
									values
									('". $_GET["id_pessoa"] ."', '". date("Ymd") ."', '". $_SESSION["id_usuario_sessao"] ."') ");
		$id_odontograma= mysql_insert_id();
	}
	$result_odo= mysql_query("select *, DATE_FORMAT(data_odontograma, '%d/%m/%Y') as data_odo
								from odontogramas
								where id_odontograma = '$id_odontograma'
								and   id_pessoa = '$id_pessoa'
								");
	$rs_odo= mysql_fetch_object($result_odo);
?>
<input type="hidden" class="escondido" name="id_odontograma" id="id_odontograma" value="<?=$rs_odo->id_odontograma;?>" />

<h3 class="titulos">Odontograma nº <?=$rs_odo->id_odontograma;?> - <?= $rs_odo->data_odo; ?></h3>

<br />
<ul class="abas">
	<li><a href="javascript:void(0);" onclick="abreDiv('denticao1'); fechaDiv('denticao2');">Dentição de leite</a></li>
	<li><a href="javascript:void(0);" onclick="abreDiv('denticao2'); fechaDiv('denticao1');">Dentição permanente</a></li>
</ul>

<? for ($i=1; $i<3; $i++) { ?>
	<div id="denticao<?=$i;?>" <? if ($i==2) echo "class=\"nao_mostra\""; ?>>
		<?
		for ($quadrante=1; $quadrante<5; $quadrante++) {
			switch($quadrante) {
				case 1: $bordas= " borda_dir borda_inf "; break;
				case 2: $bordas= " borda_inf "; break;
				case 3: $bordas= " borda_dir "; break;
				case 4: $bordas= " "; break;
			}
		?>
			<div class="parte_meio_exato <?= $bordas; ?>">
			<?
			$result_denticao= mysql_query("select * from odonto_denticao
											where tipo= '$i'
											and   quadrante = '$quadrante'
											");
			while ($rs_denticao= mysql_fetch_object($result_denticao)) {
			?>
				<div class="dente_descricao">
                    <?=$rs_denticao->id_dente;?>
                    <div id="dente<?=$rs_denticao->id_dente;?>" class="dente">
                        <?
                        for ($j=1; $j<6; $j++) {
                            $rs_face= mysql_fetch_object(mysql_query("select * from odontograma_denticao
                                                                        where id_odontograma = '". $rs_odo->id_odontograma ."'
                                                                        and   id_dente = '". $rs_denticao->id_dente ."'
                                                                        and   id_face = '$j'
                                                                        "));
                            
                            if ($rs_face->problema==1) $problema=1;
                            else $problema=0;
                        ?>
                        <div <? if ($problema==1) echo " id='in' style='background:#000000;' "; else echo " id='out' "; ?> class="face<?=$j;?>" <? if ($_GET["semclique"]!=1) { ?>onclick="setaFaceDente(this, <?= $rs_denticao->id_dente; ?>, <?= $j; ?>);" <? } ?> onmouseover="Tip('Dente <?= $rs_denticao->id_dente ." face ". $j; ?>'); trocaFundoDente(this, '#CCCCCC', 100);" onmouseout="trocaFundoDente(this, '#EFEFEF', 0);">
                            <input type="hidden" class="escondido" name="id_dente[]" id="id_dente_<?= $rs_denticao->id_dente ."_". $j; ?>" value="<?=$rs_denticao->id_dente;?>" />
                            <input type="hidden" class="escondido" name="id_face[]" id="id_face_<?= $rs_denticao->id_dente ."_". $j; ?>" value="<?=$j;?>" />
                            <input type="hidden" class="escondido" name="problema[]" id="problema_<?= $rs_denticao->id_dente ."_". $j; ?>" value="<?=$problema;?>" />
                        </div>
                        <? } //fim fim j ?>
                    </div>
                </div>
			<? } //fim while dentição ?>
			</div>
		<? } //fim for quadrante ?>
	</div>
<? } //fim for i ?>
<? } //fim pode ?>