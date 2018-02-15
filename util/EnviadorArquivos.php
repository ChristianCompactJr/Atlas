<?php

abstract class EnviadorArquivos 
{
    public static $formatosImagemSuportados = array('jpg', 'jpeg', 'png', 'gif');
    
    public static function GetMaxUploadSize() 
    {
        $max_size = -1;

        if ($max_size < 0) 
        {
            $post_max_size = self::parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) 
            {
                $max_size = $post_max_size;
            }

            $upload_max = self::parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) 
            {
                $max_size = $upload_max;
            }
        }
        return $max_size;  
    }
    
    
    
    public static function UploadArquivo($tmp, $destino, $permicoes = 755)
    {
        if($destino == 'uploads/fotos/perfil_padrao.jpg')
        {
            return;
        }
  
        $destino = self::getCaminhoUploads().$destino;
        if(move_uploaded_file($tmp, $destino))
        {
            chmod($destino, $permicoes);
        }
        else 
        {
            throw new Exception("Houve um erro a realizar a transferência do arquivo");
        }
        
    }
    
    public static function ApagarArquivo($arquivo)
    {
        $arquivo = self::getCaminhoUploads().$arquivo;
        if(is_file($arquivo))
        {
            unlink($arquivo);
        }
        
    }
    
    public static function CriarNomeArquivo($diretorio, $extencao, $prefixo = '')
    {
        $ultimoCharDiretorio = substr($diretorio, -1);
        if($ultimoCharDiretorio != '/' && $ultimoCharDiretorio != '\\')
        {
            $diretorio.='/';
        }
        $diretorio = self::getCaminhoUploads().$diretorio;
        $nomearquivo = '';
        do
        {
            $nomearquivo = $prefixo;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 20; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $nomearquivo .= $randomString.'.'.$extencao;
      
        }while(is_file($diretorio.$nomearquivo));
        
        return $nomearquivo;
        
    }
    
    private static function parse_size($size) 
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);

        $size = preg_replace('/[^0-9\\.]/', '', $size);

        if ($unit) 
        {
          return round($size * pow(1000, stripos('bkmgtpezy', $unit[0])));
        }
        else 
        {
          return round($size);
        }
    }
    
    private static function getCaminhoUploads()
    {
        return PROJECT_ROOT."view/paginas/";
    }
    
}
