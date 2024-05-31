/* FUNCOES PARA VALIDAR VALORES GENÉRICOSSSS */

function alteraTitulo() {
	document.title= "Lsbt";
}

function confirmaAlert() {
	
}

function profissaoCadastroOk() {
	var profissao= document.getElementById("profissao");
		
	if (profissao.value!="") {
		var teste= confirm('Tem certeza que deseja cadastrar esta profissão?!');
		
		if (teste)
			ajaxLink("profissao_cadastro3", "profissaoInserir&profissao="+profissao.value);
	}
	else {
		alert("Preencha o campo profissão!");
		daFoco('profissao');
	}
}

function ajeitaTecla(evtKeyPress) {
	if (document.all) { // Internet Explorer
		nTecla = evtKeyPress.keyCode;
		} else if(document.layers) { // Nestcape
			nTecla = evtKeyPress.which;
		} else {
			nTecla = evtKeyPress.which;
			if (nTecla == 8) {
				return true;
			}
		}
	
	if (((nTecla > 47) && (nTecla < 58)) || (nTecla==0) || (nTecla==8))
		return(true);
	else
		return(false);
}

function validaEmail(email) {
	var retorno= true;
	
	if (email=="")
		retorno= false;
	if (email.indexOf("@") < 2)
		retorno= false;
	if (email.indexOf(".") < 1)
		retorno= false;
	
	return(retorno);
}

function sohNumeros(numero) {
	var nonNumbers = /\D/;
	if (nonNumbers.test(numero))
		return(false);
	else
		return(true);
}

function limpaValor(valor, validos) {
	var result = "";
	var aux;
	for (var i=0; i < valor.length; i++) {
		aux = validos.indexOf(valor.substring(i, i+1));
		if (aux>=0)
			result += aux;
	}
	return result;
}

//onkeydown="formataValor(this,event);"
function formataValor(campo, teclapres) {
	var tammax = 200;
	var decimal = 2;
	var tecla = teclapres.keyCode;
	vr = limpaValor(campo.value,"0123456789");
	tam = vr.length;
	dec=decimal
	
	if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }
	
	if (tecla == 8 )
	{ tam = tam - 1 ; }
	
	if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 )
	{
	
	if ( tam <= dec )
	{ campo.value = vr ; }
	
	if ( (tam > dec) && (tam <= 5) ){
	campo.value = vr.substr( 0, tam - 2 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 6) && (tam <= 8) ){
	campo.value = vr.substr( 0, tam - 5 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ;
	}
	if ( (tam >= 9) && (tam <= 11) ){
	campo.value = vr.substr( 0, tam - 8 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 12) && (tam <= 14) ){
	campo.value = vr.substr( 0, tam - 11 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - dec, tam ) ; }
	if ( (tam >= 15) && (tam <= 17) ){
	campo.value = vr.substr( 0, tam - 14 ) + "." + vr.substr( tam - 14, 3 ) + "." + vr.substr( tam - 11, 3 ) + "." + vr.substr( tam - 8, 3 ) + "." + vr.substr( tam - 5, 3 ) + "," + vr.substr( tam - 2, tam ) ;}
	}
} 

//onkeyup="formataData(this);"
function formataData(val) {
	var pass = val.value;
	var expr = /[0123456789]/;
		
	for(i=0; i<pass.length; i++){
		var lchar = val.value.charAt(i);
		var nchar = val.value.charAt(i+1);
	
		if(i==0) {
		   if ((lchar.search(expr) != 0) || (lchar>3)){
			  val.value = "";
		   }
		   
		} else if(i==1){
			   
			   if(lchar.search(expr) != 0){
				  var tst1 = val.value.substring(0,(i));
				  val.value = tst1;				
				  continue;			
			   }
			   
			   if ((nchar != '/') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);
				
					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + '/' + tst2;
			   }

		 }else if(i==4){
			
				if(lchar.search(expr) != 0){
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;
					continue;			
				}
		
				if	((nchar != '/') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);

					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + '/' + tst2;
				}
		  }
		
		  if(i>=6) {
			  if(lchar.search(expr) != 0) {
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;			
			  }
		  }
	 }
	
	 if(pass.length>10)
		val.value = val.value.substring(0, 10);
		return true;
}

