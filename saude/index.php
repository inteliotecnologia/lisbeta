<?
require_once("conexao.php");
if (!isset($pagina)) {
	switch($_SESSION["tipo_usuario_sessao"]) {
		case 'a':
			$pagina= "_acesso/cidade_listar";
			break;
		case 'c':
		case 'p':
			$pagina= "principal";
			if (pode("t", $_SESSION["permissao"]))
				$pagina= "_tfd/tfd_listar";
			if (pode("x", $_SESSION["permissao"]))
				$pagina= "_almox/estoquem_listar";
			if (pode("f", $_SESSION["permissao"]))
				$pagina= "_farmacia/estoque_listar";
			break;
		default:
			$pagina= "principal";
			break;
	}
}
if ($_SESSION["id_cidade_sessao"]!="")
	$id_cidade_emula= $_SESSION["id_cidade_sessao"];
else
	$id_cidade_emula= pega_id_cidade_do_posto($_SESSION["id_posto_sessao"]);

$cidade= pega_cidade($id_cidade_emula);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? include("titulo.php"); ?></title>

<link rel="stylesheet" type="text/css" media="screen" href="estilo.css" />
<link rel="stylesheet" type="text/css" media="print" href="estilo_print.css" />
<link rel="shortcut icon" href="images/icone.png" />

<script language="javascript" type="text/javascript" src="js/validacoes.js"></script>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>

<script language="javascript" type="text/javascript" src="js/calendar/calendar.js?random=20060118"></script>
<link rel="stylesheet" type="text/css" media="screen" href="js/calendar/calendar.css" />

</head>

