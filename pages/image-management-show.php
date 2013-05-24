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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
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
			$ImgSlider_success = __('Selected record was successfully deleted.', LTW_TES_UNIQUE_NAME);
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
    <h2><?php echo WP_ImgSlider_TITLE ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ImgSlider_image_management&amp;sp=add">Add New</a></h2>
    <div class="tool-box">
	<?php
	$sSql = "SELECT * FROM `".WP_ImgSlider_TABLE."` order by ImgSlider_type, ImgSlider_order";
	$myData = array();
	$myData = $wpdb->get_results($sSql, ARRAY_A);
	?>
	<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/image-slider-with-description/pages/setting.js"></script>
      <form name="frm_ImgSlider_display" method="post">
        <table width="100%" class="widefat" id="straymanage">
          <thead>
            <tr>
			  <th scope="col" class="check-column" scope="row"><input type="checkbox" /></th>
			  <th scope="col">Title</td>
			  <th scope="col">Image path</td>
              <th scope="col">Type</td>
              <th scope="col">Order</td>
              <th scope="col">Display</td>
            </tr>
          </thead>
		  <tfoot>
            <tr>
			  <th scope="col" class="check-column" scope="row"><input type="checkbox" /></th>
              <th scope="col">Title</td>
			  <th scope="col">Image path</td>
              <th scope="col">Type</td>
              <th scope="col">Order</td>
              <th scope="col">Display</td>
            </tr>
          </tfoot>
		<?php 
		$i = 0;
		$displayisthere = FALSE;
		foreach ($myData as $data)
		{
			if($data['ImgSlider_status'] == 'YES') 
			{
				$displayisthere = TRUE; 
			}
			?>
			<tbody>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $data['ImgSlider_id']; ?>" name="ImgSlider_group_item[]"></th>
					<td align="left" valign="middle">
					<strong><a href="#"><?php echo esc_html(stripslashes($data['ImgSlider_title'])); ?></a></strong>
					<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ImgSlider_image_management&amp;sp=edit&amp;did=<?php echo $data['ImgSlider_id']; ?>">Edit</a> | </span>
						<span class="trash"><a onClick="javascript:ImgSlider_delete('<?php echo $data['ImgSlider_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
					</div>
					</td>
					<td align="left" valign="middle"><a href="<?php echo esc_html(stripslashes($data['ImgSlider_path'])); ?>" target="_blank"><?php echo esc_html(stripslashes($data['ImgSlider_path'])); ?></a></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_type'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_order'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_status'])); ?></td>
				</tr>
			</tbody>
			<?php 
			$i = $i+1; 
		} 
		?>
		<?php 
		if ($displayisthere == FALSE) 
		{ 
			?><tr><td colspan="5" align="center" style="color:#FF0000" valign="middle">No records available.</td></tr><?php 
		} 
		?>
        </table>
		<?php wp_nonce_field('ImgSlider_form_show'); ?>
		<input type="hidden" name="frm_ImgSlider_display_submit" value="yes"/>
      </form>
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ImgSlider_image_management&amp;sp=add">Add New</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>">Help</a>
	  </h2>
	  </div>
    </div>
	<br />
	<p class="description">Note: Use the short code to add the gallery in to the posts and pages. <?php echo WP_ImgSlider_LINK; ?></p>
</div>