
<?php
include "identifica.php";

if(isset($_GET["banco"])){
  $banco_de_dados = $_GET["banco"];
}


if(isset($_GET["nivel"])){  // passa o nivel hierarquico daquela insercao.
  $nivel = $_GET["nivel"];
}

if(isset($_GET["tabela_externa_em_edicao"])){ // é a tabela que está sendo editada e que tem um registro com id conhecido.
  $tabela_externa_em_edicao = $_GET["tabela_externa_em_edicao"];
}


if(isset($_GET["campo_externo_para_search"])){  // campo externo da tabela com cardinalidade N to N, que aponta para <tabela> tratada pelo código insere_<tabela>.php, onde <tabela> é aquela que tem um registro com id conhecido
  $campo_externo_para_search = $_GET["campo_externo_para_search"]; //
}

if(isset($_GET["id_externo_para_search"])){  // o identificador id_chave do registro da <tabela> de insere_<tabela>.php, ou seja. A <tabela> é a que é referenciada pela chave externa de $tabela, que a tabela com cardinalidade N to N, é o valor do id do registro conhecido da <tabela> que está sendo editada por insere_<tabela>.php
  $id_externo_para_search = $_GET["id_externo_para_search"];
}
$tabela=$tabela_externa_em_edicao;


$cor_letra="black";
if ($nivel=="1") {$cor_letra="white";  $cor_background="darkgray";}
if ($nivel=="2") { $cor_background="blue"; $cor_letra="white";}
if ($nivel=="3") { $cor_background="green";}
if ($nivel=="4") { $cor_background="blue"; $cor_letra="yellow";}
if ($nivel=="5") { $cor_background="orange";}

echo "<div id='todos_os_n_to_n' style='border: hidden;'>
<table style='border: hidden'>
<tr>
";
$database=$banco_de_dados;

$conn= new mysqli("localhost", $username, $pass, $database);


$sql_tabelas_de_ligacao="select 
												nome_tabela_de_ligacao           ,
												campo_externo1_tabela_de_ligacao ,
												campo_externo2_tabela_de_ligacao ,
												tabela_externa1                  ,
												campo_name_tabela_externa1       ,
												campo_id_tabela_externa1         ,
												tabela_externa2                  ,
												campo_name_tabela_externa2		 , 
												campo_id_tabela_externa2         
						from tabelas_de_ligacao;";

