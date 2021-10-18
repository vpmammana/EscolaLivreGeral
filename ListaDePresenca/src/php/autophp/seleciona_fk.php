
<?php

// 2021-04-21 -> esse código teve origem no insercao.php. Diferentemente daquele, o objetivo nao é inserir um novo dado, mas permitir a selecao de um id presente num campo externo. Para isso é preciso retirar todo o procedimento de insere_registro.php, porque não será feita insercao ou atualização, mas apenas o retorno do id do campo selecionado.

include "identifica.php";

if(isset($_GET["banco"])){
  $banco_de_dados = $_GET["banco"];
}

if(isset($_GET["tabela"])){
  $tabela = $_GET["tabela"];
}

if(isset($_GET["nivel"])){  // passa o nivel hierarquico daquela insercao.
  $nivel = $_GET["nivel"];
}

if(isset($_GET["campo_de_escolha"])){  // passa o campo que será usado para fazer a selecao. Só este campo da tabela será mostrado, ignorando os demais. 
  $campo_de_escolha = $_GET["campo_de_escolha"];
}


$cor_letra="black";
if ($nivel=="1") { $cor_background="lightgreen";}
if ($nivel=="2") { $cor_background="blue"; $cor_letra="white";}
if ($nivel=="3") { $cor_background="green";}
if ($nivel=="4") { $cor_background="blue"; $cor_letra="yellow";}
if ($nivel=="5") { $cor_background="orange";}


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
<div id='div_de_insercao_no_topo' style='background-color: ".$cor_background."; color: ".$cor_letra."'>
<h2>Selecione o curador: ('".$tabela."')</h2>
<table style='border: 1px solid ".$cor_letra."; border-collapse: collapse; padding:5px; text-align: left; width: 100%; color: ".$cor_letra."'>
";

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


if (//		(strpos($coluna,'id_chave')===false) &&
	//	(strpos($coluna,'time_stamp')===false) &&
	//	(strpos($coluna,'usuario')===false) 
		(strpos($coluna,$campo_de_escolha)===0) 
)
{
	if ($type=='date') {$default_input='0000-01-01';} else {$default_input='';}


        if (
		($rtn=='') 
           )
         {

		if (strpos($coluna,'photo_filename_')===false){

		echo "                  <td><input 
							type='text' 
							value='".$default_input."'
							class='inserivel' 
							data-tabela='".$table."' 
							data-alterado='nao' 
							data-campo='".$coluna."'
							data-nivel='".$nivel."' 
							size='".$visible_length."' 
							maxlength='".$length."' />
					</td>
			              </tr>";
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
		echo "

<td width='70%'>
<div class='dropdown'>
  <input type='text' 
	id='drop_".$coluna."_' 
	class='dropbtn' 
	onfocusout='document.getElementById(".'"'."lista_".$coluna."_".'"'.").setAttribute(".'"'."data-keyup".'"'.",".'"'."inativo".'"'.");document.getElementById(".'"'."drop_".$coluna."_".'"'.").setAttribute(".'"'."data-selecionado".'"'.",".'"'."-1".'"'."); document.getElementById(".'"'."drop_".$coluna."_".'"'.").setAttribute(".'"'."data-n-itens".'"'.",".'"'."0".'"'.");' 
        data-drop='lista_".$coluna."_'
        data-momento='insercao'
	data-id='-1'
        data-max-itens='100'
	data-banco='".$banco_de_dados."' 
	size='70'
	placeholder='click e aperte seta para baixo OU digite o nome que quer buscar'
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

  <div id='lista_".$coluna."_' class='dropdown-content'  data-keyup='inativo'>
  </div>
</div>
</td>";

		};
} // fim do if que testa se são campos especiais
   
    } // fim do while 
} // fim do if que testa se o query resultou em linhas
echo"
         <td colspan=3 style='text-align: left'><input type='button' data-table='".$table."' value='Entra na Plataforma' data-nivel='".$nivel."'       onclick='carrega_janela_principal(document.getElementById(`drop_`+`".$campo_de_escolha."`+`_`).value)'></td>
     </tr>
</table></div>

" 
?>

