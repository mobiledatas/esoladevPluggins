<?php
namespace SocialPlugin;
use Lib\Database;
use PDO;
use PDOException;
use Controller\Admin;
class Plugin{

    private $socialTable = 'esola_social_post';

    function __construct()
    {
        global $wpdb;
        $this->socialTable = $wpdb->prefix.$this->socialTable;

        
    }
    public function createTables()
    {
        if($this->existTable($this->socialTable) != true){
            $this->createSocialTable($this->socialTable);
        }
    }

    public function existTable($table)
    {
        $db = new Database();
        $cnx = $db->connect();
        $stmt = $cnx->prepare("SHOW TABLES LIKE ? ");
        $stmt->bindParam(1,$table,PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return sizeof($result)>0;
    }

    public function createSocialTable($table)
    {
       try{ 
            $db = new Database();
            $cnx = $db->connect();


            $stmt = $cnx->prepare("CREATE TABLE `$table` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                icon LONGTEXT,
                name VARCHAR(250),
                link VARCHAR(500),
                post_id INT,
                lang VARCHAR(250)
                );");

            $result = $stmt->execute();
            return ($result);
            
        }catch(PDOException $th){
            var_dump($th->getMessage());
            return false;
        }

    }

    static public function loadSrcPublic(){
        $plugin_url = plugin_dir_url(__FILE__) . 'public/app.css';
        wp_enqueue_style('EsolaAppSocial', $plugin_url);
    }
    static public function start()
    {
        
        if(is_admin()){
            // wp_register_script('VueJS','https://unpkg.com/vue@3');
            // wp_enqueue_script('VueJS');
            $plugin_url = plugin_dir_url(__FILE__) . 'public/admin.css';
            wp_enqueue_style('EsolaAppAdmin', $plugin_url);
            wp_register_script('EsolaAppSocial',plugin_dir_url(__FILE__).'public/app.js');
            wp_enqueue_script('EsolaAppSocial');
            add_action( 'wp_enqueue_scripts', function(){
                wp_localize_script( 'EsolaAppSocial', 'ajax_var', array(
                    'url'    => admin_url( 'admin-ajax.php' ),
                    'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
                    'action' => 'event-list'
                ) );
            } );
        }
        Admin::manage();
    }
       
    static public function loadApi(){
        
        add_action( 'wp_ajax_nopriv_all_social_esola', [Admin::class,'allSocial'] );
        add_action( 'wp_ajax_all_social_esola', [Admin::class,'allSocial'] );
        add_action( 'wp_ajax_create_social_esola', [Admin::class,'createSocial'] );
        add_action( 'wp_ajax_get_social_esola', [Admin::class,'getSocial'] );
        add_action( 'wp_ajax_edit_social_esola', [Admin::class,'editSocial'] );
        add_action( 'wp_ajax_delete_social_esola', [Admin::class,'deleteSocial'] );
        // if(isset($_POST['action'])){
        //     $action = $_POST['action'];
        //     echo $action;
        //     if(method_exists(Admin::class,$action)){
        //         Admin::{$action}();
        //     }
        // }
    }

    static function shortcodes(){
        ob_start();
        require_once plugin_dir_path(__FILE__).'./views/lawyers.php';
        return ob_get_clean();
    }
    
}
?>