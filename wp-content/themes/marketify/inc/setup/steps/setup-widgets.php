<p><?php _e( 'Manage your widgets to control what displays on your homepage, listing pages, standard pages, and more.', 'marketify' ); ?></p>

<?php if ( marketify()->get( 'woothemes-testimonials' ) ) : ?>
<p><?php _e( '<strong>Note:</strong> Please make sure you have selected the relevant "Customer Testimonials" category for the <em>What People Are Saying</em> widget, and "Companies We&#39;ve Helped" category for the <em>Companies We&#39;ve Helped</em> widget. These settings can not be imported automatically.' ); ?>
<?php endif; ?>

<p><a href="<?php echo admin_url( 'widgets.php' ); ?>" class="button button-primary button-large"><?php _e( 'Manage Widgets', 'marketify' ); ?></a></p>