function formataHora(val) {
	var pass = val.value;
	var expr = /[0123456789]/;
		
	for(i=0; i<pass.length; i++){
		var lchar = val.value.charAt(i);
		var nchar = val.value.charAt(i+1);
	
		if(i==0) {
		   if ((lchar.search(expr) != 0) || (lchar>3)){
			  val.value = "";
		   }
		   
		} else if(i==1){
			   
			   if(lchar.search(expr) != 0){
				  var tst1 = val.value.substring(0,(i));
				  val.value = tst1;				
				  continue;			
			   }
			   
			   if ((nchar != ':') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);
				
					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + ':' + tst2;
			   }

		 }else if(i==4){
			
				if(lchar.search(expr) != 0){
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;
					continue;			
				}
		
				if	((nchar != ':') && (nchar != '')){
					var tst1 = val.value.substring(0, (i)+1);

					if(nchar.search(expr) != 0) 
						var tst2 = val.value.substring(i+2, pass.length);
					else
						var tst2 = val.value.substring(i+1, pass.length);
	
					val.value = tst1 + ':' + tst2;
				}
		  }
		
		  if(i>=6) {
			  if(lchar.search(expr) != 0) {
					var tst1 = val.value.substring(0, (i));
					val.value = tst1;			
			  }
		  }
	 }
	
	 if(pass.length>10)
		val.value = val.value.substring(0, 10);
		return true;
}


/* ------------------------------------------------------------------------------------------------ */

function buscaNomeParecido() {
	var nome= document.getElementById("nome").value;
	
	if (nome.length>3)
		ajaxLink("pessoas_repetidas_resultado", "buscaNomeParecido&nome="+nome);
	//else
	//	alert("Digite um nome válido (nome + sobrenome)!");
}

function recarregaPaginaAtual() {
	var pagina= document.getElementById("pagina");
	ajaxLink("conteudo", "carregaPagina&pagina="+pagina.value);
}

function atribuiAtual(id_elemento) {
	var menu= document.getElementById("menu");
	var itens= menu.getElementsByTagName("li");
	
	for (i=0; i<itens.length; i++) {
		if (itens[i].id==id_elemento)
			itens[i].className= "atual";
		else
			itens[i].className= "";
	}
}

function atribuiAbaAtual(id_div, id_elemento) {
	var menu= document.getElementById(id_div);
	var itens= menu.getElementsByTagName("li");
	
	for (i=0; i<itens.length; i++) {
		if (itens[i].id==id_elemento)
			itens[i].className= "atual";
		else
			itens[i].className= "";
	}
}


function desabilitaBotao(id_form) {
	if (id_form!="formPessoaInserir") {
		var formulario= document.getElementById(id_form);
		var enviar= formulario.getElementsByTagName("button");
		enviar[0].innerHTML= "Enviando...";
		enviar[0].disabled=true;
	}
}

function desabilitaCampo(campoext) {
	try {
		var campo= document.getElementById(campoext);
		campo.disabled= true;
	} catch (eee) { }
}

function habilitaCampo(campoext) {
	var campo= document.getElementById(campoext);
	campo.disabled= false;
}

function limpaCampo(campoext) {
	var campo= document.getElementById(campoext);
	campo.value="";
}

function fechaTelaCadastro() {
	var tela_cadastro= document.getElementById("tela_cadastro");
	tela_cadastro.style.display= "none";
}

function fechaTelaAuxRapida() {
	var tela_cadastro= document.getElementById("tela_aux_rapida");
	tela_cadastro.style.display= "none";
}


function atribuiValor(campo, valor) {
	var campo_dest= document.getElementById(campo);
	campo_dest.value= valor;
}

function cadastraMembroFamilia() {
	var id_familia= document.getElementById("id_familia");
	var id_pessoa= document.getElementById("id_pessoa_mesmo");
	var parentesco= document.getElementById("parentesco");
	
	if (id_pessoa.value=="")
		alert('Selecione a pessoa à inserir!');
	else
		ajaxLink("formacao_familia", "cadastraMembroFamilia&id_familia="+id_familia.value+"&id_pessoa="+id_pessoa.value+"&parentesco="+parentesco.value);
}

function verificaDestinoRel() {
	var local= document.getElementById("local");
	
	if (local.value=="0")
		habilitaCampo("local_d");
	else
		desabilitaCampo("local_d");
}

