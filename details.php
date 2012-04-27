<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Galleries extends Module {

	public $version = '2.0';

	public function info()
	{
		return array(
			'name' => array(
				'sl' => 'Galerija',
				'en' => 'Galleries',
				'el' => 'Γκαλερί',
				'de' => 'Galerien',
				'pl' => 'Galerie',
				'nl' => 'Gallerijen',
				'fr' => 'Galeries',
				'zh' => '畫廊',
				'it' => 'Gallerie',
				'ru' => 'Галереи',
				'ar' => 'معارض الصّور',
				'br' => 'Galerias',
				'cs' => 'Galerie',
				'es' => 'Galerías',
				'fi' => 'Galleriat',
				'lt' => 'Galerijos'
			),
			'description' => array(
				'sl' => 'Modul galerije vam omogoča da ustvarite albume vaših slik.',
				'en' => 'The galleries module is a simple module that lets users display images from Files.',
				'el' => 'Παρέχει την δυνατότητα στους χρήστες να δημιουργούν άλμπουμ εικόνων.',
				'de' => 'Mit dem Galerie Modul kannst du Bildergalerien anlegen.',
				'pl' => 'Moduł pozwalający tworzyć galerie zdjęć.',
				'nl' => 'De gallerij module die gebruikers in staat stelt afbeeldingsgallerijen te maken.',
				'fr' => 'Galerie est une puissante extension permettant de créer des galeries d\'images.',
				'zh' => '這是一個功能完整的畫廊模組，可以讓用戶建立自己的畫本或相簿。',
				'it' => 'Il modulo gallerie è un potente modulo che permette agli utenti di creare gallerie di immagini.',
				'ru' => 'Галереи - мощный модуль, который даёт пользователям возможность создавать галереи изображений.',
				'ar' => 'هذه الوحدة تمُكّنك من إنشاء معارض الصّور بسهولة.',
				'br' => 'O módulo de galerias é um poderoso módulo que permite aos usuários criar galerias de imagens.',
				'cs' => 'Silný modul pro vytváření a správu galerií obrázků.',
				'es' => 'Galerías es un potente módulo que permite a los usuarios crear galerías de imágenes.',
				'fi' => 'Galleria moduuli antaa käyttäjien luoda kuva gallerioita.',
				'lt' => 'Galerijos modulis leidžia vartotojams kurti nuotraukų galerijas'
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'content',

		    'shortcuts' => array(
				array(
			 	   'name' => 'galleries.new_gallery_label',
				   'uri' => 'admin/galleries/create',
				   'class' => 'add'
				),
			),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('galleries');
		$this->dbforge->drop_table('gallery_images');

        $tables = array(
            'galleries' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'slug' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true, 'key' => true),
                'title' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
                'folder_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
                'thumbnail_id' => array('type' => 'INT', 'constraint' => 11, 'default' => null, 'unique' => true, 'key' => true),
                'description' => array('type' => 'TEXT'),
                'updated_on' => array('type' => 'INT', 'constraint' => 15, 'null' => false),
                'preview' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => null),
                'enable_comments' => array('type' => 'INT', 'constraint' => 1, 'default' => null),
                'published' => array('type' => 'INT', 'constraint' => 1, 'default' => null),
                'css' => array('type' => 'TEXT', 'null' => true),
                'js' => array('type' => 'TEXT', 'null' => true),
            ),
            'gallery_images' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'file_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false),
                'gallery_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
                'order' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
            ),
        );

        if ( ! $this->install_tables($tables))
        {
            return false;
        }

        return true;
	}

	public function uninstall()
	{
		if($this->dbforge->drop_table('galleries') &&
		   $this->dbforge->drop_table('gallery_images'))
		{
			return TRUE;
		}
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "<h4>Overview</h4>
		<p>The galleries module is a basic photo management tool. Features include drag & drop sorting and sub galleries.</p>
		<h4>Creating Galleries</h4>
		<p>To create a gallery go to Content->Files and create a new folder. Come back to Galleries and click \"Create a new gallery\" and select the folder of images that you just created in the File manager.
		Fill out the title, slug, and the (optional) Description. (The Description shows
		beside the gallery thumbnail at http://example.com/galleries). Choose whether you want to enable comments for this gallery or not
		and select Publish if you wish for the gallery to show in the list of galleries. Note: selecting Unpublish does not disable the gallery, it just
		removes it from the list at http://example.com/galleries. You can still create a navigation link directly to it and the
		gallery will be viewable. For example: http://example.com/galleries/gallery-title</p>
		<h4>Uploading Images</h4>
		<p>For instructions on how to upload images refer to the Files documentation.</p>
		<h4>Manage Gallery</h4>
		<p>Click on List Galleries->Manage. Here you may change the gallery's title, slug, description, etc. If you want a thumbnail to represent this
		gallery in the gallery list you may choose one from the dropdown and click Save. To change the order that the images are displayed in on the front-end
		simply grab the images and drag them into the proper order.</p>
		<h4>Editing an Image</h4>
		<p>From the Manage page click on the image that you would like to edit. A modal window will appear and you may change
		the title and the description of the image. You may also move the image to a different folder.</p>";
	}
}
/* End of file details.php */