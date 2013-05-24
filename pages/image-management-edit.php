<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

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
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$ImgSlider_errors = array();
	$ImgSlider_success = '';
	$ImgSlider_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_ImgSlider_TABLE."`
		WHERE `ImgSlider_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'ImgSlider_path' => $data['ImgSlider_path'],
		'ImgSlider_link' => $data['ImgSlider_link'],
		'ImgSlider_target' => $data['ImgSlider_target'],
		'ImgSlider_title' => $data['ImgSlider_title'],
		'ImgSlider_desc' => $data['ImgSlider_desc'],
		'ImgSlider_order' => $data['ImgSlider_order'],
		'ImgSlider_status' => $data['ImgSlider_status'],
		'ImgSlider_type' => $data['ImgSlider_type']
	);
}
// Form submitted, check the data
if (isset($_POST['ImgSlider_form_submit']) && $_POST['ImgSlider_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ImgSlider_form_edit');
	
	$form['ImgSlider_path'] = isset($_POST['ImgSlider_path']) ? $_POST['ImgSlider_path'] : '';
	if ($form['ImgSlider_path'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image path.', WP_ImgSlider_UNIQUE_NAME);
		$ImgSlider_error_found = TRUE;
	}

	$form['ImgSlider_link'] = isset($_POST['ImgSlider_link']) ? $_POST['ImgSlider_link'] : '';
	if ($form['ImgSlider_link'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the target link.', WP_ImgSlider_UNIQUE_NAME);
		$ImgSlider_error_found = TRUE;
	}
	
	$form['ImgSlider_target'] = isset($_POST['ImgSlider_target']) ? $_POST['ImgSlider_target'] : '';
	
	$form['ImgSlider_title'] = isset($_POST['ImgSlider_title']) ? $_POST['ImgSlider_title'] : '';
	if ($form['ImgSlider_title'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image title.', WP_ImgSlider_UNIQUE_NAME);
		$ImgSlider_error_found = TRUE;
	}
	
	$form['ImgSlider_desc'] = isset($_POST['ImgSlider_desc']) ? $_POST['ImgSlider_desc'] : '';
	$form['ImgSlider_order'] = isset($_POST['ImgSlider_order']) ? $_POST['ImgSlider_order'] : '';
	$form['ImgSlider_status'] = isset($_POST['ImgSlider_status']) ? $_POST['ImgSlider_status'] : '';
	$form['ImgSlider_type'] = isset($_POST['ImgSlider_type']) ? $_POST['ImgSlider_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($ImgSlider_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_ImgSlider_TABLE."`
				SET `ImgSlider_path` = %s,
				`ImgSlider_link` = %s,
				`ImgSlider_target` = %s,
				`ImgSlider_title` = %s,
				`ImgSlider_desc` = %s,
				`ImgSlider_order` = %d,
				`ImgSlider_status` = %s,
				`ImgSlider_type` = %s
				WHERE ImgSlider_id = %d
				LIMIT 1",
				array($form['ImgSlider_path'], $form['ImgSlider_link'], $form['ImgSlider_target'], $form['ImgSlider_title'], $form['ImgSlider_desc'], $form['ImgSlider_order'], $form['ImgSlider_status'], $form['ImgSlider_type'], $did)
			);
		$wpdb->query($sSql);
		
		$ImgSlider_success = __('Image details was successfully updated.', WP_ImgSlider_UNIQUE_NAME);
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
if ($ltw_tes_error_found == FALSE && strlen($ImgSlider_success) > 0)
{
?>
  <div class="updated fade">
    <p><strong><?php echo $ImgSlider_success; ?></strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/image-slider-with-description/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo WP_ImgSlider_TITLE ?></h2>
	<form name="ImgSlider_form" method="post" action="#" onsubmit="return ImgSlider_submit()"  >
      <h3>Edit image details</h3>
      <label for="tag-image">Enter image path</label>
      <input name="ImgSlider_path" type="text" id="ImgSlider_path" value="<?php echo $form['ImgSlider_path']; ?>" size="125" />
      <p>Where is the picture located on the internet</p>
      <label for="tag-link">Enter target link</label>
      <input name="ImgSlider_link" type="text" id="ImgSlider_link" value="<?php echo $form['ImgSlider_link']; ?>" size="125" />
      <p>When someone clicks on the picture, where do you want to send them</p>
      <label for="tag-target">Enter target option</label>
      <select name="ImgSlider_target" id="ImgSlider_target">
        <option value='_blank' <?php if($form['ImgSlider_target']=='_blank') { echo 'selected' ; } ?>>_blank</option>
        <option value='_parent' <?php if($form['ImgSlider_target']=='_parent') { echo 'selected' ; } ?>>_parent</option>
        <option value='_self' <?php if($form['ImgSlider_target']=='_self') { echo 'selected' ; } ?>>_self</option>
        <option value='_new' <?php if($form['ImgSlider_target']=='_new') { echo 'selected' ; } ?>>_new</option>
      </select>
      <p>Do you want to open link in new window?</p>
      <label for="tag-title">Enter image title</label>
      <input name="ImgSlider_title" type="text" id="ImgSlider_title" value="<?php echo $form['ImgSlider_title']; ?>" size="125" />
      <p>Enter the image title. The title will display on the image within the slideshow.</p>
      <label for="tag-desc">Enter image description</label>
      <input name="ImgSlider_desc" type="text" id="ImgSlider_desc" value="<?php echo $form['ImgSlider_desc']; ?>" size="125" />
      <p>Enter image description</p>
      <label for="tag-select-gallery-group">Select gallery group</label>
      <select name="ImgSlider_type" id="ImgSlider_type">
        <option value='GROUP1' <?php if($form['ImgSlider_type']=='GROUP1') { echo 'selected' ; } ?>>Group1</option>
        <option value='GROUP2' <?php if($form['ImgSlider_type']=='GROUP2') { echo 'selected' ; } ?>>Group2</option>
        <option value='GROUP3' <?php if($form['ImgSlider_type']=='GROUP3') { echo 'selected' ; } ?>>Group3</option>
        <option value='GROUP4' <?php if($form['ImgSlider_type']=='GROUP4') { echo 'selected' ; } ?>>Group4</option>
        <option value='GROUP5' <?php if($form['ImgSlider_type']=='GROUP5') { echo 'selected' ; } ?>>Group5</option>
        <option value='GROUP6' <?php if($form['ImgSlider_type']=='GROUP6') { echo 'selected' ; } ?>>Group6</option>
        <option value='GROUP7' <?php if($form['ImgSlider_type']=='GROUP7') { echo 'selected' ; } ?>>Group7</option>
        <option value='GROUP8' <?php if($form['ImgSlider_type']=='GROUP8') { echo 'selected' ; } ?>>Group8</option>
        <option value='GROUP9' <?php if($form['ImgSlider_type']=='GROUP9') { echo 'selected' ; } ?>>Group9</option>
        <option value='GROUP0' <?php if($form['ImgSlider_type']=='GROUP0') { echo 'selected' ; } ?>>Group0</option>
      </select>
      <p>This is to group the images. Select your slideshow group. </p>
      <label for="tag-display-status">Display status</label>
      <select name="ImgSlider_status" id="ImgSlider_status">
        <option value='YES' <?php if($form['ImgSlider_status']=='YES') { echo 'selected' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['ImgSlider_status']=='NO') { echo 'selected' ; } ?>>No</option>
      </select>
      <p>Do you want the picture to show in your galler?</p>
      <label for="tag-display-order">Display order</label>
      <input name="ImgSlider_order" type="text" id="ImgSlider_order" size="10" value="<?php echo $form['ImgSlider_order']; ?>" maxlength="3" />
      <p>What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.</p>
      <input name="ImgSlider_id" id="ImgSlider_id" type="hidden" value="<?php echo @$ImgSlider_id_x; ?>">
      <input type="hidden" name="ImgSlider_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="Update Details" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="ImgSlider_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="ImgSlider_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('ImgSlider_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo WP_ImgSlider_LINK; ?></p>
</div>