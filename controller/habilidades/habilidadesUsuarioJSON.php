<?php
header('Content-Type: application/json');

if(SessionController::IsAdmin())
{
    $dao = new HabilidadeDAO();
    
    $habilidades = $dao->GetHabilidadesUsuario($_POST['id']);
    
    $resposta = array();
    
    foreach($habilidades as $habilidade)
    {
        $resposta[] = array('idhabilidade' => $habilidade->getHabilidade()->getId(), 'nomehabilidade' => $habilidade->getHabilidade()->getNome(), 'valor' => $habilidade->getValor(), 'interesse' => $habilidade->getInteresse());
    }
    
    echo json_encode($resposta, JSON_FORCE_OBJECT);
    
}

?>

