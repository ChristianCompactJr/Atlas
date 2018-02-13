<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Redefinir Senha - Atlas</title>
        <?php Carregador::CarregarViewHeadMeta(); ?>
    </head>
    <body>
        <div class="py-5">
         <div class="container">
            <div class="row">
               <div class="col-md-12 text-center">
                  <img style = "margin:auto; width:50%;max-width: 360px" src="recursos/img/logo_escrito.png">
                  <h2 style = "text-align:center; margin:15px 0;">Redefinir senha</h2>
               </div>
            </div>
         </div>
      </div>
        <div class = "container"  id = "loginMain">
            <div class = "row">
                <div class = "col-md-12 text-center">
                <?php
                    
                    
                    
                    if((!isset($_GET['u']) || !isset($_GET['chave'])) || (SessionController::TemSessao() && $_GET['u'] !== SessionController::GetUsuario()->getId()))
                    {
                    echo "aqui";
                        echo '<p style = "color:red">Houve um erro ao redefinir a senha. Tente novamente ou contacte o suporte ou um administrador do sistema</p>';
                    }
                    else
                    {
                        $dao = new UsuarioDAO();
                        try
                        {
                            $dao->ValidarEsqueciSenha($_GET['u'], $_GET['chave']);
                            ?>
                           <form id = "Esqueci-form" onsubmit="return false">
	                        <input type="hidden" name="chave" value = "<?php echo $_GET['chave']; ?>">
	                        <input type="hidden" name="id" value = "<?php echo $_GET['u']; ?>">
                                <div class="form-group">
                                   <label for="novasenha">Sua nova senha:</label>
                                   <input type="password" id="novasenha" name="novasenha" class="form-control form-senha" placeholder="********" required>
                                   <div class="pwstrength_viewport_progress"></div>
                                </div>
                                <div class="form-group">
                                   <label for="confsenha">Confirmação da nova senha:</label>
                                   <input type="password" name="confsenha" id="confsenha" class="form-control"  placeholder="********" required>
                                </div>

                                <span class = "form-aviso"></span>
                                <div class="row">
                                   <div class="col-md-3"></div>
                                   <div class="col-md-6 text-center">                         
                                      <button type="submit" id="loginFormButton" class="btn btn-primary btn-lg btn-block">Redefinir senha</button>
                                   </div>
                                </div>
                            </form>
                            <script type = "text/javascript">
                            var validando = false;
                             $("#Esqueci-form").on("submit", function()
                             {
                                 
                                 
                                 if(validando === true){
                                     return;
                                 }
                                 validando = true;
                                  $(".form-aviso", form).html("");
                                 if($("#confsenha").val() !== $("#novasenha").val())
                                 {
                                     $("#esqueci-aviso").html("A confirmação de senha não é igual a nova senha");
                                     validando = false;
                                     return;
                                 }
                                 var form = $(this);

                                 $.ajax({

                                    url : 'controller/usuario/redefinirSenhaEsqueci.php',
                                    method : 'POST',
                                    data : form.serialize(),
                                    dataType : 'json',
                                    beforeSend : function()
                                    { 
                                      $("button[type='submit']", form).html("Validando...");
                                     
                                    },

                                    success : function(resposta)
                                    {
                                        if(resposta.tipo == "sucesso")
                                        {
                                            alert("Senha redefinida com sucesso");
                                            window.location.href = "login";
                                        }
                                        else
                                        {
                                            $(".form-aviso", form).html(resposta.mensagem);
                                        }
                                    },

                                    complete : function()
                                    {
                                        validando = false;
                                        $("button[type='submit']", form).html("Redefinir senha");
                                    }
                                 });

                             });
                             </script>
               
                            
                            
                                                           
                       <?php }
                        catch(Exception $e)
                        {
                            echo '<p style = "color:red">'.$e->getMessage().'</p>';
                        }
                    }
                 ?>
                </div>
            </div>
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
    </body>
</html>