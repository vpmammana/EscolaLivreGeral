
<?php
include "identifica.php";

if(isset($_GET["banco"])){
  $banco_de_dados = $_GET["banco"];
}

if(isset($_GET["rtn"])){  //  é a tabela remota onde está uma das duas chaves externas
  $rtn = $_GET["rtn"];    //
}

if(isset($_GET["rcn"])){  //  é a chave primária da tabela onde está a chave externa
  $rcn = $_GET["rcn"]; //
}

if(isset($_GET["table_para_search"])){  //  é a tabela onde serão buscados os dados
  $table_para_search = $_GET["table_para_search"]; //
}

if(isset($_GET["coluna"])){ // é a coluna independente da tabela_para_search 
  $coluna = $_GET["coluna"];
}

if(isset($_GET["campo_externo_para_search"])){  // campo externo da tabela com cardinalidade N to N, que aponta para <tabela> tratada pelo código insere_<tabela>.php
  $campo_externo_para_search = $_GET["campo_externo_para_search"]; //
}

if(isset($_GET["id_externo_para_search"])){  // o identificador id_chave do registro da <tabela> de insere_<tabela>.php, ou seja. A <tabela> é a que é referenciada pela chave externa de $tabela, que a tabela com cardinalidade N to N
  $id_externo_para_search = $_GET["id_externo_para_search"];
}

$table=$table_para_search;

$conn= new mysqli("localhost", $username, $pass, $banco_de_dados);


$sql_schema="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '".$table."' AND CONSTRAINT_NAME = 'PRIMARY' limit 1;";// vamos pegar o nome da chave primária

$result_schema=$conn->query("$sql_schema");
if ($result_schema->num_rows>0) {
  while($row_schema=$result_schema->fetch_assoc())
    {
	$chave_primaria=$row_schema["COLUMN_NAME"];
	}
} else {echo "<tr><td>Não tem dados</td></tr>";} // XXX



$nome_a_partir_do_rcn=str_replace("id_chave_","nome_",$rcn); // essa solucao assume que a tabela externa tem um campo nome_. Isso pode não ser verdade. O certo seria buscar o campo nome e se não tivesse, chamar um erro

$sql_externo="select a.".$nome_a_partir_do_rcn.", b.".$chave_primaria." from ".$rtn." as a, ".$table." as b  where a.".$rcn."=b.".$coluna." and b.".$campo_externo_para_search."='".$id_externo_para_search."';";

echo "<tr><td><div class='classe_contem_nomes' id='contem_nomes_".$campo_externo_para_search."_".$id_externo_para_search."'>
".$nome_a_partir_do_rcn." 
<table class='classe_contem_nomes' style='background-color: blue'>
<tr>
";

$result_externo=$conn->query("$sql_externo");
if ($result_externo->num_rows>0) {
  while($row_externo=$result_externo->fetch_assoc())
    {
	$nome_rcn=$row_externo[$nome_a_partir_do_rcn];
	$id_chave=$row_externo[$chave_primaria];
	echo "<tr><td class='classe_contem_nomes'>".$nome_rcn."<input type='button' value='apaga' onclick='apaga_registro_com_tabela(`".$tabela."`,".$id_chave.");'></td></tr>";
	} 
} else {echo "<tr><td>Não tem dados</td></tr>";}

echo "
</tr>
</table>
</div></td></tr>";

echo"</table></div>

" 
?>
