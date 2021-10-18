


<?php
include "identifica.php";

if(isset($_GET["banco"])){
  $banco = $_GET["banco"];
}

if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}


if(isset($_GET["campo"])){
  $campo = $_GET["campo"];
}

if(isset($_GET["str_busca"])){
  $str_busca = $_GET["str_busca"];
}


$database=$banco;

$conn= new mysqli("localhost", $username, $pass, $database);
	$sql="select COLUMN_NAME from information_schema.columns where TABLE_NAME='".$tabela."' and COLUMN_NAME like 'nome_%'";
$result=$conn->query("$sql");


if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
      $campo_nome=$row["COLUMN_NAME"];
   }
} else {echo "Não veio nome_";};

	$sql="select COLUMN_NAME from information_schema.columns where TABLE_NAME='".$tabela."' and COLUMN_NAME like 'id_chave__%'";
$result=$conn->query("$sql");


if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
      $campo_id=$row["COLUMN_NAME"];
   }
} else {echo "Não veio nome_";};



$sql="select ".$campo_id.",".$campo_nome." from ".$tabela." where ".$campo_nome." like '".$str_busca."%';";
$result=$conn->query("$sql");
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
	$nome=$row[$campo_nome];
	$id=$row[$campo_id];
        echo $nome."<rb>".$id."<br>";

    }
} else {echo "vazio";}

?>

