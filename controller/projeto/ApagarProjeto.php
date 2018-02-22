<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin())
    {
        try
        {
            $dao = new ProjetoDAO();
            $dao->ApagarProjeto($_POST['idprojeto']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Projeto Removido com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }

?>

