<?php

global $wpdb;

$request = SRP_PLUGIN::get_request();
$app = SRP_PLUGIN::get_app();
$SR_jforms = new jforms();

$current_link=$request->get_current_parameters(array('del','search','page_num','add','edit'));

    if($request->post('search')!='')
    {
        echo "<script>window.location='" . $current_link . '&search=' . $request->post('search') . "'</script>";
    }

$no_rsrc_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','rsrc'));

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

        function go_rsrc(){
            var sword = document.getElementById('rsrc').value;
            window.location = "<?php echo $no_rsrc_current_link?>&rsrc=" + sword ;
        }

    </script>
    <form id="myform" action="" method="post" class="form-horizontal" role="form">
        <div class="form-group">

            <div style="vertical-align: middle" class="col-sm-5">
                <?php
            $drop = new dropdown_list('rsrc');
            $drop->onchange("go_rsrc()");
            $drop->add('All History','');
            $drop->add('Redirects','Redirect');
            $drop->add('404 Rule','404Rule');
            $drop->add('General 404','404');
            $drop->run($SR_jforms);
            $drop->select($request->get('rsrc'));
            ?></div>
            <div style="text-align: right; vertical-align: middle" class="col-sm-7">
                <input onkeyup="if (event.keyCode == 13) go_search();" value="<?php echo htmlentities($request->get('search')); ?>" type="text" style="width: 250px; height: 30px;" class="small_text_box" id="search" name="search" placeholder="Search Keywords" >
                <a style="height: 31px; " href="javascript:go_search();" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> Search</a>
                <a style="height: 31px; " href="<?php echo htmlentities($request->get_current_parameters(array('search')));?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list"></span> All</a>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th class="btn btn-default table_header" style="width: 70px">Time</th>
                        <th class="btn btn-default table_header">Redirect</th>
                        <th class="btn btn-default table_header toolcell">Type</th>
                        <th class="btn btn-default table_header toolcell">Ref</th>
                        <th class="btn btn-default table_header" style="width: 140px">Address</th>
                        <th class="btn btn-default table_header" style="width: 85px">Agent</th>
                        <th class="btn btn-default table_header toolcell">Class</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $where =   " where blog='" . get_current_blog_id() . "' ";

                    if($request->get('rsrc')!='')
                    {
                        $rsrc=$request->get('rsrc');
                        if($where=="")
                        {
                            $where = " where rsrc='$rsrc' ";
                        }else
                        {
                            $where = $where . " and rsrc='$rsrc' ";
                        }
                    }


                    $order = " order by ID desc ";
                    if($request->get('search')!='')
                    {
                        $search = urldecode($request->get('search'));
                        $where = $where . " and (rfrom like '%%$search%%' or rto like '%%$search%%' or ctime like '%%$search%%' or referrer like '%%$search%%'   or country like '%%$search%%'   or ip like '%%$search%%' 	or os like '%%$search%%' or browser like '%%$search%%' or rsrc like '%%$search%%' or rtype like '%%$search%%'  )";
                    }


                    $pagination= new clogica_pagination(SR_database::WP_SEO_Redirection_LOG(),$where);
                    $pagination->set_rows(10);
                    $limit = $pagination->get_sql_limit();
                    $logs = $wpdb->get_results("select * from " . SR_database::WP_SEO_Redirection_LOG() . " $where $order $limit ");
                    $i=0;
                    foreach($logs as $log){
                        $i++;
                        ?>
                        <tr>
                            <td style="width: 70px"><?php echo $log->ctime;?></td>
                            <td><div class='arrow_from'> <a target='_blank' href='<?php echo $request->make_absolute_url($log->rfrom) ?>'><?php echo $log->rfrom ?></a></div><div class='arrow_to'> <a target='_blank' href='<?php echo $request->make_relative_url($log->rto) ?>'><?php echo $log->rto;?></a></div></td>
                            <td class="toolcell"><?php echo $log->rtype ?></td>
                            <td class="toolcell">
                                <?php if($log->referrer!=''){?>
                                    <a target="_blank" class="btn btn-primary btn-xs tool" href="<?php echo $log->referrer; ?>" title="Referrer: <?php echo $log->referrer; ?>"><span aria-hidden="true" class="glyphicon glyphicon-link"></span></a>
                                <?php }else{ ?>
                                    <label class="btn btn-default btn-xs tool disabled"><span aria-hidden="true" class="glyphicon glyphicon-link"></span></label>
                                <?php } ?>
                            </td>
                            <td style="width: 140px"><?php echo $log->country; ?><br/><?php echo $log->ip; ?></td>
                            <td style="width: 116px"><?php echo $log->os; ?><br/><?php echo $log->browser; ?></td>
                            <td class="toolcell"><?php
                                $source['Redirect'] ="options-general.php?page={$app->get_plugin_slug()}&SR_tab=redirect_manager&edit=" . $log->rID;
                                $source['404Rule'] ="options-general.php?page={$app->get_plugin_slug()}&SR_tab=404_manager&404_manager_tab=404_rules&edit=" . $log->rID;
                                $source['404']="options-general.php?page={$app->get_plugin_slug()}&SR_tab=404_manager&404_manager_tab=general_rules";
                                if(array_key_exists($log->rsrc,$source))
                                    echo "<a href='" . $source[$log->rsrc]  . "' target='_blank'>". $log->rsrc . "</a>";
                                else
                                    echo $log->rsrc;
                                ?></td>
                        </tr>
                    <?php } if($i==0){ ?>
                        <tr><td colspan="7" style="text-align: center"> No data available!</td></tr>
                    <?php } ?>

                    </tbody>
                </table>

            </div>
            <?php $pagination->run();?>
        </div>
    </form>

<?php
$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();