function pessoaPesquisar() {
	var nomeb= document.getElementById("nomeb");
	var campo_retorno= document.getElementById("campo_retorno");
	var tipo_consulta= document.getElementById("tipo_consulta");
	
	//campo para ir para o prontuário sem cpf
	var tipo_volta= document.getElementById("tipo_volta");
	
	if ( (nomeb.value=="") || (nomeb.value.length<3) ) {
		var pessoa_buscar_resultado= document.getElementById("pessoa_buscar_resultado");
		pessoa_buscar_resultado.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		nomeb.focus();
	}
	else
		ajaxLink('pessoa_buscar_resultado', 'pessoaPesquisar&nomeb='+nomeb.value+'&campo_retorno='+campo_retorno.value+'&tipo_consulta='+tipo_consulta.value+'&tipo_volta='+tipo_volta.value);
}

function carregaPeriodoArrecadamento() {
	var ano_inicial= document.getElementById("ano_inicial");
	var ano_final= document.getElementById("ano_final");
	var id_familia= document.getElementById("id_familia_geral");
	
	ajaxLink("anos_periodo", "carregaPaginaInterna&pagina=_social/arrecadacao_form&id_familia="+id_familia.value+"&ano_inicial="+ano_inicial.value+"&ano_final="+ano_final.value);
}

function somaValoresAno(y, elemento) {
	
	if (elemento.value=="") elemento.value="0";
	
	var soma=0;
	var aqui=0;
	var valor;
	
	for (var i=1; i<14; i++) {
		valor= document.getElementById("dado_"+y+"_"+i).value;
		//alert(i+" -> "+valor);
		aqui= parseFloat(valor.replace(".", "").replace(",", "."));
		
		soma += aqui;
	}
	
	soma=soma+"";
	
	/*if (soma.indexOf(".")!=-1) {
		soma= soma.replace(".", ",");
		var partes= soma.split(",");
		soma= partes[0] +","+ partes[1].substr(0, 2);
	}*/
	
	document.getElementById("soma_"+y).value= moeda.formatar(soma);
	//document.getElementById("soma_"+y).value= soma;
}

function retornaCidades() {
	var id_uf= document.getElementById('id_uf').value;
	ajaxLink("id_cidade_atualiza", "retornaCidades&id_uf="+id_uf);
}

function retornaPostos() {
	var id_cidade= document.getElementById('id_cidade_em').value;
	ajaxLink("id_posto_atualiza", "retornaPostos&id_cidade="+id_cidade);
}

function retornaMicroareas() {
	var id_posto= document.getElementById('id_posto').value;
	ajaxLink("id_microarea_atualiza", "retornaMicroareas&id_posto="+id_posto);
}

function usuarioRetornaCpfDisponibilidade() {
	var modo_cadastro_cpf= 2;
	//modo_cadastro_cpf
	//1- só com cpf
	//2- pode sem cpf
	var cpf= document.getElementById('cpf_cadastro').value;
	var erros= validaCpf(cpf);
	if (erros.length=="") {
		desabilitaCampo('enviar');
		ajaxLink('cpf_disponibilidade', 'usuarioRetornaCpfDisponibilidade&cpf='+cpf)
	}
	else {
		var cpf_disponibilidade= document.getElementById('cpf_disponibilidade');
		
		if ( (cpf.length=="") && (modo_cadastro_cpf==parseInt(2)) ) {
			habilitaCampo('enviar');
			cpf_disponibilidade.innerHTML="<span class=\"vermelho\">Prossiga para cadastro sem CPF!</span>";
		}
		else {
			desabilitaCampo('enviar');
			cpf_disponibilidade.innerHTML= "<span class=\"vermelho\">"+erros+"</span><input type=\"hidden\" class=\"escondido\" name=\"cpf_disponivel\" id=\"cpf_disponivel\" value=\"0\" />";
		}
	}

}

function usuarioRetornaCpf() {
	var cpf= document.getElementById('cpf_usuario').value;
	var erros= validaCpf(cpf);
	if (erros.length=="")
		ajaxLink('cpf_usuario_atualiza', 'usuarioRetornaCpf&cpf='+cpf)
	else {
		var cpf_usuario_atualiza= document.getElementById('cpf_usuario_atualiza');
		cpf_usuario_atualiza.innerHTML= "<span class=\"vermelho\">"+erros+"</span> <input type=\"hidden\" name=\"id_pessoa_form\" id=\"id_pessoa_form\" value=\"\" class=\"escondido\" />";
	}
}

