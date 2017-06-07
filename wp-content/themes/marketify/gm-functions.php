<?php
add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
    add_image_size( 'custom-360', 360, 292 ); // 300 pixels wide (and unlimited height)
    add_image_size( 'custom-265', 265, 215, array('center','center') ); // 300 pixels wide (and unlimited height)

}




?>

