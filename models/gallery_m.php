<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * The galleries module enables users to create albums, upload photos and manage their existing albums.
 *
 * @author 		Yorick Peterse - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Gallery Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Gallery_m extends MY_Model {

	public function tot_galleries(){

		return $this->db->count_all_results('galleries');
	}

	/**
	 * Get all galleries along with the total number of photos in each gallery
	 *
	 * @author Yorick Peterse - PyroCMS Dev Team
	 * @access public
	 * @return mixed
	 */
	public function get_all()
	{
		$this->db
			->select('galleries.*, file_folders.slug as folder_slug, file_folders.name as folder_name')
			->join('file_folders', 'file_folders.id = galleries.folder_id', 'left');

		$galleries	= parent::get_all();
		$results	= array();

		// Loop through each gallery and add the count of photos to the results
		foreach ($galleries as $gallery)
		{
			$count = $this->db
				->select('files.id')
				->join('galleries', 'galleries.folder_id = files.folder_id', 'left')
				->where('files.type', 'i')
				->where('galleries.id', $gallery->id)
				->count_all_results('files');

			$gallery->photo_count = $count;
			$results[] = $gallery;
		}

		// Return the results
		return $results;
	}

	/**
	 * Get all galleries along with the thumbnail's filename and extension
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_all_with_filename($where = NULL, $value = NULL, $num = NULL, $offset = NULL)
	{
		$this->db
			->select('galleries.*, files.filename, files.extension, files.id as file_id, file_folders.parent_id as parent')

			->join('gallery_images', 'gallery_images.file_id = galleries.thumbnail_id', 'left')
			->join('files', 'files.id = gallery_images.file_id', 'left')
			->join('file_folders', 'file_folders.id = galleries.folder_id', 'left')
			->where('galleries.published', '1');

		// Where clause provided?
		if ( ! empty($where) AND ! empty($value))
		{
			$this->db->where($where, $value);
		}

		if( $num!=NULL or $offset!=NULL)
		{
			return $this->db->get('galleries',$num,$offset)->result();
		}

		return $this->db->get('galleries')->result();
	}

	/**
	 * Insert a new gallery into the database
	 *
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param array $data The data to insert (a copy of $_POST)
	 * @return bool
	 */
	public function insert($data, $skip_validation = false)
	{
		if (is_array($data['folder_id']))
		{
			$folder = $data['folder_id'];

			$data['folder_id'] = $this->file_folders_m->insert(array(
				'name'			=> $folder['name'],
				'parent_id'		=> 0,
				'slug'			=> $folder['slug'],
				'date_added'	=> now()
			));
		}

		return (int) parent::insert(array(
			'title'				=> $data['title'],
			'slug'				=> $data['slug'],
			'folder_id'			=> $data['folder_id'],
			'thumbnail_id'		=> ! empty($data['gallery_thumbnail']) ? $data['gallery_thumbnail'] : NULL,
			'description'		=> $data['description'],
			'enable_comments'	=> $data['enable_comments'],
			'published'			=> $data['published'],
			'updated_on'		=> time(),
			'css'				=> $data['css'],
			'js'				=> $data['js']
		));
	}

	/**
	 * Update an existing gallery
	 *
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param int $id The ID of the row to update
	 * @param array $data The data to use for updating the DB record
	 * @return bool
	 */
	public function update($primary_value, $data, $skip_validation = false)
	{
        return parent::update($id, array(
			'title'				=> $data['title'],
			'slug'				=> $data['slug'],
			'folder_id'			=> $data['folder_id'],
			'description'		=> $data['description'],
			'enable_comments'	=> $data['enable_comments'],
			'thumbnail_id'		=> ! empty($data['gallery_thumbnail']) ? $data['gallery_thumbnail'] : NULL,
			'published'			=> $data['published'],
			'updated_on'		=> time(),
			'css'				=> $data['css'],
			'js'				=> $data['js']
		));
	}

	/**
	 * Callback method for validating the slug
	 * @access public
	 * @param string $slug The slug to validate
	 * @param int $id The id of gallery
	 * @return bool
	 */
	public function check_slug($slug = '', $id = 0)
	{
		return parent::count_by(array(
			'id !='	=> $id,
			'slug'	=> $slug)
		) > 0;
	}

}