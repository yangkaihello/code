<?php
namespace getbook;

use getbook\Curl;

use Exception; 

class GetBook { 
	protected $config;	//配置集
	protected $obje;	//储存的对象集

	public function config( array $url)
	{
		$this->config = $url;
	}

	public function info() : array
	{
		$curl = $this->getObje('Curl');	
		
		try {
			$param 	= $this->paramUrl();		//获取get参数
			$url 	= $this->urlJoin('info',$param);
			$data = $curl->getInfo($url);	
		} catch (Exception $e) {
			
			die( 
				'file:' . $e->getFile() . '<br />' .
				'line:' . $e->getLine() . '<br />' .
				'error:' .$e->getMessage() . " ----> code:" . $e->getCode() 
			);
		}
		return $data;
	}

	//获取书籍信息
	public function book(int $id)
	{
		$curl = $this->getObje('Curl');	
		try {
			$param 	= $this->paramUrl('bookid='.$id);		//获取get参数
			$url 	= $this->urlJoin('book',$param);
			$data = $curl->getBook($url);	
		} catch (Exception $e) {
			die( 
				'file:' . $e->getFile() . '<br />' .
				'line:' . $e->getLine() . '<br />' .
				'error:' .$e->getMessage() . " ----> code:" . $e->getCode() 
			);
		}
		return $data;
	}


	//获取章节信息
	public function chapter(int $bookid,int $id)
	{
		$curl = $this->getObje('Curl');	
		try {
			$param 	= $this->paramUrl('bookid=' . $bookid .'&chapterid='.$id);		//获取get参数
			$url 	= $this->urlJoin('chapter',$param);
			
			$data = $curl->getChapter($url);	
		} catch (Exception $e) {
			die( 
				'file:' . $e->getFile() . '<br />' .
				'line:' . $e->getLine() . '<br />' .
				'error:' .$e->getMessage() . " ----> code:" . $e->getCode() 
			);
		}
		return $data;
	}

	//获取章节信息
	public function chapterInfo(int $id)
	{
		$curl = $this->getObje('Curl');	
		try {
			$param 	= $this->paramUrl('bookid='.$id);		//获取get参数
			$url 	= $this->urlJoin('chapterInfo',$param);
			
			$data = $curl->getChapter($url);	
		} catch (Exception $e) {
			die( 
				'file:' . $e->getFile() . '<br />' .
				'line:' . $e->getLine() . '<br />' .
				'error:' .$e->getMessage() . " ----> code:" . $e->getCode() 
			);
		}
		return $data;
	}


	//完整的url拼接
	protected function urlJoin(string $api,string $param) : string
	{
		if( !isset($this->config[$api]) ){
			throw new Exception("缺少 " . $api . " 接口类型", 415);
		}
		return $this->config[$api] . "&" . $param;
	}


	//get数据拼接
	protected function paramUrl($param = '')
	{
		if( !isset($this->config['apikey']) ){
			throw new Exception("缺少 apikey", 415);
		}

		if( !isset($this->config['apikey']) ){
			throw new Exception("缺少 spid", 415);
		}

		if(!empty($param)){
			$param = '&' . $param;
		}
		return 'apikey=' . $this->config['apikey'] . '&spid=' . $this->config['spid'] . $param;
	}
	//获取对象
	protected function getObje($obje){
		if(!isset($this->obje[$obje])){
			$this->obje[$obje] = call_user_func(['getbook\\' . $obje, 'create']); 
		}
		return $this->obje[$obje];
	}
}
?>