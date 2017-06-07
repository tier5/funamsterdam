<?php
global $wpdb;

$request = SRP_PLUGIN::get_request();
$security = SRP_PLUGIN::get_security();

$table_name = SR_database::WP_SEO_404_links();
$SR_jforms = new jforms();

$current_link=$request->get_current_parameters(array('del','search','page_num','add','edit'));
$no_tabs_link = $request->get_current_parameters(array('del','search','page_num','add','edit','shown','sort','link_type','SR_tab'));
?>
    <br/>
    <form id="myform" action="" method="post" class="form-horizontal" role="form">

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
                    $order=" order by counter desc ";
                    $links_404 = $wpdb->get_results("select * from " . SR_database::WP_SEO_404_links() . " $where $order limit 50 ");
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
        </div>

    </form>



<?php

$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();
