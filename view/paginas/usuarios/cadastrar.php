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
            <h1>Cadastrar Usuários</h1>
            <div class = "row">
                <div class = "col-md-12">
                    <form id ="cadastro-usuario-form" enctype="multipart/form-data" onsubmit="return false">
                        <div class ="form-group">
                            <label for="nome">Nome:</label>
                            <input type ="text" name = "nome" class ="form-control" placeholder = "Nome do usuário" required>
                        </div>
                        <div class ="form-group">
                            <label for="email">Email:</label>
                            <input type ="email" name ="email" class ="form-control" placeholder = "Email do usuário" required>
                        </div>
                        <div class ="form-group">
                            <label for="nome">Senha:</label>
                            <input type ="password" name = "senha" id ="senha" class ="form-control" placeholder = "A senha do usuário" required>
                        </div>
                        <div class ="form-group">
                            <label for="password">Confirmação da senha:</label>
                            <input type ="password" name ="confsenha" id ="confsenha" class ="form-control" placeholder = "Confirmação da senha do usuário" required>
                        </div>
                        <div class ="form-group">
                            <label for="foto">Foto de perfil (Não é obrigatório. Tamanho máximo de <?php echo EnviadorArquivos::GetMaxUploadSize() /1000000; ?>MB):</label>
                            <input type ="file" name ="foto" class ="form-control" placeholder = "Foto de perfil do usuário">
                        </div>
                        <div class ="form-group">
                            <label class="checkbox" for = "administrador" style = "margin-left:20px">
                                <input type="checkbox" style ="margin-left: -20px" value="lembrar" name = "administrador">Marque se o usuário é administrador
                            </label> 
                        </div>
                         <span class = "form-aviso"></span>
                        <div class="row">
                           <div class="col-md-3"></div>
                           <div class="col-md-6 text-center">                         
                              <button type="submit" class="btn btn-primary btn-lg btn-block">Cadastrar</button>
                           </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        <script type ="text/javascript">
            var validandoCadastro = false;
           $("#cadastro-usuario-form").on("submit", function()
           {
               if(validandoCadastro === true){
                   return;
               }
               validandoCadastro = true;
               var formData = new FormData(this);
               var form = $(this);
               if($("#confsenha").val() !== $("#senha").val())
                {
                    $(".form-aviso", form).html("A confirmação de senha não é igual a nova senha");
                    validandoCadastro = false;
                    return;
                }
               $.ajax({
                  
                  url : '../controller/usuario/CadastroController.php',
                  method : 'POST',
                  data : formData,
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Cadastrando...");
                    $(".form-aviso", form).html("");
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                          alert("Usuário cadastrado com sucesso");
                      }
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      validandoCadastro = false;
                      $("button[type='submit']", form).html("Cadastrar");
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
