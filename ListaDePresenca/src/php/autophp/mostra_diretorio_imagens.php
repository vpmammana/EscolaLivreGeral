

<?php

if(isset($_GET["banco"])){
  $banco= $_GET["banco"];
}
if(isset($_GET["diretorio"])){    // diretorio onde o php vai buscar os arquivos. Não deveria ser flexível no apaga (forçamos imagens)
  $diretorio_puro= $_GET["diretorio"];
}
if(isset($_GET["id_input"])){    // id do elemento da página que chama esta página, o qual guarda o nome do arquivo de imagem. Precisa colocar 					// o nome selecionado quando esta página fecha, por isso 
  $id_input= $_GET["id_input"];
}
if(isset($_GET["id_input_path"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $id_input_path= $_GET["id_input_path"];
}
if(isset($_GET["id_input_img"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $id_input_img= $_GET["id_input_img"];
}

if(isset($_GET["jpg"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $jpg= $_GET["jpg"]; // 1 mostra; 0 nao mostra
}

if(isset($_GET["gif"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $gif= $_GET["gif"]; // 1 mostra; 0 nao mostra

}

if(isset($_GET["png"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $png= $_GET["png"]; // 1 mostra; 0 nao mostra

}

if(isset($_GET["pdf"])){   // id do elemento da página que chama esta página, o qual guarda o diretorio da imagem
  $pdf= $_GET["pdf"]; // 1 mostra; 0 nao mostra
}
if ($pdf!=0) {$pdf_checked="checked";} else {$pdf_checked="";}
if ($jpg!=0) {$jpg_checked="checked";} else {$jpg_checked="";}
if ($gif!=0) {$gif_checked="checked";} else {$gif_checked="";}
if ($png!=0) {$png_checked="checked";} else {$png_checked="";}



$diretorio="../".$diretorio_puro; // precisa subir um diretório porque está no autophp, que está no mesmo nível de imagens, abaixo de deia
echo "
<html>
<head>
<title>Lista de Arquivos do Diretorio $diretorio</title>
</head>
<style>
table, td, th {
	background-color: gray;
        border: 1px solid black;
        border-collapse: collapse;
}
</style>
<body>
<h1>Imagens do diretório <i style='color: blue'>$diretorio</i></h1>
<table>
<tr>
<td class='escolha'><input type='checkbox' ".$jpg_checked." onmouseup='alert(`alo`); recarrega(".intval($jpg+1).",".$png.",".$gif.",".$pdf.");'>JPG</input></td>
<td class='escolha'><input type='checkbox' ".$png_checked." onmouseup='alert(`alo`); recarrega(".$jpg.",".intval($png+1).",".$gif.",".$pdf.");'>PNG</input></td>
<td class='escolha'><input type='checkbox' ".$gif_checked." onmouseup='alert(`alo`); recarrega(".$jpg.",".$png.",".intval($gif+1).",".$pdf.");'>GIF</input></td>
<td class='escolha'><input type='checkbox' ".$pdf_checked." onmouseup='alert(`alo`); recarrega(".$jpg.",".$png.",".$gif.",".intval($pdf+1).");'>PDF</input></td>
</tr>
</table>

<table>
<tr>
<th>Nome Arquivo</th><th>Tipo</th><th>Imagem</th><th>Selecionar</th><th>A imagem está sendo usada?</th>
</tr>
";
$arquivos=scandir ($diretorio);
$conta=0;
foreach ($arquivos as $value)
	{

                $imageFileType = strtolower(pathinfo($value,PATHINFO_EXTENSION));
	if(($imageFileType != "jpg" || $jpg==0)  && ($imageFileType != "png" || $png==0) && ($imageFileType != "jpeg" || $jpg==0) && ($imageFileType != "gif" || $gif==0) && ($imageFileType != "pdf" || $pdf==0) ) 
		
		{} 
			else
				{

					$dados_imagem=getimagesize($diretorio."/"."$value");
					if ($imageFileType != "pdf")
                                                   {$nome_src=$diretorio."/".$value;}
					else { $nome_src="pdf_thumb.php?pdf=".$diretorio."/".$value;}
 

					echo "<tr><td id='valor_".$conta."'>".$value."</td>
					      <td id='extensao_".$conta."'>".$imageFileType."</td>
 
						<td>
						<img src='".$nome_src."' style='width: 200px; height: auto; background-color: white'>
				   	      </td>
					      <td>
						<input type='button' value='seleciona' 
							onclick='
								var dir_forcado=\"../imagens/\"; // a ideia era tentar ter como saber na tela qual o dir... isso aqui precisa de revisao
								try {

									var tipo_tag=window.opener.document.getElementById(\"".$id_input_path."\").tagName;
									if (tipo_tag==\"SPAN\"){dir_forcado=\"".$diretorio."/\";} else {dir_forcado=\"../imagens/\";}
									
									window.opener.document.getElementById(\"".$id_input_path."\").value=\"".$diretorio_puro."\";
							        	window.opener.document.getElementById(\"".$id_input_path."\").setAttribute(\"data-alterado\", \"alterado\");
								    }
								catch(err) {alert(\"Ignore esse erro e seja uma pessoa feliz! \"+err.message);}
								window.opener.document.getElementById(\"".$id_input."\").value=dir_forcado+document.getElementById(\"valor_".$conta."\").innerText;
							        window.opener.document.getElementById(\"".$id_input."\").setAttribute(\"data-alterado\", \"alterado\");

								window.opener.document.getElementById(\"".$id_input_img."\").setAttribute(\"data-ja-tentei\", \"nao\");

								if (document.getElementById(\"extensao_".$conta."\").innerText!=\"pdf\"){
								window.opener.document.getElementById(\"".$id_input_img."\").src=\"".$diretorio."\"+\"/\"+document.getElementById(\"valor_".$conta."\").innerText;} else {
								window.opener.document.getElementById(\"".$id_input_img."\").src=\"pdf_thumb.php?pdf=".$diretorio."\"+\"/\"+document.getElementById(\"valor_".$conta."\").innerText;}
								window.close();
	
						'/></td>
					      <td><input type='button' value='apaga' onclick='apaga(".'"'.$diretorio.'"'.",document.getElementById(".'"valor_'.$conta.'"'.").innerText)'></td>
					      <td>".$dados_imagem[0]."x".$dados_imagem[1]."<br>".$dados_imagem["mime"]."</td>
					      <td><b>Imagem usada por:</b><br>";
				    
					include "identifica.php";;
					$database=$banco;

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
										 and col.TABLE_SCHEMA = '".$banco."'
										 order by col.TABLE_SCHEMA,
										          col.TABLE_NAME,
											           col.ordinal_position;

";

					$result=$conn->query("$sql");


					if ($result->num_rows>0) {
					  while($row=$result->fetch_assoc())
					    {
					      $tabela=$row["table"];
					      $nome_coluna=trim($row["COLUMN_NAME"]);
					      if (stripos($nome_coluna,"photo_filename_")===false){} else {
                                                        $sql2="select ".$nome_coluna." from ".$tabela." where ".$nome_coluna."='".$value."'";
							$result2=$conn->query("$sql2");
							if ($result2->num_rows>0) {
					  			while($row2=$result2->fetch_assoc())
					    			{
					      				$filename=$row2[$nome_coluna];
										echo "Tabela: ".$tabela."<br>";
					    			}
							} else {}

					      }
					   }
					}
echo "</td></tr>";
					$conta=$conta+1;
				}
	}

echo "
</table>

<script>
function recarrega(jpg, png, gif, pdf){
 var jpg_selecionado=jpg & 1;
 var png_selecionado=png & 1;
 var gif_selecionado=gif & 1;
 var pdf_selecionado=pdf & 1;


          var resposta='';
           var url='mostra_diretorio_imagens.php?banco=".$banco."&diretorio=".$diretorio_puro."&id_input=".$id_input."&id_input_path=".$id_input_path."&id_input_img=".$id_input_img."&jpg='+jpg_selecionado+'&gif='+gif_selecionado+'&png='+png_selecionado+'&pdf='+pdf_selecionado;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
                     document.body.innerHTML=resposta;
                     }
           oReq.send();


}


function apaga(diretorio, arquivo){

if (confirm('Tem certeza que você quer apagar o arquivo '+arquivo+' do diretório imagens?'))
{
           var resposta='';
           var url='apaga_imagens.php?arquivo=../imagens/'+arquivo;
           var oReq=new XMLHttpRequest();
           oReq.open('GET', url, false);
           oReq.onload = function (e) {
                     resposta=oReq.responseText;
		     alert(resposta);
			var resposta2='';
           		var url2='mostra_diretorio_imagens.php?banco=".$banco."&diretorio=".$diretorio_puro."&id_input=".$id_input."&id_input_path=".$id_input_path."&id_input_img=".$id_input_img."&jpg=1&gif=1&png=1&pdf=1';
           		var oReq2=new XMLHttpRequest();
           		oReq2.open('GET', url2, false);
	           	oReq2.onload = function (e) {
                     		resposta2=oReq2.responseText;
                                document.body.innerHTML='';
                                document.body.innerHTML=resposta2;
                     		}
           		oReq2.send();
                     }
           oReq.send();
}
}
</script>

</body>
</html>
";


?>
