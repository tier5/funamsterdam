<?php

global $wpdb;

$request = SRP_PLUGIN::get_request();
$options = SRP_PLUGIN::get_options();
$app = SRP_PLUGIN::get_app();
$security = SRP_PLUGIN::get_security();


$table_name = SR_database::WP_SEO_404_links();
$SR_jforms = new jforms();

if($request->post('clear_history')!='')
{
    // to be fixed after moving to options object
    $wpdb->query(" TRUNCATE TABLE " . SR_database::WP_SEO_404_links());
    $app->echo_message("404 list is cleared successfully!");
}

$current_link=$request->get_current_parameters(array('del','search','page_num','add','edit'));
$no_link_type_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','link_type'));
$no_sort_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','sort'));
$no_shown_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','shown'));
$no_tabs_link = $request->get_current_parameters(array('del','search','page_num','add','edit','shown','sort','link_type','SR_tab'));
$no_country_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','country'));
?>

    <script type="text/javascript">
        function go_search(){
            var sword = encodeURIComponent(document.getElementById('search').value);
            if(sword!=''){
                window.location = "<?php echo $current_link?>&search=" + sword ;
            }else
            {
                alert('Please input any search words!');
                document.getElementById('search').focus();
            }
        }

        function go_link_type(){
            var sword = document.getElementById('link_type').value;
            window.location = "<?php echo $no_link_type_current_link?>&link_type=" + sword ;
        }

        function go_sort(){
            var sword = document.getElementById('sort').value;
            window.location = "<?php echo $no_sort_current_link?>&sort=" + sword ;
        }

        function go_shown(){
            var sword = document.getElementById('shown').value;
            window.location = "<?php echo $no_shown_current_link?>&shown=" + sword ;
        }

        function go_country(){
            var sword = document.getElementById('country').value;
            window.location = "<?php echo $no_country_current_link?>&country=" + sword ;
        }

        function do_clear_history()
        {
            if(confirm('Are you sure you want to clear all 404 list?'))
            {
                document.getElementById('clear_history_flag').value = '1';
                document.getElementById('myform').submit();
            }
        }
    </script>
    <br/>
    <form id="myform" action="" method="post" class="form-horizontal" role="form">

        <div class="form-group">

            <div class="col-sm-4">
                <?php
                $drop = new dropdown_list('sort');
                $drop->onchange("go_sort()");
                $drop->add('Order by Date','');
                $drop->add('Order by Views','views');
                $drop->add('Order by Type','type');
                $drop->run($SR_jforms);
                $drop->select($request->get('sort'));
                ?>

                <?php
                $drop = new dropdown_list('shown');
                $drop->onchange("go_shown()");
                $drop->add('Seen by Visitors','');
                $drop->add('Seen by Bots','bots');
                $drop->add('Show All','all');
                $drop->run($SR_jforms);
                $drop->select($request->get('shown'));
                ?>
            </div>

            <div style="text-align: right; vertical-align: middle" class="col-sm-8">

                <?php
                $drop = new dropdown_list('country');
                $drop->onchange("go_country()");
                $drop->add('All Countries','');
                $countries = $wpdb->get_results("select country from " . SR_database::WP_SEO_404_links() . " where blog='" . get_current_blog_id() . "' group by country;");
                foreach ( $countries as $country ) {
                    $drop->add($country->country, $country->country);
                }
                $drop->run($SR_jforms);
                $drop->select($request->get('country'));

                ?>
                <?php
                $drop = new dropdown_list('link_type');
                $drop->onchange("go_link_type()");
                $drop->add('All Types','');
                $drop->add('Links','1');
                $drop->add('Images','2');
                $drop->add('CSS/JS','3');
                $drop->add('Other Files','4');
                $drop->run($SR_jforms);
                $drop->select($request->get('link_type'));
                ?>
                <input onkeyup="if (event.keyCode == 13) go_search();" value="<?php echo htmlentities($request->get('search')); ?>" type="text" style="width: 120px; height: 30px;" class="small_text_box" id="search" name="search" placeholder="Search Keywords" >
                <a style="height: 31px; " href="javascript:go_search();" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> Search</a>
                <a id="show_all" style="height: 31px; " href="<?php echo $request->get_current_parameters(array('search','shown','sort','link_type'));?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list"></span> All</a>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-12">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="btn btn-default table_header toolcell"><span class="btn btn-default btn-xs"><span aria-hidden="true" class="glyphicon glyphicon-share-alt"></span></span></th>
                        <th class="btn btn-default table_header" style="width: 140px">Discovered</th>
                        <th class="btn btn-default table_header">Link</th>
                        <th class="btn btn-default table_header toolcell"><span class="btn btn-default btn-xs"><span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></span></th>
                        <th class="btn btn-default table_header toolcell">Ref</th>
                        <th class="btn btn-default table_header" style="text-align: center">IP</th>
                        <th class="btn btn-default table_header" style="text-align: center">Country</th>
                        <th class="btn btn-default table_header" style="text-align: center">OS</th>
                        <th class="btn btn-default table_header" style="text-align: center">Browser</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $where="where blog='" . get_current_blog_id() . "' ";

                    if($request->get('search')!='')
                    {
                        $search = urldecode($request->get('search'));
                        if($where=="")
                        {
                            $where = " where (ctime like '%%$search%%' or link like '%%$search%%' or referrer like '%%$search%%' or ip like '%%$search%%' or country like '%%$search%%' or  os like '%%$search%%' or  browser like '%%$search%%' ) ";
                        }else
                        {
                            $where = $where . " and (ctime like '%%$search%%' or link like '%%$search%%' or referrer like '%%$search%%' or ip like '%%$search%%' or country like '%%$search%%' or  os like '%%$search%%' or  browser like '%%$search%%' ) ";
                        }
                    }

                    if($request->get('link_type')!='')
                    {
                        $link_type=$request->get('link_type');
                        if($where=="")
                        {
                            $where = " where link_type='$link_type' ";
                        }else
                        {
                            $where = $where . " and link_type='$link_type' ";
                        }
                    }

                    if($request->get('country')!='')
                    {
                        $country=$request->get('country');
                        if($where=="")
                        {
                            $where = " where country='$country' ";
                        }else
                        {
                            $where = $where . " and country='$country' ";
                        }
                    }

                    if($request->get('shown')!='all')
                    {
                        $shown=$request->get('shown');
                        $sql="(browser<>'GoogleBot' and browser<>'SearchBot' and os<>'GoogleBot' and os<>'SearchBot')";
                        if($request->get('shown')=='bots')
                        {
                            $sql="(browser='GoogleBot' or browser='SearchBot' or os='GoogleBot' or os='SearchBot')";
                        }

                        if($where=="")
                        {
                            $where = " where $sql ";
                        }else
                        {
                            $where = $where . " and $sql ";
                        }
                    }


                    $order = "order by ctime desc ";
                    if($request->get('sort')!='')
                    {
                        $sort = $request->get('sort');
                        if($sort == 'views')
                        {
                            $order=" order by counter desc ";
                        }
                        else if($sort == 'type')
                        {
                            $order=" order by link_type asc ";
                        }
                    }

                    $pagination= new clogica_pagination($table_name,$where);
                    $pagination->set_rows(10);
                    $limit = $pagination->get_sql_limit();
                    $links_404 = $wpdb->get_results("select * from " . SR_database::WP_SEO_404_links() . " $where $order $limit ");
                    $i=0;
                    foreach($links_404 as $link){
                        $i++;
                        ?>
                        <tr>
                            <td class="toolcell">
                                <a class="btn btn-success btn-xs tool" href="<?php echo $no_tabs_link ?>&SR_tab=redirect_manager&add=1&link=<?php echo $security->encode_url($link->link);?>" title="Redirect this link"><span aria-hidden="true" class="glyphicon glyphicon-share-alt"></span></a>
                            </td>
                            <td style="width: 140px"><?php echo $link->ctime; ?></td>
                            <td><a href="<?php echo $request->make_absolute_url($link->link);?>" target="_blank"><span class="fa fa-external-link-square"></span></a> <?php echo $link->link;?></td>
                            <td class="toolcell"><?php echo $link->counter;?></td>
                            <td class="toolcell">
                                <?php if($link->referrer!=''){?>
                                <a target="_blank" class="btn btn-primary btn-xs tool" href="<?php echo $link->referrer; ?>" title="Referrer: <?php echo $link->referrer; ?>"><span aria-hidden="true" class="glyphicon glyphicon-link"></span></a>
                                <?php }else{ ?>
                                    <label class="btn btn-default btn-xs tool disabled"><span aria-hidden="true" class="glyphicon glyphicon-link"></span></label>
                                <?php } ?>

                            </td>
                            <td style="width: 110px; text-align: center"><?php echo $link->ip;?></td>
                            <td style="width: 140px; text-align: center"><?php echo $link->country;?></td>
                            <td style="width: 90px; text-align: center"><?php echo $link->os;?></td>
                            <td style="width: 120px; text-align: center"><?php echo $link->browser;?></td>
                        </tr>
                    <?php } if($i==0){ ?>
                        <tr><td colspan="9" style="text-align: center"> No data available!</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php $pagination->run();?>

        </div>
        <div style="text-align: right">* Too many 404 errors? <a target="_blank" href="http://www.clogica.com/kb/too-many-404-errors.htm">click here to see why?</a></div>

    </form>


<?php
$SR_jforms->add_script("
$('#search').focusin(function() {
$('#show_all').hide();
$('#search').animate({width: '180px'});
});

$('#search').focusout(function() {
$('#show_all').show();
$('#search').animate({width: '130px'});
});

");
$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();
