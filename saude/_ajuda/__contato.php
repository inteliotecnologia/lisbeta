<? if ($_SESSION["id_usuario_sessao"]!="") { ?>
<div id="tela_mensagens2">
<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Entre com contato conosco</h2>

<div class="parte_esquerda">
	
	<br />
	
	<h4>Suporte via e-mail: suporte@intelio.com.br</h4>
	
	<? /*<form action="<?= AJAX_FORM ?>formContato" method="post" name="formContato" id="formContato" onsubmit="return ajaxForm('conteudo', 'formContato');">
		<p>Preencha os campos abaixo e submeta o formul�rio que deste modo estaremos recebendo sua mensagem.</p>
		<br />
		
		<label>Nome:</label>
		<input name="nome" id="nome" value="<?= $_SESSION["nome_pessoa_sessao"]; ?>" onmouseover="Tip('Informe seu nome.');" />
		<br />

		<label>E-mail:</label>
		<input name="email" id="email" onmouseover="Tip('Informe seu e-mail, para que possamos entrar em contato.');"  />
		<br />

		<label>Telefone:</label>
		<input name="telefone" id="telefone" onmouseover="Tip('Informe seu telefone, caso necess�rio.');" />
		<br />

		<label>Cidade:</label>
		<input name="cidade" id="cidade" onmouseover="Tip('Informe sua cidade, caso necess�rio.');" />
		<br />

		<label>Tipo:</label>
		<select name="tipo_contato" id="tipo_contato" onmouseover="Tip('Selecione o tipo do contato.');" />
			<option value="">--- selecione ---</option>
			<option value="D�vida">D�vida</option>
			<option value="Sugest�o">Sugest�o</option>
			<option value="Reclama��o">Reclama��o</option>
			<option value="Reportar problema">Reportar problema</option>
		</select>
		<br />
		
		<label>�rea:</label>
		<select name="area_contato" id="area_contato" onmouseover="Tip('Selecione a �rea do sistema referente ao contato.');" />
			<option value="">--- selecione ---</option>
			<option value="Prontu�rio">Prontu�rio</option>
			<option value="Consultas">Consultas</option>
			<option value="Cadastro de pessoas">Cadastro de pessoas</option>
			<option value="Almoxarifado">Almoxarifado</option>
			<option value="Consultas">Consultas</option>
			<option value="Rem�dios">Rem�dios</option>
			<option value="Exames">Exames</option>
			<option value="Outros">Outros</option>
		</select>
		<br />

		<label>Mensagem:</label>
		<textarea name="mensagem" id="mensagem" onmouseover="Tip('Digite a mensagem.');"></textarea>
		<br />
		
		<label>&nbsp;</label>
		<button type="submit">Enviar</button>
		<br />
		
	</form> */ ?>
</div>

<div class="parte_direita">
	<? /*
	Se preferir entrar em contato individualmente, clique nos links abaixo:
	
	<ul class="recuo1">
		<li>
			<span class="texto_destaque">Jaison Niehues</span> (48) 9985.3850 <br />
			<a href="mailto:jaison@lisbeta.net">jaison@lisbeta.net</a> <br />
			Bacharel em Ci�ncia da Computa��o
		</li>
		<li>
			<span class="texto_destaque">Rosivete Coan Niehues</span> (48) 9996.6496 <br />
			<a href="mailto:rosivete@lisbeta.net">rosivete@lisbeta.net</a> <br />
			MSc. em Engenharia Biom�dica
		</li>
	</ul> */ ?>
</div>
<? } ?>