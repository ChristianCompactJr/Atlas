<?php
    $pdao = new ProjetoDAO();
    $projeto = $pdao->GetProjeto($_POST['idprojeto']);
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getID() == $projeto->getScrumMaster())
    {
        try
        {
            $dao = new TarefaMicroDAO();
            $dao->AdicionarTarefa($_POST['idmacro'], $_POST['nome'], $_POST['descricao'],$_POST['observacoes'], $_POST['links'], $_POST['prioridade'], $_POST['estimativa'], $_POST['estado']);
            JSONResponder::ResponderSucesso("Tarefa micro cadastrada  com sucesso", true, true);
        }
        catch (Exception $e)
        {
            JSONResponder::ResponderFalha($e->getMessage(), true, true);
        }
    }   

?>
