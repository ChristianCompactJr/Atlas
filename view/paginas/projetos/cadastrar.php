<?php

if(!SessionController::IsAdmin())
{
    header("location: login");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastrar Usuários - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>  
            
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
               
        
        
        <div class ="container">
            <h1>Cadastrar Projetos</h1>
            <div class = "row">
                <div class = "col-md-12">
                    <form id ="cadastro-projeto-form"  onsubmit="return false">
                        <div class ="form-group">
                            <label for="nome">Nome:</label>
                            <input type ="text" name = "nome" class ="form-control" placeholder = "Nome do projeto" required>
                        </div>
                        <div class ="form-group">
                            <label for="cliente">Cliente:</label>
                            <input type ="text" name = "cliente" class ="form-control" placeholder = "Nome do cliente" required>
                        </div>
                        <div class ="form-group">
                            <label>Scrum Master:</label><br />
                            <button id ="btn-escolher-scrum-master" type ="button" class ="btn btn-success" title="Escolher Scrum Master">Escolher Scrum Master</button>
                        </div>
                        <div class ="form-group">
                            <label>Equipe SCRUM:</label><br />
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
                                                <tbody id = "table-devs-conteudo">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id ="btn-escolher-dev" type ="button" class ="btn btn-success" title="Escolher Desenvolvedores">Adicionar Equipe</button>
                        </div>
                        <div class ="form-group">
                            <label for="inicio">Data de inicio:</label>
                            <input type ="text" name = "inicio" class ="form-control" placeholder = "A data de inicio do projeto" required>
                        </div>
                        <div class ="form-group">
                            <label for="prazo">Prazo:</label>
                            <input type ="text" name = "prazo" class ="form-control" placeholder = "O prazo do projeto" required>
                        </div>
                        <div class ="form-group">
                            <label for="backlog">Backlog:</label>
                            <textarea rows ="7" name = "backlog" class ="form-control" placeholder = "O backlog do proejto"></textarea>
                        </div>
                        <div class ="form-group">
                            <label for="backlog">Observações:</label>
                            <textarea rows ="7" name = "backlog" class ="form-control" placeholder = "O backlog do proejto"></textarea>
                        </div>
                         <div class ="form-group">
                            <label for="estagio">Estágio:</label>
                            <select name ="estagio" class ="form-control">
                                <option value ="Desenvolvimento">Desenvolvimento</option>
                                <option value ="Entrege">Entrege</option>
                                <option value ="Manutenção">Manutenção</option>
                            </select>
                        </div>   
                         <span class = "form-aviso"></span>
                        <div class="row">
                           <div class="col-md-3"></div>
                           <div class="col-md-6 text-center">                         
                              <button type="submit" class="btn btn-primary btn-lg btn-block">Cadastrar Projeto</button>
                           </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
         <div class="modal fade" id="modal-escolher-dev" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Escolha um usuário</h4>
                </div>
                <div class="modal-body">
                    <form id="pesquisar-usuario-dev-form" class = "form-inline" onsubmit="return false">
                        <div class ="form-group">
                            <label for ="pesquisar-usuario-dev">Pesquisar:</div>
                            <input type ="text" name ="pesquisar-usuario-dev" class ="form-control" id ="pesquisar-usuario-dev-form_input" placeholder = "Digite o nome do usuário">
                            <button type ="submit" class = "btn btn-success">Pesquisar</button>
                        </div>
                    </form>
                    
                    <div class ="row" id = "modal-escolher-dev__conteudo">
                        
                    </div>
                </div>

              </div>

            </div>
        </div>
        <script>
            var resultadoDeUsuarioArray;
            
            function CarregarDevs(devs, add)
            {
                if(add == true)
                {
                    devs = devs.filter(function (el) {
                    var tem = false;    
                    $(".hidden-dev-input").each(function(index)
                    {
                       var hiddenval = $(this).val();
                       console.log(hiddenval + " " + el.id);
                       if(hiddenval == el.id)
                       {
                           tem = true;
                       }
                    });
                        
                    return tem == false;
                });
               
                    
                }
                
                var htmlString = '';
                var totalUsuarios = Object.keys(devs).length;
                for(var i = 0; i < totalUsuarios; i++)
                {
                    htmlString += '<img src ="../'+devs[i].foto+'" class = "img-thumbnail" style = "display:block;margin:auto;max-width:150px;"><h3 class = "text-center">'+devs[i].nome+'</h3>';

                    var totalHabilidades = Object.keys(devs[i].habilidades).length;

                    for(var j = 0; j < totalHabilidades; j++)
                    {
                        
                        
                        
                        
                        var valor = devs[i].habilidades[j].valor;
                        var classe;
                        if(valor <= 20)
                        {
                            classe = "progress-bar-danger";
                        }
                        else if(valor <= 40 && valor > 20)
                        {
                            classe = "progress-bar-warning";
                        }
                         else if(valor <= 60 && valor > 40)
                        {
                            classe = "progress-bar-info";
                        }
                         else if(valor <= 80 && valor > 60)
                        {
                            classe = "progress-bar-primary";
                        }
                         else 
                        {
                            classe = "progress-bar-success";
                        }
                        var interessadoString = '';

                        if(devs[i].habilidades[j].interesse == true)
                        {
                            interessadoString = '<div class="interessado-icon" title = "Interessado"><div class="text">*</div></div>';
                        }
                        htmlString += '<div class="progress"><div class="progress-bar '+classe+'" role="progressbar" aria-valuenow="'+valor+'" aria-valuemin="0" aria-valuemax="100" style="width: '+valor+'%;"><span class="sr-only">'+valor+'% Complete</span></div><span class="progress-type">'+devs[i].habilidades[j].nomehabilidade+'</span><span class="progress-completed">'+valor+'</span>'+interessadoString+'</div></div>';
                    }
                    if(add == true)
                    {
                        htmlString += '<button type = "button" class = "btn btn-primary btn-block btn-modal-escolher-dev" data-id="'+devs[i].id+'" data-imgsrc = '+devs[i].foto+' data-nome = '+devs[i].nome+' >Escolher</button>';
                        $("#pesquisar-usuario-dev-form").show();
                    }
                    else
                    {
                        $("#pesquisar-usuario-dev-form").hide();
                    }
                    
                    
                    htmlString += '<hr /><br />';


                }

                $("#modal-escolher-dev__conteudo").html(htmlString);
                $("#modal-escolher-dev").modal('show');
            }
            
            $("#modal-escolher-dev__conteudo").on('click', '.btn-modal-escolher-dev', function()
            {
                var imgsrc = $(this).data('imgsrc');
                var nome = $(this).data('nome');
                var id = $(this).data('id');
               $("#table-devs-conteudo").append('<tr><input type = "hidden" name = "dev[]" class = "hidden-dev-input" value = "'+id+'"><td><img src = "../'+imgsrc+'" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">'+nome+'</span></td><td><a class="btn btn-primary ver-dev-btn" data-id="'+id+'"><em class="fa fa-eye"></em></a><a class="btn btn-danger excluir-dev-btn"><em class="fa fa-trash"></em></a></td></tr>'); 
               $("#modal-escolher-dev").modal('hide');
            });
            
            $("#table-devs-conteudo").on('click', '.excluir-dev-btn', function()
            {
               $(this).parent().parent().remove(); 
            });
            $("#table-devs-conteudo").on('click', '.ver-dev-btn', function()
            {
                var id = $(this).data('id');
              var newArray = resultadoDeUsuarioArray.filter(function (el) {
                return el.id == id;
              });
              CarregarDevs(newArray, false);
            });
            
            
            $("#pesquisar-usuario-dev-form").on('submit', function()
            {
                var pesquisa = $("#pesquisar-usuario-dev-form_input").val().toUpperCase();

                var newArray = resultadoDeUsuarioArray.filter(function (el) {
                return el.nome.toUpperCase().match(pesquisa)
              });
              CarregarDevs(newArray, true);
            });
            
            
            
            
            $("#btn-escolher-dev").on('click', function(){
               
               $.ajax({
                  url : '../controller/usuario/CarregarJSONCompleto.php',
                  method : 'POST',
                  
                  success : function(resposta)
                  {
                       resultadoDeUsuarioArray = $.map(resposta, function(el) { return el });
                       
                       CarregarDevs(resultadoDeUsuarioArray, true);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                      console.log(jqXHR.responseText);
                  }   
                  
               });
               
            });
            
            $("#cadastro-projeto-form").on('submit', function()
            {
               console.log($(this).serialize()); 
            });
        </script>

    </body>
</html>
