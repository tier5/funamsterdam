<?php

global $wpdb;

$request = SRP_PLUGIN::get_request();
$app = SRP_PLUGIN::get_app();
$options = SRP_PLUGIN::get_options();
$misc = SRP_PLUGIN::get_misc();
$security = SRP_PLUGIN::get_security();

$table_name =SR_database::WP_SEO_Redirection();
$SR_jforms = new jforms();
$SR_redirect_cache = new clogica_SR_redirect_cache();
$redirect_manager_tab =  $request->get('redirect_manager_tab');

//- Add Update Code  ------------------------------------
if($request->post('add')!='' || $request->post('edit','int')>0)
{
	$search_for = '';
	$replace_with  = '';
	
    $enabled=$request->post("enabled");
    $grpID=$request->post("grpID","int");
    $redirect_from_type=$request->post("redirect_from_type");
    $redirect_from_folder_settings = $request->post("redirect_from_folder_settings");
    $redirect_from_subfolders=$request->post("redirect_from_subfolders");
    $redirect_to_folder_settings=$request->post("redirect_to_folder_settings");
    $redirect_to_type=$request->post("redirect_to_type");
    $redirect_type=$request->post("redirect_type");
   
    $search_for=$request->post("search_for");
    $replace_with=$request->post("replace_with");

    $redirect_from=urldecode($request->make_relative_url($request->post('redirect_from')));
	
	if($search_for!='' && $replace_with !='')
	{
		$redirect_from = $search_for."|".$replace_with;
	}
	
    $redirect_to=$request->make_relative_url($request->post('redirect_to'));

    $wpdb->query("delete from " . SR_database::WP_SEO_404_links() . " where blog='" . get_current_blog_id() . "' and link='$redirect_from' ");

    if(($request->post('edit','int')>0 && $wpdb->get_var("select ID from " . SR_database::WP_SEO_Redirection() . " where blog='" . get_current_blog_id() . "' and redirect_from='$redirect_from' and cat='link'")==$request->post('edit','int')) || $wpdb->get_var("select redirect_from from $table_name where blog='" . get_current_blog_id() . "' and redirect_from='$redirect_from' and cat='link'")!=$redirect_from)
    {
		
        $regex="";
        if($redirect_from_type =='Folder')
        {
            if(substr($redirect_from,-1)!='/')
            {
                $redirect_from = $redirect_from . '/';
            }

            if($redirect_from_folder_settings==2)
            {
                if($redirect_from_subfolders ==0)
                {
                    $regex= '^'. $misc->regex_prepare($redirect_from) . '.*';
                }
                else
                {
                    $regex= '^'. $misc->regex_prepare($redirect_from) . '[^/]*$';
                }
            }
            else if($redirect_from_folder_settings==3)
            {
                if($redirect_from_subfolders ==0)
                {
                    $regex= '^'. $misc->regex_prepare($redirect_from) . '.+';
                }
                else
                {
                    $regex= '^'. $misc->regex_prepare($redirect_from) . '[^/]+$';
                }
            }
        }
        else if($redirect_from_type =='Regex')
        {
            $regex= $redirect_from;
        }
        else if($redirect_from_type =='Contain')
        {
            $regex= '^.*' . $misc->regex_prepare($redirect_from) . '.*$';
        }
        else if($redirect_from_type =='StartWith')
        {
            $regex= '^' . $misc->regex_prepare($redirect_from) . '.*$';
        }
        else if($redirect_from_type =='EndWith')
        {
            $regex= '^.*' . $misc->regex_prepare($redirect_from) . '$';
        }
        else if($redirect_from_type =='Filetype')
        {
            $regex= '^.*' . $misc->regex_prepare( '.' . $redirect_from) . '$';
        }

        
        if ($redirect_from_type=='Page' || $redirect_from_type=='Regex')
        {
            $redirect_from_folder_settings="";
            $redirect_from_subfolders="";
        }

        if ($redirect_to_type=='Page')
        {
            $redirect_to_folder_settings="";
        }

        if($redirect_to_type =='Folder')
        {
            if(substr($redirect_to,-1)!='/')
                $redirect_to= $redirect_to. '/';
        }

        if($request->post('edit')!='')
        {
            $ID = $request->post('edit','int');
            $wpdb->query("update $table_name set enabled='$enabled', grpID=$grpID, redirect_from_type='$redirect_from_type', redirect_to_type='$redirect_to_type', redirect_from_folder_settings='$redirect_from_folder_settings', redirect_from_subfolders='$redirect_from_subfolders', redirect_to_folder_settings='$redirect_to_folder_settings', redirect_type='$redirect_type', redirect_from='$redirect_from', redirect_to='$redirect_to', regex='$regex' where blog='" . get_current_blog_id() . "' and ID=$ID    ");
			
			
            SR_redirect_manager::clear_fixed_404($redirect_from, $regex);
            $app->echo_message("<b>The redirect is updated successfully!</b>",'success') ;                       
            $SR_redirect_cache->free_cache();
        }else
        {
            $cat='link';
		
            $wpdb->query("insert into $table_name(enabled,grpID,redirect_from_type,redirect_from_folder_settings,redirect_from_subfolders,redirect_to_type,redirect_to_folder_settings,redirect_type,redirect_from,redirect_to,cat,regex,blog) values('$enabled','$grpID','$redirect_from_type','$redirect_from_folder_settings','$redirect_from_subfolders','$redirect_to_type','$redirect_to_folder_settings','$redirect_type','$redirect_from','$redirect_to','$cat','$regex','" . get_current_blog_id() . "') ");
		
            SR_redirect_manager::clear_fixed_404($redirect_from, $regex);
            $app->echo_message("<b>New redirect is added successfully!</b>",'success') ;
            $SR_redirect_cache->free_cache();
        }
    }else
    {
        $app->echo_message("<b>This redirect is already exists!</b>",'danger') ;
    }

    if($request->post('add')!='')
    {
        if($request->post('post_operation')=='draft')
        {
            if($request->post('post_operation_id','int')>0)
            {
                $draft_post = array();
                $draft_post['ID'] = $request->post('post_operation_id','int');
                $draft_post['post_status'] = 'draft';
                wp_update_post( $draft_post );
            }
        }elseif($request->post('post_operation')=='trash')
        {
            if($request->post('post_operation_id','int')>0)
            wp_trash_post($request->post('post_operation_id','int'));
            $_POST['return']='';
        }
    }
}

