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
function bc_promotion_shortcode( $atts ) {
    if(isset($atts['single']) && !empty($atts['single'])){
        $id = $atts['single'];
        $post = get_post( $id );
        $promotion_type = get_post_meta($id, 'promotion_type', TRUE);
        if($promotion_type == 'Builder'){
        
        $promotion_title1 = get_post_meta($id, 'promotion_title1', TRUE);
        $promotion_color = get_post_meta($id, 'promotion_color', TRUE);
        $promotion_expiry_date = get_post_meta($id, 'promotion_expiry_date1', TRUE);
        $promotion_subheading = get_post_meta($id, 'promotion_subheading', TRUE);
        $promotion_footer_heading = get_post_meta($id, 'promotion_footer_heading', TRUE);?>
        <div class="col-lg-6">
            <div class="widget lazur-bg no-padding" style="background-color:<?php echo $promotion_color; ?>">
                <div class="p-m">
                    <h3 class="font-bold no-margins text-center ml-1"><?php echo $promotion_title1 ?></h3>
                    <h5 class="m-xs text-center"><?php echo $promotion_subheading; ?></h5>

                    <div class="text-center ml-1 font-italic"><small><?php echo "Expires ".$promotion_expiry_date; ?> </small></div>
                    <div class="text-center font-italic"><small class="text-center"><?php echo $promotion_footer_heading; ?></small></div>
                </div>
            </div>
        </div>
        <?php 
        }elseif($promotion_type == 'Image'){
            $promotion_image = get_post_meta($id, 'promotion_custom_image', TRUE);
            ?>
            <div class="col-lg-6">
            <img class="lazy-loaded" data-src="<?= $promotion_image ?>" src="<?= $promotion_image ?>" width="300" height="300">
            </div>
        <?php } ?>
        
    <?php } elseif($atts[0] == 'all'){
        $args = array( 'post_type' => 'bc_promotions', 'posts_per_page' => -1, 'order'=> 'ASC','post_status'      => 'publish');
        $the_query = get_posts( $args );
        $coupon_data = [];
        foreach ($the_query as $key => $value) {
            $promotion_type = get_post_meta($value->ID, 'promotion_type', TRUE);
            if($promotion_type == 'Builder'){
                $promotion_title1 = get_post_meta($value->ID, 'promotion_title1', TRUE);
                $promotion_color = get_post_meta($value->ID, 'promotion_color', TRUE);
                $promotion_expiry_date = get_post_meta($value->ID, 'promotion_expiry_date1', TRUE);
                $promotion_subheading = get_post_meta($value->ID, 'promotion_subheading', TRUE);
                $promotion_footer_heading = get_post_meta($value->ID, 'promotion_footer_heading', TRUE);
                $coupon_data[] = [
                        'post_id' => $value->ID,
                        'promotion_type' => $promotion_type,
                        'promotion_color' => $promotion_color,
                        'promotion_title' => $promotion_title1,
                        'promotion_subheading' => $promotion_subheading,
                        'promotion_footer_heading' => $promotion_footer_heading,
                        'promotion_expiry_date' => $promotion_expiry_date,
                        'show' => true,
                    ];
            }elseif($promotion_type == 'Image'){
                $promotion_title1 = get_post_meta($value->ID, 'promotion_title2', TRUE);
                $promotion_expiry_date = get_post_meta($value->ID, 'promotion_expiry_date2', TRUE);
                $promotion_custom_image = get_post_meta($value->ID, 'promotion_custom_image', TRUE);

                $coupon_data[] = [
                        'post_id' => $value->ID,
                        'promotion_type' => $promotion_type,
                        'promotion_title' => $promotion_title1,
                        'promotion_image' => $promotion_custom_image,
                        'promotion_expiry_date' => $promotion_expiry_date,
                        'show' => true,
                    ];
            }
        }
        foreach ($coupon_data as $key => $coupon_value) {
            if($coupon_value['promotion_type'] == 'Builder'){ ?>
            <ul class="list-group">
              <li class="list-group-item">
                <div class="col-lg-6">
                    <div class="widget lazur-bg no-padding" style="background-color:<?php echo $coupon_value['promotion_color']; ?>">
                        <div class="p-m">
                            <h3 class="font-bold no-margins text-center ml-1"><?php echo $coupon_value['promotion_title']; ?></h3>
                            <h5 class="m-xs text-center"><?php echo $coupon_value['promotion_subheading']; ?></h5>
                            <div class="text-center ml-1 font-italic"><small><?php echo "Expires ".$coupon_value['promotion_expiry_date']; ?> </small></div>
                            <div class="text-center font-italic"><small class="text-center"><?php echo $coupon_value['promotion_footer_heading']; ?></small></div>
                        </div>
                    </div>
                </div>
              </li>
            </ul> 
            <?php }elseif($coupon_value['promotion_type'] == 'Image' && !empty($coupon_value['promotion_image'])){?>
             <ul class="list-group">
                <li class="list-group-item">
                    <div class="col-lg-6">
                    <img class="lazy-loaded" data-src="<?= $coupon_value['promotion_image'] ?>" src="<?= $coupon_value['promotion_image'] ?>" width="300" height="300">
                    </div>
                </li>
            </ul>
            <?php }
        }    
    }
}

// Admin notice for displaying shortcode on index page
add_action('admin_notices', 'bc_promotion_general_admin_notice');
function bc_promotion_general_admin_notice(){
    global $pagenow;
    global $post;
    if ( $pagenow == 'edit.php' && $post->post_type == "bc_promotions" ) {
         echo '<div class="notice notice-success is-dismissible">
            <p>Shortcode [bc-promotion all]</p>
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

