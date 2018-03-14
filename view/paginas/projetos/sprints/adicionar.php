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
   
    if(SessionController::IsAdmin() || SessionController::GetUsuario()->getId() == $master)
    {
        $nivelAcesso = 2;
    }
    else
    {
        header("location: ".UrlManager::GetPathToView("inicial"));
    }

    $macrodao = new TarefaMacroDAO();
    $microdao = new TarefaMicroDAO();
    $tarefasMacro = $macrodao->getTarefasMacro($projeto->getID());
    //$arrayMacro = array();
   // foreach($tarefasMacro as $tm)
   // {
   //     $arrayMacro[] = $tm->toArray();
   // }
    
}

catch(Exception $e)
{
     header("location: ".UrlManager::GetPathToView("inicial"));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Adicionar Sprint - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>          
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        <div class ="container">
            <h1>Adicionar Sprints - <?php echo $projeto->getNome();?></h1>
            <form id="form-cadastrar-sprint" onsubmit="return false" id = "form-cadastro-sprint">
                <input type ="hidden" value ="<?php echo $projeto->getId(); ?>" name ="projeto">
                <div id ="sprint-hidden-group">
                </div>
                <div class ="form-group">
                    <label for="nome">Nome:</label>
                    <input type ="text" class = "form-control" name = "nome" id="sprint-nome" placeholder = "Digite o nome/tílulo da sprint" required>
                </div>
                <div class ="form-group">
                    <label for="nome">Data de Início:</label>
                    <input type ="text" class = "form-control form-data" name = "nome" id ="sprint-inicio" placeholder ="A data de início da Sprint" required>
                </div>
                <div class ="form-group">
                    <label for="nome">Prazo:</label>
                    <input type ="text" class = "form-control form-data" name = "nome" id ="sprint-prazo" placeholder = "A data prazo da sprint" accept=""required>
                </div>
                <h2>Tarefas Disponíveis</h2> 
                <?php 
                            
                            foreach($tarefasMacro as $tarefaMacro)
                            {
                                
                                 echo  '<h3>'.$tarefaMacro->getNome().'</h3><div class ="table-responsive"><table class = "table table-hover table-bordered"><thead><tr><th>Selecionar</th><th>Nome</th><th>Prioridade</th><th>Estimativa</th><th>Estado</th> </tr></thead><tbody>';
                                $tarefasMicro = $microdao->getTarefasMicroDisponiveisSprint($tarefaMacro->getId());
                                
                                foreach($tarefasMicro as $tarefaMicro)
                                {
                                    if($tarefaMicro->getEstado() == "Concluída")
                                    {
                                        continue;
                                    }
                                    echo '<tr data-id-micro = "'.$tarefaMicro->getId().'" data-id-macro = "'.$tarefaMacro->getId().'">';
                                    echo '<td><div class = "hiddens_td"></div><button type = "button" class = "btn btn-success btn-escolher-resp"><i class = "fa fa-id-badge"></i> Selecionar Tarefa</button> <button type = "button" style = "display:none" class = "btn btn-warning btn-cancelar-resp"><i class = "fa fa-times"></i>Cancelar Seleção</button></td>';
                                    echo '<td>'.$tarefaMicro->getNome().'</td>';
                                    echo '<td>'.$tarefaMicro->getPrioridade().'</td>';
                                    echo '<td>'.$tarefaMicro->getEstimativa().'</td>';
                                    echo '<td>'.$tarefaMicro->getEstadoColored().'</td>';
                                    echo '</tr>';
                                }
                               echo '</tbody></table></div>';
                            }
                            
                 ?>
                
            <div class ='col-md-offset-3 col-md-6'>
                <button type ="submit" class="btn btn-success btn-lg btn-block">Cadastrar Sprint</button>
            </div>
                
            </form>
            
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px"    >
                <a href ="<?php echo UrlManager::GetPathToView("projetos/sprints/?idprojeto=".$projeto->getId()); ?>" class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left"></i> Voltar para Sprints</a>
            </div>
            <div class ='col-md-offset-3 col-md-6' style = "margin-top:10px">
                <a href ="<?php echo UrlManager::GetPathToView("projetos/visualizar?id=".$projeto->getId()); ?>" class="btn btn-primary btn-lg btn-block"><i class="fa fa-arrow-circle-left"></i> Voltar para o projeto</a>
            </div>
        </div>
        <?php Carregador::CarregarViewFooter(); ?>  
        <div class="modal fade" id="modalEscolherResp" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Escolher Responsáveis</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                     
                    <div class ="col-md-12 col-sm-12">
                        <form onsubmit = "return false" id="form-responsaveis">
                            <input type ="hidden" id ="hidden-resp-macro" value="" name = "macro">
                            <input type ="hidden" id ="hidden-resp-micro" value="" name = "micro">
                        <label for="responsaveis">Escolha os Responsaveis:</label>
                           <select class="js-example-basic-multiple" id = "select-responsaveis"  style = "padding:0;" name="responsaveis" multiple="multiple" placeholder = "Selecione um desenvolvedor" required>
                            <?php
                                $devs = $dao->GetDevsProjeto($projeto->getId());
                                foreach($devs as $dev)
                                {
                                    echo '<option value = "'.$dev->getID().'">'.$dev->getNome().'</option>';
                                }
                            ?>
                               
                            </select>
                                <div class ='col-md-12' style = "margin-top:10px">
                                    <button type ="submit" class="btn btn-primary btn-lg btn-block" style = "font-size:15px"><i class="fa fa-check-circle"></i> Escolher responsáveis</button>
                                </div>
                                <div class ='col-md-12' style = "margin-top:10px">
                                    <button type ="button" class="btn btn-success btn-lg btn-block btn-sem-resp" style = "font-size:15px"><i class="fa fa-check-circle"></i> Nenhum responsável específico</button>
                                </div>
                                <div class ='col-md-12' style = "margin-top:10px">
                                    <button type ="button" class="btn btn-warning btn-lg btn-block" data-dismiss="modal" style = "font-size:15px"><i class="fa fa-times"> </i>  Cancelar seleção</button>
                                </div>
                        </form>
                        
                        
                        
                    </div>
                 </div>
                </div>

              </div>

            </div>
        </div>
        <script>
            $(document).ready(function(){
               
               $("#select-responsaveis").select2();
               
               $("#form-responsaveis").on('submit', function()
               {
                   var obj = {'micro' : $("#hidden-resp-micro").val()};
                   var arraySelected = new Array();
                   $("#select-responsaveis option:selected").each(function()
                   {
                      arraySelected.push({'id' : $(this).val(), 'nome' : $(this).html()});
                   });
                   obj['responsaveis'] = arraySelected;
                   var linha = $('tr[data-id-micro="'+obj.micro+'"]');
                   var inputString = '';
                   for(var i = 0; i < obj.responsaveis.length; i++)
                   {
                       inputString += obj.responsaveis[i].nome+'; <input type = "hidden" class = "hidden-responsavel" data-id-micro = "'+obj.micro+'" value = "'+obj.responsaveis[i].id+'">';
                   }
                   $(".hiddens_td", linha).html(inputString);
                   $(".btn-escolher-resp", linha).hide();
                   $(".btn-cancelar-resp", linha).show();
                   
                   $("#sprint-hidden-group").append('<input type = "hidden" class = "micro-escolhida" value = "'+obj.micro+'">');
                $("#modalEscolherResp").modal('hide');
               });
               
               $(".btn-escolher-resp").on('click', function()
               {
                   $("#hidden-resp-macro").val($(this).parent().parent().data('id-macro'));
                   $("#hidden-resp-micro").val($(this).parent().parent().data('id-micro'));
                   $("#modalEscolherResp").modal('show');
               });
               $(".btn-sem-resp").on('click', function()
               {
                   var micro = $("#hidden-resp-micro").val();
                   var linha = $('tr[data-id-micro="'+micro+'"]');
                   $(".hiddens_td", linha).html("");
                   $(".btn-escolher-resp", linha).hide();
                   $(".btn-cancelar-resp", linha).show();
                   
                   $("#sprint-hidden-group").append('<input type = "hidden" class = "micro-escolhida" value = "'+micro+'">');
                $("#modalEscolherResp").modal('hide');
               });
               $(".btn-cancelar-resp").on('click', function()
               {
                   var linha = $(this).parent();
                   var idmicro = linha.parent().data('id-micro');
                   $(".hiddens_td", linha).html("");
                   $("#sprint-hidden-group input[value='"+idmicro+"']").remove();
                   $(".btn-escolher-resp", linha).show();
                   $(".btn-cancelar-resp", linha).hide();
               });
               
               
            });
            
            $("#form-cadastrar-sprint").on('submit', function()
            {
                var arrayEscolhidas = new Array();
                
                $(".micro-escolhida").each(function()
                {
                    var idEscolhida = $(this).val();
                   var obj = {'id' : idEscolhida};
                   
                   var responsaveis = new Array();
                   
                   $(".hidden-responsavel[data-id-micro='"+idEscolhida+"']").each(function()
                   {
                      responsaveis.push($(this).val());
                   });
                   
                   obj['responsaveis'] = responsaveis;
                   arrayEscolhidas.push(obj);
                   
                });
                var objescolhidas = {'nome' : $("#sprint-nome").val() ,'inicio' : $("#sprint-inicio").val(), 'prazo' : $("#sprint-prazo").val() ,'projeto' : <?php echo $projeto->getId(); ?>, 'escolhidas' : arrayEscolhidas};
                AdicionarCSRFTokenObj(objescolhidas);
                console.log(objescolhidas);
                var form = $(this);
                $.ajax({
                  url : ' <?php echo UrlManager::GetPathToController("sprints/cadastro"); ?>',
                  method : 'POST',
                  data : objescolhidas,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Cadastrando...");
                  },
                  
                  success : function(resposta)
                  {
                      console.log(resposta);
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                  },
                  
                  complete : function()
                  {
                        $("button[type='submit']", form).html("Cadastrar Sprint");
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                      GerarNotificacao(jqXHR.responseText, 'danger');
                  }    
               });
            });
            $('.form-data').datepicker(
            {
                dateFormat: 'dd/mm/yy',
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                nextText: 'Proximo',
                prevText: 'Anterior'
            });

            $(".form-data").on('focus', function()
            {
                $(this).blur();
            });
            
            
        </script>
                    
    </body>
</html>