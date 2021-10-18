

<?php
// Inicio do programa insere_____.php

if(isset($_GET["offset"])){
  $offset = $_GET["offset"];
}
if(isset($_GET["limit"])){
  $limit = $_GET["limit"];
}
if(isset($_GET["campo_busca"])){
  $campo_busca = $_GET["campo_busca"];
}
if(isset($_GET["valor_busca"])){
  $valor_busca = $_GET["valor_busca"];
}

if(isset($_GET["scrollx"])){
  $scrollx = $_GET["scrollx"];
} else {$scrollx=0;}

if(isset($_GET["scrolly"])){
  $scrolly = $_GET["scrolly"];
} else {$scrolly=0;}



$limitador_registros_insere=50;

echo "
<html>
<head>
<title>Inserção de dados na tabela (maio-2020): tipos_vinculos ".$campo_busca."</title>
<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate'/>
<meta http-equiv='Pragma' content='no-cache'/>
<meta http-equiv='Expires' content='0'/>

<meta charset='UTF-8'>
<style>

tr.principal:first-child {background: black; color: white;}
tr.principal:nth-child(even) 
			{
					border-left: 1px solid #CCC;
					background: #AAA;
			}
tr.principal:nth-child(odd) 
			{

					border-left: 1px solid #BBB;
					background: #DDD;
			}
tr.principal > * {
	padding: 5px;
	border-bottom: 3px solid #0000EE;
}

.classe_ponto_insercao_nton > * {
	display:table-cell;
	vertical-align: top;
	text-align: top;
	width: 100%;
box-sizing: border-box;

}

.tabela_contem_nomes {
	font-size: 1em;
	background-color: #00084b;
	color: white;
	border-collapse: collapse;
	border: 2px solid darkgray;
	padding: 3px;
	width: 100%;
}

.classe_contem_nomes > * {
	font-size: 1em;
	background-color: #001a4d;
	color: white;
	border-collapse: collapse;
	border: 1px solid white;

}

.classe_filho_ponto_insercao_nton {
    background-color: lightgray;	
	color: black;
	display:table-cell;
	vertical-align: top;
	text-align: top;
	width: 100%;
    box-sizing: border-box;
	border-collapse: separate;

}

div.cabecalio {
	background-color: green;
	border: 1px solid black;
}

.interna {
    font-size: 10px;
    border: none;
    padding: 0px;
}

	.cabecalio_table > * {
		background-color: #060606;
		color: white;
		padding: 10px;
	}

	.botoeira {
	border: 1px solid red;
        background-color: silver;
	border-collapse: collapse;
	font-size: small;
	margin-left: auto;
	margin-right: auto;
        padding: 2px;
}

th,td {
	padding: 0px;
        font-size: small;
}

input[type=button]{
	font-size: x-small;
}


.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 2px;
  font-size: x-small;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 2px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

[data-alterado='alterado'] {
   background-color: red;
}

[data-keyup='keyup'] {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}

</style>
</head>
<body id='conteudo'>
<div class='cabecalio'>
<h1>Inserção de Dados na tabela (maio/2020): tipos_vinculos</h1>
<div id='id_comentario_tabela' style='border: 2px solid blue; background: yellow'></div>
</div>
<div id='insercao' class='botoeira'>
</div>
";
include "identifica.php";
$database="escolax";
$path_imagem="";

$conn= new mysqli("localhost", $username, $pass, $database);

$sql_comment="SELECT TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_NAME = 'tipos_vinculos';";

$result_comment=$conn->query("$sql_comment");

if ($result_comment->num_rows>0) {
 	while($row_comment=$result_comment->fetch_assoc()){
		$comentario=$row_comment["TABLE_COMMENT"];	
	}

} else {$comentario="Esta tabela não tem comentários.";}

echo "<script>document.getElementById('id_comentario_tabela').innerHTML='".$comentario."';</script>";

$sql="select count(*) from tipos_vinculos";
$result=$conn->query("$sql");
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
	$contagem_registro=$row["count(*)"];
    }
}
echo "<div style='border: 1px solid black; background-color: orange; width: 100%'>
	<table style='width: 100%'>
	<tr>
		<td>Número Total de Registros:".$contagem_registro."</td>
		<td> Registro Inicial:".$offset."</td>
		<td> Registros por pagina:".$limit."</td>
	</tr>
	</table>
</div>
"; 

