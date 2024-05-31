<?
if (@pode("r", $_SESSION["permissao"])) {

	if (isset($_SESSION["id_posto_sessao"]))
		$id_cidade= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);
	if (isset($_SESSION["id_cidade_sessao"]))
		$id_cidade= $_SESSION["id_cidade_sessao"];

	$result= mysql_query("select pessoas.id_pessoa, pessoas.nome, pessoas.cpf, pessoas.id_responsavel,
							consultas.*, DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
							consultas.id_usuario as id_med
							from  pessoas, consultas, postos
							where consultas.id_consulta = '$id_consulta'
							and   consultas.id_pessoa = pessoas.id_pessoa
							and   consultas.id_posto = postos.id_posto
							and   postos.id_cidade = '$id_cidade'
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
	
?>

<? if ($fechar==1) { ?>
<a href="javascript:void(0);" onclick="fechaDiv('tela_relatorio');" class="fechar">x</a>
<? } ?>

<h2 class="titulos_mais">Atestado nº <?= $rs->id_consulta; ?></h2>

<br /><br /><br />
<div class="texto_centralizado_meio">
    <p>Atesto para os devidos fins, que o paciente  <strong><?= $rs->nome; ?></strong> necessita afastar-se do trabalho por <strong><?= $rs->dias_atestado; ?></strong> dias - CID <strong><?= pega_cod_cid($rs->diagnostico_inicial); ?></strong>.</p>
    <p><?= pega_cidade($id_cidade); ?>, <?= data_extenso(); ?></p>
</div>

<br /><br /><br /><br /><br />

<div>
    <br /><br /><br /><br />
    <div id="assinatura">
        <?= pega_nome_pelo_id_usuario($rs->id_med); ?>
        <?= pega_crm_pelo_id_usuario($rs->id_med); ?>
    </div>
</div>

<script language="javascript" type="text/javascript">
	//window.print();
</script>

<?
}
else {
	$erro_a= 3;
	include("__erro_acesso.php");
}
?>