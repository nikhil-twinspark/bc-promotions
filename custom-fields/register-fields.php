<?php
/**
 * Create the metabox
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function bc_create_metabox() {
    // Can only be used on a single post type (ie. page or post or a custom post type).
    // Must be repeated for each post type you want the metabox to appear on.
    add_meta_box(
        'bc_render_metabox', // Metabox ID
        'Coupon', // Title to display
        'bc_render_metabox', // Function to call that contains the metabox content
        'bc_promotions', // Post type to display metabox on
        'test', // Where to put it (normal = main colum, side = sidebar, etc.)
        'high' // Priority relative to other metaboxes
    );
}
add_action( 'add_meta_boxes', 'bc_create_metabox' );

/**
 * Render the metabox markup
 * This is the function called in `bc_create_metabox()`
 */
function bc_render_metabox() {
// Get the current post data
global $post; // Get the current post data
$title = get_post_meta( $post->ID, 'promotion_title1', true );
$color = get_post_meta( $post->ID, 'promotion_color', true );
$date = get_post_meta( $post->ID, 'promotion_expiry_date1', true );
$subheading = get_post_meta( $post->ID, 'promotion_subheading', true );
$footer_heading = get_post_meta( $post->ID, 'promotion_footer_heading', true );

$title2 = get_post_meta( $post->ID, 'promotion_title2', true );
$date2 = get_post_meta( $post->ID, 'promotion_expiry_date2', true );
$promotion_custom_image = get_post_meta( $post->ID, 'promotion_custom_image', true );
?>
<!-- Design of the page -->
<div class="col-lg-12">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a class="button " data-toggle="tab" href="#tab-1" aria-expanded="true"> Coupon Builder </a>
            </li>
            <li class="">
                <a class="button " data-toggle="tab" href="#tab-2" aria-expanded="false">Custom Image</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="container">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label><?php _e( 'Title', 'promotion_title_metabox' );?></label>
                            <input type="hidden" name="tab_val1" value="true" id="tab_val1">
                            <input type="text" name="promotion_title_metabox1" id="promotion_title_metabox1" value="<?php echo esc_attr( $title ); ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?php _e( 'Background Color (hex)', 'promotion_color_metabox' );?></label>
                                <input type="text" name="promotion_color_metabox" id="promotion_color_metabox" class="form-control background-color" value="<?php echo esc_attr( $color ); ?>" required />

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group expiration_date">
                                <label><?php _e( 'Expiration Date', 'custompromotion_expiry_date_metabox_date' );?></label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                    <!-- <i class="fa fa-calendar"></i> -->
                                    </span>

                                    <input type="text" name="promotion_expiry_date_metabox1" id="promotion_expiry_date_metabox1" value="<?php echo esc_attr( $date ); ?>" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label><?php _e( 'Subheading (HTML allowed)', 'promotion_subheading' );?></label>
                              <textarea class="form-control" rows="5" name="promotion_subheading" id="promotion_subheading" required ><?= $subheading ?></textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label><?php _e( 'Footer (HTML allowed)', 'promotion_footer_heading' );?></label>
                              <textarea class="form-control" rows="5" name="promotion_footer_heading" id="promotion_footer_heading" required ><?= $footer_heading ?></textarea>
                            </div> 
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label><?php _e( 'Preview', 'promotion_footer_heading' );?></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label><?php _e( 'Shortcode' );?></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="widget lazur-bg no-padding rounded-0" style="background-color:<?php echo $color;?>">
                                <div class="p-m">
                                    <h3 id="promotion_title" class="font-bold no-margins text-center ml-1"><?php echo esc_attr( $title ); ?></h3>
                                    <h5 id="promotion_subheading_msg" class="m-xs text-center"><?= $subheading ?></h5>

                                    
                                    <div class="text-center ml-1 font-italic"><small id="expires1"><?php if (!empty($date)) { echo "Expires"; }?> <?php echo esc_attr( $date ); ?></small></div>
                                    <div class="text-center font-italic"><small id="promotion_footerheading_msg" class="text-center"><?= $footer_heading ?></small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <h5>[bc-promotion coupon_id="<?= $post->ID?>"]</h5>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <div class="container">
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php _e( 'Title', 'promotion_title_metabox' );?></label>
                                
                                <input type="hidden" name="tab_val2" value="false" id="tab_val2">

                                <input type="text" name="promotion_title_metabox2" id="promotion_title_metabox2" value="<?php echo esc_attr( $title2 ); ?>" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php _e( 'Upload a custom image (Size 300*300)', 'promotion_color_metabox' );?></label>

                                <input type="text" name="promotion_custom_image" class="meta-image form-control" value="<?php echo $promotion_custom_image;?>">
                                <input type="button" class="button bc-promotion-image-upload" value="Browse">
                                <input type="button" class="button bc-promotion-image-remove" value="Remove Image">
                                <p>
                                    <div class="image-preview">
                                        <?php if(isset($promotion_custom_image) && !empty($promotion_custom_image)){?>

                                        <img src="<?php echo $promotion_custom_image;?>" style="max-width: 250px;">
                                        <?php }else{?>
                                        <img style="width: 250px; height: 250px;" src="http://placehold.it/300x300" />
                                        <?php }?>
                                    </div>
                                </p>


                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group expiration_date">
                                <label><?php _e( 'Expiration Date', 'custompromotion_expiry_date_metabox_date' );?></label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                    <!-- <i class="fa fa-calendar"></i> -->
                                    </span>
                                    <input type="text" name="promotion_expiry_date_metabox2" id="promotion_expiry_date_metabox2" value="<?php echo esc_attr( $date2 ); ?>" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shortcode</label>
                                <h5>[bc-promotion coupon_id="<?= $post->ID?>"]</h5>

                            </div>
                        </div>

                      </div>
                    </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php
// Security field
// This validates that submission came from the
// actual dashboard and not the front end or
// a remote server.
wp_nonce_field( '_namespace_form_metabox_nonce', '_namespace_form_metabox_process' );
}

