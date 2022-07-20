<?php
   defined( 'ABSPATH' ) || exit;
// Rush Filter Register Category Label Input
 add_settings_section(
    'rushfilter_lable_change_name_settings_section',
    __( 'Change Post Filter Taxonomy Label', 'rush-filter' ),
    '',
    'rushfilter-settings'
);
register_setting(
    'rushfilter-settings',
    'rushfilter_tax_name_settings_options'
);
add_settings_field(
    'rushfilter_cat_settings_input_field',
    __( 'Post Category Label: ', 'rush-filter' ),
    'rushfilter_cat_settings_input_field_callback',
    'rushfilter-settings',
    'rushfilter_lable_change_name_settings_section',
);

// Rush Filter Tag Label Input Field
register_setting(
    'rushfilter-settings',
    'rushfilter_tag_name_settings_options'
);
add_settings_field(
    'rushfilter_tag_settings_input_field',
    __( 'Post Tag Label: ', 'rush-filter' ),
    'rushfilter_tag_settings_input_field_callback',
    'rushfilter-settings',
    'rushfilter_lable_change_name_settings_section',
);


// Rush Filter Post View Label Input Field
register_setting(
    'rushfilter-settings',
    'rushfilter_post_view_settings_options'
);
add_settings_field(
    'rushfilter_post_view_input_field',
    __( 'Post View Label: ', 'rush-filter' ),
    'rushfilter_post_view_input_field_callback',
    'rushfilter-settings',
    'rushfilter_lable_change_name_settings_section',
);


// Rush Filter Author Label Input Field
register_setting(
    'rushfilter-settings',
    'rushfilter_post_author_settings_options'
);
add_settings_field(
    'rushfilter_post_author_input_field',
    __( 'Post View Label: ', 'rush-filter' ),
    'rushfilter_post_author_input_field_callback',
    'rushfilter-settings',
    'rushfilter_lable_change_name_settings_section',
);



add_settings_section(
    'rushfilter_product_lable_change_name_settings_section',
    __( 'Change Product Filter Taxonomy Label', 'rush-filter' ),
    '',
    'rushfilter-settings'
);
register_setting(
    'rushfilter-settings',
    'rushfilter_product_cat_name_settings_options'
);
add_settings_field(
    'rushfilter_product_cat_settings_input_field',
    __( 'Product Category Label: ', 'rush-filter' ),
    'rushfilter_product_cat_settings_input_field_callback',
    'rushfilter-settings',
    'rushfilter_product_lable_change_name_settings_section',
);



