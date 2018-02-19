<?php

if(!SessionController::TemSessao())
{
    header("location: ../login");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Projetos - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>    
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               
        
        
        <div class ="container" style = "margin-bottom:20px">
            <h1>Visualizar Projetos</h1>
            <ul class="nav nav-pills">
                <li role="presentation" class="active projetos-tab" id ="meus-projetos"><a href="#">Meus Projetos</a></li>
                <li role="presentation" class ="projetos-tab" id = "todos-projetos"><a href="#">Todos os Projetos</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="row">
                <ul class="thumbnails" id = "projetos-conteudo">

                </ul>
            </div>
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        
        <script>
            
            $(document).ready(function()
            {
                var carregandoProjetos = true;
               var idusuario = <?php echo SessionController::GetUsuario()->getId() ?>;
               var loader = '<div class="container"><div class="row"> <div id="loader"> <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="lading"></div></div></div>'; 
               var todosProjetos = new Array();
               var meusProjetos = new Array();
               
               
               function CarregarProjetos (json)
               {
                      var htmlString = '';
                      var total = Object.keys(json).length;
                      for(var i = 0; i < total; i++)
                      {
                          var farolString;
                          if(json[i].farol == 'verde')
                          {
                             farolString = '<span style = "color:green;font-weight:bold">Adiantado</span>';
                          }
                          else if(json[i].farol == 'amarelo')
                          {
                             farolString = '<span style = "color:orange;font-weight:bold">Como previsto</span>';
                          }
                          else
                          {
                              farolString = '<span style = "color:red;font-weight:bold">Atrasado</span>';
                          }
                          
                          
                          var visualizarString;
                          var master;
                          if(json[i].master.id == idusuario)
                            {
                                 master = true;
                                 visualizarString = "Gerenciar"
                            }
                            else
                            {
                                master = false;
                                visualizarString = "Visualizar";
                            }
                            
                          htmlString += '<div class="col-md-4"><div class="thumbnail"><div class="caption"><h3>'+json[i].nome+'</h3><p><Strong>Inicio: </Strong>'+json[i].inicio+'</p><p><Strong>Prazo: </Strong>'+json[i].prazo+'</p><p><Strong>Estágio: </Strong>'+json[i].estagio+'</p><p><Strong>Andamento: </Strong>'+farolString+'</p><div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'+json[i].porcentual+'" aria-valuemin="0" aria-valuemax="100" style="width: '+json[i].porcentual+'%"><span class="sr-only">'+json[i].porcentual+'% Completado</span></div><span class="progress-type">Completado</span><span class="progress-completed" style = "padding: 3px 10px 2px;">'+json[i].porcentual+'%</span></div><div class ="btn-group btn-group-justified"><a href="#" class="btn btn-primary btn-block">'+visualizarString+'</a>'
                        <?php
                        if(SessionController::IsAdmin())
                        {?>
                            htmlString += '<a href="#" class="btn btn-success">Editar</a><a href="#" class="btn btn-danger btn-excluir-projeto" data-idprojeto="'+json[i].id+'">Apagar</a>';
                        <?php }
                        else
                        {?>
                            
                            if(master == true)
                            {
                                htmlString +=  '<a href="#" class="btn btn-success">Editar</a>';
                            }
                            
                        <?php }
                        
                        ?>
                        htmlString += '</div></div></div></div>';
                      }
                       $("#projetos-conteudo").html(htmlString);

               }
               
               function CarregarJSON(__callback)
               {
                $.ajax({

                   url: '../controller/projeto/PaginacaoController.php',
                   method : 'POST',
                   data : {idusuario : idusuario},

                   beforeSend : function()
                   {
                       $("#projetos-conteudo").html(loader);
                   },

                   success : function(resposta)
                   {

                       todosProjetos = $.map(resposta, function(el)
                         {
                             return el
                         });
                       meusProjetos = todosProjetos.filter(function(el)
                         {
                             if(el.master.id == idusuario)
                             {
                                 return true;
                             }
                             else
                             {
                                 var totalDevs = Object.keys(el.devs);

                                 for(var j = 0; j < totalDevs; j++)
                                 {
                                     if(el.devs[j].id == idusuario)
                                     {
                                         return true;
                                     }
                                 }
                                 return false;
                             }

                             return el.id == id;
                         });
                         carregandoProjetos = false;
                         __callback();
                   },
                   error : function(a, b, c)
                   {
                       GerarNotificacao("Houve um erro ao carregar os projetos", "danger");
                   }  
                });
                
            }
               
               
               $("#meus-projetos").on('click', function()
               {
                   if(carregandoProjetos == true)
                   {
                       return;
                   }
                   $(".projetos-tab.active").removeClass("active");
                   
                   CarregarProjetos(meusProjetos);
                   
                   $(this).addClass("active");
               });
               
               $("#todos-projetos").on('click', function()
               {
                   if(carregandoProjetos == true)
                   {
                       return;
                   }
                   $(".projetos-tab.active").removeClass("active");
                   
                   CarregarProjetos(todosProjetos);
                   
                   $(this).addClass("active");
               });
               
               $("#projetos-conteudo").on('click', '.btn-excluir-projeto', function()
               {
                   var idprojeto = $(this).data('idprojeto');
                   var btn = $(this);
                   var meusprojetoselecionado;
                   $.ajax({
                      url :'../controller/projeto/ApagarProjeto.php',
                      method : 'POST',
                      data : {idprojeto: idprojeto},
                      
                      beforeSend : function()
                      {
                          btn.attr("disabled", true);
                          if($("#meus-projetos").is(".active"))
                          {
                              meusprojetoselecionado = true;
                          }
                          else
                          {
                              meusprojetoselecionado = false;
                          }
                      },
                      success : function(resposta)
                      {   
                          
                          if(resposta.tipo == "sucesso")
                          {
                             
                              GerarNotificacao(resposta.mensagem, "success");
                          }
                          else
                          {
                               btn.attr("disabled", false);
                              GerarNotificacao(resposta.mensagem, "danger");
                          }
                          
                           CarregarJSON(function()
                            {
                                if(meusprojetoselecionado == true)
                                {
                                    CarregarProjetos(meusProjetos);
                                }
                                else
                                {
                                    CarregarProjetos(todosProjetos);
                                }
                                 
                            });
                          
                      },
                      error : function(a, b, c)
                      {
                            GerarNotificacao(a.responseText, "danger");
                            btn.attr("disabled", false);
                      }
                      
                   });
               });
               
               CarregarJSON(function()
               {
                   CarregarProjetos(meusProjetos);
               });
              

            });
            
        </script>
        
    </body>
</html>
