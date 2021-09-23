export class ServiceUtils{



    static fetchJSON(url){
		console.log(url);
        return fetch(
            url,
            {
                method: "GET",
                headers:{
                    "Accept":"application/json",
                    "Content-Type": "application/json"
                }
            })
        .then(resposta => {
        	if (resposta.ok) {
        		return resposta.json();
        	} else {
        		resposta.json().then(data => {
            		console.table(data);
            		alert(`${data.message} Erros: ${data.errors}`)
            	});
        		throw new Error("Erro na requisição!");
        	}
        	/*
            if (resposta.ok){
                return resposta.json();
            }else{
                if (resposta.status == 403){
                    alert(`Permissão Negada`);
                }else{
                    alert(`Erro na requisição: ${resposta.status}`);
                }
                throw new Error("Erro na requisição!");
            }*/
        })
        .then (respostaJSON => respostaJSON)
        .catch((erro) =>{
            throw erro;
        });
    }



    static fetchBlob(url){
        return fetch(url)
        .then(resposta => {
            if (resposta.ok){
                return resposta.blob();
            }else{
                throw "Erro na requisição!";
            }
        })
        .then (respostaBlob => respostaBlob)
        .catch((erro) =>{
            throw erro;
        });
    }



    static postJSON(url, objeto){
        return fetch(
            url,
            {
                method: "POST",
                headers:{
                    "Accept":"application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(objeto)
            })
        .then(resposta => {
        	if (resposta.ok) {
        		return resposta.json();
        	} else {
        		resposta.json().then(data => {
            		console.table(data);
            		alert(`${data.message} Erros: ${data.errors}`)
            	});
        		throw new Error("Erro na requisição!");
        	}
        	
            /*if (resposta.ok){
                return resposta.json();
            }else{
                if (resposta.status == 403){
                    alert(`Permissão Negada`);
                }else{
                    alert(`Erro na requisição: ${resposta.status}`);
                }
                throw new Error("Erro na requisição!");
            }*/
        })
        .then (respostaJSON => respostaJSON)
        .catch( error => {
            throw error;
        });
    }


    static deleteJSON(url, objeto){
        return fetch(
            url,
            {
                method: "DELETE",
                headers:{
                    "Accept":"application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(objeto)
            })
        .then(resposta => {
        	if (resposta.ok) {
        		return resposta.json();
        	} else {
        		resposta.json().then(data => {
            		console.table(data);
            		alert(`${data.message} Erros: ${data.errors}`)
            	});
        		throw new Error("Erro na requisição!");
        	}
            /*if (resposta.ok){
                return;
            }else{
                 if (resposta.status == 403){
                    alert(`Permissão Negada`);
                }else{
                    alert(`Erro na requisição: ${resposta.status}`);
                }
                throw new Error("Erro na requisição!");
            }*/
        })
        .catch( error => {
            throw error;
        });
    }


    static putJSON(url, objeto){
        return fetch(
            url,
            {
                method: "PUT",
                headers:{
                    "Accept":"application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(objeto)
            })            
        .then(resposta => {
        	if (resposta.ok) {
        		return resposta.json();
        	} else {
        		resposta.json().then(data => {
            		console.table(data);
            		alert(`${data.message} Erros: ${data.errors}`)
            	});
        		throw new Error("Erro na requisição!");
        	}
        	
        	/*console.table(resposta);
            if (resposta.ok){
                return resposta.json();
            }else{
                if (resposta.status == 403){
                    alert(`Permissão Negada: ${resposta.status} - ${resposta.body.message}`);
                }else{
                    alert(`Erro na requisição: ${resposta.status} - ${resposta.body.message}`);
                }
                throw new Error("Erro na requisição!");
            }*/
        })
        .then (respostaJSON => respostaJSON)
        .catch((erro) =>{
            throw erro;
        });
    }
}