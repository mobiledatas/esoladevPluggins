<?php
namespace Entity;
class Social{

    private $table = 'esola_social';
    public $id;
    public $icon;
    public $name;
    public $link;
    public $post_id;
    public $lang;
    function __construct($id=null,$icon=null,$name=null,$link=null,$post_id=null,$lang=null){

        $this->id = $id;
        $this->icon = $icon;
        $this->name = $name;
        $this->link = $link;
        $this->post_id = $post_id;
        $this->lang = $lang;
    }


}

?>