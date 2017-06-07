<?php
$SR_jforms = new jforms();

$request = SRP_PLUGIN::get_request();
$options = SRP_PLUGIN::get_options();
$app = SRP_PLUGIN::get_app();

SR_option_manager::option_listener();
$check = new bcheckbox_option();
?>
<script>

    function do_clear_history()
    {
        if(confirm('Are you sure you want to clear all history?'))
        {
            document.getElementById('clear_history_flag').value = '1';
            document.getElementById('options_from').submit();
        }
    }

    function do_clear_404()
    {
        if(confirm('Are you sure you want to clear all discovered 404?'))
        {
            document.getElementById('clear_404_flag').value = '1';
            document.getElementById('options_from').submit();
        }
    }

</script>
<form id="options_from" action="<?php echo $request->get_current_parameters(array("add","edit","del"));?>" method="post" class="form-horizontal" role="form" data-toggle="validator">
<br/><h4><span class="glyphicon glyphicon-cog"></span> General Options</h4><hr/>
    <div class="form-group">
        <label class="control-label col-sm-2" for="plugin_status">Plugin Status:</label>
        <div class="col-sm-10">
            <?php
                $drop=new dropdown_list("plugin_status");
                $drop->add('Enabled','1');
                $drop->add('Disabled','0');
				$drop->add('Disabled For Admin Only','2');
                $drop->run($SR_jforms);
                $drop->select($options->read_option_value('plugin_status'));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="show_redirect_box">Redirect Box:<br/><br/>Permalink:<br/><br/>Cache:</label>
        <div class="col-sm-10">
            <div style="margin-top:5px;"><?php $check->create_single_option('show_redirect_box',$options->read_option_value('show_redirect_box')) ?>	Show Redirect Box in posts and other selected <a target="_blank" href="options-general.php?page=<?php echo $app->get_plugin_slug()?>&SR_tab=redirect_manager&redirect_manager_tab=post_types">Post Types</a>.</div>
            <div style="margin-top:5px;"><?php $check->create_single_option('add_auto_redirect',$options->read_option_value('add_auto_redirect')) ?>	Add automatically a 301 redirect for the modified post permalinks. (Recommended)</div>
            <div style="margin-top:5px;"><?php $check->create_single_option('reflect_modifications',$options->read_option_value('reflect_modifications')) ?>	Reflect changes in post permalinks to the database. (Recommended)</div>
            <div style="margin-top:5px;"><?php $check->create_single_option('cache_enable',$options->read_option_value('cache_enable')) ?>	Enable caching redirects to boost performance. (Recommended)</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_general_options" value="save_general_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save General Options</button>
        </div>
    </div>

<br/><h4><span class="glyphicon glyphicon-time"></span> Redirection History Options</h4><hr/>
    <div class="form-group">
        <label class="control-label col-sm-2" for="history_status">History Status:</label>
        <div class="col-sm-10">
            <?php
            $drop=new dropdown_list("history_status");
            $drop->add('Enabled','1');
            $drop->add('Disabled','0');
            $drop->run($SR_jforms);
            $drop->select($options->read_option_value('history_status'));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2" for="history_limit">History Limit:</label>
        <div class="col-sm-10">
            <?php
            $drop=new dropdown_list("history_limit");
            $drop->add('7 days','7');
            $drop->add('1 month','30');
            $drop->add('2 months','60');
            $drop->add('3 months','90');
            $drop->run($SR_jforms);
            $drop->select($options->read_option_value('history_limit'));
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_history_options" value="save_history_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save History Options</button> <button type="button" onclick="do_clear_history();" id="clear_history" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Clear All History</button>
            <input id="clear_history_flag" type="hidden" name="clear_history" value="" />
        </div>
    </div>

<br/><h4><span class="glyphicon glyphicon-exclamation-sign"></span> 404 Error Pages Options</h4><hr/>
    <div class="form-group">
        <label class="control-label col-sm-2" for="p404_discovery_status">404 Discovery Status:</label>
        <div class="col-sm-10">
            <?php
            $drop=new dropdown_list("p404_discovery_status");
            $drop->add('Enabled','1');
            $drop->add('Disabled','0');
            $drop->run($SR_jforms);
            $drop->select($options->read_option_value('p404_discovery_status'));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="p404_rules">404 Rules Status:</label>
        <div class="col-sm-10">
            <?php
            $drop=new dropdown_list("p404_rules");
            $drop->add('Enabled','1');
            $drop->add('Disabled','0');
            $drop->run($SR_jforms);
            $drop->select($options->read_option_value('p404_rules'));
            ?> Manage <a target="_blank" href="options-general.php?page=<?php echo $app->get_plugin_slug()?>&SR_tab=404_manager&404_manager_tab=404_rules">404 Rules</a> and <a target="_blank" href="options-general.php?page=<?php echo $app->get_plugin_slug()?>&SR_tab=404_manager&404_manager_tab=general_rules">General 404 Rules</a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_404_options" value="save_404_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save 404 Options</button> <button type="button" onclick="do_clear_404();" id="clear_404" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Clear All Discovered</button>
            <input id="clear_404_flag" type="hidden" name="clear_404" value="" />
        </div>
    </div>

<br/><h4><span class="glyphicon glyphicon-trash"></span> Uninstall Options</h4><hr/>
    <div class="form-group">
        <label class="control-label col-sm-2" for="keep_data">Plugin Data:</label>
        <div class="col-sm-10">
            <div style="margin-top:5px;"><?php $check->create_single_option('keep_data',$options->read_option_value('keep_data')) ?> Keep redirection data after uninstall the plugin, this will be useful when you install it later.</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_uninstall_options" value="save_uninstall_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save Uninstall Options</button> <button type="submit" name="optimize_tables" value="optimize_tables" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-cog"></span> Optimize Database</button>
        </div>
    </div>
<br/><hr/>
    <div class="form-group">
        <div class="col-sm-10">
            <button type="submit" name="save_all_options" value="save_all_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save All Options</button> <button type="submit" name="reset_options" value="reset_options" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-refresh"></span> Reset Options</button>
        </div>
    </div>
<br/>
</form>
<?php
$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();