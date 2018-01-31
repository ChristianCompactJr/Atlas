<?php
    header('Content-Type: application/json');
    try {
        EnviadorEmail::EnviarEmail('Suporte: '.$_POST['assunto'], $_POST['mensagem'], $_POST['email']);
       $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Mensagem enviada com sucesso'); 
    } 
    catch (Exception $ex) {
        $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
    }
     echo json_encode($resposta, JSON_FORCE_OBJECT);
     
?>