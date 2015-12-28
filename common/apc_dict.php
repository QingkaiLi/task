<?php
require_once 'apc_wrap.php';
    
class ApcDict {
    
    private $name; 
    
    public function __construct($name) {
        $this->name = $name; 
    }
    
    public function keys_key() {
        return "_AD_KEYS_" . $this->name; 
    }
    
    public function value_key($key) {
        return "_AD_VALUE_" . $this->name . "." . $key; 
    }
    
    public function keys_dict() {
        $keys = apcfetch($this->keys_key()); 
        if ($keys === FALSE) {
            $keys = array();
            apcstore($this->keys_key(), $keys); 
        }
        return $keys;  
    }
    
    public function keys() {
        $keys_dict = $this->keys_dict(); 
        return array_keys($keys_dict); 
    }
    
    public function values() {
        $keys_dict = $this->keys_dict();
        $values = array();
        foreach ($keys_dict as $key => $one) {
            $value = $this->fetch($key); 
            array_push($values, $value); 
        }
        return $values;
    }
    
    public function store($key, $value, $assert_exists=false) {
        // if $assert_exists is true, do not check dict keys. Caller should be assure that the key exists.
        if (!$assert_exists) {
            $keys_dict = $this->keys_dict(); 
            if (!isset($keys_dict[$key])) {
                // new key, store it
                $keys_dict[$key] = 1;
                apcstore($this->keys_key(), $keys_dict);  
            }
        }
        apcstore($this->value_key($key), $value);
    } 
    
    public function fetch($key) {
        return apcfetch($this->value_key($key)); 
    } 
    
    public function delete($key) {
        $keys_dict = $this->keys_dict();
        if (isset($keys_dict[$key])) {
            unset($keys_dict[$key]); 
            apcstore($this->keys_key(), $keys_dict); 
        }
        apcdelete($this->value_key($key)); 
    }
    
    public function clear() {
        $keys_dict = $this->keys_dict();
        foreach ($keys_dict as $key => $one) {
            apcdelete($this->value_key($key)); 
        }
        apcdelete($this->keys_key());
    }
}
?>