<?php

$phptabs= SRP_PLUGIN::get_tabs();
$phptabs->init();
$phptabs->set_parameter('redirect_manager_tab');
$phptabs->set_sub_type();
$phptabs->set_ignore_parameter(array('del','search','page_num','add','edit','page404','grpID','shown','sort','link_type','link','return','post_operation','post_operation_id','country'));
$phptabs->add_file_tab('redirects','Redirects','option_redirects.php','file');
$phptabs->add_file_tab('redirects_rules','General Rules','option_redirects.php','file');
$phptabs->add_file_tab('custom_groups','Custom Groups','option_groups_custom.php','file');
$phptabs->add_file_tab('system_groups','System Groups','option_groups_system.php','file');
$phptabs->add_file_tab('post_types','Post Types','option_post_type.php','file');
$phptabs->add_file_tab('redirect_cache','Redirect Cache','option_redirect_cache.php','file');
$phptabs->add_file_tab('SSL','HTTP/HTTPS','option_redirect_SSL.php','file');
$phptabs->run();

?>