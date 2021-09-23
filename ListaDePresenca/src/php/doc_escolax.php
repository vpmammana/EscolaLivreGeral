<!DOCTYPE html>
<html lang='pt-br'>
<head>
<title>Documento de referência técnica da Escolax</title>
<meta charset="utf-8">
<meta name="keywords"  content="Escolax,Giramundonics,portlet">
<meta name="author"    content="Antonio Albuquerque,Vitor Mammana">
<link rel='stylesheet' href='./super_documentacao.css' type='text/css'>
</head>
<body onload="lista_h2()">
<div id="menu">
Conteúdo
</div>

<div id="conteudo">
<h1>Documento de Referência da Escolax</h1>
<table>
<tr><td>
<img src="./super_wash.jpg" width="400" style="border: 1px solid black"></td>
<td><div class="comentario">
<b>Objetivo</b>
<p></p><p></p>
Descrever as características da solução Escolax, criando as condições para o usuário instalar e utilizar a solução.
<br \><br \>

<b>Breve Histórico</b>
A Escolax visa criar um sistema de controle de acesso para a Escolas Públicas. O Sistema foi desenvolvido a partir de uma solicitação da Professora Thatiane, Diretora da Escola Roberto Panico de Londrina. A Profa. Thatiane descreveu sua visão sobre como o sistema deveria ser: as crianças seriam identificadas a partir de um QR code colado na parte de trás da carteira escolar já existente dos estudantes. A partir desta solicitação, o coordenador do Projeto WASH, Dr. Victor Mammana, entrou em contato com os membros do Laboratório Hacker de Campinas (LHC), solicitando voluntários para a realização do projeto, principalmente no que se refere ao aplicativo de identificação de QR Code. O Sr. Fernando Bonafé apresentou-se como voluntário para desenvolver o aplicativo. O Sr. Bonafé indicou que se concentraria no desenvolvimento do front-end (aplicativo de coleta dos QR Codes) em formato javascript puro. Victor Mammana ficou responsável pelo desenvolvimento do back-end.

<p></p>
Este projeto é mais uma iniciativa do <a href="http://wash.net.br" target="_blank">Programa Wash</a> - Workshop Aficionados em Software e Hardware. Para sua realização foi preciso contar com a colaboração do Sr. Fernando Bonafé, que liderou o desenvolvimento do aplicativo de coleta de QR Codes. Por unanimidade, ficou decidido que o código fonte seria disponibilizado no formato de licença livre.

<br \><br \>Seja Bem Vind@, e aproveite!!
</div>
</td></tr></table>
<p></p>
<h2 id="consideracoes_iniciais">Considerações Iniciais sobre a Escolax</h2>
A solução Escolax constitui-se de um sistema formado pelos seguintes blocos distintos:
<ul>
<li>a plataforma Potlatch: um sistema CRUD (Create, Retrieve, Update & Delete), voltado para a entrada de dados de forma estruturada no banco de dados
localizado no servidor MySQL.Serve para manutenção dos dados, tais como: correção de registros, inserção de registros um-a-um, criação de novas categorias, etc. É tipicamente um "back-office" mais amigável do que um "phpmyadmin".
 </li>

<li>um visualizador de dados: Giramundônics.  É destinado uma visualização prática da presença dos alunos.
Não tem ferramentas de entrada de dados. Não é um CRUD.
Permite visualizar a lista de estudantes presentes na escola.
</li>

<li>um sistema de geração de QR codes (criado por Fernando Bonafé e adaptado por Victor Mammana).
A partir de uma lista CSV de nomes de estudantes e número de identificação do estudante, é possível gerar um arquivo PDF com os QR Codes de todos os estudantes. Este sistema foi desenvolvido por Fernando Bonafé.
</li>

<li>um aplicativo de celular para a coleta de QR codes (criado por Fernando Bonafé).
Trata-se do coração da coleta de QR codes, que ao final envia os dados para o banco de dados. Foi criado por Fernado Bonafé e adpatado por Victor Mammana para o modelo de dados da base Escolax. 
</li>
</ul>

Dentre as informações que podem ser disponibilizados pela Escolax, estão:
<ul>
<li>Nomes e demais dados cadastrais das pessoas ligadas à escola (professores, estudantes, etc). A proposta é que os dados sensíveis sejam criptografados, ou o servidor fique isolado da rede principal da escola. Estes dados são guardados na tabela <i>registrados</i></li>
<li>Todas as turmas da escola</li>
</ul>

<h2>Repositório e instalação da Escolax</h2>

<p>
A Escolax é constituída por um front-end (desenvolvido por Fernando Bonafé) e por um back-end (desenvolvido por Victor Pellegrini Mammana).
</p>

<p>
O repositório encontra-se em <a href="https://github.com/bonafe/EscolaBrasil/tree/main/ListaDePresenca/Turmas">GitHub de Bonafé</a>.
</p>

<p>
Para instalar a plataforma é preciso copiar o diretório <b>Turmas</b> para o diretório <b>/var/www/html/</b> do servidor LAMP.
</p>





</div>
<script>
function lista_h2(){
var i;
x=document.getElementsByTagName("H2");
menuzinho=document.getElementById("menu");
menuzinho.innerHTML=menuzinho.innerHTML+"<br><br>";
for (i=0; i<x.length; i++){
menuzinho.innerHTML=menuzinho.innerHTML+"<br><a class='lista_de_conteudo' href='#"+x[i].id+"'>"+x[i].innerHTML+"</a><br>";

}
}
</script>
</body>
</html>

