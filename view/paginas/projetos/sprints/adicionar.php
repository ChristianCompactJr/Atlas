<?php



if(!SessionController::TemSessao())
{
    header('locationn ../index.php');
}
    

$dao = new ProjetoDAO();
$nivelAcesso = 0;
try
{
    
    
    
    $projeto = $dao->GetProjeto($_GET['idprojeto']);
    $master = $projeto->getScrumMaster();
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $master->getId())
    {
        $nivelAcesso = 2;
    }
    else
    {
        header("location: ../index.php");
    }

    $tdao = new TarefaMacroDAO();
    $tarefasMacro = $tdao->getTarefasMacro($projeto->getID());
    $arrayMacro = array();
    foreach($tarefasMacro as $tm)
    {
        $arrayMacro[] = $tm->toArray();
    }
    
}

catch(Exception $e)
{
    header("location: ../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Projeto - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>  
            
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               

        
        <div class ="container">
            <h1>Adicionar Sprints - <?php echo $projeto->getNome();?></h1>
            
        
        <?php Carregador::CarregarViewFooter(); ?>
       
        
        <script>
            var tarefas = <?php echo json_encode($arrayMacro, JSON_FORCE_OBJECT); ?>;
        </script>
                    
    </body>
</html>