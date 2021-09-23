Para gerar o Banco de dados é preciso criar o banco de dados no MySQL.

O Banco de dados precisa se chamar escolax. 

O Administrador de Banco de Dados precisa criar um usuario e uma senha para esse usuario.

Havendo um banco de dados chamado "escolax" no servidor de banco de dados, é preciso criar a estrutura do banco de dados usando o arquivo que está no diretório "dados" do repositório. 

O comando para criar a estrutura do banco de dados eh:

sudo mysql -u root -pSENHA escolax < gera_escola.sql
