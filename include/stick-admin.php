<?PHP
//stick-admin.php
//Plugin Name: Woocommerce Stickers for starblank
//Description of the file: ADMIN PAGE Options
//Author: Starblank
//version: 1.8.4
//Text Domain: custom-product-stickers-for-woocommerce


//number and order of tabs
$tabOptions=Array('new','sale','unavailable');


//Admin menu hooks
  add_action( 'admin_menu', 'cpsw_star_stick_woo_create_menu' );
  add_action( 'admin_init', 'cpsw_star_stick_woo_settings' );

//function for creating admin menu
function cpsw_star_stick_woo_create_menu() {
	add_menu_page('Product Stickers Options', 'Stickers', 'administrator', __FILE__, 'cpsw_star_stick_product_options' , plugins_url('/img/stick-logo.png', __FILE__) );
}


//Register settings that will be used 
function cpsw_star_stick_woo_settings() { 
 global $tabOptions;

  //register_setting( 'stick-woo-option-group-new', 'stick-woo-new-dias' );

 foreach($tabOptions as $option){ 
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-activo-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-width-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-height-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-transdiv-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-transimg-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'image_attachment_id-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-imagen-activo-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-text-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-color1-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-color2-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-align-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-css-ribbon-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-css-ribbon-span-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-css-ribbon-span-before-'.$option );
  register_setting( 'stick-woo-option-group'.$option, 'stick-woo-ribbon-css-ribbon-span-after-'.$option );
  if ($option=='new') register_setting( 'stick-woo-option-group'.$option, 'stick-woo-new-dias');
  
  //default values
  if (!get_option('stick-woo-width-'.$option)) update_option('stick-woo-width-'.$option,'100px');
  if (!get_option('stick-woo-height-'.$option)) update_option('stick-woo-height-'.$option,'100px');
  if (!get_option('stick-woo-transdiv-'.$option)) update_option('stick-woo-transdiv-'.$option,0);
  if (!get_option('stick-woo-transimg-'.$option)) update_option('stick-woo-transimg-'.$option,1);
  //if (!get_option('stick-woo-ribbon-'.$option)) update_option('stick-woo-ribbon-'.$option,true);
  if (!get_option('stick-woo-imagen-activo-'.$option)) update_option('stick-woo-imagen-activo-'.$option,false);
  if (!get_option('stick-woo-ribbon-color1-'.$option)) update_option('stick-woo-ribbon-color1-'.$option,'#000000');
  if (!get_option('stick-woo-ribbon-color2-'.$option)) update_option('stick-woo-ribbon-color2-'.$option,'#FF0000');
  if (!get_option('stick-woo-ribbon-align-'.$option)) update_option('stick-woo-ribbon-align-'.$option,'izquierda');
}
//Special default options
if (!get_option('stick-woo-ribbon-text-new')) update_option('stick-woo-ribbon-text-new','NEW');
if (!get_option('stick-woo-ribbon-text-sale')) update_option('stick-woo-ribbon-text-sale','SALE');
if (!get_option('stick-woo-ribbon-text-unavailable')) update_option('stick-woo-ribbon-text-unavailable','UNAVAILABLE');
//if (!get_option('stick-woo-new-dias')) update_option('stick-woo-new-dias',10);

}


//Select TAB
if (!isset($_GET['tab'])) $tab='new'; else $tab=$_GET['tab'];