function usuarioRetornaCpfCompleto(local) {
	//se eh da tfd
	var encontrou = local.search("@");
	
	//local=f _acNUM / t2@NUM
	//campon=numero/posicao
	
	if (encontrou!=-1) {
		var localn= local.slice(0, 2);
		var campon= local.slice(3, 4);
		var acompanhante= "";
		var tipo= document.getElementById("tipo"+campon).value;
		var cpf= document.getElementById("cpf_usuario"+campon).value;
		var div_retorno= "cpf_usuario_atualiza"+campon;
	}
	else {
		//Se é do acompanhante
		var encontrou2 = local.search("_");
		
		if (encontrou2!=-1) {
			var localn= "ac";
			var campon= local.slice(3, 4);
			var acompanhante= 1;
			var tipo= "";
			var cpf= document.getElementById("cpf_usuario"+local).value;
			var div_retorno= "cpf_usuario_ac_atualiza"+campon;
		}
		else {
			var localn= local;
			var campon= "";
			var acompanhante= "";
			var tipo= "";
			var cpf= document.getElementById('cpf_usuario').value;
			var div_retorno= "cpf_usuario_atualiza";
		}
	}
	
	var erros= validaCpf(cpf);
		
	if (erros.length=="") {
		desabilitaCampo("botaoInserir");
		ajaxLink(div_retorno, 'usuarioRetornaCpfCompleto&cpf='+cpf+'&local='+localn+'&campon='+campon+'&acompanhante='+acompanhante+'&tipo='+tipo);
	}
	else {
		var cpf_usuario_atualiza= document.getElementById(div_retorno);
		cpf_usuario_atualiza.innerHTML= " <input id=\"id_pessoa_mesmo\" class=\"escondido\" type=\"hidden\" value=\"\" name=\"id_pessoa\" /> <span class=\"vermelho\">"+erros+"</span>";
	}
}

function verificaUsuario(id_usuario) {
	var usuario= document.getElementById("usuario").value;
	if (usuario!="") {
		ajaxLink("nome_usuario_atualiza", "verificaUsuario&usuario="+usuario+"&id_usuario="+id_usuario)
	}
	else {
		var nome_usuario_atualiza= document.getElementById('nome_usuario_atualiza');
		nome_usuario_atualiza.innerHTML= "<span class=\"vermelho\">Entre com o nome de usuário!</span>";
	}
}

function abreCadastro(ident) {
	var cpf_usuario= document.getElementById("cpf_usuario");
	
	ajaxLink('tela_cadastro', 'pessoaInserirMostra&cpf='+cpf_usuario.value+'&retorno='+ident);
	abreDivSo('tela_cadastro');
}

function abreCadastroSo() {
	ajaxLink('tela_cadastro', 'pessoaInserirMostra&retorno=conteudo');
	abreDivSo('tela_cadastro');
}

function abreCadastroPessoaFamilia() {
	ajaxLink('tela_cadastro', 'pessoaInserirMostra&retorno=familia_inserir');
	abreDivSo('tela_cadastro');
}

function editaDadosPessoais(ident) {
	var id_pessoa= document.getElementById("id_pessoa_mesmo");

	ajaxLink('tela_cadastro', 'carregaPaginaInterna&pagina=_pessoas/pessoa_editar&id_pessoa='+id_pessoa.value+'&retorno='+ident);
	abreDivSo('tela_cadastro');
}

function cadastraDependente(ident) {
	var id_responsavel= document.getElementById("id_pessoa_dep");
	
	ajaxLink('tela_cadastro', 'pessoaInserirMostra&id_responsavel='+id_responsavel.value+'&retorno='+ident);
	abreDivSo('tela_cadastro');
}

function abreDiv(div, campo_destino1, valor1, campo_destino2, valor2) {
	
	var div_mesmo= document.getElementById(div);	
	div_mesmo.style.display="block";
	
	try {
		var campo_dest1= document.getElementById(campo_destino1);
		campo_dest1.value= valor1;
		
		var campo_dest2= document.getElementById(campo_destino2);
		campo_dest2.value= valor2;
		
		if (campo_destino2=="tit_remedio") {
			campo_dest2.innerHTML= valor2;
			var campo_dest3= document.getElementById("tit_remedio_pre");
			campo_dest3.value= valor2;
		}
	} catch(eee) { }
}

