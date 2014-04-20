<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_ImgSlider_display_submit']) && $_POST['frm_ImgSlider_display_submit'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$ImgSlider_success = '';
	$ImgSlider_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_ImgSlider_TABLE."
		WHERE `ImgSlider_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'imgslider'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('ImgSlider_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_ImgSlider_TABLE."`
					WHERE `ImgSlider_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$ImgSlider_success_msg = TRUE;
			$ImgSlider_success = __('Selected record was successfully deleted.', 'imgslider');
		}
	}
	
	if ($ImgSlider_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $ImgSlider_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Image slider with description', 'imgslider'); ?>
	<a class="add-new-h2" href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=add"><?php _e('Add New', 'imgslider'); ?></a></h2>
    <div class="tool-box">
	<?php
	$sSql = "SELECT * FROM `".WP_ImgSlider_TABLE."` order by ImgSlider_type, ImgSlider_order";
	$myData = array();
	$myData = $wpdb->get_results($sSql, ARRAY_A);
	?>
	<script language="JavaScript" src="<?php echo WP_ImgSlider_PLUGIN_URL; ?>/pages/setting.js"></script>
      <form name="frm_ImgSlider_display" method="post">
        <table width="100%" class="widefat" id="straymanage">
          <thead>
            <tr>
			  <th scope="col" class="check-column"><input type="checkbox" /></th>
			  <th scope="col"><?php _e('Title', 'imgslider'); ?></th>
			  <th scope="col"><?php _e('Image path', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Type', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Order', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Display', 'imgslider'); ?></th>
            </tr>
          </thead>
		  <tfoot>
            <tr>
			  <th scope="col" class="check-column"><input type="checkbox" /></th>
              <th scope="col"><?php _e('Title', 'imgslider'); ?></th>
			  <th scope="col"><?php _e('Image path', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Type', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Order', 'imgslider'); ?></th>
              <th scope="col"><?php _e('Display', 'imgslider'); ?></th>
            </tr>
          </tfoot>
		<?php 
		$i = 0;
		$displayisthere = FALSE;
		if(count($myData) > 0 )
		{
			foreach ($myData as $data)
			{
				?>
				<tbody>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td><input type="checkbox" value="<?php echo $data['ImgSlider_id']; ?>" name="ImgSlider_group_item[]"></td>
					<td align="left" valign="middle">
					<strong><a href="#"><?php echo esc_html(stripslashes($data['ImgSlider_title'])); ?></a></strong>
					<div class="row-actions">
					<span class="edit">
						<a title="Edit" href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=edit&amp;did=<?php echo $data['ImgSlider_id']; ?>"><?php _e('Edit', 'imgslider'); ?></a> | 
					</span>
					<span class="trash">
						<a onClick="javascript:ImgSlider_delete('<?php echo $data['ImgSlider_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'imgslider'); ?></a>
					</span> 
					</div>
					</td>
					<td align="left" valign="middle">
					<a href="<?php echo esc_html(stripslashes($data['ImgSlider_path'])); ?>" target="_blank"><?php echo esc_html(stripslashes($data['ImgSlider_path'])); ?></a>
					</td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_type'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_order'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_status'])); ?></td>
				</tr>
				</tbody>
				<?php 
				$i = $i+1; 
			}
		}
		else
		{
			?><tr><td colspan="6" align="center"><?php _e('No records available', 'imgslider'); ?></td></tr><?php 
		}
		?>
        </table>
		<?php wp_nonce_field('ImgSlider_form_show'); ?>
		<input type="hidden" name="frm_ImgSlider_display_submit" value="yes"/>
      </form>
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=add"><?php _e('Add New', 'imgslider'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><?php _e('Help', 'imgslider'); ?></a>
	  </h2>
	  </div>
    </div>
	<br />
	<p class="description"><?php _e('Note: Use the short code to add the gallery in to the posts and pages.', 'imgslider'); ?></p>
	<p class="description">
		<?php _e('Check official website for more information', 'imgslider'); ?>
		<a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><?php _e('click here', 'imgslider'); ?></a>
	</p>
</div>