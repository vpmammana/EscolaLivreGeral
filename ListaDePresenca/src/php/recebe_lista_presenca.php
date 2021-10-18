<?php    
    $lista_presenca_string = file_get_contents('php://input');
    $lista_presenca = json_decode($lista_presenca_string);    

	include "identifica.php";
    $base = "escolax";
    
	$fp = fopen('data.txt','w');
	fwrite($fp, "String");
	fwrite($fp, $lista_presenca_string);
	$conn = new mysqli("localhost", $username, $pass, $base);

    $sql = "insert into presencas(data_tempo, id_registrado, id_evento) values (?,?,1);";
    $stmt = $conn->prepare($sql);

    foreach($lista_presenca as $i => $registro_presenca) {
        
        $lista_conteudo = explode("-", $registro_presenca->conteudo);
        $nome = $lista_conteudo[0];
        $numero = (int)$lista_conteudo[1];
        error_log($registro_presenca->data); 
		$replaced =  str_replace("/","-",$registro_presenca->data);
		fwrite($fp, $replaced);
        if (!$stmt->bind_param('si', $registro_presenca->data, $numero)){
            echo "Não foi possível adicionar parâmetro data: (" . $stmt->errno . ") " . $stmt->error;
        }        

        if (!$stmt->execute()) {
            echo "Não foi possível executar SQL: (" . $stmt->errno . ") " . $stmt->error;
        }
    }
    echo "{'resultado':'true'}";
	fclose($fp);
?>
