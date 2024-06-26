<?
require_once("conexao.php");
require_once("funcoes.php");
require_once("includes/Browser.php");

$browser = new Browser();

if (!isset($_GET["pagina"])) $pagina= "principal";
else $pagina= $_GET["pagina"];
//echo "i". $_SESSION["id_empresa"];
$empresa= pega_empresa($_SESSION["id_empresa"]);

if (($_GET["pagina"]=="qualidade/reclamacao_listar") && ($_GET["num_pagina"]=="") && ($_SESSION["reclamacao_ancora"]!="") && ($_GET["redirecionado"]=="")) {
	if ($_SESSION["reclamacao_num_pagina"]!="")
		header("location: ./?pagina=qualidade/reclamacao_listar&motivo=". $_SESSION["reclamacao_origem"] ."&redirecionado=1&num_pagina=". $_SESSION["reclamacao_num_pagina"] ."#reclamacao_". $_SESSION["reclamacao_ancora"]);
	else
		header("location: ./?pagina=qualidade/reclamacao_listar&motivo=". $_SESSION["reclamacao_origem"] ."&redirecionado=1#reclamacao_". $_SESSION["reclamacao_ancora"]);
}

if (($_GET["pagina"]=="com/livro") && ($_GET["voltando"]!="")) {
	header("location: ./?pagina=com/livro&data=". $_SESSION["livro_reclamacao_data"] ."#livro_". $_SESSION["livro_reclamacao_ancora"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><? include("titulo.php"); ?> <?= $empresa; ?></title>
    
    <link rel="stylesheet" type="text/css" media="screen" href="estilo.css" />
    <link rel="stylesheet" type="text/css" media="print" href="estilo_print.css" />
    <link rel="shortcut icon" href="images/ico.png" />

    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="estilo_ie6.css" />
	<![endif]-->
    
    <script language="javascript" type="text/javascript" src="js/jquery-1.7.min.js"></script>
    
    <script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
    <script language="javascript" type="text/javascript" src="js/ajax.js"></script>
    <script language="javascript" type="text/javascript" src="js/menu.js"></script>
    <script language="javascript" type="text/javascript" src="js/shortcut.js"></script>
    <script language="javascript" type="text/javascript" src="js/sortable.js"></script>
    
    <link rel="stylesheet" type="text/css" media="screen" href="js/calendar/calendar.css" />
	<script language="javascript" type="text/javascript" src="js/calendar/calendar.js?random=20060118"></script>
    
    <? /*if ($browser->getBrowser()!=Browser::BROWSER_IPAD) { ?>
    <script language="javascript" type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript" src="js/config_editor.js"></script>
	<? }*/ ?>
    
</head>

<body onLoad="horizontal();" class="sistema">
	<script language="javascript" type="text/javascript" src="js/wz_tooltip.js"></script>
	<noscript>
		<meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>

	<div id="corpo">
    	<div id="ajax_rotina" class="escondido"></div>
		<div id="topo">
        	<div id="logo">
            	Cadastro :: Comit� 55
			</div>
            
            <? if ($_SESSION["id_empresa"]!="") { ?>
            <div id="logo_empresa" class="print">
            	<img src="<?= CAMINHO; ?>empresa_<?= $_SESSION["id_empresa"] ;?>.jpg" alt="Logo empresa" />
			</div>
            <? } ?>
            
			<div id="opcoes" class="screen">
            	<? if ($_SESSION["tipo_usuario"]=='a') { ?>
				<a href="javascript:void(0);" onclick="abreDiv('emula_empresa');">Trocar empresa</a> |
                <? } ?>
                
                
                <a href="javascript:void(0);" onclick="window.print();">Imprimir</a> |
				
                <?
				if (($_SESSION["id_departamento_sessao"]=="1") && ($_SESSION["id_turno_sessao"]!="-3")) {
					$link_sair= "fechar";
					$pagina_sair= "index";
				}
				else {
					$link_sair= "logout";
					$pagina_sair= "index2";
				}
				?>
                <a href="<?=$pagina_sair;?>.php?pagina=<?=$link_sair;?>">Sair (Q)</a>
			</div>
			
            <? if ($_SESSION["tipo_usuario"]=='a') { ?>
            <div id="emula_empresa" class="telinha1 screen">
                <a href="javascript:void(0);" onclick="fechaDiv('emula_empresa');" class="fechar">x</a>
                
                <h2>Trocar empresa</h2>
                <br />
                
                <form action="<?= AJAX_FORM; ?>formEmpresaEmular" id="formEmpresaEmular" name="formEmpresaEmular" method="post">
                    
                    <label for="id_empresa_emula">Empresa:</label>
                    <select name="id_empresa_emula" id="id_empresa_emula" title="Empresa">
                        <option selected="selected" value="">- NENHUMA -</option>
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
                        <option  <? if ($i%2==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_emp->id_empresa; ?>"<? if ($rs_emp->id_empresa==$_SESSION["id_empresa"]) echo "selected=\"selected\""; ?>><?= $rs_emp->nome_rz; ?></option>
                        <? $i++; } ?>
                    </select>
                    <br />
                    
                    <br />
                    
                    <label>&nbsp;</label>
                    <button type="submit">Emular</button>
                </form>
            </div>
            <? } ?>
			<div id="infos">

			</div>		
		</div>
        <div id="infos_mesmo">
			
            
            <strong>Usu�rio:</strong> <?= $_SESSION["nome"]; ?> <br />
            <? if ($_SESSION["id_empresa"]!="") { ?>
            <strong>Empresa:</strong> <?= $empresa; ?> <br />
            <? } ?>
            <? if ( ($_SESSION["id_turno_sessao"]!="") && ($_SESSION["id_turno_sessao"]!="-4") ) { ?>
            <strong>Turno:</strong> <?= str_replace(" 1", "", pega_turno($_SESSION["id_turno_sessao"])); ?>
            <? } ?>
        </div>
		<div id="menu">
			<? include("__menu.php"); ?>
		</div>
		<div id="conteudo">
			<?
			$paginar= $pagina;
			if (strpos($paginar, "/")) {
				$parte_pagina= explode("/", $paginar);
				
				if (file_exists("_". $parte_pagina[0] ."/". "__". $parte_pagina[1] .".php"))
					include("_". $parte_pagina[0] ."/". "__". $parte_pagina[1] .".php");
				else
					echo "<h2>Erro</h2><p>P�gina n�o encontrada!</p>";
			}
			else {
				if (file_exists("__". $paginar .".php"))
					include("__". $paginar .".php");
				else
					echo "<h2>Erro</h2><p>P�gina n�o encontrada!</p>";
			}
			?>
		
		</div>
		
		<div id="rodape">
			<address class="sistema">
				<?= VERSAO; ?> <br />
                
			</address>
            <address class="relatorio">
				Relat�rio gerado por:&nbsp;&nbsp;<strong><?= $_SESSION["nome_fantasia"]; ?></strong></em>
			</address>
		</div>
	</div>
<?
if (isset($_GET["ctrl"])) {
	switch ($_GET["ctrl"]) {
		case 1: $msg= "ATEN��O!!!\\n\\nSe voc� estava realizando alguma opera��o, certifique-se que ela\\nfoi realizada ou n�o e tome as devidas provid�ncias!";
				break;
		default: $msg= "Curioso ;P";
				break;
	}
?>
<script language="javascript" type="text/javascript">alert('<?= $msg; ?>');</script>
<? } /* ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-801754-23");
pageTracker._initData();
pageTracker._trackPageview();
</script>
*/ ?>
</body>
</html>

<? $fecha= mysql_close($conexao); ?>
