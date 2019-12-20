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
    $Ids = null;
    $args  = array( 'post_type' => 'bc_promotions', 'posts_per_page' => -1, 'order'=> 'DESC','post_status'  => 'publish');

    if(isset($atts['coupon_id'])) {
        $Ids = explode(',', $atts['coupon_id']);
        $postIds = $Ids;
        $args['post__in'] = $postIds;
    }
    $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
        while($query->have_posts()) : $query->the_post();

        $promotion_type = get_post_meta(get_the_ID(), 'promotion_type', TRUE);
        if($promotion_type == 'Builder'){
        $date = get_post_meta( get_the_ID(), 'promotion_expiry_date1', true );
        if($date >= current_time('m/d/Y')){
            $title = get_post_meta( get_the_ID(), 'promotion_title1', true );
            $color = get_post_meta( get_the_ID(), 'promotion_color', true );
            $subheading = get_post_meta( get_the_ID(), 'promotion_subheading', true );
            $footer_heading = get_post_meta( get_the_ID(), 'promotion_footer_heading', true ); ?>
    
            <div class="col-md-4 col-lg-4 p-2 text-center">
                <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank">
                    <div class="bc_color_secondary bc_color_primary_bg p-3 mb-3" style="background-color: <?php echo $color;?>">
                        <div class="py-4 px-3 pt-0 border-white bc_coupon_container">
                            <span class="pb-3  bc_font_alt_1 bc_text_36 d-block"><?php echo $title; ?></span>
                            <span class="bc_text_30 d-block my-2"><?php echo $subheading;?></span>
                            <span class="mt-3 bc_text_16">expires <?php echo $date;?></span>
                        </div>
                    </div>
                </a>
            </div>
    <?php }
    }else if($promotion_type == 'Image'){
        $date2 = get_post_meta( get_the_ID(), 'promotion_expiry_date2', true );
        if($date2 >= current_time('m/d/Y')){
            $title2 = get_post_meta( get_the_ID(), 'promotion_title2', true );
            $promotion_custom_image = get_post_meta( get_the_ID(), 'promotion_custom_image', true ); ?>
            <div class="col-md-4 col-lg-4 p-2 text-center">
                <a href="<?php the_permalink(get_the_ID()); ?>" target="_blank">
                    <img src="<?php echo $promotion_custom_image;?>" style="width:350px;height:228px;">
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

<?php }

// Admin notice for displaying shortcode on index page
add_action('admin_notices', 'bc_promotion_general_admin_notice');
function bc_promotion_general_admin_notice(){
    global $pagenow;
    global $post;
    if ($pagenow == 'edit.php' &&  (isset($post->post_type) ? $post->post_type : null) == 'bc_promotions') { 
     echo '<div class="notice notice-success is-dismissible">
            <p>Shortcode [bc-promotion]</p>
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
            if(isset($expiry_date) && !empty($expiry_date)){
                if($curdate > $expiry_date){
                    echo '<span class="expired">'.$expiry_date.'</span>';
                }else{
                    echo $expiry_date;
                }
            }elseif(isset($expiry_date2) && !empty($expiry_date2)){
                if($curdate > $expiry_date2){
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
            if(isset($expiry_date) && !empty($expiry_date)){
                if ($curdate > $expiry_date) {
                    echo '<span class="expired">Expired</span>';
                }else{
                    echo ucfirst($status);
                }
            }elseif(isset($expiry_date2) && !empty($expiry_date2)){
                if ($curdate > $expiry_date2) {
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