//- Add Delete Forms  ------------------------------------
if($request->get('add')!='' || $request->get('edit')!='' )
{
    require "option_redirects_add_edit.php";

}else if($request->post('change_group')!='' && $request->post('sel_items')!='')
{
    require "option_change_group.php";
}
else
{

//- Check for return or any action on posts
    if($request->get('del','int')>0)
    {
        $ID=$request->get('del','int');
        $wpdb->query("delete from $table_name where blog='" . get_current_blog_id() . "' and ID='$ID'");
        $app->echo_message("<b>The redirect is deleted successfully!</b>",'success') ;
        $SR_redirect_cache->free_cache();
    }
    if($request->post('return')!='' && $request->post('post_operation')!='trash')
    {
        echo "<div style='text-align: center'></br><br/><h4><span class=\"glyphicon glyphicon-refresh\"></span> Redirecting you back, please wait ...</h4></br></br></div>";
        $misc->js_redirect($request->post('return'));
    }
    elseif($request->get('return')!='' && $request->post('post_operation')!='trash')
    {
        echo "<div style='text-align: center'></br><br/><h4><span class=\"glyphicon glyphicon-refresh\"></span> Redirecting you back, please wait ...</h4></br></br></div>";
        $misc->js_redirect(urldecode($request->get('return')));
    }
//- List Delete Code  ------------------------------------

    $check = new bcheckbox_option();
    $current_link=$request->get_current_parameters(array('del','search','page_num','add','edit'));
    $no_group_current_link=$request->get_current_parameters(array('del','search','page_num','add','edit','grpID'));
    if($request->get('grpID')=='')
    {
        $current_link=$no_group_current_link;
    }

    if($request->post('search')!='')
    {
        echo "<script>window.location='" . $current_link . '&search=' . $request->post('search','title') . "'</script>";
    }
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
        function go_group(){
            var sword = document.getElementById('grpID').value;
            window.location = "<?php echo $no_group_current_link?>&grpID=" + sword ;
        }
    </script>
<?php

//- Delete Code  ------------------------------------

    if($request->post('sel_items')!='' && $request->post('change_group')=='')
    {
        $IDs=$request->post('sel_items');
        $wpdb->query("delete from $table_name where blog='" . get_current_blog_id() . "' and ID in ($IDs)");
        $count = count(explode(',',$IDs));
        if($count>1)
        {
            $app->echo_message("<b>$count Redirects are deleted successfully!</b>",'success') ;
        }else
        {
            $app->echo_message("<b>$count Redirect is deleted successfully!</b>",'success') ;
        }
        $SR_redirect_cache->free_cache();
    }
    elseif($request->post('change_group')!='' && $request->post('sel_items')=='')
    {
        $app->echo_message("<b>Please select the redirects you need to apply the group change on them!</b>",'error') ;
    }

//- Change Group Code  ------------------------------------

    if($request->post('save_groups')!='' && $request->post('change_ids')!='')
    {
        $IDs=$request->post('change_ids');
        $change_grpID=$request->post('grpID');
        $wpdb->query("update $table_name set grpID=$change_grpID where blog='" . get_current_blog_id() . "' and ID in ($IDs)");
        $app->echo_message("<b>Group is changed successfully!</b>",'success') ;
    }

    ?>
<br/>
<form id="myform" action="" method="post" class="form-horizontal" role="form">
    <div class="form-group">

        <div class="col-sm-5">
            <a href="<?php echo $current_link;?>&add=1" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Add New</a>
            <button type="submit" name="change_group" value="change_group" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-link"></span> Change Group</button>
            <a href="#" onclick="if(confirm('Are you sure you want to delete the selected redirects?')) document.getElementById('myform').submit();" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Delete</a>
        </div>

        <div style="text-align: right; vertical-align: middle" class="col-sm-7">
            <?php
            $drop = new dropdown_list('grpID');
            $drop->onchange("go_group()");
            $drop->add('All Groups','');
            $groups = $wpdb->get_results("select * from `".  SR_database::WP_SEO_Groups() . "` where blog='" . get_current_blog_id() . "' order by group_type desc;");
                foreach ( $groups as $group ) {

                    $count= $wpdb->get_var("select count(*) as cnt from `$table_name` where cat='link' and grpID=" . $group->ID);
                    $drop->add($group->group_title . ' (' . $count . ')' ,$group->ID);
                }
            $drop->run($SR_jforms);
            $drop->select($request->get('grpID'));
            ?>
               <input onkeyup="if (event.keyCode == 13) go_search();" value="<?php echo $request->get('search','textbox');?>" type="text" style="max-width: 200px; height: 30px;" class="small_text_box" id="search" name="search" placeholder="Search Keywords" >
                <a style="height: 31px; " href="javascript:go_search();" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> Search</a>
            <a style="height: 31px; " href="<?php echo $request->get_current_parameters(array('search'));?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list"></span> All</a>
        </div>

    </div>
        <div class="form-group">
            <div class="col-sm-12">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th class="btn btn-default table_header toolcell"><?php
                        $check->set_group("sel_items");
                        $check->set_primary_style();
                        $check->create_check_all_option();
                        ?></th>
                    <th class="btn btn-default table_header toolcell">Edit</th>
                    <th class="btn btn-default table_header">Redirect from</th>
                    <th class="btn btn-default table_header"><?php if($redirect_manager_tab == 'redirects_rules'){echo 'Rule';}else{echo 'Redirect to';}?></th>
                    <th style="width: 85px; text-align: center" class="btn btn-default table_header ">Type</th>
                </tr>
                </thead>
                <tbody>
                <?php
					if($redirect_manager_tab == 'redirects_rules')
					{
						$where_cond = " and redirect_from_type = 'Replace'";
					}else{
						$where_cond = " and redirect_from_type <> 'Replace'";
					}
                    $where=" where cat='link' and blog='" . get_current_blog_id() . "' ".$where_cond;
                    if($request->get('search')!='')
                    {
                        $search = urldecode($request->get('search'));
                        $where = $where . " and (redirect_from like '%%$search%%' or redirect_to like '%%$search%%' or redirect_type like '%%$search%%'  )";
                    }

                    if($request->get('grpID')!='')
                    {
                        $grpID = $request->get('grpID');
                        $where = $where . " and (grpID=$grpID)";
                    }

                    $where = $where . " order by ID desc";
                    $pagination= new clogica_pagination($table_name,$where);
                    $pagination->set_rows(10);
                    $limit = $pagination->get_sql_limit();
                    $redirects = $wpdb->get_results("select * from $table_name $where $limit ");
                    $i=0;
                    foreach($redirects as $redirect){
                        $i++;
                ?>
                <tr>
                    <td scope="row" class="toolcell"><?php $check->create_grouped_option($redirect->ID)?></td>
                    <td class="toolcell">
                        <a class="btn btn-primary btn-xs tool" href="<?php echo $request->get_current_parameters(array('edit'));?>&edit=<?php echo $redirect->ID; ?>"><span aria-hidden="true" class="fa fa-pencil"></span></a>
                    </td>
                    <td><span class="<?php echo $redirect->redirect_from_type ?>_<?php echo $redirect->enabled ?>"></span><?php if($redirect->redirect_from_type == 'Page' || $redirect->redirect_from_type == 'Folder' ){ ?><a href="<?php echo $request->make_absolute_url($redirect->redirect_from);?>" target="_blank"><span class="fa fa-external-link-square"></span></a> <?php }?>
					<?php if($redirect->redirect_from_type == 'Replace'){$redirect_from_ = explode('|',$redirect->redirect_from); echo "<b>Replacing </b> ".$redirect_from_[0]." <b>With </b>".$redirect_from_[1]; }else{ echo $redirect->redirect_from;}?></td>
                    <td>
                    <?php if($redirect_manager_tab == 'redirects_rules'){echo $redirect->redirect_from_type;}else{echo $redirect->redirect_to; ?>
                    <span class="<?php echo $redirect->redirect_to_type ?>_<?php echo $redirect->enabled ?>"></span><?php if($redirect->redirect_to_type == 'Page' || $redirect->redirect_to_type == 'Folder' ){ ?><a href="<?php echo $request->make_absolute_url($redirect->redirect_to);?>" target="_blank"><span class="fa fa-external-link-square"></span></a> <?php }}?></td>
                    <td style="width: 85px; text-align: center"><?php echo $redirect->redirect_type; ?></td>
                </tr>
                <?php } if($i==0){ ?>
                <tr><td colspan="5" style="text-align: center"> No data available!</td></tr>
                <?php } ?>

                </tbody>
            </table>
            </div>
            <?php $pagination->run();?>
        </div>
    <div style="text-align: right">* Need Help? <a target="_blank" href="http://www.clogica.com/kb/adding-redirects.htm">click here to see how to add redirects</a></div>

</form>

<?php
}

$SR_jforms->set_small_select_pickers();
$SR_jforms->hide_alerts();
$SR_jforms->run();
