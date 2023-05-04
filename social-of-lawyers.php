<?php
/**
 * Plugin Name: Social For Lawyers Esola Plugin
 * 
 */

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/src/Plugin.php';
use SocialPlugin\Plugin;
function firstRegistration(){
    (new Plugin())->createTables();
}
function start(){
    add_menu_page('Social for Lawyers Post',"Social Lawyers Esola",4,'social-lawyers-esola',[Plugin::class,'start']);
}
register_activation_hook(__FILE__,'firstRegistration');
add_action('admin_menu','start');
add_shortcode('esola-lawyers-social',[Plugin::class,'shortcodes']);
Plugin::loadApi();
Plugin::loadSrcPublic();
?>