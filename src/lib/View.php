<?php
namespace Lib; 
class View{



    static public function render($view,$vars = [])
    {
        
        $i = new self();
        ob_start();
        foreach ($vars as $key => $v) {
           $i->{$key} = $v; 
        }
        $i->view($view,$i);
        echo ob_get_clean();
    }

    public function view($view,$i)
    {
        $i;
        require_once __DIR__.'/../views/'.$view.'.php';
    }
}
?>