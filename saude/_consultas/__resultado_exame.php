<?
if (@pode_algum("ec", $_SESSION["permissao"])) {
	$result= mysql_query("select consultas_exames.*, exames.exame,
								DATE_FORMAT(consultas.data_consulta, '%d/%m/%Y %H:%i:%s') as data_consulta,
								consultas.id_pessoa,
								consultas.id_usuario as id_medico
								from postos, consultas, consultas_exames, exames
								where consultas.id_posto = postos.id_posto
								and   postos.id_cidade = '". pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]) ."'
								and   consultas.id_consulta = consultas_exames.id_consulta
								and   consultas_exames.id_exame = exames.id_exame
								and   consultas_exames.id_consulta_exame = '". $_GET["id_consulta_exame"] ."'
								order by consultas.id_consulta desc
								");
	$rs= mysql_fetch_object($result);
?>


<h2>Resultado de exame</h2>

<a href="javascript:void(0);" onclick="fechaDiv('tela_aux_rapida');" class="fechar">x</a>

<div id="formulario">
    <form action="<?= AJAX_FORM; ?>formResultadoExame" method="post" id="formResultadoExame" name="formResultadoExame" onsubmit="return ajaxForm('formulario', 'formResultadoExame');">
        <input name="id_consulta_exame" id="id_consulta_exame" class="escondido" type="hidden" value="<?= $rs->id_consulta_exame; ?>" />
        
        <label>Paciente:</label>
        <?= pega_nome($rs->id_pessoa); ?>
        <br />
        
        <label>Médico (prescreveu):</label>
        <?= pega_nome_pelo_id_usuario($rs->id_medico); ?>
        <br />
        
        <label>Data consulta:</label>
        <?= $rs->data_consulta; ?>
        <br />
        
        <label>Exame:</label>
        <?= $rs->exame; ?>
        <br />
        
        <label for="resultado">Resultado:</label>
        <textarea name="resultado" id="resultado"><?= $rs->resultado; ?></textarea>
        <br />
    
        <label>&nbsp;</label>
        <button type="submit">Enviar</button>
    </form>
</div>
        
<script language="javascript" type="text/javascript">daFoco('resultado');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>