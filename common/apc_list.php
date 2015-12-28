<?php
require_once 'apc_wrap.php';

class ApcListControl {
    public $baseindex = 0; 
    public $length = 0; 
}

class ApcList {
    
    private $name; 
    private $control = NULL;
    
    public function __construct($name) {
        $this->name = $name; 
    }
    
    private function ctrlkey() {
        return "_AL_CTL_" . $this->name; 
    }
    
    private function elemkey($index) {
        return "_AL_ELM_" . $this->name . "." . $key; 
    }

    private function control() {
        if ($this->control !== NULL) return $this->control; 
        $v = apcfetch($this->ctrlkey());
        if ($v === FALSE) {
            $v = new ApcListControl();
            apcstore($this->ctrlkey(), $v);
        }
        $this->control = $v;
        return $v;  
    }
    
    public function size() {
        $ctrl = $this->control();
        return $ctrl->length; 
    }

    public function get($index) {
        
    }
    
    public function append($elem) {
        
    }
    
    public function shift($elem) {
        
    }

    public function clear() {
    }
}
?>