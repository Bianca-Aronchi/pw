<?php
require_once 'model/montadora.php';
require_once 'view/montadora.php';

# Removendo 'veiculo' da array $url;
array_shift($url);

function get($consulta){
    $montadora = new Montadora();
    $viewMontadora = new viewMontadora();
    if(count($consulta) == 1 && $consulta[0] == ""){
        $montadora = $montadora->consultar();
        $viewMontadora->exibirMontadora($montadora);
    }
    elseif(count($consulta) == 1){
        $montadora = $montadora->consultarPorId($consulta[0]);
        $viewMontadora->exibirMontadora($montadora);
    }    
    elseif(count($consulta) == 2 && $consulta[0] == "nome"){       
        $montadora = $montadora->consultar($consulta[1]);
        $viewMontadora->exibirMontadora($montadora);
    }
    else{
        $codigo_resposta = 404;
        $erro = [
            'result'=>false,
            'erro'  => 'Erro: 404 - Recurso não encontrado'
        ];
        require_once 'view/erro404.php';
    }   
} 

function post($dados_montadora){
    $montadora = new Montadora();
    $viewMontadora = new viewMontadora();
    $montadora->id                  = $dados_montadora->id;
    $montadora->nome                = $dados_montadora->nome;
    $montadora->logotipo            = $dados_montadora->logotipo;
    $montadora->data_cadastro       = $dados_montadora->data_cadastro;
    $montadora->data_alteracao      = $dados_montadora->data_alteracao;
  
    $viewMontadora->exibirMontadora($montadora->cadastrar());
}

function delete($registro){
    $montadora = new Montadora();
    $viewMontadora = new viewMontadora();
    $result = false;
    $erro = "";
    if($montadora->excluir($registro)){
        $result = true;
    }
    else{
        $erro = $montadora->getErro();
    }
    $viewMontadora->deleteMontadora($result, $erro);

}

function put($registro, $dados_montadora){
    $montadora = new Montadora();
    $viewMontadora = new viewMontadora();
    $montadora->id                  = $dados_montadora->id;
    $montadora->nome                = $dados_montadora->nome;
    $montadora->logotipo            = $dados_montadora->logotipo;
    $montadora->data_cadastro       = $dados_montadora->data_cadastro;
    $montadora->data_alteracao      = $dados_montadora->data_alteracao;
    $viewMontadora->exibirMontadora($montadora->alterar());
}

switch($method){    
    // case "GET":get(@$url[0],@$url[1]);
    case "GET":get($url);
    break;
    case "POST":post($dadosRecebidos);
    break;
    case "PUT":put(@$url[0],$dadosRecebidos);
    break;
    case "DELETE":delete(@$url[0]);
    break;
    default:{
        echo json_encode(["method"=>"ERRO"]);
    }
    break;
}