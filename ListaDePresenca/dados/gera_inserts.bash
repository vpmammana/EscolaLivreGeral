cat alunos_aluizio.csv | awk 'BEGIN{FS=";";}{gsub(/ /,"_",$6);print "insert ignore into turmas (nome_turma) values (\"Turma_"$6"\"); ";}'
