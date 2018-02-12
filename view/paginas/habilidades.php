<?php

if(!SessionController::TemSessao())
{
    header("location: login");
}

$usuario = SessionController::GetUsuario();

$dao = new HabilidadeDAO();



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
             <h1>Habilidades</h1>
              <?php
        $habilidades = $dao->GetHabilidadesUsuario($usuario->getId());
        
        foreach($habilidades as $habilidade)
        {
            //echo '<ul><li>'.$habilidade->getUsuario()->getNome().'</li><li>'.$habilidade->getHabilidade()->getNome().'</li><li>'.$habilidade->getValor().'</li></ul>';
            
            echo '<div class = "row"><div class = "col-md-12"><h3>'.$habilidade->getHabilidade()->getNome().'</h3><div class = "habilidade-slider" data-habilidade-id = "'.$habilidade->getHabilidade()->getId().'" data-habilidade-valor = "'.$habilidade->getValor().'"><div class="ui-slider-handle habilidade-slider-handle"></div></div></div></div>';
            
        }
        
        ?>
             
             <button class ="btn btn-block btn-primary" id = "btn-salvar-habilidades" style = "margin-top: 20px" type = "button">Salvar Habilidades</button> 
        </div>
        
        <?php Carregador::CarregarViewFooter(); ?>
        <script>
            $(".habilidade-slider").each(function(index)
            {
                var handle = $(".habilidade-slider-handle", $(this));
                $(this).slider({range : "min", max : 100, min : 0, value : $(this).data('habilidade-valor'),slide: function( event, ui ) {handle.text( ui.value );}, create : function() {  handle.text( $( this ).slider( "value" ) ); }});
                
            });
            $("#btn-salvar-habilidades").on('click', function()
            {
                var btn = $(this);
                btn.attr('disabled', true);
                var habilidadesValor = [];
                
                $(".habilidade-slider").each(function(index)
                {
                    var val = $(this).slider("option", "value");
                    var id = $(this).data('habilidade-id');
                    habilidadesValor.push({id : id, valor : val});
                });
                 $.ajax({
                       url : 'controller/habilidades/atualizar.php',
                       method : 'POST',
                        dataType: "json",
                       data : {info : habilidadesValor, id : <?php echo SessionController::GetUsuario()->getId(); ?>},
                       
                       success : function(resposta)
                       {
                           if(resposta.tipo == "sucesso")
                            {
                                 GerarNotificacao(resposta.mensagem, 'success');
                            }

                            else
                            {
                                GerarNotificacao(resposta.mensagem, 'danger');
                            }
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
         
        </script>
    </body>
</html>
