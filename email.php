<?
$enviado= mail("jaisonn@gmail.com", "PARTICIPE DÁRIO 15 | ". $_POST["nome"],
					"
					<b>IP:</b> $REMOTE_ADDR <br />
					<b>Nome:</b> ". $_POST["nome"] ." <br />
					<b>E-mail:</b> ". $_POST["email"] ." <br />
					<b>Telefone:</b> ". $_POST["telefone"] ." <br />
					<b>Cidade:</b> ". $_POST["cidade"] ." <br />
					<b>Assunto:</b> ". $_POST["assunto"] ." <br />
					<b>Mensagem:</b> ". nl2br($_POST["mensagem"]) ."
					<br /><br />
					------
					<br /><br />
					". NOME ."<br />
					<a href=\"". URL ."\">". URL ."</a>
					",
					"From: $nome <$email> \nContent-type: text/html\n");
	
if ($enviado) $msg= "contato-enviado";
else $msg= "contato-nao-enviado";

echo $msg;
?>