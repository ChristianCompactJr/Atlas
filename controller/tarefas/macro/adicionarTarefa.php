<?php

    header('Content-Type: application/json');
    $pdao = new ProjetoDAO();
    $projeto = $pdao->GetProjeto($_POST['idprojeto']);
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $dao = new TarefaMacroDAO();
            $dao->AdicionarTarefa($projeto->getId(), $_POST['nome'], $_POST['descricao']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Tarefa macro cadastrada com sucesso');
        }
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }   

?>