$numero_paginas=intdiv($contagem_registro,$limit)+1;
echo "<div style='width: 100%; height: 40px; overflow-y: scroll'>";
for ($i_pag = 1; $i_pag <= $numero_paginas; $i_pag++) {
    $delta_offset=($i_pag-1)*$limit;	
    echo "<input class='pagina' type='button' style='width: 90px;' value='Pág.".$i_pag." (".$delta_offset.")'  onclick='carrega_offset(".$limit.",".$delta_offset.");'></input>";
}
echo "</div>";

// esta parte é para colocar os campos de busca

echo "
<div style='border: 1px solid black; background-color: green; width: 100%'><table style='width: 100%'><tr><td>Consulta:<input type='text' id='id_campo_de_search' data-nivel='0' placeholder='Coloque a consulta aqui!' /></td><td><input 
				type='button'
				value='id_chave_tipo_vinculo' 
				data-nivel='0' 
				name='busca_principal'
				onclick='var itz=document.getElementById(`id_campo_de_search`).value;  if (itz===``) {alert(`Você deixou o campo de busca vazio!`);} else {carrega_busca(`id_chave_tipo_vinculo`,itz,50,0);;}'
			></td><td> <input 
				type='button'
				value='nome_tipo_vinculo' 
				data-nivel='0' 
				name='busca_principal'
				onclick='var itz=document.getElementById(`id_campo_de_search`).value;  if (itz===``) {alert(`Você deixou o campo de busca vazio!`);} else {carrega_busca(`nome_tipo_vinculo`,itz,50,0);;}'
			></td><td> </td><td> <input type='button' data-nivel='0' name='busca_principal' value='ultimo'/></td><td> <input type='button' value='Todos' data-nivel='0'/></td></tr></table></div>
";



echo "
<table class='botoeira'>
<tr class='cabecalio_table'><th></th><th>id</th><th>nome_tipo_vinculo</th></tr>";



// comeca o query!

$row_number=$offset;

if ($campo_busca=="") {$sql="select * from tipos_vinculos order by nome_tipo_vinculo limit ".$limit." offset ".$offset;}
else {$sql="select * from tipos_vinculos where ".$campo_busca." like '".$valor_busca."%' order by '".$campo_busca."' limit ".$limit;}
echo $sql;
$result=$conn->query("$sql");
$num_registros_achados=$result->num_rows;
if ($num_registros_achados>0) {
  while($row=$result->fetch_assoc())
    {

$campos_atualizaveis="[";


$id_chave_tipo_vinculo=$row["id_chave_tipo_vinculo"];

$id_registro=$row["id_chave_tipo_vinculo"];

$nome_tipo_vinculo=$row["nome_tipo_vinculo"];
$campos_atualizaveis=$campos_atualizaveis.",".'"campo_'.$id_registro.'_2"';
$campos_atualizaveis=$campos_atualizaveis."]";
echo "<tr class='principal'><td>".$row_number."</td><td>".$id_chave_tipo_vinculo."</td><td>
				<input 
					id='campo_".$id_registro."_2' 
                                        class='editavel'
					type='text' 
					value='".$nome_tipo_vinculo."'
					data-alterado='nao'
					data-tabela='tipos_vinculos'
					data-campo='nome_tipo_vinculo'
                                        data-id='".$id_registro."'
        				data-nivel='0'
				/>
			   </td>
	<th class='classe_ponto_insercao_nton' id='ponto_insercao_nton_".'"'.$id_registro.'"'."'>
	".''.$id_registro.''."	
	</th>
	<th>
		<input 
			type='button' 
			value='atualiza'
		        data-nivel='0' 
			onclick='var matriz=".$campos_atualizaveis."; atualiza(matriz);'
		/>
	</th><th>
		<input 
			type='button' 
			value='apaga'
		        data-nivel='0' 
			onclick='apaga_registro(".'"'.$id_registro.'"'.")'
		/>
	</th></tr>";
    $row_number++;
    }
}


echo "
</tr>
</table>
<div  id='mensagem_de_carregamento' style='left: 50%; top: 50%; width: auto; height: auto; border: 1px solid black; background-color: yellow; padding: 10px; font-size: 50px; position: absolute;  visibility: visible; z-index:1000'>Carregando...</div>
<script>

var nivel_insercao=0; // indica o nivel de insercao de dados a que se refere um botao de insercao.
                      // variavel nivel_insercao eh necessaria para limitar os campos de insercao aos que se refere a aquele botao de insercao

var escrolx=".$scrollx.";
var escroly=".$scrolly.";


