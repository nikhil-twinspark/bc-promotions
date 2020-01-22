<?php
/**
 * Plugin Name:       BC Promotions - Coupon Builder
 * Plugin URI:        https://github.com/nikhil-twinspark/bc-promotions
 * Description:       A simple plugin for creating coupons for promotion.
 * Version:           1.0.0
 * Author:            Blue Corona
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bc-promotions
 * Domain Path:       /languages
 */

 if ( ! defined( 'WPINC' ) ) {
     die;
 }

define( 'BC_VERSION', '1.0.0' );
define( 'BCDOMAIN', 'bc-promotions' );
define( 'BCPATH', plugin_dir_path( __FILE__ ) );


require_once( BCPATH . 'post-types/register-type.php' );
add_action( 'init', 'bc_register_promotion_type' );

require_once( BCPATH . '/custom-fields/register-fields.php');

function bc_rewrite_flush() {
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'bc_rewrite_flush' );

// plugin uninstallation
register_uninstall_hook( BCPATH, 'bc_custom_uninstall' );
function bc_custom_uninstall() {
    // delete_posts();
}

// Add Conditionally css & js for specific pages
add_action('admin_enqueue_scripts', 'bc_include_css_js');
function bc_include_css_js($hook){
    wp_enqueue_media();
    $current_screen = get_current_screen();
    if ( $current_screen->post_type == 'bc_promotions') {
        // Include CSS Libs
        wp_register_style('bc-plugin-css', plugins_url('assests/css/bootstrap.min.css', __FILE__), array(), '1.0.0', 'all');
        wp_enqueue_style('bc-plugin-css');
        wp_enqueue_style('bc-colorpicker-css',plugins_url('assests/css/bootstrap-colorpicker.min.css', __FILE__), array(), '1.0.0', 'all');
        wp_enqueue_style('bc-datepicker',plugins_url('assests/css/datepicker3.css', __FILE__), array(), '1.0.0', 'all');
        // wp_enqueue_style('bc-fontawesome',plugins_url('assests/css/font-awesome.min.css', __FILE__), array(), '1.0.0', 'all');
        wp_enqueue_style('bc-style',plugins_url('assests/css/bc-style.css', __FILE__), array(), '1.0.0', 'all');


        

        // Inculde JS Libs
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', true);
        wp_enqueue_script('bootstap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', true);

        wp_enqueue_script('bc-datepicker-js', plugin_dir_url(__FILE__).'assests/js/bootstrap-datepicker.js', true);

        wp_enqueue_script('bc-colorpicker-js', plugin_dir_url(__FILE__).'assests/js/bootstrap-colorpicker.min.js', true);

        wp_enqueue_script('bc-custom-js', plugin_dir_url(__FILE__).'assests/js/bc-custom.js', true);
        wp_enqueue_script('bc-image-upload-js', plugin_dir_url(__FILE__).'assests/js/bc-image-upload.js', array( 'jquery'));
    } 
}

add_shortcode( 'bc-promotion', 'bc_promotion_shortcode' );
function bc_promotion_shortcode( $atts , $content = null ) {

    static $count = 0;
    $count++;
    add_action( 'wp_footer' , function() use($count){
    ?>
    <script>
        var couponSwiper = new Swiper('#bc_coupons_swiper_<?php echo $count ?>', {
        pagination: false,
        slidesPerView: 3,
        spaceBetween: 32,
        breakpoints: {
            320: {slidesPerView: 1},
            480: {slidesPerView: 1},
            640: {slidesPerView: 2},
            768: {slidesPerView: 3},
            1000: {slidesPerView: 3}
        },
        navigation: {
            nextEl: '.bc_coupon_swiper_next',
            prevEl: '.bc_coupon_swiper_prev',
        },
    });
    </script>
    <?php });

    $Ids = null;
    $args  = array( 'post_type' => 'bc_promotions', 'posts_per_page' => -1, 'order'=> 'DESC','post_status'  => 'publish');

    if(isset($atts['coupon_id'])) {
        $Ids = explode(',', $atts['coupon_id']);
        $postIds = $Ids;
        $args['post__in'] = $postIds;
    }
    ob_start();
    $query = new WP_Query( $args );
        if ( $query->have_posts() ) : ?>
        <div class="container-fluid bc_about_bg">
        <div class="container pb-5">
        <h2 class="bc_font_alt_1 text-capitalize text-center py-5">Our Promotions</h2>
        <div class="mt-5">
            <!-- before pase -->
            <div id="bc_coupons_swiper_<?php echo $count ?>" class="bc_promotions swiper-container text-center my-4 swiper-container-initialized swiper-container-horizontal">
                <div class="swiper-wrapper text-center">
        <?php
        while($query->have_posts()) : $query->the_post();

        $promotion_type = get_post_meta(get_the_ID(), 'promotion_type', TRUE);
        if($promotion_type == 'Builder'){
        $date = get_post_meta( get_the_ID(), 'promotion_expiry_date1', true );
        if(strtotime($date) >= strtotime(current_time('m/d/Y'))){
            $title = get_post_meta( get_the_ID(), 'promotion_title1', true );
            $color = get_post_meta( get_the_ID(), 'promotion_color', true );
            $subheading = get_post_meta( get_the_ID(), 'promotion_subheading', true );
            $footer_heading = get_post_meta( get_the_ID(), 'promotion_footer_heading', true ); ?>
            <div class="swiper-slide">
                <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank">
                    <div class="bc_color_secondary bc_color_primary_bg p-3 mb-3" style="background-color: <?php echo $color;?>">
                        <div class="py-4 px-3 pt-0 border-white bc_coupon_container">
                            <span class="pb-3 bc_font_alt_1 bc_text_36 d-block bc_color_secondary text-capitalize "><?php echo $title; ?></span>
                           <span class="bc_text_36 d-block my-2"><?php echo $subheading;?></span>
                            <span class="mt-3 bc_text_20 ">expires <?php echo $date;?></span>
                        </div>
                    </div>
                </a>
            </div>
    <?php }
    }else if($promotion_type == 'Image'){
        $date2 = get_post_meta( get_the_ID(), 'promotion_expiry_date2', true );
        if(strtotime($date2) >= strtotime(current_time('m/d/Y'))){
            $title2 = get_post_meta( get_the_ID(), 'promotion_title2', true );
            $promotion_custom_image = get_post_meta( get_the_ID(), 'promotion_custom_image', true ); ?>
            <div class="swiper-slide">
                <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank">
                    <img class="img-fluid" src="<?php echo $promotion_custom_image;?>">
                </a>
            </div>
    <?php }
        }
    ?>
    <?php
    endwhile; 
    wp_reset_query();
    endif;
    ?>
            </div>
                <ul class=" list-unstyled">
                    <li class="list-inline-item bc_coupon_swiper_prev bc_swiper-button-prev"> <em class="fa fa-chevron-circle-left"></em> </li>
                    <li class="list-inline-item bc_coupon_swiper_next bc_swiper-button-next"> <em class="fa fa-chevron-circle-right"></em> </li>
                </ul>
            </div>
        </div>
    </div>    
</div>

<?php 
$output = ob_get_clean();
return $output;
}

