<? if (($_SESSION["id_cidade_sessao"]!="") && (@pode("t", $_SESSION["permissao"])) ) { ?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Nova TFD</h2>

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>


<fieldset>
    <legend>Formulário de inserção</legend>

    <form action="<?= AJAX_FORM; ?>formTfdInserir" method="post" id="formTfdInserir" name="formTfdInserir" onsubmit="return ajaxForm('conteudo', 'formTfdInserir');">
        
        <input id="qtde_pessoas" type="hidden" class="escondido" value="0" />
        
        <label for="id_motorista">Motorista:</label>
        <select name="id_motorista" id="id_motorista" class="tamanho200">
            <option value="">--- selecione ---</option>
            <?
            $result_mot= mysql_query("select pessoas.id_pessoa, pessoas.nome from pessoas, tfds_motoristas
                                            where pessoas.id_pessoa = tfds_motoristas.id_pessoa
                                            and   tfds_motoristas.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
                                            order by pessoas.nome asc
                                            ") or die(mysql_error());
            $i=0;
            while ($rs_mot= mysql_fetch_object($result_mot)) {
            ?>
            <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_mot->id_pessoa; ?>"><?= $rs_mot->nome; ?></option>
            <? $i++; } ?>
        </select>	
        <br />
        
        <label for="id_veiculo">Veículo:</label>
        <select name="id_veiculo" id="id_veiculo" class="tamanho200">
            <option value="">--- selecione ---</option>
            <?
            $result_vei= mysql_query("select * from tfds_veiculos
                                            where tfds_veiculos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'
                                            order by veiculo asc
                                            ") or die(mysql_error());
            $i=0;
            while ($rs_vei= mysql_fetch_object($result_vei)) {
            ?>
            <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_vei->id_veiculo; ?>"><?= $rs_vei->veiculo ." - ". $rs_vei->placa; ?></option>
            <? $i++; } ?>
        </select>	
        <br />
        
        <label for="id_cidade">Cidade/UF:</label>
        <div id="id_cidade_atualiza">
            <select name="id_cidade_tfd" id="id_cidade_tfd">
              <option value="">--- selecione ---</option>
              <?
                $result_cid= mysql_query("select id_cidade, cidade, uf from cidades, ufs
                                            where cidades.tfd= '1'
                                            and   cidades.id_uf = ufs.id_uf
                                            order by cidade");
                $i= 0;
                while ($rs_cid= mysql_fetch_object($result_cid)) {
              ?>
              <option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>"><?= $rs_cid->cidade ."/". $rs_cid->uf; ?></option>
              <? $i++; } ?>
            </select>
        </div>
        <br />
        
        <label for="data_partida">Data partida:</label>
        <input name="data_partida" id="data_partida" onkeyup="formataData(this);" maxlength="10" value="<?= date("d/m/Y"); ?>" />
        <br />
        
        <? /*<label for="data_chegada_prevista">Data prevista p/ chegada:</label>
        <input name="data_chegada_prevista" id="data_chegada_prevista" onkeyup="formataData(this);" maxlength="10" />
        <br />*/ ?>
         
        <fieldset>
            <legend>Pacientes</legend>
            
            <label>&nbsp;</label><a href="javascript:void(0);" onclick="adicionaPessoaTfd('p');">adicionar paciente</a>
            <br />
            
            <div id="tfd_p">
                
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Caronas</legend>
            
            <label>&nbsp;</label><a href="javascript:void(0);" onclick="adicionaPessoaTfd('c');">adicionar carona</a>
            <br />
            
            <div id="tfd_c">
            
            </div>
        </fieldset>
        
        <label>&nbsp;</label>
        <button id="botaoInserir" type="submit">Inserir</button>
        <br />
        
    </form>
</fieldset>

<script language="javascript" type="text/javascript">daFoco('id_motorista');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>