document.body.scrollLeft=escrolx;
document.body.scrollTop=escroly;



var conta_loads=0;

mostra_botao('insercao','tipos_vinculos','0');

setTimeout(function (){document.getElementById('mensagem_de_carregamento').style.visibility='hidden';},1);

document.addEventListener('load', function() {
	document.body.scrollLeft=escrolx; 
	document.body.scrollTop=escroly;
	document.getElementById('mensagem_de_carregamento').innerText=  'Carregando: ' + conta_loads + '/".$limitador_registros_insere."';
    document.getElementById('mensagem_de_carregamento').style.visibility='visible';
	document.getElementById('mensagem_de_carregamento').style.left=Math.trunc(document.body.clientWidth/2 + escrolx - document.getElementById('mensagem_de_carregamento').style.clientWidth/2);
	document.getElementById('mensagem_de_carregamento').style.top=Math.trunc(document.body.clientHeight/2 + escroly - document.getElementById('mensagem_de_carregamento').style.clientHeight/2);

	if(document.getElementById('aguarde_inicio')){
	   document.getElementById('aguarde_inicio').remove();
	} 
	 


    setTimeout(
				function(e) // truque para garantir que em algum momento a mensagem de carregamento vai ser apagada
					{
					     if (conta_loads>".$limitador_registros_insere." - 2 || conta_loads==0 || conta_loads>".$num_registros_achados." -2) {document.getElementById('mensagem_de_carregamento').style.visibility='hidden'; }
					},500);

conta_loads++;
}, true);


function re_carrega_NtoN(ponto_insercao, banco, rtn, rcn, tabela, coluna, campo_externo, id_externo){ // é parecida com carrega_NtoN mas permite recarregar a lista NtoN quando há atualização
// ainda não está sendo usado - o objetivo no futuro é acelerar a atualização


// Dicionário:
// ponto_insercao: id do DIV onde serão colocados os dados
// banco: banco de dados
// rtn:   é o nome da tabela remota de onde vai se buscar nomes para colocar no id_chave
// rcn:   é o nome do campo de chave primária da rtn
// tabela: é a tabela que estabelece a relação n_to_n
// coluna: é a chave externa da tabela acima, que permite buscar o nome na rtn
// campo_externo: é a chave externa que se relaciona com id_chave na <tabela> tratada em insere_<tabela>.php
// id_externo: é o valor do campo_externo



           var resposta='';
		   var url='puxa_lista_NtoN.php?banco='+banco+'&rtn='+rtn+'&rcn='+rcn+'&table_para_search='+tabela+'&coluna='+coluna+'&campo_externo_para_search='+campo_externo+'&id_externo_para_search='+id_externo;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
					 document.getElementById(ponto_insercao).innerHTML=resposta;
                     }
           oReq.send();

}// fim re_carrega_NtoN



function carrega_NtoN(ponto_insercao, banco, tabela, campo_externo, id_externo){
           var resposta='';
           var url='NtoN_insercao.php?banco='+banco+'&tabela_externa_em_edicao='+tabela+'&nivel=0&campo_externo_para_search='+campo_externo+'&id_externo_para_search='+id_externo;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
					 document.getElementById(ponto_insercao).innerHTML=resposta;
                     }
           oReq.send();

}// fim carrega_NtoN


function percorre_ponto_insercao(){

var pontos=document.getElementsByClassName('classe_ponto_insercao_nton');
var i;

for (i=0; i<pontos.length; i++){
	console.log('varanda: '+pontos.id);
	carrega_NtoN(pontos[i].id,'escolax', 'tipos_vinculos','id_documento',document.getElementById(pontos[i].id).innerText);
	// aqui vc tem que passar o nome da tabela NtoN e o nome do campo dessa tabela que é uma chave externa que aponta para o id_chave_ da <tabela> referida neste arquivo insere_<tabela>.php
} // fim do for


} // fim percorre_ponto_insercao


function ativa_alterados(){
var inputs_inseriveis=document.getElementsByClassName('inserivel');
var i;
var input_inserivel;
for (i=0; i<inputs_inseriveis.length; i++) {
input_inserivel=inputs_inseriveis[i];
input_inserivel.addEventListener('keydown', function(e){e.target.style.backgroundColor='#FF0000';e.target.setAttribute('data-alterado','alterado') }, false);
}
}

ativa_alterados();
if ('tipos_vinculos'=='documentos'){percorre_ponto_insercao();}

