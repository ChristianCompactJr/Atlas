<?php



if(!SessionController::TemSessao())
{
    header('locationn ../../index.php');
}
    

$projetodao = new ProjetoDAO();
$sprintdao = new SprintDAO();
$usuariodao = new UsuarioDAO();
$microdao = new TarefaMicroDAO();
$tarefasprintdao = new TarefaSprintDAO();
$nivelAcesso = 0;
try
{
    
    
    $sprint = $sprintdao->GetSprint($_GET['idsprint']);
    $projeto = $projetodao->GetProjeto($sprint->getProjeto());
    $master = $projeto->getScrumMaster();
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $master)
    {
        $nivelAcesso = 2;
    }
    else
    {
        $devs = $projetodao->GetDevsProjeto($projeto->getId());
        
        foreach($devs as $dev)
        {
            if($dev->getId() == SessionController::GetUsuario()->getId())
            {
                $nivelAcesso = 1;
                break;
            }
        }
        
    }
    
    
    
}



catch(Exception $e)
{
    header("location: ../../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sprints - <?php echo $projeto->getNome();?></title>
        <?php Carregador::CarregarViewHeadMeta(); ?>  
            
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               

        
        <div class ="container">
            
            <h1><?php echo $sprint->getNome();?> - <?php echo $projeto->getNome(); ?></h1>
            
            <div class ="row">
                <p><h3 style = "display:inline">Data de início: </h3><span style = "font-size: 17px"><?php echo $sprint->getData_InicioFormatted(); ?></span></p> 
            </div>
            <div class ="row">
                <p><h3 style = "display:inline">Prazo: </h3><span style = "font-size: 17px"><?php echo $sprint->getPrazoFormatted(); ?></span></p> 
            </div>
            <div class ="row">
                <p><h3 style = "display:inline">Estagio da Sprint: </h3><span style = "font-size: 17px"><?php echo $sprint->getEstagio(); ?></span></p> 
            </div>
            <h3>Tarefas: </h3>
            <div class ="table-responsive">
                <table class = "table table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Estado Tarefa Micro</th>
                            <th>Responsáveis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                           $tarefasSprint = $tarefasprintdao->GetTarefasSprint($sprint->getId());
                           foreach($tarefasSprint as $tarefaSprint)
                           {
                               $tarefamicro = $microdao->getTarefa($tarefaSprint->getMicro());
                               echo '<tr>';
                               echo '<td>'.$tarefamicro->getNome().'</td>';
                               echo '<td>'.$tarefamicro->getEstadoColored().'</td>';
                               echo '<td>';
                               $responsaveis = $tarefaSprint->getResponsaveis();
                               if(count($responsaveis) == 0)
                               {
                                   echo 'Equipe';
                               }
                               else
                               {
                                    foreach($tarefaSprint->getResponsaveis() as $responsavel)
                                   {
                                       echo '<img src = "'.UrlManager::GetPathToView($responsavel->getFoto()).'" class = "img-thumbnail" style = "max-width:40px; margin-right:10px;">'.$responsavel->getNome();
                                   }
                               }
                               
                               
                               echo '</td>';
                               
                               echo '</tr>';
                           }
                                
                        ?>
                    </tbody>
                </table>
            </div>  
            <?php
                if($sprint->getEstagio() == "Revisão")
                { echo ' <h4 class = "text-center">Esta sprint está em revisão</h4>'; } ?>
                    
           
            <?php if($nivelAcesso == 2 && $sprint->getEstagio() == "Desenvolvimento")
            { ?>
                
            
             <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                <button class="btn btn-success btn-lg btn-block" id = "btn-marcar-revisao" data-id-sprint="<?php echo $sprint->getId(); ?>"><i class="fa fa-check"></i> Marcar Sprint para Revisão/Restrospectiva</button>
            </div>
             
            <?php } 
           
            
            else if($sprint->getEstagio() == "Revisão" && $nivelAcesso == 2 ) { ?>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                <a class="btn btn-success btn-lg btn-block" href="<?php echo UrlManager::GetPathToView("projetos/sprints/revisao?idsprint=".$sprint->getId()); ?>"><i class="fa fa-archive"></i> Revisar Sprint</a>
            </div>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                <button class="btn btn-success btn-lg btn-block" id = "btn-marcar-revisao" data-id-sprint="<?php echo $sprint->getId(); ?>"><i class="fa fa-check"></i> Marcar Sprint para Revisão/Restrospectiva</button>
            </div>
            <?php } ?>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                <a href ="<?php echo UrlManager::GetPathToView("projetos/sprints/?idprojeto=".$projeto->getId()); ?>" class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left"></i> Voltar para Sprints</a>
            </div>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px">
                <a href ="<?php echo UrlManager::GetPathToView("projetos/visualizar?id=".$projeto->getId()); ?>" class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left"></i> Voltar para o projeto</a>
            </div>
            
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        <?php if($nivelAcesso == 2)
        { ?>
        <script>
            $("#btn-marcar-revisao").on('click', function()
            {
                
                
                var btne = $(this);
                var revisao = function(btn)
                {
                    var btn = btne;
                    var id = btn.data('id-sprint');
                    var obj = {'id' :id};
                    AdicionarCSRFTokenObj(obj);
                    
                    $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("sprints/marcarRevisao"); ?>',
                    method : 'POST',
                    data : obj,
                   
                    success : function(resposta)
                     {
                        
                         GerarNotificacao(resposta.mensagem, resposta.tipo);
                         
                         window.location.href = "revisao?idsprint=<?php echo $sprint->getId() ?>";
                         
                     },
                    error : function ()
                    {
                        GerarNotificacao("Houve um erro interno na aplicação", "danger");
                    }  
                   
                    });
                };
              
                GerarConfirmacao("Tens certeza que desejas começar a revisar a sprint <i><?php echo $sprint->getNome(); ?>?</i>", revisao);
                
              
               
            });
        </script>
        
        <?php } ?>
    </body>
</html>
