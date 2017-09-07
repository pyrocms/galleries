<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Galleries Plugin
 *
 * Create a list of images
 *
 * @package		PyroCMS
 * @author		Jerel Unruh - PyroCMS Dev Team
 * @copyright	Copyright (c) 2008 - 2010, PyroCMS
 *
 */
class Plugin_Galleries extends Plugin
{

	public $version = '1.3';
	public $name = array(
		'en' => 'Gallery',
	);
	public $description = array(
		'en' => 'A plugin to display Gallery posts and images.'
	);

	/**
	 * Returns a PluginDoc array
	 *
	 * @return array
	 */
	public function _self_doc()
	{

		$info = array(
			'images' => array(
				'description' => array(
					'en' => 'List images in gallery.'
				),
				'single' => false,// single tag or double tag (tag pair)
				'double' => true,
				'variables' => 'slug|offset|limit',// the variables available inside the double tags
				'attributes' => array(// an array of all attributes
					'slug' => array(// the attribute name. If the attribute name is used give most common values as separate attributes
						'type' => 'slug',// Can be: slug, number, flag, text, any. A flag is a predefined value.
						'flags' => '',// valid flag values that the plugin will recognize. IE: asc|desc|random
						'default' => '',// the value that it defaults to
						'required' => false,// is this attribute required?
						),
					'limit' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => false,
						),
					'offset' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '0',
						'required' => false,
						),
					),
				),
			'exists' => array(
				'description' => array(
					'en' => 'Check if specified gallery exists.'
				),
				'single' => true,
				'double' => false,
				'variables' => 'slug',
				'attributes' => array(
					'slug' => array(
						'type' => 'slug',
						'flags' => '',
						'default' => '',
						'required' => false,
						),
					),
				),
			'listing' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Display list of gallery optionally filtering them by slug.'
				),
				'single' => false,// single tag or double tag (tag pair)
				'double' => true,
				'variables' => 'slug|offset|limit',// the variables available inside the double tags
				'attributes' => array(// an array of all attributes
					'slug' => array(// the attribute name. If the attribute name is used give most common values as separate attributes
						'type' => 'slug',// Can be: slug, number, flag, text, any. A flag is a predefined value.
						'flags' => '',// valid flag values that the plugin will recognize. IE: asc|desc|random
						'default' => '',// the value that it defaults to
						'required' => false,// is this attribute required?
						),
					'limit' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => false,
						),
					'offset' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '0',
						'required' => false,
						),
					),
				),
			);

		return $info;
	}

	/**
	 * Image List
	 * 
	 * Creates a list of gallery images
	 * 
	 * Usage:
	 * 
	 * {{ galleries:images slug="nature" limit="5" }}
	 * 	<a href="{{ url:base }}galleries/{{ gallery_slug }}/{{ id }}" title="{{ name }}">
	 * 		<img src="{{ url:site }}files/thumb/{{ file_id }}/75/75" alt="{{ description }}"/>
	 * 	</a>
	 * {{ /galleries:images }}
	 * 
	 * The following is a list of tags that are available to use from this method
	 * 
	 * 	{{ file_id }}
	 * 	{{ folder_id }}
	 * 	{{ gallery_id }}
	 * 	{{ gallery_slug }}
	 * 	{{ title }}
	 * 	{{ order }}
	 * 	{{ name }}
	 * 	{{ filename }}
	 * 	{{ description }}
	 * 	{{ extension }}
	 * 
	 * @return	array
	 */
	function images()
	{
		$limit	= $this->attribute('limit');
		$slug	= $this->attribute('slug');
		$offset = $this->attribute('offset');
		
		$this->load->model(array(
			'gallery_m',
			'gallery_image_m'
		));

		$gallery = $this->gallery_m->get_by('slug', $slug);

		return $gallery ? $this->gallery_image_m->get_images_by_gallery($gallery->id, array(
			'limit' => $limit,
			'offset' => $offset
		)) : array();
	}

	/**
	 * Gallery exists
	 * 
	 * Check if a gallery exists
	 * 
	 * @return	int 0 or 1
	 */
	function exists()
	{
		$slug = $this->attribute('slug');

		$this->load->model('gallery_m');

		return (int) ($slug ? $this->gallery_m->count_by('slug', $slug) > 0 : FALSE);
	}
	
	
	/**
	 * Gallery List
	 * 
	 * Creates a list of galleries
	 * 
	 * Usage:
	 * 
	 * {{ galleries:listing slug="nature" limit="5" }}
	 * 	<a href="{{ url }}" title="{{ title }}">
	 * 		<img src="{{ thumbnail }}" alt="{{ description }}" align="left"/> {{ title }}
	 * 	</a>
	 * {{ /galleries:listing }}
	 * 
	 * The following is a list of tags that are available to use from this method
	 * 
	 * 	{{ id }}
	 * 	{{ title }}
	 * 	{{ slug }}
	 * 	{{ folder_id }}
	 * 	{{ description }}
	 * 	{{ folder_slug }}
	 * 	{{ folder_name }}
	 * 	{{ photo_count }}
	 * 	{{ thumbnail }}
	 * 	{{ url }}
	 * 
	 * @return	array
	 */
	function listing()
	{
		$limit	= $this->attribute('limit');
		$slug	= $this->attribute('slug');
		$offset = $this->attribute('offset');
		
		$this->load->model(array(
			'gallery_m'
		));

		$options = array(
			"slug" => $slug,
			"limit" => $limit,
			"offset" => $offset
		);
		$galleries = $this->gallery_m->get_all_by_slug($options);
		
		return $galleries ? $galleries : array();
	}
}

/* End of file plugin.php */