function disable_niveis(){
var x = document.getElementsByTagName('INPUT');
var i;
for (i = 0; i < x.length; i++) {
  console.log('TAG INPUT -> '+x[i].id+' nivel -> '+nivel_insercao);
  if (x[i].className=='pagina' || x[i].getAttribute('data-nivel')==nivel_insercao) {x[i].disabled=false;} else {x[i].disabled=true;};
}
}


// INICIO DOS SCRIPTS DO DROP MENU

function carrega_drop_btn(element){

  if(element.getAttribute('data-momento')=='atualizacao'){
           var resposta='';
           var url='auto_ler_tabela_campo.php?banco=escolax&tabela='+element.getAttribute('data-fk-tabela')+'&campo_id='+element.getAttribute('data-fk-id')+'&id='+element.getAttribute('data-fkid');
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
                     element.value=resposta;
                     element.setAttribute('data-default',resposta);
                     }
           oReq.send();
       }
} // carrega_drop_btn


function ativa_eventos_dropbtn(){ // ativa os eventos de teclado e demais dos dropbtn

var drops=document.getElementsByClassName('dropbtn');
var i;
for (i=0; i<drops.length; i++) {
console.log(drops[i].id);
var drop_singular=drops[i];


// blur é quando perde o foco: input value tem que retornar ao valor default
// importante verificar se o elemento já tem o evento registrado, antes de registrar um novo. De outra forma, posso ter um x=x+2 para o valor de selecionado porque registro dois eventos que fazem x=x=+1...
if (drop_singular.getAttribute('data-event-blur')==='NAO') {drop_singular.addEventListener('blur', function(e){e.target.setAttribute('data-event-blur','BLUR');  e.target.value=e.target.getAttribute('data-default');}, false);}
if (drop_singular.getAttribute('data-event-focus')==='NAO'){drop_singular.addEventListener('focus',function(e){e.target.setAttribute('data-event-focus','FOCUS');   cai(e.target.id,e.target.getAttribute('data-drop')); e.target.value=''; e.target.value='';}, false);}
if (drop_singular.getAttribute('data-event-keyup')==='NAO') {drop_singular.addEventListener('keyup', 
		function(e){ 
			        e.target.setAttribute('data-event-keyup','KEYUP');
				var selecionado=e.target.getAttribute('data-selecionado');
                                var n_itens=e.target.getAttribute('data-n-itens');
	
				if ((e.keyCode==40) && ((selecionado<parseInt(n_itens)-1) || (selecionado<0)) ) {
							e.target.setAttribute('data-selecionado',parseInt(selecionado)+1);
						   }
 
				if ((e.keyCode==38) && (selecionado>-1)) {
							e.target.setAttribute('data-selecionado',parseInt(selecionado)-1);
						   }
 
				if ((e.keyCode<28) 
					&& (e.keyCode!=9) // evita que saia do dropbox quando o tab é usado 
					&& (e.keyCode!=14) // evita que saia do dropbox quando ocorre shift in 
					&& (e.keyCode!=15) // no manual dizia que shift out é 15, mas parece que 16 na verdade
					&& (e.keyCode!=16)) { // evita que saia do dropbox com SHIFT out
                                                        console.log(e.keyCode);
							if (e.keyCode==13){
                                                                console.log('selecionado: '+e.target.getAttribute('data-selecionado'));
                                                                console.log('id input: '+e.target.getAttribute('data-fkid'));
                                                                var drop_elem=e.target.getAttribute('data-drop');
								console.log('drop element: '+drop_elem);
								e.target.setAttribute('data-fkid',document.getElementById('a_'+drop_elem+'_'+e.target.getAttribute('data-selecionado')).getAttribute('data-id-fk'));
                                                            if (e.target.getAttribute('data-momento')=='atualizacao'){
								atualiza_fk(e.target.id);
								carrega_drop_btn(e.target);}
								else {e.target.value=document.getElementById('a_'+drop_elem+'_'+e.target.getAttribute('data-selecionado')).getAttribute('data-innertext');
e.target.setAttribute('data-default',e.target.value);
console.log('target: '+e.target.value);
}
								
							} else {e.target.value=e.target.getAttribute('data-default');}
							e.target.setAttribute('data-keyup','inativo');
                                                        if (e.keyCode==8){
										e.target.value='';
										cai(e.target.id,e.target.getAttribute('data-drop'));
									} else {
                                                        			document.activeElement.blur();
                                                                               }

							
						
						   }
 
				else {cai(e.target.id,e.target.getAttribute('data-drop'));}
console.log(selecionado);
		}, false);}
}
}
// fim da funcao que atribui eventos aos dropbtn

