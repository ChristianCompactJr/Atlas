<?php
    header('Content-Type: application/json');
    if(SessionController::IsAdmin())
    {
        try 
        {
            $dao = new HabilidadeDAO;
            $dao->CriarHabilidade($_POST['nome']);
            $resposta = array('tipo' => 'sucesso', 'mensagem' => 'Habilidade criada com sucesso');
        } 
        catch (Exception $e)
        {
            $resposta = array('tipo' => 'erro', 'mensagem' => $e->getMessage());
        }       
    }
    echo json_encode($resposta, JSON_FORCE_OBJECT);
?>
