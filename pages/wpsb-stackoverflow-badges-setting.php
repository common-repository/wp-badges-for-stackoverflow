<?php
global $wpdb;
//sanitize all post values
$stack_setting_submit= sanitize_text_field( $_POST['stack_setting_submit'] );
$user_id= sanitize_text_field( $_POST['user_id'] );
$stack_color_pic= sanitize_text_field( $_POST['stack_color_pic'] );
$saved= sanitize_text_field( $_POST['saved'] );

if($stack_setting_submit!='') { 
    if(isset($user_id) ) {
		update_option('user_id', $user_id);
    }
    if(isset($stack_color_pic) ) {
		update_option('stack_color_pic', $stack_color_pic);
    }
	if($saved==true) {
		$message='saved';
	} 
}
?>
  <?php
        if ( $message == 'saved' ) {
		echo ' <div class="added-success"><p><strong>Settings Saved.</strong></p></div>';
		}
   ?>
   
<div class="wrap netgo-stack-post-setting">
    <form method="post" id="stackSettingForm" action="">
	<h2><?php _e('Stackoverflow Badges Setting','');?></h2>
		<table class="form-table">
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="user_id"><?php _e('User ID','');?></label>
				</th>
				<td>
				<input name="user_id" type="text" value="<?php echo get_option('user_id'); ?>" class="user_id" id="user_id" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="stack_color_pic"><?php _e('Background Color','');?></label>
				</th>
				<td>
				<input name="stack_color_pic" type="text" value="<?php echo get_option('stack_color_pic'); ?>" class="wp-color-picker-field" data-default-color="#ffffff" />
				</td>
			</tr>
		</table>
		
        <p class="submit">
		<input type="hidden" name="saved" value="saved"/>
        <input type="submit" name="stack_setting_submit" class="button-primary" value="Save Changes" />
		  <?php if(function_exists('wp_nonce_field')) wp_nonce_field('stack_setting_submit', 'stack_setting_submit'); ?>
        </p>
    </form>
</div>