function abreDivSo(div) {
	var div_mesmo= document.getElementById(div);
	div_mesmo.style.display="block";
}

function abreFechaDiv(div) {
	var div_mesmo= document.getElementById(div);
	
	if ((div_mesmo.className=="nao_mostra") || (div_mesmo.className=="escondido")) {
		div_mesmo.style.display=="block";
		div_mesmo.className= "mostra";
	}
	else {
		div_mesmo.style.display=="none";
		div_mesmo.className= "nao_mostra";
	}
}

function fechaDiv(div) {
	var div_mesmo= document.getElementById(div);
	div_mesmo.style.display="none";
}

function preencheDiv(div, conteudo) {
	var div_mesmo= document.getElementById(div);
	div_mesmo.innerHTML=conteudo;
}

function daFoco(campo) {
	document.getElementById(campo).focus();
}

function daBlur(campo) {
	document.getElementById(campo).blur();
}

/* ------------------------------------------------------------------------------------------------ */

function validaCpf(cpf) {
	 var strcpf = cpf;
	 var str_aux = "";
	 var erros= "";
	 
	 for (i = 0; i <= strcpf.length - 1; i++)
	   if ((strcpf.charAt(i)).match(/\d/))
		 str_aux += strcpf.charAt(i);
	   else if (!(strcpf.charAt(i)).match(/[\.\-]/)) {
		 erros += "Apenas números no campo CPF!\n";
		 break;
		 //return false;
	   }

	 if (str_aux.length < 11) {
	   erros += "O campo CPF deve conter 11 dígitos!\n";
	   //return false;
	 }
	 else {
		 soma1 = soma2 = 0;
		 for (i = 0; i <= 8; i++) {
		   soma1 += str_aux.charAt(i) * (10-i);
		   soma2 += str_aux.charAt(i) * (11-i);
		 }
		 d1 = ((soma1 * 10) % 11) % 10;
		 d2 = (((soma2 + (d1 * 2)) * 10) % 11) % 10;
		 if ((d1 != str_aux.charAt(9)) || (d2 != str_aux.charAt(10))) {
		   erros += "O CPF digitado é inválido!\n";
		   //return false;
		 }
		  if ((cpf=="00000000000") || (cpf=="11111111111") || (cpf=="22222222222") || (cpf=="33333333333") || 
		  (cpf=="44444444444") || (cpf=="55555555555") || (cpf=="66666666666") || (cpf=="77777777777") || 
		  (cpf=="88888888888") || (cpf=="99999999999") ) {
		   erros += "O CPF digitado é inválido!!\n";
		   //return false;
		 }
	 }
	 return (erros);
}

function validaCnpj(CNPJ) {
	 erro = new String;
	 if (CNPJ.length < 18)
	 	erro = "CNPJ inválido!";
	 if ((CNPJ.charAt(2) != ".") || (CNPJ.charAt(6) != ".") || (CNPJ.charAt(10) != "/") || (CNPJ.charAt(15) != "-")){
	 if (erro.length == 0)
	 	erro = "CNPJ inválido!";
	 }
	 //substituir os caracteres que não são números
   if(document.layers && parseInt(navigator.appVersion) == 4){
		   x = CNPJ.substring(0,2);
		   x += CNPJ. substring (3,6);
		   x += CNPJ. substring (7,10);
		   x += CNPJ. substring (11,15);
		   x += CNPJ. substring (16,18);
		   CNPJ = x;
   } else {
		   CNPJ = CNPJ. replace (".","");
		   CNPJ = CNPJ. replace (".","");
		   CNPJ = CNPJ. replace ("-","");
		   CNPJ = CNPJ. replace ("/","");
   }
   var nonNumbers = /\D/;
   if (nonNumbers.test(CNPJ))
   	  if (erro.length == 0)	
		erro += "O campo CNPJ suporta apenas números!";
	
   var a = [];
   var b = new Number;
   var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
   for (i=0; i<12; i++){
		   a[i] = CNPJ.charAt(i);
		   b += a[i] * c[i+1];
   }
   if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
   b = 0;
   for (y=0; y<13; y++) {
		   b += (a[y] * c[y]);
   }
   if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
   if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
	   if (erro.length == 0)
		   erro = "CNPJ inválido!";
   }
   return(erro);
}

