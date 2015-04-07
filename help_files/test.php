<?php
require_once __DIR__ . '/vendor/autoload.php';
error_reporting(E_ALL);
session_start();

// $id = 178;
// $result = '<select name="Regiao">';
// $result .= classes\Regiao::listar(array('id_regiao','regiao'), array("id_pais"=>$id), 'select');
// $result .= '</option>';
// printf() $result;

// $username, $password, $nome = null, $email = null, $permissoes = null
$user = new classes\User('User Teste', 'password', 'Nome Teste', 'teste@teste.com', 'F');
$user->register();

// $pais, $codigo
$pais = new classes\Pais('Portugal', 'PT');
$pais->insert();

// precisa pais | $regiao, $descricao, $pais, $codigo = ''
$regiao = new classes\Regiao('Teste', 'Descricao Regiao Teste', $pais->getPais(), $pais->getCodigo());
$regiao->insert();

// precisa regiao | $subregiao, $descricao, $regiao, $descricao_regiao, $pais, $codigo
$subregiao = new classes\SubRegiao('Sub Teste', 'Descricao Sub Regiao Teste', $regiao->getRegiao(), $regiao->getDescricao(), $regiao->getPais(), $regiao->getCodigo());
$subregiao->insert();

// $produtor, $informacoes, $website
$produtor = new classes\Produtor('Produtor Teste', 'Informacoes Produtor Teste', 'www.produtor_teste.pt');
$produtor->insert();

// precisa produtor subregiao | $id_produtor, $id_subregiao
$produtor_subregiao = new classes\ProdutorSubRegiao($produtor->getIdProdutor(), $subregiao->getIdsubregiao());
$produtor_subregiao->insert();

// $casta, $caracteristicas, $cor, $sinonimo
$casta = new classes\Casta('Casta Teste', 'Caracteristicas Teste', 'Cor Teste', 'Sinonimo Teste');
$casta->insert();

// precisa produtor_subregiao | $vinho, $ano, $tipo, $alcool, $volume, $caracteristicas, $premios, $front_label, $back_label, $valor_aprox, $stamptime, $vinificacao, $servico_consumo, $gastronomia, $public, $id_produtor_regiao
$vinho = new classes\Vinho('Vinho Teste', '1990', 'Tipo Teste', 'Alcool Teste', 'Volume Teste', 'Caracteristicas Teste', 'Premio Teste', 'Front Label Teste', 'Back Label Teste', 'Valor Aprox Teste', '15:00', 'Vinificacao Teste', 'Servico Consumo Teste', 'Gastronomia Teste', 't', $produtor_subregiao->getIdProdutorSubRegiao());
$vinho->insert();

// precisa vinho casta | $percentagem, $id_vinho, $id_casta
$vinhocasta = new classes\VinhoCasta('Percentagem Teste', $vinho->getIdVinho(), $casta->getIdCasta());
$vinhocasta->insert();

// -- GOOD

// $wishlist, $public, $id_user
$wishlist = new classes\WishList('WishList Teste', 'f', $user->getIdUser());
$wishlist->insert();

// precisa vinho | $id_wishlist, $id_vinho
$wishvinho = new classes\WishVinho($wishlist->getIdWishList(), $vinho->getIdVinho());
$wishvinho->insert();

// $evento, $descricao, $stamptime, $start_date, $end_date, $id_user
$evento = new classes\Evento('Event Teste', 'Descricao Teste', '15:00', '01/01/2013', '02/02/2014', $user->getIdUser());
$evento->insert();

// $noticia, $descricao, $stamptime, $id_user
$noticia = new classes\Noticia('Noticia Teste', 'Descricao Teste', '20:00', $user->getIdUser());
$noticia->insert();

// precisa vinho | $comentario, $rating, $id_vinho, $id_user
$comentario = new classes\Comentario('Comentario Teste', '3', $vinho->getIdVinho(), $user->getIdUser());
$comentario->insert();

?>
