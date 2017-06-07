<?php

class Marketify_EDD_Metaboxes {

	public function __construct() {
		add_action( 'add_meta_boxes',          array( $this, 'add_meta_boxes' ) );
		add_action( 'edd_metabox_fields_save', array( $this, 'save_post' ) );

		add_filter( 'marketify_video_field',   array( $this, 'video_field' ) );
		add_filter( 'marketify_demo_field',    array( $this, 'demo_field' ) );
	}

	public function add_meta_boxes() {
		add_meta_box( 'edd-marketify-video', sprintf( __( '%s Video URL', 'marketify' ), edd_get_label_singular() ), array( $this, 'meta_box_video' ), 'download', 'normal', 'high' );
		add_meta_box( 'edd-marketify-demo', sprintf( __( '%s Demo URL', 'marketify' ), edd_get_label_singular() ), array( $this, 'meta_box_demo' ), 'download', 'normal', 'high' );
	}

	public function meta_box_video() {
		global $post;

		echo EDD()->html->text( array(
			'name'  => 'edd_video',
			'value' => esc_url( $post->edd_video ),
			'class' => 'large-text'
		) );
	}

	public function meta_box_demo() {
		global $post;

		echo EDD()->html->text( array(
			'name'  => 'edd_demo',
			'value' => esc_url( $post->edd_demo ),
			'class' => 'large-text'
		) );
	}

	public function save_post( $fields ) {
		$fields[] = 'edd_video';
		$fields[] = 'edd_demo';

		return $fields;
	}

	public function video_field() {
		return 'edd_video';
	}

	public function demo_field() {
		return 'edd_demo';
	}

}
