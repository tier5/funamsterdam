<?php if ( EDD_FES()->vendors->user_is_status( 'pending' ) ) { 
	$redirect_to = get_permalink( EDD_FES()->helper->get_option( 'fes-vendor-dashboard-page', get_permalink() ) );
	$redirect_to = add_query_arg( array('task' => 'logout'), $redirect_to );
	?>
	<p><?php echo sprintf(__( 'Your application has been submitted and will be reviewed. Click %shere%s to logout.', 'edd_fes' ), '<a href="' . $redirect_to . '">', '</a>' ); ?></p>
<?php } else { 
	$base_url = get_permalink( EDD_FES()->helper->get_option( 'fes-vendor-dashboard-page', get_permalink() ) );
	wp_redirect( $base_url ); exit;		
}