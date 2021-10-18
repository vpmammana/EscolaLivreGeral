

<?php


if(isset($_GET["banco"])){
  $banco = $_GET["banco"];
}

if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}

if(isset($_GET["nome_chave_primaria"])){
  $nome_chave_primaria = $_GET["nome_chave_primaria"];
}

if(isset($_GET["busca_str"])){
  $busca_str = $_GET["busca_str"];
}



include "identifica.php";
$database=$banco;

$conn= new mysqli("localhost", $username, $pass, $database);


$campos_excluidos=array(1,2,3,4);
unset($campos_excluidos);

$sql_excluidos="select nome_do_campo_excluido from excluidos_sub_views";

$result_excluidos=$conn->query("$sql_excluidos");
if ($result_excluidos->num_rows>0) {  // aqui nos vamos montar o query que busca as chaves primárias
  while($row_excluidos=$result_excluidos->fetch_assoc())
    {
      $coluna_excluida=$row_excluidos["nome_do_campo_excluido"];
      $campos_excluidos[]=$coluna_excluida;
    }
} 

                      $sql5="select ".$nome_chave_primaria." from ".$tabela.";";
                      $result_5=$conn->query("$sql5");

if ($result_5->num_rows>0){
while ($row5=$result_5->fetch_assoc())
{
$id_chave_primaria=$row5[$nome_chave_primaria];

 
$sql="select col.TABLE_NAME as 'table',
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
                                                                                 and col.TABLE_SCHEMA = '".$banco."'
                                                                                 and tab.TABLE_NAME= '".$tabela."'
                                                                                 order by col.TABLE_SCHEMA,
                                                                                          col.TABLE_NAME,
                                                                                                   col.ordinal_position;";

$result=$conn->query("$sql");

$acumula_colunas="";
$acumula_tabelas_fk="";
$virgula="";
$virgula_tables="";
$sub_select="";
$chave_primaria=""; // chave primaria da tabela atual
if ($result->num_rows>0) {  // aqui nos vamos montar o query que busca as chaves primárias
  while($row=$result->fetch_assoc())
    {
      $nome_coluna=$row["COLUMN_NAME"];
      $primary_table=$row["primary_table"];
      $primary_column=$row["pk_COLUMN_NAME"];
      if (strpos($nome_coluna, "id_chave") !== false) {$chave_primaria=$nome_coluna;}  // assume que chave primária é sempre o primeiro campo da tabela. Se não for asssim: crash!
      if (in_array($nome_coluna, $campos_excluidos)) {} else
      {
	      if ($primary_table!="") // esse if é para formar a lista concatenada de campos. Esse if também geraa os selects que vão buscar na tabela primária  os nomes
		{
  
		      $acumula_tabelas_fk=$acumula_tabelas_fk.$virgula_table.$primary_table;
		      $virgula_table=", ";
                      

		      $sql3="select ".$nome_coluna." from ".$tabela." where ".$chave_primaria." ='".$id_chave_primaria."'";
                      $result_3=$conn->query("$sql3");
         
                      if ($result_3->num_rows>0) {
				while ($row3=$result_3->fetch_assoc())
				{
					$id_fk=$row3[$nome_coluna]; // esse é o id que precisa ser procurado na tabela FK
				} // while
		      } //if result_3>numrows
		      $sql2="select COLUMN_NAME from information_schema.columns where TABLE_NAME='".$primary_table."' and COLUMN_NAME like 'nome_%'";
                      $result_2=$conn->query("$sql2");
         
                      if ($result_2->num_rows>0) {
				while ($row2=$result_2->fetch_assoc())
				{
					$coluna_com_nome=$row2["COLUMN_NAME"];
				} // while
		      } //if result_2>numrows
                      else {$coluna_com_nome="nao_achei_nome";}
		      
		      $sub_select="(select ".$coluna_com_nome." from ".$primary_table." where ".$primary_column."=".$id_fk.")";
		      $nome_coluna=$sub_select;
    		}
	      $acumula_colunas=$acumula_colunas.$virgula.$nome_coluna;


	      $virgula=", '/' , ";
    }
    }
} else {echo 'Deu Problema: '.$conn->error;}
$sql_final= "select CONCAT(".$acumula_colunas.") as saida from ".$tabela." where ".$nome_chave_primaria." = ".$id_chave_primaria.";";
$result_final=$conn->query("$sql_final");
if ($result_final->num_rows>0){
	while ($row_final=$result_final->fetch_assoc())
		{
		    $saida=$row_final["saida"];
		}
}
if ((stripos($saida, $busca_str) === 0) || $busca_str=="") {echo $saida."<rb>".$id_chave_primaria."<br>";} 

$acumula_colunas="";

}

} else {echo "Tabela vazia: ".$conn->error;}
?>
