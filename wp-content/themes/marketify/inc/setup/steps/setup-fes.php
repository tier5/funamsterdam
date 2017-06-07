<p><?php _e( 'When Easy Digital Downloads - Frontend Submissions is activated it will automatically create pages for your store. You need to do a few things to complete this setup:', 'marketify' ); ?></p>

<ol>
<li><a href="<?php echo admin_url( 'nav-menus.php' ); ?>"><?php _e( 'Add the Frontend Submissions pages to your Menu', 'marketify' ); ?></a></li>
<li><a href="<?php echo admin_url( 'admin.php?page=fes-settings' ); ?>"><?php _e( 'Turn off the Frontend Submission&#39;s CSS', 'marketify' ); ?></a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-submission-form', false ) ); ?>"><?php _e( 'Configure your Submission form', 'marketify' ); ?></a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-registration-form', false ) ); ?>"><?php _e( 'Configure your Registration form', 'marketify' ); ?></a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-profile-form', false ) ); ?>"><?php _e( 'Configure your Profile form', 'marketify' ); ?></a></li>
</ol>

<p></p>
