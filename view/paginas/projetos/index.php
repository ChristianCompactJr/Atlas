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
        <div class ="container">
            <h1>Visualizar Projetos</h1>
            <ul class="nav nav-pills">
                <li role="presentation" class="active projetos-tab" id ="meus-projetos"><a href="#">Meus Projetos</a></li>
                <li role="presentation" class ="projetos-tab" id = "todos-projetos"><a href="#">Todos os Projetos</a></li>
            </ul>
        </div>
        <div class="container">
            <div class ="row">
            <div class="col-sm-12 col-md-6">
            <form id="pesquisar-projeto" style="margin:20px 0px;" onsubmit="return false"> 
                <div class="input-group stylish-input-group">
                    <input type="text" class="form-control"  placeholder="Pesquisar por nome" >
                    <span class="input-group-addon">
                        <button type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>  
                    </span>
                </div>
            </form>
        </div>
            </div>
            
            <div class="row">
                <ul class="thumbnails" id = "projetos-conteudo">

                </ul>
            </div>
            <div class ="container">
                <div class ="">
            <ul class="pagination pull-right" id = "div-paginacao">
                <li class ="active">
                    <a href="#" class="pagination_pagina" data-idpagina="1">1</a>
                </li>
                <li>
                    <a href="#" class="pagination_pagina" data-idpagina="2">2</a>
                </li>
                <li>
                    <a href="#" class="pagination_pagina" data-idpagina="3">3</a>
                </li>
            </ul>
                </div>
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
               var resultadosPorPagina = 6;
               
               function CarregarProjetos (json, offset ,recarregarPaginacao)
               {
                     $("#pesquisar-projeto button[type='submit']").attr('disabled', true);
                      
                      var htmlString = '';
                      var total = Object.keys(json).length;
                      var paginationString = '';
                      if(recarregarPaginacao == true)
                        {
                            offset = 0;
                            var totalpaginas = Math.ceil(total / resultadosPorPagina);
                            for(var w = 0; w < totalpaginas; w++)
                            {
                                paginationString += '<li';
                              if(w == 0)
                              {
                                  paginationString += ' class = "active"';
                              }
                              paginationString += '><a href="#" class="pagination_pagina" data-idpagina="'+w+'">'+(w+1)+'</a></li>'; 
                            }
                        }
                     for(var i = offset, j = 0;  i < total && j < resultadosPorPagina; i++, j++)
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
                          else if(json[i].farol == 'entrege')
                          {
                              farolString = '<span style = "color:green;font-weight:bold">Entrege</span>';
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
                            
                          htmlString += '<div class="col-md-4"><div class="thumbnail"><div class="caption"><h3>'+json[i].nome+'</h3><p><Strong>SCRUM Master: </Strong><img src = "../'+json[i].master.foto+'" class = "img-thumbnail" style = "max-width:50px;margin-right:5px;">'+json[i].master.nome+'</p><p><Strong>Inicio: </Strong>'+json[i].inicio+'</p><p><Strong>Prazo: </Strong>'+json[i].prazo+'</p><p><Strong>Estágio: </Strong>'+json[i].estagio+'</p><p><Strong>Andamento: </Strong>'+farolString+'</p><div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'+json[i].porcentual+'" aria-valuemin="0" aria-valuemax="100" style="width: '+json[i].porcentual+'%"><span class="sr-only">'+json[i].porcentual+'% Completado</span></div><span class="progress-type">Completado</span><span class="progress-completed" style = "padding: 3px 10px 2px;">'+json[i].porcentual+'%</span></div><div class ="btn-group btn-group-justified"><a href="visualizar?id='+json[i].id+'" class="btn btn-primary btn-block">'+visualizarString+'</a>'
                        <?php
                        if(SessionController::IsAdmin())
                        {?>
                            htmlString += '<a href="editar?id='+json[i].id+'" class="btn btn-success">Editar</a><a href="#" class="btn btn-danger btn-excluir-projeto" data-idprojeto="'+json[i].id+'" data-nomeprojeto = "'+json[i].nome+'">Apagar</a>';
                        <?php }
                        else
                        {?>
                            
                            if(master == true)
                            {
                                htmlString +=  '<a href="editar?id='+json[i].id+'" class="btn btn-success">Editar</a>';
                            }
                            
                        <?php }
                        
                        ?>
                        htmlString += '</div></div></div></div>';
                      }
                       $("#projetos-conteudo").html(htmlString);
                       if(recarregarPaginacao == true)
                          {
                              $("#div-paginacao").html(paginationString);
                          }
                         $("#pesquisar-projeto button[type='submit']").attr('disabled', false);
               }
               
               function CarregarJSON(__callback)
               {
                   var data = {idusuario : idusuario};
                AdicionarCSRFTokenObj(data);
                $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("projeto/PaginacaoController.php"); ?>',
                   method : 'POST',
                   data : data,

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
                                 var totalDevs = Object.keys(el.devs).length;

                                 for(var j = 0; j < totalDevs; j++)
                                 {
                                     if(el.devs[j].id == idusuario)
                                     {
                                         return true;
                                     }
                                 }
                                 return false;
                             }

                         });
                         carregandoProjetos = false;
                         __callback();
                   },
                   error : function(a, b, c)
                   {
                       console.log(a);
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
                   
                   CarregarProjetos(meusProjetos, 0, true);
                   
                   $(this).addClass("active");
                   $("#pesquisar-projeto input").val('');
               });
               
               $("#todos-projetos").on('click', function()
               {
                   if(carregandoProjetos == true)
                   {
                       return;
                   }
                   $(".projetos-tab.active").removeClass("active");
                   
                   CarregarProjetos(todosProjetos, 0, true);
                   
                   $(this).addClass("active");
                   $("#pesquisar-projeto input").val('');
               });
               
               $("#projetos-conteudo").on('click', '.btn-excluir-projeto', function()
               {
                   var idprojetoesc = $(this).data('idprojeto');
                   var btnesc = $(this);
                   var nomeprojeto = $(this).data('nomeprojeto');
                   
                   
                   
                   var excluir = function()
                   {
                   var idprojeto = idprojetoesc;
                   var btn = btnesc;
                   var meusprojetoselecionado;
                   var data = {idprojeto: idprojeto};
                AdicionarCSRFTokenObj(data);
                   $.ajax({
                       url : '<?php echo UrlManager::GetPathToController("projeto/ApagarProjeto.php"); ?>',
                      method : 'POST',
                      data : data,
                      
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
                          GerarNotificacao(resposta.mensagem, resposta.tipo);
                          if(resposta.tipo != "success")
                          {
                             btn.attr("disabled", false);
                          }
                           CarregarJSON(function()
                            {
                                if(meusprojetoselecionado == true)
                                {
                                    CarregarProjetos(meusProjetos, 0, true);
                                }
                                else
                                {
                                    CarregarProjetos(todosProjetos, 0, true);
                                }
                                 
                            });
                          
                      },
                      error : function(a, b, c)
                      {
                            GerarNotificacao(a.responseText, "danger");
                            btn.attr("disabled", false);
                      }
                      
                   });
               }
               GerarConfirmacao("Tem certeza que queres excluir o projeto "+nomeprojeto+"?", excluir);
               });
               CarregarJSON(function()
               {
                   CarregarProjetos(meusProjetos, 0, true);
               });
              
                $("#pesquisar-projeto").on('submit', function()
                {
                    var pesquisa = $("input", $(this)).val().toUpperCase();
                    
                    var selecionados;
                    if($("#meus-projetos").is(".active"))
                    {
                        selecionados = meusProjetos.filter(function(el)
                        {
                           return el.nome.toUpperCase().match(pesquisa)
                        }); 
                    }
                    else
                    {
                        selecionados = todosProjetos.filter(function(el)
                        {
                           return el.nome.toUpperCase().match(pesquisa)
                        });
                    }
                    
                    CarregarProjetos(selecionados, 0, true);
                    
                     
                    
                });
                
                $("#div-paginacao").on('click', '.pagination_pagina', function()
                {
                    
                    if($(this).parent().is(".active"))
                    {
                        return;
                    }
                    
                    
                    
                   var paginaoffset = $(this).data('idpagina') * resultadosPorPagina;
                   if($("#meus-projetos").is(".active"))
                    {
                        var filtrado = meusProjetos.filter(function(el)
                        {
                            var pesquisa = $("#pesquisar-projeto input").val().toUpperCase();
                           return el.nome.toUpperCase().match(pesquisa);
                        });
                        
                        CarregarProjetos(filtrado, paginaoffset, false);
                    }
                    else
                    {
                        var filtrado = todosProjetos.filter(function(el)
                        {
                            var pesquisa = $("#pesquisar-projeto input").val().toUpperCase();
                           return el.nome.toUpperCase().match(pesquisa);
                        });
                        CarregarProjetos(filtrado, paginaoffset, false);
                    }
                    
                    $("#div-paginacao .active").removeClass("active");
                    $(this).parent().addClass("active");
                   
                   
                });
                 $("#projetos-conteudo").on('click', '.btn-editar-projeto', function()
                {
                    var idprojeto = $(this).data('projeto-id');
                    var projetoEscolhido = todosProjetos.filter(function(el)
                    {
                       return idprojeto === el.id;
                    });
                    
                    
                    <?php
                        if(!SessionController::IsAdmin())
                        { ?>
                           if(projetoEscolhido.master.id != idusuario)
                           {
                               return;
                           }
                            
                        <?php }
                    ?>

                   
                });
            });
            
           
            
        </script>
        
    </body>
</html>
