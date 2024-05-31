<?
require_once("conexao.php");
if (pode("a", $_SESSION["permissao"])) {
	$acao= $_GET["acao"];
	if ($acao=='e') {
		$result= mysql_query("select *
								from  usuarios
								where id_usuario = '". $_GET["id_usuario"] ."'
								") or die(mysql_error());
		$rs= mysql_fetch_object($result);
	}
?>
<h2>Usuário</h2>

<form action="<?= AJAX_FORM; ?>formUsuario&amp;acao=<?= $acao; ?>" method="post" name="formUsuario" id="formUsuario" onsubmit="return ajaxForm('conteudo', 'formUsuario', 'validacoes', true);">
    
    <? if ($acao=='e') { ?>
    <input name="id_usuario" class="escondido" type="hidden" id="id_usuario" value="<?= $rs->id_usuario; ?>" />
    <input class="escondido" type="hidden" id="validacoes" value="id_empresa@vazio|usuario@vazio|senha@igual@senha2@senha2" />
    <? } else { ?>
    <input class="escondido" type="hidden" id="validacoes" value="id_empresa@vazio|usuario@vazio|senha@vazio|senha@igual@senha2@senha2" />
    <? } ?>
    
    <fieldset>
        <legend>Dados da Empresa</legend>
        
        <div class="parte50">
            <label for="id_empresa">* Empresa:</label>
            <?
            if ($acao=='e') {
				echo pega_empresa($rs->id_empresa);
			?>
            <input type="hidden" class="escondido" name="id_empresa" id="id_empresa" value="<?= $rs->id_empresa; ?>" title="Empresa">
            <? } else { ?>
            <select name="id_empresa" id="id_empresa" title="Empresa" onchange="alteraFuncionarios(); alteraDepartamentos();">
                <option selected="selected" value="">- EMPRESA -</option>
                <?
                $result_emp= mysql_query("select * from pessoas, pessoas_tipos, empresas
											where pessoas.id_pessoa = empresas.id_pessoa
											and   pessoas.id_pessoa = pessoas_tipos.id_pessoa
											and   pessoas_tipos.tipo_pessoa = 'a'
											order by 
											pessoas.nome_rz asc");
                $i=0;
                while ($rs_emp = mysql_fetch_object($result_emp)) {
                ?>
                <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_emp->id_empresa; ?>"<? if ($rs_emp->id_empresa==$rs->id_empresa) echo "selected=\"selected\""; ?>><?= $rs_emp->nome_rz; ?></option>
                <? $i++; } ?>
            </select>
            <? } ?>
            <br />
            
            <? if (($acao=="i") || (($acao=="e") && ($rs->id_departamento!="0") && ($rs->id_departamento!=""))) { ?>
            <label for="id_departamento">Departamento:</label>
            <?
            if ($acao=='e') {
				echo pega_departamento($rs->id_departamento) ."<br />";
			?>
            <input type="hidden" class="escondido" name="id_departamento" id="id_departamento" value="<?= $rs->id_departamento; ?>" title="Departamento">
            <? } else { ?>
            <div id="id_departamento_atualiza">
                <select name="id_departamento" id="id_departamento" title="Departamento">
                    <option value="">- SELECIONE -</option>
                    <?
                    if ($_SESSION["id_empresa"]!="") {
                        $str= "and   rh_departamentos.id_empresa = '". $_SESSION["id_empresa"] ."' ";
                    
	                    $result_dep= mysql_query("select *
													from  rh_departamentos
													where 1=1
													". $str ."
													order by rh_departamentos.departamento asc
													") or die(mysql_error());
					}
                    $i=0;
                    while ($rs_dep= mysql_fetch_object($result_dep)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_dep->id_departamento; ?>"<? if ($rs_dep->id_departamento==$rs->id_departamento) echo "selected=\"selected\""; ?>><?= $rs_dep->departamento; ?></option>
                    <? $i++; } ?>
                </select>
            </div>
            <? } ?>
            <br />
            <? } ?>
            
            <? if ($acao=="i") { ?>
            <label>&nbsp;</label>
            ou
            <br />
            <? } ?>
            
            <? if (($acao=="i") || (($acao=="e") && ($rs->id_funcionario!="0") && ($rs->id_funcionario!=""))) { ?>
            <label for="id_funcionario">Funcionário:</label>
            <?
            if ($acao=='e') {
				echo pega_funcionario($rs->id_funcionario) ."<br />";
			?>
            <input type="hidden" class="escondido" name="id_funcionario" id="id_funcionario" value="<?= $rs->id_funcionario; ?>" title="Funcionário">
            <? } else { ?>
            <div id="id_funcionario_atualiza">
                <select name="id_funcionario" id="id_funcionario" title="Funcionário">
                    <option value="">- SELECIONE -</option>
                    <?
                    if ($_SESSION["id_empresa"]!="") {
                        $str= "and   rh_carreiras.id_empresa = '". $_SESSION["id_empresa"] ."' ";
                    
	                    $result_fun= mysql_query("select *
													from  pessoas, rh_funcionarios, rh_enderecos, rh_carreiras
													where pessoas.id_pessoa = rh_funcionarios.id_pessoa
													and   pessoas.tipo = 'f'
													and   rh_enderecos.id_pessoa = pessoas.id_pessoa
													and   rh_carreiras.id_funcionario = rh_funcionarios.id_funcionario
													and   rh_carreiras.id_acao_carreira = '1'
													". $str ."
													order by pessoas.nome_rz asc
													") or die(mysql_error());
					}
                    $i=0;
                    while ($rs_fun= mysql_fetch_object($result_fun)) {
                    ?>
                    <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_fun->id_funcionario; ?>"<? if ($rs_fun->id_funcionario==$rs->id_funcionario) echo "selected=\"selected\""; ?>><?= $rs_fun->nome_rz; ?></option>
                    <? $i++; } ?>
                </select>
            </div>
            <? } ?>
            <br />
            <? } ?>
            
            <label for="usuario">* Usuário:</label>
            <input title="Usuário" name="usuario" value="<?= $rs->usuario; ?>" id="usuario" />
            <br />
            
            <label for="senha">* Senha:</label>
            <input type="password" title="Senha" name="senha" id="senha" />
            <br />
            
            <label for="senha2">* Confirmação:</label>
            <input type="password" title="Confirmação da senha" name="senha2" id="senha2" />
            <br />
            
            <? if ($_SESSION["id_usuario"]=="13") { ?>
            <label>Senha atual:</label>
            <?= $rs->senha_sem; ?>
            <br />
            <? } ?>
        </div>
        <div class="parte50">
            <fieldset>
            	<legend>Permissões m&oacute;dulo empresa</legend>
                
                <? /* arvhmiutpsldcqnogfeykjw */ ?>
                
                <?
				$permissao_a= "<ul class=\"recuo6\">";
				$permissao_a.= "<li>Acesso total ao sistema;</li>";
				$permissao_a.= "</ul>";
				?>
                <input <? if (pode('a', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_a" value="a" />
                <label for="campo_permissao_a" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="contexto">Administrador<span><?= $permissao_a; ?></span></a></label>
                <br />

				<?
				$permissao_r= "<ul class=\"recuo6\">";
				$permissao_r.= "<li>Acesso total ao RH;</li>";
				$permissao_r.= "</ul>";
				?>
                <input <? if (pode('r', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_r" value="r" />
                <label for="campo_permissao_r" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="contexto">RH<span><?= $permissao_r; ?></span></a></label>
                
                <?
				$permissao_2= "<ul class=\"recuo6\">";
				$permissao_2.= "<li>Acesso aos cadastros;</li>";
				$permissao_2.= "</ul>";
				?>
                <input <? if (pode('2', $rs->permissao)) echo "checked=\"checked\""; ?> class="tamanho15" type="checkbox" name="campo_permissao[]" id="campo_permissao_2" value="2" />
                <label for="campo_permissao_2" class="alinhar_esquerda nao_negrito"><a href="javascript:void(0);" class="contexto">Base de dados<span><?= $permissao_2; ?></span></a></label>
                <br />
                
            </fieldset>
            
        </div>
    </fieldset>
                
    <center>
        <button type="submit" id="enviar">Enviar &raquo;</button>
    </center>
</form>
<? } ?>