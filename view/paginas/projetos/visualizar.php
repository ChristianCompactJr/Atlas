<?php
if(!SessionController::TemSessao())
{
    header("location: ../index.php");
}

try
{
    $dao = new ProjetoDAO();
    $projeto = $dao->GetProjeto($_GET['id']);
    $udao = new UsuarioDAO();
}



catch(Exception $e)
{
    header("location: ../index.php");
}

$scrumMaster = $udao->GetUsuario($projeto->getScrumMaster());
 $devs = $dao->GetDevsProjeto($_GET['id']);
 $podeGerenciar = SessionController::IsAdmin() || $projeto->getScrumMaster() == SessionController::GetUsuario()->getId();
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Projeto - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>  
            
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               
        
        <div class ="container">
            <h1><?php echo $projeto->getNome(); ?></h1>
            <div class ="row">
                <p><h3 style = "display:inline">SCRUM Master: </h3><img src = "../<?php echo $scrumMaster->getFoto(); ?>" class = "img-thumbnail" style = "max-width:70px;margin-right:5px;"><strong><?php echo $scrumMaster->getNome(); ?></strong></p> 
            </div>
            <div class ="row">
                 <p><h3 style = "display:inline">Equipe SCRUM: </h3>
                <table class ="table">                       
                    <tbody>
                    <?php
                   
                        foreach($devs as $dev)
                        {
                            echo '<tr><input type = "hidden" name = "dev[]" class = "hidden-dev-input" value = "'.$dev->getId().'"><td><img src = "../'.$dev->getFoto().'" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><strong>'.$dev->getNome().'</strong></td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
                
            </div>
            <div class ="row">
                <p><h3 style = "display:inline">Cliente: </h3><span style = "font-size: 17px"><?php echo $projeto->getCliente(); ?></span></p> 
            </div>
            <div class ="row">
                <p><h3 style = "display:inline">Data de inicio: </h3><span style = "font-size: 17px"><?php echo $projeto->getInicioFormatted(); ?></span></p> 
            </div>
            <div class ="row">
                <p><h3 style = "display:inline">Prazo: </h3><span style = "font-size: 17px"><?php echo $projeto->getPrazoFormatted(); ?></span></p> 
            </div>
            
            <div class ="row">
                <p><h3>Backlog: </h3><span style = "font-size: 17px"><?php echo $projeto->getBacklogFormatted(); ?></span></p> 
            </div>
            <div class ="row">
                <p><h3>Observações: </h3><span style = "font-size: 17px"><?php echo $projeto->GetObservacoesFormatted(); ?></span></p> 
            </div>
             <div class ="row">
                 <p><h3 style = "display:inline">Estagio: </h3><span style = "font-size: 17px"><?php echo $projeto->getEstagio(); ?></span></p> 
            </div>
            <?php
                $farol = $projeto->getFarol();
                $farolString;
                $porcentual = $projeto->getPorcentual();
                $corFarol;
                if($farol == 'verde')
                {
                    $farolString = "Adiantado";
                    $corFarol = 'green';
                }
                else if($farol == 'vermelho')
                {
                    $farolString = "Atrasado";
                    $corFarol = 'red';
                }
                else if($farol == 'amarelo')
                {
                    $farolString = "Como Previsto";
                    $corFarol = 'orange';
                }
                else if($farol == 'entrege')
                {
                    $corFarol = 'green';
                    $farolString = "Entrege";
                }
                else
                {
                    $corFarol = 'black';
                    $farolString = "Desconhecido";
                }
            ?>
            
            <div class ="row">
                <p><h3 style = "display:inline">Andamento: </h3><span style = "font-size: 17px; color: <?php echo $corFarol; ?>"><b><?php echo $farolString; ?></b></span></p> 
            </div>
            <div class ="row">
                <p><h3>Progresso: </h3></p> 
            <div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $porcentual; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $porcentual; ?>%"><span class="sr-only"><?php echo $porcentual; ?>% Completado</span></div><span class="progress-type">Completado</span><span class="progress-completed" style = "padding: 3px 10px 2px;"><?php echo $porcentual; ?>%</span></div>
            </div>
            
            <div class ="row">
                <p><h3>Tarefas: </h3></p>
            
                <div class="col-md-12" id = "tarefas-conteudo">
		</div>
            </div>
            
            <?php
                if($podeGerenciar)
                {?>
            
            <div class="col-md-offset-3 col-md-6 text-center">                         
                <button type="button"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modalAdicionarMacro">Adicionar Tarefa Macro</button>
            </div>
                <?php } ?>
        </div>
                   
        <?php Carregador::CarregarViewFooter(); ?>
         <script>
             var carregado;
             var idprojeto = <?php echo $projeto->getId() ?>;
             function carregarJSON()
             {
                 $.ajax({
                 method : 'POST',
                 url : '../controller/tarefas/macro/carregarJSON.php',
                 data : {idprojeto : idprojeto},
                 
                 success : function(json)
                 {
                     var htmlString = '';
                     var totalMacro = Object.keys(json).length;
                     
                         
                     for(var i = 0; i < totalMacro; i++)
                     {
                         htmlString += '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title"><a data-toggle="collapse" href="#macro-conteudo_'+json[i].id+'">'+json[i].nome+'</a></h3></div>';
                         htmlString += '<div class ="panel-collapse collapse tarefa-macro-panel" id="macro-conteudo_'+json[i].id+'"><div class="panel-body"><p>'+json[i].descricao+'</p></div>';
                         htmlString += '<ul class = "list-group">';
                         var totalMicro = Object.keys(json[i].micros).length;
                         for(var j = 0; j < totalMicro; j++)
                         {
                             var concluidaString;
                             
                             if(json[i].micros[j].concluida == true)
                             {
                                 concluidaString = '<label class="btn btn-success btn-sm concluida-btn"><span><i class="fa fa-check"></i> Concluída</span></label>';
                             }
                             else
                             {
                                 concluidaString = '<label class="btn btn-warning btn-sm concluida-btn"><span class=""><i class="fa fa-exclamation"></i> Não Concluída</span></label>';
                             }
                             
                             htmlString += '<li class ="list-group-item">'+concluidaString+'<h4>'+json[i].micros[j].nome+'</h4>';
                             
                             var btnString = '<div class="pull-right"><button class="btn btn-primary btn-abrir-modal-ver-micro" data-id-micro='+json[i].micros[j].id+'><i class = "fa fa-eye"></i></button>';
                            <?php if($podeGerenciar){ ?>
                                    btnString += '<button class="btn btn-success btn-abrir-modal-editar-micro" data-id-micro='+json[i].micros[j].id+'><i class = "fa fa-edit"></i></button><button class="btn btn-danger btn-excluir-micro" data-id-micro = "'+json[i].micros[j].id+'" data-nome-micro = "'+json[i].micros[j].nome+'"><i class = "fa fa-trash"></i></button>';
                            <?php }  ?>
                             
                             htmlString += btnString;
                         }
                         <?php if($podeGerenciar){ ?>
                             htmlString += '<li class ="list-group-item"><div class="pull-right"><button class="btn btn-success btn-abrir-modal-adicionar-micro" data-id-macro='+json[i].id+'><i class = "fa fa-plus-circle"></i> Adicionar Tarefa Micro</button><button class="btn btn-primary btn-abrir-modal-editar-macro" data-id-macro='+json[i].id+'><i class = "fa fa-edit"></i> Editar Tarefa Macro</button><button class="btn btn-danger btn-excluir-macro" data-id-macro="'+json[i].id+'" data-nome-macro="'+json[i].nome+'"><i class = "fa fa-trash"></i> Excluir Tarefa Macro</button></div></li>'
                     <?php }  ?>
                         
                         
                         htmlString += '</ul></div></div>';
                             
                         
                     }
                     $("#tarefas-conteudo").html(htmlString);
                     carregado = $.map(json, function(el)
                    {
                        return el
                    }); 
                 },
                 error : function(a)
                 {
                     GerarNotificacao("Houve um erro ao carregar as tarefas", 'danger');
                 }
                 
             });
             }
             
             var loader = '<div class="container"><div class="row"> <div id="loader"> <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="lading"></div></div></div>'; 
             $("#tarefas-conteudo").html(loader);
             $(document).ready(function()
             {
                 carregarJSON();
             })
             
             
             
        </script>
        <?php if($podeGerenciar)
        {
        ?>
        
        <div class="modal fade" id="modalAdicionarMacro" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Adicionar Tarefa Macro</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                    <div class = "col-md-1">
                    </div>
                    <div class ="col-md-10 col-sm-12">
                        <form onsubmit="return false" id ="form-adicionarMacro">
                            <input type ="hidden" name ="idprojeto"  value = "<?php echo $projeto->getId()?> ">
                            <div class="form-group">
                              <label for="nome">Nome: </label>
                               <input type="text" name = "nome" class="form-control" placeholder="Nome da tarefa tarefa macro" required>
                            </div>
                            <div class="form-group">
                              <label for="nome">Descricao: </label>
                              <textarea name = "descricao" class="form-control" style = "resize:vertical" rows="6" placeholder="Descrição da tarefa macro" required></textarea>
                            </div>
                            <div class="col-md-offset-3 col-md-6 text-center">                         
                                <button type="submit"  class="btn btn-primary btn-lg btn-block">Adicionar</button>
                            </div>
                        </form>      
                    </div>
                 </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="modalEditarMacro" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Editar Tarefa Macro</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                    <div class = "col-md-1">
                    </div>
                    <div class ="col-md-10 col-sm-12">
                        <form onsubmit="return false" id ="form-editarMacro">
                            <input type ="hidden" name ="idmacro" id ="hidden-edit-macro" value = "">
                            <div class="form-group">
                              <label for="nome">Nome: </label>
                               <input type="text" name = "nome" id ="nome-edit-macro" class="form-control" placeholder="Nome da tarefa tarefa macro" required>
                            </div>
                            <div class="form-group">
                              <label for="nome">Descricao: </label>
                              <textarea name = "descricao" id ="descricao-edit-macro" class="form-control" style = "resize:vertical" rows="6" placeholder="Descrição da tarefa macro" required></textarea>
                            </div>
                            <div class="col-md-offset-3 col-md-6 text-center">                         
                                <button type="submit"  class="btn btn-primary btn-lg btn-block">Editar</button>
                            </div>
                        </form>      
                    </div>
                 </div>
                </div>
              </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAdicionarMicro" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Adicionar Tarefa Micro</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                    <div class = "col-md-1">
                    </div>
                    <div class ="col-md-10 col-sm-12">
                        <form onsubmit="return false" id ="form-adicionarMicro">
                            <input type ="hidden" name ="idprojeto"  value = "<?php echo $projeto->getId()?> ">
                            <input type ="hidden" name ="idmacro" id ="hidden-id-macro" value = ""> 
                            <div class="form-group">
                              <label for="nome">Nome: </label>
                               <input type="text" name = "nome" class="form-control" placeholder="Nome da tarefa tarefa macro" required>
                            </div>
                            <div class="form-group">
                              <label for="descricao">Descricao: </label>
                              <textarea name = "descricao" class="form-control" style = "resize:vertical" rows="6" placeholder="Descrição da tarefa macro" required></textarea>
                            </div>
                            <div class="form-group">
                              <label for="tempo">Tempo previsto para conclusão: </label>
                               <input type="text" name = "tempo" class="form-control" placeholder="Tempo previsto para a concluisão da tarefa">
                            </div>
                            <div class="form-group">
                              <label for="links">Links úteis: </label>
                              <textarea name = "links" class="form-control" style = "resize:vertical" rows="6" placeholder="Links"></textarea>
                            </div>
                            <div class="col-md-offset-3 col-md-6 text-center">                         
                                <button type="submit"  class="btn btn-primary btn-lg btn-block">Adicionar</button>
                            </div>
                        </form>      
                    </div>
                 </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="modalEditarMicro" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Editar Tarefa Micro</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                    <div class = "col-md-1">
                    </div>
                    <div class ="col-md-10 col-sm-12">
                        <form onsubmit="return false" id ="form-editarMicro">
                            <input type ="hidden" name ="idprojeto" value = "<?php echo $projeto->getId()?> ">
                            <input type ="hidden" name ="idmicro" id ="hidden-edit-micro" value = ""> 
                            <div class="form-group">
                              <label for="nome">Nome: </label>
                               <input type="text" name = "nome" id ="nome-edit-micro" class="form-control" placeholder="Nome da tarefa tarefa macro" required>
                            </div>
                            <div class="form-group">
                              <label for="descricao">Descricao: </label>
                              <textarea name = "descricao" id ="descricao-edit-micro" class="form-control" style = "resize:vertical" rows="6" placeholder="Descrição da tarefa macro" required></textarea>
                            </div>
                            <div class="form-group">
                              <label for="tempo">Tempo previsto para conclusão: </label>
                               <input type="text" name = "tempo" id ="tempo-edit-micro" class="form-control" placeholder="Tempo previsto para a concluisão da tarefa">
                            </div>
                            <div class="form-group">
                              <label for="links">links_utis: </label>
                              <textarea name = "links" class="form-control" id ="link-edit-micro" style = "resize:vertical" rows="6" placeholder="Links"></textarea>
                            </div>
                            <div class="checkbox">
                                <label>
                                  <input type="checkbox" name = "concluida" id="concluida-edit-micro"> Concluída
                                </label>
                              </div>
                            <div class="col-md-offset-3 col-md-6 text-center">                         
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Editar</button>
                            </div>
                        </form>      
                    </div>
                 </div>
                </div>
              </div>
            </div>
        </div>
        
        <script>
        $("#form-adicionarMacro").on('submit', function()
        {
           
           var form = $(this);
           var btn = $("button[type='submit']", form);
           
           $.ajax({
              
              url : '../controller/tarefas/macro/adicionarTarefa.php',
              method : 'POST',
              dataType:'json',
              data : form.serialize(),
              
              beforeSend : function()
              {
                  btn.attr('disabled', true);
              },
              
              success : function(resposta)
              {
                  var tipo;
                  if(resposta.tipo == "sucesso")
                  {
                      tipo = 'success';
                      carregarJSON();
                  }
                  else
                  {
                      tipo = 'danger';
                  }
                  
                  GerarNotificacao(resposta.mensagem, tipo);
              },
              
              error : function()
              {
                  GerarNotificacao("Houve um erro ao cadastrar a tarefa macro", 'danger');
              },
              complete : function()
              {
                    btn.attr('disabled', false);
              }
              
              
              
           });
           
           
        });
        
        $("#form-editarMacro").on('submit', function()
        {
           
           var form = $(this);
           var btn = $("button[type='submit']", form);
           
           $.ajax({
              
              url : '../controller/tarefas/macro/editarTarefa.php',
              method : 'POST',
              dataType:'json',
              data : form.serialize(),
              
              beforeSend : function()
              {
                  btn.attr('disabled', true);
              },
              
              success : function(resposta)
              {
                  var tipo;
                  if(resposta.tipo == "sucesso")
                  {
                      tipo = 'success';
                      carregarJSON();
                  }
                  else
                  {
                      tipo = 'danger';
                  }
                  
                  GerarNotificacao(resposta.mensagem, tipo);
              },
              
              error : function(a)
              {
                  
                  GerarNotificacao("Houve um erro ao cadastrar a tarefa macro", 'danger');
              },
              complete : function()
              {
                    btn.attr('disabled', false);
              }
              
              
              
           });
           
           
        });
        
        $("#form-editarMicro").on('submit', function()
        {
           
           var form = $(this);
           var btn = $("button[type='submit']", form);
           
           $.ajax({
              
              url : '../controller/tarefas/micro/editarTarefa.php',
              method : 'POST',
              dataType:'json',
              data : form.serialize(),
              
              beforeSend : function()
              {
                  btn.attr('disabled', true);
              },
              
              success : function(resposta)
              {
                  var tipo;
                  if(resposta.tipo == "sucesso")
                  {
                      tipo = 'success';
                      carregarJSON();
                  }
                  else
                  {
                      tipo = 'danger';
                  }
                  
                  GerarNotificacao(resposta.mensagem, tipo);
              },
              
              error : function(a)
              {
                  
                  GerarNotificacao("Houve um erro ao editar a tarefa micro", 'danger');
              },
              complete : function()
              {
                    btn.attr('disabled', false);
              }
              
              
              
           });
           
           
        });
        
        
        $("#form-adicionarMicro").on('submit', function()
        {
           
           var form = $(this);
           var btn = $("button[type='submit']", form);
           
           $.ajax({
              
              url : '../controller/tarefas/micro/adicionarTarefa.php',
              method : 'POST',
              dataType:'json',
              data : form.serialize(),
              
              beforeSend : function()
              {
                  btn.attr('disabled', true);
              },
              
              success : function(resposta)
              {
                  var tipo;
                  if(resposta.tipo == "sucesso")
                  {
                      tipo = 'success';
                      carregarJSON();
                  }
                  else
                  {
                      tipo = 'danger';
                  }
                  
                  GerarNotificacao(resposta.mensagem, tipo);
                  
              },
              
              error : function()
              {
                  GerarNotificacao("Houve um erro ao cadastrar a tarefa micro", 'danger');
              },
              complete : function()
              {
                    btn.attr('disabled', false);
              }
              
              
              
           });
           
           
        });
        $("#tarefas-conteudo").on('click',".btn-abrir-modal-adicionar-micro", function()
        {
               $("#hidden-id-macro").val($(this).data('id-macro'));
               $("#modalAdicionarMicro").modal('show');
        });
           
        $("#tarefas-conteudo").on('click',".btn-excluir-micro", function()
        {
            var btne = $(this);
            var excluir = function()
            {
                
                var btn = btne;
                $.ajax({

                  url : '../controller/tarefas/micro/apagarTarefa.php',
                  method : 'POST',
                  dataType:'json',
                  data : {id: btn.data('id-micro')},

                  beforeSend : function()
                  {
                      btn.attr('disabled', true);
                  },

                  success : function(resposta)
                  {
                      var tipo;
                      if(resposta.tipo == "sucesso")
                      {
                          tipo = 'success';
                           btn.parent().parent().remove();
                      }
                      else
                      {
                          tipo = 'danger';
                      }
                     

                      GerarNotificacao(resposta.mensagem, tipo);
                  },

                  error : function(a)
                  {
                      GerarNotificacao("Houve um erro ao apagar a tarefa micro", 'danger');
                  },
                  complete : function()
                  {
                        btn.attr('disabled', false);
                  }



               });
                
           };
           GerarConfirmacao('Tens certeza que deseja excluir a tarefa micro '+btne.data("nome-micro")+'?', excluir);
        });
        
        
        $("#tarefas-conteudo").on('click',".btn-excluir-macro", function()
        {
            var btne = $(this);
            var excluir = function()
            {
                
                var btn = btne;
                $.ajax({

                  url : '../controller/tarefas/macro/apagarTarefa.php',
                  method : 'POST',
                  dataType:'json',
                  data : {id: btn.data('id-macro')},

                  beforeSend : function()
                  {
                      btn.attr('disabled', true);
                  },

                  success : function(resposta)
                  {
                      var tipo;
                      if(resposta.tipo == "sucesso")
                      {
                          tipo = 'success';
                          btn.parent().parent().parent().parent().parent().remove();
                      }
                      else
                      {
                          tipo = 'danger';
                      }
                      

                      GerarNotificacao(resposta.mensagem, tipo);
                  },

                  error : function(a)
                  {
                      GerarNotificacao("Houve um erro ao apagar a tarefa macro", 'danger');
                  },
                  complete : function()
                  {
                        btn.attr('disabled', false);
                  }



               });
                
           };
           GerarConfirmacao('Tens certeza que deseja excluir a tarefa macro '+btne.data("nome-macro")+'?', excluir);
        });
        
         $("#tarefas-conteudo").on('click',".btn-abrir-modal-editar-macro", function()
        {
            var escolhidoId = $(this).data('id-macro');

            var escolhido = carregado.filter(function(el)
            {
               return el.id == escolhidoId;
            });
            
            $("#hidden-edit-macro").val(escolhidoId);
            $("#nome-edit-macro").val(escolhido[0].nome);
            $("#descricao-edit-macro").html(escolhido[0].descricao);
            $("#modalEditarMacro").modal('show');
        });
        
         $("#tarefas-conteudo").on('click',".btn-abrir-modal-editar-micro", function()
        {
            var escolhidoId = $(this).data('id-micro');
            var escolhido = null;
           
            for(var i = 0; i < Object.keys(carregado).length; i++)
            {
             
                var esta = false;
                for(var j = 0; j < Object.keys(carregado[i].micros).length; j++)
                {
                    if(carregado[i].micros[j].id == escolhidoId)
                    {
                        esta = true;
                        escolhido = carregado[i].micros[j];
                        break;
                    }
                }
                if(esta == true)
                {
                    break;
                }
                 
            }
            
            if(escolhido === null)
            {
                return;
            }    
            var concluida;
            if(escolhido.concluida == true)
            {
                concluida = true;
            }
            else
            {
                concluida = false;
            }
            $("#concluida-edit-micro").attr('checked', concluida)
            $("#hidden-edit-micro").val(escolhidoId);
            $("#nome-edit-micro").val(escolhido.nome);
            $("#descricao-edit-micro").html(escolhido.descricao);
            $("#tempo-edit-micro").html(escolhido.tempo);
            $("#link-edit-micro").html(escolhido.links);
            $("#modalEditarMicro").modal('show');
           
        });
        </script>
        
        <?php } ?>
        <script>
             $("#tarefas-conteudo").on('click',".btn-abrir-modal-ver-micro", function()
        {
            var escolhidoId = $(this).data('id-micro');
            var escolhido = null;
           
            for(var i = 0; i < Object.keys(carregado).length; i++)
            {
             
                var esta = false;
                for(var j = 0; j < Object.keys(carregado[i].micros).length; j++)
                {
                    if(carregado[i].micros[j].id == escolhidoId)
                    {
                        esta = true;
                        escolhido = carregado[i].micros[j];
                        break;
                    }
                }
                if(esta == true)
                {
                    break;
                }
                 
            }
            
            if(escolhido === null)
            {
                return;
            }    
            var concluida;
            if(escolhido.concluida == true)
            {
                concluida = true;
            }
            else
            {
                concluida = false;
            }
            
            var concluidaString;
                             
            if(escolhido.concluida == true)
            {
                concluidaString = '<label class="btn btn-success btn-sm btn-block concluida-btn"><span><i class="fa fa-check"></i> Concluída</span></label>';
            }
            else
            {
                concluidaString = '<label class="btn btn-warning btn-sm btn-block concluida-btn"><span class=""><i class="fa fa-exclamation"></i> Não Concluída</span></label>';
            }
            $("#ver-micro-nome").html(escolhido.nome);
            $("#ver-micro-descricao").html(escolhido.descricao);
            $("#ver-micro-tempo").html(escolhido.tempo);
            $("#ver-micro-links").html(escolhido.links);
            $("#ver-micro-concluida").html(concluidaString);
            $("#modalVerMicro").modal('show');
           
        });
            
            
        </script>
        <div class="modal fade" id="modalVerMicro" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Ver Tarefa Micro</h4>
                </div>
                <div class="modal-body" style = "padding:20px 50px">
                    <div class ="row">
                        <p>
                            <h3>Nome: </h3>
                            <span style = "font-size: 17px" id = "ver-micro-nome"></span>
                        </p> 
                    </div>
                    <div class ="row">
                        <p>
                            <h3>Descrição: </h3>
                            <span style = "font-size: 17px" id = "ver-micro-descricao"></span>
                        </p> 
                    </div>
                    <div class ="row">
                        <p>
                            <h3>Tempo previsto para conclusão: </h3>
                            <span style = "font-size: 17px" id = "ver-micro-tempo"></span>
                        </p> 
                    </div>
                    <div class ="row">
                        <p>
                            <h3>Links úteis: </h3>
                            <span style = "font-size: 17px" id = "ver-micro-links"></span>
                        </p> 
                    </div>
                    <div class ="row" id = "ver-micro-concluida">
                        
                    </div>
                </div>
              </div>
            </div>
        </div>
        
    </body>
</html>
