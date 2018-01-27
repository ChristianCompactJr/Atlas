<?php
   if(SessionController::TemSessao())
   {
       header("location: projeto");
   }

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <title>Login - Atlas</title>
      <?php Carregador::CarregarViewHeadMeta() ?>
   </head>
   <body>
      <div class="py-5">
         <div class="container">
            <div class="row">
               <div class="col-md-12 text-center">
                  <img style = "margin:auto; width:50%;max-width: 360px" src="recursos/img/logo_escrito.png">
                  <h2 style = "text-align:center; margin:15px 0;">Gerenciador de SCRUM</h2>
               </div>
            </div>
         </div>
      </div>
      <div class = "container"  id = "loginMain">
         <div class = "row">
            <div class = "col-md-12">
               <form id = "Login-Form" onsubmit="return false">
                  <div class="form-group">
                     <label for="email">Email:</label>
                     <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
                  </div>
                  <div class="form-group">
                     <label for="senha">Senha:</label>
                     <input type="password" name="senha" class="form-control"  placeholder="********" required>
                  </div>
                    <div class="form-group">
                     <label class="checkbox" for = "lembrar" style = "margin-left:20px">
                        <input type="checkbox" style ="margin-left: -20px" value="lembrar" name = "lembrar"> Lembrar de mim
                    </label> 
                    </div>
                    
                  <span style ="color:red" id = "loginAviso"></span>
                  <div class="row">
                     <div class="col-md-3"></div>
                     <div class="col-md-6 text-center">                         
                        <button type="submit" id="loginFormButton" class="btn btn-primary btn-lg btn-block">Entrar</button>
                     </div>
                  </div>
                  <span><a href = "#" class = "link">Esqueci minha senha</a></span>
                  
               </form>
            </div>
         </div>
      </div>
       <?php Carregador::CarregarViewFooter(); ?>
       <script type ="text/javascript">
           var validando = false;
           $("#Login-Form").on("submit", function()
           {
               if(validando === true){
                   return;
               }
               validando = false;
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/usuario/LoginController.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                  },
                  
                  success : function(resposta)
                  {
                      console.log(resposta);
                      if(resposta.tipo == "sucesso")
                      {
                          window.location.href = "projeto";
                      }
                      else
                      {
                          $("#loginAviso").html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      $("button[type='submit']", form).html("Entrar");
                  }
                  
                  
                  
               });
               
           });
        </script>
   </body>
</html>