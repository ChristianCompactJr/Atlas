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
        $devs = $dao->GetDevsProjeto($projeto->getId());
        
        foreach($devs as $dev)
        {
            if($dev->getId() == SessionController::GetUsuario()->getId())
            {
                $nivelAcesso = 1;
                break;
            }
        }
        
    }
    
    
    if(SessionController::GetUsuario()->getId() != $projeto->getScrumMaster() && !SessionController::IsAdmin())
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
            <h1>Sprints - <?php echo $projeto->getNome();?></h1>
            <div class ="table-responsive">
                <table class = "table table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data de Início</th>
                            <th>Prazo</th>
                            <th>Andamento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <?php if($nivelAcesso >= 2)
            { ?>
                <div class="col-md-offset-3 col-md-6 text-center">                         
                    <a href ="adicionar?idprojeto=?<?php echo $projeto->getId(); ?>" class="btn btn-primary btn-lg btn-block">Adicionar Sprint</a>
                </div>
            <?php } ?>
            
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
    </body>
</html>
