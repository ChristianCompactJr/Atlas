    <?php
    SessionController::VerificarCSRFToken();
    try
    {
        $dao = new UsuarioDAO();  
        $dao->ApagarUsuario($_POST['id']);
        JSONResponder::ResponderSucesso("Usuário apagado com sucesso.", true, true);
        
    }
    catch(Exception $e)
    {
        JSONResponder::ResponderFalha($e->getMessage(), true, true);
    }