<?php



if(!SessionController::TemSessao())
{
    header('locationn ../../index.php');
}
    

$dao = new ProjetoDAO();
$nivelAcesso = 0;
try
{
    
    
    
    $projeto = $dao->GetProjeto($_GET['idprojeto']);
    $master = $projeto->getScrumMaster();
    
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $master)
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
            
            <h1>Sprints - <?php echo $projeto->getNome();?></h1>
            <div class ="table-responsive">
                <table class = "table table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data de Início</th>
                            <th>Prazo</th>
                            <th>Estagio</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sprintdao = new SprintDAO();
                            
                            $sprints = $sprintdao->GetSprintsProjeto($projeto->getId());
                            foreach($sprints as $sprint)
                            {
                                $linkVisualizar = UrlManager::GetPathToView("projetos/sprints/visualizar")."?idsprint=".$sprint->getId();
                                $linkRevisao = UrlManager::GetPathToView("projetos/sprints/revisao")."?idsprint=".$sprint->getId();
                                echo '<tr>';
                                echo '<td><a href = "'.$linkVisualizar.'">'.$sprint->getNome().'</a></td>';
                                echo '<td>'.$sprint->getData_InicioFormatted().'</td>';
                                echo '<td>'.$sprint->getPrazoFormatted().'</td>';
                                echo '<td>'.$sprint->GetEstagioColored().'</td>';
                                
                                
                                echo '<td><a href = "'.$linkVisualizar.'" class = "btn btn-primary" title = "Visualizar sprint"><i class = "fa fa-eye"></i></a>';
                                if($nivelAcesso > 0)
                                {
                                    if(($sprint->getEstagio() == 'Revisão' && $nivelAcesso > 1) || ($sprint->getEstagio() == 'Concluída' && $nivelAcesso > 0))
                                    {
                                        echo '<a href = "'.$linkRevisao.'" class = "btn btn-success" title = "Revisão"><i class = "fa fa-archive"></i></a>';
                                    }
                                    
                                    if($nivelAcesso > 1) { echo '<button type = "button" class = "btn btn-danger excluir-sprint-btn"  data-id-sprint = "'.$sprint->getId().'" data-nome-sprint = "'.$sprint->getNome().'" title = "Excluir sprint"><i class = "fa fa-trash"></i></button>';}
                                }
                                
                                echo '</td></tr>';
                            }
                            
                        ?>
                    </tbody>
                </table>
            </div>
            <?php if($nivelAcesso >= 2)
            { ?>
                <div class="col-md-offset-3 col-md-6 text-center">                         
                    <a href ="adicionar?idprojeto=<?php echo $projeto->getId(); ?>" class="btn btn-primary btn-lg btn-block">Adicionar Sprint</a>
                </div>
            <?php } ?>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px">
                <a href ="<?php echo UrlManager::GetPathToView("projetos/visualizar?id=".$projeto->getId()); ?>" class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left"></i> Voltar para o projeto</a>
            </div>
            
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        <?php if($nivelAcesso == 2)
        { ?>
        <script>
            $(".excluir-sprint-btn").on('click', function()
            {
                
                
                var btne = $(this);
                var excluir = function(btn)
                {
                    var btn = btne;
                    var id = btn.data('id-sprint');
                    var obj = {'id' :id};
                    AdicionarCSRFTokenObj(obj);
                    
                    $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("sprints/excluir"); ?>',
                    method : 'POST',
                    data : obj,
                   
                    success : function(resposta)
                     {
                         btn.parent().parent().remove();
                         GerarNotificacao(resposta.mensagem, resposta.tipo);
                     },
                    error : function ()
                    {
                        GerarNotificacao("Houve um erro interno na aplicação", "danger");
                    }  
                   
                    });
                };
                
                GerarConfirmacao("Tens certeza que desejas apagar a sprint <i>"+$(this).data('nome-sprint')+"</i>", excluir);
                
              
               
            });
        </script>
        
        <?php } ?>
    </body>
</html>
