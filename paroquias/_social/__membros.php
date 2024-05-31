<?
if (@pode_algum("zl", $_SESSION["permissao"]) ) {
	if ($_SESSION["id_posto_sessao"]!="")
		$str_condicao= "and   postos.id_posto = '". $_SESSION["id_posto_sessao"] ."'";
	else
		$str_condicao= "and   postos.id_cidade = '". $_SESSION["id_cidade_sessao"] ."'";
		
	$result= mysql_query("select *
							from familias, microareas, microareas_coord, postos
							where familias.id_familia = '". $id_familia ."'
							and   familias.id_microarea = microareas.id_microarea
							and   microareas.id_coord = microareas_coord.id_coordenacao
							and   microareas_coord.id_posto = postos.id_posto
							$str_condicao
							") or die(mysql_error());
	$rs= mysql_fetch_object($result);
?>

<div id="tela_mensagens2">
	<? include("__tratamento_msgs.php"); ?>
</div>

<h2 class="titulos">Membros da família</h2>

<a href="javascript:void(0);" onclick="abreCadastroSo();" id="botao_voltar">cadastrar pessoa</a>

<br />

<div id="tela_cadastro">
</div>

<div id="pessoa_buscar" class="escondido">
    <?
    include("_pessoas/__pessoa_buscar.php");
    ?>
</div>

<fieldset>
    <legend>Dados da família</legend>
	
    <div class="partei">
        <label>Código:</label>
        <?= $rs->id_familia; ?>
        <br />
        
        <label>Chefe:</label>
        <?= pega_chefe_familia($rs->id_familia); ?>
        <br />
    </div>
    <div class="partei">
        <label>Quadra:</label>
        <?= $rs->microarea; ?>
        <br />
        
        <label>Missionário(s):</label>
        <?= pega_nomes($rs->id_pessoas); ?>
        <br />
        
        <label>Posto:</label>
        <?= $rs->posto; ?>
        <br />
    </div>
    
</fieldset>

<fieldset>
    <legend>Membros da família</legend>
    
    <div class="partei screen">
        <input id="id_familia" class="escondido" type="hidden" value="<?= $rs->id_familia; ?>" name="id_familia"/>
        
        <label>CPF:</label>
        <input id="cpf_usuario" maxlength="11" onblur="usuarioRetornaCpfCompleto('');" name="cpf_usuario"/>
        <button onclick="abreFechaDiv('pessoa_buscar'); daFoco('nomeb');" type="button">busca</button>
        <br/>
        
        <label>&nbsp;</label>
        <div id="cpf_usuario_atualiza">
			<input id="id_pessoa_mesmo" class="escondido" type="hidden" value="" name="id_pessoa"/>
		</div>
        <br />
        
        <label>Parentesco:</label>
        <select name="parentesco" id="parentesco">
			<?
            $vetor= pega_parentesco('l');
            
            $i=1; $j=0;
            while ($vetor[$i]) {
            ?>
            <option <? if (($j%2)==0) echo "class=\"cor_sim\""; ?>  value="<?= $i; ?>"><?= $vetor[$i]; ?></option>
            <? $i++; $j++; } ?>
        </select>
        <br />
        
        <label>&nbsp;</label>
        <button onclick="cadastraMembroFamilia(); limpaCampo('cpf_usuario'); preencheDiv('cpf_usuario_atualiza', '<span class=vermelho>Refaça a busca para adicionar mais membros.</span>');" type="button">Inserir</button>
        <br/>
    </div>
    
    <div class="partei">
        <fieldset>
            <legend>Formação da família</legend>
            
            <div id="formacao_familia">
            </div>
            
        </fieldset>
    </div>
</fieldset>
    
<script language="javascript" type="text/javascript">
	ajaxLink('formacao_familia', 'pegaMembrosFamilia&id_familia=<?= $rs->id_familia; ?>');
	//daFoco('id_psf');
    </script>
<?
}
else {
	$erro_a= 1;
	include("__erro_acesso.php");
}
?>