<?php

if(!SessionController::IsAdmin())
{
    header("location: login");
}
$resultadosPorPagina = 7;
$dao = new UsuarioDAO();
$totalpaginas = ceil($dao->GetTotalUsuarios() / $resultadosPorPagina);
$dao = new HabilidadeDAO;


$habilidades = $dao->GetHabilidades();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Configurar Habilidades - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?> 
        <style>
            .salvar-habilidade-btn, .salvar-habilidade-input
            {
                display:none;
            }
            </style>
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        
        <div class ="container">
             <h1>Configurar Habilidades</h1>
        </div>
        <div class ="container">
            <div class ="row">
                <div class ="col-md-12">
                    <div class ="table-responsive">
                        <table class ="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id = "table-conteudo">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class ="row">
                <div class ="col-md-7"></div>
                <div class ="col-md-7 col-sm-12">
                    <form class ="form-inline" onsubmit="return false" id = "form-criar-habilidade">
                        <label for ="nome">Criar Habilidade:</label>
                        <input class = "form-control" type ="text" name = "nome" placeholder = "Nome da habilidade">
                        <button type ="submit" class ="btn btn-success">Criar</button>
                    </form>
                </div>
            </div>
        </div>
</div>

        <?php Carregador::CarregarViewFooter(); ?>
        <script>
            var loader = '<div class="container"><div class="row"> <div id="loader"> <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="lading"></div></div></div>'; 
            function CarregarHabilidades()
            {
                 $.ajax({
                   
                   url : 'controller/habilidades/carregarTodas.php',
                   method : 'POST',
                   
                   beforeSend : function()
                   {
                       $("#table-conteudo").html(loader);
                   },
                   
                   success : function(resposta)
                   {
                      var htmlString = '';
                      for(var i = 0; i < Object.keys(resposta).length; i++)
                      {
                          htmlString += '<tr><td class = "col-md-6"><input type ="text" value ='+resposta[i].nome+' class = "form-control salvar-habilidade-input"><span>'+resposta[i].nome+'</span></td><td class = "col-md-6"><a class="btn btn-success salvar-habilidade-btn" data-habilidade = "'+resposta[i].id+'" title = "Salvar"><em class="fa fa-save"></em></a><a class="btn btn-primary editar-habilidade-btn" data-habilidade = "'+resposta[i].id+'" title = "Editar"><em class="fa fa-edit"></em></a><a class="btn btn-danger excluir-habilidade-btn" data-habilidade = "'+resposta[i].id+'" data-habilidade-nome = "'+resposta[i].nome+'" title = "Exclir"><em class="fa fa-trash"></em></a></td></tr>';
                      }
                      $("#table-conteudo").html(htmlString);
                      
                   },
                   error : function(jqXHR, textStatus, errorThrown)
                  {  
                      GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
                   
               }) 
            }
            
            CarregarHabilidades();
            
            $("#form-criar-habilidade").on('submit', function()
            {
               var form = $(this);
               $.ajax({
                   
                   url : 'controller/habilidades/criar',
                   method : 'POST',
                   data : form.serialize(),
                   
                   beforeSend : function()
                   {
                       $("input", form).attr('disabled', true);
                   },
                   
                   success : function(resposta)
                   {
                      if(resposta.tipo == 'erro')
                      {
                          GerarNotificacao(resposta.mensagem, 'danger');
                      }
                      else
                      {
                          GerarNotificacao(resposta.mensagem, 'success');
                          CarregarHabilidades();
                      }
                   },
                   complete : function ()
                   {
                       $("input", form).attr('disabled', false);
                   },
                   error : function(jqXHR, textStatus, errorThrown)
                  {  
                      GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
                   
               }) 
            });
            
            
            
            $("#table-conteudo").on('click', ".editar-habilidade-btn", function()
            {
               var id = $(this).data('habilidade');
               
               $('.salvar-habilidade-btn[data-habilidade="'+id+'"]').css('display', 'inline-block');
               var linha = $(this).parent().parent();

               $("td:nth-child(1) .salvar-habilidade-input", linha).show();
               $("td:nth-child(1) span", linha).hide();
               $(this).hide();
            });
            
            $("#table-conteudo").on('click', ".salvar-habilidade-btn", function()
            {
               var id = $(this).data('habilidade');
               var button = $(this);
               var linha = $(this).parent().parent();
               var input =  $("td:nth-child(1) .salvar-habilidade-input", linha);
               var texto = $("td:nth-child(1) span", linha);
                       
               $.ajax({
                  url : 'controller/habilidades/alterar.php',
                  method : 'POST',
                  data : {id : id, nome : input.val()},
                  
                  beforeSend : function ()
                  {
                      button.attr('disabled', true);
                      input.attr('disabled', true);
                  },
                  
                  success : function (resposta)
                  {
                      
                      if(resposta.tipo == 'erro')
                      {
                          GerarNotificacao(resposta.mensagem, 'danger');
                      }
                      else
                      {
                          GerarNotificacao(resposta.mensagem, 'success');
                      }
                      
                      texto.html(input.val());
                      texto.show();
                      input.hide();
                      $('.editar-habilidade-btn[data-habilidade="'+id+'"]').css('display', 'inline-block');
                  },
                  
                  complete : function()
                  {
                       button.attr('disabled', false);
                      input.attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {  
                      GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
                  
                  
               });
               $(this).hide();
            });
            
            $("#table-conteudo").on('click', ".excluir-habilidade-btn", function()
            {
               
               var idhabilidade = $(this).data('habilidade');
                var linhahab = $(this).parent().parent();
                var texto = $("td:nth-child(1) span", linhahab);
               var alterar = function(){
                    var id = idhabilidade;
                   var linha = linhahab;
                   
                   $.ajax({
                   url : 'controller/habilidades/apagar.php',
                  method : 'POST',
                  data : {id : id},
                  
                  beforeSend : function ()
                  {
                      $("input, button", linha).attr('disabled', true);
                  },
                  
                  success : function (resposta)
                  {
                      if(resposta.tipo == 'erro')
                      {
                          GerarNotificacao(resposta.mensagem, 'danger');
                          $("input, button", linha).attr('disabled', false);
                      }
                      else
                      {
                          GerarNotificacao(resposta.mensagem, 'success');
                          linha.remove();
                      }
                  },
                  
                  error : function(jqXHR, textStatus, errorThrown)
                  {  
                      GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
                  
                  
                });   
            };
           
           GerarConfirmacao("Tens certeza que deseja apagar a habilidade "+texto.html()+"?", alterar);
        });
            
        </script>
    </body>
</html>
