<?php
namespace getbook;

use Exception;

class Curl {
	public $result; 

	public static function create(){
		return new Curl;
	}

	public function getInfo(string $url) : array
	{
		if(!isset( $url )){
			throw new Exception("缺少info接口地址", 415);
		}
		return $this->getRes($url);
	}

	public function getBook(string $url) : array
	{
		if(!isset( $url )){
			throw new Exception("缺少book接口地址", 415);
		}
		return $this->getRes($url);
	}

	public function getChapter(string $url) : array
	{
		if(!isset( $url )){
			throw new Exception("缺少chapter接口地址", 415);
		}
		return $this->getRes($url);
		
	}

	public function getChapterInfo(string $url) : array
	{
		if(!isset( $url )){
			throw new Exception("缺少chapterInfo接口地址", 415);
		}
		return $this->getRes($url);
		
	}


	public function getResource($url)
	{
		$curl = curl_init($url);
		curl_setopt_array($curl,[
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_TIMEOUT => 10,
		]);
		$output = curl_exec($curl);
		curl_close($curl);

		$fileName 	= md5(microtime(true)) . '.' . pathinfo($url)['extension'];
		$autoPath 	= date('Ymd');
		$uploadPath = 'uploads' . DS . 'book' . DS . 'cover' . DS . $autoPath ;
		$path 		= ROOT_PATH . $uploadPath;

		$retPath 	= $autoPath . DS . $fileName;

		if(!is_dir($path)) @mkdir($path, 0777);
		file_put_contents($path . DS . $fileName, $output);
		$this->imageCompress($path . DS . $fileName);
		
		return $retPath;
	}

	//图片压缩
	protected function imageCompress($filePath)
	{
		list($width,$height,$suffix) = getimagesize($filePath);

		$image_p = imagecreatetruecolor($width, $height);

		switch($suffix){
			case 1: $image = imagecreatefromgif($filePath); break;
			case 2: $image = imagecreatefromjpeg($filePath);break;
			case 3: $image = imagecreatefrompng($filePath); break;
		}
		
		imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

		switch($suffix){
			case 1: imagegif($image_p,$filePath); break;
			case 2: imagejpeg($image_p,$filePath,50);break;
			case 3: imagepng($image_p,$filePath); break;
		}

	}

	/**
	 *	curl 请求
	 *	@param url 请求http链接
	 *	@return 返回数组集
	 */
	protected function curl( string $url) : array
	{
		$curl = curl_init($url);
		curl_setopt_array($curl,[
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_TIMEOUT => 10,
		]);
		$output = curl_exec($curl);
		$header = curl_getinfo($curl);
		if(strpos($header['content_type'],'application/json') === false){
			throw new Exception("接口存在问题", 404);
		}
		curl_close($curl);
		if( $output )
		{
			return json_decode($output,true);	
		}else{
			return [];
		}
		
	}
	protected function getRes($url)
	{	
		$data = $this->curl($url);

		if($data['code'] != 200){
			throw new Exception($data['msg'], $data['code']);
		}	//捕获所有不是200状态的报错信息
		return $data;
	}
	protected function xmlParse( string $xml ) : array
	{
		$xml = simplexml_load_string($xml); 
		return json_decode(json_encode($xml),true);
	}
}
?>