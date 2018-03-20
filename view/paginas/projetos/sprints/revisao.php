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
    if($sprint->getEstagio() == "Desenvolvimento")
    {
       
        header("location: ../../index.php");
    }
    
    
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
    
    
    if($nivelAcesso == 0)
    {
       
       header("location: ../../index.php");
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
        <title>Revisão - <?php echo $sprint->getNome();?></title>
        <?php Carregador::CarregarViewHeadMeta(); ?>  
            
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               

        
        <div class ="container">
            
            <h1>Revisão - <?php echo $sprint->getNome();?> - <?php echo $projeto->getNome(); ?></h1>
            <h3>Tarefas: </h3>
            <div class ="table-responsive">
                <table class = "table table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Estado Atual Tarefa Micro</th>
                            <th>Novo Estado Tarefa Micro</th>
                            <th>Responsáveis</th>
                            <th>Desempenho dos responsáveis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                           $tarefasSprint = $tarefasprintdao->GetTarefasSprint($sprint->getId());
                           foreach($tarefasSprint as $tarefaSprint)
                           {
                               $tarefamicro = $microdao->getTarefa($tarefaSprint->getMicro());
                               
                               if($sprint->getEstagio() == "Concluída")
                               {
                                    echo '<tr>';
                                    echo '<td>'.$tarefamicro->getNome().'</td>';
                                    echo '<td class = "estado-atual">'.$tarefaSprint->getHistoricoAtualColored().'</td>';
                                    echo '<td>'.$tarefaSprint->getHistoricoNovoColored().'</td>';
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
                                    echo '<td>'.$tarefaSprint->getDesempenho().'</td>';
                                    echo '</tr>';
                               }
                               else if($sprint->getEstagio() == "Revisão")
                               {
                                    echo '<tr class = "linha-tarefa" data-id-tarefa-sprint = "'.$tarefaSprint->getId().'" data-id-tarefa-micro = "'.$tarefamicro->getId().'" >';
                                    echo '<td>'.$tarefamicro->getNome().'</td>';
                                    echo '<td class = "estado-atual">'.$tarefamicro->getEstadoColored().'</td>';
                                    echo '<td><select class = "form-control estado-novo"><option value = "Incompleta">Incompleta</option><option value = "Instável">Instável</option><option value = "Qualificada">Qualificada</option></div></td>';
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
                                    echo '<td><select class = "form-control desempenho"><option value = "Muito Baixo">Muito Baixo</option><option value = "Baixo">Baixo</option><option value = "Normal">Normal</option><option value = "Alto">Alto</option><option value = "Muito Alto">Muito Alto</option></div></form></td>';
                                    echo '</tr>';
                               }
                              
                           }
                                
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($sprint->getEstagio() == 'Revisão')
            { ?>
                <div class ="form-group">
                    <label for="retrospectiva">Retrospectiva/Observações</label>
                    <textarea id="retrospectiva" class ="form-control" rows = "6" name = "retrospectiva"></textarea>
                </div>
                <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                    <button class="btn btn-success btn-lg btn-block" id = "btn-marcar-concluida" data-id-sprint="<?php echo $sprint->getId(); ?>"><i class="fa fa-check"></i> Concluir Sprint</button>
                </div>
                <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                    <button class="btn btn-warning btn-lg btn-block" id = "btn-marcar-desenvolvimento" data-id-sprint="<?php echo $sprint->getId(); ?>"><i class="fa fa-times"></i> Cancelar Revisão</button>
                </div>
            
           <?php } else { ?>
            
                <div class ="row">
                <p><h3>Retrospectiva: </h3></p>
            
                    <div class="col-md-12">
                        <p><?php echo $sprint->getRetrospectivaFormatted(); ?></p>
                    </div>
		</div>
                 
            
            
          <?php  } ?>
             
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
            $("#btn-marcar-concluida").on('click', function()
            {
               var obj = {'id-sprint' : <?php echo $sprint->getId() ?> };
               AdicionarCSRFTokenObj(obj);
               var resultado = new Array();
               $(".linha-tarefa").each(function()
               {
                  var estadoNovo = $(".estado-novo option:selected", $(this)).val();
                  var desempenho = $(".desempenho option:selected", $(this)).val();
                  resultado.push({'idtarefasprint': $(this).data('id-tarefa-sprint'), 'idmicro': $(this).data('id-tarefa-micro'), 'estadoNovo' : estadoNovo, 'desempenho' : desempenho});
                 
                  
               });
               
               obj['resultado'] = resultado;
               obj['retrospectiva'] = $("#retrospectiva").val();
                var revisao = function()
                {
                    var info = obj;
                    
                    $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("sprints/marcarConcluida"); ?>',
                    method : 'POST',
                    data : info,
                    dataType: 'json',
                    success : function(resposta)
                     {
                         window.location.href = "../sprints?idprojeto=<?php echo $projeto->getId(); ?>";
                         GerarNotificacao(resposta.mensagem, resposta.tipo);
                     },
                    error : function (a)
                    {
                        console.log(a);
                        GerarNotificacao("Houve um erro interno na aplicação", "danger");
                    }  
                   
                    });
                };
              
                GerarConfirmacao("Tens certeza que desejas concluir a revisão da sprint <i><?php echo $sprint->getNome(); ?>?</i>", revisao);
   
               
            });
            
            $("#btn-marcar-desenvolvimento").on('click', function()
            {
                
                
                var btne = $(this);
                var revisao = function(btn)
                {
                    var btn = btne;
                    var id = btn.data('id-sprint');
                    var obj = {'id' :id};
                    AdicionarCSRFTokenObj(obj);
                    
                    $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("sprints/marcarDesenvolvimento"); ?>',
                    method : 'POST',
                    data : obj,
                    dataType : 'json',
                   
                    success : function(resposta)
                     {
                        window.location.href = "visualizar?idsprint=<?php echo $sprint->getId() ?>";
                         GerarNotificacao(resposta.mensagem, resposta.tipo);
                     },
                    error : function (a)
                    {
                        console.log(a);
                        GerarNotificacao("Houve um erro interno na aplicação", "danger");
                    }  
                   
                    });
                };
              
                GerarConfirmacao("Tens certeza que desejas cancelar a revisão da sprint <i><?php echo $sprint->getNome(); ?>?</i>", revisao);
   
            });
        </script>
        
        <?php } ?>
    </body>
</html>
