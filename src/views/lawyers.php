<?php
global $wpdb;
$lang = pll_current_language() != null ? pll_current_language() : 'es';
$category = $lang == 'es' ? 'Team' : 'Lawyers'; 
$lawyers = new WP_Query([
    'post_type'=>'page',
    'category__in' => array(get_cat_ID($category)),
    'posts_per_page'=> -1,
    'orderby'=> $lang == 'es' ?'post_title':'post_name',
    'order'=>'ASC'
]);
?>
<?php if($lawyers->have_posts()): ?>
    
<div class="esola_lawyers_grid">
        <?php foreach ($lawyers->get_posts() as $lawyer) : ?>
                <div class="esola_lawyer_card">
                    <a href="<?php echo get_post_permalink($lawyer->ID) ?>" target="_blank">
                        
                    <div class="esola_lawyer_img">
                        <img style="width: 100%;" src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($lawyer->ID)) ?>" alt="">
                    </div>
                    <h4><?php echo $lawyer->post_title ?></h4>
                    
                    </a>
                    <?php
                    $socials = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."esola_social_post WHERE post_id = $lawyer->ID");
                    ?>
                    <?php if(sizeof($socials)>0): ?>
                        <ul>
                            <?php foreach($socials as $social): ?>
                                <li>
                                    <a style="font-size: 10px" target="_blank" href="<?= $social->link ?>" class="">
                                        <?php echo require __DIR__. '/icons/linkedin.php' ?>
                                        <span style="display: none;"><?= $social->name ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>
    </div>
    <br>
    
    <?php else: ?>
    <span>No se encontraron otros resultados para "<?php echo $_GET['search'] ?>"</span>
    <?php endif; ?>