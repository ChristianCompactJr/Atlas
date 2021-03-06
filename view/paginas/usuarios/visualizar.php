<?php

if(!SessionController::IsAdmin())
{
    header("location: login");
}
$resultadosPorPagina = 7;
$dao = new UsuarioDAO();
$totalpaginas = ceil($dao->GetTotalUsuarios() / $resultadosPorPagina);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Usuários - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?> 
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        
        <div class ="container">
             <h1>Visualizar Usuários</h1>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-6 col-sm-12">
                    <div id="imaginary_container"> 
                        <form class="input-group form-group stylish-input-group" onsubmit="return false" id = "pesquisa-bar">
                            <input type="text" class="form-control" id ="pesquisa-bar-input" placeholder="Pesquisar por nome" >
                            <span class="input-group-addon">
                                <button type="submit">
                                    <em class="fa fa-search"></em>
                                </button>  
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class ="container">
            <div class ="row">
                <div class ="col-md-12">
                    <div class ="table-responsive">
                        <table class ="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Administrador</th>
                                    <th>Ativo</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id = "table-conteudo">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <ul class="pagination pull-right" id = "div-paginacao">
              
            </ul>
        </div>
       
</div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        <script>
             var cacheLike = '';   
             var loader = '<div class="container"><div class="row"> <div id="loader"> <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="lading"></div></div></div>'; 
             var resultadosPorPaginas = <?php echo $resultadosPorPagina; ?>;
             var totalPaginas = <?php echo $totalpaginas; ?>;
             var paginaAtual = 1;
               var totalResposta;
               var resultado;
               
               ConstruirPaginacao(totalPaginas);
               CarregarUsuarios(0, resultadosPorPaginas, '');
            function CarregarUsuarios(inicio, limite, like){
                var data = {inicio: inicio, limite : limite, like : like};
                AdicionarCSRFTokenObj(data); 
                 $.ajax({
                    url : '<?php echo UrlManager::GetPathToController("usuario/PaginacaoController.php"); ?>',
                    method : 'POST',
                    data : data,
                    dataType : 'json',
                    
                    beforeSend : function()
                    {
                        $("#table-conteudo").html(loader);
                        
                        if(cacheLike != like)
                        {
                            paginaAtual = 1;
                        }
                    },
                    
                    success : function (resposta)
                    {
                        resultado = resposta.usuarios;
                        var htmlString = '';
                        totalResposta = Object.keys(resposta.usuarios).length;
                        for(var i = 0; i < totalResposta; i++)
                        {
                            var administradorString;
                            if(resposta.usuarios[i].administrador == 0)
                            {
                                administradorString = "Não";
                            }
                            else
                            {
                                administradorString = "Sim";
                            }
                            
                            var ativoString;
                            if(resposta.usuarios[i].ativo == 0)
                            {
                                ativoString = "Não";
                            }
                            else
                            {
                                ativoString = "Sim";
                            }
                            
                            htmlString += '<tr><td><img src = "../'+resposta.usuarios[i].foto+'" class = "img-thumbnail" style = "max-width:50px; margin-right:10px;"><span class = "visualizar-td-nome">'+resposta.usuarios[i].nome+'</span></td><td>'+resposta.usuarios[i].email+'</td><td>'+administradorString+'</td><td>'+ativoString+'</td><td><a class="btn btn-primary ediar-usuario-btn" data-usuario = "'+resposta.usuarios[i].id+'"><em class="fa fa-edit"></em></a><a class="btn btn-primary editar-habilidades-btn" data-usuario = "'+resposta.usuarios[i].id+'"><em class="fa fa-graduation-cap"></em></a><a class="btn btn-danger excluir-usuario-btn" data-usuario = "'+resposta.usuarios[i].id+'"><em class="fa fa-trash"></em></a></td></tr>';
                        }
                        var totalPaginasResposta = Math.ceil (resposta.total / resultadosPorPaginas);
                 
                 
          
                        if(totalPaginasResposta != totalPaginas)
                        {
                            ConstruirPaginacao(totalPaginasResposta);
                        }

                        $("#table-conteudo").html(htmlString);
                        cacheLike = like;
                    },
                    
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
                  } 
                });
        }
        function ConstruirPaginacao(quantidade)
        {
            var htmlString = '';
            
            for(var i = 1; i <= quantidade; i++)
            {
                htmlString += '<li><a href="#" class = "pagination_pagina" data-idpagina="'+i+'">'+i+'</a></li>';
            }
            $("#div-paginacao").html(htmlString);
            $("#div-paginacao li.active").removeClass('active');
            $("#div-paginacao li:nth-child("+paginaAtual+")").addClass('active');
        }
        
        $("#div-paginacao").on('click', '.pagination_pagina', function()
        {
            if($(this).data('idpagina') == paginaAtual)
            {
                return;
                
            }
            $("#div-paginacao li.active").removeClass('active');
            $(this).parent().addClass("active");
            paginaAtual = $(this).data('idpagina');
            
            CarregarUsuarios((paginaAtual - 1) * resultadosPorPaginas , resultadosPorPaginas, cacheLike);
        });
        
        
        $("#table-conteudo").on('click', '.editar-habilidades-btn', function()
        {
            var id = $(this).data('usuario');
            var data = {id : id};
            AdicionarCSRFTokenObj(data); 
             $.ajax({
             data : data,
             url : '<?php echo UrlManager::GetPathToController("habilidades/habilidadesUsuarioJSON.php"); ?>',
             method : 'POST',
             dataType : 'json',
             
             success : function(resposta)
             {
                htmlString = '';
                for(var j = 0; j < Object.keys(resposta).length; j++)
                {
                    htmlString += '<div class = "col-md-12"><h3>'+resposta[j].nomehabilidade+'</h3><div class = "habilidade-slider" data-habilidade-id = "'+resposta[j].idhabilidade+'" data-habilidade-valor = "'+resposta[j].valor+'"><div class="ui-slider-handle habilidade-slider-handle"></div></div> <div class="checkbox"><label><input type="checkbox" class = "habilidade-interesse-input" data-habilidade = "'+resposta[j].idhabilidade+'" ';
                    
                    if(resposta[j].interesse == true)
                    {
                        htmlString += 'checked';
                    }
                    
                    htmlString += '>Tem interesse nesta habilidade</label></div></div>';
                    
                }
                alterarHabilidadeUsuarioId = id;
                $("#habilidades-editar-conteudo").html(htmlString);
                
                $(".habilidade-slider").each(function(index)
                {
                    
                    var handle = $(".habilidade-slider-handle", $(this));
                $(this).slider({range : "min", max : 100, min : 0, value : $(this).data('habilidade-valor'),slide: function( event, ui ) {handle.text( ui.value );}, create : function() {  handle.text( $( this ).slider( "value" ) ); }});
                 
                });
                
                
                $("#modalEditarHabilidades").modal('show');
                
                 
             },
             
             error : function(jqXHR, textStatus, errorThrown)
            {
                GerarNotificacao(jqXHR.responseText, "danger");
            } 
             
           });
        });
        
        $("#table-conteudo").on('click', '.ediar-usuario-btn', function()
        {
            var usuario;
            var dataid = $(this).data('usuario');
            for(var i = 0; i < totalResposta; i++)
            {
                if(resultado[i].id == dataid)
                {
                    usuario = resultado[i];
                    break;
                }
            }
            
            $("#modalEditarUsuario .modal-title").html(usuario.nome);
            $("#modalEditarUsuario-img").attr('src', '../'+usuario.foto);
            $(".alterar-hidden-id").val(usuario.id);
            $("#modalEditarUsuario-nome").val(usuario.nome);
            $("#modalEditarUsuario-email").val(usuario.email);
            $("#modalEditarUsuario-novasenha").val("");
            $("#modalEditarUsuario-confsenha").val("");
            $("#modalEditarUsuario-foto").val('');
            
            if(usuario.administrador == true)
            {
                $("#modalEditarUsuario-administrador").attr("checked", true);
            }
            else
            {
                $("#modalEditarUsuario-administrador").attr("checked", false);
            }
            if(usuario.ativo == true)
            {
                $("#modalEditarUsuario-ativo").attr("checked", true);
            }
            else
            {
                $("#modalEditarUsuario-ativo").attr("checked", false);
            }
            
            $('#modalEditarUsuario').modal('show'); 

            
        });
        $("#table-conteudo").on('click', '.excluir-usuario-btn', function()
        {
            var btn = $(this);
            var btnid = btn.data('usuario');
            var excluir = function(){
            var id = btnid;
            var data = {id : id};
            AdicionarCSRFTokenObj(data);
           $.ajax({
             data : data,
             url : '<?php echo UrlManager::GetPathToController("usuario/ApagarController.php"); ?>',
             method : 'POST',
             dataType : 'json',
             
             success : function(resposta)
             {
                 GerarNotificacao(resposta.mensagem, resposta.tipo);
                 
                if(resposta.tipo == "success")
                {
                    btn.parent().parent().remove();
                }
                 
                 
             },
             
             error : function(jqXHR, textStatus, errorThrown)
            {
                GerarNotificacao(jqXHR.responseText, "danger");
            } 
             
           });
        };
         GerarConfirmacao("Tem certeza que deseja apagar o usuário " + $("td:first-child .visualizar-td-nome", btn.parent().parent()).html(),  excluir);
    });
        $("#pesquisa-bar").on('submit', function()
        {
            var pesquisa = $("#pesquisa-bar-input").val();
            
            CarregarUsuarios(0, resultadosPorPaginas, pesquisa);
            
            ConstruirPaginacao(totalResposta);
        });
        
        
       
        </script>
     
    <div class="modal fade" id="modalEditarUsuario" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
         <div class = "row">
            <div class = "col-md-1">
            </div>
            <div class ="col-md-10 col-sm-12">
                
                <img src ="" id ="modalEditarUsuario-img" class = "img-responsive" style = "max-width: 200px; display:block; margin:auto;">
                <form onsubmit="return false" id = "alterar-nome">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="nome">Nome: </label>
                       <input type="text" name = "nome" id="modalEditarUsuario-nome" class="form-control" placeholder="Novo nome do usuário" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar nome</button>
                    </div>
                </form>
                <hr />
                <form onsubmit="return false" id = "alterar-email">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="email">Email: </label>
                       <input type="text" name = "email" id="modalEditarUsuario-email" class="form-control" placeholder="Novo email do usuário" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar Email</button>
                    </div>
                </form>
                <hr />
                <form onsubmit="return false" id = "alterar-senha">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="senha">Nova senha: </label>
                       <input type="password" name = "senha" id="modalEditarUsuario-novasenha" class="form-control" placeholder="********" required>
                       <div class="pwstrength_viewport_progress"></div>
                    </div>
                    <div class="form-group">
                      <label for="confsenha">Confirmação da nova senha: </label>
                       <input type="password" name = "confsenha" id="modalEditarUsuario-confsenha" class="form-control" placeholder="********" required>
                       
                    </div>
                    <span class = "form-aviso"></span>
                    <button type="submit" class="btn btn-success">Alterar senha</button> 
                </form>
                <hr />
                <form onsubmit="return false" enctype="multipart/form-data" id = "alterar-foto">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="foto">Foto de perfil (tamanho máximo de <?php echo EnviadorArquivos::GetMaxUploadSize() /1000000; ?>MB): </label>
                       <input type="file" name = "foto" id="modalEditarUsuario-foto" class="form-control" placeholder="Nova foto de perfil" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar foto de perfil</button>
                    </div>
                </form>
                 <hr />
                <form onsubmit="return false" id = "alterar-administrador">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" id="modalEditarUsuario-administrador">Administrador
                        </label>
                    </div>
                    <span class = "form-aviso"></span>
                    <button type="submit" class="btn btn-success">Alterar Administração</button>
                    
                    
                </form>
                  <hr />
                <form onsubmit="return false" id = "alterar-ativo">
                    <input type ="hidden" name ="id" class = "alterar-hidden-id">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" id="modalEditarUsuario-ativo">Ativo
                        </label>
                    </div>
                    <span class = "form-aviso"></span>
                    <button type="submit" class="btn btn-success">Alterar Ativo</button>
                </form>
            </div>
         </div>
        </div>
        
      </div>
      
    </div>
