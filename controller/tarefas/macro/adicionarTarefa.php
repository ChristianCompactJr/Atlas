<?php
    $pdao = new ProjetoDAO();
    $projeto = $pdao->GetProjeto($_POST['idprojeto']);
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $dao = new TarefaMacroDAO();
            $dao->AdicionarTarefa($projeto->getId(), $_POST['nome'], $_POST['descricao']);
            JSONResponder::ResponderSucesso("Tarefa macro cadastrada com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
     
    }   

?>
