<?php

class TarefaSprint {
    private $id;
    private $sprint;
    private $micro;
    private $responsaveis;
    private $desempenho;
    private $historico_atual;
    private $historico_novo;
    
    
    function __construct($id, $sprint, $micro, array $responsaveis = array(), $desempenho = null, $historico_atual = null, $historico_novo = null) {
        foreach($responsaveis as $responsavel)
        {
            if(!is_a($responsavel, "Usuario"))
            {
                throw new Exception("Apenas objetos usuários podem ser responsáveis");
            }
        }
        $this->id = $id;
        $this->sprint = $sprint;    
        $this->micro = $micro;
        $this->responsaveis = $responsaveis;
        $this->desempenho = $desempenho;
        $this->historico_atual = $historico_atual;
        $this->historico_novo = $historico_novo;
    }
    
    
    function getHistoricoAtualColored()
    {
        if($this->historico_atual == 'Incompleta')
        {
            $classe = 'danger';
        }
        else if($this->historico_atual == 'Qualificada')
        {
            $classe = 'success';
        }
        else if($this->historico_atual == 'Instável')
        {
            $classe = 'warning';
        }
        else
        {
            $classe = 'danger';
        }
        
        return '<span class = "text-'.$classe.'"><b>'.$this->historico_atual.'</b></span>';
    }
    function getHistorico_atual() {
        return $this->historico_atual;
    }

    function getHistorico_novo() {
        return $this->historico_novo;
    }

    function setHistorico_atual($historico_atual) {
        $this->historico_atual = $historico_atual;
    }

    function setHistorico_novo($historico_novo) {
        $this->historico_novo = $historico_novo;
    }

        function getHistoricoNovoColored()
    {
        if($this->historico_novo == 'Incompleta')
        {
            $classe = 'danger';
        }
        else if($this->historico_novo == 'Qualificada')
        {
            $classe = 'success';
        }
        else if($this->historico_novo == 'Instável')
        {
            $classe = 'warning';
        }
        else
        {
            $classe = 'danger';
        }
        
        return '<span class = "text-'.$classe.'"><b>'.$this->historico_novo.'</b></span>';
    }
    
    
    function getMicro() {
        return $this->micro;
    }

    function setMicro($micro) {
        $this->micro = $micro;
    }

        
    public function AdicionarResponsavel(Usuario $responsavel)
    {
        $this->responsaveis[] = $responsavel;
    }
    
    
    function getId() {
        return $this->id;
    }

    function getSprint() {
        return $this->sprint;
    }


    function getResponsaveis() {
        return $this->responsaveis;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSprint($sprint) {
        $this->sprint = $sprint;
    }

    function setResponsaveis($responsaveis) {
        $this->responsaveis = $responsaveis;
    }

    function getDesempenho() {
        return $this->desempenho;
    }

    function setDesempenho($desempenho) {
        $this->desempenho = $desempenho;
    }


    
}
