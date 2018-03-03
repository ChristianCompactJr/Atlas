<?php
SessionController::VerificarCSRFToken();
    header('Content-Type: application/json');
    if(SessionController::IsAdmin() || (isset($_POST['idusuario']) && $_POST['idusuario'] == SessionController::GetUsuario()->getId()))
    {
       $dao = new ProjetoDAO();
       $projetos = $dao->GetProjetos();
       $retorno = array();
       $udao = new UsuarioDAO();
       foreach($projetos as $projeto)
       {
           $master = $udao->GetUsuario($projeto->getScrumMaster());
           $devs = $projeto->getEquipe();
           $devsJson = array();
           foreach($devs as $dev)
           {
               $devsJson[] = $dev->ToJSONPreparedArray();
           }
           $masterJson = $master->ToJSONPreparedArray();
           $retorno[] = array('id' => $projeto->getId(),'nome' => $projeto->getNome(),'master' => $masterJson, 'devs' => $devsJson, 'inicio' => $projeto->getInicioFormatted(), 'prazo' => $projeto->getPrazoFormatted(), 'cliente' => $projeto->getCliente(), 'observacoes' => $projeto->getObservacoes(), 'estagio' => $projeto->getEstagio(), 'porcentual' => $projeto->getPorcentual(), 'farol' => $projeto->getFarol());
       }
       
       echo json_encode($retorno, JSON_FORCE_OBJECT);
       
    }

?>