ativa_eventos_dropbtn();

function cai(id_input,id_div){
console.log('porra: '+id_input+' porra2: '+id_div);
var elemento_input=document.getElementById(id_input);
var elemento_div=document.getElementById(id_div);

var str_busca=elemento_input.value;

if ((str_busca!='') || (parseInt(elemento_input.getAttribute('data-selecionado'))>-1)) {

		elemento_div.setAttribute('data-keyup','keyup');
		var fk_banco=elemento_input.getAttribute('data-fk-banco');
		var fk_tabela=elemento_input.getAttribute('data-fk-tabela');
		var fk_campo=elemento_input.getAttribute('data-fk-id');
		var max_itens=elemento_input.getAttribute('data-max-itens');
		busca_lista(id_input, id_div,fk_banco, fk_tabela, fk_campo, str_busca, max_itens);
               
		} 
		else {elemento_div.setAttribute('data-keyup','inativo');}
}


function busca_lista(elemento_input, elemento, banco, tabela, campo, str_busca, max_itens){
// busca a lista de valores de campos fk, de acordo com o nome_, usando o que foi teclado como search. Coloca no dropdown
           var resposta='';
           var url='busca_str.php?banco='+banco+'&tabela='+tabela+'&campo='+campo+'&str_busca='+str_busca;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
								var input=document.getElementById(elemento_input);
                                                                var myNode=document.getElementById(elemento); //é o div?
			
								while (myNode.firstChild) {
								myNode.removeChild(myNode.firstChild);
								}		

                     resposta=oReq.responseText;
                     var matriz=resposta.split('<br>', max_itens);
		     // pode acontecer, como é o caso das tabelas autorizacoes, curadores_conteudos e expectadores, de uma tabela fazer referencia a uma outra sem nome_%
                     if (matriz[0].includes('veio nome')) // a resposta do php completa eh (nao veio nome). Usei uma fracao por causa do acento 
					{
						// se percebe que nao veio nome, ou seja, nao tem nome_, entao ele busca um subselect
						 busca_lista_sub_select(elemento_input, elemento, banco, tabela, campo, str_busca, max_itens);
                                                 return;
					}
 
                     var conta=0;

                     matriz.forEach(function (item, index) {
 							   console.log('>'+item+'<');
							   if (item.trim()!=''){
								var node = document.createElement('a');            // Create a <li> node
                     						var item_matriz=item.split('<rb>', max_itens);
								var att_innertext = document.createAttribute('data-innertext');
							        att_innertext.value = item_matriz[0];
								node.setAttributeNode(att_innertext);	
								var att_id = document.createAttribute('data-id-fk');
							        att_id.value =	item_matriz[1];
								node.setAttributeNode(att_id);	
                                                                node.id='a_'+elemento+'_'+conta;
								var textnode = document.createTextNode('#'+item_matriz[0]+'#');     // Create a text node
                                                                textnode.id='text_'+elemento+'_'+conta;
								node.appendChild(textnode);                        // Append the text to <a>
								myNode.appendChild(node);     // Append <a> to <div> with id='lista'
								node.addEventListener('mousedown',function (){console.log('clicou');},false);
								if (index==input.getAttribute('data-selecionado'))
									{
										node.style.backgroundColor='#000000';
										node.style.color='#FFFFFF';
									}
								conta=conta+1;
                                                                           }

                                                           ;}
							   );
							   input.setAttribute('data-n-itens',conta);
                     }
           oReq.send();

}

