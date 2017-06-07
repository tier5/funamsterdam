<?php
$SR_jforms = new jforms();

$request = SRP_PLUGIN::get_request();
$options = SRP_PLUGIN::get_options();
$app = SRP_PLUGIN::get_app();

SR_option_manager::manage_404_options();
SR_option_manager::clear_404_errors();
$check = new bcheckbox_option();
?>
<script>
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
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_404_options" value="save_404_options" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save 404 Options</button> <button type="button" onclick="do_clear_404();" id="clear_404" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Clear All Discovered</button>
            <input id="clear_404_flag" type="hidden" name="clear_404" value="" />
        </div>
    </div>
    <br/>
    </form>
    <br/>


<?php
$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();