function validaData(data_nasc) {
	var erros = "";
	if (data_nasc=="")
		erros += "Entre com a data solicitada!\n";
	else {
		var dia= data_nasc.substring(0, 2);
		var mes= data_nasc.substring(3, 5);
		var ano= data_nasc.substring(6, 10);
		
		var barra1= data_nasc.substring(2, 3);
		var barra2= data_nasc.substring(5, 6);
		
		if ((barra1=="/") && (barra2=="/")) {
			var erro= "";
			var nonNumbers = /\D/;
						
			if ( (dia<=0) || (dia>31)  || (nonNumbers.test(dia)) )
				erros += "Dia inválido\n";
			else {
				if ( ((mes=="02") || (mes=="04") || (mes=="06") || (mes=="09") || (mes=="11")) && (dia=="31") )
					erros += "O mes informado não possui o dia 31!\n";
			}
			if ( (mes<=0) || (mes>12)  || (nonNumbers.test(mes)) )
				erros += "Mês invalido\n";
				
			/*var dataAtual= new Date();
			var anoAtual= dataAtual.getFullYear();
			
			if ( (ano<=0) || (ano>anoAtual) || (nonNumbers.test(ano)) )
				erros += "Ano inválido!\n";
			*/
			//ano bissexto
			if ((ano%4!=0) && (mes==2) && (dia>28))
				erros += "Dia inválido! \n";
			
			if (ano.length<4)
				erros += "Ano inválido! Preencha com 4 dígitos (ex: 1986) \n";
		}
		else
			erros += "Preencha a data no formato dd/mm/AAAA\n";
	}
	return(erros);
}



/*
---------------------------------------------------------------------
---------------------------------------------------------------------
------------- FUNCOES PARA VALIDAR FORMULARIOS ---------------------
---------------------------------------------------------------------
---------------------------------------------------------------------
*/

