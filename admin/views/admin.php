<?php
/**
 * Represents the view for the dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Visual_Recipe_Index_Pro
 * @author    Simon Austin <simon@kremental.com>
 * @license   GPL-2.0+
 * @link      http://kremental.com
 * @copyright 2014 Kremental
 */


$tabs = array(
		'general' => 'General',
		'advanced' => 'Advanced Options',
		'multi' => 'Multiple Category Instructions'
);


// define variables for the options
$opt_name = 'vrip_options';
$opt_val = get_option( $opt_name );
$hidden_var_name = 'vrip_hidden';
$opt_categories = 'vrip_categories';
$opt_theme = 'vrip_theme';
$opt_content = 'vrip_content';
$opt_title = 'vrip_title';
$opt_url = 'vrip_url';

// see if the user has posted some information
// if they did, set the hidden variable to 'Y'
if (isset ( $_POST[$hidden_var_name] ) && $_POST[$hidden_var_name] == 'Y' ) {
	// read posted value
	$opt_val = $_POST[$opt_categories];
	
	// save the posted values in the database
	// update_option ( $opt_categories, $opt_val );
	
	// set up array to create page
	$_p = array();
	$pageurl = $_POST['pageurl'];
	$_p['post_title'] = $_POST['pagetitle'];
	$_p['post_status'] = 'publish';
	$categories = $_POST['categories'];  // this will be used in content
	$theme = $_POST['theme']; // this will be used in content
	
	// Generate shortcodes
	foreach ($categories as $cat_id){
		$cat_name = get_cat_name( $cat_id );
		$_p['post_content'] .= $cat_name . PHP_EOL;
		$_p['post_content'] .= "[vrip id=$cat_id num=2000 orderby='title' theme='$theme']" . PHP_EOL . PHP_EOL;
	}
	
	$_p['comment_status'] = 'closed';
	$_p['ping_status'] = 'closed';
	$_p['post_type'] = 'page';
	
	// create the page
	$page_id = wp_insert_post($_p);
	if ( !$page_id ) {
		// There was an error
?>
		<div class='updated'><p><strong><?php  _e('Whoops, an error occured creating the page.', 'Visual Recipe Index Pro');?></strong></p></div>
<?php
	} else {

	// set the url for the newly created page
	// STUB
	
	// put a settings updated message on the screen
	
?>
	<div class='updated'><p><strong><?php  _e('Visual Recipe Index Page Created.', 'Visual Recipe Index Pro');?></strong></p></div>
<?php
	}
}

// Find out what tab we should display
if ( isset ( $_GET['tab'] ) ) {
	$current = $_GET['tab'];
} else {
	$current = 'general';
}
$links = array();
foreach ($tabs as $tab => $name) {
	if ( $tab == $current ){
		$links[] = "<a class='nav-tab nav-tab-active' href='?page=visual-recipe-index-pro&tab=$tab '>$name</a>";
	} else {
		$links[] = "<a class='nav-tab' href=?page=visual-recipe-index-pro&tab=$tab '>$name</a>";
	}
}
// now display the settings editing page
?>
<div class='wrap'>
	<div id='icon-themes' class='icon32'><br /></div>
	<h2 class='nav-tab-wrapper'><?php 
foreach ( $links as $link ) {
	echo $link;
}?></h2>
<?php if ( isset( $_GET['settings-updated'] ) ) {
	echo "div class='updated'><p>Visual Recipe Index Pro updated successfully.</p></div>";
}?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	This plugin allows you to easily create stunning, automatically updating visual recipe indexes for your site.
	<ol>
		<li>Add options for numbers of columns?</li>
		<li>Preview</li>
		<li>Publish</li>
	</ol>
	<div id='vrip-form'>
		<form name='vrip-settings' method='post' action='options-general.php?page=visual-recipe-index-pro'>
		<?php settings_fields( 'vrip-options-group' )?>
			<input type='hidden' name='<?php echo $hidden_var_name; ?>' value='Y'>
			<div class='vrip-admin'>Choose your theme (use some jQuery here to preview?)</div>
			<div class='vrip-admin-input'>
				<select name='theme' id='vrip-theme'>
					<option value='masonry'>Pintrest Style</option>
					<option value='original'>Original</option>
				</select>
			</div>
			<div class='vrip-admin'>Choose Categories to display (hold down the CTRL or CMD key to choose multiple categories) (use jQuery to preview?)</div>
			<div class='vrip-admin-input'>
				<select name='categories[]' id='vrip-categories' multiple='multiple'>
<?php
	/** Choose from a list of categories to include in recipe index */	
	$args = array(
	  'orderby' => 'name',
	  'order' => 'ASC'
	  );
	$categories = get_categories($args);
	  foreach($categories as $category) { 
	  	// STUB - work in way to pre-check boxes that have already been chosen
	    echo '<option value='.$category->term_id.'>'. $category->name.'</option>\n';
	  } 
?>
				</select>
			</div>
			<div class='vrip-admin'>Title of the recipe index</div>
			<div class='vrip-admin-input'><input type='text' name='pagetitle' id='vrip-pagetitle' value='Recipe Index'></div>
			<div class='vrip-admin'>URL</div>
			<div class='vrip-admin-input'><?php echo site_url(); ?>/<input type='text' name='pageurl' id='vrip-pageurl' value='recipe-index'></div>
			<div class='vrip-admin-input'><input type='submit' value='Create recipe index'></div>
		</form> <!-- vrip-settings -->
	</div> <!-- vrip-form -->
</div>
