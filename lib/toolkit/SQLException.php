<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_DIR'))
    exit('Veuillez charger le toolkit avant d\'utiliser SQLException !');

if(TOOLKIT_VERSION_ID < 101)
    exit('Version du toolkit trop ancienne pour supporter SQLException !');

class SQLException extends Exception{
    private $query;

    public function __construct($message, $query) {
        parent::__construct($message);
        $this->query = $query;

        foreach($this->getTrace() as $trace){
            if(str_replace(TOOLKIT_DIR, '', $trace['file']) === $trace['file']){
                $this->file = $trace['file'];
                $this->line = $trace['line'];
                break;
            }
        }
    }

    public function __toString() {
        ob_start();
        $message = $this->message;
        $file = $this->file;
        $line = $this->line;
        $query = $this->query;
        $trace = $this->getTraceAsString();
        require TOOLKIT_DIR.'templates'.DS.'error'.DS.'sql'.EXT;
        return ob_get_clean();
    }
}
