cat alunos_aluizio.csv | awk 'BEGIN{FS=";";}{print "insert into registrados (nome_registrado, RA) values (\""$7"\",\""$2"\");";}'