//Main function - admin menu
function cpsw_star_stick_product_options() {
	global $tab,$tabOptions;
	
	//control access permissions
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	//main form
	echo '<div class="wrap"><form method="post" action="options.php">'; 
	settings_fields( 'stick-woo-option-group'.$tab );

	//get image?
	$img_new=wp_get_attachment_image_src(get_option('image_attachment_id-'.$tab));
	
	wp_enqueue_media();
?>
<h1><?php echo __( 'STICKERS FOR PRODUCT ','custom-product-stickers-for-woocommerce').strtoupper($tab);?></h1>
<h2 class="nav-tab-wrapper">
<?php foreach($tabOptions as $option){
	echo '<a href="?page=custom-product-stickers-for-woocommerce%2Finclude%2Fstick-admin.php&tab='.$option.'" class="nav-tab'.($tab==$option ? " nav-tab-active" : "").'">'.esc_html__( 'Sticker for product ','custom-product-stickers-for-woocommerce').get_option('stick-woo-ribbon-text-'.$option).'</a>';
}?>
</h2>
<br><input type='checkbox' name='stick-woo-activo-<?php echo $tab;?>' value='true' <?php if (get_option('stick-woo-activo-'.$tab)) echo 'checked';?> /><?php echo esc_html__( 'Activate for product  ','custom-product-stickers-for-woocommerce').$tab;?>
<?php 

//Theres  special options for NEW, CUSTOM
if ($tab=='new') {
	if (!get_option('stick-woo-new-dias')) update_option('stick-woo-new-dias',10);
	$es_nuevo=get_option('stick-woo-new-dias');
	echo "<br><br>".esc_html__( 'Days for considering product as new:','custom-product-stickers-for-woocommerce')."<input style='width:40px;' type='text' name='stick-woo-new-dias' value='".($es_nuevo=='' ? '10' : $es_nuevo)."'/>";
}
if ($tab=='custom') {
        echo "<br><br>".esc_html__( 'Product type:','custom-product-stickers-for-woocommerce')."<select name='stick-woo-custom-tipo'>
		<option value='tipo1' ".(get_option('stick-woo-custom-tipo')=='tipo1' ? 'selected="selected"' : '').">tipo1</option>
                <option value='tipo2' ".(get_option('stick-woo-custom-tipo')=='tipo2' ? 'selected="selected"' : '').">tipo2</option></select>";
}

?>
<br><br><h2><?php echo esc_html__( 'RIBBON','custom-product-stickers-for-woocommerce');?></h2>
<hr>
<table class="form-table">
<tr valign="top">
<th scope="row">
<input type='checkbox' name='stick-woo-ribbon-<?php echo $tab;?>' value='true' <?php if (get_option('stick-woo-ribbon-'.$tab)) echo 'checked';?> /><?php echo esc_html__( 'Use ribbon','custom-product-stickers-for-woocommerce');?></th>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Ribbon text','custom-product-stickers-for-woocommerce');?></th>
<td><input style="width:100px;" type="text" name="stick-woo-ribbon-text-<?php echo $tab;?>" value="<?php echo esc_attr( get_option('stick-woo-ribbon-text-'.$tab) ); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Text color','custom-product-stickers-for-woocommerce');?></th>
<td><input class="colorpicker" type="text" name="stick-woo-ribbon-color1-<?php echo $tab;?>" value="<?php echo esc_attr( get_option('stick-woo-ribbon-color1-'.$tab) ); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Background color','custom-product-stickers-for-woocommerce');?></th>
<td><input class="colorpicker" type="text" name="stick-woo-ribbon-color2-<?php echo $tab;?>" value="<?php echo esc_attr( get_option('stick-woo-ribbon-color2-'.$tab) ); ?>" /></td>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Align','custom-product-stickers-for-woocommerce');?></th>
<td><select name="stick-woo-ribbon-align-<?php echo $tab;?>" selected="<?php echo esc_attr( get_option('stick-woo-ribbon-align-'.$tab) ); ?>">
	<option value="izquierda" <?php if (get_option('stick-woo-ribbon-align-'.$tab)=='izquierda') echo 'selected="selected"'; ?>><?php echo esc_html__( 'Left','custom-product-stickers-for-woocommerce');?></option>
        <option value="derecha" <?php if (get_option('stick-woo-ribbon-align-'.$tab)=='derecha') echo 'selected="selected"'; ?>><?php echo esc_html__( 'Right','custom-product-stickers-for-woocommerce');?></option>
</select></td>
</tr>	
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Custom CSS - Class: ','custom-product-stickers-for-woocommerce')."ribbon-".$tab;?></th>
<td><textarea style="width:50%; height:100px;" name="stick-woo-ribbon-css-ribbon-<?php echo $tab;?>"><?php echo esc_attr( get_option('stick-woo-ribbon-css-ribbon-'.$tab) ); ?></textarea></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Custom CSS - Class: ','custom-product-stickers-for-woocommerce')."ribbon-".$tab;?> span</th>
<td><textarea style="width:50%; height:100px;" name="stick-woo-ribbon-css-ribbon-span-<?php echo $tab;?>"><?php echo esc_attr( get_option('stick-woo-ribbon-css-ribbon-span-'.$tab) ); ?></textarea></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Custom CSS - Class: ','custom-product-stickers-for-woocommerce')."ribbon-".$tab;?> span::before</th>
<td><textarea style="width:50%; height:100px;" name="stick-woo-ribbon-css-ribbon-span-before-<?php echo $tab;?>"><?php echo esc_attr( get_option('stick-woo-ribbon-css-ribbon-span-before-'.$tab) ); ?></textarea></td>
</tr>
<tr valign="top">
<th scope="row"><?php echo esc_html__( 'Custom CSS - Class: ','custom-product-stickers-for-woocommerce')."ribbon-".$tab;?> span::after</th>
<td><textarea style="width:50%; height:100px;" name="stick-woo-ribbon-css-ribbon-span-after-<?php echo $tab;?>"><?php echo esc_attr( get_option('stick-woo-ribbon-css-ribbon-span-after-'.$tab) ); ?></textarea></td>
</tr>

</table>
<h2><?php echo esc_html__( 'IMAGE','custom-product-stickers-for-woocommerce');?></h2>
<hr>
<table class="form-table">
	<tr valign="top">
	<th scope=row"><input type='checkbox' name='stick-woo-imagen-activo-<?php echo $tab;?>' value='true' <?php if (get_option('stick-woo-imagen-activo-'.$tab)) echo 'checked';?> /><?php echo  esc_html__( 'Use image','custom-product-stickers-for-woocommerce');?></th>
	</tr>
        <tr valign="top">
        <th scope="row"><?php echo  esc_html__( 'Width (px o %)','custom-product-stickers-for-woocommerce');?></th>
        <td><input style="width:60px;" type="text" name="stick-woo-width-<?php echo $tab;?>" value="<?php echo esc_attr( get_option('stick-woo-width-'.$tab) ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php echo  esc_html__( 'Height (px o %)','custom-product-stickers-for-woocommerce');?></th>
        <td><input style="width:60px;" type="text" name="stick-woo-height-<?php echo $tab;?>" value="<?php echo esc_attr( get_option('stick-woo-height-'.$tab) ); ?>" /></td>
        </tr>
        <tr valign="top">
   </table>

   <div class='image-preview-wrapper'><strong><?php echo  esc_html__( 'Sticker image','custom-product-stickers-for-woocommerce');?></strong><br>
                <img id='image-preview' src='<?php echo $img_new[0];?>' width='100' height='100' style='max-height: 100px; width: 100px;'>
        </div>
        <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
        <input type='hidden' name='image_attachment_id-<?php echo $tab;?>' id='image_attachment_id' value='<?php echo get_option('image_attachment_id-'.$tab)?>'>

<br><br><strong><?php echo  esc_html__( 'Layer transparency','custom-product-stickers-for-woocommerce');?><br><input style="width:40px;" type="text"  name="outrange1" id="outrange1" value="<?php if (get_option('stick-woo-transdiv-'.$tab)) echo get_option('stick-woo-transdiv-'.$tab); else echo "0";?>"/></strong><?php echo  esc_html__( 'More transparent','custom-product-stickers-for-woocommerce');?>
<input name="stick-woo-transdiv-<?php echo $tab;?>" id="inrange1" type="range" oninput="outrange1.value = inrange1.value" name="points" step="0.1" id="points" value="<?php echo get_option('stick-woo-transdiv-'.$tab)?>" min="0" max="1"><?php echo  esc_html__( 'Less transparent','custom-product-stickers-for-woocommerce');?>

<br><br><strong><?php echo  esc_html__( 'Image transparency','custom-product-stickers-for-woocommerce');?><br><input style="width:40px;" type="text"  name="outrange2" id="outrange2" value="<?php if (get_option('stick-woo-transimg-'.$tab)) echo get_option('stick-woo-transimg-'.$tab); else echo "0";?>"/></strong><?php echo  esc_html__( 'More transparent','custom-product-stickers-for-woocommerce');?>
<input name="stick-woo-transimg-<?php echo $tab;?>" id="inrange2" type="range" oninput="outrange2.value = inrange2.value" name="points" step="0.1" id="points" value="<?php echo get_option('stick-woo-transimg-'.$tab)?>" min="0" max="1"><?php echo  esc_html__( 'Less transparent','custom-product-stickers-for-woocommerce');?>


<?php
	
	do_settings_sections( 'stick-woo-option-group'.$tab );
	submit_button();
	echo '</form>';
	echo '</div>';
	//help us
	echo '<style>
	      	.donate-us a{
   		 color: #f11111;
    		 text-decoration: initial;
    		 font-weight: 600;
    		 font-size: 15px;
    		 padding: 10px;
		}
		.donate-us{
    		 display: table;
    		 float: right;
		}
		.donate-us:hover {
    		 text-decoration: underline;
		}
		.rate-plugin{   
		 margin-top: 10px;
		 background-color: #f5deb3;
    		 padding: 7px;
    		 font-weight: 600;
    		 display: table;
		}
	      </style>
<div class="rate-plugin">If you like the plugin , please show your support by rating <a href="https://wordpress.org/support/plugin/custom-product-stickers-for-woocommerce/reviews/#new-post" target="_blank">here.</a>
		<div class="donate-us"><a href="https://goo.gl/7Q3g1E"><span class="dashicons dashicons-heart" style="margin-right: 2px;"></span> Donate</a>
		</div>
	</div>';
}



//Color picker control function
function cpsw_star_color_picker_assets($hook_suffix) {
    // $hook_suffix to apply a check for admin page.
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('functions.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'cpsw_star_color_picker_assets' );


//Image load control function
add_action( 'admin_footer', 'cpsw_star_media_selector_print_scripts' );
function cpsw_star_media_selector_print_scripts() {
	global $tab;
	$my_saved_attachment_post_id = get_option( 'image_attachment_id-'.$tab, 0 );
	if (!$my_saved_attachment_post_id) $my_saved_attachment_post_id=0;
	?><script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			//var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var wp_media_post_id = (wp.media == undefined) ? null : wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#image_attachment_id' ).val( attachment.id );
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script><?php
}



