<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;   
}
$plugin_dir_url =  plugin_dir_url( __FILE__ );
?>
<style>

	 /*upgrade css*/



	.upgrade{background:#f4f4f9;padding: 50px 0; width:100%; clear: both;}

	.upgrade .upgrade-box{ background-color: #808a97;

		color: #fff;

		margin: 0 auto;

	   min-height: 110px;

		position: relative;

		width: 60%;}



	.upgrade .upgrade-box p{ font-size: 15px;

		 padding: 19px 20px;

		text-align: center;}



	.upgrade .upgrade-box a{background: none repeat scroll 0 0 #6cab3d;

		border-color: #ff643f;

		color: #fff;

		display: inline-block;

		font-size: 17px;

		left: 50%;

		margin-left: -150px;

		outline: medium none;

		padding: 11px 6px;

		position: absolute;

		text-align: center;

		text-decoration: none;

		top: 36%;

		width: 277px;}



	.upgrade .upgrade-box a:hover{background: none repeat scroll 0 0 #72b93c;}

   

   /**premium box**/    

	.premium-box{ width:100%; height:auto; background:#fff; float:left; }

	.premium-features{}

	.premium-heading{color:#484747;font-size: 40px; padding-top:35px;text-align:center;text-transform:uppercase;}

	.premium-features li{ width:100%; float:left;  padding: 80px 0; margin: 0; }

	.premium-features li .detail{ width:50%; }

	.premium-features li .img-box{ width:50%;box-sizing:border-box; }

	



	.premium-features li:nth-child(odd) { background:#f4f4f9; }

	.premium-features li:nth-child(odd) .detail{float:right; text-align:left; }

	.premium-features li:nth-child(odd) .detail .inner-detail{}

	.premium-features li:nth-child(odd) .detail p{ }

	.premium-features li:nth-child(odd) .img-box{ float:left; text-align:right; padding-right:30px;}



	.premium-features li:nth-child(even){  }

	.premium-features li:nth-child(even) .detail{ float:left; text-align:right;}

	.premium-features li:nth-child(even) .detail .inner-detail{ margin-right: 46px;}

	.premium-features li:nth-child(even) .detail p{ float:right;} 

	.premium-features li:nth-child(even) .img-box{ float:right;}



	.premium-features .detail{}

	.premium-features .detail h2{ color: #484747;  font-size: 24px; font-weight: 700; padding: 0; line-height:1.1;}

	.premium-features .detail p{  color: #484747;  font-size: 13px;  max-width: 327px;}

 

 /**images**/

 

 .pd_prm_option1 { background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option1.png") no-repeat; width:100%; max-width:527px; height:201px; display:inline-block; background-size:100% auto;}

 .prm_option2{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option2.png") no-repeat; width:100%;max-width:614px; height:191px; display:inline-block;  background-size:100% auto; }

 .prm_option3{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option3.png") no-repeat; width:100%;max-width:668px;   height:296px; display:inline-block;background-size:100% auto;}

 .prm_option4{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option4.png") no-repeat; width:100%;max-width:539px;  height:126px; display:inline-block;  background-size:100% auto;}					

 .prm_option5{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option5.png") no-repeat; width:100%;max-width:368px; height:59px; display:inline-block; background-size:100% auto;}	

 .prm_option6{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option6.png") no-repeat; width:100%; max-width:454px; height: 93px; display:inline-block; background-size:100% auto;}  					

 .prm_option7{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option7.png") no-repeat; width:100%; max-width:450px; height: 177px; display:inline-block; background-size:100% auto;}  					

 .prm_option8{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option8.png") no-repeat; width:100%; max-width:495px; height: 397px; display:inline-block; background-size:100% auto;}  					

 /*.prm_option9{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option9.png") no-repeat; width:100%; max-width:689px; height: 346px; display:inline-block; background-size:100% auto;}  					

 .prm_option10{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option10.png") no-repeat; width:100%; max-width:600px; height: 279px; display:inline-block; background-size:100% auto;}  					

 .prm_option11{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option11.png") no-repeat; width:100%; max-width:395px; height: 462px; display:inline-block; background-size:100% auto;}

 .prm_option12{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option12.png") no-repeat; width:100%; max-width:405px; height: 450px; display:inline-block; background-size:100% auto;}

 .prm_option13{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>images/prm_option13.png") no-repeat; width:100%; max-width:323px; height: 70px; display:inline-block; background-size:100% auto;}*/					 
					
.premium-box-head {
    background: #eae8e7 none repeat scroll 0 0;
    height: 500px;
    text-align: center;
    width: 100%;
}

.premium-box .pho-upgrade-btn {
    text-align: center;
}
.pho-upgrade-btn a {
    display: inline-block;
    margin-top: 75px;
}
.main-heading {
    background: #fff none repeat scroll 0 0;
    margin-bottom: -70px;
    text-align: center;
}
.main-heading h1 {
    margin: 0;
}
.main-heading img {
    margin-top: -200px;
}
.premium-box-container {
    margin: 0 auto;
}
.premium-box-container .description:nth-child(2n+1) {
    background: #fff none repeat scroll 0 0;
}
.premium-box-container .description {
    display: block;
    padding: 35px 0;
    text-align: center;
}
.premium-box-container .pho-desc-head::after {
    background: rgba(0, 0, 0, 0) url(<?php echo $plugin_dir_url; ?>images/head-arrow.png) no-repeat scroll 0 0;
    content: "";
    height: 98px;
    position: absolute;
    right: -40px;
    top: -6px;
    width: 69px;
}
.premium-box-container .pho-desc-head {
    margin: 0 auto;
    position: relative;
    width: 768px;
}
.premium-box-container .pho-desc-head h2 {
    color: #02c277;
    font-size: 28px;
    font-weight: bolder;
    margin: 0;
    text-transform: capitalize;
}
.pho-plugin-content {
    margin: 0 auto;
    overflow: hidden;
    width: 768px;
}
.pho-plugin-content p {
    color: #212121;
    font-size: 18px;
    line-height: 32px;
}
.premium-box-container .description:nth-child(2n+1) .pho-img-bg {
    background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>images/image-frame-odd.png) no-repeat scroll 100% top;
}
.description .pho-plugin-content .pho-img-bg {
    border-radius: 5px 5px 0 0;
    height: auto;
    margin: 0 auto;
    padding: 70px 0 40px;
    width: 750px;
}
.pho-plugin-content img {
    max-width: 100%;
    width: auto;
}
.premium-box-container .description:nth-child(2n) {
    background: #eae8e7 none repeat scroll 0 0;
}
.premium-box-container .description {
    display: block;
    padding: 35px 0;
    text-align: center;
}

.premium-box-container .description:nth-child(2n) .pho-img-bg {
    background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>images/image-frame-even.png) no-repeat scroll 100% top;
}
				</style>
				

		<div class="premium-box">
					
					<div class="premium-box-head">
					   <div class="pho-upgrade-btn">
					   <a href="http://www.phoeniixx.com/product/woocommerce-quick-view/" target="_blank"><img src="<?php echo $plugin_dir_url;?>images/premium-btn.png"></a>
					   </div>
					</div>
					<div class="main-heading">
						<h1> <img src="<?php echo $plugin_dir_url;?>images/premium-head.png">
           
						</h1>
					</div>
					<div class="premium-box-container">
						<div class="description">
							<div class="pho-desc-head">
							<h2>Custom Button Type Options</h2></div>
                
							<div class="pho-plugin-content">
								<p>This option allows you to choose whether you want a text or an icon based plugin, so that you get to design the button to fit with your site theme.</p>
								<div class="pho-img-bg">
								<img src="<?php echo $plugin_dir_url;?>images/prm_option1.png">
								</div>
							</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>General Options</h2></div>
							
								<div class="pho-plugin-content">
									<p><strong>Enable Quick View</strong> - Enable this checkbox to display Quick view on your site.</p>
									<p><strong>Quick View Product Navigation</strong> - This option gives you navigation arrows in a single popup so customers can browse freely.</p>
									<p><strong>Quick View Navigation Style</strong> - Two types of style are available i.e Slide(Thumbnail and product image) and Rotate (Thumbnail).</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option2.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>Product Options</h2></div>
							
								<div class="pho-plugin-content">
									<p>You can pick the content you want to appear on your popup box based on your database. Create custom popup content just for your site.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option3.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>Product “View Details”</h2></div>
							
								<div class="pho-plugin-content">
									<p>With this neat feature customers can jump right onto the product page through the popup box making browsing easier for your customers.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option4.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>Enable to AJAX Add to Cart</h2></div>
							
								<div class="pho-plugin-content">
									<p>This option enables you to add the product to cart using Ajax.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option5.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>Select Thumbnails Type</h2></div>
							
								<div class="pho-plugin-content">
									<p>This option allows you to choose whether you want to display Thumbnails type in <strong>“Slider mode”</strong> or <strong>“Classic mode”</strong>.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option6.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>Share Option</h2></div>
							
								<div class="pho-plugin-content">
									<p>This option enables you to share the product on social media. You can select the socials you want to share your product to.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option7.png">
									</div>
								</div>
						</div>
						<div class="description">
							<div class="pho-desc-head">
							<h2>General/Content Styling options</h2></div>
							
								<div class="pho-plugin-content">
									<p>These complete General and Content styling options give you the freedom to create a popup box that is in line with the theme of your website.</p>
									<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url;?>images/prm_option8.png">
									</div>
								</div>
						</div>
					</div>


											

						<!--<ul class="premium-features">


							

			

							
							

						  
							<li>

								 <div class="detail">

								  <div class="inner-detail">

								   <h2>More of Advanced Customization Options</h2>

									<p>

									You could set field placeholder for ‘Username’, ‘Email’ and ‘Password’, as per your choice. 

									The advanced settings also give you the option to set your own link label for ‘Forget Password’, ‘Login’ & ‘Register’. 



									</p>

								   </div> 

								 </div>

							    <div class="img-box"><span class="prm_option9"></span></div>

							</li>

							

						  	<li>

								<div class="img-box"><span class="prm_option10"></span></div>

								 <div class="detail">

								  <div class="inner-detail">

								   <h2>Description Box for Login & Register</h2>

									<p>

									 The plugin gives you the option to have a Login Popup Description Box and a Register Popup Description Box. You could add relevant descriptions to these boxes. You could also set Login Popup Title Text, Login Button Label, Register Popup Title text and Register Button Label.

									</p>

								   </div>

								  </div>							  

							</li>

							<li>

								 <div class="detail">

								  <div class="inner-detail">

								   <h2>Select Popup ‘Entrance’ Effects</h2>

									<p>

									 You could select a Popup Entrance Effect from the given list of Effects. The list includes bounceIn, fadIn, slideInUp, rotateIn and many more similar options.

									</p>

								   </div> 

								 </div>

							    <div class="img-box"><span class="prm_option11"></span></div>

							</li>

							

						  	<li>

								<div class="img-box"><span class="prm_option12"></span></div>

								 <div class="detail">

								  <div class="inner-detail">

								   <h2>Select Popup ‘Exit’ Effects</h2>

									<p>

									You could select Popup Exit Effect from the list of Effects provided to you. Some of the Effects that are available for you to choose include- bounceOut, fadeOut, rotateOut & slideOutUp.

									</p>

								   </div>

								  </div>							  

							</li>

							<li>

								 <div class="detail">

								  <div class="inner-detail">

								   <h2>Mobile Compatibility</h2>

									<p>

									 The Popup feature is mobile compatible as well.

									</p>

								   </div> 

								 </div>

							    <div class="img-box"><span class="prm_option13"></span></div>

							</li>

						 

						 

						</ul>-->

					<div class="pho-upgrade-btn">
        <a target="_blank" href="http://www.phoeniixx.com/product/woocommerce-quick-view/"><img src="<?php echo $plugin_dir_url;?>images/premium-btn.png"></a>
</div>	


						

				   </div>