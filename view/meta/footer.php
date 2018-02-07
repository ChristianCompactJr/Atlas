<footer>
    <div class="pull-right hidden-xs">
       <b>Versão</b> <?php echo PROJECT_VERSION ?>
       <a href = "#" class = "link" data-toggle="modal" data-target="#modalSuporte" >Contactar Suporte</a>
    </div>
    <strong>Copyright © <?php echo date("Y"); ?> <a href = "http://www.compactjr.com" class = "link" >Compact Jr.</a></strong> Todos os Direitos Reservados.
</footer>

<div class="modal fade" id="modalSuporte" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Suporte</h4>
        </div>
        <div class="modal-body">
         <div class = "row">
            <div class = "col-md-1">
            </div>
            <div class ="col-md-10 col-sm-12">
               <form onsubmit="return false" id = "form-enviar_suporte">
                   <div class="form-group">
                      <label for="email">Email: </label>
                     <input type="email" name = "email" class="form-control" placeholder="Digite seu email para podermos lhe responder" required>
                  </div>
                  <div class="form-group">
                      <label for="assunto">Assunto: </label>
                     <input type="text" name = "assunto" class="form-control" placeholder="Digite aqui o assunto" required>
                  </div>
                  <div class="form-group">
                     <label for="mensagem">Mensagem:</label>
                     <textarea class="form-control" name = "mensagem" rows="7" placeholder = "Digite aqui sua mensagem" required></textarea>
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
            var validandoEmail = false;
           $("#form-enviar_suporte").on("submit", function()
           {
               if(validandoEmail === true){
                   return;
               }
               validandoEmail = true;
               var form = $(this);
               
               $.ajax({
                  
                  url : 'controller/email/enviarEmailSuporte.php',
                  method : 'POST',
                  data : form.serialize(),
                  dataType : 'json',
                  beforeSend : function()
                  { 
                    $("button[type='submit']", form).html("Enviando...");
                  },
                  
                  success : function(resposta)
                  {
                      alert(resposta.mensagem)
                  },
                  
                  complete : function()
                  {
                      validandoEmail = false;
                      $("button[type='submit']", form).html("Enviar");
                  },
                  error : function(jqXHR, textStatus, errorThrown)
                  {
                        alert(jqXHR.responseText);
                  }           
               });
               
           });
</script>