

<?php
if(isset($_GET["banco"])){
  $banco = $_GET["banco"];
}


if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}


if(isset($_GET["campo_id"])){
  $campo_id = $_GET["campo_id"];
}
if(isset($_GET["id"])){
  $id = $_GET["id"];
}

$campo_nome='';
$achado='';

include 'identifica_barra_hiphen.php';
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

$sql="select ".$campo_nome." from ".$tabela." where ".$campo_id." =".$id;

$result=$conn->query("$sql");


if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
      $achado=$row[$campo_nome];
   }
};
echo $achado;
?>
