<?php
/**
* Plugin Name: Knjižnice: Gradnik COBISS
* Plugin URI: https://www.knjiznice.si
* Description: Gradnik za iskanje po sistemu COBISS.
* Version: 1.2.0
* Author: TrueCAD d.o.o.
* Author URI: https://www.truecad.si/
**/add_action( 'init', 'cobiss_search_load_textdomain' );function cobiss_search_load_textdomain() {  load_plugin_textdomain('cobiss-search', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); }


function knjiznice_register_widget_cobisssearch() {
    register_widget('knjiznice_widget_cobisssearch');
}

add_action('widgets_init', 'knjiznice_register_widget_cobisssearch');

class knjiznice_widget_cobisssearch extends WP_Widget {
	function __construct() {
		parent::__construct('knjiznice_widget_cobisssearch', __('COBISS iskalnik', 'cobiss-search'), array( 'description' => __( 'Iskanje po COBISS-u', 'cobiss-search' )));
	}
	
	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title']);
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		?>
		<form action="https://plus.si.cobiss.net/opac7/bib/search" method="GET" target="_blank">
			<input type="hidden" name="db" value="<?php echo $instance['cobissakronim']; ?>" />
			<input type="hidden" name="mat" value="allmaterials" />
			<input type="text" name="q" placeholder="<?php echo __('iskanje po cobiss', 'cobiss-search'); ?>" />
			<button type="submit" aria-label="najdi"><i class="fa fa-search" aria-hidden="true"></i></button>
		</form>		
		<?php
		echo $args['after_widget'];
	}
	
	public function form($instance) {
		$title = '';
		$cobissakronim = '';
		if (isset($instance['title'])) $title = $instance['title'];
		else $title = __('', 'knjiznice');
		
		if (isset($instance['cobissakronim'])) $cobissakronim = $instance['cobissakronim'];
		else $cobissakronim = __('', 'knjiznice');
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		<label for="<?php echo $this->get_field_id('cobissakronim'); ?>"><?php _e( 'COBISS akronim:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('cobissakronim'); ?>" name="<?php echo $this->get_field_name('cobissakronim'); ?>" type="text" value="<?php echo esc_attr($cobissakronim); ?>" />
		</p>
		<?php 
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['cobissakronim'] = (!empty($new_instance['cobissakronim'])) ? strip_tags($new_instance['cobissakronim']) : '';
		return $instance;
	}
}