SELECT vinho.vinho, vinho.tipo, casta.casta, casta.cor, produtor.produtor, subregiao.subregiao, regiao.regiao, pais.pais
FROM vinho
INNER JOIN vinho_casta ON vinho_casta.id_vinho = vinho.id_vinho
INNER JOIN casta ON vinho_casta.id_casta = casta.id_casta
INNER JOIN produtor_subregiao ON vinho.id_produtor_regiao = produtor_subregiao.id_produtor_subregiao
INNER JOIN produtor ON produtor_subregiao.id_produtor = produtor.id_produtor
INNER JOIN subregiao ON produtor_subregiao.id_subregiao = subregiao.id_subregiao
INNER JOIN regiao ON subregiao.id_regiao = regiao.id_regiao
INNER JOIN pais ON regiao.id_pais = pais.id_pais