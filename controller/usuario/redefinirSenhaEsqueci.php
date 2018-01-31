<?php

header('Content-Type: application/json');
    $dao = new UsuarioDAO();
    if($_POST['novasenha'] !== $_POST['confsenha'])
    {
        $resposta = array('tipo' => 'erro', 'mensagem' => "A confirmação de senha não é igual a nova senha.");
        echo json_encode($resposta, JSON_FORCE_OBJECT);
        return;
    }
    
    try {
       $dao->AtualizarSenhaEsqueci($_POST['id'], $_POST['novasenha'], $_POST['chave']);
        $resposta = array('tipo' => 'sucesso', 'mensagem' => "Senha redefinida com sucesso");
        
    } 
    catch (Exception $ex) {
       $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);
    
?>
