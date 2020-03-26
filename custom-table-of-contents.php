<?php
/**
* Plugin Name: Custom TOC plugin
* Plugin URI: https://devslib.com/how-to-add-table-of-contents-without-plugin/
* Description: This is a custom TOC plugin
* Version: 1.0
* Author: David Moss
* Author URI: https://devslib.com/
**/

//=====================================================================
// Table of contents shortcode
// Example: [ctoc title="Table Title" type="ul" items="Item1,Item 2"]
//=====================================================================

function custom_toc_shortcode_devslib( $atts ) {
	// defaults
	$a = shortcode_atts( array(
		'title' => 'Table of contents',
		'type' => 'ol',
		'items' => ''
	), $atts );
	$r = '<div class="toc"><h4>'.$a['title'].'</h4><'.$a['type'].'>';

	if(!empty($a['items'])){
		$items = explode(",", $a['items']);
		foreach($items as $item){
			$r .= '<li><a href="#'.str_replace(' ', '_', $item).'">'.$item.'</a></li>';
		}
	}
	
	$r .= '</'.$a['type'].'></div>';
	return $r;
}

add_shortcode( 'ctoc', 'custom_toc_shortcode_devslib' );

//===========================================================================
// Function to add ID to every heading tag if [ctoc] shortcode is used
// `<h2>Hello there</h2>` converts to `<h2 id="Hello_there">Hello there</h2>`
//===========================================================================


function auto_id_headings_devslib( $content ) {
	
	if(has_shortcode( $content, 'ctoc' )){
		$content = preg_replace_callback( '/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i', function( $matches ) {
			if ( ! stripos( $matches[0], 'id=' ) ) :
				$matches[0] = $matches[1] . $matches[2] . ' id="' . str_replace(' ', '_', $matches[3]) . '">' . $matches[3] . $matches[4];
			endif;
			return $matches[0];
		}, $content );
	}
	
    return $content;
}
add_filter( 'the_content', 'auto_id_headings_devslib' );

//==========================================================
// ADD CSS CODES IN WP_HEAD
//==========================================================

function css_for_custom_toc_devslib() {
    ?>
<style>
.toc{float:right;max-width:40%;min-width:35%;padding:10px 0 0 10px;border:1px solid #ccc;margin:0 0 10px 10px}.toc h4{margin-bottom:10px}.toc ul,.toc ol{margin:10px 10px 10px 20px}@media only screen and (max-width:959px){.toc{width:100%;max-width:unset;min-width:unset;float:none;padding:10px;margin:unset}}</style>
    <?php
    
}
add_action('wp_head', 'css_for_custom_toc_devslib');
