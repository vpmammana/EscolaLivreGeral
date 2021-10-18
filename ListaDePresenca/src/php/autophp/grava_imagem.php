

<?php
// Criado a partir de padrão do W3C
$target_dir = getcwd()."/../imagens/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Verifica se a imagem é real ou fake... 
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "O arquivo contém uma imagem - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "O arquivo não contém uma imagem.";
        $uploadOk = 0;
    }
}
// Verifica se o arquivo existe
if (file_exists($target_file)) {
    echo "Desculpe, mas este arquivo já existe. Troque o nome antes de subir.";
    $uploadOk = 0;
}
// Verifica o tamanho do arquivo
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Desculpe, mas seu arquivo é muito grande!.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf" 
 
&& $imageFileType != "gif" ) {
    echo "Apenas arquivos JPG, JPEG, PNG & GIF são permitidos.";
    $uploadOk = 0;
}
// verifica se uploadok está 0 por um erro
if ($uploadOk == 0) {
    echo "Desculpe, mas não foi possível subir seu arquivo.";
// Se tudo estiver ok, tente subir o arquivo (upload)
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Legal! O arquivo ". basename( $_FILES["fileToUpload"]["name"]). " foi uploaded.";
    } else {
        echo "Desculpe, houve um erro no processo de upload do seu arquivo". basename( $_FILES["fileToUpload"]["name"]);
    }
}
?>

