import { CSVLondrina } from "./modulos/csv/londrina/CSVLondrina.js";

window.geradorQRCode = null;
window.listaAlunos = [];

window.onload = () => {
    gerarQRCode("1"); 

    document.querySelector("#btnGerarQRCode").addEventListener("click", () =>{
        
        let textoQRCode = `${document.querySelector("#turma").value}-${document.querySelector("#numero").value}-${document.querySelector("#nome").value}`;
        gerarQRCode(textoQRCode);                
    });

    let inputArquivoCSV = document.querySelector("#arquivoCSV");

    document.querySelector("#btnEnviarCSV").addEventListener("click", ()=>{
        inputArquivoCSV.click();
    });

    document.querySelector("#btnGerarPDF").addEventListener("click", ()=>{
        gerarPDF();
    });
    

    inputArquivoCSV.addEventListener("change", ()=>{
        const reader = new FileReader();
        reader.onload = evento => { 
            let conteudoBase64 = evento.target.result.split(",")[1];
            let conteudoUnicode = b64DecodeUnicode(conteudoBase64);
            if (confirm("Deseja processar CSV específico para Londrina?")){
                CSVLondrina.processarCSV(conteudoUnicode);
            }else{                
                processarCSVListaAlunos(conteudoUnicode);
            }            
        };
        reader.readAsDataURL(inputArquivoCSV.files[0]);
    }); 
}

function gerarQRCode(textoQRCode){
    let textoSemAcentos = retira_acentos(textoQRCode);
    let containerQRCode = document.querySelector("#containerQRCode");
    if (!window.geradorQRCode){
        window.geradorQRCode = new QRCode(containerQRCode, textoSemAcentos);
    }else{
        window.geradorQRCode.clear();
        window.geradorQRCode.makeCode(textoSemAcentos);
    }
}

function processarCSVListaAlunos (csv){

    window.listaAlunos = [];
    csv = csv.replace(/\r/g,"")
    let linhas = csv.split("\n");
    for (let iLinha = 1; iLinha < linhas.length; iLinha++){
        let campos = linhas[iLinha].split(";");
        
        if (campos[0] && campos[1]){

            console.log (`Turma:${campos[0]} --- Número:${campos[1]} --- Nome:${campos[2]}`);                    
            window.listaAlunos.push({turma:campos[0], numero:campos[1], nome:campos[2]});
        }
    }    
}

function b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}  

function gerarPDF(){
    const doc = new window.jspdf.jsPDF();            

    const linhas_por_pagina = 3;
    const colunas_por_pagina = 2;

    let linhaAtual = 1;
    let colunaAtual = 1;

    let x = 10;
    let y = 15;

    for (let iAluno in window.listaAlunos){

        let aluno = window.listaAlunos[iAluno];

        if (linhaAtual == 1){            
            y = 15;
        }
        switch (colunaAtual){
            case 1: 
                x = 15;
                break;
            case 2:
                x = 120;
                break;            
        }
        
        let textoQRCode = `${aluno.turma}-${aluno.numero}-${aluno.nome}`;
        gerarQRCode(textoQRCode);

        let imgQRCode = containerQRCode.querySelector("img");
        doc.addImage(imgQRCode.src.split(",")[1], "JPG", x, y);

        doc.text (`${aluno.numero.toString()}-${aluno.nome.split(" ")[0]}`, x, y-5);                        
        doc.text (`Turma: ${aluno.turma}`, x, y+74);

        linhaAtual++;
        y += 95;

        if (linhaAtual > linhas_por_pagina){
            
            linhaAtual = 1;
            colunaAtual++;

            if (colunaAtual > colunas_por_pagina){
                colunaAtual = 1;                

                if (iAluno < (window.listaAlunos.length-1)){
                    doc.addPage();
                }
            }                        
        }                
    }

    let nomeArquivoPDF = `${dataEmString(new Date())}_qrcode-alunos.pdf`;            
    doc.save(nomeArquivoPDF);
}

function dataEmString(data){    
    let dia = String(data.getDate()).padStart(2, '0');
    let mes = String(data.getMonth() + 1).padStart(2, '0'); //January is 0!
    let ano = data.getFullYear();
    let hora = String(data.getHours()).padStart(2, '0');
    let minuto = String(data.getMinutes()).padStart(2, '0');
    return `${ano}-${mes}-${dia}_${hora}-${minuto}`;
}

//Copiado de https://pt.stackoverflow.com/questions/237762/remover-acentos-javascript
function retira_acentos(str) {
    let com_acento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝŔÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿŕ";

    let sem_acento = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYRsBaaaaaaaceeeeiiiionoooooouuuuybyr";

    let novastr="";
    for(let i=0; i<str.length; i++) {
        let troca=false;
        for (let a=0; a<com_acento.length; a++) {
            if (str.substr(i,1)==com_acento.substr(a,1)) {
                novastr+=sem_acento.substr(a,1);
                troca=true;
                break;
            }
        }
        if (troca==false) {
            novastr+=str.substr(i,1);
        }
    }
    return novastr;
}       