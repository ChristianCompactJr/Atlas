
<?php
SessionController::VerificarCSRFToken();
    $dao = new UsuarioDAO();
    
    try {
        $usuario = $dao->Autenticar($_POST['email'], $_POST['senha']);
        SessionController::CriarSessao($usuario);
        
        if(isset($_POST['lembrar']))
        {
            SessionController::CriarToken();
        }
        JSONResponder::ResponderSucesso("Login realizado com sucesso", true, true);
        
        
    } 
    catch (Exception $ex) {
       JSONResponder::ResponderFalha($ex->getMessage(), true, true);
    }
    
    

?>