

<?php
if(isset($_GET["banco"])){
  $banco = $_GET["banco"];
}


if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}


if(isset($_GET["campos"])){
  $campos = $_GET["campos"];
}

if(isset($_GET["valores"])){
  $valores = $_GET["valores"];
}

include "identifica.php";
$database=$banco;

$conn= new mysqli("localhost", $username, $pass, $database);

$sql="insert into ".$tabela."  (".$campos.") values (".$valores.")";


if ($conn->query($sql)===true){ echo "Inserção de campos efetivada";} else {echo "<br> Deu problema com o sql: ".$sql." erro:".$conn->error;}
return;
?>
