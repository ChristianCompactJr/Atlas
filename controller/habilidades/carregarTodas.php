<?php
SessionController::VerificarCSRFToken();
    header('Content-Type: application/json');
    if(SessionController::IsAdmin())
    {
        $dao = new HabilidadeDAO();
        $habilidades = $dao->GetHabilidades();
        $resposta = array();
        foreach ($habilidades as $habilidade)
        {
            $resposta[] = array('id' => $habilidade->getId(), 'nome' => $habilidade->getNome());
        }
        
         echo json_encode($resposta, JSON_FORCE_OBJECT);
    }
    
   



?>
