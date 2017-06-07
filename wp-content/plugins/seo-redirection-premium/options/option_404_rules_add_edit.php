<?php
global $wpdb;

$request = SRP_PLUGIN::get_request();

$SR_redirect_cache = new clogica_SR_redirect_cache();
$current_link=$request->get_current_parameters(array('del','search','page_num','add','edit'));


/* Default Values ----------------------------- */
$enabled=1;
$redirect_from_type='Folder';
$redirect_to_type='Page';
$redirect_type='301';
$redirect_from='';
$redirect_to='';

if($request->get('add')!='')
{
    echo '<h4>Add New Rule</h4><hr/>';
}else if(intval($request->get('edit'))>0) {
    echo '<h4>Update Existing Rule</h4><hr/>';
    // load the defaults from databse ...
    $item = $wpdb->get_row(" select * from " . SR_database::WP_SEO_Redirection() . " where blog='" . get_current_blog_id() . "' and ID=". intval($request->get('edit')));
    $redirect_from=$item->redirect_from;
    $redirect_to=$item->redirect_to;
    $redirect_type=$item->redirect_type;
    $redirect_from_type=$item->redirect_from_type;
    $enabled=$item->enabled;
}
?>
    <form method="post" action="<?php echo $request->get_current_parameters(array("add","edit"));?>" class="form-horizontal" role="form" data-toggle="validator">

        <div class="form-group">
            <label class="control-label col-sm-2" for="enabled">Rule Status:</label>
            <div class="col-sm-10">
                <?php
                $drop = new dropdown_list('enabled');
                $drop->add('Enabled','1');
                $drop->add('Disabled','0');
                $drop->run($SR_jforms);
                $drop->select($enabled);
                ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="redirect_from">Rule:</label>
            <div class="col-sm-10">
                <?php

                    $drop = new dropdown_list('redirect_from_type');
                    $drop->add('Folder', 'Folder', 'Folder_1');
                    $drop->add('Start With', 'StartWith', 'StartWith_1');
                    $drop->add('End With', 'EndWith', 'EndWith_1');
                    $drop->add('Contain', 'Contain', 'Contain_1');
                    $drop->add('File Type', 'Filetype', 'Filetype_1');
                    $drop->add('Regex', 'Regex', 'Regex_1');
                    $drop->add('Coming from Search Engine', 'CSE', 'CSE_1');
                    $drop->add('Coming from Specific Site', 'CSS', 'CSS_1');
                    $drop->add('Coming from My Site', 'CMS', 'CMS_1');
                    $drop->add('Coming from Specific Page', 'CSP', 'CSP_1');
                    $drop->add('Coming from Specific Folder', 'CSF', 'CSF_1');
                    $drop->run($SR_jforms);
                    $drop->select($redirect_from_type);
                    ?>
                    <input type="text" style="width: 350px" class="small_text_box" value="<?php echo $redirect_from ?>"
                           id="redirect_from" name="redirect_from" placeholder="Redirect From"
                           data-error="This Field can not be empty" required>

            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="redirect_to">Redirect To:</label>
            <div class="col-sm-10">
                <input type="text" style="width: 350px" class="small_text_box" value="<?php echo $redirect_to ?>" id="redirect_to" name="redirect_to" placeholder="Redirect To" data-error="This Field can not be empt" required>

            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Redirect Type:</label>
            <div class="col-sm-10">
                <?php
                $drop = new dropdown_list('redirect_type');

                $drop->add('301 (SEO)','301');
                $drop->add('302','302');
                $drop->add('307','307');

                $drop->run($SR_jforms);
                $drop->select($redirect_type);
                ?>
            </div>
        </div>

        <div class="form-group">
            <br/>
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="save" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  <a href="<?php echo $request->get_current_parameters(array("add","edit"));?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Cancel</a>
            </div>
            <br/><br/>
        </div>
        <?php
        $fildname="noaction";
        $fildval="";

        if($request->get("add")!="")
        {
            $fildname="add";
            $fildval="1";
        }elseif($request->get("edit","int")!=0)
        {
            $fildname="edit";
            $fildval=$request->get("edit","int");
        }
        echo "<input type=\"hidden\" name=\"$fildname\" value=\"$fildval\" >";
        ?>

    </form>

    <script language="JavaScript">
        jQuery(document).ready(function($){

           function  redirect_from_type_change(){
               if($('#redirect_from_type').val() == 'CSE' || $('#redirect_from_type').val() == 'CMS'){
                   $('#redirect_from').fadeOut();
                   $('#redirect_from').prop('required', false);
               }else
               {
                   $('#redirect_from').fadeIn();
                   $('#redirect_from').prop('required', true);
               }
           }
            redirect_from_type_change();

            $("#redirect_from_type").bind("change", function () {
                redirect_from_type_change();
             });
        });
    </script>