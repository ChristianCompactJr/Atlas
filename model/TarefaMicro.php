<?php

class TarefaMicro {
    private $id;
    private $macro;
    private $nome;
    private $descricao;
    private $observacoes;
    private $linkUteis;
    private $prioridade;
    private $estimativa;
    private $estado; //Incompleta, Instável ou Qualificada
    
    
    function __construct($id, $macro, $nome, $descricao, $observacoes, $linkUteis, $prioridade, $estimativa, $estado) {
        $this->id = $id;
        $this->macro = $macro;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->observacoes = $observacoes;
        $this->linkUteis = $linkUteis;
        $this->prioridade = $prioridade;
        $this->estimativa = $estimativa;
        $this->estado = $estado;
    }

        
    
    public function toArray()
    {
        return array('id' => $this->id, 'macro' => $this->macro, 'nome' => $this->nome, 'descricao' => $this->getDescricaoFormatted(), 'descricaoUnformatted' => $this->descricao, 'observacoes' => $this->getObservacoesFormatted(), 'observacoesUnformatted' => $this->observacoes ,'links' => $this->getLinksUteisFormatted(), 'linksUnformatted' => $this->linkUteis, 'estimativa' => $this->estimativa, 'estadocolored' => $this->getEstadoColored() ,'prioridade' => $this->prioridade, 'estado' => $this->estado);
    }
    
        function getObservacoes() {
        return $this->observacoes;
    }

       function getEstado() {
        return $this->estado;
    }
    
    function getEstadoColored()
    {
        if($this->estado == 'Incompleta')
        {
            $classe = 'danger';
        }
        else if($this->estado == 'Qualificada')
        {
            $classe = 'success';
        }
        else if($this->estado == 'Instável')
        {
            $classe = 'warning';
        }
        else
        {
            $classe = 'danger';
        }
        
        return '<span class = "text-'.$classe.'"><b>'.$this->estado.'</b></span>';
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }
    function getPrioridade() {
        return $this->prioridade;
    }

    function getEstimativa() {
        return $this->estimativa;
    }

    function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }

    function setEstimativa($estimativa) {
        $this->estimativa = $estimativa;
    }
    
    function getId() {
        return $this->id;
    }

    function getMacro() {
        return $this->macro;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    public function getDescricaoFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->descricao);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
    }
    public function getObservacoesFormatted()
    {
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->observacoes);
          return str_replace(array("\r\n", "\r", "\n"), "<br /><br />", $texto);
    }
    
    function getTempoPrevisto() {
        return $this->tempoPrevisto;
    }

    function getLinkUteis() {
        return $this->linkUteis;
    }
    
    public function getLinksUteisFormatted()
    {
        $retorno = '';
        $texto =  preg_replace("/[\r\n]+/", "\n", $this->linkUteis);
        $texto = str_replace(array("\r\n", "\r", "\n"), "<br />", $texto);
        $linhas = explode('<br />', $texto);
        foreach($linhas as $linha)
        {
            $palavras = explode(' ', $linha);
            
            foreach($palavras as $palavra)
            {
                if(filter_var($palavra, FILTER_VALIDATE_URL))
                {
                    $retorno .= '<a href = "'.$palavra.'" target = "_blank" class = "link">'.$palavra."</a>";
                }
                else
                {
                    $retorno .= $palavra;
                }
                $retorno .= " ";
            }
            $retorno .= "<br />";
        }
        $retorno = preg_replace('#(<br */?>\s*)+#i', '<br />', $retorno);
        return $retorno;
        
        
        
       
    }

    function getConcluida() {
        return $this->concluida;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setMacro($macro) {
        $this->macro = $macro;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTempoPrevisto($tempoPrevisto) {
        $this->tempoPrevisto = $tempoPrevisto;
    }

    function setLinkUteis($linkUteis) {
        $this->linkUteis = $linkUteis;
    }

    function setConcluida($concluida) {
        $this->concluida = $concluida;
    }



    
}
