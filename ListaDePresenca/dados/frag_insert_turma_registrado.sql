insert into turmas_registrados (id_registrado, id_turma, id_tipo_vinculo) values ((select id_chave_registrado from registrados where nome_registrado="nao_aplica"),(select id_chave_turma from turmas where nome_turma="nao_aplica"),(select id_chave_tipo_vinculo from tipos_vinculos where nome_tipo_vinculo="nao_aplica"));

