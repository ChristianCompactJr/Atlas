<?php
    SessionController::VerificarCSRFToken();
    $dao = new UsuarioDAO();
    if($_POST['novasenha'] !== $_POST['confsenha'])
    {
        JSONResponder::ResponderFalha("A confirmação de senha não é igual a nova senha.", true, true);
    }
    
    try {
       $dao->AtualizarSenhaEsqueci($_POST['id'], $_POST['novasenha'], $_POST['chave']);
       JSONResponder::ResponderSucesso("Senha redefinida com sucesso", true, true);
        
    } 
    catch (Exception $ex) {
       JSONResponder::ResponderFalha($ex->getMessage(), true, true);
    }
    
?>