<body class="sistema">
	<script language="javascript" type="text/javascript" src="js/wz_tooltip.js"></script>
	<input type="hidden" class="escondido" name="pagina" id="pagina" value="<?= $pagina; ?>" />
	<noscript>
		<meta http-equiv="Refresh" content="1; url=index2.php?pagina=erro" />
	</noscript>

	<div id="corpo">
		<div id="topo">
        	<div id="cabecalho_impressao">
            	<h2>S.M.S. de <?= $cidade; ?></h2>
                <h2>RELATÓRIO OFICIAL</h2>
				<h2>Data: <?= date("d/m/Y"); ?></h2>
            </div>
            
            <? if (($_SESSION["id_cidade_sessao"]=="8227") || ($_SESSION["id_cidade_sessao"]=="8465")) { ?>
            <div class="aviso">
            	Seu acesso a este sistema será cancelado <strong>em breve</strong>! <br />
                Entre em contato com a equipe Lisbeta para regularizar a situação de seu município: <strong>48 9985.3850</strong>.
            </div>
            <? } ?>
            
			<div id="logo_cidade">
				<?
                if (file_exists("images/brasao_". $id_cidade_emula .".gif"))
                    echo "<img src=\"images/brasao_". $id_cidade_emula .".gif\" alt=\"Brasão\" />";
                ?>
			</div>
			<div id="logo_lisbeta">
				<img src="images/logo.png" alt="Lisbeta" />
			</div>
			<div id="sair">
				<a href="javascript:void(0);" onclick="window.print();"><img src="images/m_imprimir.png" alt="Imprimir" /></a>
				<? if (($_SESSION["tipo_usuario_sessao"]=='a') || ($_SESSION["id_cidade_sessao"]!="") || ($_SESSION["trocando"]!="")) { ?>
				<a href="javascript:void(0);" onclick="abreDivSo('emula_posto');" title="Trocar posto"><img src="images/m_trocar.png" alt="Trocar" /></a>
				<? } ?>				
				<a href="index2.php?pagina=logout"><img src="images/m_sair.png" alt="Sair" /></a>
			</div>

			<div id="infos">
				<? if (($_SESSION["tipo_usuario_sessao"]=='a') || ($_SESSION["id_cidade_sessao"]!="") || ($_SESSION["trocando"]!="")) { ?>
				<div id="emula_posto">
					<a href="javascript:void(0);" onclick="fechaDiv('emula_posto');" class="fechar">x</a>
					
					<h2>Trocar cidade/posto</h2>
							
					<form action="<?= AJAX_FORM; ?>formPostoEmular" id="formPostoEmular" name="formPostoEmular" method="post" onsubmit="return false;">
						
                        <? if ($_SESSION["tipo_usuario_sessao"]=='a') { ?>
                        <label class="tamanho50" for="id_cidade_em">Cidade:</label>
						<select name="id_cidade_em" id="id_cidade_em" onchange="retornaPostos();">
						  <option value="">--- selecione ---</option>
						<?
						$result_cid= mysql_query("select cidades.id_cidade, cidades.cidade, ufs.uf from cidades, ufs
													where cidades.id_uf = ufs.id_uf 
													and   cidades.sistema = '1'
													order by cidades.cidade asc ");
						$i= 0;
						while ($rs_cid= mysql_fetch_object($result_cid)) {
						?>
						<option <? if (($i%2)==0) echo "class=\"cor_sim\""; ?> value="<?= $rs_cid->id_cidade; ?>" <? if ($id_cidade_emula==$rs_cid->id_cidade) echo "selected=\"selected\""; ?>><?= $rs_cid->cidade ."/". $rs_cid->uf; ?></option>
						<? $i++; } ?>
						</select>
						<br />
                        <?
                        }
						else
							echo "
							<input name=\"id_cidade_em\" id=\"id_cidade_em\" type=\"hidden\" class=\"escondido\" value=\"". $id_cidade_emula ."\" />
							<p class=\"alinhar_esquerda\">Selecione um local:</p>";
						?>
                        
						<label class="tamanho50" for="id_posto_em">Local:</label>
						<div id="id_posto_atualiza">
							<select name="id_posto_em" id="id_posto_em">
								<option value="">--- NENHUM ---</option>
								<?
								$result_postos= mysql_query("select postos.id_posto, postos.posto from postos, cidades
															where cidades.id_cidade = postos.id_cidade
															and   cidades.id_cidade = '". $id_cidade_emula ."'
															and   postos.situacao = '1'
															order by cidade");
								while($rs_postos= mysql_fetch_object($result_postos)) {
									if (($i%2)==0)
										$classe= "class=\"cor_sim\"";
									else
										$classe= "";
									
									if ($_SESSION["id_posto_sessao"]==$rs_postos->id_posto)
										$selecionavel= " selected=\"selected\" ";
									else
										$selecionavel= "";
								?>
								<option <?= $classe; ?> value="<?= $rs_postos->id_posto; ?>" <? if ($_SESSION["id_posto_sessao"]==$rs_postos->id_posto) echo "selected=\"selected\""; ?>><?= $rs_postos->posto; ?></option>
								<? $i++; } ?>
							</select>
						</div>
						<br /><br />
						
						<label class="tamanho50">&nbsp;</label>
						<button type="submit" onclick="return ajaxForm('menu', 'formPostoEmular');">Emular</button>
					</form>
				</div>

				<? } ?>
			</div>		
		</div>
		<div id="menu">
			<? include("__menu.php"); ?>
		</div>
		<div id="conteudo">
			<?
			include("__submenu.php");
			
			$paginar= $pagina;
			if (strpos($paginar, "/")) {
				$parte= explode("/", $paginar);
				include($parte[0] ."/". "__". $parte[1] .".php");
			}
			else
				include("__". $paginar .".php");
			?>
		
		</div>
		<br /><br />&nbsp;
		<div id="rodape">
			<address class="sistema">
				Todos os direitos reservados a Intelio Tecnologia.<br />
				Copyright 2006-<?= date("Y"); ?> &copy; Cópia proibida
			</address>
            <address class="relatorio">
				<em>Todos os direitos reservados à Intelio Tecnologia.<br />
				Relatório gerado por:&nbsp;&nbsp;<strong><?= $_SESSION["nome_pessoa_sessao"]; ?></strong></em>&nbsp;&nbsp;| http://www.wblz.com.br/
			</address>
		</div>
	</div>
<?
if (isset($_GET["ctrl"])) {
	switch ($_GET["ctrl"]) {
		case 1: $msg= "ATENÇÃO!!!\\n\\nSe você estava realizando alguma operação,certifique-se que ela\\nfoi realizada ou não e tome as devidas providências!";
				break;
		default: $msg= "Curioso ;P";
				break;
	}
?>
<script language="javascript" type="text/javascript">alert('<?= $msg; ?>');</script>
<? } ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-801754-23");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body>
</html>
