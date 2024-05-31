/* FUNCOES PARA VALIDAR VALORES GENÉRICOSSSS */

function alteraTitulo() {
	document.title= "Lsbt";
}

function confirmaAlert() {
	
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

function abreFechaCadastroSocial(local, valor) {
	var tela_cadastro= document.getElementById("tela_cadastro");
	var cadastro_social= document.getElementById("cadastro_social");
	var dados_sociais= document.getElementById("dados_sociais");
	
	if ((cadastro_social.className=="nao_mostra") || (cadastro_social.className=="escondido")) {
		cadastro_social.style.display="block";
		cadastro_social.className= "mostra";
		tela_cadastro.style.width= "710px";
		dados_sociais.value= "1";
		if (local=='i')
			ajaxLink("formulario_social", "carregaPaginaInterna&pagina=_pessoas/social_inserir");
		else
			ajaxLink("formulario_social", "carregaPaginaInterna&pagina=_pessoas/social_editar&id_pessoa="+valor);
	}
	else {
		cadastro_social.style.display="none";
		cadastro_social.className= "nao_mostra";
		tela_cadastro.style.width= "334px";
		dados_sociais.value= "0";
	}
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

function mostraRegistroSolicitacaoTfd() {
	var situacao_solicitacao= document.getElementById("situacao_solicitacao");
	var registro_atualiza= document.getElementById("registro_atualiza");
	
	if ((situacao_solicitacao.value==2) || (situacao_solicitacao.value==4))
		registro_atualiza.className="mostra";
	else
		registro_atualiza.className="escondido";
		
}

function geraEstadoNutricional() {
	var tipo_acompanhamento= document.getElementById("tipo_acompanhamento");
	var sexo= document.getElementById("sexo");
	var peso= document.getElementById("peso");
	var altura= document.getElementById("altura");
	
	if (tipo_acompanhamento.value=="g") {
		var semana_gestacional= document.getElementById("semana_gestacional");
		
		if ((peso.value!="") && (altura.value!="") && (semana_gestacional.value!=""))
			ajaxLink("estado_nutricional", "pegaEstadoNutricional&tipo_acompanhamento="+tipo_acompanhamento.value+"&peso="+peso.value+"&altura="+altura.value+"&semana_gestacional="+semana_gestacional.value);
	}
	else {
		var idade= document.getElementById("idade_paciente");
		var meses= document.getElementById("meses_paciente");
	
		if ((peso.value!="") && (altura.value!=""))
			ajaxLink("estado_nutricional", "pegaEstadoNutricional&tipo_acompanhamento="+tipo_acompanhamento.value+"&idade="+idade.value+"&meses="+meses.value+"&sexo="+sexo.value+"&peso="+peso.value+"&altura="+altura.value);
	}
}

function retornaFinalidades() {
	var tipo_ida= document.getElementById("tipo_ida").value;
	
	ajaxLink("id_finalidade_atualiza", "retornaFinalidades&tipo_ida="+tipo_ida);
}

function retornaIdSolicitacaoTfd() {
	var id_cidade= document.getElementById("id_cidade2").value;
	
	if (id_cidade!="")
		ajaxLink("id_atualiza", "retornaIdSolicitacaoTfd&id_cidade="+id_cidade);
	
}

function retornaEntidades() {
	var id_cidade= document.getElementById("id_cidade2").value;
	
	ajaxLink("id_entidade_atualiza", "retornaEntidades&id_cidade="+id_cidade);
}

function removePessoaTfd(id, tipo) {
	var local = document.getElementById("tfd_"+tipo+"");
	var registro = document.getElementById("div_"+tipo+"_"+id);
	local.removeChild(registro);
	
	var num_divs = document.getElementsByTagName("code");
	atribuiValor("qtde_pessoas", num_divs.length);
}

function adicionaPessoaTfd(tipo) {
	var div_mesmo= document.getElementById("tfd_"+tipo);
	var contador= document.createElement("code");
	
	var num_divs = document.getElementsByTagName("code");
	atribuiValor("qtde_pessoas", num_divs.length+1);
	
	var div_dentro= document.createElement("div");
	div_dentro.id= "div_"+tipo+"_"+num_divs.length;

	var fieldset_dentro= document.createElement("fieldset");
	fieldset_dentro.className= "escuro";
	
	var legend_dentro= document.createElement("legend");
	legend_dentro.innerHTML= "Pessoa "+(parseInt(num_divs.length)+1)+" | ";
	legend_dentro.className= "escuro";

	var link_excluir= document.createElement("a");
	link_excluir.href= "javascript:removePessoaTfd('"+num_divs.length+"', '"+tipo+"')";
	link_excluir.title= "clique para retirar a pessoa";
	link_excluir.innerHTML= "remover";
	
	legend_dentro.appendChild(link_excluir);
	fieldset_dentro.appendChild(legend_dentro);
	
	var rotulo= document.createElement("label");
	rotulo.innerHTML="CPF:";
	
	var cpf_usuario= document.createElement("input");
	cpf_usuario.name= "cpf_usuario";
	cpf_usuario.setAttribute("maxlength", "11");
	cpf_usuario.setAttribute("onblur", "usuarioRetornaCpfCompleto('t2@"+num_divs.length+"')");
	cpf_usuario.id= "cpf_usuario"+num_divs.length;
	
	var tipo_campo= document.createElement("input");
	tipo_campo.name= "tipo[]";
	tipo_campo.id= "tipo"+num_divs.length;
	tipo_campo.value= tipo;
	tipo_campo.type= "hidden";
	tipo_campo.className= "escondido";
	
	var busca= document.createElement("button");
	busca.type= "button";
	busca.setAttribute("onclick", "abreFechaDiv('pessoa_buscar'); atribuiValor('campo_retorno', '"+num_divs.length+"'); atribuiValor('tipo_consulta', '"+tipo+"'); daFoco('nomeb');");
	busca.className= "espaco_dir";
	busca.innerHTML= "buscar";
	
	var rotulo2= document.createElement("label");
	rotulo2.innerHTML=" ";
	
	var cpf_usuario_atualiza= document.createElement("div");
	cpf_usuario_atualiza.id= "cpf_usuario_atualiza"+num_divs.length;
	
	var id_pessoa= document.createElement("input");
	id_pessoa.type= "hidden";
	id_pessoa.name= "id_pessoa[]";
	id_pessoa.value= "";
	id_pessoa.className= "escondido";
	
	cpf_usuario_atualiza.appendChild(id_pessoa);
	
	div_dentro.appendChild(fieldset_dentro);
	
	fieldset_dentro.appendChild(rotulo);
	fieldset_dentro.appendChild(cpf_usuario);
	fieldset_dentro.appendChild(tipo_campo);
	fieldset_dentro.appendChild(busca);
	
	fieldset_dentro.appendChild(document.createElement("br"));
	fieldset_dentro.appendChild(contador);
	fieldset_dentro.appendChild(rotulo2);
	fieldset_dentro.appendChild(cpf_usuario_atualiza);
	fieldset_dentro.appendChild(document.createElement("br"));
	
	var ida= document.createElement("input");
	ida.name= "ida[]";
	ida.type= "checkbox";
	ida.className= "tamanho30";
	ida.setAttribute("checked", "checked");
	ida.id= "ida"+num_divs.length;
	ida.value= 1;
	
	var rotulo_ida= document.createElement("label");
	rotulo_ida.innerHTML="Ida";
	rotulo_ida.setAttribute("for", "ida"+num_divs.length);
	rotulo_ida.className="label2";
	
	var volta= document.createElement("input");
	volta.name= "volta[]";
	volta.type= "checkbox";
	volta.className= "tamanho30";
	volta.setAttribute("checked", "checked");
	volta.id= "volta"+num_divs.length;
	volta.value= 1;
	
	var rotulo_volta= document.createElement("label");
	rotulo_volta.innerHTML="Volta";
	rotulo_volta.setAttribute("for", "volta"+num_divs.length);
	rotulo_volta.className="label2";
	
	var rotulo_obs= document.createElement("label");
	rotulo_obs.innerHTML="OBS:";

	var obs= document.createElement("textarea");
	obs.name= "obs[]";
	
	fieldset_dentro.appendChild(document.createElement("label"));
	fieldset_dentro.appendChild(ida);
	fieldset_dentro.appendChild(rotulo_ida);
	fieldset_dentro.appendChild(volta);
	fieldset_dentro.appendChild(rotulo_volta);
	fieldset_dentro.appendChild(document.createElement("br"));
	fieldset_dentro.appendChild(document.createElement("br"));
	fieldset_dentro.appendChild(rotulo_obs);
	fieldset_dentro.appendChild(obs);
	fieldset_dentro.appendChild(document.createElement("br"));
	
	if (tipo=="p") {
		var fieldset_acompanhante= document.createElement("fieldset");
		fieldset_acompanhante.className= "escuro";
		
		var legend_acompanhante= document.createElement("legend");
		legend_acompanhante.className= "escuro";
		legend_acompanhante.innerHTML= "Acompanhantes";
		
		var div_esquerda= document.createElement("div");
		div_esquerda.className= "parte_esquerda";
		
		var div_direita= document.createElement("div");
		div_direita.className= "parte_direita sem";
		
		var fieldset_acompanhante_mesmo= document.createElement("fieldset");
		
		var legend_acompanhante_mesmo= document.createElement("legend");
		legend_acompanhante_mesmo.innerHTML= "Acompanhantes já inseridos";
		
		var acompanhantes_atualiza= document.createElement("div");
		acompanhantes_atualiza.id= "acompanhantes_atualiza"+num_divs.length;
		acompanhantes_atualiza.innerHTML= "<span class='vermelho'>Nenhum acompanhante até o momento!</span>";
		
		var rotulo_ac= document.createElement("label");
		rotulo_ac.innerHTML="CPF:";
		
		var cpf_usuario_ac= document.createElement("input");
		cpf_usuario_ac.name= "cpf_usuario_ac";
		cpf_usuario_ac.setAttribute("maxlength", "11");
		cpf_usuario_ac.setAttribute("onblur", "usuarioRetornaCpfCompleto('_ac"+num_divs.length+"')");
		cpf_usuario_ac.id= "cpf_usuario_ac"+num_divs.length;
		
		/*var add_ac= document.createElement("button");
		add_ac.type= "button";
		add_ac.setAttribute("onclick", "usuarioRetornaCpfAcompanhanteTfd('"+num_divs.length+"')");
		add_ac.className= "espaco_dir";
		add_ac.innerHTML= "adicionar";*/
		
		var busca_ac= document.createElement("button");
		busca_ac.type= "button";
		busca_ac.setAttribute("onclick", "abreFechaDiv('pessoa_buscar'); atribuiValor('campo_retorno', '"+num_divs.length+"'); atribuiValor('tipo_consulta', 'ac'); daFoco('nomeb');");
		busca_ac.className= "espaco_dir";
		busca_ac.innerHTML= "buscar";
		
		var rotulo_ac2= document.createElement("label");
		rotulo_ac2.innerHTML=" ";
		
		var cpf_usuario_ac_atualiza= document.createElement("div");
		cpf_usuario_ac_atualiza.id= "cpf_usuario_ac_atualiza"+num_divs.length;
		
		var acompanhantes= document.createElement("input");
		acompanhantes.name= "acompanhantes[]";
		acompanhantes.type= "hidden";
		acompanhantes.className= "escondido";
		acompanhantes.id= "acompanhantes"+num_divs.length;
		
		fieldset_acompanhante.appendChild(legend_acompanhante);
		fieldset_acompanhante.appendChild(div_esquerda);
		fieldset_acompanhante.appendChild(div_direita);
		
		fieldset_acompanhante_mesmo.appendChild(legend_acompanhante_mesmo);
		fieldset_acompanhante_mesmo.appendChild(acompanhantes_atualiza);
		fieldset_acompanhante_mesmo.appendChild(acompanhantes);
		div_direita.appendChild(fieldset_acompanhante_mesmo);
		
		div_esquerda.appendChild(rotulo_ac);
		div_esquerda.appendChild(cpf_usuario_ac);
		div_esquerda.appendChild(busca_ac);
		div_esquerda.appendChild(document.createElement("br"));
		
		div_esquerda.appendChild(rotulo_ac2);
		div_esquerda.appendChild(cpf_usuario_ac_atualiza);
		
		div_esquerda.appendChild(document.createElement("br"));
		
		fieldset_dentro.appendChild(fieldset_acompanhante);
	}
	
	div_dentro.appendChild(document.createElement("br"));
	
	div_mesmo.appendChild(div_dentro);
}

function atualizaValorAcompanhante(id_campo, id_pessoa) {
	var acompanhantes= document.getElementById("acompanhantes"+id_campo);
	var original= document.getElementById("id_pessoa_mesmo"+id_campo);
	
	if (original)
		var original_mesmo= original.value;
	else
		var original_mesmo= "-1";
	
	var encontrou = acompanhantes.value.search("@"+id_pessoa+"@");
	
	//alert("id_pessoa:"+id_pessoa);
	//alert("original:"+original.value);
	
	if ((encontrou==-1) && (id_pessoa!=original_mesmo)) {
		var novo_valor= acompanhantes.value+"@"+id_pessoa+"@";
		acompanhantes.value= novo_valor;
		ajaxLink("acompanhantes_atualiza"+id_campo, "fazLeituraAcompanhantes&valor="+novo_valor+"&id_campo="+id_campo);
		
		alert("Acompanhante inserido com sucesso!\n\nPara inserir outro, faça uma nova busca e aperte em \"Adicionar >>\"!");
	}
	else
		alert("Operação não permitida!");
}

function removeValorAcompanhante(id_campo, id_pessoa) {
	var acompanhantes= document.getElementById("acompanhantes"+id_campo);
	var novo_valor = acompanhantes.value.replace("@"+id_pessoa+"@", "");
	
	acompanhantes.value= novo_valor;
	
	ajaxLink("acompanhantes_atualiza"+id_campo, "fazLeituraAcompanhantes&valor="+novo_valor+"&id_campo="+id_campo);
}

function fazLeituraAcompanhantes(valor, campo) {
	ajaxLink("acompanhantes_atualiza"+campo, "fazLeituraAcompanhantes&valor="+valor);
}

function mostraSolicitacao(elemento) {
	if (elemento.value=="")
		preencheDiv("solicitacao_detalhes", "<span class=\"vermelho\">Seleciona a solicitação no campo acima!</span>");
	else
		ajaxLink("solicitacao_detalhes", "mostraSolicitacao&id_solicitacao="+elemento.value)
}

function usuarioRetornaCpfTfd(num) {
	var cpf= document.getElementById("cpf_usuario"+num).value;
	var erros= validaCpf(cpf);
	if (erros.length=="")
		ajaxLink('cpf_usuario_atualiza'+num, 'usuarioRetornaCpf&cpf='+cpf+'&tfd=1')
	else {
		var cpf_usuario_atualiza= document.getElementById('cpf_usuario_atualiza'+num);
		cpf_usuario_atualiza.innerHTML= "<span class=\"vermelho\">"+erros+"</span>";
	}
}

function usuarioRetornaCpfAcompanhanteTfd(num) {
	var cpf= document.getElementById("cpf_usuario_ac"+num).value;
	var erros= validaCpf(cpf);
	if (erros.length=="")
		ajaxLink('cpf_usuario_ac_atualiza'+num, 'usuarioRetornaCpf&cpf='+cpf+'&tfd=1&acompanhante=1&num='+num)
	else {
		var cpf_usuario_atualiza= document.getElementById('cpf_usuario_ac_atualiza'+num);
		cpf_usuario_atualiza.innerHTML= "<span class=\"vermelho\">"+erros+"</span>";
	}
}

function removeMedSaidaPessoa(id) {
	var teste= confirm('Tem certeza que deseja excluir este medicamento da entrega atual?');
	
	if (teste) {
		//var local = document.getElementById("mais_medicamentos");
		atribuiValor("qtde_pego_"+id, "0");
		var registro = document.getElementById("nova_div_"+id);
		registro.className="escondido";
		//local.removeChild(registro);
		
		//var qtde_sol= document.getElementById("qtde_sol");
		//var novo_valor= (parseInt(qtde_sol.value)-1);
		//qtde_sol.value= novo_valor;
	}
}

function insereNovoMedicamentoSaidaPessoa(id_remedio, qtde_atual, remedio) {
	do var qtde= prompt("Digite a quantidade a ser entregue DESTE medicamento: (SOMENTE NÚMEROS)\n\nQuantidade atual: "+qtde_atual);
	while ((qtde=="") || (parseInt(qtde)>parseInt(qtde_atual)));
	
	if ( (qtde!="") && (sohNumeros(qtde)) ) {
		var mais_medicamentos= document.getElementById("mais_medicamentos");
		
		var nova_table= document.createElement("table");
		nova_table.id= "nova_div_"+id_remedio;
		nova_table.width= "100%";
		nova_table.setAttribute("cellspacing", "0");
		
		var nova_tr= document.createElement("tr");
		
		var nova_td1= document.createElement("td");
		nova_td1.innerHTML= remedio;
		nova_td1.width= "35%";
		
		var nova_td2= document.createElement("td");
		nova_td2.innerHTML= qtde_atual +" unid(s)";
		nova_td2.width= "20%";
		nova_td2.setAttribute("align", "center");
		
		var nova_td3= document.createElement("td");
		nova_td3.innerHTML= "-";
		nova_td3.width= "15%";
		nova_td3.setAttribute("align", "center");
		
		var nova_td4= document.createElement("td");
		nova_td4.width= "20%";
		
		var nova_td5= document.createElement("td");
		nova_td5.width= "10%";
		
		var nova_id_remedio= document.createElement("input");
		nova_id_remedio.name= "id_remedio[]";
		nova_id_remedio.value= id_remedio;
		nova_id_remedio.className= "escondido";
		
		var nova_origem_saida= document.createElement("input");
		nova_origem_saida.name= "origem_saida[]";
		nova_origem_saida.value= "b";
		nova_origem_saida.className= "escondido";
		
		var nova_qtde_atual= document.createElement("input");
		nova_qtde_atual.name= "qtde_atual[]";
		nova_qtde_atual.value= qtde_atual;
		nova_qtde_atual.className= "escondido";
		
		var nova_qtde= document.createElement("input");
		nova_qtde.name= "qtde[]";
		nova_qtde.value= "0";
		nova_qtde.className= "escondido";
		
		var nova_qtde_pego= document.createElement("input");
		nova_qtde_pego.name= "qtde_pego[]";
		nova_qtde_pego.id= "qtde_pego_"+id_remedio;
		nova_qtde_pego.value= qtde;
		nova_qtde_pego.className= "tamanho30";
		
		var nova_tipo_apres= document.createElement("input");
		nova_tipo_apres.name= "tipo_apres[]";
		nova_tipo_apres.value= "u";
		nova_tipo_apres.className= "escondido";
		
		nova_td4.appendChild(nova_id_remedio);
		nova_td4.appendChild(nova_origem_saida);
		nova_td4.appendChild(nova_qtde_atual);
		nova_td4.appendChild(nova_qtde);
		nova_td4.appendChild(nova_qtde_pego);
		nova_td4.appendChild(nova_tipo_apres);
		
		var link_excluir= document.createElement("a");
		link_excluir.href= "javascript:removeMedSaidaPessoa('"+id_remedio+"')";
		link_excluir.setAttribute("onmouseover", "Tip('Clique para excluir o medicamento!');");
		link_excluir.innerHTML= "remover";
		link_excluir.className= "link_excluir";
		
		nova_td5.appendChild(link_excluir);
		
		nova_tr.appendChild(nova_td1);
		nova_tr.appendChild(nova_td2);
		nova_tr.appendChild(nova_td3);
		nova_tr.appendChild(nova_td4);
		nova_tr.appendChild(nova_td5);
		
		nova_table.appendChild(nova_tr);
		mais_medicamentos.appendChild(nova_table);
		
		//var qtde_sol= document.getElementById("qtde_sol");
		//var novo_valor= (parseInt(qtde_sol.value)+1);
		//qtde_sol.value= novo_valor;
		//ajaxLink("remedios_periodicos", "cadastraPeriodico&id_pessoa="+id_pessoa.value+"&id_remedio="+id_remedio+"&qtde="+qtde);
	}
	else
		alert("Para cadastrar, entre com um valor válido (somente números)!");
}



function cadastraPeriodico(id_remedio) {
	var id_pessoa= document.getElementById("id_pessoa_peri");
	
	do var qtde= prompt("Digite a quantidade regular para entrega DESTE medicamento: (SOMENTE NÚMEROS)");
	while (qtde=="");
	
	if ( (qtde!="") && (sohNumeros(qtde)) ) {
		if (id_pessoa.value=="")
			alert('Selecione a pessoa à inserir!');
		else
			ajaxLink("remedios_periodicos", "cadastraPeriodico&id_pessoa="+id_pessoa.value+"&id_remedio="+id_remedio+"&qtde="+qtde);
	}
	else
		alert("Para cadastrar, entre com um valor válido (somente números)!");
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

function somaDadosSSA2(y, total, j, elemento) {
	//alert("id_linha: "+y+", total: "+total+", id_microarea: "+j);
	
	if (elemento.value=="") elemento.value=0;
	
	var soma= 0;
	for (var i=1; i<total+1; i++) {
		var aqui= parseInt(document.getElementById("dado_"+i+"_"+y).value);
		//alert("posicao");
		soma += aqui;
	}
	var soma_linha= document.getElementById("soma_"+y);
	soma_linha.value= soma;
	
	//somar total de hospitalizacoes
	//total_hosp_i total_hosp
	if ((y>34) && (y<40)) {
		soma=0;
		//pegar valor to total_hosp daqui
		var total_hosp_j= document.getElementById("total_hosp_"+j);
		
		for (var k=35; k<40; k++) {
			var aqui= parseInt(document.getElementById("dado_"+j+"_"+k).value);
			soma += aqui;
		}
		
		total_hosp_j.value= soma;
		
		//somar o total de hosps
		var soma= 0;
		for (var i=1; i<total+1; i++) {
			var aqui= parseInt(document.getElementById("total_hosp_"+i).value);
			//alert("posicao");
			soma += aqui;
		}
		
		document.getElementById("total_hosp").value=soma;
	}
	//somar total de obitos
	//total_obito_i total_obito
	if ((y>40) && (y<58)) {
		soma=0;
		//pegar valor to total_obito daqui
		var total_obito_j= document.getElementById("total_obito_"+j);
		
		for (var k=40; k<58; k++) {
			var aqui= parseInt(document.getElementById("dado_"+j+"_"+k).value);
			soma += aqui;
		}
		
		total_obito_j.value= soma;
		
		//somar o total de obitos
		var soma= 0;
		for (var i=1; i<total+1; i++) {
			var aqui= parseInt(document.getElementById("total_obito_"+i).value);
			//alert("posicao");
			soma += aqui;
		}
		
		document.getElementById("total_obito").value=soma;
	}
	
	
}

function somaDadosPMA2(campo, elemento) {
	//alert("id_linha: "+y+", total: "+total+", id_microarea: "+j);
	if (elemento.value=="") elemento.value=0;
	
	var total_consultas_area= document.getElementById("total_consultas_area");
	var total_consultas= document.getElementById("total_consultas");
	var total_visitas_domiciliares= document.getElementById("total_visitas_domiciliares");
	
	var soma=0;
	
	if ((campo>=1) && (campo<12)) {
		for (var i=1; i<12; i++) {
			if (i!=2) {
				var aqui= parseInt(document.getElementById("campo_"+i).value);
				//alert("posicao");
				soma += aqui;
				if (i==1) var valor1= aqui;
			}
		}
		total_consultas.value= soma;
		total_consultas_area.value= (soma-valor1);
	}
	else {
		if ((campo>65) && (campo<71)) {
			for (var i=66; i<71; i++) {
				var aqui= parseInt(document.getElementById("campo_"+i).value);
				//alert("posicao");
				soma += aqui;
			}
			total_visitas_domiciliares.value= soma;
		}
	}
}


function verificaDestinoRel() {
	var local= document.getElementById("local");
	
	if (local.value=="0")
		habilitaCampo("local_d");
	else
		desabilitaCampo("local_d");
}

function abrePreConsulta(id_consulta) {
	
	ajaxLink('conteudo', 'carregaPagina&amp;pagina=_consultas/consulta_inserir&amp;id_agenda=<?= $rs->id_agenda; ?>')	
}

function consultaPesquisar() {
	var pesquisa= document.getElementById("pesquisa").value;
	ajaxLink('pesquisa_consulta_atualiza', 'consultaPesquisar&pesquisa='+pesquisa);
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

function pegaProntuario() {
	var cpf= document.getElementById("cpf_usuario").value;
	var erros= validaCpf(cpf);
	if (erros.length=="")
		ajaxLink('prontuario_atualiza', 'pegaProntuario&cpf='+cpf);
	else {
		var prontuario_atualiza= document.getElementById('prontuario_atualiza');
		prontuario_atualiza.innerHTML= "<span class=\"vermelho\">"+erros+"</span>";
	}
}

function pegaPeriodico() {
	var cpf= document.getElementById("cpf_usuario").value;
	var erros= validaCpf(cpf);
	if (erros.length=="")
		ajaxLink('periodico_atualiza', 'pegaPeriodico&cpf='+cpf);
	else {
		var periodico_atualiza= document.getElementById('periodico_atualiza');
		periodico_atualiza.innerHTML= "<span class=\"vermelho\">"+erros+"</span>";
	}
}

function alteraDadosAcompanhamento(id_pessoa) {
	ajaxLink("acompanhamento_dados", "alteraDadosAcompanhamento&id_pessoa="+id_pessoa);
}

function pegaProntuarioSemCpf(id_pessoa) {
	ajaxLink('prontuario_atualiza', 'pegaProntuarioSemCpf&id_pessoa='+id_pessoa);
}

function pegaPeriodicoSemCpf(id_pessoa) {
	ajaxLink('periodico_atualiza', 'pegaPeriodicoSemCpf&id_pessoa='+id_pessoa);
}

function pegaAcompSemCpf(id_pessoa) {
	ajaxLink('acompanhamento_ac', 'pegaAcompSemCpf&id_pessoa='+id_pessoa);
}

function alteraProfissional(tipo) {
	ajaxLink("profissionais_atualiza", "alteraProfissional&tipo="+tipo);
}

function materialPesquisar(origem) {
	var pesquisa= document.getElementById("pesquisa");
	
	if ( (pesquisa.value=="") || (pesquisa.value.length<3) ) {
		var pesquisa_material_atualiza= document.getElementById("pesquisa_material_atualiza");
		pesquisa_material_atualiza.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		pesquisa.focus();
	}
	else
		ajaxLink('pesquisa_material_atualiza', 'materialPesquisar&pesquisa='+pesquisa.value+'&origem='+origem);
}

function examePesquisar(ident, origem) {
	var pesquisa= document.getElementById("pesquisa2");
	
	if ( (pesquisa.value=="") || (pesquisa.value.length<3) ) {
		var pesquisa_exame_atualiza= document.getElementById("pesquisa_exame_atualiza");
		pesquisa_exame_atualiza.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		pesquisa.focus();
	}
	else
		ajaxLink('pesquisa_exame_atualiza', 'examePesquisar&pesquisa='+pesquisa.value+'&ident='+ident+'&origem='+origem);
}

function procedimentoOdontoPesquisar() {
	var pesquisa= document.getElementById("pesquisa3");
	
	if ( (pesquisa.value=="") || (pesquisa.value.length<3) ) {
		var pesquisa_procedimento_ondonto_atualiza= document.getElementById("pesquisa_procedimento_ondonto_atualiza");
		pesquisa_procedimento_ondonto_atualiza.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		pesquisa.focus();
	}
	else
		ajaxLink('pesquisa_procedimento_ondonto_atualiza', 'procedimentoOdontoPesquisar&pesquisa='+pesquisa.value);
}

function cidPesquisar() {
	var pesquisa= document.getElementById("pesquisa3");
	
	if ( (pesquisa.value=="") || (pesquisa.value.length<3) ) {
		var pesquisa_cid_atualiza= document.getElementById("pesquisa_cid_atualiza");
		pesquisa_cid_atualiza.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		pesquisa.focus();
	}
	else
		ajaxLink('pesquisa_cid_atualiza', 'cidPesquisar&pesquisa='+pesquisa.value);
}



function remedioPesquisar(ident, origem) {
	var pesquisa= document.getElementById("pesquisa");
	
	if ( (pesquisa.value=="") || (pesquisa.value.length<3) ) {
		var pesquisa_remedio_atualiza= document.getElementById("pesquisa_remedio_atualiza");
		pesquisa_remedio_atualiza.innerHTML= "<span class=\"vermelho\">Entre com pelo menos 3 caracteres para realizar a busca!</span>";
		pesquisa.focus();
	}
	else
		ajaxLink('pesquisa_remedio_atualiza', 'remedioPesquisar&pesquisa='+pesquisa.value+'&ident='+ident+'&origem='+origem);
}

function removeRemedioReceita(id_remedio) {
	var local = document.getElementById("receita_ok");
	var registro = document.getElementById("receita_"+id_remedio);
	local.removeChild(registro);
	
	//var local_rel = document.getElementById("receita_rel");
	//var registro_rel = document.getElementById("receita_rel_"+ id_remedio);
	//local_rel.removeChild(registro_rel);
	
	var local_atualizado = document.getElementById("receita_ok");
	var num_divs = local_atualizado.getElementsByTagName("div");
	
	//se voltar a ficar vazio poe a msg em vermelho =)
	try {
		if (num_divs.length==0) {
			var str= "<span class=\"vermelho\">Nenhum remédio receitado até o momento!</span>";
			local.innerHTML= str;
			//local_rel.innerHTML= str;
		}
	}
	catch(ee) {
		alert("Erro, nao foi possivel atualizar a receita, mas o processo pode continuar normalmente!");
	}
}

function exameCadastroOk() {
	var exame= document.getElementById("exame");
	var tipo_exame= document.getElementById("tipo_exame");
	
	if (exame.value!="") {
		var teste= confirm('Tem certeza que deseja cadastrar esse exame?\n\nTenha absoluta certeza dele não existir no sistema,\npara evitar cadastros duplicados!');
		if (teste)
			ajaxLink("exame_cadastro3", "exameInserir&exame="+exame.value+"&tipo_exame="+tipo_exame.value);
	}
	else
		alert("Preencha o campo exame!");
}

function removeDiagnosticoInicial() {
	var diagnostico_ok = document.getElementById("diagnostico_ok");
	diagnostico_ok.innerHTML= "<span class='vermelho'>Nada selecionado até o momento!</span>";
	
	var diagnostico_inicial = document.getElementById("diagnostico_inicial");
	diagnostico_inicial.value="";
}


function removeExame(id) {
	var local = document.getElementById("lista_ul");
	var registro = document.getElementById("li_exame_"+id);
	local.removeChild(registro);
	
	var lista_ul= document.getElementById("lista_ul");
	var contaExames = lista_ul.getElementsByTagName("li");
	
	if (contaExames.length==0) {
		var div_mesmo= document.getElementById("exames_solicitacao_ok");
		div_mesmo.innerHTML= "<span class='vermelho'>Nenhum exame solicitado até o momento!</span>";
	}
	//no relatorio
	
	/*var local = document.getElementById("rel_exames_atualiza");
	var registro = document.getElementById("exame_rel_"+id);
	local.removeChild(registro);
	
	var num_exames_rel= local.getElementsByTagName("div");
	
	if (num_exames_rel.length==0)
		local.innerHTML= "<span class='vermelho'>Nenhum exame solicitado!</span>";*/
}

function removeProcedimentoOdonto(id) {
	var local = document.getElementById("lista_ul");
	var registro = document.getElementById("li_procedimento_"+id);
	local.removeChild(registro);
	
	var lista_ul= document.getElementById("lista_ul");
	var contaExames = lista_ul.getElementsByTagName("li");
	
	if (contaExames.length==0) {
		var div_mesmo= document.getElementById("tratamentos_ok");
		div_mesmo.innerHTML= "<span class='vermelho'>Nenhum tratamento executado até o momento!</span>";
	}
	//no relatorio
	/*
	var local = document.getElementById("rel_exames_atualiza");
	var registro = document.getElementById("exame_rel_"+id);
	local.removeChild(registro);
	
	var num_exames_rel= local.getElementsByTagName("div");
	
	if (num_exames_rel.length==0)
		local.innerHTML= "<span class='vermelho'>Nenhum exame solicitado!</span>";
	*/
}


function adicionaOdontoProcedimento(id, nome) {
	var div_mesmo= document.getElementById("tratamentos_ok");
	
	var exame= document.createElement("input");
	exame.name= "id_oprocedimento[]";
	exame.id= "id_oprocedimento"+id;
	exame.className= "escondido";
	exame.value= id;
	
	var nome_exame= document.createElement("div");
	nome_exame.className= "nomeDeExame";
	nome_exame.innerHTML= nome;
	
	var link_excluir= document.createElement("a");
	link_excluir.href= "javascript:removeProcedimentoOdonto('"+id+"')";
	link_excluir.title= "clique para excluir o procedimento";
	link_excluir.innerHTML= "remover";
	
	var item_lista= document.createElement("li");
	item_lista.id= "li_procedimento_"+id;
	
	item_lista.appendChild(exame);
	item_lista.appendChild(nome_exame);
	item_lista.appendChild(link_excluir);
	
	var lista= div_mesmo.getElementsByTagName("ul");
	
	if (lista.length==0) {
		var lista_ul= document.createElement("ul");
		lista_ul.id= "lista_ul";
		lista_ul.className= "recuo1";
	}
	else
		var lista_ul= document.getElementById("lista_ul");
	
	var contaExames = lista_ul.getElementsByTagName("li");
	
	if (contaExames.length==0)
		div_mesmo.innerHTML= "";

	try {
		var teste_exame= lista_ul.getElementsByTagName("li");
		/*
		var passa=1;
		
		for (var i=0; i<teste_exame.length; i++) {
			if (teste_exame[i].id=="li_procedimento_"+id) {
				passa=0;
				break;
			}
		}
		
		if(passa==1) {*/
			lista_ul.appendChild(item_lista);
			div_mesmo.appendChild(lista_ul);
			
			/*
			var rel_exames_atualiza= document.getElementById("rel_exames_atualiza");
			
			var num_exames_rel= rel_exames_atualiza.getElementsByTagName("div");

			if (num_exames_rel.length==0)
				rel_exames_atualiza.innerHTML= "";
			
			var exame_rel= document.createElement("div");
			exame_rel.id="exame_rel_"+id;
			exame_rel.className= "flutuar_esquerda";
			exame_rel.innerHTML=nome+";&nbsp;";
			
			rel_exames_atualiza.appendChild(exame_rel);
			
		}
		else
			alert("Exame já solicitado!");*/

	}
	catch (eee) { }
}


function adicionaExame(id, nome) {
	var div_mesmo= document.getElementById("exames_solicitacao_ok");
	
	var exame= document.createElement("input");
	exame.name= "id_exame[]";
	exame.id= "id_exame"+id;
	exame.className= "escondido";
	exame.value= id;
	
	var nome_exame= document.createElement("div");
	nome_exame.className= "nomeDeExame";
	nome_exame.innerHTML= nome;
	
	var link_excluir= document.createElement("a");
	link_excluir.href= "javascript:removeExame('"+id+"')";
	link_excluir.title= "clique para excluir o exame";
	link_excluir.innerHTML= "remover";
	
	var item_lista= document.createElement("li");
	item_lista.id= "li_exame_"+id;
	
	item_lista.appendChild(exame);
	item_lista.appendChild(nome_exame);
	item_lista.appendChild(link_excluir);
	
	var lista= div_mesmo.getElementsByTagName("ul");
	
	if (lista.length==0) {
		var lista_ul= document.createElement("ul");
		lista_ul.id= "lista_ul";
		lista_ul.className= "recuo1";
	}
	else
		var lista_ul= document.getElementById("lista_ul");
	
	var contaExames = lista_ul.getElementsByTagName("li");
	
	if (contaExames.length==0)
		div_mesmo.innerHTML= "";

	try {
		var teste_exame= lista_ul.getElementsByTagName("li");
		
		var passa=1;
		
		for (var i=0; i<teste_exame.length; i++) {
			if (teste_exame[i].id=="li_exame_"+id) {
				passa=0;
				break;
			}
		}
		
		if(passa==1) {
			lista_ul.appendChild(item_lista);
			div_mesmo.appendChild(lista_ul);
			alert("Solicitação de exame adicionada com sucesso!");
			/*
			var rel_exames_atualiza= document.getElementById("rel_exames_atualiza");
			
			var num_exames_rel= rel_exames_atualiza.getElementsByTagName("div");

			if (num_exames_rel.length==0)
				rel_exames_atualiza.innerHTML= "";
			
			var exame_rel= document.createElement("div");
			exame_rel.id="exame_rel_"+id;
			exame_rel.className= "flutuar_esquerda";
			exame_rel.innerHTML=nome+";&nbsp;";
			
			rel_exames_atualiza.appendChild(exame_rel);
			*/
		}
		else
			alert("Exame já solicitado!");

	}
	catch (eee) {	
	}
}

function remedioCadastroOk() {
	var remedio= document.getElementById("remedio");
	var tipo_remedio= document.getElementById("tipo_remedio");
	var classificacao_remedio= document.getElementById("classificacao_remedio");
	var controlado_val;
	
	if (classificacao_remedio.checked) controlado_val= "c";
	else controlado_val= "n";
		
	if ((remedio.value!="") && (remedio.value.length>3) && (tipo_remedio.value!="")) {
		var teste= confirm('Tem certeza que deseja cadastrar esse medicamento?\n\nTenha absoluta certeza dele não existir no sistema,\npara evitar cadastros duplicados!');
		
		if (teste)
			ajaxLink("remedio_cadastro3", "remedioInserir&remedio="+remedio.value+"&tipo_remedio="+tipo_remedio.value+"&classificacao_remedio="+controlado_val);
	}
	else {
		alert("Preencha o campo remédio e selecione o tipo!\n\nO campo remédio deve ser preenchido com no mínimo 3 caracteres!");
		daFoco('remedio');
	}
}

function alteraLocaisAplicacao(valor) {
	switch(valor) {
		case 't': fechaDiv("consulta_nebulizar"); fechaDiv("consulta_aplicar"); break;
		case 'a': fechaDiv("consulta_nebulizar"); abreDivSo("consulta_aplicar"); break;
		case 'n': fechaDiv("consulta_aplicar"); abreDivSo("consulta_nebulizar"); break;
	}
}

function remedioReceitaOk() {
	var local = document.getElementById("receita_ok");
	//var local_rel = document.getElementById("receita_rel");
	
	var registro = document.createElement("div");
	//var registro_rel = document.createElement("div");
	
	registro.className= "div_receita_ok";
	//registro_rel.className= "div_receita_ok";
		
	var tit_remedio_pre= document.getElementById("tit_remedio_pre");
	var id_remedio_pre= document.getElementById("id_remedio_pre");
	var qtde= document.getElementById("qtde");
	var tipo_apresentacao= document.getElementById("tipo_apresentacao");
	var tipo_acao= document.getElementById("tipo_acao");
	var acao_local= document.getElementById("acao_local");
	var neb_com= document.getElementById("neb_com");
	var qtde_tomar= document.getElementById("qtde_tomar");
	var tipo_tomar= document.getElementById("tipo_tomar");
	var periodicidade1= document.getElementById("periodicidade1");
	var periodicidade2= document.getElementById("periodicidade2");
	var periodo= document.getElementById("periodo");
	var observacoes= document.getElementById("observacoes");
	
	registro.id= "receita_"+id_remedio_pre.value;
	//registro_rel.id= "receita_rel_"+id_remedio_pre.value;
	
	//validacao
	
	var erros= "";
	var foco= null;
	var nonNumbers = /\D/;
	
	if (qtde.value=="") {
		if (foco==null) foco= qtde;
		erros += "Entre com a quantidade a ser entregue para o paciente!\n";
	}
	else {
		if (nonNumbers.test(qtde.value)) {
			if (foco==null) foco= qtde;
			erros += "O campo quantidade aceita somente números!\n";
		}
	}
	
	if (qtde_tomar.value=="") {
		if (foco==null) foco= qtde_tomar;
		erros += "Entre com a quantidade de remédios para o paciente tomar (receita)!\n";
	}
	else {
		if (nonNumbers.test(qtde_tomar.value)) {
			if (foco==null) foco= qtde_tomar;
			erros += "O campo quantidade a tomar aceita somente número!\n";
		}
	}
	
	var periodicidade;
	var str_per;
	
	if (periodicidade1.value!="") {
		periodicidade= periodicidade1;
		str_per= "d";
	}
	if (periodicidade2.value!="") {
		periodicidade= periodicidade2;
		str_per= "h";
	}
	
	if ( (periodicidade1.value!="") || (periodicidade2.value!="") ) {
		if (periodicidade.value=="") {
			if (foco==null) foco= periodicidade;
			erros += "Entre com a periodicidade do uso do medicamento (receita)!\n";
		}
		else {
			if (nonNumbers.test(periodicidade.value)) {
				if (foco==null) foco= periodicidade1;
				erros += "O campo periodicidade aceita somente números!\n";
			}
		}
	}
	else {
		if (foco==null) foco= periodicidade1;
		erros += "Entre com a periodicidade do uso do medicamento (receita)!\n";	
	}

	if (periodo.value!="") {
		if (nonNumbers.test(periodo.value)) {
			if (foco==null) foco= periodo;
			erros += "O campo período aceita somente número!\n";
		}
	}


	if (foco!=null)
		foco.focus();
	if (erros.length>0) {
		alert (erros);
	}
	else {
		//esse
		var _tit_remedio_pre= document.createElement("h3");
		//var _tit_remedio_pre_rel= document.createElement("h3");
		
		var _link_excluir= document.createElement("a");
		//esse
		var _infos= document.createElement("span");
		//var _infos_rel= document.createElement("span");
		
		var _id_remedio= document.createElement("input");
		var _qtde= document.createElement("input");
		var _tipo_apresentacao= document.createElement("input");
		var _tipo_acao= document.createElement("input");
		var _acao_local= document.createElement("input");
		var _neb_com= document.createElement("input");
		var _qtde_tomar= document.createElement("input");
		var _tipo_tomar= document.createElement("input");
		var _tipo_periodicidade= document.createElement("input");
		var _periodicidade= document.createElement("input");
		var _periodo= document.createElement("input");
		var _observacoes= document.createElement("input");
		var _sep= document.createElement("br");
		
		var contaRems = local.getElementsByTagName("div");
		
		//if ainda nao tem nenhum remedio limpa a div
		if (contaRems.length==0)
			local.innerHTML= "";

		//var contaRems_rel = local_rel.getElementsByTagName("div");
		
		//if ainda nao tem nenhum remedio limpa a div
		//if (contaRems_rel.length==0)
		//	local_rel.innerHTML= "";

		var tipo_apres_string= "";
		var tipo_tomar_string= "";
		var tipo_acao_string= "";
		
		switch(tipo_apresentacao.value) {
			case 'c': tipo_apres_string= "caixa(s)"; break;
			case 'u': tipo_apres_string= "unidade(s)"; break;
		}
		switch(tipo_tomar.value) {
			case 'c': tipo_tomar_string= "comprimido(s) "; break;
			case 'g': tipo_tomar_string= "gota(s) "; break;
			case 'i': tipo_tomar_string= "injeção(s) "; break;
			case 'm': tipo_tomar_string= "ml "; break;
			case 's': tipo_tomar_string= "spray "; break;
			case 'f': tipo_tomar_string= "flaconete "; break;
			case 'd': tipo_tomar_string= "dose "; break;
		}
		switch(tipo_acao.value) {
			case 't': tipo_acao_string= "Tomar "; break;
			case 'a': tipo_acao_string= "Aplicar "; break;
			case 'n': tipo_acao_string= "Nebulizar "; break;
		}
		
		var via_string="";
		
		if(tipo_acao.value=='a') {
			switch(acao_local.value) {
				case '1': via_string= "via endovenosa";
				case '2': via_string= "via intra-muscular";
				case '3': via_string= "via subcutânea";
				case '4': via_string= "via intra dérmica";
				case '5': via_string= "via sub-lingual";
				case '6': via_string= "(uso tópico)";
			}
		}
		
		if(tipo_acao.value=='n')
			via_string= neb_com.value;
		
		if (tipo_acao.value=='n')
			var informacoes = "<br /><p>"+tipo_acao_string+" "+tit_remedio_pre.value+" ("+ qtde_tomar.value+" "+tipo_tomar_string+") com "+via_string;
		else
			var informacoes = "<br /><p>"+tipo_acao_string+" "+via_string+" "+qtde_tomar.value+" "+tipo_tomar_string;
		
		if (str_per=='d')
			informacoes += " "+periodicidade.value+" vez(es) ao dia ";
		if (str_per=='h')
			informacoes += " de "+periodicidade.value+" em "+periodicidade.value+" hora(s) ";
		
		if (periodo.value!="")
			informacoes += "por "+periodo.value+" dia(s).</p>";
		
		informacoes += "<p><em>"+observacoes.value+"</em></p>";
		
		_infos.innerHTML= informacoes;
		//_infos_rel.innerHTML= informacoes;
		
		_tit_remedio_pre.className= "flutuar_esquerda";
		_tit_remedio_pre.innerHTML= tit_remedio_pre.value+" - "+qtde.value+" "+tipo_apres_string;

		//_tit_remedio_pre_rel.className= "flutuar_esquerda";
		//_tit_remedio_pre_rel.innerHTML= tit_remedio_pre.value+" - "+qtde.value+" "+tipo_apres_string;

		_link_excluir.className= "fechar";
		_link_excluir.href= "javascript:removeRemedioReceita('"+id_remedio_pre.value+"')";
		_link_excluir.title= "clique para retirar este remédio da receita";
		_link_excluir.innerHTML= "x";
		
		_id_remedio.value= id_remedio_pre.value;
		_id_remedio.className= "escondido";
		_id_remedio.type= "hidden";
		_id_remedio.name= "pos_id_remedio[]";
		
		_qtde.value= qtde.value;
		_qtde.className= "escondido";
		_qtde.type= "hidden";
		_qtde.name= "pos_qtde[]";
		
		_tipo_apresentacao.value= tipo_apresentacao.value;
		_tipo_apresentacao.className= "escondido";
		_tipo_apresentacao.type= "hidden";
		_tipo_apresentacao.name= "pos_tipo_apresentacao[]";
		
		_tipo_acao.value= tipo_acao.value;
		_tipo_acao.className= "escondido";
		_tipo_acao.type= "hidden";
		_tipo_acao.name= "pos_tipo_acao[]";
		
		_acao_local.value= acao_local.value;
		_acao_local.className= "escondido";
		_acao_local.type= "hidden";
		_acao_local.name= "pos_acao_local[]";
		
		_neb_com.value= neb_com.value;
		_neb_com.className= "escondido";
		_neb_com.type= "hidden";
		_neb_com.name= "pos_neb_com[]";
		
		_qtde_tomar.value= qtde_tomar.value;
		_qtde_tomar.className= "escondido";
		_qtde_tomar.type= "hidden";
		_qtde_tomar.name= "pos_qtde_tomar[]";
		
		_tipo_tomar.value= tipo_tomar.value;
		_tipo_tomar.className= "escondido";
		_tipo_tomar.type= "hidden";
		_tipo_tomar.name= "pos_tipo_tomar[]";
		
		_tipo_periodicidade.value= str_per;
		_tipo_periodicidade.className= "escondido";
		_tipo_periodicidade.type= "hidden";
		_tipo_periodicidade.name= "pos_tipo_periodicidade[]";

		_periodicidade.value= periodicidade.value;
		_periodicidade.className= "escondido";
		_periodicidade.type= "hidden";
		_periodicidade.name= "pos_periodicidade[]";
		
		_periodo.value= periodo.value;
		_periodo.className= "escondido";
		_periodo.type= "hidden";
		_periodo.name= "pos_periodo[]";
		
		_observacoes.value= observacoes.value;
		_observacoes.className= "escondido";
		_observacoes.type= "hidden";
		_observacoes.name= "pos_observacoes[]";
		
		local.appendChild(registro);
		//local_rel.appendChild(registro_rel);
		
		//registro_rel.appendChild(_tit_remedio_pre_rel);
		//registro_rel.appendChild(_infos_rel);
		
		registro.appendChild(_tit_remedio_pre);
		registro.appendChild(_link_excluir);
		registro.appendChild(_infos);
		registro.appendChild(_id_remedio);
		registro.appendChild(_qtde);
		registro.appendChild(_tipo_apresentacao);
		registro.appendChild(_tipo_acao);
		registro.appendChild(_acao_local);
		registro.appendChild(_neb_com);
		registro.appendChild(_qtde_tomar);
		registro.appendChild(_tipo_tomar);
		registro.appendChild(_tipo_periodicidade);
		registro.appendChild(_periodicidade);
		registro.appendChild(_periodo);
		registro.appendChild(_observacoes);
		//registro.appendChild(_sep);
		
		fechaDiv("receita_remedio");
		
		alert("Prescrição adicionada com sucesso!");
		
		qtde.value= "";
		qtde_tomar.value= "";
		periodicidade1.value= "";
		periodicidade2.value= "";
		periodo.value= "";
		observacoes.value= "";
		
		daFoco("qtde");
	}
}

function retornaCidades() {
	var id_uf= document.getElementById('id_uf').value;
	ajaxLink("id_cidade_atualiza", "retornaCidades&id_uf="+id_uf);
}

function retornaPostos() {
	var id_cidade= document.getElementById('id_cidade_em').value;
	ajaxLink("id_posto_atualiza", "retornaPostos&id_cidade="+id_cidade);
}

function retornaPsfs() {
	var id_cidade= document.getElementById('id_cidade').value;
	ajaxLink("id_origem_atualiza", "retornaPsfs&id_cidade="+id_cidade);
}

function retornaMicroareas() {
	var id_psf= document.getElementById('id_psf').value;
	ajaxLink("id_microarea_atualiza", "retornaMicroareas&id_psf="+id_psf);
}

function retornaCBOs() {
	var id_ofamilia= document.getElementById('id_ofamilia').value;
	ajaxLink("id_cbo_atualiza", "retornaCBOs&id_ofamilia="+id_ofamilia);
}

function alteraCbosProcedimento() {
	var id_procedimento= document.getElementById('id_procedimento').value;
	
	switch(id_procedimento) {
		case "3":
		case "4":
		case "5":
		case "6":
		case "7":
		case "8":
		case "12":
		case "13":
		case "14":
		case "15":
		case "16":
		case "17":
				fechaDiv("procedimentos_cbos");
				abreDiv("procedimentos_identificar");
				break;
		case "9":
				abreDiv("procedimentos_cbos");
				fechaDiv("procedimentos_identificar");
				break;
		default: fechaDiv("procedimentos_cbos");
				 fechaDiv("procedimentos_identificar");
	}
}

function atualizaSolicitacoesTfd(num_campo, id_pessoa) {
	//alert('trocando..'+num_campo+'...'+id_pessoa);
	preencheDiv("solicitacao_detalhes", "<span class=\"vermelho\">Seleciona a solicitação no campo acima!</span>");
	ajaxLink("solicitacao_atualiza"+num_campo, "atualizaSolicitacoesTfd&id_pessoa="+id_pessoa);
}

function atualizaHistorico(ident) {
	//historico de consultas
	var id_pessoa= document.getElementById("id_pessoa_mesmo");
	
	if (ident==1)
		ajaxLink("historico_resumo", "carregaPaginaInterna&pagina=_pessoas/historico_consultas_resumo&id_pessoa_hist="+id_pessoa.value+"&tipo_hist=v");
	//historico de medicamentos
	else
		ajaxLink("almox_direita2", "carregaPaginaInterna&pagina=_pessoas/historico_meds_resumo&id_pessoa_hist="+id_pessoa.value+"&tipo_hist=v");
}

function alteraDestinoSaidaMats() {
	var destino_saida_atualiza= document.getElementById("destino_saida_atualiza");
	var subtipo_trans= document.getElementById("subtipo_trans");
	var modo_almox= document.getElementById("modo_almox");
	
	//se for distribuição
	//se for farmacia q soh identifica controlado e o remedio for controlado
	//se for farmacia q identifica todos os tipos de remédio
	if ((subtipo_trans.value=="b") && (modo_almox.value==2)) {
		desabilitaCampo("botaoInserir");
		destino_saida_atualiza.innerHTML="<label>CPF:</label> <input name=\"cpf_usuario\" id=\"cpf_usuario\" onblur=\"usuarioRetornaCpfCompleto('a');\" maxlength=\"11\" />"+
									"<button type=\"button\" onclick=\"abreFechaDiv('pessoa_buscar'); daFoco('nomeb');\">busca</button><br />"+
									"<label>&nbsp;</label><div id=\"cpf_usuario_atualiza\"><input type=\"hidden\" name=\"id_pessoa\" id=\"id_pessoa_form\" value=\"0\" class=\"escondido\" /></div><br />";
	}
	else {
		destino_saida_atualiza.innerHTML= "";
		habilitaCampo("botaoInserir");
	}
}


function alteraDestinoSaida() {
	var destino_saida_atualiza= document.getElementById("destino_saida_atualiza");
	var subtipo_trans= document.getElementById("subtipo_trans");
	var classificacao_remedio= document.getElementById("classificacao_remedio");
	var modo_farmacia= document.getElementById("modo_farmacia");
	
	//se for distribuição
	//se for farmacia q soh identifica controlado e o remedio for controlado
	//se for farmacia q identifica todos os tipos de remédio
	if ( ((subtipo_trans.value=="b") && (modo_farmacia.value==1) && (classificacao_remedio.value=="c") ) || ( (subtipo_trans.value=="b") && (modo_farmacia.value==2)) ) {
		desabilitaCampo("botaoInserir");
		destino_saida_atualiza.innerHTML="<label>CPF:</label> <input name=\"cpf_usuario\" id=\"cpf_usuario\" onblur=\"usuarioRetornaCpfCompleto('f');\" maxlength=\"11\" />"+
									"<button type=\"button\" onclick=\"abreFechaDiv('pessoa_buscar'); daFoco('nomeb');\">busca</button><br />"+
									"<label>&nbsp;</label><div id=\"cpf_usuario_atualiza\"><input type=\"hidden\" name=\"id_pessoa\" id=\"id_pessoa_form\" value=\"0\" class=\"escondido\" /></div><br />";
	}
	else {
		destino_saida_atualiza.innerHTML= "";
		habilitaCampo("botaoInserir");
	}
}

function usuarioRetornaCpfDisponibilidade() {
	var modo_cadastro_cpf= document.getElementById("modo_cadastro_cpf").value;
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


function verificaSeJaTemRemedio(div, campo_destino1, valor1, campo_destino2, valor2) {
	//var local= document.getElementById("receita_ok");
	var regiao = document.getElementById("receita_"+valor1);
	
	//se nao existir a div ainda
	try {
		if (regiao)
			alert('Este remédio já está na receita!\nPara corrigir os dados, exclua o registro à direita na tela!');
		else {
			abreDiv(div, campo_destino1, valor1, campo_destino2, valor2);
			daFoco("qtde");
		}
	}
	catch(ee) {

	}

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

function trocaCamposSaida(classificacao_remedio) {
	var subtipo_trans= document.getElementById("subtipo_trans").value;
	var destino_saida_atualiza= document.getElementById("destino_saida_atualiza");
	
	if (classificacao_remedio=="c") {
		if (subtipo_trans=="b") {
			desabilitaCampo('botaoInserir');
			destino_saida_atualiza.innerHTML="<label>CPF:</label> <input name=\"cpf_usuario\" id=\"cpf_usuario\" onblur=\"usuarioRetornaCpfCompleto('f');\" maxlength=\"11\" /><br /><label>&nbsp;</label><div id=\"cpf_usuario_atualiza\"><input type=\"hidden\" name=\"id_pessoa\" id=\"id_pessoa_form\" value=\"0\" class=\"escondido\" /></div><br />";
		}
	}
	else {
		habilitaCampo('botaoInserir');
		preencheDiv('destino_saida_atualiza', '<input type=\'hidden\' name=\'id_pessoa\' id=\'id_pessoa_form\' value=\'0\' class=\'escondido\' />');	
	}
}

function daFoco(campo) {
	document.getElementById(campo).focus();
}
function daBlur(campo) {
	document.getElementById(campo).blur();
}

function trocaFundoDente(elemento, cor, zindex) {
	if (elemento.id=="out") {
		elemento.style.background=cor;
		elemento.style.zIndex=zindex;
	}
}

function setaFaceDente(elemento, id_dente, face) {
	//jah setado
	if (elemento.id=="in") {
		elemento.style.background="#EFEFEF";
		elemento.id="out";
		elemento.style.zIndex="1";
		document.getElementById("problema_"+id_dente+"_"+face).value="0";
	}
	else {
		elemento.style.background="#000000";
		elemento.id="in";
		elemento.style.zIndex="100";
		document.getElementById("problema_"+id_dente+"_"+face).value="1";
	}
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
		case "formRemedioInserir":
		case "formRemedioEditar":
			var remedio= document.getElementById("remedio");
			var tipo_remedio= document.getElementById("tipo_remedio");
			if (remedio.value=="") {
				if (foco==null) foco= remedio;
				erros += "Preencha o campo remédio!\n";
			}
			if (tipo_remedio.value=="") {
				if (foco==null) foco= tipo_remedio;
				erros += "Selecione o tipo do remédio!\n";
			}
			break;
		case "formExameInserir":
			var exame= document.getElementById("exame");
			if (exame.value=="") {
				if (foco==null) foco= exame;
				erros += "Preencha o campo exame!\n";
			}
			break;
		case "formProntuario":
			var cpf= document.getElementById("cpf_usuario").value;
			//alert(cpf);
			erros += validaCpf(cpf);
			break;
		case "formFilaInserir":
			var id_pessoa= document.formFilaInserir.id_pessoa;
			
			if (id_pessoa.value=="")
				erros += "Selecione a pessoa na lista!\n";
			break;
		case "formPreConsulta":
			var id_agenda= document.getElementById("id_agenda");
			
			if (id_agenda.value=="")
				erros += "Selecione a pessoa na lista!\n";

			break;
		case "formAgendaInserir":
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			var dia_agendamento= document.getElementById("dia_agendamento");
			var hora_agendamento= document.getElementById("hora_agendamento");
			
			if (id_pessoa.value=="")
				erros += "Selecione a pessoa na lista!\n";
			
			if (dia_agendamento.value=="") {
				if (foco==null) foco= dia_agendamento;
				erros += "Digite o dia da consulta!\n";
			}
			if (hora_agendamento.value=="") {
				if (foco==null) foco= hora_agendamento;
				erros += "Digite a hora da consulta!\n";
			}

			break;
		case "formConsultaInserir":
			var id_pessoa= document.getElementById("id_pessoa").value;
			if (id_pessoa=="")
				erros += "Selecione a pessoa!\n";
			break;
			
		case "formConsultaEditar":
			var id_consulta= document.getElementById("id_consulta").value;
			if (id_consulta=="")
				erros += "Erro! Recarregue a página!\n";
			break;
			
		case "formPessoaBuscar":
			/*var txt_busca= document.getElementById("txt_busca");
			
			if ( (txt_busca.value=="") || (txt_busca.value.length<3) ) {
				if (foco==null) foco= txt_busca;
				erros += "Entre com pelo menos 3 caracteres para realizar a busca!\n";
			}*/
			break;
		case "formProcInserir":
			var qtde= document.getElementById("qtde");
			var data= document.getElementById("data");
			var erros_data_solicitacao= validaData(data.value);
			
			if (qtde.value=="") {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade!\n";
			}
			if (erros_data_solicitacao.length>0) {
				erros += erros_data_solicitacao;
				if (foco==null) foco= data;
			}

			break;
		case "formAcompInserir":
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			var data= document.getElementById("data");
			var erros_data_solicitacao= validaData(data.value);
			
			//alert("uala!!!: "+ id_pessoa.value);
			
			if (id_pessoa.value=="") {
				if (foco==null) foco= qtde;
				erros += "Selecione a pessoa!\n";
			}
			else {
				var tipo_acompanhamento= document.getElementById("tipo_acompanhamento");
				var estado_nutricional= document.getElementById("estado_nutricional_campo");
				var peso= document.getElementById("peso");
				
				if (tipo_acompanhamento.value=="") {
					if (foco==null) foco= id_pessoa;
					erros += "Selecione a pessoa!\n";
				}
				
				if (estado_nutricional.value=="") {
					if (foco==null) foco= peso;
					erros += "Insira os dados para calcular o estado nutricional!\n";
				}
			}
			
			if (erros_data_solicitacao.length>0) {
				erros += erros_data_solicitacao;
				if (foco==null) foco= data;
			}

			break;
		case "formGrupoInserir":
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			
			if (id_pessoa.value=="")
				erros += "Selecione a pessoa!\n";

			break;
		case "formPessoaInserir":
		case "formPessoaEditar":
			var modo_cadastro_cpf= document.getElementById("modo_cadastro_cpf");
			var id_responsavel= document.getElementById("id_responsavel");
			var nome= document.getElementById("nome");
			
			if (id_responsavel.value=="0")
				var cpf= document.getElementById("cpf_cadastro");
			
			var nome_mae= document.getElementById("nome_mae");
			var data_nasc= document.getElementById("data_nasc");
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
				if ((modo_cadastro_cpf.value==2) && (cpf.value==""))
					erros2 += validaCpf(cpf.value);
				else
					erros += validaCpf(cpf.value);
			}
		
			if ((id_form=="formPessoaInserir") && (retorno.value=="conteudo")) {
				var cpf_disponivel= document.getElementById("cpf_disponivel");
				
				if (id_form=="formPessoaInserir") {
					if (modo_cadastro_cpf.value!=2) {
						if (cpf_disponivel.value=="0")
							erros += "CPF não testado ou indisponível para cadastro!";
					}
					//se o CPF não for obrigatório...
					//data de nascimento ou nome da mãe obrigatórios
					else {
						//se o campo cpf estiver vazio
						if (cpf.value=="") {
							var erros_data_nasc;
							var erros_nome_mae;
							
							if (nome_mae.value=="")
								erros += "Entre com a o nome da mãe!\n(você está fazendo o cadastro sem o CPF).\n";
								
							erros += validaData(data_nasc.value);
						}
						else {
							erros += validaCpf(cpf.value);
						}
					}
				}
				//editar
				else {
					if (id_responsavel.value==0) {
						if ((modo_cadastro_cpf.value==2) && (cpf.value==""))
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

		case "formEstoqueMinimo":
			var id_remedio= document.getElementById("id_remedio");
			var qtde_minima= document.getElementById("qtde_minima");

			if (id_remedio.value=="") {
				erros += "Recarregue o sistema através do botão F5!\n";
			}
			if (qtde_minima.value=="") {
				if (foco==null) foco= qtde_minima;
				erros += "Digite a quantidade mínima do medicamento!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde_minima.value)) {
					if (foco==null) foco= qtde_minima;
					erros += "O campo \"quantidade mínima\" aceita somente números!\n";
				}
			}
		break;

		case "formAlmoxEntrada":
			var id_remedio= document.getElementById("id_remedio");
			var qtde= document.getElementById("qtde");
			var subtipo_trans= document.getElementById("subtipo_trans");

			if (id_remedio.value=="") {
				if (foco==null) foco= id_remedio;
				erros += "Selecione o remédio para dar entrada no estoque!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do medicamento a dar entrada em estoque!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
			}
			if (subtipo_trans.value=="") {
				if (foco==null) foco= subtipo_trans;
				erros += "Selecione a origem dos remédios!\n";
			}

		break;

		case "formAlmoxSaida":
			var id_remedio= document.getElementById("id_remedio");
			var qtde= document.getElementById("qtde");
			
			if (document.formAlmoxSaida.tipo_apres[0].checked)
				var tipo_apres= 'c';
			if (document.formAlmoxSaida.tipo_apres[1].checked)
				var tipo_apres= 'u';
			
			var tit_qtde_c= document.getElementById("tit_qtde_c");
			var tit_qtde_u= document.getElementById("tit_qtde_u");
			var subtipo_trans= document.getElementById("subtipo_trans");

			if (id_remedio.value=="") {
				if (foco==null) foco= id_remedio;
				erros += "Selecione o remédio para dar entrada no estoque!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do medicamento a dar saída em estoque!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
				//se o campo qtde está ok até agora
				else {
					//se for caixa
					if (tipo_apres=='c') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_c.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
					else if (tipo_apres=='u') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_u.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
				}
			}
			if (subtipo_trans.value=="") {
				if (foco==null) foco= subtipo_trans;
				erros += "Selecione o motivo da saída!\n";
			}
			else {
				var classificacao_remedio= document.getElementById("classificacao_remedio");
				//soh fazer verificacao da id_pessoa qdo eh controlado e estiver distribuindo
				if ((subtipo_trans.value=="b") && (classificacao_remedio.value=="c") ) {
					var id_pessoa= document.getElementById("id_pessoa_mesmo");
					var cpf_usuario= document.getElementById("cpf_usuario");
					
					//se for distribuicao temporaria e pessoa estiver vazio e o remedio for controlado
					if (id_pessoa.value=="0") {
						if (foco==null) foco= cpf_usuario;
						erros += "O campo \"CPF de Destino\" deve conter um valor válido!\n";
					}
				}
			}

		break;
		case "formAlmoxMovPosto":
			var id_remedio= document.getElementById("id_remedio");
			var qtde= document.getElementById("qtde");
			var id_posto= document.getElementById("id_posto_d");
			
			if (document.formAlmoxMovPosto.tipo_apres[0].checked)
				var tipo_apres= 'c';
			if (document.formAlmoxMovPosto.tipo_apres[1].checked)
				var tipo_apres= 'u';
			
			var tit_qtde_c= document.getElementById("tit_qtde_c");
			var tit_qtde_u= document.getElementById("tit_qtde_u");

			if (id_remedio.value=="") {
				if (foco==null) foco= id_remedio;
				erros += "Selecione o remédio!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do remédio!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
				//se o campo qtde está ok até agora
				else {
					//se for caixa
					if (tipo_apres=='c') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_c.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
					else if (tipo_apres=='u') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_u.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
				}
			}
			if (id_posto.value=="") {
				if (foco==null) foco= id_posto;
				erros += "Selecione o posto de destino dos medicamentos!\n";
			}

		break;
		case "formDistBuscar":
			var txt_busca= document.getElementById("txt_busca");

			if (txt_busca.value=="") {
				if (foco==null) foco= txt_busca;
				erros += "Entre com a expressão para pesquisar!\n";
			}
		break;
		case "formDistInserir":
			var qtde_sol= document.getElementById("qtde_sol");
			
			var qtde= new Array();
			var qtde_pego= new Array();
			var i;
			
			var controle= "";
			
			for (i=0; i<qtde_sol.value; i++) {
				qtde[i]= document.getElementById("qtde_"+i);
				qtde_pego[i]= document.getElementById("qtde_pego_"+i);
				
				if ( (qtde_pego[i].value=="") || (parseInt(qtde_pego[i].value)==0) || (!sohNumeros(qtde_pego[i].value)) ) {
					//if (foco==null) foco= qtde_pego[i];
					if (controle=="")
						controle = "Confira os campos!\nEntre com o número de medicamentos entregues!\n";
				}
				else {
					if (parseInt(qtde_pego[i].value) > parseInt(qtde[i].value)) {
						//if (foco==null) foco= qtde_pego[i];
						if (controle=="")
							controle = "Confira os campos!\nRespeite o número máximo de remédios a pegar!\n";
					}
				}
			}
			
			erros= controle;
			
		break;
		case "formSaidaPessoaInserir":
			/*var qtde_sol= document.getElementById("qtde_sol");
			
			var qtde= new Array();
			var qtde_atual= new Array();
			var qtde_pego= new Array();
			var i;
			
			var controle= "";
			
			for (i=0; i<qtde_sol.value; i++) {
				qtde[i]= document.getElementById("qtde_"+i);
				qtde_atual[i]= document.getElementById("qtde_atual_"+i);
				qtde_pego[i]= document.getElementById("qtde_pego_"+i);
				
				if ( (qtde_pego[i].value=="") || (!sohNumeros(qtde_pego[i].value)) ) {
					//if (foco==null) foco= qtde_pego[i];
					if (controle=="")
						controle = "Confira os campos!\nEntre com o número de medicamentos entregues (0 para não entregar).\n";
				}
				else {
					if (parseInt(qtde_pego[i].value) > parseInt(qtde_atual[i].value)) {
						if (controle=="")
							controle = "Confira os campos!\nQuantidade a ser entregue é maior que a quantidade atual!\n";
					}
				}
			}
			
			erros= controle;
			*/
		break;
		case "formRelatorioResumo":
			var tipo_periodo_p= document.getElementById("tipo_periodo_p");
			
			if (tipo_periodo_p.checked) {
				var inicio= document.getElementById("inicio");
				var erros_inicio= validaData(inicio.value);
				
				if (erros_inicio.length>0) {
					erros += erros_inicio;
					if (foco==null) foco= inicio;
				}
					
				var fim= document.getElementById("fim");
				var erros_fim= validaData(fim.value);
				
				if (erros_fim.length>0) {
					erros += erros_fim;
					if (foco==null) foco= fim;
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
		case "formSolicitacaoTfdInserir":
			var id_interno= document.getElementById("id_interno");
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			var id_cidade= document.getElementById("id_cidade2");
			
			var id_finalidade= document.getElementById("id_finalidade");
			
			var data_solicitacao= document.getElementById("data_solicitacao");
		
			var erros_data_solicitacao= validaData(data_solicitacao.value);
			
			if (id_interno.value=="") {
				if (foco==null) foco= cpf_usuario;
				erros += "Selecione a cidade para gerar o código da solicitação!\n";
			}
			if (id_pessoa.value=="") {
				if (foco==null) foco= cpf_usuario;
				erros += "Entre com a pessoa!\n";
			}
			if (id_cidade.value=="") {
				if (foco==null) foco= id_cidade;
				erros += "Selecione a cidade!\n";
			}
			if (id_finalidade.value=="") {
				if (foco==null) foco= id_finalidade;
				erros += "Selecione a finalidade!\n";
			}			
			if (erros_data_solicitacao.length>0) {
				erros += erros_data_solicitacao;
				if (foco==null) foco= data_solicitacao;
			}
		break;
		case "formSolicitacaoTfdEditar":
			var situacao_solicitacao= document.getElementById("situacao_solicitacao");
		
			if (situacao_solicitacao.value=="") {
				if (foco==null) foco= situacao_solicitacao;
				erros += "Selecione a situação!\n";
			}
		break;
		case "formTfdInserir":
			var id_motorista= document.getElementById("id_motorista");
			var id_veiculo= document.getElementById("id_veiculo");
			var id_cidade= document.getElementById("id_cidade_tfd");
			
			var data_partida= document.getElementById("data_partida");
			
			var erros_data_partida= validaData(data_partida.value);
			
			if (id_motorista.value=="") {
				if (foco==null) foco= id_motorista;
				erros += "Selecione o motorista!\n";
			}
			if (id_veiculo.value=="") {
				if (foco==null) foco= id_veiculo;
				erros += "Selecione o veículo!\n";
			}
			if (id_cidade.value=="") {
				if (foco==null) foco= id_cidade;
				erros += "Selecione o destino!\n";
			}
			
			if (erros_data_partida.length>0) {
				erros += erros_data_partida;
				if (foco==null) foco= data_partida;
			}
		break;
		
		case "formMAlmoxEntrada":
			var id_material= document.getElementById("id_material");
			var qtde= document.getElementById("qtde");
			var subtipo_trans= document.getElementById("subtipo_trans");

			if (id_material.value=="") {
				if (foco==null) foco= id_material;
				erros += "Selecione o material para dar entrada no estoque!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do material a dar entrada em estoque!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
			}
			if (subtipo_trans.value=="") {
				if (foco==null) foco= subtipo_trans;
				erros += "Selecione a origem do material!\n";
			}
		break;
		case "formMAlmoxSaida":
			var id_material= document.getElementById("id_material");
			var qtde= document.getElementById("qtde");
			
			if (document.formMAlmoxSaida.tipo_apres[0].checked)
				var tipo_apres= 'c';
			if (document.formMAlmoxSaida.tipo_apres[1].checked)
				var tipo_apres= 'u';
			
			var tit_qtde_c= document.getElementById("tit_qtde_c");
			var tit_qtde_u= document.getElementById("tit_qtde_u");
			var subtipo_trans= document.getElementById("subtipo_trans");

			if (id_material.value=="") {
				if (foco==null) foco= id_material;
				erros += "Selecione o material para dar saída no estoque!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do material a dar saída em estoque!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
				//se o campo qtde está ok até agora
				else {
					//se for caixa
					if (tipo_apres=='c') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_c.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
					else if (tipo_apres=='u') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_u.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste remédio!\n";
						}
					}
				}
			}
			if (subtipo_trans.value=="") {
				if (foco==null) foco= subtipo_trans;
				erros += "Selecione o motivo da saída!\n";
			}

		break;
		case "formMAlmoxMovPosto":
			var id_material= document.getElementById("id_material");
			var qtde= document.getElementById("qtde");
			var id_posto= document.getElementById("id_posto_d");
			
			if (document.formMAlmoxMovPosto.tipo_apres[0].checked)
				var tipo_apres= 'c';
			if (document.formMAlmoxMovPosto.tipo_apres[1].checked)
				var tipo_apres= 'u';
			
			var tit_qtde_c= document.getElementById("tit_qtde_c");
			var tit_qtde_u= document.getElementById("tit_qtde_u");

			if (id_material.value=="") {
				if (foco==null) foco= id_material;
				erros += "Selecione o material!\n";
			}
			if ( (qtde.value=="") || (qtde.value=="0") ) {
				if (foco==null) foco= qtde;
				erros += "Digite a quantidade do material!\n";
			}
			else {
				var nonNumbers = /\D/;
				if (nonNumbers.test(qtde.value)) {
					if (foco==null) foco= qtde;
					erros += "O campo \"quantidade\" aceita somente números!\n";
				}
				//se o campo qtde está ok até agora
				else {
					//se for caixa
					if (tipo_apres=='c') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_c.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste material!\n";
						}
					}
					else if (tipo_apres=='u') {
						if ( parseInt(qtde.value) > parseInt(tit_qtde_u.value) ) {
							if (foco==null) foco= qtde;
							erros += "O campo \"quantidade\" está com um valor maior que o estoque deste material!\n";
						}
					}
				}
			}
			if (id_posto.value=="") {
				if (foco==null) foco= id_posto;
				erros += "Selecione o posto de destino dos materiais!\n";
			}

		break;
		case "formFamiliaInserir":
		case "formFamiliaEditar":
			var id_pessoa= document.getElementById("id_pessoa_mesmo");
			
			var id_microarea= document.getElementById("id_microarea");
			
			var endereco= document.getElementById("endereco");
			var bairro= document.getElementById("bairro");
			
			//var renda= document.getElementById("renda");
			//var renda_percapita= document.getElementById("renda_percapita");
			
			//var num_comodos= document.getElementById("num_comodos");
			
			if ((id_pessoa.value=="") || (id_pessoa.value=="0"))
				erros += "Identifique o chefe da família!\n";
			
			if (id_microarea.value=="")
				erros += "Selecione a microárea!\n";
			if (endereco.value=="") {
				if (foco==null) foco= endereco;
				erros += "Digite o endereço!\n";
			}
			if (bairro.value=="") {
				if (foco==null) foco= bairro;
				erros += "Digite o bairro!\n";
			}
			
			/*
			if (renda.value=="") {
				if (foco==null) foco= renda;
				erros += "Digite a renda!\n";
			}
			if (renda_percapita.value=="") {
				if (foco==null) foco= renda_percapita;
				erros += "Digite a renda per capita!\n";
			}
			if (num_comodos.value=="") {
				if (foco==null) foco= num_comodos;
				erros += "Digite o número de cômodos da residência!\n";
			}
			
			if (id_form=="formFamiliaInserir")
				if ( (!document.formFamiliaInserir.vacina[0].checked) && (!document.formFamiliaInserir.vacina[1].checked) )
					erros += "Marque a situação da vacina!\n";
			*/
		break;
		case "formParecerInserir":
			var id_familia= document.getElementById("id_familia");
			
			var data_parecer= document.getElementById("data_parecer");
			var parecer= document.getElementById("parecer");
			var providencias= document.getElementById("providencias");
			
			if (id_familia.value=="")
				erros += "Família fantando!\n";
			
			if (data_parecer.value=="") {
				if (foco==null) foco= data_parecer;
				erros += "Digite a data do parecer técnico!\n";
			}
			if (parecer.value=="") {
				if (foco==null) foco= parecer;
				erros += "Digite o parecer técnico!\n";
			}
			if (providencias.value=="") {
				if (foco==null) foco= providencias;
				erros += "Digite as providencias e encaminhamentos!\n";
			}
			
		break;
		case "formVisitaInserir":
			var id_familia= document.getElementById("id_familia");
			
			var data_visita= document.getElementById("data_visita");
			var situacao= document.getElementById("situacao");
			var parecer= document.getElementById("parecer");
			
			if (id_familia.value=="")
				erros += "Família fantando!\n";
			
			if (data_visita.value=="") {
				if (foco==null) foco= data_visita;
				erros += "Digite a data da visita!\n";
			}
			if (situacao.value=="") {
				if (foco==null) foco= situacao;
				erros += "Digite a situação!\n";
			}
			if (parecer.value=="") {
				if (foco==null) foco= parecer;
				erros += "Digite o parecer técnico!\n";
			}
			
		break;
		case "formAssistenciaInserir":
			var id_familia= document.getElementById("id_familia");
			
			var data_assistencia= document.getElementById("data_assistencia");
			var valor= document.getElementById("valor");
			var assistencia= document.getElementById("assistencia");
			
			if (id_familia.value=="")
				erros += "Família fantando!\n";
			
			if (data_assistencia.value=="") {
				if (foco==null) foco= data_assistencia;
				erros += "Digite a data da assistência prestada!\n";
			}
			if (valor.value=="") {
				if (foco==null) foco= valor;
				erros += "Digite o valor da assistência prestada!\n";
			}
			if (assistencia.value=="") {
				if (foco==null) foco= assistencia;
				erros += "Digite a assistência prestada!\n";
			}
			
		break;
		default: void(0);
	}
	if (foco!=null)
		foco.focus();
	if (erros.length>0) {
		if (id_form=="formProntuario")
			document.getElementById("prontuario_atualiza").innerHTML= "<span class=\"vermelho\">"+erros+"</span>";
		else
			alert (erros);
		
		return false;
	}
	else {
		if (erros2.length>0) {
			return confirm("Você está cadastrando uma pessoa sem o CPF!\nTenha certeza de estar inserindo os dados corretos\ne peça à pessoa para trazer o CPF na próxima visita!\n\nTEM CERTEZA QUE DESEJA CONTINUAR?");
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