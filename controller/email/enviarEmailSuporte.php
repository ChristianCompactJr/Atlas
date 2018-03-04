<?php

    try {
        EnviadorEmail::EnviarEmail('Suporte: '.$_POST['assunto'], $_POST['mensagem'], $_POST['email']);
       JSONResponder::ResponderSucesso("Mensagem enviada com sucesso", true, true);
    } 
    catch (Exception $ex) {
        JSONResponder::ResponderFalha($e->getMessage(), true, true);
    }
     
     
?>