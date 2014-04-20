<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$ImgSlider_errors = array();
$ImgSlider_success = '';
$ImgSlider_error_found = FALSE;

// Preset the form fields
$form = array(
	'ImgSlider_path' => '',
	'ImgSlider_link' => '',
	'ImgSlider_target' => '',
	'ImgSlider_title' => '',
	'ImgSlider_desc' => '',
	'ImgSlider_order' => '',
	'ImgSlider_status' => '',
	'ImgSlider_type' => ''
);

// Form submitted, check the data
if (isset($_POST['ImgSlider_form_submit']) && $_POST['ImgSlider_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ImgSlider_form_add');
	
	$form['ImgSlider_path'] = isset($_POST['ImgSlider_path']) ? $_POST['ImgSlider_path'] : '';
	if ($form['ImgSlider_path'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image path.', 'imgslider');
		$ImgSlider_error_found = TRUE;
	}

	$form['ImgSlider_link'] = isset($_POST['ImgSlider_link']) ? $_POST['ImgSlider_link'] : '';
	if ($form['ImgSlider_link'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the target link.', 'imgslider');
		$ImgSlider_error_found = TRUE;
	}
	
	$form['ImgSlider_target'] = isset($_POST['ImgSlider_target']) ? $_POST['ImgSlider_target'] : '';
	
	$form['ImgSlider_title'] = isset($_POST['ImgSlider_title']) ? $_POST['ImgSlider_title'] : '';
	if ($form['ImgSlider_title'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image title.', 'imgslider');
		$ImgSlider_error_found = TRUE;
	}
	
	$form['ImgSlider_desc'] = isset($_POST['ImgSlider_desc']) ? $_POST['ImgSlider_desc'] : '';
	$form['ImgSlider_order'] = isset($_POST['ImgSlider_order']) ? $_POST['ImgSlider_order'] : '';
	$form['ImgSlider_status'] = isset($_POST['ImgSlider_status']) ? $_POST['ImgSlider_status'] : '';
	$form['ImgSlider_type'] = isset($_POST['ImgSlider_type']) ? $_POST['ImgSlider_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($ImgSlider_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_ImgSlider_TABLE."`
			(`ImgSlider_path`, `ImgSlider_link`, `ImgSlider_target`, `ImgSlider_title`, `ImgSlider_desc`, `ImgSlider_order`, `ImgSlider_status`, `ImgSlider_type`)
			VALUES(%s, %s, %s, %s, %s, %d, %s, %s)",
			array($form['ImgSlider_path'], $form['ImgSlider_link'], $form['ImgSlider_target'], $form['ImgSlider_title'], $form['ImgSlider_desc'], $form['ImgSlider_order'], $form['ImgSlider_status'], $form['ImgSlider_type'])
		);
		$wpdb->query($sql);
		
		$ImgSlider_success = __('New image details was successfully added.', 'imgslider');
		
		// Reset the form fields
		$form = array(
			'ImgSlider_path' => '',
			'ImgSlider_link' => '',
			'ImgSlider_target' => '',
			'ImgSlider_title' => '',
			'ImgSlider_desc' => '',
			'ImgSlider_order' => '',
			'ImgSlider_status' => '',
			'ImgSlider_type' => ''
		);
	}
}

if ($ImgSlider_error_found == TRUE && isset($ImgSlider_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $ImgSlider_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($ImgSlider_error_found == FALSE && strlen($ImgSlider_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $ImgSlider_success; ?></strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo WP_ImgSlider_PLUGIN_URL; ?>/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Image slider with description', 'imgslider'); ?></h2>
	<form name="ImgSlider_form" method="post" action="#" onsubmit="return ImgSlider_submit()"  >
      <h3><?php _e('Add New image', 'imgslider'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'imgslider'); ?></label>
      <input name="ImgSlider_path" type="text" id="ImgSlider_path" value="" size="100" />
      <p><?php _e('Where is the picture located on the internet', 'imgslider'); ?> (Example: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/600x400/600x400_1.jpg)</p>
      <label for="tag-link"><?php _e('Enter target link', 'imgslider'); ?></label>
      <input name="ImgSlider_link" type="text" id="ImgSlider_link" value="#" size="100" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'imgslider'); ?></p>
      <label for="tag-target"><?php _e('Enter target option', 'imgslider'); ?></label>
      <select name="ImgSlider_target" id="ImgSlider_target">
        <option value='_blank' selected="selected">_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'imgslider'); ?></p>
      <label for="tag-title"><?php _e('Enter image title', 'imgslider'); ?></label>
      <input name="ImgSlider_title" type="text" id="ImgSlider_title" value="" size="100" />
      <p><?php _e('Enter the image title. The title will display on the image within the slideshow.', 'imgslider'); ?></p>
      <label for="tag-desc"><?php _e('Enter image description', 'imgslider'); ?></label>
      <input name="ImgSlider_desc" type="text" id="ImgSlider_desc" value="" size="100" />
      <p><?php _e('Enter image description', 'imgslider'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery group', 'imgslider'); ?></label>
      <select name="ImgSlider_type" id="ImgSlider_type">
        <option value='GROUP1'>Group1</option>
        <option value='GROUP2'>Group2</option>
        <option value='GROUP3'>Group3</option>
        <option value='GROUP4'>Group4</option>
        <option value='GROUP5'>Group5</option>
        <option value='GROUP6'>Group6</option>
        <option value='GROUP7'>Group7</option>
        <option value='GROUP8'>Group8</option>
        <option value='GROUP9'>Group9</option>
        <option value='GROUP0'>Group0</option>
      </select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'imgslider'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'imgslider'); ?></label>
      <select name="ImgSlider_status" id="ImgSlider_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'imgslider'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'imgslider'); ?></label>
      <input name="ImgSlider_order" type="text" id="ImgSlider_order" size="10" value="1" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'imgslider'); ?></p>
      <input name="ImgSlider_id" id="ImgSlider_id" type="hidden" value="">
      <input type="hidden" name="ImgSlider_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Insert Details', 'imgslider'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="ImgSlider_redirect()" value="<?php _e('Cancel', 'imgslider'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="ImgSlider_help()" value="<?php _e('Help', 'imgslider'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('ImgSlider_form_add'); ?>
    </form>
</div>
	<p class="description">
		<?php _e('Check official website for more information', 'imgslider'); ?>
		<a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><?php _e('click here', 'imgslider'); ?></a>
	</p>
</div>