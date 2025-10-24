<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Uploader extends CI_Controller {
	
	/* Constructor */
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('jbimages','language'));
		
		// is_allowed is a helper function which is supposed to return False if upload operation is forbidden
		// [See jbimages/is_alllowed.php] 
		
		if (is_allowed() === FALSE)
		{
			exit;
		}
		
		// User configured settings
		$this->config->load('uploader_settings', TRUE);
	}
	
	/* Language set */
	
	private function _lang_set($lang)
	{
		// We accept any language set as lang_id in **_dlg.js
		// Therefore an error will occur if language file doesn't exist
		
		$this->config->set_item('language', $lang);
		$this->lang->load('jbstrings', $lang);
	}
	
	/* Default upload routine */
		
	public function upload ($lang='english')
	{
		// Set language
		$this->_lang_set($lang);
		
		// Get configuartion data (we fill up 2 arrays - $config and $conf)
		
		$conf['img_path']			= $this->config->item('img_path',		'uploader_settings');
		$conf['allow_resize']		= $this->config->item('allow_resize',	'uploader_settings');
		
		$config['allowed_types']	= $this->config->item('allowed_types',	'uploader_settings');
		$config['max_size']			= $this->config->item('max_size',		'uploader_settings');
		$config['encrypt_name']		= $this->config->item('encrypt_name',	'uploader_settings');
		$config['overwrite']		= $this->config->item('overwrite',		'uploader_settings');
		$config['upload_path']		= $this->config->item('upload_path',	'uploader_settings');
		
		if (!$conf['allow_resize'])
		{
			$config['max_width']	= $this->config->item('max_width',		'uploader_settings');
			$config['max_height']	= $this->config->item('max_height',		'uploader_settings');
		}
		else
		{
			$conf['max_width']		= $this->config->item('max_width',		'uploader_settings');
			$conf['max_height']		= $this->config->item('max_height',		'uploader_settings');
			
			if ($conf['max_width'] == 0 and $conf['max_height'] == 0)
			{
				$conf['allow_resize'] = FALSE;
			}
		}
		
		// Load uploader
		$this->load->library('upload', $config);
		
		if ($this->upload->do_upload()) // Success
		{
			// General result data
			$result = $this->upload->data();
			
			// Shall we resize an image?
			if ($conf['allow_resize'] and $conf['max_width'] > 0 and $conf['max_height'] > 0 and (($result['image_width'] > $conf['max_width']) or ($result['image_height'] > $conf['max_height'])))
			{				
				// Resizing parameters
				$resizeParams = array
				(
					'source_image'	=> $result['full_path'],
					'new_image'		=> $result['full_path'],
					'width'			=> $conf['max_width'],
					'height'		=> $conf['max_height']
				);
				
				// Load resize library
				$this->load->library('image_lib', $resizeParams);
				
				// Do resize
				$this->image_lib->resize();
			}
			
			// Add our stuff
			$result['result']		= "file_uploaded";
			$result['resultcode']	= 'ok';
			$result['file_name']	= $conf['img_path'] . '/' . $result['file_name'];
			
			// custom eow resizing

			$path = $_SERVER['DOCUMENT_ROOT'];
			include_once($path . "/proshop/includes/secure.php");
			include_once($path . "/proshop/includes/functions.php");
			include_once($path . "/proshop/includes/connect.php");
			include_once($path . "/proshop/classes/resize-class.php");

			$save_path = getOption("imagePathFront");
			$physical_path = getOption("physicalPath");
			
			$filename = str_replace($save_path, "", $result['file_name']);
			
			$imgSize = getimagesize($physical_path . $save_path . $filename);
			$imgWidth = $imgSize[0];
			$imgHeight = $imgSize[1];
			
			if ($imgWidth > $imgHeight) {
				$orientation = "landscape";
				} elseif ($imgWidth < $imgHeight) {
					$orientation = "portrait";
					} else {
						$orientation = "square";
			}
			
			if (!file_exists($physical_path . $save_path . "square/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(200, 200, 'crop');

				$resizeObj -> saveImage($physical_path.$save_path."square/" . $filename, 100);
			}
			
			if (!file_exists($physical_path . $save_path . "500/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(500, 0, 'landscape');

				$resizeObj -> saveImage($physical_path.$save_path."500/" . $filename, 100);
			}
			
			if (!file_exists($physical_path . $save_path . "750/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(750, 0, 'landscape');

				$resizeObj -> saveImage($physical_path.$save_path."750/" . $filename, 100);
			}
			
			if (!file_exists($physical_path . $save_path . "1000/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(1000, 0, 'landscape');

				$resizeObj -> saveImage($physical_path.$save_path."1000/" . $filename, 100);
			}
			
			if (!file_exists($physical_path . $save_path . "1536/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(1536, 0, 'landscape');

				$resizeObj -> saveImage($physical_path.$save_path."1536/" . $filename, 100);
			}
			
			if (!file_exists($physical_path . $save_path . "1920/".$filename)) {
				$resizeObj = new resize($physical_path.$save_path.$filename);

				$resizeObj -> resizeImage(1920, 0, 'landscape');

				$resizeObj -> saveImage($physical_path.$save_path."1920/" . $filename, 100);
			}
			
			// CREATE GRAYSCALE PNG FOR SCOLL PLACEHOLDER
			$strPicName = $filename;
			$strPicName = str_replace(".jpg", "", $strPicName);
			$strPicName = str_replace(".jpeg", "", $strPicName);
			$strPicName = str_replace(".gif", "", $strPicName);
			$strPicName = str_replace(".png", "", $strPicName);

			$im = imagecreatetruecolor($imgWidth, $imgHeight);
			$gray = imagecolorallocate($im, 0, 0, 0);

			imagecolortransparent($im, $gray);

			imagepng($im, $physical_path . $save_path . "siteGray/" . $strPicName . ".png");

			imagedestroy($im);
			
			// Output to user
			$this->load->view('ajax_upload_result', $result);
		}
		else // Failure
		{
			// Compile data for output
			$result['result']		= $this->upload->display_errors(' ', ' ');
			$result['resultcode']	= 'failed';
			
			// Output to user
			$this->load->view('ajax_upload_result', $result);
		}
	}
	
	/* Blank Page (default source for iframe) */
	
	public function blank($lang='english')
	{
		$this->_lang_set($lang);
		$this->load->view('blank');
	}
	
	public function index($lang='english')
	{
		$this->blank($lang);
	}
}

/* End of file uploader.php */
/* Location: ./application/controllers/uploader.php */