function busca_lista_sub_select(elemento_input, elemento, banco, tabela, campo, str_busca, max_itens){
// funcao para o caso da tabela foreign nao ter nome_... dai tem que buscar na tabela fk da fk.
           console.log(str_busca);
           var resposta='';
           var url='busca_registro_inteiro.php?banco='+banco+'&tabela='+tabela+'&nome_chave_primaria='+campo+'&busca_str='+str_busca;
           // este codigo PHP busca apenas os campos que nao estao na tabela campos_excluidos... isso reduz o tamanho do string que aparece no dropdown
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
								var input=document.getElementById(elemento_input);
                                                                var myNode=document.getElementById(elemento); //é o div?
			
								while (myNode.firstChild) {
								myNode.removeChild(myNode.firstChild);
								}		

                     resposta=oReq.responseText;
                     var matriz=resposta.split('<br>', max_itens);
		     // pode acontecer, como é o caso das tabelas autorizacoes, curadores_conteudos e expectadores, de uma tabela fazer referencia a uma outra sem nome_%
                     var conta=0;

                     matriz.forEach(function (item, index) {
							   if (item.trim()!=''){

 							   console.log('>'+item+'<');
								var node = document.createElement('a');            // Create a <li> node
                     						var item_matriz=item.split('<rb>', max_itens);
							console.log(item_matriz[0]);
								var att_innertext = document.createAttribute('data-innertext');
							        att_innertext.value = item_matriz[0];
								node.setAttributeNode(att_innertext);	
								var att_id = document.createAttribute('data-id-fk');
							        att_id.value =	item_matriz[1];
								node.setAttributeNode(att_id);	
                                                                node.id='a_'+elemento+'_'+conta;
								var textnode = document.createTextNode('#'+item_matriz[0]+'#');     // Create a text node
                                                                textnode.id='text_'+elemento+'_'+conta;
								node.appendChild(textnode);                        // Append the text to <a>
								myNode.appendChild(node);     // Append <a> to <div> with id='lista'
								node.addEventListener('mousedown',function (){console.log('clicou');},false);
								if (index==input.getAttribute('data-selecionado'))
									{
										node.style.backgroundColor='#000000';
										node.style.color='#FFFFFF';
									}
								conta=conta+1;
                                                                           }

                                                           ;}
							   );
							   input.setAttribute('data-n-itens',conta);
                     }
           oReq.send();
}

// FIM DOS SCRIPTS DO DROP MENU


function ativa_eventos_editaveis(){

var inputs_editaveis=document.getElementsByClassName('editavel');
var i;
var input_singular;
for (i=0; i<inputs_editaveis.length; i++) {
input_singular=inputs_editaveis[i];
input_singular.addEventListener('keydown', function(e){e.target.style.backgroundColor='#FF0000';e.target.setAttribute('data-alterado','alterado') }, false);
}
}

ativa_eventos_editaveis();

function desliga_autocomplete(){
// tira o auto complete dos campos dropbtn
var inputElements = document.getElementsByTagName('input');
for (i=0; inputElements[i]; i++) {
if (inputElements[i].className && (inputElements[i].className.indexOf('dropbtn') != -1)) {
inputElements[i].setAttribute('autocomplete','off');
}
}
}

desliga_autocomplete();

var x = document.getElementsByClassName('dropbtn');
var i;
for (i = 0; i < x.length; i++) {
// o programa auto_ler_tabela_campo.php é usado para buscar os dados na tabela chave (foreign key)
// se o dropbtn for de inserao de dados, ao inves de atualização, nao faz sentido buscar dados na base, porque o campo tem que estar vazio
  if(x[i].getAttribute('data-momento')=='atualizacao'){
           var resposta='';
           var url='auto_ler_tabela_campo.php?banco=escolax&tabela='+x[i].getAttribute('data-fk-tabela')+'&campo_id='+x[i].getAttribute('data-fk-id')+'&id='+x[i].getAttribute('data-fkid');
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
	             x[i].value=resposta;
                     x[i].setAttribute('data-default',resposta);
                     }
           oReq.send();
       }
}
function carrega_busca(campo_busca,valor_busca,limit,offset){
 escrolx = window.pageXOffset || document.body.scrollLeft;
 escroly = window.pageYOffset || document.body.scrollTop;
 
mensagem_de_carregamento_ativada();

  alert('Busca pelo campo '+campo_busca);
	var resposta='';
	var url='insere_tipos_vinculos.php?offset='+offset+'&limit='+limit+'&campo_busca='+campo_busca+'&valor_busca='+valor_busca+'&scrollx='+escrolx+'&scrolly='+escroly;
        var oReq=new XMLHttpRequest();
	oReq.open('GET',url, false);
	oReq.onload= function (e) {
			    resposta=oReq.responseText;
			    window.document.body.innerText='';
			    window.document.write(resposta);
			}
	oReq.send();
}
function carrega_offset(limit,offset){
escrolx = window.pageXOffset || document.body.scrollLeft;
escroly = window.pageYOffset || document.body.scrollTop;

mensagem_de_carregamento_ativada();

setTimeout(
function (){

	var resposta='';
	var url='insere_tipos_vinculos.php?offset='+offset+'&limit='+limit+'&campo_busca=".$campo_busca."&valor_busca=".$valor_busca."&scrollx='+escrolx+'&scrolly='+escroly;
        var oReq=new XMLHttpRequest();
	oReq.open('GET',url, false);
	oReq.onload= function (e) {
			    resposta=oReq.responseText;
			    window.document.body.innerText='';
			    window.document.write(resposta);
			}
	oReq.send();
}, 300);

}

