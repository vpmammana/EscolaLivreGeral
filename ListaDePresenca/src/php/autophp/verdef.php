
<?php
include "identifica.php";

if(isset($_GET["banco"])){
  $banco_de_dados = $_GET["banco"];
}

if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}
$database=$banco_de_dados;

$conn= new mysqli("localhost", $username, $pass, $database);

$sql="
select col.TABLE_NAME as 'table',
       col.ordinal_position as col_id, col.data_type as dt, col.character_maximum_length as ml,
       col.COLUMN_NAME as COLUMN_NAME,
       case when kcu.REFERENCED_TABLE_SCHEMA is null
            then null
            else '>-' end as rel,
       kcu.REFERENCED_TABLE_NAME
              as primary_table,
       kcu.REFERENCED_COLUMN_NAME as pk_COLUMN_NAME,
       kcu.constraint_name as fk_constraint_name
       from information_schema.columns col
       join information_schema.tables tab
            on col.TABLE_SCHEMA = tab.TABLE_SCHEMA
	         and col.TABLE_NAME = tab.TABLE_NAME
		 left join information_schema.key_column_usage kcu
		      on col.TABLE_SCHEMA = kcu.TABLE_SCHEMA
		           and col.TABLE_NAME = kcu.TABLE_NAME
			        and col.COLUMN_NAME = kcu.COLUMN_NAME
				     and kcu.REFERENCED_TABLE_SCHEMA is not null
				     where col.TABLE_SCHEMA not in('information_schema','sys',
					                                   'mysql', 'performance_schema')
									         and tab.table_type = 'BASE TABLE'
										 and col.TABLE_SCHEMA = '".$banco_de_dados."'
										 and tab.TABLE_NAME= '".$tabela."'
										 order by col.TABLE_SCHEMA,
										          col.TABLE_NAME,
											           col.ordinal_position;

;";
echo "
<html>
<head>
<title>Inspeção de Estrutura de Tabela</title>
<style>

html {
	width: 100%;
        height: 100%;
}

body {
	width: 100%;
        height: 100%;
        background-color: green;

}

table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  background-color: silver;
}
div {
        display: inline-block;
}
</style>
</head>
<body id='conteudo'>
<div  style='background-color: yellow'>
<h1>Descrição da Tabela: '".$tabela."'</h1>
<table>
<tr>
<th>Campo</th>
<th>Tipo</th>
<th>Tamanho</th>
<th>FK_table</th>
<th>FK_column</th>

</tr>";
$result=$conn->query("$sql");
if ($result->num_rows>0) {
  while($row=$result->fetch_assoc())
    {
	$table=$row["table"];
	$coluna=$row["COLUMN_NAME"];
	$type=$row["dt"];
	$length=$row["ml"];
	//$rtn="rtn";
	$rtn=$row["primary_table"];
	//$rcn="rcn";
	$rcn=$row["pk_COLUMN_NAME"];
	echo "<tr><td> ".$coluna."</td><td>".$type."</td><td>".$length."</td><td>".$rtn."</td><td>".$rcn."</td></tr>";

    }
}
echo"</div></table><input type='button' value='Fecha' data-nivel='".$nivel."'  onclick='window.close()'>
<script>
var mywindow=window;
mywindow.resizeTo(document.getElementById('conteudo').scrollWidth+50,document.getElementById('conteudo').scrollHeight+50);
</script>
</body>
</html>" 
?>

