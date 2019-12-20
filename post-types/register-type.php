<?php
function bc_register_promotion_type() {
    $labels = array( 
        'name' => __( 'Promotions', BCDOMAIN ),
        'singular_name' => __( 'Promotion', BCDOMAIN ),
        // 'archives' => __( 'Promotions Calendar', BCDOMAIN ),
        'add_new' => __( 'Add New', BCDOMAIN ),
        'add_new_item' => __( 'Add New Promotion', BCDOMAIN ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'has_archive' => 'promotions',
        'rewrite' => array( 'has_front' => true ,'slug' => 'promotions'),
        'menu_icon' => 'dashicons-tag',
        'supports' => false,
        'show_in_rest' => true,
    );

    register_post_type( 'bc_promotions', $args );
}
