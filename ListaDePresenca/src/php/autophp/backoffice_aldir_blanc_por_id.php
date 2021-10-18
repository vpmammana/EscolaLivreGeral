

<?php

// esse backoffice sintético é exclusivo para a entrada de dados no banco de dados da aldir blanc (escolax). Ele assume que existe um campo id_curador na tabela documentos e usa o nome do usuário como chave de busca, para que o usuário veja apenas os registros que estão associados a ele.

include 'identifica_barra_hiphen.php';
$banco_de_dados = 'escolax'; 
$limitador_registros=50;
if(isset($_GET['id_usuario'])){
  $id_usuario = $_GET['id_usuario'];
}


$conn= new mysqli('localhost', $username, $pass, $banco_de_dados);


echo '
<html>
	<head>
		<title>
			BackOffice Simplificado
		</title>
	</head>
	<style>

table, th, td {
		padding: 10px;
		background-color: gray;
		border: 1px solid white;
		font-size: 1.5rem;
}

.tabela_principal {
		width: 70%;
		left: 15%;
		top: 15%;
		position: absolute;
}

h1 {
	border: 2px solid black;
	background-color: darkblue;
	color: white;
}

	</style>

<body>
<h1>BackOffice Potlatch - Simplificado - Usuário: '.$usuario.'</h1>
<table class="tabela_principal">
<tr>
<th>Tabela</th><th>Descrição</th><th>Botão de Acesso</th>
</tr>
';

// o trecho abaixo foi retirado porque nessa versão do backoffice_aldir_blanc_por_id, o id vem da porta de entrada 
//$sql_id_usuario='select id_chave_registrado from registrados where nome_registrado like "'.$usuario.'%" ';
//
//$result_id_usuario=$conn->query($sql_id_usuario);
//if ($result_id_usuario->num_rows>0) {
//  while($row_id_usuario=$result_id_usuario->fetch_assoc())
//    {
//		$id_usuario=$row_id_usuario['id_chave_registrado'];
//	}
//} else {echo 'Usuário não encontrado';}

$sql_schema='SELECT nome_tabela, descricao_tabela FROM tabelas_para_o_usuario;'; 

$result_schema=$conn->query($sql_schema);
if ($result_schema->num_rows>0) {
  while($row_schema=$result_schema->fetch_assoc())
    {
		$nome_tabela=$row_schema['nome_tabela'];
		$descricao_tabela=$row_schema['descricao_tabela'];
		if ($nome_tabela=='documentos'){
				echo '<tr><td>'.$nome_tabela.'</td><td>'.$descricao_tabela.'</td><td><input type="button" value="'.$nome_tabela.'" onclick="Abre_insere(`insere_'.$nome_tabela.'.php?offset=0&limit='.$limitador_registros.'&campo_busca=id_curador&valor_busca='.$id_usuario.'`);" ></td></tr>';
										}
		else {
				echo '<tr><td>'.$nome_tabela.'</td><td>'.$descricao_tabela.'</td><td><input type="button" value="'.$nome_tabela.'" onclick="Abre_insere(`insere_'.$nome_tabela.'.php?offset=0&limit='.$limitador_registros.'&campo_busca=&valor_busca=`);" ></td></tr>';
		}
	}
} else {echo '<tr><td>Não tem dados</td></tr>';} 
echo '
</table>
<script>

function Abre_insere(codigo_php){


	
var w=window.open(``,`Insere_backoffice_hiper`,`width=900 height=600`);
w.document.body.innerHTML="<div id=`aguarde_inicio` style=`position: absolute; left: 50%; top: 50%; font-size: 50px; padding: 10px; color: black; border: 10px solid black; background-color: yellow; `>AGUARDE</div>";

setTimeout(
function(){
           var resposta=``;
           var url=codigo_php;
           var oReq=new XMLHttpRequest();
           oReq.open(`GET`, url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
                     w.document.write(resposta);
                     }
           oReq.send();
}, 300);
}


</script>
</body>
</html>
';


?>