/**
 * Save the metabox
 * @param  Number $post_id The post ID
 * @param  Array  $post    The post data
 */
function bc_save_metabox( $post_id, $post ) {
    if ( isset($_POST['tab_val1']) && $_POST['tab_val1'] == 'true' ) {
        $sanitizedtitle = wp_filter_post_kses( strip_tags($_POST['promotion_title_metabox1']) );
        // print_r($sanitizedtitle); die('ss');
        $sanitizedcolor = wp_filter_post_kses( $_POST['promotion_color_metabox'] );
        $get_expiry_date = wp_filter_post_kses( $_POST['promotion_expiry_date_metabox1'] );
        $date_format = str_replace('/', '-', $get_expiry_date);
        $sanitizedexpiry =  date('m/d/Y', strtotime($date_format));

        $sanitizedsubheading = wp_filter_post_kses( $_POST['promotion_subheading'] );
        $sanitizedfooterheading = wp_filter_post_kses( $_POST['promotion_footer_heading'] );
        // Save our submissions to the database
        update_post_meta( $post->ID, 'promotion_title1', $sanitizedtitle );
        update_post_meta( $post->ID, 'promotion_color', $sanitizedcolor );
        update_post_meta( $post->ID, 'promotion_expiry_date1', $sanitizedexpiry );
        update_post_meta( $post->ID, 'promotion_subheading', $sanitizedsubheading );
        update_post_meta( $post->ID, 'promotion_footer_heading', $sanitizedfooterheading );
        update_post_meta( $post->ID, 'promotion_type', 'Builder' );
        // Empty Tab2 value
        update_post_meta( $post->ID, 'promotion_title2', '' );
        update_post_meta( $post->ID, 'promotion_expiry_date2', '' );
        update_post_meta( $post->ID, 'promotion_custom_image', '' );
    }else if ( isset($_POST['tab_val2']) &&  $_POST['tab_val2'] == 'true' ) {
        $sanitizedtitle = wp_filter_post_kses( strip_tags($_POST['promotion_title_metabox2']) );
        $get_expiry_date = wp_filter_post_kses( $_POST['promotion_expiry_date_metabox2'] );
        $date_format = str_replace('/', '-', $get_expiry_date);
        $sanitizedexpiry =  date('m/d/Y', strtotime($date_format));

        $sanitizedimage = wp_filter_post_kses( $_POST['promotion_custom_image'] );
        // Save our submissions to the database
        update_post_meta( $post->ID, 'promotion_title2', $sanitizedtitle );
        update_post_meta( $post->ID, 'promotion_expiry_date2', $sanitizedexpiry );
        update_post_meta( $post->ID, 'promotion_custom_image', $sanitizedimage );
        update_post_meta( $post->ID, 'promotion_type', 'Image' );
        // Empty tab 1 value
        update_post_meta( $post->ID, 'promotion_title1', '' );
        update_post_meta( $post->ID, 'promotion_color', '' );
        update_post_meta( $post->ID, 'promotion_expiry_date1', '' );
        update_post_meta( $post->ID, 'promotion_subheading', '' );
        update_post_meta( $post->ID, 'promotion_footer_heading', '' );
    }
}
add_action( 'save_post', 'bc_save_metabox', 1, 2 );

// Change Title on insert and update of location title
add_filter('wp_insert_post_data', 'bc_promotions_change_title');
function bc_promotions_change_title($data){
    if($data['post_type'] != 'bc_promotions'){
        return $data;
    }
    if ( !empty( $_POST['promotion_title_metabox1'] ) ) {
        $data['post_title'] = $_POST['promotion_title_metabox1'];
    }
    if ( !empty( $_POST['promotion_title_metabox2'] ) ) {
        $data['post_title'] = $_POST['promotion_title_metabox2'];
    }
    return $data;
}
