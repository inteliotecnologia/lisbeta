<?
if (@pode_algum("ceim", $_SESSION["permissao"])) {
	$result= mysql_query("select *, DATE_FORMAT(data_nasc, '%d/%m/%Y') as data_nasc,
							DATE_FORMAT(ultima_menstruacao, '%d/%m/%Y') as ultima_menstruacao
							from acompanhamento, pessoas
							where acompanhamento.id_acompanhamento = '". $_GET["id_acompanhamento"] ."'
							and   acompanhamento.id_pessoa = pessoas.id_pessoa
							and   acompanhamento.id_posto = '". $_SESSION["id_posto_sessao"] ."'
							") or die(mysql_error());
	$rs_paciente= mysql_fetch_object($result);
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Editar acompanhamento</h2>
<br />

<form action="<?= AJAX_FORM; ?>formAcompEditar" method="post" id="formAcompEditar" name="formAcompEditar" onsubmit="return ajaxForm('conteudo', 'formAcompEditar');">
	
    <input name="id_acompanhamento" class="escondido" type="hidden" value="<?= $rs_paciente->id_acompanhamento; ?>" />
    
    <fieldset>
        <legend>Novo acompanhamento</legend>
        
        <div class="parte40">
            
            <label>Local:</label>
            <?
            if ($_SESSION["id_posto_sessao"]!="") {
                $local= pega_posto($_SESSION["id_posto_sessao"]);
                $ident_local= 'p';
            }
            if ($_SESSION["id_cidade_sessao"]!="") {
                $local= pega_cidade($_SESSION["id_cidade_sessao"]);
                $ident_local= 'c';
            }
            
            echo $local;
            ?>
            <br />
            
            <label>Nome:</label>
            <?= $rs_paciente->nome; ?>
            <br/>
            
            <?
            $idade= calcula_idade($rs_paciente->data_nasc);
            if (!is_int($idade) || ($idade<0))
                echo "<script language='javascript'>alert('Clique em \'editar dados\' e corrija a data de nascimento! Consta como \'". $rs->data_nasc ."\' !');</script>";
        
            if ($idade<=6) $tipo_acompanhamento= "c";
            else {
                if (($idade>=7) && ($idade<20)) $tipo_acompanhamento= "a";
                else {
                    if (($idade>=20) && ($idade<60)) $tipo_acompanhamento= "d";
                    else $tipo_acompanhamento= "i";
                }
            }
            $meses= calcula_meses($rs_paciente->data_nasc);
            
			echo "<input type=\"hidden\" class=\"escondido\" id=\"tipo_acompanhamento\" name=\"tipo_acompanhamento\" value=\"". $rs_paciente->tipo_acompanhamento ."\" />";
			echo "<input type=\"hidden\" class=\"escondido\" id=\"idade_paciente\" name=\"idade\" value=\"". $idade ."\" />";
			echo "<input type=\"hidden\" class=\"escondido\" id=\"meses_paciente\" name=\"meses\" value=\"". $meses ."\" />";
			echo "<input type=\"hidden\" class=\"escondido\" id=\"sexo\" name=\"sexo\" value=\"". $rs_paciente->sexo ."\" />";
			
            echo "<label>Data nasc.:</label> ". $rs_paciente->data_nasc ." <br />";
            echo "<label>Idade:</label> ". $idade ." anos";
            
            if ($idade<7) echo " (". $meses ." meses) ";
            echo "<br />";
            
            echo "<label>Sexo:</label> ". pega_sexo($rs_paciente->sexo) ." <br />";
            ?>
	     </div>
         <div class="parte60">
         	<fieldset id="fieldset_acomp">
            	<legend>Acompanhamento</legend>
                
                <div id="acompanhamento_ac">
                	<?
                    $pressao1= $rs_paciente->pressao1;
					$pressao2= $rs_paciente->pressao2;
					
					require_once("_acomp/__acomp.php");
					?>
                </div>
                
            </fieldset>
         </div>
         
         <br /><br /><br />
         
        <center>
            <button id="botaoInserir" type="submit">Editar</button>
        </center>
        <br />
        
    </fieldset>
    
</form>

<script language="javascript" type="text/javascript">daFoco('cpf_usuario');</script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>