function mensagem_de_carregamento_ativada(){
	document.getElementById('mensagem_de_carregamento').innerText=  'Aguarde Carregar ';
    document.getElementById('mensagem_de_carregamento').style.visibility='visible';
	document.getElementById('mensagem_de_carregamento').style.left=Math.trunc(document.body.clientWidth/2 + escrolx);
	document.getElementById('mensagem_de_carregamento').style.top=Math.trunc(document.body.clientHeight/2 + escroly);
}


function carrega(){
escrolx = window.pageXOffset || document.body.scrollLeft;
escroly = window.pageYOffset || document.body.scrollTop;

mensagem_de_carregamento_ativada();

setTimeout(
function (){
	var resposta='';
	var url='insere_tipos_vinculos.php?offset=0&limit=".$limitador_registros_insere."&campo_busca=".$campo_busca."&valor_busca=".$valor_busca."&scrollx='+escrolx+'&scrolly='+escroly;
        var oReq=new XMLHttpRequest();
	oReq.open('GET',url, false);
	oReq.onload= function (e) {
			    resposta=oReq.responseText;
			    window.document.body.innerText='';
			    window.document.write(resposta);
			}
	oReq.send();
},300);
}


function apaga_registro_com_tabela(tabela, id){ // igual a apaga_registro, mas tem tabela como parametro de entrada

if (!confirm('O registro '+id+' da tabela '+tabela+'será apagado.')) {return;}
var resposta='';
var url='apaga_registro.php?banco=escolax&tabela='+tabela+'&id='+id;
alert(url);
var oReq=new XMLHttpRequest();
oReq.open('GET', url, false);
oReq.onload= function (e) {
	resposta=oReq.responseText;
        alert(resposta);
	carrega();

}
oReq.send();

}



function apaga_registro(id){
var resposta='';
var url='apaga_registro.php?banco=escolax&tabela=tipos_vinculos&id='+id;
alert(url);
var oReq=new XMLHttpRequest();
oReq.open('GET', url, false);
oReq.onload= function (e) {
	resposta=oReq.responseText;
        alert(resposta);
	carrega();

}
oReq.send();

}

//cuidado para nao confundir esse insere_registro com o insere_registro.php... sao diferentes - o javascript chama o php

function insere_registro(tabela, nivel_de_insercao, div_insercao, campo_extra, valor_extra){ // nivel de insercao indica para quais campos o botao insere vai agir

// campo_extra e valor extra permitem forçar a inserção de um campo e valor que não estão representados por um input no DOM. Isso é útil quando estou usando a inserção NtoN dentro de uma tabela, em que eu já sei qual o registro que eu selecionei

var conta_campos=0;
var inputs_inseriveis=document.getElementById(div_insercao).getElementsByClassName('inserivel'); // pega apenas os inseriveis que estao dentro de div insercao
var i;
var input_inserivel;

if (campo_extra ==='') {var acumula_campos='';} else {var acumula_campos=campo_extra+', ';}
if (valor_extra ==='') {var acumula_valores='';} else {var acumula_valores=valor_extra+', ';}

alert(nivel_de_insercao+acumula_campos);

var virgula='';
for (i=0; i<inputs_inseriveis.length; i++) {
input_inserivel=inputs_inseriveis[i];

if (input_inserivel.getAttribute(".'"'."data-nivel".'"'.")!=nivel_de_insercao) {continue;}

if (conta_campos>0) {virgula=',';} else {virgula='';}
acumula_campos=acumula_campos+virgula+input_inserivel.getAttribute(".'"'."data-campo".'"'.");
acumula_valores=acumula_valores+virgula+'".'"'."'+input_inserivel.value+'".'"'."';
conta_campos=conta_campos+1;
}
// na hora de inserir os registros vc precisa acumular quais
var inputs_inseriveis=document.getElementById(div_insercao).getElementsByClassName('dropbtn');
var i;
var input_inserivel;
var virgula='';
for (i=0; i<inputs_inseriveis.length; i++) {
input_inserivel=inputs_inseriveis[i];
if (input_inserivel.getAttribute(".'"'."data-nivel".'"'.")!=nivel_de_insercao) {continue;}

	if (input_inserivel.getAttribute('data-momento')=='insercao'){
		if (conta_campos>0) {virgula=',';} else {virgula='';}
		acumula_campos=acumula_campos+virgula+input_inserivel.getAttribute(".'"'."data-campo".'"'.");
		acumula_valores=acumula_valores+virgula+'".'"'."'+input_inserivel.getAttribute('data-fkid')+'".'"'."';
                conta_campos=conta_campos+1;
	}
}


var resposta='';
var url='insere_registro.php?banco=escolax&tabela='+tabela+'&campos='+acumula_campos+'&valores='+acumula_valores;
alert(url);
var oReq=new XMLHttpRequest();
oReq.open('GET', url, false);
oReq.onload= function (e) {
	resposta=oReq.responseText;
        alert(resposta);
        var inputs_inseriveis2=document.getElementById(div_insercao).getElementsByClassName('inserivel');
        var input_inserivel2;
	var i;
	for (i=0; i< inputs_inseriveis2.length; i++) 
                {
			input_inserivel2=inputs_inseriveis2[i];
			input_inserivel2.value='';
                        input_inserivel2.style.backgroundColor='#FFFFFF';
                }

        var inputs_inseriveis2=document.getElementById(div_insercao).getElementsByClassName('dropbtn');
        var input_inserivel2;
	var i;
	for (i=0; i< inputs_inseriveis2.length; i++) 
                {
			input_inserivel2=inputs_inseriveis2[i];
			if (input_inserivel2.getAttribute('data-momento')=='insercao') {
				input_inserivel2.value='';
			}
                }
	carrega();
}
oReq.send();
}

