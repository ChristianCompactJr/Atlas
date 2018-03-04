<?php
    if(SessionController::IsAdmin())
    {
        if(!isset($_POST['dev']) || !isset($_POST['master']))
        {
            JSONResponder::ResponderAviso("O projeto deve ter um SCRUM Master e pelo menos um equipe SCRUM.", true, true);
        }
        else
        {
            try
            {
                $dao = new ProjetoDAO();
                $dao->CriarProjeto($_POST['nome'], $_POST['cliente'], $_POST['master'], $_POST['dev'], $_POST['inicio'], $_POST['prazo'], $_POST['obs'], $_POST['estagio']);
                JSONResponder::ResponderSucesso("Projeto cadastrado com sucesso", true, true);
            }
            catch (Exception $e)
            {
                JSONResponder::ResponderFalha($e->getMessage(), true, true);
            }
        }    
     
    }

?>