</div>
        
        
        <div class="modal fade" id="modalEditarHabilidades" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Habilidades</h4>
        </div>
        <div class="modal-body" >

            <div class = "row" id = "habilidades-editar-conteudo" style = "padding:20px">
                
            </div>
            <button class ="btn btn-block btn-primary" id = "btn-salvar-habilidades" style = "margin-top: 20px" type = "button">Salvar Habilidades</button> 
        </div>
        
      </div>
      
    </div>
</div>
        <script>
            var alterarHabilidadeUsuarioId;
            $("#btn-salvar-habilidades").on('click', function()
            {
                var btn = $(this);
                btn.attr('disabled', true);
                var habilidadesValor = [];
                
                $(".habilidade-slider").each(function(index)
                {
                    var val = $(this).slider("option", "value");
                    var id = $(this).data('habilidade-id');
                    var interesseInput = $('.habilidade-interesse-input[data-habilidade="'+id+'"]');
                    var interesse;
                    if(interesseInput.is(":checked"))
                    {
                        interesse = true;
                    }
                    else
                    {
                        interesse = false;
                    }
                    habilidadesValor.push({id : id, valor : val, interesse : interesse});
                });
                var data = {info : habilidadesValor, id : alterarHabilidadeUsuarioId};
                AdicionarCSRFTokenObj(data);
                 $.ajax({
                        url : '<?php echo UrlManager::GetPathToController("habilidades/atualizar.php"); ?>',
                        method : 'POST',
                        dataType: "json",
                       data : data,
                       
                       success : function(resposta)
                       {
                           GerarNotificacao(resposta.mensagem, resposta.tipo);
                       },
                       complete : function()
                        {
                            btn.attr('disabled', false);
                        },
                       error : function(jqXHR, textStatus, errorThrown)
                         {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                        } 
                    });
            });
            
            
            $("#alterar-nome").on("submit", function()
           {
               var form = $(this);
               
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarNome.php"); ?>',
                  method : 'POST',
                  data : GerarSerializedParam(form),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                      if(resposta.tipo == "success")
                      {
                           var id = $(".alterar-hidden-id", form).val();
                           var linha =  $('#table-conteudo a[data-usuario="'+id+'"]').parent().parent();
                           $("td:nth-child(1) .visualizar-td-nome", linha).html($("#modalEditarUsuario-nome").val());
                      }
                     
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Email");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
           $("#alterar-email").on("submit", function()
           {
               var form = $(this);
               
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarEmail.php"); ?>',
                  method : 'POST',
                  data : GerarSerializedParam(form),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                      if(resposta.tipo == "success")
                      {
                           var id = $(".alterar-hidden-id", form).val();
                           var linha =  $('#table-conteudo a[data-usuario="'+id+'"]').parent().parent();
                           $("td:nth-child(2)", linha).html($("#modalEditarUsuario-email").val());
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Email");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
           
           $("#alterar-senha").on("submit", function()
           {
               var form = $(this);
               $(".form-aviso", form).html("");
               
               if($("#modalEditarUsuario-novasenha").val() !== $("#modalEditarUsuario-confsenha").val())
               {
                   $(".form-aviso", form).html("A senha e a confirmação da senha não conferem.");
                   return;
               }
               
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarSenha.php"); ?>',
                  method : 'POST',
                  data : GerarSerializedParam(form),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Senha");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
           
           $("#alterar-foto").on("submit", function()
           {
               var form = $(this);
               var formData = GerarFormDataFormulario(this);
               
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarFoto.php"); ?>',
                  method : 'POST',
                  data : formData,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                      if(resposta.tipo == "success")
                      {
                           $("#modalEditarUsuario-img").attr('src', "../"+resposta.novafoto);
                           var id = $(".alterar-hidden-id", form).val();
                           var linha =  $('#table-conteudo a[data-usuario="'+id+'"]').parent().parent();
                           $("td:nth-child(1) img", linha).attr('src', "../"+resposta.novafoto);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar foto de perfil");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  },
                  cache: false,
        contentType: false,
        processData: false,
               });
               
           });
           
           $("#alterar-administrador").on("submit", function()
           {
               var form = $(this);
               var administrador;
               var id = $(".alterar-hidden-id", form).val();
               if($("#modalEditarUsuario-administrador").is(":checked"))
               {
                   administrador = true;
               }
               else
               {
                   administrador = false;
               }
               var data = {id : id, administrador : administrador};
            AdicionarCSRFTokenObj(data); 
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarAdministrador.php"); ?>',
                  method : 'POST',
                  data : data,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                      
                      if(resposta.tipo == "success")
                      {
                           var id = $(".alterar-hidden-id", form).val();
                           var linha =  $('#table-conteudo a[data-usuario="'+id+'"]').parent().parent();
                           var texto;
                           if(administrador == true)
                           {
                               texto = 'Sim';
                               $("#modalEditarUsuario-ativo").attr("checked", true);
                               $("td:nth-child(4)", linha).html(texto);
                           }
                           else
                           {
                               texto = 'Não';
                           }
                           
                           $("td:nth-child(3)", linha).html(texto);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar administrador");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
           
           $("#alterar-ativo").on("submit", function()
           {
               var form = $(this);
               var ativo;
               var id = $(".alterar-hidden-id", form).val();
               if($("#modalEditarUsuario-ativo").is(":checked"))
               {
                   ativo = true;
               }
               else
               {
                   ativo = false;
               }
               var data = {id : id, ativo : ativo};
            AdicionarCSRFTokenObj(data); 
               $.ajax({
                  url : '<?php echo UrlManager::GetPathToController("usuario/alterar/AlterarAtivo.php"); ?>',
                  method : 'POST',
                  data : data,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      GerarNotificacao(resposta.mensagem, resposta.tipo);
                      
                      if(resposta.tipo == "success")
                      {
                           var texto;
                           var id = $(".alterar-hidden-id", form).val();
                           var linha =  $('#table-conteudo a[data-usuario="'+id+'"]').parent().parent();
                           $("td:nth-child(4)", linha).html(texto);
                           
                           if(ativo == true)
                           {
                               texto = 'Sim';
                           }
                           else
                           {
                               texto = 'Não';
                               $("#modalEditarUsuario-administrador").attr("checked", false);
                               $("td:nth-child(3)", linha).html(texto);
                           }
                           $("td:nth-child(4)", linha).html(texto);
                      }
                     
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar ativo");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
        </script>
    </body>
</html>
