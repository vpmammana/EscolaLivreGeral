<?php    
    $lista_presenca_string = file_get_contents('php://input');
    $lista_presenca = json_decode($lista_presenca_string);    
    
    $usuario = "root";
    //TODO: puxar senha da varíavel de ambiente    
    $senha = "";
    $base = "escolax";

    $conn = new mysqli("localhost", $usuario, $senha, $base);

    $sql = "insert into presenca(data, nome, numero, direcao) values (?,?,?,0)";
    $stmt = $conn->prepare($sql);

    foreach($lista_presenca as $i => $registro_presenca) {
        
        $lista_conteudo = explode("-", $registro_presenca->conteudo);
        $nome = $lista_conteudo[0];
        $numero = (int)$lista_conteudo[1];
        
        if (!$stmt->bind_param('ssi', $registro_presenca->data, $nome, $numero)){
            echo "Não foi possível adicionar parâmetro data: (" . $stmt->errno . ") " . $stmt->error;
        }        

        if (!$stmt->execute()) {
            echo "Não foi possível executar SQL: (" . $stmt->errno . ") " . $stmt->error;
        }
    }
    echo "{'resultado':'true'}";
?>