<?php
/**
 *
 *
 * @package   Brasa Slider
 * @author    Matheus Gimenez <contato@matheusgimenez.com.br>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Matheus Gimenez
 *
 * @wordpress-plugin
 * Plugin Name:       Brasa Slider
 * Plugin URI:        http://codeispoetry.info/plugins/reveal-modal
 * Description:       Brasa Slider
 * Version:           1.0.0
 * Author:            Matheus Gimenez
 * Plugin URI:        http://codeispoetry.info/plugins/reveal-modal
 * Text Domain:       brasa_slider
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/brasadesign/reveal-modal
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class Brasa_Slider {
	public function __construct() {
		define( 'BRASA_SLIDER_URL', plugin_dir_url( __FILE__ ) );
		add_image_size( 'brasa_slider_img', 1006, 408, true );
		add_action('init',array($this, 'init')); //init
		add_action( 'admin_init', array($this, 'admin_scripts') );
		add_action( 'add_meta_boxes', array( $this, 'add_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_shortcode( 'brasa_slider',  array( $this, 'shortcode' ) );
	}
	public function init(){
		if(isset($_GET['brasa_slider_ajax']) && $_GET['brasa_slider_ajax'] == 'true' && current_user_can('edit_posts')){
			$this->ajax_search();
		}
		wp_enqueue_script('jquery');
		wp_enqueue_script(
			'brasa_slider_jqueryui_js',
			BRASA_SLIDER_URL . 'assets/js/slick.min.js',
			array('jquery')
		);
		wp_enqueue_style( 'brasa_slider_css_frontend', BRASA_SLIDER_URL . 'assets/css/slick.css' );
		$this->register_cpt();
	}
	private function register_cpt(){
		$labels = array(
			'name'                => _x( 'Brasa Sliders', 'Post Type General Name', 'brasa_slider' ),
			'singular_name'       => _x( 'Brasa Slider', 'Post Type Singular Name', 'brasa_slider' ),
			'menu_name'           => __( 'Brasa Slider', 'brasa_slider' ),
			'parent_item_colon'   => __( 'Slider parent', 'brasa_slider' ),
			'all_items'           => __( 'All sliders', 'brasa_slider' ),
			'view_item'           => __( 'View slider', 'brasa_slider' ),
			'add_new_item'        => __( 'Add New Slider', 'brasa_slider' ),
			'add_new'             => __( 'Add New', 'brasa_slider' ),
			'edit_item'           => __( 'Edit Slider', 'brasa_slider' ),
			'update_item'         => __( 'Update Slider', 'brasa_slider' ),
			'search_items'        => __( 'Search Slider', 'brasa_slider' ),
			'not_found'           => __( 'Not found', 'brasa_slider' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'brasa_slider' ),
			);
		$args = array(
			'label'               => __( 'brasa_slider_cpt', 'brasa_slider' ),
			'description'         => __( 'Brasa Slider', 'brasa_slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-images-alt',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
			);
		register_post_type( 'brasa_slider_cpt', $args );
	}
	public function admin_scripts(){
		if(isset($_GET['post'])){
			$post = get_post($_GET['post']);
		}
		if(isset($_GET['post_type']) && $_GET['post_type'] == 'brasa_slider_cpt' || isset($post) && $post->post_type == 'brasa_slider_cpt'){
			wp_enqueue_style( 'brasa_slider_css', BRASA_SLIDER_URL . 'assets/css/admin.css' );
			wp_enqueue_script('jquery');
			wp_enqueue_script(
				'brasa_slider_jqueryui_js',
				BRASA_SLIDER_URL . 'assets/js/jquery-ui.min.js',
				array('jquery')
				);
			wp_enqueue_script(
				'brasa_slider_all_js',
				BRASA_SLIDER_URL . 'assets/js/all.js',
				array('jquery')
				);
		}
	}
	public function add_boxes(){
		add_meta_box(
			'brasa_slider_search'
			,__( 'Search posts by name', 'brasa_slider' )
			,array( $this, 'render_search_meta' )
			,'brasa_slider_cpt'
			,'advanced'
			,'core'
			);
		add_meta_box(
			'brasa_slider_sortable'
			,__( 'Order slider', 'brasa_slider' )
			,array( $this, 'render_sortable_meta' )
			,'brasa_slider_cpt'
			,'advanced'
			,'core'
			);
	}
	public function render_search_meta($post){
		_e('<input type="text" id="search_brasa_slider" placeholder="Search.. ">','brasa_slider');
		_e('<a id="search-bt-slider" class="button button-primary button-large">Search!</a>','brasa_slider');
		echo '<div id="brasa_slider_result" data-url="'.home_url().'"></div>';
	}
	public function render_sortable_meta($post){
		echo '<input type="text" name="brasa_slider_input" id="brasa_slider_hide" style="display:none">';
		echo '<ul id="brasa_slider_sortable_ul">';
		$ids = get_post_meta( $post->ID, 'brasa_slider_ids', true );
		if(!empty($ids)){
			$ids = explode(',',$ids);
			foreach ($ids as $id) {
				echo '<li class="brasa_slider_item is_item" data-post-id="'.$id.'" id="'.$id.'">';
			    echo get_the_post_thumbnail($id);
	      		echo '<div class="title_item">';
	      		echo get_the_title($id);
	   			echo '</div>';
	   		    echo '<div class="container_brasa_link" style="width:100%;margin-left:25%;">';
	      		echo '<label>Link:</label><br>';
	      		echo '<input class="link_brasa_slider" type="text" name="brasa_slider_link_'.$id.'" placeholder="'.__('Link','brasa_slider').'" value="'.esc_url(get_post_meta($post->ID, 'brasa_slider_id'.$id, true )).'">';
	 			echo '</div>';
	   			_e('<a class="rm-item">Remove this</a>','brasa-slider');
	   			echo '</li>';
			}
		}
		echo '</ul>';
	}
	private function ajax_search(){
		$key = $_GET['key'];
	      	/**
			 * The WordPress Query class.
			 * @link http://codex.wordpress.org/Function_Reference/WP_Query
			 *
			 */
	      	$args = array(
				//Type & Status Parameters
	      		'post_type'   => 'any',
	      		's'         => $key
	      		);

	      	$query = new WP_Query( $args );

	      	if ( $query->have_posts() ) {
	      		_e('<h2>Click to select</h2>','brasa-slider');
	      		while ( $query->have_posts() ) {
	      			$query->the_post();
	      			echo '<div class="brasa_slider_item" data-post-id="'.get_the_ID().'">';
	      			the_post_thumbnail();
	      			echo '<div class="title_item">';
	      			the_title();
	      			echo '</div>';
	      			echo '<div class="container_brasa_link" style="width:100%;margin-left:25%;">';
	      			echo '<label>Link:</label><br>';
	      			echo '<input class="link_brasa_slider" type="text" name="brasa_slider_link_'.get_the_ID().'" placeholder="'.__('Link','brasa_slider').'" value="'.get_permalink(get_the_ID()).'">';
	      			echo '</div>';
	      			_e('<a class="rm-item" data-post-id="'.get_the_ID().'">Remove this</a>','brasa-slider');
	      			echo '</div>';
	      		}
	      	}
	      	else{
	      		echo 'Not found';
	      	}
	    die();
	}
	public function save($post_id){
		if(isset($_POST['brasa_slider_input'])){
			$ids = $_POST['brasa_slider_input'];
			if(!empty($ids)){
				update_post_meta($post_id, 'brasa_slider_ids', $ids);
				$ids = explode(',', $ids);
				foreach ($ids as $id) {
					update_post_meta($post_id,'brasa_slider_id'.$id,esc_url($_POST['brasa_slider_link_'.$id]));
				}
			}
			else{
			    delete_post_meta( $post_id, 'brasa_slider_ids' );
			}
		}
	}
	public function shortcode($atts){
		$html = '';
		// Attributes
		extract( shortcode_atts(
			array(
				'name' => '',
				), $atts )
		);
		$slider = get_page_by_title( $atts['name'], OBJECT, 'brasa_slider_cpt' );
		if(!empty($slider) && isset($slider)){
			$ids = get_post_meta( $slider->ID, 'brasa_slider_ids', true );
			$ids = explode(',', $ids);
		    $html = '<div class="col-md-12 is_slider" id="slider-'.$slider->post_name.'">';
		    foreach ($ids as $id) {
		    	$img = get_post_thumbnail_id($id);
		    	$img = wp_get_attachment_image_src( $img, 'brasa_slider_img', false );
		    	$html .= '<div class="slick_slide">';
		    	$html .= '<a href="'.esc_url(get_post_meta($slider->ID, 'brasa_slider_id'.$id, true )).'"><img src="'.$img[0].'" class="img_slider"></a>';
		    	$html .= '</div>';
		    }
		    $html .= '</div>';
		    return $html;
		}
		else{
			return false;
		}
	}
}
new Brasa_Slider();
