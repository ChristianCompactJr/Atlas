<?php 
    header('Content-Type: application/json');
    $dao = new UsuarioDAO();
    try {
        $usuario = $dao->Autenticar($_POST['email'], $_POST['senha']);
        SessionController::CriarSessao($usuario);
        
        if(isset($_POST['lembrar']))
        {
            SessionController::CriarToken();
        }
       $resposta = array('tipo' => 'sucesso', 'mensagem' => '');
        
        
    } 
    catch (Exception $ex) {
       $resposta = array('tipo' => 'erro', 'mensagem' => $ex->getMessage());
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);
    
    

?>