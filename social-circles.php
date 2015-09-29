<?php
/*
Plugin Name: Social Circles
Plugin URI: http://www.studiopress.com/plugins/social-circles
Description: Create social media icons for the header or sidebar of your website.
Author: Brian Gardner
Author URI: http://www.briangardner.com

Version: 0.9.1

License: GNU General Public License v2.0 (or later)
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

class Social_Circles_Widget extends WP_Widget {

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $sizes;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $profiles;

	/**
	 * Constructor method.
	 *
	 * Set some global values and create widget.
	 */
	function __construct() {

		/**
		 * Default widget option values.
		 */
		$this->defaults = array(
			'title'          => '',
			'new_window'     => 0,
			'style'          => 4,
			'bg_color'       => '#fbb114',
			'bg_color_hover' => '#fd518d',
			'alignment'      => 'alignleft',
			'email'          => '',
			'etsy'           => '',
			'facebook'       => '',
			'flickr'         => '',
			'gplus'          => '',
			'instagram'      => '',
			'linkedin'       => '',
			'pinterest'      => '',
			'rss'            => '',
			'tumblr'         => '',
			'twitter'        => '',
			'youtube'        => '',
		);

		/**
		 * Icon sizes.
		 */
		$this->styles = array( 'Corsiva', 'Georgia', 'Lobster', 'Oswald', 'Playbill', 'Sinhala', 'Ubuntu' );

		/**
		 * Social profile choices.
		 */
		$this->profiles = array(
			'email' => array(
				'label' => __( 'Email URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-email"><a href="%s" %s>Email</a></li>',
				'background_position' => '0 0'
			),
			'etsy' => array(
				'label' => __( 'Etsy URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-etsy"><a href="%s" %s>Etsy</a></li>',
				'background_position' => '-64px 0'
			),
			'facebook' => array(
				'label' => __( 'Facebook URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-facebook"><a href="%s" %s>Facebook</a></li>',
				'background_position' => '-128px 0'
			),
			'flickr' => array(
				'label' => __( 'Flickr URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-flickr"><a href="%s" %s>Flickr</a></li>',
				'background_position' => '-192px 0'
			),
			'gplus' => array(
				'label' => __( 'Google+ URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-gplus"><a href="%s" %s>Google+</a></li>',
				'background_position' => '-256px 0'
			),
			'instagram' => array(
				'label' => __( 'Instagram URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-instagram"><a href="%s" %s>Instragram</a></li>',
				'background_position' => '-320px 0'
			),
			'linkedin' => array(
				'label' => __( 'LinkedIn URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-linkedin"><a href="%s" %s>LinkedIn</a></li>',
				'background_position' => '0 -64px'
			),
			'pinterest' => array(
				'label' => __( 'Pinterest URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-pinterest"><a href="%s" %s>Pinterest</a></li>',
				'background_position' => '-64px -64px'
			),
			'rss' => array(
				'label' => __( 'RSS URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-rss"><a href="%s" %s>RSS</a></li>',
				'background_position' => '-128px -64px'
			),
			'tumblr' => array(
				'label' => __( 'Tumblr URI', 'social-circles' ),
				'pattern'	 => '<li class="social-circles-tumblr"><a href="%s" %s>Tumblr</a></li>',
				'background_position' => '-192px -64px'
			),
			'twitter' => array(
				'label' => __( 'Twitter URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-twitter"><a href="%s" %s>Twitter</a></li>',
				'background_position' => '-256px -64px'
			),
			'youtube' => array(
				'label' => __( 'YouTube URI', 'social-circles' ),
				'pattern' => '<li class="social-circles-youtube"><a href="%s" %s>YouTube</a></li>',
				'background_position' => '-320px -64px'
			),
		);

		$widget_ops = array(
			'classname'	  => 'social-circles',
			'description' => __( 'Displays select social icons.', 'social-circles' ),
		);

		$control_ops = array(
			'id_base' => 'social-circles',
		);

		parent::__construct( 'social-circles', __( 'Social Circles', 'social-circles' ), $widget_ops, $control_ops );

		/** Load CSS in <head> */
		add_action( 'wp_head', array( $this, 'css' ) );

	}

	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 */
	function form( $instance ) {

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'social-circles' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label><input id="<?php echo $this->get_field_id( 'new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="1" <?php checked( 1, $instance['new_window'] ); ?>/> <?php esc_html_e( 'Open links in new window?', 'social-circles' ); ?></label></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Icon Font', 'social-circles' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
				<?php
				foreach ( (array) $this->styles as $style ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $style ), selected( $style, $instance['style'], 0 ), esc_html( $style ) );
				}
				?>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e( 'Icon Color:', 'social-circles' ); ?></label> <input id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['bg_color'] ); ?>" size="8" /></p>

		<p><label for="<?php echo $this->get_field_id( 'bg_color_hover' ); ?>"><?php _e( 'Hover Color:', 'social-circles' ); ?></label> <input id="<?php echo $this->get_field_id( 'bg_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'bg_color_hover' ); ?>" type="text" value="<?php echo esc_attr( $instance['bg_color_hover'] ); ?>" size="8" /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'social-circles' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="alignleft" <?php selected( 'alignleft', $instance['alignment'] ) ?>><?php _e( 'Align Left', 'social-circles' ); ?></option>
				<option value="alignright" <?php selected( 'alignright', $instance['alignment'] ) ?>><?php _e( 'Align Right', 'social-circles' ); ?></option>
			</select>
		</p>

		<hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

		<?php
		foreach ( (array) $this->profiles as $profile => $data ) {

			printf( '<p><label for="%s">%s:</label>', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $data['label'] ) );
			printf( '<input type="text" id="%s" class="widefat" name="%s" value="%s" /></p>', esc_attr( $this->get_field_id( $profile ) ), esc_attr( $this->get_field_name( $profile ) ), esc_url( $instance[$profile] ) );

		}

	}

	/**
	 * Form validation and sanitization.
	 *
	 * Runs when you save the widget form. Allows you to validate or sanitize widget options before they are saved.
	 *
	 */
	function update( $newinstance, $oldinstance ) {

		foreach ( $newinstance as $key => $value ) {

			/** Validate hex code colors */
			if ( strpos( $key, '_color' ) && 0 == preg_match( '/^#(([a-fA-F0-9]{3}$)|([a-fA-F0-9]{6}$))/', $value ) ) {
				$newinstance[$key] = $oldinstance[$key];
			}

			/** Sanitize Profile URIs */
			elseif ( array_key_exists( $key, (array) $this->profiles ) ) {
				$newinstance[$key] = esc_url( $newinstance[$key] );
			}

		}

		return $newinstance;

	}

	/**
	 * Widget Output.
	 *
	 * Outputs the actual widget on the front-end based on the widget options the user selected.
	 *
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

			if ( ! empty( $instance['title'] ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

			$output = '';

			$new_window = $instance['new_window'] ? 'target="_blank"' : '';

			foreach ( (array) $this->profiles as $profile => $data ) {
				if ( ! empty( $instance[$profile] ) )
					$output .= sprintf( $data['pattern'], esc_url( $instance[$profile] ), $new_window );
			}

			if ( $output )
				printf( '<ul class="%s">%s</ul>', $instance['alignment'], $output );

		echo $after_widget;

	}

	/**
	 * Custom CSS.
	 *
	 * Outputs custom CSS to control the look of the icons.
	 */
	function css() {

		/** Pull widget settings, merge with defaults */
		$all_instances = $this->get_settings();
		$instance = wp_parse_args( $all_instances[$this->number], $this->defaults );

		/** The image locations */
		$imgs = array(
			'Corsiva'  => plugin_dir_url( __FILE__ ) . 'images/social-circles-corsiva.png',
			'Georgia'  => plugin_dir_url( __FILE__ ) . 'images/social-circles-georgia.png',
			'Lobster'  => plugin_dir_url( __FILE__ ) . 'images/social-circles-lobster.png',
			'Oswald'   => plugin_dir_url( __FILE__ ) . 'images/social-circles-oswald.png',
			'Playbill' => plugin_dir_url( __FILE__ ) . 'images/social-circles-playbill.png',
			'Sinhala'  => plugin_dir_url( __FILE__ ) . 'images/social-circles-sinhala.png',
			'Ubuntu'   => plugin_dir_url( __FILE__ ) . 'images/social-circles-ubuntu.png',
		);

		/** The CSS to output */
		$css = '.social-circles {
			overflow: hidden;
		}
		.social-circles .alignleft, .social-circles .alignright {
			margin: 0; padding: 0;
		}
		.social-circles ul li {
			background: none !important;
			border: none !important;
			float: left;
			list-style-type: none !important;
			margin: 0 5px 10px !important;
			padding: 0 !important;
		}
		.social-circles ul li a,
		.social-circles ul li a:hover {
			-moz-border-radius: 50%;
			-moz-transition: all 0.2s ease-in-out;
			-webkit-border-radius: 50%;
			-webkit-transition: all 0.2s ease-in-out;
			background: ' . $instance['bg_color'] . ' url(' . $imgs[$instance['style']] . ') no-repeat;
			border-radius: 50%;
			display: block;
			height: 64px;
			overflow: hidden;
			text-indent: -999px;
			transition: all 0.2s ease-in-out;
			width: 64px;
		}

		.social-circles ul li a:hover {
			background-color: ' . $instance['bg_color_hover'] . ';
		}';

		/** Individual Profile button styles */
		foreach ( (array) $this->profiles as $profile => $data ) {

			if ( ! $instance[$profile] )
				continue;

			$css .= '.social-circles ul li.social-circles-' . $profile . ' a,
			.social-circles ul li.social-circles-' . $profile . ' a:hover {
				background-position: ' . $data['background_position'] . ';
			}';

		}

		/** Minify a bit */
		$css = str_replace( "\t", '', $css );
		$css = str_replace( array( "\n", "\r" ), ' ', $css );

		/** Echo the CSS */
		echo '<style type="text/css" media="screen">' . $css . '</style>';

	}

}

add_action( 'widgets_init', 'scw_load_widget' );
/**
 * Widget Registration.
 *
 * Register Social Circles widget.
 *
 */
function scw_load_widget() {

	register_widget( 'Social_Circles_Widget' );

}