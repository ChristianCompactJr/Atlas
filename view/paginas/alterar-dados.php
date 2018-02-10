<?php

if(!SessionController::TemSessao())
{
    header("location: login");
}

$usuario = SessionController::GetUsuario();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Alterar Dados - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>    
    </head>
    <body>
        <?php Carregador::CarregarViewNavbar(); ?>  
        <div class ="container">
             <h1>Alterar dados</h1>
 
            <div class = "row">
            <div class ="col-md-12 col-sm-12">
                
                <img src ="" id ="modalEditarUsuario-img" class = "img-responsive" style = "max-width: 200px; display:block; margin:auto;">
                <form onsubmit="return false" id = "alterar-nome">
                    <input type ="hidden" name ="id" value ="<?php echo $usuario->getId(); ?>" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="nome">Nome: </label>
                       <input type="text" name = "nome" value ="<?php echo $usuario->getNome(); ?>" id="modalEditarUsuario-nome" class="form-control" placeholder="Novo nome do usuário" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar nome</button>
                    </div>
                </form>
                <hr />
                <form onsubmit="return false" id = "alterar-email">
                    <input type ="hidden" name ="id" value ="<?php echo $usuario->getId(); ?>" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="email">Email: </label>
                       <input type="text" name = "email" value ="<?php echo $usuario->getEmail(); ?>" id="modalEditarUsuario-email" class="form-control" placeholder="Nove email do usuário" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar Email</button>
                    </div>
                </form>
                <hr />
                <form onsubmit="return false" id = "alterar-senha">
                    <input type ="hidden" name ="id" value ="<?php echo $usuario->getId(); ?>" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="senha">Nova senha: </label>
                       <input type="password" name = "senha" id="modalEditarUsuario-novasenha" class="form-control" placeholder="********" required>
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
                    <input type ="hidden" name ="id" value ="<?php echo $usuario->getId(); ?>" class = "alterar-hidden-id">
                    <div class="form-group">
                      <label for="foto">Foto de perfil (tamanho máximo de <?php echo EnviadorArquivos::GetMaxUploadSize() /1000000; ?>MB): </label>
                       <input type="file" name = "foto" id="modalEditarUsuario-foto" class="form-control" placeholder="Digite seu email para podermos lhe responder" required>
                       <span class = "form-aviso"></span>
                       <button type="submit" class="btn btn-success">Alterar foto de perfil</button>
                    </div>
                </form>
                 <hr />
            </div>
         </div>
        </div>
        <?php Carregador::CarregarViewFooter(); ?>
        <script>
            $("#alterar-nome").on("submit", function()
           {
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/usuario/alterar/AlterarNome.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                           alert(resposta.mensagem);
                          $("#nome-menu").html($("#modalEditarUsuario-nome").val());
                      }
                     
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Email");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
                  } 
               });
               
           });
           $("#alterar-email").on("submit", function()
           {
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/usuario/alterar/AlterarEmail.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                           alert(resposta.mensagem);
                          
                      }
                     
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Email");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
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
                  
                  url : 'controller/usuario/alterar/AlterarSenha.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                           alert(resposta.mensagem);
                      }
                     
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar Senha");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
                  } 
               });
               
           });
           
           $("#alterar-foto").on("submit", function()
           {
               var form = $(this);
               var formData = new FormData(this);

               $.ajax({
                  
                  url : 'controller/usuario/alterar/AlterarFoto.php',
                  method : 'POST',
                  data : formData,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                    $("input, textarea, button", form).attr('disabled', true);
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                           alert(resposta.mensagem);
                           $("#modalEditarUsuario-img").attr('src', "../"+resposta.novafoto);
                      }
                     
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Alterar foto de perfil");
                      $("input, textarea, button", form).attr('disabled', false);
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
                  },
                  cache: false,
        contentType: false,
        processData: false,
               });
               
           });
        </script>
    </body>
</html>
