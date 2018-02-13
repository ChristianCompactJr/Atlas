<?php
   if(SessionController::TemSessao())
   {
       header("location: inicial");
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
                    
                  <span class = "form-aviso"></span>
                  <div class="row">
                     <div class="col-md-3"></div>
                     <div class="col-md-6 text-center">                         
                        <button type="submit" id="loginFormButton" class="btn btn-primary btn-lg btn-block">Entrar</button>
                     </div>
                  </div>
                  <span><a href = "#" class = "link" data-toggle="modal" data-target="#modalEsqueci">Esqueci minha senha</a></span>
                  
               </form>
            </div>
         </div>
      </div>
       <?php Carregador::CarregarViewFooter(); ?>
       <div class="modal fade" id="modalEsqueci" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Esqueci minha senha</h4>
                </div>
                <div class="modal-body">
                 <div class = "row">
                     
                    <div class = "col-md-1">
                    </div>
                     
                    <div class ="col-md-10 col-sm-12" id = "conteudo-enviar-esqueci">
                       <form onsubmit="return false" id = "form-enviar_esqueci">
                           <div class="form-group">
                              <label for="email">Digite seu Email para podermos redefinir sua senha: </label>
                             <input type="email" name = "email" class="form-control" placeholder="exemplo@dominio.com" required>
                          </div>

                          <div class="row">
                             <div class="col-md-12 text-center">                         
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar</button>
                             </div>
                          </div>
                       </form>
                    </div>
                 </div>
                </div>

              </div>

            </div>
        </div>
       <script type ="text/javascript">
           var validando = false;
           $("#Login-Form").on("submit", function()
           {
               if(validando === true){
                   return;
               }
               validando = true;
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/usuario/LoginController.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Validando...");
                    $(".form-aviso", form).html("");
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                          window.location.href = "inicial";
                      }
                      else
                      {
                          $(".form-aviso", form).html(resposta.mensagem);
                      }
                  },
                  
                  complete : function()
                  {
                      validando = false;
                      $("button[type='submit']", form).html("Entrar");
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                       GerarNotificacao(jqXHR.responseText, 'danger');
                  } 
               });
               
           });
           
           $("#form-enviar_esqueci").on("submit", function()
           {
               if(validando === true){
                   return;
               }
               validando = true;
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/email/enviarEmailEsqueci.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Enviando...");
                  },
                  
                  success : function(resposta)
                  {
                      if(resposta.tipo == "sucesso")
                      {
                          var htmlString = '<img src = "recursos/img/email.png" class = "img-responsive" style = "display:block;margin:auto;max-width:140px;"> <h3 class = "text-center">Enviamos uma mensagem para seu email</h3><p>Siga as instruções na mensagem para alterar sua senha.<br /><strong>Não se esqueça de checar sua caixa de entrada de span.</strong></p>';
                         $("#conteudo-enviar-esqueci").html(htmlString);
                      }
                      else
                      {
                          GerarNotificacao(resposta.mensagem, 'danger');
                      }
                  },
                  
                  complete : function()
                  {
                      validando = false;
                      $("button[type='submit']", form).html("Enviar");
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