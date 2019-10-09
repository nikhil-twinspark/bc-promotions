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


function bc_promotion_shortcode( $atts ) {
    // print_r($atts[0]); die();
    // print_r($atts['single']); die();
    // echo $atts['single'];
    if(isset($atts['single']) && !empty($atts['single'])){
        $id = $atts['single'];
        $post = get_post( $id );

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

                    <div class="text-center ml-1 font-italic"><small><?php echo $promotion_expiry_date; ?> </small></div>
                    <div class="text-center font-italic"><small class="text-center"><?php echo $promotion_footer_heading; ?></small></div>
                </div>
            </div>
        </div>
          
    <?php } elseif($atts[0] == 'all'){
        $args = array( 'post_type' => 'bc_promotions', 'posts_per_page' => -1, 'order'=> 'ASC');
        $the_query = get_posts( $args );
        $coupon_data = [];
        foreach ($the_query as $key => $value) {
            $promotion_title1 = get_post_meta($value->ID, 'promotion_title1', TRUE);
            $promotion_color = get_post_meta($value->ID, 'promotion_color', TRUE);
            $promotion_expiry_date = get_post_meta($value->ID, 'promotion_expiry_date1', TRUE);
            $promotion_subheading = get_post_meta($value->ID, 'promotion_subheading', TRUE);
            $promotion_footer_heading = get_post_meta($value->ID, 'promotion_footer_heading', TRUE);
            
            if(isset($promotion_title1) && !empty($promotion_title1) && !in_array($promotion_title1, [null,false,''])){
                $coupon_data[] = [
                        'post_id' => $value->ID,
                        'promotion_title' => $promotion_title1,
                        'promotion_color' => $promotion_color,
                        'promotion_expiry_date' => $promotion_expiry_date,
                        'promotion_subheading' => $promotion_subheading,
                        'promotion_footer_heading' => $promotion_footer_heading,
                        'show' => true,
                    ];
            }
        }
        foreach ($coupon_data as $key => $coupon_value) { ?>
            <ul class="list-group">
              <li class="list-group-item">
                <div class="col-lg-6">
                    <div class="widget lazur-bg no-padding" style="background-color:<?php echo $coupon_value['promotion_color']; ?>">
                        <div class="p-m">
                            <h3 class="font-bold no-margins text-center ml-1"><?php echo $coupon_value['promotion_title']; ?></h3>
                            <h5 class="m-xs text-center"><?php echo $coupon_value['promotion_subheading']; ?></h5>

                            <div class="text-center ml-1 font-italic"><small><?php echo $coupon_value['promotion_expiry_date']; ?> </small></div>
                            <div class="text-center font-italic"><small class="text-center"><?php echo $coupon_value['promotion_footer_heading']; ?></small></div>
                        </div>
                    </div>
                </div>
              </li>
            </ul>
        <?php }    
    }

}
// [bc-promotion single=69]
// [bc-promotion all]
add_shortcode( 'bc-promotion', 'bc_promotion_shortcode' );

// Admin notice for displaying shortcode on index page
function bc_promotion_general_admin_notice(){
    global $pagenow;
    global $post;
    // echo "<pre>";
    // print_r($post);
    // die('ss');
    if ( $pagenow == 'edit.php' && $post->post_type == "bc_promotions" ) {
         echo '<div class="notice notice-success is-dismissible">
            <p>Shortcode [bc-promotion all]</p>
         </div>';
    }
}
add_action('admin_notices', 'bc_promotion_general_admin_notice');


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
    // echo "<pre>";
    // print_r($post);
    // die('ss');
    switch ($column_name) {
        case 'title':
            echo $get_title = get_post_meta( $post->ID , 'custom_title' , true );
            break;
        case 'promotion_expiry_date1':
            $expiry_date =  get_post_meta( $post->ID , 'promotion_expiry_date1' , true );
            $curdate = date('m/d/Y');
            if($curdate > $expiry_date){
                echo '<span class="expired">'.$expiry_date.'</span>';
            }else{
                echo $expiry_date;
            }
            break;
        case 'updated':
            // echo $post->post_modified;
            echo get_the_date('m/d/Y'); 
            break;
        case 'type':
            echo get_post_meta( $post->ID , 'promotion_type' , true );
            break;
        case 'status':
            $status = $post->post_status;
            $expiry_date =  get_post_meta( $post->ID , 'promotion_expiry_date1' , true );
            $curdate = date('m/d/Y');
            if ($curdate > $expiry_date) {
                echo '<span class="expired">Expired</span>';
            }else{
                echo ucfirst($status);
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


/*
 * @param string $name Name of option or name of post custom field.
 * @param string $value Optional Attachment ID
 * @return string HTML of the Upload Button
 */
function misha_image_uploader_field( $name, $value = '') {
    $image = ' button">Upload image';
    $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state ot the "Remove image" button
 
    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
 
        // $image_attributes[0] - image URL
        // $image_attributes[1] - image width
        // $image_attributes[2] - image height
 
        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
        $display = 'inline-block';
 
    } 
 
    return '
    <div>
        <a href="#" class="misha_upload_image_button' . $image . '</a>
        <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . esc_attr( $value ) . '" />
        <a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
    </div>';
}