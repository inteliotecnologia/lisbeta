<?
require_once("conexao.php");
if (pode_algum("u", $_SESSION["permissao"])) {
	if ($_GET["id_funcionario"]!="") $id_funcionario= $_GET["id_funcionario"];
	else $id_funcionario= $_POST["id_funcionario"];
?>

<h2>Busca de abastecimento</h2>

<div id="conteudo_interno">
    <fieldset>
        <legend>Formul�rio de busca</legend>
        
        <form action="<?= AJAX_FORM; ?>formAbastecimentoBuscar" method="post" name="formAbastecimentoBuscar" id="formAbastecimentoBuscar" onsubmit="return ajaxForm('conteudo', 'formAbastecimentoBuscar', 'validacoes');">

            <div class="parte50">
                
                <input class="escondido" type="hidden" id="validacoes" value="data@data" />
                <input class="escondido" type="hidden" name="geral" value="<?= $_GET["geral"]; ?>" />
                
                <? if ($_GET["geral"]=="") { ?>
                <label for="id_abastecimento">Requisi��o:</label>
                <input name="id_abastecimento" id="id_abastecimento" class="tamanho25p espaco_dir" value="" title="Requisi��o" />
                <br /><br />
                <? } ?>
                
                <label for="periodo">Per�odo:</label>
                <select name="periodo" id="periodo" title="Per�odo">	  		
					<?
					$i=0;
                    $result_per= mysql_query("select distinct(DATE_FORMAT(data, '%m/%Y')) as data2
												from fi_abastecimentos order by data desc ");
					
                    while ($rs_per= mysql_fetch_object($result_per)) {
						$data= explode('/', $rs_per->data2);
						if ((date("d")>=25) && ($i==0)) {
							$proximo_mes= date("m/Y", mktime(0, 0, 0, $data[0]+1, 1, $data[1]));
							$data2= explode('/', $proximo_mes);
                    ?>
                    <option <? if ($i%2==1) echo "class=\"cor_sim\""; ?> value="<?= $proximo_mes; ?>"><?= traduz_mes($data2[0]) .'/'. $data2[1]; ?></option>
                    <? } ?>
                    <option <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_per->data2; ?>"><?= traduz_mes($data[0]) .'/'. $data[1]; ?></option>
                    <? $i++; } ?>
                </select>
                <br />
                
                <label>&nbsp;</label>
                ou
                <br />
                
                <label for="data1">Datas:</label>
                <input name="data1" id="data1" class="tamanho25p espaco_dir" onfocus="displayCalendar(this, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" value="" title="Data 1" />
                <div class="flutuar_esquerda espaco_dir">�</div>
                <input name="data2" id="data2" class="tamanho25p espaco_dir" onfocus="displayCalendar(this, 'dd/mm/yyyy', this);" onkeyup="formataData(this);" maxlength="10" value="" title="Data 2" />
                <br />
                
                <label for="id_veiculo">Ve�culo:</label>
                <select name="id_veiculo" id="id_veiculo" title="Ve�culo">
                    <option value="">- TODOS -</option>
					<?
                    $result_vei= mysql_query("select *
                                                from  op_veiculos
                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
                                                order by veiculo asc
                                                ") or die(mysql_error());
                    $i=0;
                    while ($rs_vei= mysql_fetch_object($result_vei)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_vei->id_veiculo; ?>"<? if ($rs_vei->id_veiculo==$rs->id_veiculo) echo "selected=\"selected\""; ?>><?= $rs_vei->codigo .") ". $rs_vei->veiculo ." (". $rs_vei->placa .")"; ?></option>
                    <? $i++; } ?>
                </select>
                <br />
                
                <label for="id_funcionario">Motorista:</label>
                <select name="id_funcionario" id="id_funcionario" title="Motorista">
                    <option value="">- TODOS -</option>
                    <?
                    $result_fun= mysql_query("select *
                                                from  pessoas, rh_funcionarios, rh_carreiras
                                                where pessoas.id_pessoa = rh_funcionarios.id_pessoa
                                                and   pessoas.tipo = 'f'
                                                and   rh_funcionarios.status_funcionario = '1'
                                                and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
                                                and   rh_carreiras.atual = '1'
                                                and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
                                                order by pessoas.nome_rz asc
                                                ") or die(mysql_error());
                    $i=0;
                    while ($rs_fun= mysql_fetch_object($result_fun)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_fun->id_funcionario; ?>" <? if ($rs_fun->id_funcionario==$rs->id_funcionario) echo "selected=\"selected\""; ?>><?= $rs_fun->nome_rz; ?></option>
                    <? $i++; } ?>
                </select>
                <br />
                
                <label for="id_departamento">Departamento:</label>
                <select name="id_departamento" id="id_departamento" title="Departamento">
                    <option value="">- TODOS -</option>
                    <?
                    $result_dep= mysql_query("select * from rh_departamentos
                                                where id_empresa = '". $_SESSION["id_empresa"] ."'
                                                 ");
                    $i=0;
                    while ($rs_dep = mysql_fetch_object($result_dep)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_dep->id_departamento; ?>" <? if ($rs_dep->id_departamento==$rs->id_departamento) echo "selected=\"selected\""; ?>><?= $rs_dep->departamento; ?></option>
                    <? $i++; } ?>
                </select>
                <br />
				
                <label for="id_usuario_at">Autorizado por:</label>
                <select name="id_usuario_at" id="id_usuario_at" title="Autorizado por">
                    <option value="">- TODOS -</option>
                    <?
                    $result_fun= mysql_query("select *
                                                from  pessoas, rh_funcionarios, rh_carreiras
                                                where pessoas.id_pessoa = rh_funcionarios.id_pessoa
                                                and   pessoas.tipo = 'f'
                                                and   rh_funcionarios.status_funcionario = '1'
                                                and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
                                                and   rh_carreiras.atual = '1'
                                                and   rh_carreiras.id_departamento = '3'
                                                and   rh_funcionarios.id_empresa = '". $_SESSION["id_empresa"] ."'
                                                order by pessoas.nome_rz asc
                                                ") or die(mysql_error());
                    $i=0;
                    while ($rs_fun= mysql_fetch_object($result_fun)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_fun->id_funcionario; ?>"<? if ($rs_fun->id_funcionario==$rs->id_usuario_at) echo "selected=\"selected\""; ?>><?= $rs_fun->nome_rz; ?></option>
                    <? $i++; } ?>
                </select>
                <br />
                
            </div>
            <br /><br /><br />
            
            <center>
                <button type="submit" id="enviar">Enviar &raquo;</button>
            </center>
        </form>
        
    </fieldset>
</div>

<script language="javascript" type="text/javascript">
	daFoco("id_abastecimento");
</script>

<? } ?>