// Admin notice for displaying shortcode on index page
add_action('admin_notices', 'bc_promotion_general_admin_notice');
function bc_promotion_general_admin_notice(){
    global $pagenow;
    global $post;
    if ($pagenow == 'edit.php' &&  (isset($post->post_type) ? $post->post_type : null) == 'bc_promotions') { 
     echo '<div class="notice notice-success is-dismissible">
            <p><b>Shortcode Example</b> All : [bc-promotion] Specific : [bc-promotion coupon_id="1,2,3"]</p>
         </div>';
    }
}


/** ADMIN COLUMN - HEADERS*/
add_filter('manage_edit-bc_promotions_columns', 'add_new_promotions_columns');
function add_new_promotions_columns($concerts_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = _x('Title', 'column name');
    $new_columns['promotion_expiry_date1'] = __('Expiration');
    $new_columns['updated'] = __('Updated');
    $new_columns['type'] = __('Type');
    $new_columns['status'] = __('Status');
    return $new_columns;
}

/** ADMIN COLUMN - CONTENT*/
add_action('manage_bc_promotions_posts_custom_column', 'manage_promotions_columns', 10, 2);
function manage_promotions_columns($column_name, $id) {
    global $post;
    switch ($column_name) {
        case 'title':
            echo $get_title = get_post_meta( $post->ID , 'custom_title' , true );
            break;
        case 'promotion_expiry_date1':
            $expiry_date =  get_post_meta( $post->ID , 'promotion_expiry_date1' , true );
            $expiry_date2 =  get_post_meta( $post->ID , 'promotion_expiry_date2' , true );
            $curdate = date('m/d/Y');
            $expiry_date_timestamp = strtotime($expiry_date);
            $expiry_date2_timestamp = strtotime($expiry_date2);
            $curdate_timestamp = strtotime($curdate);

            if(isset($expiry_date) && !empty($expiry_date)){
                if($curdate_timestamp > $expiry_date_timestamp){
                    echo '<span class="expired">'.$expiry_date.'</span>';
                }else{
                    echo $expiry_date;
                }
            }elseif(isset($expiry_date2) && !empty($expiry_date2)){
                if($curdate_timestamp > $expiry_date2_timestamp){
                    echo '<span class="expired">'.$expiry_date2.'</span>';
                }else{
                    echo $expiry_date2;
                }
            }
            break;
        case 'updated':
            echo get_the_date('m/d/Y'); 
            break;
        case 'type':
            echo get_post_meta( $post->ID , 'promotion_type' , true );
            break;
        case 'status':
            $status = $post->post_status;
            $expiry_date =  get_post_meta( $post->ID , 'promotion_expiry_date1' , true );
            $expiry_date2 =  get_post_meta( $post->ID , 'promotion_expiry_date2' , true );
            $curdate = date('m/d/Y');
            $expiry_date_timestamp = strtotime($expiry_date);
            $expiry_date2_timestamp = strtotime($expiry_date2);
            $curdate_timestamp = strtotime($curdate);
            if(isset($expiry_date) && !empty($expiry_date)){
                if ($curdate_timestamp > $expiry_date_timestamp) {
                    echo '<span class="expired">Expired</span>';
                }else{
                    echo ucfirst($status);
                }
            }elseif(isset($expiry_date2) && !empty($expiry_date2)){
                if ($curdate_timestamp > $expiry_date2_timestamp) {
                    echo '<span class="expired">Expired</span>';
                }else{
                    echo ucfirst($status);
                }
            }
            break;
        default:
            break;
    } // end switch
}

// /*
//  * ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
//  * https://gist.github.com/906872
//  */
// add_filter("manage_edit-bc_promotions_sortable_columns", 'promotions_sort');
// function promotions_sort($columns) {
//     $custom = array(
//         'state' => 'state',
//         'city'  => 'city',
//         'category'  => 'category',
//         'date_custom'   => 'date_custom',
//         'status'    => 'status',
//     );
//     return wp_parse_args($custom, $columns);
// }

