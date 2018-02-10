<?php session_start();
	//error_reporting(0);
	//include("formeu/config/confg.php");
	//include("session_check.php");
	//user_id = $_SESSION["user_id"];
	/**
	 * Class CropAvatar
	 */
	class CropAvatar {
		private $src;
		private $data;
		private $dst;
		private $type;
		private $extension;
		private $msg;
		
		/**
		 * CropAvatar constructor.
		 *
		 * @param $src
		 * @param $data
		 * @param $file
		 * @param $destination
		 */
		function __construct($src, $data, $file, $destination) {
			$this->setSrc($src);
			$this->setData($data);
			$this->setFile($file);
			$this->dst = $destination;
			$this->crop($this->src, $this->dst, $this->data);
		}
		
		/**
		 * @param $src
		 */
		private function setSrc($src) {
			if (!empty($src)) {
				$type = exif_imagetype($src);
				if ($type) {
					$this->src       = $src;
					$this->type      = $type;
					$this->extension = image_type_to_extension($type);
				}
			}
		}
		
		/**
		 * @param $data
		 */
		private function setData($data) {
			if (!empty($data)) {
				$this->data = json_decode(stripslashes($data));
			}
		}
		
		/**
		 * @param $file
		 */
		private function setFile($file) {
			$type = exif_imagetype($file);
			if ($type) {
				$extension = image_type_to_extension($type);
				$src       = $file;
				if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
					/*if (file_exists($src)) {
					  unlink($src);
					}*/
					//$result = move_uploaded_file($file, $src);
					$this->src       = $src;
					$this->type      = $type;
					$this->extension = $extension;
				} else {
					$this->msg = 'Please upload image with the following types: JPG, PNG, GIF';
				}
			} else {
				$this->msg = 'Please upload image file';
			}
		}
		
		/**
		 * @param $src
		 * @param $dst
		 * @param $data
		 */
		private function crop($src, $dst, $data) {
			if (!empty($src) && !empty($dst) && !empty($data)) {
				switch ($this->type) {
					case IMAGETYPE_GIF:
						$src_img = imagecreatefromgif($src);
						break;
					case IMAGETYPE_JPEG:
						$src_img = imagecreatefromjpeg($src);
						break;
					case IMAGETYPE_PNG:
						$src_img = imagecreatefrompng($src);
						break;
				}
				if (!$src_img) {
					$this->msg = "Failed to read the image file";
					
					return;
				}
				$size      = getimagesize($src);
				$size_w    = $size[0]; // natural width
				$size_h    = $size[1]; // natural height
				$src_img_w = $size_w;
				$src_img_h = $size_h;
				$degrees   = $data->rotate;
				// Rotate the source image
				if (is_numeric($degrees) && $degrees != 0) {
					// PHP's degrees is opposite to CSS's degrees
					$new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
					imagedestroy($src_img);
					$src_img   = $new_img;
					$deg       = abs($degrees) % 180;
					$arc       = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
					$src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
					$src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
					// Fix rotated image miss 1px issue when degrees < 0
					$src_img_w -= 1;
					$src_img_h -= 1;
				}
				$tmp_img_w = $data->width;
				$tmp_img_h = $data->height;
				$dst_img_w = 436; // Custom Width
				$dst_img_h = 580; // Custom Height
				$src_x     = $data->x;
				$src_y     = $data->y;
				if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
					$src_x = $src_w = $dst_x = $dst_w = 0;
				} else if ($src_x <= 0) {
					$dst_x = -$src_x;
					$src_x = 0;
					$src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
				} else if ($src_x <= $src_img_w) {
					$dst_x = 0;
					$src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
				}
				if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
					$src_y = $src_h = $dst_y = $dst_h = 0;
				} else if ($src_y <= 0) {
					$dst_y = -$src_y;
					$src_y = 0;
					$src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
				} else if ($src_y <= $src_img_h) {
					$dst_y = 0;
					$src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
				}
				// Scale to destination position and size
				$ratio = $tmp_img_w / $dst_img_w;
				$dst_x /= $ratio;
				$dst_y /= $ratio;
				$dst_w /= $ratio;
				$dst_h /= $ratio;
				$dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
				// Add transparent background to destination image
				imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
				imagesavealpha($dst_img, true);
				$result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				if ($result) {
					if (!imagepng($dst_img, $dst)) {
						$this->msg = "Failed to save the cropped image file";
					}
				} else {
					$this->msg = "Failed to crop the image file";
				}
				imagedestroy($src_img);
				imagedestroy($dst_img);
			}
		}
		
		/**
		 * @param $code
		 *
		 * @return mixed|string
		 */
		/** @noinspection PhpUnusedPrivateMethodInspection
		 * @param $code
		 *
		 * @return mixed|string
		 */
		private function codeToMessage($code) {
			$errors = array(
				UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
				UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
				UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded',
				UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
				UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
				UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
				UPLOAD_ERR_EXTENSION  => 'File upload stopped by extension',
			);
			if (array_key_exists($code, $errors)) {
				return $errors[$code];
			}
			
			return 'Unknown upload error';
		}
		
		public function getResult() {
			return !empty($this->data) ? $this->dst : $this->src;
		}
		
		public function getMsg() {
			return $this->msg;
		}
	}
	/**
	 * @param $contents
	 * @param $uploadPath
	 *
	 * @return bool
	 */
	function saveTempImage($contents, $uploadPath) {
		//You can use your imagination and store the images on the server using your own naming logic, we use the following:
		$res = file_put_contents($uploadPath, base64_decode($contents));
		if ($res === false)
			return false;
		//Validate that it's a JPEG image
		$im = @imagecreatefromjpeg($uploadPath);
		if ($im)
			return $uploadPath;
		//Delete the image if it's not JPEG
		@unlink($uploadPath);
		
		return false;
	}
	
	$filedata = preg_split('/,/', $_POST['filedata']);
	// upload path of the original photo
	$ds         = DIRECTORY_SEPARATOR;
	$file_name  = uniqid() . '.jpg';
	$uploadPath = __DIR__ . "{$ds}photo{$ds}" . $file_name;
	$max_size   = 1048576 * 8;
	if ($_POST['filesize'] > $max_size) {
		$response = array(
			'state'   => 501,
			'message' => 'Please select file with less then 8MB',
			'result'  => '',
		);
		echo json_encode($response);
		exit;
	}
	if ($filename = saveTempImage($filedata[1], $uploadPath)) {
		// thumbnail path
		$dir_sag       = 'photo/compressed/';
		$img_file_sag  = uniqid() . '.jpg';
		$thumbnail     = $dir_sag . $img_file_sag;
		$crop          = new CropAvatar(
			null,
			isset($_POST['img_data']) ? $_POST['img_data'] : null,
			$filename,
			$thumbnail
		);
		//$user_id_pro   = $_SESSION['user_id'];
//		$pro_pic_upd   = $img_file_sag;
//		$query_new     = mysqli_query($con, "SELECT * FROM `pro_pic` where user_id = '$user_id' ");
//		$rows_new      = mysqli_fetch_assoc($query_new);
//		$rows_new_find = mysqli_num_rows($query_new);
//		if ($rows_new_find == 0) {
//			$pro_pic_update_q = mysqli_query($con, "INSERT INTO `pro_pic` (`id`, `user_id`, `pro_pic`, `ver_sta` ) VALUES (NULL, '$user_id', '$pro_pic_upd', '1')");
//		} else {
//			$pro_pic_update_q_new = mysqli_query($con, "UPDATE pro_pic SET pro_pic = '$pro_pic_upd',ver_sta = '1' WHERE user_id = '$user_id'");
//		}
//		mysqli_query($con, "UPDATE user_info SET pro_pic_upload = '0' WHERE user_id = '$user_id'");
//		mysqli_query($con, "delete from pro_pic_del_alert where user_id = '$user_id'");
		$response = array(
			'state'   => 200,
			'message' => $crop->getMsg(),
			'result'  => $crop->getResult(),
		);
	} else {
		$response = array(
			'state'   => 500,
			'message' => 'Unable to save image!',
			'result'  => '',
		);
	}
	$src = $thumbnail;
	$des = 'photo/' . $img_file_sag;
	compress($src, $des, 75);
	unlink($uploadPath);
	unlink($thumbnail);
	echo json_encode($response);
	/**
	 * @param $source
	 * @param $destination
	 * @param $quality
	 *
	 * @return string
	 */
	function compress($source, $destination, $quality) {
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);
		if (file_exists($destination)) {
			unlink($destination);
		}
		imagejpeg($image, $destination, $quality);
		$file_size = filesize($destination);
		$bytes     = number_format($file_size / 1024, 2);
		if ($bytes > 50) {
			compress($source, $destination, $quality - 5);
		}
	}