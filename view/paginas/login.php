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
               <form id = "loginForm" onsubmit="return false">
                  <div class="form-group">
                     <label for="email">Email:</label>
                     <input type="email" name="email" class="form-control" placeholder="Digite email">
                  </div>
                  <div class="form-group">
                     <label for="senha">Senha:</label>
                     <input type="password" name="senha" class="form-control"  placeholder="********">
                  </div>
                    <div class="form-group">
                     <label class="checkbox" style = "margin-left:20px">
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
                  <span><a href = "#" class = "link" data-dismiss="modal" data-toggle="modal" data-target="#modalLogin">Esqueci minha senha</a></span>
                  
               </form>
            </div>
         </div>
      </div>
   </body>
</html>