function mostra_botao(div_insercao, tabela, nivel){
	nivel_insercao=nivel;
	  var botao='<input  type=\"button\" data-nivel=\"'+nivel+'\" value=\"mostra inserção '+tabela+'\" onclick=\"painel_insercao(`'+div_insercao+'`,`'+tabela+'`)\" />';
	  document.getElementById(div_insercao).innerHTML=botao;

        disable_niveis();

}


function painel_insercao(div_insercao, tabela){
	   nivel_insercao++;
           var resposta='';
           var url='insercao.php?banco=escolax&tabela='+tabela+'&nivel='+nivel_insercao;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
                     var nivel_itz=nivel_insercao-1;
	             document.getElementById(div_insercao).innerHTML=resposta+'<br><input type=\"button\" value=\"fecha inserção '+tabela+nivel_insercao+'\"  data-nivel=\"'+nivel_insercao+'\"   onclick=\"mostra_botao(\''+div_insercao+'\',\''+tabela+'\',\''+ nivel_itz +'\')\" />';
		     ativa_eventos_dropbtn();
		     ativa_alterados();
		     desliga_autocomplete();
	   	     disable_niveis();
                     }
           oReq.send();
}



// atualiza() recebe uma matriz com todos os campos que precisam ser atualizados (menos foreign keys).

function atualiza_fk (id_elemento_campo){

		var campo=document.getElementById(id_elemento_campo);
	                var resposta='';
	                var url='atualiza_campos.php?banco=escolax&tabela='+campo.getAttribute('data-tabela')+'&campo='+campo.getAttribute('data-campo')+'&id='+campo.getAttribute('data-id')+'&valor='+campo.getAttribute('data-fkid');;
	                var oReq=new XMLHttpRequest();
			oReq.open('GET', url, false);
			oReq.onload = function (e) {
				resposta=oReq.responseText;
				alert(resposta);
			}
                oReq.send();
}



function atualiza (matriz){
   matriz.forEach(minhafuncao);

	function minhafuncao(item, index){
		var campo=document.getElementById(item);
                if (campo.getAttribute('data-alterado')=='alterado'){
	                var resposta='';
	                var url='atualiza_campos.php?banco=escolax&tabela='+campo.getAttribute('data-tabela')+'&campo='+campo.getAttribute('data-campo')+'&id='+campo.getAttribute('data-id')+'&valor='+campo.value;
	                var oReq=new XMLHttpRequest();
			oReq.open('GET', url, false);
			oReq.onload = function (e) {
				resposta=oReq.responseText;
				alert(resposta);
				campo.style.backgroundColor='#FFFFFF';
                                campo.setAttribute('data-alterado','nao');
			}
                oReq.send();
	        } 
	}

}

var mywindow=window;
mywindow.resizeTo(document.getElementById('conteudo').scrollWidth+50,document.getElementById('conteudo').scrollHeight+50);
</script>

</body>
</html>";

?>
