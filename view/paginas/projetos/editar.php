<?php

if(!SessionController::TemSessao())
{
    header('locationn ../index.php');
}
    

$dao = new ProjetoDAO();

try
{
      
    
    $projeto = $dao->GetProjeto($_GET['id']);

    if(SessionController::GetUsuario()->getId() != $projeto->getScrumMaster() && !SessionController::IsAdmin())
    {
        header("location: ../index.php");
    }

    $udao = new UsuarioDAO();
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
            <h1>Editar Projeto</h1>
            <div class = "row">
                <div class = "col-md-12">
                    <form id ="cadastro-projeto-form"  onsubmit="return false" method = "POST" action = "../controller/projeto/CadastroController.php">
                        <input type ="hidden" name="idprojeto" value ="<?php echo $projeto->getId() ?>" /> 
                        <div class ="form-group">
                            <label for="nome">Nome:</label>
                            <input type ="text" name = "nome" class ="form-control" placeholder = "Nome do projeto" value="<?php echo $projeto->getNome(); ?>" required>
                        </div>
                        <div class ="form-group">
                            <label for="cliente">Cliente:</label>
                            <input type ="text" name = "cliente" class ="form-control" placeholder = "Nome do cliente" value="<?php echo $projeto->getCliente(); ?>"  required>
                        </div>
                        <?php
                            $scrumMaster = $udao->GetUsuario($projeto->getScrumMaster());
                            if(SessionController::IsAdmin())
                            { ?>
                                
                        <div class ="form-group">
                            <label>Scrum Master:</label><br />
                            <div class ="container">
                                <div class ="row">
                                    <div class ="col-md-12">
                                        <div class ="table-responsive">
                                            <table class ="table">
                                                
                                                <tbody id = "table-master-conteudo">
                                                <?php    
                                                
                                                    
                                                    
                                                    echo '<tr><input type = "hidden" name = "master" class = "hidden-dev-input" value = "'.$scrumMaster->getId().'"><td><img src = "../'.$scrumMaster->getFoto().'" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">'.$scrumMaster->getNome().'</span></td><td><a class="btn btn-primary ver-dev-btn" data-id="'.$scrumMaster->getId().'"><em class="fa fa-eye"></em></a><a class="btn btn-danger excluir-dev-btn"><em class="fa fa-trash"></em></a></td></tr>';
                                                    
                                                 ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id ="btn-escolher-scrum-master" type ="button" class ="btn btn-success" title="Escolher Scrum Master">Escolher Scrum Master</button>
                        </div>
                        
                        <?php }
                        else
                        {
                            echo '<input type = "hidden" name = "master" class = "hidden-dev-input" value = "'.$scrumMaster->getId().'">';
                        }
                        ?>
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
                                                    <?php
                                                        $devs = $dao->GetDevsProjeto($_GET['id']);
                                                        foreach($devs as $dev)
                                                        {
                                                            echo '<tr><input type = "hidden" name = "dev[]" class = "hidden-dev-input" value = "'.$dev->getId().'"><td><img src = "../'.$dev->getFoto().'" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">'.$dev->getNome().'</span></td><td><a class="btn btn-primary ver-dev-btn" data-id="'.$dev->getId().'"><em class="fa fa-eye"></em></a><a class="btn btn-danger excluir-dev-btn"><em class="fa fa-trash"></em></a></td></tr>';
                                                        }
                                                    ?>
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
                            <input type ="text" name = "inicio" id ="inicio" class ="form-control" placeholder = "A data de inicio do projeto"  value="<?php echo $projeto->getInicioFormatted(); ?>"  required>
                        </div>
                        <div class ="form-group">
                            <label for="prazo">Prazo:</label>
                            <input type ="text" name = "prazo" id ="prazo" class ="form-control" placeholder = "O prazo do projeto" value="<?php echo $projeto->getPrazoFormatted(); ?>"  required>
                        </div>
                        <div class ="form-group">
                            <label for="obs">Observações:</label>
                            <textarea rows ="7" name = "obs" class ="form-control" placeholder = "Observações importantes do projeto"><?php echo preg_replace("/[\r\n]+/", "\n", $projeto->getObservacoes());?></textarea>
                        </div>
                         <div class ="form-group">
                            <label for="estagio">Estágio:</label>
                            <select name ="estagio" class ="form-control" value = "">
                                
                                <?php
                                $estagio = $projeto->getEstagio();
                                $htmlString = '';
                                $htmlString .= '<option value ="Desenvolvimento"';
                                if($estagio == 'Desenvolvimento')
                                {
                                    $htmlString .= ' selected';
                                }
                                $htmlString .= '>Desenvolvimento</option>';
                                
                                $htmlString .= '<option value ="Entrege"';
                                if($estagio == 'Entrege')
                                {
                                    $htmlString .= ' selected';
                                }
                                $htmlString .= '>Entrege</option>';
                                
                                
                                $htmlString .= '<option value ="Manutenção"';
                                if($estagio == 'Manutenção')
                                {
                                    $htmlString .= ' selected';
                                }
                                $htmlString .= '>Manutenção</option>';
                                
                                echo $htmlString;
                                
                                ?>
                            </select>
                        </div>   
                         <span class = "form-aviso"></span>
                        <div class="row">
                           <div class="col-md-3"></div>
                           <div class="col-md-6 text-center">                         
                              <button type="submit" class="btn btn-primary btn-lg btn-block">Editar Projeto</button>
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
                    <form id="pesquisar-usuario-dev-form" class = "form-inline" onsubmit="return false" data-mostrarhabilidades="">
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
            
            $(document).ready(function()
            {
                

            var resultadoDeUsuarioArray = null;

            function CarregarDevs(devs, add, mostrarhabilidades, master)
            {
                var classebtnescolher;
                
                if(master == true)
                {
                    classebtnescolher = 'btn-modal-escolher-master';
                }
                else
                {
                    classebtnescolher = 'btn-modal-escolher-dev'
                }
                
                if (add == true)
                {
                    devs = devs.filter(function(el)
                    {
                        var tem = false;
                        $(".hidden-dev-input").each(function(index)
                        {
                            var hiddenval = $(this).val();
                            if (hiddenval == el.id)
                            {
                                tem = true;
                            }
                        });

                        return tem == false;
                    });


                }

                var htmlString = '';
                var totalUsuarios = Object.keys(devs).length;
                for (var i = 0; i < totalUsuarios; i++)
                {
                    htmlString += '<img src ="../' + devs[i].foto + '" class = "img-thumbnail" style = "display:block;margin:auto;max-width:150px;"><h3 class = "text-center">' + devs[i].nome + '</h3>';
                    if (mostrarhabilidades == true)
                    {


                        var totalHabilidades = Object.keys(devs[i].habilidades).length;

                        for (var j = 0; j < totalHabilidades; j++)
                        {
                            var valor = devs[i].habilidades[j].valor;
                            var classe;
                            if (valor <= 20)
                            {
                                classe = "progress-bar-danger";
                            }
                            else if (valor <= 40 && valor > 20)
                            {
                                classe = "progress-bar-warning";
                            }
                            else if (valor <= 60 && valor > 40)
                            {
                                classe = "progress-bar-info";
                            }
                            else if (valor <= 80 && valor > 60)
                            {
                                classe = "progress-bar-primary";
                            }
                            else
                            {
                                classe = "progress-bar-success";
                            }
                            var interessadoString = '';

                            if (devs[i].habilidades[j].interesse == true)
                            {
                                interessadoString = '<div class="interessado-icon" title = "Interessado"><div class="text">*</div></div>';
                            }
                            htmlString += '<div class="progress"><div class="progress-bar ' + classe + '" role="progressbar" aria-valuenow="' + valor + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + valor + '%;"><span class="sr-only">' + valor + '% Complete</span></div><span class="progress-type">' + devs[i].habilidades[j].nomehabilidade + '</span><span class="progress-completed">' + valor + '</span>' + interessadoString + '</div></div>';
                        }
                    }
                    
                    var larguraMaster = Object.keys(devs[i].master).length;
                    var larguraDev = Object.keys(devs[i].devs).length;
                    htmlString += "<h4>Projetos atuais</h4>";
                    if(larguraMaster == 0 && larguraDev == 0)
                    {
                        htmlString += "<br />Está envolvido em nenhum projeto";
                    }
                    else
                    {
                        htmlString += '<strong>Como Scrum Master:</strong>';
                        if(larguraMaster == 0)
                        {               
                            htmlString += " Nenhum";
                        }
                        else
                        {
                            var masterFiltrados = new Array();
                            for(var w = 0; w < larguraMaster; w++)
                            {
                                if(devs[i].master[w].estagio != 'Entrege')
                                {
                                    masterFiltrados.push(devs[i].master[w]);
                                }  
                            }
                            
                                
                            var larguraFiltrados = masterFiltrados.length;
                            if(larguraFiltrados == 0)
                            {               
                                htmlString += " Nenhum";
                            }
                            else
                            {
                                for(var w = 0; w < larguraFiltrados; w++)
                                {

                                    htmlString += ' ' + masterFiltrados[w].nomeprojeto;
                                    if(w != larguraFiltrados - 1)
                                    {
                                        if(w == larguraFiltrados - 2)
                                        {
                                            htmlString += ' e';
                                        }
                                        else
                                        {
                                            htmlString += ',';
                                        }
                                    }

                                }
                            }
                            
                        }
                        htmlString += "<br /> <strong>Como Equipe SCRUM:</strong>";
                        if(larguraDev == 0)
                        {               
                            htmlString += "Nenhum";
                        }
                        else
                        {
                            
                            var devsFiltrados = new Array();
                            for(var w = 0; w < larguraDev; w++)
                            {
                                if(devs[i].devs[w].estagio != 'Entrege')
                                {
                                    devsFiltrados.push(devs[i].devs[w]);
                                }  
                            }
                            
                            var larguraFiltrados = devsFiltrados.length;
                            if(larguraFiltrados == 0)
                            {               
                                htmlString += " Nenhum";
                            }
                            else
                            {
                                for(var w = 0; w < larguraFiltrados; w++)
                                {

                                    htmlString += ' ' + devsFiltrados[w].nomeprojeto;
                                    if(w != larguraFiltrados - 1)
                                    {
                                        if(w == larguraFiltrados - 2)
                                        {
                                            htmlString += ' e';
                                        }
                                        else
                                        {
                                            htmlString += ',';
                                        }
                                    }

                                }
                            }
                        }
                    }
                    
                    if (add == true)
                    {
                        htmlString += '<button type = "button" class = "btn btn-primary btn-block '+classebtnescolher+'" data-id="' + devs[i].id + '" data-imgsrc = ' + devs[i].foto + ' data-nome = ' + devs[i].nome + ' >Escolher</button>';

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
                $("#table-devs-conteudo").append('<tr><input type = "hidden" name = "dev[]" class = "hidden-dev-input" value = "' + id + '"><td><img src = "../' + imgsrc + '" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">' + nome + '</span></td><td><a class="btn btn-primary ver-dev-btn" data-id="' + id + '"><em class="fa fa-eye"></em></a><a class="btn btn-danger excluir-dev-btn"><em class="fa fa-trash"></em></a></td></tr>');
                $("#modal-escolher-dev").modal('hide');
            });
            $("#modal-escolher-dev__conteudo").on('click', '.btn-modal-escolher-master', function()
            {
                var imgsrc = $(this).data('imgsrc');
                var nome = $(this).data('nome');
                var id = $(this).data('id');
                $("#table-master-conteudo").html('<tr><input type = "hidden" name = "master" class = "hidden-dev-input" value = "' + id + '"><td><img src = "../' + imgsrc + '" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">' + nome + '</span></td><td><a class="btn btn-primary ver-dev-btn" data-id="' + id + '"><em class="fa fa-eye"></em></a><a class="btn btn-danger excluir-dev-btn"><em class="fa fa-trash"></em></a></td></tr>');
                $("#modal-escolher-dev").modal('hide');
            });


            $("#table-devs-conteudo, #table-master-conteudo").on('click', '.excluir-dev-btn', function()
            {
                $(this).parent().parent().remove();
            });
            $("#table-devs-conteudo").on('click', '.ver-dev-btn', function()
            {
                var id = $(this).data('id');
                var newArray = resultadoDeUsuarioArray.filter(function(el)
                {
                    return el.id == id;
                });
                CarregarDevs(newArray, false, true, false);
            });
             $("#table-master-conteudo").on('click', '.ver-dev-btn', function()
            {
                var id = $(this).data('id');
                var newArray = resultadoDeUsuarioArray.filter(function(el)
                {
                    return el.id == id;
                });
                CarregarDevs(newArray, false, false, false);
            });


            $("#pesquisar-usuario-dev-form").on('submit', function()
            {
                var pesquisa = $("#pesquisar-usuario-dev-form_input").val().toUpperCase();

                var newArray = resultadoDeUsuarioArray.filter(function(el)
                {
                    return el.nome.toUpperCase().match(pesquisa)
                });
                var mh = $(this).attr('data-mostrarhabilidades');
                var mhboolean = false;
                if(mh == 1)
                {
                    mhboolean = true;
                }

                CarregarDevs(newArray, true, mhboolean, !mhboolean);
            });


            
            
            function CarregarJSON(__callback)
            {
                $.ajax(
                {
                    url : '<?php echo UrlManager::GetPathToController("usuario/CarregarJSONCompleto.php"); ?>',
                    method: 'POST',
                    dataType: 'json',
                    success: function(resposta)
                    {
                        resultadoDeUsuarioArray = $.map(resposta, function(el)
                        {
                            return el
                        });


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                    }

                });
               
                if(!isNaN(__callback))
                {
                    __callback();
                }
                
            }
            
            $("#btn-escolher-dev").on('click', function()
            {
                if (resultadoDeUsuarioArray !== null)
                {
                    CarregarDevs(resultadoDeUsuarioArray, true, true, false);
                }
                $("#pesquisar-usuario-dev-form").attr('data-mostrarhabilidades', '1');

            });
            $("#btn-escolher-scrum-master").on('click', function()
            {
                if (resultadoDeUsuarioArray !== null)
                {
                    CarregarDevs(resultadoDeUsuarioArray, true, false, true);
                }
                $("#pesquisar-usuario-dev-form").attr('data-mostrarhabilidades', '0');

            });
            $("#cadastro-projeto-form").on('submit', function()
            {
                var mastercount = $('input[name="master"]').length;
                <?php
                    if(SessionController::IsAdmin())
                    { ?>
                        if(mastercount != 1)
                        {
                            GerarNotificacao("O projeto deve ter um SCRUM Master", 'danger');
                            return;
                        }
                    
                <?php }
                ?>
                
                var devcount = $('input[name="dev[]"').length;
                if(devcount <= 0)
                {
                    GerarNotificacao("O projeto deve ter um membro na equipe SCRUM", 'danger');
                
                    return;
                }
                var form = $(this);
                
                $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("projeto/EditarController.php"); ?>',
                  method : 'POST',
                  data : GerarSerializedParam(form),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                  },
                  
                  success : function(resposta)
                  {
                        GerarNotificacao(resposta.mensagem, resposta.tipo);
                        
                        CarregarJSON();
                  },
                  
                  complete : function()
                  {
                      validando = false;
                      $("button[type='submit']", form).html("Editar Projeto");
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                       GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
                
            });

            $('#inicio, #prazo').datepicker(
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

            $("#inicio, #prazo").on('focus', function()
            {
                $(this).blur();
            });
            CarregarJSON();
                        });
        </script>

    </body>
</html>
