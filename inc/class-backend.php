<?php
/**
 * Backend class
 *
 * nonce, validera postningen
 * frontend, get_image('position');
 */

namespace RI;

class Backend  {


	private static $instance;


	/**
	 * Construct
	 */
	public function __construct(){

		add_action('save_post', array( $this, 'save_related_images' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_ri_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

	}


	/**
	 * return the instance
	 */
	public static function get() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Backend;
		}

		return self::$instance;
	}


	/**
	 * Load scripts we need
	 * */
	public function scripts() {

		// Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs.
		wp_enqueue_media();

		// Add scripts
		wp_register_script( 'ri-media', RI_PLUGIN_URL.'js/media.js', array( 'jquery' ), '1.0.0', true );

		// get positions
		$pre_defined_positions = \RI\Config::get('pre-defined-positions');

		$positions_as_json = json_encode($pre_defined_positions);

		wp_localize_script( 'ri-media', 'ri_media',
			array(
				'title' => __( 'Upload or Choose Your Image Files' ),
				'button' => __( 'Add related images' ),
				'positions' => $positions_as_json
			)
		);

		wp_enqueue_script( 'ri-media' );

	}


	/**
	 * Add metabox
	 */
	public function add_ri_meta_box() {

		add_meta_box( 'ri_meta_box_id', 'Related images', array( $this, 'ri_box' ), 'post' );

	}

	/**
	 * print the meta box
	 */
	public function ri_box( $post ) {

		$vars = array();

		$key = $post->ID.'_'.\RI\Config::get('related_images');

		$vars['related_images'] = get_post_meta( $post->ID, $key, true );

		echo \RI\Tpl::get()->process_template( 'meta-box', $vars );

	}


	/**
	 * save images and positions
	 */
	public function save_related_images( $post_id ){

		/**
		 * Don't save on autosave
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;


		/**
		 * Check the permissions
		 */
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;


		/**
		 * get the data
		 */
		$data = $this->get_images_and_positions( $_POST['related-images-select'] );


		/**
		 * save
		 */
		$this->save_metadata_fields( $post_id, $data );


	}

	/**
	 * split up the post and figure out images and positions
	 */
	public function get_images_and_positions( $related_images ){

		$return = array();

		foreach ($related_images as $key => $value) {

			$exploded = explode('_', $value);

			$item = array(
				'img_id' => $exploded[0],
				'position' => $exploded[1],
			);

			array_push($return, $item);

		}

		return $return;

	}



	/**
	 * Save or delete the metadata
	 */
	protected function save_metadata_fields( $post_id, $data ) {

		$nbr = count( $data );

		$key = $post_id.'_'.\RI\Config::get('related_images');

		if ( $nbr > 0 ) {
			update_post_meta( $post_id, $key, $data );
		} else {
			delete_post_meta( $post_id, $key );
		}

	}

}

Backend::get();