$result_tl=$conn->query("$sql_tabelas_de_ligacao");
if ($result_tl->num_rows>0) {
  while($row_tl=$result_tl->fetch_assoc())
    {
		$nome_tabela_de_ligacao           =   $row_tl["nome_tabela_de_ligacao"          ]; 
		$campo_externo1_tabela_de_ligacao =   $row_tl["campo_externo1_tabela_de_ligacao"]; 
		$campo_externo2_tabela_de_ligacao =   $row_tl["campo_externo2_tabela_de_ligacao"]; 
		$tabela_externa1                  =   $row_tl["tabela_externa1"                 ]; 
		$campo_name_tabela_externa1       =   $row_tl["campo_name_tabela_externa1"      ]; 
		$campo_id_tabela_externa1         =   $row_tl["campo_id_tabela_externa1"        ]; 
		$tabela_externa2                  =   $row_tl["tabela_externa2"                 ]; 
		$campo_name_tabela_externa2		  =   $row_tl["campo_name_tabela_externa2" 	  ]; 
		$campo_id_tabela_externa2         =   $row_tl["campo_id_tabela_externa2"       ]; 


if ($tabela_externa1==$tabela_externa_em_edicao || $tabela_externa2==$tabela_externa_em_edicao )
{
	if ($tabela_externa1==$tabela_externa_em_edicao)
	 {
	 	$campo_externo_para_search=$campo_externo1_tabela_de_ligacao;
    	$tabela_independente=$tabela_externa2;
    	$campo_independente=$campo_externo2_tabela_de_ligacao;
    	$chave_externa_independente=$campo_id_tabela_externa2;
    	$nome_externa_independente=$campo_name_tabela_externa2;
    
	 }
	if ($tabela_externa2==$tabela_externa_em_edicao)
	 {
	 	$campo_externo_para_search=$campo_externo2_tabela_de_ligacao;
    	$tabela_independente=$tabela_externa1;
    	$campo_independente=$campo_externo1_tabela_de_ligacao;
    	$chave_externa_independente=$campo_id_tabela_externa1;
    	$nome_externa_independente=$campo_name_tabela_externa1;


	 }

	 
} else {continue;} // se a tabela externa nao for a que está em edição, aquele tabela_de_ligacao não interessa

echo "
<td style='vertical-align: top;'>
<div class='classe_filho_ponto_insercao_nton' id='insercao_nton_".$nome_tabela_de_ligacao."_".$id_externo_para_search."' style=' border: 3px solid red; padding: 3px;'>
<table class='tabela_interna_da_insercao' style='vertical-align: top ;border-collapse: collapse; border: none; padding:5px; text-align: left; width: 100%; '>
";


// o if abaixo provavelmente não é necessário, porque se o registro se refere à $tabela_externa_em edicao, então, provavelmente não 
//haverá uma tabela externa apontando para ela mesma (exceto no caso de uma estrutura de árvore, por isso vou manter - mas ao manter vou tirar a possibilidade do pai da folha ser escolhido)
if ($nome_tabela_de_ligacao===$tabela_externa_em_edicao ) { // pula o while no caso da própria tabela que está em edição
        continue;  // interrompe o while até a próxima iteração
    }


echo "<tr><td style='vertical-align: top; border: none; '><span style='vertical-align: top; font-size: 20px'>'".$nome_tabela_de_ligacao."'</span></td></tr>";

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
										 and tab.TABLE_NAME= '".$nome_tabela_de_ligacao."'
										 order by col.TABLE_SCHEMA,
										          col.TABLE_NAME,
											           col.ordinal_position;

;";

$max_tamanho_text=90; // maximo tamanho do input text, quando nao for int
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
        if ($length>$max_tamanho_text){$visible_length=$max_tamanho_text;} else {$visible_length=$length;}
        if ($type=='int') {$visible_length=4;}


if (		(strpos($coluna,'id_chave')===false) &&
		(strpos($coluna,'time_stamp')===false) &&
		(strpos($coluna,'usuario')===false) 
)
{
	if ($type=='date') {$default_input='0000-01-01';} else {$default_input='';}

//	if (strpos($coluna,$campo_externo_para_search)===false){
//
//	echo "<tr>";
//	}

        if (
		($rtn=='') 
           )
         {

		if (strpos($coluna,'photo_filename_')===false){
			if (strpos($coluna,$campo_externo_para_search)===false){
			echo "          <tr>        <td>".$coluna."<br><input 
								type='text' 
								value='".$default_input."'
								placeholder='Entre dado'
								class='inserivel' 
								data-tabela='".$table."' 
								data-alterado='nao' 
								data-campo='".$coluna."'
								data-nivel='".$nivel."' 
								size='".$visible_length."' 
								maxlength='".$length."' />
						</td>
				              </tr>";
			} else {echo "O id de inserção na tabela '".$campo_externo_para_search."' será '".$id_externo_para_search."'.";}
		}
		else {
			echo "
			<td>	<img id=".'"photo_filename_img"'." style=".'"background-color: white; width: 80; height: auto"'."
				     src=".'"../imagens/lupa.png"'.">
				<input type=".'"button"'." value=".'"Imagem do Servidor"'." data-nivel=".'"'.$nivel.'"'." 
					onclick=".'"
						var identificador=`id_diretorio_photos`;
						var dummy=``;
						var mostra=`MostraArquivosDiretorio`;
						var w_h=`width=1000 height=1000`;
						var w=window.open(dummy,mostra,w_h);
						var resposta=``;
						var url=`mostra_diretorio_imagens.php?banco='.$banco_de_dados.'&diretorio=imagens&id_input=id_campo_nome_photo_filename&id_input_path=`+identificador+`&id_input_img=photo_filename_img&jpg=1&png=1&gif=1&pdf=0`;
						console.log(url);
						var oReq = new XMLHttpRequest();
						var gueti=`GET`;
						oReq.open(gueti, url, false);
						oReq.onload= function (e) {
							resposta=oReq.responseText;
							w.document.write(resposta);
						}
						oReq.send();
					"'."
						 
					
				/>
				<input type='button' value='Amplia' data-nivel='".$nivel."' 
					onclick='
						var file_=document.getElementById(`id_campo_nome_photo_filename`).value;
						if (file_ != ``) { 
						var wu=window.open(``, `Janela_Ampliacao`, `width=`+screen.availWidth+` height=`+screen.availHeight);
						if (file_.indexOf(`pdf`)>-1){
									wu.document.write(`<embed src=\"../imagens/`+file_+`\" width=\"800px\" height=\"2100px\"/>` );
								} 
								else
								{
									wu.document.write(`<embed src=\"../imagens/`+file_+`\" width=\"800px\" height=\"auto\"/>` );
								}	
						} else {alert(`Você não selecionou uma imagem!`);}
				'>
				<input type='button' value='Sobe Imagem' data-nivel='".$nivel."' onclick='document.getElementById(`id_sobe_imagem`).click();'>
				<input style='display: none' data-nivel='".$nivel."' id='id_sobe_imagem'
					type='file'
					onchange='
						var resposta=``;
						var nome_arquivo=this.value.replace(/^.*[\\\/]/, resposta);
						var ide_campo_nome_foto=`id_campo_nome_photo_filename`;
						document.getElementById(ide_campo_nome_foto).setAttribute(`data-alterado`,`alterado`);
						document.getElementById(ide_campo_nome_foto).value=nome_arquivo;
						if (this.files && this.files[0]) {
							var ide=`photo_filename_img`;
							var extensao=(files[0].name.split(`.`).pop().toLowerCase());
							if (extensao.indexOf(`pdf`)<0) {document.getElementById(ide).src=window.URL.createObjectURL(this.files[0]);}
							var fd= new FormData();
							var faili=`fileToUpload`;
							fd.append(faili,this.files[0],nome_arquivo);
							var xhr = new XMLHttpRequest();
							var pousti=`POST`;
							var nome_faili=`grava_imagem.php`;
							xhr.open(pousti, nome_faili);
							xhr.onloadend= function(e) 
									{
									 resposta=xhr.responseText; 
									 var thumb=`pdf_thumb.php?pdf=`+document.getElementById(`id_diretorio_photos`).innerText+files[0].name;
									 if (extensao.indexOf(`pdf`)>-1) {document.getElementById(ide).src=thumb;}
									 alert(resposta+` thumb:`+thumb+` ide:`+ide);
									}
							xhr.send(fd);
							document.getElementById(ide_campo_nome_foto).value=document.getElementById(`id_diretorio_photos`).innerText+nome_arquivo;
						}
						
					'/>		


				<input 
                                        id='id_campo_nome_photo_filename' 
                                        class='inserivel'
                                        type='text' 
                                        value=''
                                        data-alterado='nao'
                                        data-tabela='".$table."'
                                        data-campo='".$coluna."'
                                        data-id=''
                                        data-nivel='".$nivel."'
                                />
				
				<div style='border: 1px solid blue; background-color: black; color: white'>Diretório no Servidor: <span id='id_diretorio_photos'>../imagens/</span></div>
			</td>
			";
		}
			
			
			
			
	} 
	else 
		{
// o input-div abaixo mimetiza um dropdown. Ele é equivalente ao dropdown de outra parte do programa, usado para atualizar dados, na tabela completa. Esse aqui é mais simples porque o dado ainda não existe, então não temos um id para a chave primária, por exemplo.

if (strpos($coluna,$campo_externo_para_search)===false){


		echo "
<tr>
<td>
<input type='button' data-table='".$table."' value='Insere ".'"'.$nome_tabela_de_ligacao.'"'."' data-nivel='".$nivel."' onclick='insere_registro(".'"'.$table.'"'.",".$nivel.",".'"insercao_nton_'.$nome_tabela_de_ligacao.'_'.$id_externo_para_search.'"'.",".'"'.$campo_externo_para_search.'"'.",".'"'.$id_externo_para_search.'"'.")'/>
<div id='form_".$nome_tabela_de_ligacao."_".$id_externo_para_search."' style='backgroundcolor: blue'><input type='button' value='adiciona ".$rtn."' data-nivel='".$nivel."' onclick='painel_insercao(\"form_".$nome_tabela_de_ligacao."_".$id_externo_para_search."\",\"".$rtn."\")'/></div>
</td>
</tr>
<tr>
<td>
<div class='dropdown'>
  <input type='text' 
	id='drop_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search."' 
	class='dropbtn' 
	onfocusout='document.getElementById(".'"'."lista_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search.'"'.").setAttribute(".'"'."data-keyup".'"'.",".'"'."inativo".'"'.");document.getElementById(".'"'."drop_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search.'"'.").setAttribute(".'"'."data-selecionado".'"'.",".'"'."-1".'"'."); document.getElementById(".'"'."drop_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search.'"'.").setAttribute(".'"'."data-n-itens".'"'.",".'"'."0".'"'.");' 
        data-drop='lista_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search."'
        data-momento='insercao'
	data-id='-1'
        data-max-itens='100'
	data-banco='".$banco_de_dados."' 
	data-tabela='".$tabela."'
	data-campo='".$coluna."' 
	data-fkid='-1' 
        data-default=''
	data-fk-banco='".$banco_de_dados."' 
	data-fk-tabela='".$rtn."' 
	data-fk-id='".$rcn."'
	data-selecionado='-1'
        data-event-blur='NAO'
	data-event-focus='NAO'
	data-event-keyup='NAO'
	data-n-itens='0/'
	data-nivel='".$nivel."' 
        autocomplete='off'
  />
  <div id='lista_".$coluna."_".$campo_externo_para_search."_".$id_externo_para_search."'  class='dropdown-content'  data-keyup='inativo'>
  </div>
</div>
</td>
</tr>
";



//$nome_a_partir_do_rcn=str_replace("id_chave_","nome_",$rcn); // essa solucao assume que a tabela externa tem um campo nome_. Isso pode não ser verdade. O certo seria buscar o campo nome e se não tivesse, chamar um erro
//
//    	$tabela_independente=$tabela_externa2;
//    	$campo_independente=$campo_externo2_tabela_de_ligacao;
//    	$chave_externa_independente=campo_id_tabela_externa2;
//    	$nome_externa_independente=campo_nome_tabela_externa2;

		} // fim do if que testa se o campo é $campo_externo_de_search
		// else {echo "<td>É campo da tabela externa".$campo_externo_de_search."</td>";} Esse else é para testar se o strpos está funcionando
		};
} // fim do if que testa se são campos especiais
 echo"<tr>
         <td colspan=3 style='text-align: left'></td>
     </tr>

";
  
    } // fim do while 
} // fim do if que testa se o query resultou em linhas

