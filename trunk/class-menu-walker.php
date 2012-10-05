<?php

class isDraftMenuWalker extends Walker_Nav_Menu {

	/* Override start_el method */
	
	function start_el(&$output, $item, $depth, $args) {
	   global $wp_query;
	   if ( !$this->isObjectDraft( $item->object_id ) ) {
		   	$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
		   	$class_names = $value = '';
	
		   	$classes = empty( $item->classes ) ? array() : (array) $item->classes;
	
		   	$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		   	$class_names = ' class="'. esc_attr( $class_names ) . '"';
	
		   	$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
	
		   	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		   	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		  	$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		   	$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
			$item_output .= $description.$args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
	
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	
	/* Override end_el method */
	
	function end_el(&$output, $item, $depth, $args) {
		global $wp_query;
		if ( !$this->isObjectDraft( $item->object_id ) ) {
			$output .= apply_filters( 'walker_nav_menu_end_el', $item_output, $item, $depth, $args );
		}
	
	}

	
	private function isObjectDraft( $object_id ) {
		$page = get_page( $object_id );
		if ( $page ) {
			if ( $page->post_status != "publish" ) {
				return true;
			}
		}
		return false;
	}
}

?>