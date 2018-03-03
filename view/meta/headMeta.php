<?php
$voltas = UrlManager::GetBackSlashes();

?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo UrlManager::GetPathToView("recursos/img/logo_limpo.png"); ?>" type="image/png">
<link rel ="stylesheet" type ="text/css" href ="<?php echo UrlManager::GetPathToView("recursos/css/bootstrap.min.css"); ?>" />    
<link rel ="stylesheet" type ="text/css" href ="<?php echo UrlManager::GetPathToView("recursos/css/animate.css"); ?>" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
<link rel ="stylesheet" type ="text/css" href ="<?php echo UrlManager::GetPathToView("recursos/css/style.css"); ?>" />
<script type = "text/javascript" src ="<?php echo UrlManager::GetPathToView("recursos/js/jquery.js"); ?>" ></script>
<script type = "text/javascript" src ="<?php echo UrlManager::GetPathToView("recursos/js/jqueryui.js"); ?>" ></script>
<script type = "text/javascript" src ="<?php echo UrlManager::GetPathToView("recursos/js/bootstrap.min.js"); ?>" ></script>
<script type ="text/javascript" src = "<?php echo UrlManager::GetPathToView("recursos/js/bootstrap-notify.min.js"); ?>"></script>
<script type ="text/javascript" src = "<?php echo UrlManager::GetPathToView("recursos/js/bootbox.min.js"); ?>"></script>
<script type ="text/javascript" src = "<?php echo UrlManager::GetPathToView("recursos/js/font-awesome.js"); ?>"></script>
<script> 
    function GerarNotificacao(mensagem, tipo)
    {
        $.notify({
	
        message: mensagem 
        },{
                // settings
                element: 'body',
	position: null,
	type: tipo,
	allow_dismiss: true,
	newest_on_top: true,
	showProgressbar: false,
	placement: {
		from: "bottom",
		align: "center"
	},
	offset: 20,
	spacing: 10,
	z_index: 10001,
	delay: 3000,
	timer: 1000,
	url_target: '_blank',
	mouse_over: null,
	animate: {
		enter: 'animated fadeInDown',
		exit: 'animated fadeOutUp'
	},
        });
    }
    
    function GerarConfirmacao(mensagem, funcao)
    {
        var retorno;
        bootbox.confirm({ 
  message: mensagem, 
  buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancelar'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirmar'
        }
    },
  callback: function(result){ if(result == true){funcao();}
      }
});
return retorno;
    }
    
    
    
function GerarFormDataFormulario(formulario)
{
    var fm = new FormData(formulario);
    fm.append("csrftoken", "<?php echo SessionController::GetCSRFToken() ?>");
    return fm;
}

function AdicionarCSRFTokenObj(obj)
{
    obj['csrftoken'] = '<?php echo SessionController::GetCSRFToken() ?>';
}

function GerarSerializedParam(formulario)
{
    var retorno = formulario.serializeArray();
  
    retorno.push({'name' : 'csrftoken', 'value' : '<?php echo SessionController::GetCSRFToken() ?>'});
   
    return $.param(retorno);
}

    
</script>