<?php
    abstract class EnviadorEmail
    {
        private static $destino = "christian@compactjr.com";
        
        public static function EnviarEmail($assunto, $mensagem, $remetente = 'christian@compactjr.com')
        {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <'.$remetente.'>' . "\r\n";
           if(!@mail(self::$destino, $assunto, $mensagem, $headers))
           {
               throw new Exception ("Houve um erro ao enviar a mensagem.");
           }
                
        }
    }

?>
