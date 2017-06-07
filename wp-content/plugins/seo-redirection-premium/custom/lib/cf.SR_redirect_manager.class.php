<?php

if(!class_exists('SR_redirect_manager')){
    class SR_redirect_manager {
        

        /* ----------------------------------------------- */
        public static function check_default_permalink()
        {            
            $htaccess = SRP_PLUGIN::get_htaccess();
            $htaccess->init();
            if(!$htaccess->is_saved_rule('SEO_REDIRECTION'))
            {
                $htaccess->add_rule('SEO_REDIRECTION', " ErrorDocument 404 /index.php?error=404 ");
                $htaccess->update_htaccess();

            }
        }


        /* ----------------------------------------------- */
        public static function hook_redirection()
        {
            add_action( 'wp', array( 'SR_redirect_manager', 'redirect' ),1);
            add_action( 'wp_login', array( 'SR_redirect_manager', 'clear_expired_history' ));
            add_action( 'post_updated', array( 'SR_redirect_manager', 'post_updated' ), 10, 3);
            add_action( 'auto-draft_to_publish', array( 'SR_redirect_manager', 'published' ));
            add_action( 'draft_to_publish', array( 'SR_redirect_manager', 'published' ));
            add_action( 'untrash_post', array( 'SR_redirect_manager', 'published' ));
            add_action( 'publish_to_trash', array( 'SR_redirect_manager', 'unpublished' ) );
            add_action( 'publish_to_draft', array( 'SR_redirect_manager', 'unpublished_draft' ));
            add_action( 'publish_to_pending', array( 'SR_redirect_manager', 'unpublished_pending' ));
            add_action( 'wp_login', array( 'SR_redirect_manager', 'check_default_permalink' ) );

        }

        /* ----------------------------------------------- */
        public static function published($post_id)
        {
            global $wpdb;
            $permalink="";
            list( $permalink, $postname ) = get_sample_permalink($post_id);
            $permalink = str_replace( '%postname%', $postname, $permalink );
            $permalink = str_replace( '%pagename%', $postname, $permalink );
            $permalink= SRP_PLUGIN::get_request()->make_relative_url($permalink);
            $wpdb->query(" delete from " . SR_database::WP_SEO_Redirection() . " where cat='link' and blog='" . get_current_blog_id() . "' and redirect_from='$permalink' ");
        }

        /* ----------------------------------------------- */
        public static function unpublished($post_id,$status='Trash' )
        {
            $permalink = urlencode(SRP_PLUGIN::get_request()->make_relative_url(get_permalink($post_id)));
            SRP_PLUGIN::get_app()->hook_message("<b>SEO Redirection Premium</b> has detected a changed post status from <b>Published</b> to <b>$status</b>, to redirect this link and prevent 404 error <a target=\"_blank\" href=\"options-general.php?page=". SRP_PLUGIN::get_app()->get_plugin_slug() ."&SR_tab=redirect_manager&add=1&link=$permalink\">Click here</a> ","warning" );
        }

        /* ----------------------------------------------- */
        public static function unpublished_draft($post_id)
        {
            self::unpublished($post_id,'Draft');
        }

        /* ----------------------------------------------- */
        public static function unpublished_pending($post_id)
        {
            self::unpublished($post_id,'Pending');
        }

        /* ----------------------------------------------- */
        public static function redirect()
        {
			
            global $wpdb, $post;
			
		
            if(SRP_PLUGIN::get_options()->read_option_value('plugin_status') !=0) // not disabled
            {
				if(current_user_can('manage_options') == 1 && SRP_PLUGIN::get_options()->read_option_value('plugin_status') == 2) // if disable for admin and the user is admin
				{
					// nothing
					
				}else{
					
                $table_name = SR_database::WP_SEO_Redirection();
                $SR_redirect_cache = new clogica_SR_redirect_cache();
                $permalink= self::get_permalink();
                $permalink_alternative=self::get_alternative_permalink();
                $post_cache_result="";
				
				
				// check general rule (replace)
				
				$theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and redirect_from_type = 'Replace' and blog='" . get_current_blog_id() . "' limit 1");
				
				if($wpdb->num_rows>0 && $theurl->redirect_from !='')
				{
					$redirect_from = $theurl->redirect_from;
					$newPath = $_SERVER['REQUEST_URI'];
					
					if(strpos($redirect_from,'|'))
						{
							$redirect_from_ = explode('|',$redirect_from);
						}
						//die($search_for);
						$search_for=$redirect_from_[0];
						$replace_with=$redirect_from_[1];
						
					//echo "search for". $search_for;

					if(strpos($newPath, $search_for) && $search_for !='')
					{
					$newPath = str_replace($search_for,$replace_with,$newPath);
					$new_url = site_url().$newPath;
					

					header("HTTP/1.1 301 Moved Permanently"); 
					header("Location: ".$new_url); 
					}
				}
				

                if(is_singular())
                {
                    $post_cache_result=$SR_redirect_cache->redirect_cached($post->ID);
                }
                if($post_cache_result == 'not_redirected')
                {
                return 0;
                }

                $permalink_options = "(redirect_from='$permalink' or redirect_from='$permalink_alternative' )";
                $permalink_regex_options = "('$permalink' regexp regex or '$permalink_alternative'  regexp regex )";

                // Check redirects cat='link' -------------------------------------
                $theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and cat='link' and blog='" . get_current_blog_id() . "' and regex='' and $permalink_options  ");
                if($wpdb->num_rows>0 && $theurl->redirect_to!=''){
                    self::make_redirect($permalink,$theurl->redirect_to,$theurl->redirect_type,'Redirect',$theurl->ID,$theurl);
                    return 0;
                }

                $theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and cat='link' and blog='" . get_current_blog_id() . "' and regex<>'' and $permalink_regex_options order by LENGTH(regex) desc ");
                if($wpdb->num_rows>0 && $theurl->redirect_to!=''){
                    self::make_redirect($permalink,$theurl->redirect_to,$theurl->redirect_type,'Redirect',$theurl->ID,$theurl);
                    return 0;
                }

                // Check if this page is 404 --------------------------------------
                if(is_404())
                {
                    if(SRP_PLUGIN::get_options()->read_option_value('p404_rules')=='1') {
                        // Check for regular 404 rules ---------------------------------
                        $SearchBot = 0;
                        $os = SRP_PLUGIN::get_browser()->get_visitor_OS();
                        $browser = SRP_PLUGIN::get_browser()->get_visitor_Browser();
                        $referrer = SRP_PLUGIN::get_request()->make_relative_url(SRP_PLUGIN::get_browser()->get_referrer());
                        if ($os == 'SearchBot' || $browser == 'SearchBot') $SearchBot = 1;

                        // SearchBot
                        if ($SearchBot == 1) {
                            $theurl = $wpdb->get_row(" select * from $table_name where enabled=1  and blog='" . get_current_blog_id() . "' and cat='404rule' and regex='' and redirect_from_type='CSE'");
                            if ($wpdb->num_rows > 0 && $theurl->redirect_to != '') {
                                self::make_redirect($permalink, $theurl->redirect_to, $theurl->redirect_type, '404Rule', $theurl->ID);
                                return 0;
                            }
                        }

                        // Check for referrer CSP
                        $theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and blog='" . get_current_blog_id() . "' and cat='404rule' and regex='' and redirect_from_type='CSP' and redirect_from='$referrer'   ");
                        if ($wpdb->num_rows > 0 && $theurl->redirect_to != '') {
                            self::make_redirect($permalink, $theurl->redirect_to, $theurl->redirect_type, '404Rule', $theurl->ID);
                            return 0;
                        }

                        // Check for  referrer regex
                        $theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and blog='" . get_current_blog_id() . "' and cat='404rule' and regex<>'' and (redirect_from_type='CSS' or redirect_from_type='CMS' or redirect_from_type='CSF') and '$referrer' regexp regex order by LENGTH(regex) desc   ");
                        if ($wpdb->num_rows > 0 && $theurl->redirect_to != '') {
                            self::make_redirect($permalink, $theurl->redirect_to, $theurl->redirect_type, '404Rule', $theurl->ID);
                            return 0;
                        }

                        // other regexs on the redirect_from
                        $theurl = $wpdb->get_row(" select * from $table_name where enabled=1 and blog='" . get_current_blog_id() . "' and cat='404rule' and regex<>'' and '$permalink' regexp regex order by LENGTH(regex) desc ");
                        if ($wpdb->num_rows > 0 && $theurl->redirect_to != '') {
                            self::make_redirect($permalink, $theurl->redirect_to, $theurl->redirect_type, '404Rule', $theurl->ID);
                            return 0;
                        }

                    }

                    if(SRP_PLUGIN::get_options()->read_option_value('p404_discovery_status')=='1')
                    {
                        self::log_404_redirection($permalink);
                    }

                    if(SRP_PLUGIN::get_options()->read_option_value('p404_rules')=='1') {
                    // Check for general 404 rules ---------------------------------
                        $type=self::get_link_type($permalink);

                        if($type==1 && SRP_PLUGIN::get_options()->read_option_value('pages_status')=='on')
                        {
                            self::make_redirect($permalink,SRP_PLUGIN::get_options()->read_option_value('redirect_pages_to'),'301','404',0);
                            return 0;
                        }
                        elseif($type==2 && SRP_PLUGIN::get_options()->read_option_value('images_status')=='on')
                        {
                            self::make_redirect($permalink,SRP_PLUGIN::get_options()->read_option_value('redirect_images_to'),'301','404',0);
                            return 0;
                        }
                        elseif($type==3 && SRP_PLUGIN::get_options()->read_option_value('scripts_status')=='on')
                        {
                            self::make_redirect($permalink,SRP_PLUGIN::get_options()->read_option_value('redirect_scripts_to'),'301','404',0);
                            return 0;
                        }
                        elseif($type==4 && SRP_PLUGIN::get_options()->read_option_value('otherfiles_status')=='on')
                        {
                            self::make_redirect($permalink,SRP_PLUGIN::get_options()->read_option_value('redirect_otherfiles_to'),'301','404',0);
                            return 0;
                        }
                    }
                }

                if(is_singular() && $post_cache_result == 'not_found')
                {
                    $SR_redirect_cache->add_redirect($post->ID,0,'','',0);
                }
            }
			}
        }


        /* ----------------------------------------------- */
        public static function make_redirect($redirect_from, $redirect_to, $redirect_type,$src, $src_id,$obj=null)
        {
			
            global $post;
            if($redirect_to == $redirect_from || !SRP_PLUGIN::get_request()->is_valid_url($redirect_to)) return 0;

            if(is_object($obj) && $obj->redirect_to_type=='Folder' && $obj->redirect_to_folder_settings=='2' ){
                if($obj->redirect_from_type=='Folder')
                {
                    if($obj->redirect_from_folder_settings=='2' || $obj->redirect_from_folder_settings=='3')
                    {
                        if(strlen($redirect_from) > strlen($obj->redirect_from))
                        {
                            $difference=substr($redirect_from,intval(strlen($obj->redirect_from)-strlen($redirect_from)));
                            $redirect_to = $redirect_to . $difference;
                        }
                    }
                }
                elseif ($obj->redirect_from_type!='Page')
                {
                    $page=substr(strrchr($redirect_from, "/"), 1);
                    $redirect_to = $redirect_to . '/' . $page;
                }
            }

            if(SRP_PLUGIN::get_options()->read_option_value('history_status')=='1'){
                self::log_redirection_history($redirect_from, $redirect_to, $redirect_type,$src, $src_id);
            }

            $redirect_to = SRP_PLUGIN::get_request()->make_absolute_url($redirect_to);
            if(is_singular())
            {
                $SR_redirect_cache = new clogica_SR_redirect_cache();
                $SR_redirect_cache->add_redirect($post->ID,1,$redirect_from,$redirect_to,$redirect_type);
            }

            if($redirect_type=='301')
            {
                header ('HTTP/1.1 301 Moved Permanently');
                header ("Location: " . $redirect_to);
                exit();
            }
            elseif($redirect_type=='307')
            {
                header ('HTTP/1.0 307 Temporary Redirect');
                header ("Location: " . $redirect_to);
                exit();
            }
            elseif($redirect_type=='302')
            {
                header ("Location: " . $redirect_to);
                exit();
            }

        }


        /* ----------------------------------------------- */
        public static function log_404_redirection($link)
        {
            global $wpdb ;
            $referrer=SRP_PLUGIN::get_browser()->get_referrer();
            $ip=SRP_PLUGIN::get_browser()->get_visitor_IP();
            $country=SRP_PLUGIN::get_browser()->get_visitor_country();
            $os=SRP_PLUGIN::get_browser()->get_visitor_OS();
            $browser=SRP_PLUGIN::get_browser()->get_visitor_Browser();
            $link_type=SR_redirect_manager::get_link_type($link);
            if($os!='Unknown' || $browser!='Unknown'){

                $id=$wpdb->get_var(" select ID from " . SR_database::WP_SEO_404_links() . " where blog='" . get_current_blog_id() . "' and link='$link' ");
                if(intval($id)>0)
                {
                    $wpdb->query(" update " . SR_database::WP_SEO_404_links() . " set counter=counter+1 where id='$id' ");
                }else
                {
                    $wpdb->query(" insert IGNORE  into " . SR_database::WP_SEO_404_links() . "(ctime,link,referrer,ip,country,os,browser,link_type,blog) values(NOW(),'$link','$referrer','$ip','$country','$os','$browser','$link_type','" . get_current_blog_id() . "') ");
                }
            }
        }


        /*--------------------------------------------------*/
        public static function post_updated($post_ID, $post_after, $post_before)
        {
            global $wpdb;
            // Autosave
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return;
            // AJAX
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
                return;
            // Post revision
            if ( false !== wp_is_post_revision( $post_ID ) )
                return;

            $post_before_permalink="";
            if(SRP_PLUGIN::get_request()->post('seo_premalink')!=''){
                $post_before_permalink=SRP_PLUGIN::get_request()->post('seo_premalink');
            } else{
                $post_before_permalink=SRP_PLUGIN::get_request()->make_relative_url(get_permalink($post_before));
            }
            $post_after_permalink= SRP_PLUGIN::get_request()->make_relative_url(get_permalink($post_after));


            if($post_before_permalink!= $post_after_permalink && $post_before->post_status == $post_after->post_status && $post_after->post_status=='publish' )
            {
                // do action
                $grpID= SR_option_manager::get_group_id('Modified Posts');
                if($grpID<1) {
                    $grpID=1;
                }

                $wpdb->query(" update " . SR_database::WP_SEO_Redirection() . " set redirect_from='$post_after_permalink'  where redirect_from='$post_before_permalink' and cat='link' and blog='" . get_current_blog_id() . "' ");
                $wpdb->query(" delete from " . SR_database::WP_SEO_Redirection() . " where redirect_from='$post_after_permalink' and cat='link' and blog='" . get_current_blog_id() . "' ");

                if(SRP_PLUGIN::get_options()->read_option_value('reflect_modifications')=='1'){
                    $wpdb->query(" update " . SR_database::WP_SEO_Redirection() . " set redirect_to='$post_after_permalink'  where redirect_to='$post_before_permalink' and cat='link' and blog='" . get_current_blog_id() . "'");
                }

                if(SRP_PLUGIN::get_options()->read_option_value('add_auto_redirect')=='1'){
                    $redirect_from_type='Page';
                    $redirect_to_type='Page';
                    $redirect_from_folder_settings = '1';
                    $redirect_from_subfolders='0';
                    $redirect_to_folder_settings='1';
                    $sql = "insert into " . SR_database::WP_SEO_Redirection() . "(redirect_from,redirect_to,redirect_type,redirect_from_type,redirect_to_type,redirect_from_folder_settings,redirect_from_subfolders,redirect_to_folder_settings,cat,blog,grpID) values ('$post_before_permalink','$post_after_permalink','301','$redirect_from_type','$redirect_to_type','$redirect_from_folder_settings','$redirect_from_subfolders','$redirect_to_folder_settings','link','" . get_current_blog_id() . "','$grpID') ";
                    $wpdb->query($sql);
                    SRP_PLUGIN::get_app()->hook_message("<b>SEO Redirection pro</b> has detected a change in permalink, a redirect from <b>" . $post_before_permalink . "</b> to <b>" . $post_after_permalink . "</b> has been added! " );
                }
            }

        }


        /* ----------------------------------------------- */
        public static function log_redirection_history($rfrom, $rto, $rtype,$rsrc, $rID)
        {
            global $wpdb ;
            $referrer=SRP_PLUGIN::get_browser()->get_referrer();
            $ip=SRP_PLUGIN::get_browser()->get_visitor_IP();
            $country=SRP_PLUGIN::get_browser()->get_visitor_country();
            $os=SRP_PLUGIN::get_browser()->get_visitor_OS();
            $browser=SRP_PLUGIN::get_browser()->get_visitor_Browser();
            $wpdb->query(" insert into " . SR_database::WP_SEO_Redirection_LOG() . "(rID,rfrom,rto,rtype,rsrc,ctime,referrer,ip,country,os,browser,blog) values('$rID','$rfrom','$rto','$rtype','$rsrc',NOW(),'$referrer','$ip','$country','$os','$browser','" . get_current_blog_id() . "') ");
        }


        /* ----------------------------------------------- */
        public static function get_link_type($permalink)
        {
            // 1= links, 2=images(png,jpg,gif,bmp), 3=css/js, 4=other files (if there is . before two or thee characters at the end)
            $permalink=strtolower($permalink);
            $type=1;
            $web_page_ext= array('.asp','.aspx','.cfm','.html','.yaws','.htm','.xhtml','.jhtml','.jsp','.jspx','.wss','.php','.php4','.php3','.cgi');
            if(substr($permalink,-4)=='.jpg' || substr($permalink,-4)=='.png' || substr($permalink,-4)=='.gif' || substr($permalink,-4)=='.bmp' )
            {
                $type=2;
            }
            elseif(substr($permalink,-4)=='.css' || substr($permalink,-3)=='.js')
            {
                $type=3;
            }
            elseif(substr($permalink,-3,1)=='.' || substr($permalink,-4,1)=='.')
            {
                if(!in_array(substr($permalink,-4),$web_page_ext) && !in_array(substr($permalink,-5),$web_page_ext))
                {
                    $type=4;
                }
            }
            return $type;
        }


        /* ----------------------------------------------- */
        public static function get_permalink()
        {
            $permalink=urldecode(SRP_PLUGIN::get_request()->get_current_relative_url());
            if(substr($permalink,0,1)==":")
            {
                $first_slash=stripos($permalink,"/");
                $permalink = substr($permalink,$first_slash,strlen($permalink)-$first_slash);
            }
            return SRP_PLUGIN::get_security()->sanitize($permalink);
        }


        /* ----------------------------------------------- */
        public static function get_alternative_permalink()
        {
            $permalink=self::get_permalink();
            $permalink_alternative="";
            if(substr($permalink,-1)=='/')
            {
                $permalink_alternative = substr($permalink,0,intval(strlen($permalink)-1));
            }else
            {
                $permalink_alternative = $permalink . '/';
            }
            return $permalink_alternative;
        }


        /* ----------------------------------------------- */
        public static function clear_expired_history()
        {
            global $wpdb;
            $limit= SRP_PLUGIN::get_options()->read_option_value('history_limit');
            $expdate = date('Y-n-j', time() - (intval($limit) * 24 * 60 * 60));
            $wpdb->query("delete FROM " . SR_database::WP_SEO_Redirection_LOG() . " WHERE blog='" . get_current_blog_id() . "' and date_format(date(ctime),'%Y-%m-%d') < date_format(date('$expdate'),'%Y-%m-%d')");
            SR_option_manager::optimize_tables();
        }


        /* ----------------------------------------------- */
        public static function clear_fixed_404($source, $regex='')
        {
            global $wpdb;
            $sql="delete FROM " . SR_database::WP_SEO_404_links() . " where link='$source'";
            if($regex!='')
            {
                $sql="delete FROM " . SR_database::WP_SEO_404_links() . " where link regexp '$regex' ";
            }
            $wpdb->query($sql);
        }
        
        


    }}