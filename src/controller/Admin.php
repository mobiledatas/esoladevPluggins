<?php 
namespace Controller;

use Entity\Social as EntitySocial;
use Lib\View;
use Model\Social;

class Admin{

    function __construct()
    {
        
    }

    public static function manage()
    {
        $lang = isset($_GET['lang']) ? $_GET['lang']:'es';
        $cats = array(get_cat_ID('Team'));
        if($lang == 'en'){
            $cats = array(get_cat_ID('Lawyers'));
        }
        $posts = get_posts([
            'post_type'=>'page',
            'category__in'=>$cats,
            'lang'=>$lang,
            'numberposts'=> -1,
            'orderby'=> $lang == 'es' ? 'post_title':'post_name',
            'order'=>'ASC'
         ]);
        $links = (new Social())->all();
        View::render('manage',['data'=>$links,'posts'=>$posts]);
    }

    public static function getSocial(){
        $social = (new Social())->get($_POST['id']);
        header('Content-type: application/json');
        echo json_encode(['social'=>$social]);
        wp_die();
    }

    public static function editSocial(){
        $icon = '';
        $name = 'Linkedin';
        if(isset($_POST['name'])){
            switch ($_POST['name']){
                case "Facebook":
                    $icon = 'et_pb_font_icon et_pb_facebook_icon';
                    break;
                case "Linkedin":
                    $icon = 'et_pb_font_icon et_pb_linkedin_icon';
                    break;
                default:
                    $icon = 'et_pb_font_icon et_pb_linkedin_icon';
                    $name = 'Facebook';
                    break;
            }
        };
        try {
            $id = $_POST['id'];
            $post_id = $_POST['post_id'];
            $link = $_POST['link'];

            $socialE = new EntitySocial($id,$icon,$name,$link,$post_id,$_POST['lang']);
            $res = (new Social())->update($socialE);
            echo json_encode(['response'=>['affectedItems'=>$res]]);    
        } catch (\Throwable $th) {
            echo json_encode(['response'=>['affectedItems'=>'0']]);    //throw $th;
        }
        
        wp_die();
    }

    public static function allSocial(){
        $links = (new Social())->all();
        header('Content-type: application/json');
        echo json_encode(['data'=>$links]);
        wp_die();
    }

    public static function createSocial(){
        
        $icon = '';
        $name = 'Linkedin';
        if(isset($_POST['name'])){
            switch ($_POST['name']){
                case "Linkedin":
                    $icon = 'et_pb_font_icon et_pb_linkedin_icon';
                    break;
                default:
                    $icon = 'et_pb_font_icon et_pb_linkedin_icon';
                    $name = 'Facebook';
                    break;
            }
        };
        try {
            $social = new EntitySocial(null,$icon,$name,$_POST['link'],$_POST['post_id'],$_POST['lang']);
            $socialM = new Social();
            $id = $socialM->create($social);
            echo json_encode(['response'=>['lastId'=>$id]]);
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['response'=>$th->getMessage()]);//throw $th;
        }
        wp_die();
    }

    public function deleteSocial()
    {
        try {
            $id =$_POST['id'];
            $entity  = new EntitySocial($id);
            $res = (new Social())->delete($entity);
            echo json_encode(['response'=>['affectedItems'=>$res]]);    
        } catch (\Throwable $th) {
            echo json_encode(['response'=>$th->getMessage()]);
        }
        wp_die();
    }
}
?>