function validaForm(id_form) {
	
	var erros= "";
	var erros2= "";
	var foco= null;
	
	switch(id_form) {
		case "formPessoaBuscar":
			/*var txt_busca= document.getElementById("txt_busca");
			
			if ( (txt_busca.value=="") || (txt_busca.value.length<3) ) {
				if (foco==null) foco= txt_busca;
				erros += "Entre com pelo menos 3 caracteres para realizar a busca!\n";
			}*/
			break;
		case "formPessoaInserir":
		case "formPessoaEditar":
			var modo_cadastro_cpf= 2;
			var id_responsavel= document.getElementById("id_responsavel");
			var nome= document.getElementById("nome");
			
			if (id_responsavel.value=="0")
				var cpf= document.getElementById("cpf_cadastro");
			
			var endereco= document.getElementById("endereco");
			var bairro= document.getElementById("bairro");
			var retorno= document.getElementById("retorno");
			
			if (id_form=="formPessoaEditar") {
				var id_pessoa= document.getElementById("id_pessoa");
				if (id_pessoa.value=="") {
					if (foco==null) foco= nome;
					erros += "Erro de transação, recarregue o sistema!\n";
				}
				var sexo= document.formPessoaEditar.sexo;
				var id_cidade= document.formPessoaEditar.id_cidade;
			}
			else {
				var sexo= document.formPessoaInserir.sexo;
				var id_cidade= document.formPessoaInserir.id_cidade;
			}
			
			if (nome.value=="") {
				if (foco==null) foco= nome;
				erros += "Entre com o nome da pessoa!\n";
			}
			if (!sexo[0].checked && !sexo[1].checked)
				erros += "Selecione o sexo!\n";
			
			//if (cpf.className=="testandolala") {
			//	erros += validaCpf(cpf.value);
			//}
			
			if (id_responsavel.value==0) {
				if ((modo_cadastro_cpf==2) && (cpf.value==""))
					erros2 += validaCpf(cpf.value);
				else
					erros += validaCpf(cpf.value);
			}
		
			if ((id_form=="formPessoaInserir") && (retorno.value=="conteudo")) {
				var cpf_disponivel= document.getElementById("cpf_disponivel");
				
				if (id_form=="formPessoaInserir") {
					if (modo_cadastro_cpf==1) {
						if (cpf_disponivel.value=="0")
							erros += "CPF não testado ou indisponível para cadastro!";
					}
					//se o CPF não for obrigatório...
					//data de nascimento ou nome da mãe obrigatórios
					else {
						//se o campo cpf estiver vazio
						if (cpf.value=="") {
							
						}
						else {
							erros += validaCpf(cpf.value);
						}
					}
				}
				//editar
				else {
					if (id_responsavel.value==0) {
						if ((modo_cadastro_cpf==2) && (cpf.value==""))
							erros2 += validaCpf(cpf.value);
						else
							erros += validaCpf(cpf.value);
					}
				}
			}

			
			/*if (endereco.value=="") {
				if (foco==null) foco= endereco;
				erros += "Entre com o endereço!\n";
			}
			if (bairro.value=="") {
				if (foco==null) foco= bairro;
				erros += "Entre com o bairro!\n";
			}*/
			if (id_cidade.value=="")
				erros += "Selecione a cidade!\n";
			break;
		case "formUsuarioInserir":
			var id_pessoa_form= document.getElementById("id_pessoa_form");
			var permissao= document.formUsuarioInserir.permissao_acesso;
			var tipo_usuario= document.getElementById("tipo_usuario");
			var usuario= document.getElementById("usuario");
			var senha= document.getElementById("senha");
			var senha2= document.getElementById("senha2");

			if (id_pessoa_form.value=="")
				erros += "Entre com o CPF para localizar a pessoa!\n";

			if (tipo_usuario.value=="") {
				if (foco==null) foco= tipo_usuario;
				erros += "Selecione o tipo do usuário!\n";
			}
			if (usuario.value=="") {
				if (foco==null) foco= tipo_usuario;
				erros += "Digite o nome de usuário!\n";
			}
			else {
				if (permissao.value==0)
					erros += "Este nome de usuário não está disponível!\n";
			}
			if (senha.value=="") {
				if (foco==null) foco= senha;
				erros += "Digite a senha!\n";
			}
			if (senha.value.length<6) {
				if (foco==null) foco= senha;
				erros += "A senha precisa ter no mínimo 6 dígitos!\n";
			}
			else {
				if (senha.value!=senha2.value) {
					if (foco==null) foco= senha2;
					erros += "A confirmação de senha está incorreta!\n";
				}
			}
		break;
		case "formUsuarioEditar":
			var permissao= document.formUsuarioEditar.permissao_acesso;
			var tipo_usuario= document.getElementById("tipo_usuario");
			var usuario= document.getElementById("usuario");
			var senha= document.getElementById("senha");
			var senha2= document.getElementById("senha2");

			if (tipo_usuario.value=="") {
				if (foco==null) foco= tipo_usuario;
				erros += "Selecione o tipo do usuário!\n";
			}
			if (usuario.value=="") {
				if (foco==null) foco= tipo_usuario;
				erros += "Digite o nome de usuário!\n";
			}
			else {
				if (permissao.value==0)
					erros += "Este nome de usuário não está disponível!\n";
			}
			if (senha.value=="") {
				if (foco==null) foco= senha;
				erros += "Digite a senha!\n";
			}
			if (senha.value.length<6) {
				if (foco==null) foco= senha;
				erros += "A senha precisa ter no mínimo 6 dígitos!\n";
			}
			else {
				if (senha.value!=senha2.value) {
					if (foco==null) foco= senha2;
					erros += "A confirmação de senha está incorreta!\n";
				}
			}
		break;

		case "formRecupera":
			var email= document.getElementById("email");
			
			if (!validaEmail(email.value))  {
				if (foco==null) foco= email;
				erros += "Digite o e-mail corretamente!\n";
			}
			
		break;
		case "formSenha":
			var email_s= document.getElementById("email_s");
			var senha_atual= document.getElementById("senha_atual");
			var nova_senha= document.getElementById("senha_nova");
			var nova_senha2= document.getElementById("senha_nova2");
			
			if (email_s.value!="") {
				if (!validaEmail(email_s.value))  {
					if (foco==null) foco= email_s;
					erros += "Digite o e-mail corretamente!\n";
				}
			}
			if (senha_atual.value=="") {
				if (foco==null) foco= senha_atual;
				erros += "Entre com a senha atual (é exigida para atualizar os dados)!\n";
			}
			if (nova_senha.value!="") {
				if (nova_senha.value.length<6) {
					if (foco==null) foco= nova_senha;
					erros += "A senha precisa ter no mínimo 6 dígitos!\n";
				}
				if (nova_senha.value!=nova_senha2.value) {
					if (foco==null) foco= nova_senha2;
					erros += "Digite corretamente a confirmação da nova senha!\n";
				}
			}
		break;
		case "formContato":
			var nome= document.getElementById("nome");
			var email= document.getElementById("email");
			var telefone= document.getElementById("telefone");
			var tipo_contato= document.getElementById("tipo_contato");
			var area_contato= document.getElementById("area_contato");
			var mensagem= document.getElementById("mensagem");

			if (nome.value=="") {
				if (foco==null) foco= nome;
				erros += "Entre com o nome!\n";
			}
			if ((email.value=="") && (telefone.value=="")) {
				if (foco==null) foco= email;
				erros += "Entre com o e-mail ou telefone para que possamos entrar em contato!\n";
			}
			if (tipo_contato.value=="") {
				if (foco==null) foco= tipo_contato;
				erros += "Selecione o tipo de contato!\n";
			}
			if (area_contato.value=="") {
				if (foco==null) foco= area_contato;
				erros += "Selecione a área!\n";
			}
			if (mensagem.value=="") {
				if (foco==null) foco= mensagem;
				erros += "Entre com a mensagem!\n";
			}
		break;
		case "formFamiliaInserir":
		case "formFamiliaEditar":
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			var id_microarea= document.getElementById("id_microarea");
			
			if ((id_pessoa.value=="") || (id_pessoa.value=="0"))
				erros += "Identifique o chefe da família!\n";
			
			if (id_microarea.value=="")
				erros += "Selecione a quadra!\n";

		break;
		default: void(0);
	}
	if (foco!=null)
		foco.focus();
	if (erros.length>0) {
		alert (erros);
		return false;
	}
	else {
		if (erros2.length>0) {
			return true; //return confirm("Você está cadastrando uma pessoa sem o CPF!\nTenha certeza de estar inserindo os dados corretos\ne peça à pessoa para trazer o CPF na próxima visita!\n\nTEM CERTEZA QUE DESEJA CONTINUAR?");
		}
		else
			return true;
	}
}

