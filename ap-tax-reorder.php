<?php
/*
Plugin Name: AgentPress Listings Taxonomy Reorder
Description: Allows the reordering of AgentPress Listings taxonomies after creation.
Version: 1.0
Author: Robert Iseley
Author URI: http://www.robertiseley.com
*/


add_action('admin_menu', 'ap_tax_reorder_init', 20);
function ap_tax_reorder_init() {
	add_submenu_page( 'edit.php?post_type=listing', __( 'Reorder Taxonomies', 'apl' ), __( 'Reorder Taxonomies', 'apl' ), 'manage_options', 'ap-tax-reorder', 'ap_tax_reorder');
}

add_action( 'admin_enqueue_scripts', 'ap_tax_reorder_enqueue' );
function ap_tax_reorder_enqueue() {
		wp_enqueue_script('jquery-ui-sortable');
}

function ap_tax_reorder() {
	$ap_taxes = get_option('agentpress_taxonomies');

	if($_POST) {
		$new_order = $_POST['ap-tax'];
		$ap_taxes_reordered = array();
		foreach( $new_order as $tax ) {
			if($ap_taxes[$tax])
				$ap_taxes_reordered[$tax] = $ap_taxes[$tax];	
		}
		$ap_taxes = $ap_taxes_reordered;
		update_option('agentpress_taxonomies', $ap_taxes_reordered);
		
	}
screen_icon( 'themes' ); ?>
<h2><?php _e( 'Reorder Taxonomies', 'apl' ); ?></h2>
<div id="col-container">
<style>
	#sortable { list-style-type: none; margin: 10px 0 ; padding: 0; }
	#sortable li .item { 
		-moz-border-radius: 6px 6px 6px 6px;
		border: 1px solid #E6E6E6;
		font-weight: bold;
		height: auto;
		line-height: 35px;
		overflow: hidden;
		padding-left: 10px;
		position: relative;
		text-shadow: 0 1px 0 white;
		width: auto;
		word-wrap: break-word;
		cursor: move;
		background: none repeat-x scroll left top #DFDFDF;
		-moz-box-shadow: 2px 2px 3px #888;
		-webkit-box-shadow: 2px 2px 3px #888;
		box-shadow: 2px 2px 3px #888;
	}
	#sortable li span { position: absolute; margin-left: -1.3em; }
	.ui-state-highlight { background: #E6E6E6; border: 1px #666 dashed; }
	.ap-submit { padding: 5px 10px; }
	.ap-submit:hover { background: #eaf2fa; font-weight: bold;}
	</style>
	<script>
	jQuery(function($) {
		$( "#sortable" ).sortable({ placeholder: 'ui-state-highlight', forcePlaceholderSize: true});
		$( "#sortable" ).disableSelection();
	});
	</script>
	<div id="col-left">
	<div class="col-wrap">
    <span>Drag and Drop to reorder</span>
	<form method="post">
	<ul id="sortable">
    	<?php foreach($ap_taxes as $ap_tax_key => $ap_tax_value) { ?>
        	<li class="ui-state-default">
            	<div class="item">
					<?php echo $ap_tax_value['labels']['name']; ?><input type="hidden" id="ap-tax[]" name="ap-tax[]" value="<?php echo $ap_tax_key; ?>" />
                </div>
            </li>
        <?php } ?>
	</ul>
    <input class="ap-submit" type="submit" value="Save" />
	</form>
	</div>
	</div><!-- /col-left -->

</div><!-- /col-container -->
<?php
}

?>