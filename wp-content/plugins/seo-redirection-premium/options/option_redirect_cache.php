<?php
$SR_jforms = new jforms();

$request = SRP_PLUGIN::get_request();
$app = SRP_PLUGIN::get_app();
$options = SRP_PLUGIN::get_options();

if(intval($options->read_option_value("cache_enable"))==0)
{
    $app->echo_message("<b>Cache is disabled!, you can enable it from General Options</b>","error","echo");   
}

$SR_redirect_cache = new clogica_SR_redirect_cache();
if($request->post('delete_cache')!='')
{
    $SR_redirect_cache->free_cache(1);
}

?>

    <h4>Redirect Cache</h4><hr/>
<form action="<?php echo $request->get_current_parameters(array("add","edit","del"));?>" method="post" class="form-horizontal" role="form" data-toggle="validator">
    <div class="container">
        <p><b>Redirect Cache</b> is used to improve the plugin performance and speed up redirect process.</p>
    </div>

    <div class="row">
        <div class="col-sm-10 database_icon">
            <h4 style="display: inline; color: #7dcc1a"><b><?php echo $SR_redirect_cache->count_cache(); ?> Redirects</b></h4> are cached in the database<br/><br/>
            <button type="submit" name="delete_cache" value="delete_cache" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Clear All Cache</button>
        </div>
    </div>


</form>





<?php
$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();