function validaLogin(frm) {
	if(frm.usuario.value=="") {
		alert("O campo usuario está em branco!\nFavor preenchê-lo!")
		frm.usuario.focus();
		return false;
	}
	if(frm.senha.value=="")	{
		alert("O campo senha está em branco!\nFavor preenchê-lo!")
		frm.senha.focus()
		return false;
	}
	desabilitaBotao("formLogin");
	return true;
}

/**
* moeda
*
* @abstract Classe que formata de desformata valores monetários
* em float e formata valores de float em moeda.
*
* @author anselmo
*
* @example
* moeda.formatar(1000)
* >> retornar 1.000,00
* moeda.desformatar(1.000,00)
* >> retornar 1000
*
* @version 1.0
**/
var moeda = {
/**
* retiraFormatacao
* Remove a formatação de uma string de moeda e retorna um float
* @param {Object} num
*/
desformatar: function(num){
num = num.replace(".","");
num = num.replace(",",".");
return parseFloat(num);
},
/**
* formatar
* Deixar um valor float no formato monetário
* @param {Object} num
*/
formatar: function(num){
x = 0;
if(num<0){
num = Math.abs(num);
x = 1;
}

if(isNaN(num)) num = "0";
cents = Math.floor((num*100+0.5)%100);
num = Math.floor((num*100+0.5)/100).toString();
if(cents < 10) cents = "0" + cents;
for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
num = num.substring(0,num.length-(4*i+3))+'.'
+num.substring(num.length-(4*i+3));
ret = num + ',' + cents;
if (x == 1) ret = ' - ' + ret;return ret;
},

/**
* arredondar
* @abstract Arredonda um valor quebrado para duas casas
* decimais.
* @param {Object} num
*/
arredondar: function(num){
return Math.round(num*Math.pow(10,2))/Math.pow(10,2);
}}