<? if (@pode("p", $_SESSION["permissao"])) { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<div id="busca">
	<form action="<?= AJAX_FORM; ?>formProducaoPeriodo" method="post" id="formProducaoPeriodo" name="formProducaoPeriodo" onsubmit="return ajaxForm('conteudo', 'formProducaoPeriodo');">

		<label class="tamanho100" for="periodo">Período:</label>

		<select name="periodo" id="periodo" class="tamanho80">
			<?
			$result_per= mysql_query("select distinct mes, ano from ssa2_dados");
			while ($rs_per= mysql_fetch_object($result_per)) {
				$periodo= $rs_per->mes ."/". $rs_per->ano;
			?>
			<option value="<?= $periodo; ?>" <? if ($_POST["periodo"]==$periodo) echo "selected=\"selected\""; ?>><?= $periodo; ?></option>
			<? } ?>
		</select>	

		<button>Buscar</button>
	</form>
</div>

<h2 class="titulos">Produção - Alterar situação</h2>

<p>Esta página serve para você, digitadora de produção, fechar o acesso aos relatórios mensais dos demais PSF's, sendo que os dados do mês já foram digitados e colhidos.</p>

<label class="tamanho200">Produção liberada para os PSF's:</label>

<?
echo sim_nao(pega_status_producao_mes($_SESSION["id_cidade_sessao"], date("m"), date("Y")));
?>

<a href="javascript:void(0);" onclick="ajaxLink('conteudo', 'producaoStatusTrocar');"><img src="images/ico_troca.gif" alt="Alterar situação da produção" /></a>


<br />
<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>