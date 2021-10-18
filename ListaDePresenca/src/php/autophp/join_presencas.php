

<?php

echo "
<html>
<head>
<title>Inserção de dados (mar2020): presencas</title>
<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate'/>
<meta http-equiv='Pragma' content='no-cache'/>
<meta http-equiv='Expires' content='0'/>

<meta charset='UTF-8'>

<style>

div.cabecalio {
	background-color: lightgreen;
	border: 1px solid black;
}

table,td,th {
	background-color: green;
        border: 1px solid black;
        border-collapse: collapse;
}
</style>

</head>
<body id='conteudo'>
<div class='cabecalio'>
<h1>Duas Visualizações Left Join: presencas</h1>
</div>

<table>

<tr>
<th>Ordem Direta</th><th>Ordem Inversa</th>
</tr>
<tr>
<td>
<table>
<tr>
<th>id_chave_registrado</th><th>id_chave_evento</th>
</tr>
";


include "identifica.php";
$database="escolax";

$conn= new mysqli("localhost", $username, $pass, $database);

$sql1="select COLUMN_NAME as nome0 from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='escolax' and TABLE_NAME='registrados' and COLUMN_NAME like 'nome_%';";

$result=$conn->query("$sql1");
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
       $nome0=$row["nome0"];
    }
}  else {echo "nao achou o campo comecando com nome_1";}

$sql2="select COLUMN_NAME as nome1 from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='escolax' and TABLE_NAME='eventos' and COLUMN_NAME like 'nome_%';";

$result=$conn->query("$sql2");
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
       $nome1=$row["nome1"];
    }
}
$sql="select ".$nome0." as n0, ".$nome1." as n1 from  registrados as a, presencas as b left join eventos as c on b.id_evento=c.id_chave_evento where b.id_registrado=a.id_chave_registrado  order by ".$nome0.";";

$result=$conn->query("$sql");
$velho="";
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
       $campo_1=$row["n0"];
       $campo_2=$row["n1"];
	if (($velho!=$campo_1) && ($velho!="")) {echo "</td></tr>";}
       if ($velho!=$campo_1) { $velho=$campo_1;
                                echo "<tr>
	<td class='fks_field' data-default='' data-fks-table='registrados' data-fks-field='id_chave_registrado' data-fkid='".$campo_1."'>".$campo_1."</td>
	<td class='fks_field' data-default='' data-fks-table='eventos' data-fks-field='id_chave_evento' data-fkid='".$campo_2."'>".$campo_2."<br>";


                             }
        else {

            echo $campo_2."<br>";
	

         }

    }

	echo "</td></tr>";

} else {echo "Erro: ".$conn->error;}


echo "
</table>
</td>
<td>
<table>
<tr>
<th>id_chave_evento</th><th>id_chave_registrado</th>
</tr>";



$sql="select ".$nome1." as n0, ".$nome0." as n1 from  eventos as a, presencas as b left join registrados as c on b.id_registrado=c.id_chave_registrado where b.id_evento=a.id_chave_evento  order by ".$nome1.";";

$result=$conn->query("$sql");
$velho="";
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
       $campo_1=$row["n0"];
       $campo_2=$row["n1"];
	if (($velho!=$campo_1) && ($velho!="")) {echo "</td></tr>";}
       if ($velho!=$campo_1) { $velho=$campo_1;
                                echo "<tr>
	<td class='fks_field' data-default='' data-fks-table='registrados' data-fks-field='id_chave_registrado' data-fkid='".$campo_1."'>".$campo_1."</td>
	<td class='fks_field' data-default='' data-fks-table='eventos' data-fks-field='id_chave_evento' data-fkid='".$campo_2."'>".$campo_2."<br>";


                             }
        else {

            echo $campo_2."<br>";
	

         }

    }

	echo "</td></tr>";

} else {echo "Erro: ".$conn->error;}

echo "
</table>

</td>
</tr>
</table>
</body>
</html>


";



?>

