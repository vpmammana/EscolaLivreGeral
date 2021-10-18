<?php 
if(isset($_POST["submit"])){
 // Count total files
 $countfiles = count($_FILES["file"]["name"]);
echo "
<h1>Lista de Arquivos a serem gravados no servidor</h2>
<html>
<style>

table, td, th 
	{
		background-color: green;
		border: 1px solid black;
        } 

</style><body><table>"; 
 // Looping all files
 for($i=0;$i<$countfiles;$i++){
   $filename = $_FILES["file"]["name"][$i];
   echo "<tr><td>".$filename."</td></tr>"; 
   // Upload file
   move_uploaded_file($_FILES["file"]["tmp_name"][$i],"../imagens/".$filename);
    
 }
echo "</table>";
} else {echo "Ocorreu algum problema: o servidor não recebeu os arquivos.";}

echo "<a href='http://localhost/dev_usu_ario/autophp/backoffice.html'>Volta para BackOffice</a>";
?>