$sql_schema="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '".$table."' AND CONSTRAINT_NAME = 'PRIMARY' limit 1;";// vamos pegar o nome da chave primária

$result_schema=$conn->query("$sql_schema");
if ($result_schema->num_rows>0) {
  while($row_schema=$result_schema->fetch_assoc())
    {
	$chave_primaria=$row_schema["COLUMN_NAME"];
	}
} else {echo "<tr><td>Não tem dados</td></tr>";} 



// código abaixo gera a lista de campos na tabela remota que estão associados a um campo na <tabela> que está sendo editada.
$sql_externo="select a.".$nome_externa_independente.", b.".$chave_primaria." from ".$tabela_independente." as a, ".$table." as b  where a.".$chave_externa_independente."=b.".$campo_independente." and b.".$campo_externo_para_search."='".$id_externo_para_search."';";

echo "<tr><td><div class='classe_contem_nomes'  id='contem_nomes_".$nome_tabela_de_ligacao."_".$id_externo_para_search."'>
".$nome_externa_independente." 
<table class='tabela_contem_nomes'>
<tr>
"; //XXX

$result_externo=$conn->query("$sql_externo");
$quantidade_de_resultados=$result_externo->num_rows;

if ($quantidade_de_resultados>30){  // aqui deveria entrar o $quantidade_maxima_de_linhas_no_NtoN
	echo "<tr><td>Mais do 30 itens.<br> Use a tabela própria.</td></tr>";
}
else 
{
	if ($quantidade_de_resultados>0) {
	  while($row_externo=$result_externo->fetch_assoc())
	    {
		$nome_rcn=$row_externo[$nome_externa_independente];
		$id_chave=$row_externo[$chave_primaria];
		echo "<tr><td class='tabela_contem_nomes'>".$nome_rcn."</td><td class='tabela_contem_nomes'><input type='button' value='apaga' onclick='apaga_registro_com_tabela(`".$nome_tabela_de_ligacao."`,".$id_chave.");'></td></tr>";
		} 
	} else {echo "<tr><td>Não tem dados. ".$sql_externo." </td></tr>";}
}
echo "
</tr>
</table>
</div></td></tr>";


echo "
</table>
</div>
</td>
";

} // fim do while que percorre a tabela tabelas_de_ligacao
} // fim do if que verifica se o result foi > 0
echo "</tr><table><div>";

?>

