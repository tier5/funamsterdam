<?php

$phptabs= SRP_PLUGIN::get_tabs();
$phptabs->init();
$phptabs->set_parameter('404_manager_tab');
$phptabs->set_sub_type();
$phptabs->set_ignore_parameter(array('del','search','page_num','add','edit','page404','grpID','shown','sort','link_type','link'));
$phptabs->add_file_tab('discovered_404_errors','Discovered 404 Errors','option_404_list.php','file');
$phptabs->add_file_tab('top_404_errors','Top Traffic 404 Errors','option_404_top.php','file');
$phptabs->add_file_tab('404_rules','404 Rules','option_404_rules.php','file');
$phptabs->add_file_tab('general_rules','General Rules','option_404_general_rules.php','file');
$phptabs->add_file_tab('404_options','404 Options','option_404_options.php','file');
$phptabs->run();


