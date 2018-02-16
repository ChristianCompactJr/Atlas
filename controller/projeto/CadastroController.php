<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin())
    {
        if(!isset($_POST['dev']) || !isset($_POST['master']))
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => 'O Projeto deve ter um SCRUM Master e pelo mesmo um equipe SCRUM');
        }
        else
        {
            try
            {
                $dao = new ProjetoDAO();
                $dao->CriarProjeto($_POST['nome'], $_POST['cliente'], $_POST['master'], $_POST['dev'], $_POST['inicio'], $_POST['prazo'], $_POST['backlog'], $_POST['obs'], $_POST['estagio']);
                $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Projeto cadastrado com sucesso');
            }
            catch (Exception $e)
            {
                $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
            }
        }
        
        
        echo json_encode($resposta, JSON_FORCE_OBJECT);
     
    }

?>

