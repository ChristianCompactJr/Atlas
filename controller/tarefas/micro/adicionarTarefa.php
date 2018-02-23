<?php

    header('Content-Type: application/json');
    $pdao = new ProjetoDAO();
    $projeto = $pdao->GetProjeto($_POST['idprojeto']);
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $dao = new TarefaMicroDAO();
            $dao->AdicionarTarefa($_POST['idmacro'], $_POST['nome'], $_POST['descricao'], $_POST['tempo'], $_POST['links']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Tarefa micro cadastrada com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }   

?>
