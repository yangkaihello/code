<?php
namespace access;

use access\database;
use access\sql;
use access\config;

header("Content-type: text/html; charset=utf-8"); 
include __DIR__ . DIRECTORY_SEPARATOR . 'access/autoload.php';
spl_autoload_register(array('Autoload', 'classLoader')); 		//自动加载
set_time_limit(0);				//php 执行无时间限制
ignore_user_abort(true);		//客户端执行
error_reporting(E_ALL);			//显示报错代码
ini_set("display_errors", 1);  //显示报错代码
/**
 * sitemap 的使用类
 */
class sitemap{
	/*
		$sitemap = [
				'priority' 	=> 1,
				'changefreq'	=> 'daily', //有效值为：always、hourly、daily、weekly、monthly、yearly、never
				'sitemappath' => __DIR__ . '/sitemap/', // sitemap 存放目录
				'sitemaphost' => 'https://www.3234.com/', // sitemap 提交url
				'indexes' => __DIR__,  //sitemap 索引存放目录 网站根目录
			];
	*/ //sitemap 配置样本
	public $sitemap = array();
	
	public function __construct(array $config){		
		
		$this->sitemap['priority'] 		= isset($config['priority']) ? $config['priority'] : 1;
		$this->sitemap['changefreq'] 	= isset($config['changefreq']) ? $config['changefreq'] : 'daily';
		$this->sitemap['sitemaphost'] 	= isset($config['sitemaphost']) ? $config['sitemaphost'] : die("sitemap 索引提交域名 为填写");
		$this->sitemap['sitemappath'] 	= isset($config['sitemappath']) ? $config['sitemappath'] : die("sitemap 存放目录为填写");
		$this->sitemap['indexes'] 		= isset($config['indexes']) ? $config['indexes'] : die("sitemap 索引存放目录为填写");
		
	}
	
	
	/**
	 * 用作数据搜索
	 * @param data 需要一个二维数组 只需要提供url 键值需要提供文件名
	 * @param indexes 是否生成索引文件
	 * @return 二维数组
	 */
	
	public function sitemapXml(array $data,$indexes = true){
		$path = $this->sitemap['sitemappath'];
		if(!file_exists($path)) die('请检测是否存在文件夹');
		$path = $this->pathJud($path);

		$xml = '';
		foreach($data as $fileName=>$val){
			foreach($val as $v){
				$xml .= '<url>';
				$xml .= '<loc>'.$v['url'].'</loc>';
				$xml .= '<priority>'.$this->sitemap['priority'].'</priority>';
				$xml .= '<changefreq>'.$this->sitemap['changefreq'].'</changefreq>';
				$xml .= '</url>';
			}
		}
		$str = '<?xml version="1.0" encoding="utf-8"?><urlset>'.$xml.'</urlset>';
		file_put_contents($path.$fileName, $str);
		unset($str,$data,$xml);
		
		if($indexes == true){
			$this->sitemapIndexesXml();
		}
		return true;
	}
	
	public function sitemapIndexesXml(){
		$indexesPath = $this->pathJud($this->sitemap['indexes']);
		$sitemapPath = $this->sitemap['sitemappath'];
	
		//第3步 生成索引xml
		$sitemapFile = scandir($sitemapPath);
		if(!empty($sitemapFile)){
			$now = date("Y-m-d",time());
			$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= '<sitemapindex>';
			foreach ($sitemapFile as $v){
				if($v == '.' || $v == '..') continue;
			    $xml .= '<sitemap>';
			    $xml .= '<loc>'.$this->sitemap['sitemaphost'].$v.'</loc>';
			    $xml .= '<lastmod>'.$now.'</lastmod>';
			    $xml .= '</sitemap>';
			}
			$xml .= '</sitemapindex>';
			file_put_contents($indexesPath . 'sitemap.xml', $xml);
		}
		unset($sitemapFile,$xml);
	}
	
	private function pathJud($path){
		
		if (strpos($path, '/') !== false)
		{
			 $basename = implode('/',array_filter(explode('/', $path))) . '/';
			 
		} 
		elseif (strpos($path, '\\') !== false)
		{
			$basename = implode('\\',array_filter(explode('\\', $path))) . '\\';
		}
		if(PHP_OS != 'WINNT')  $basename = '/'.$basename;
		return $basename;
	}
	
}

$mysql = new sql(config::$mysql); //数据库对象
$sqlserv = new sql(config::$sqlserv); //数据库对象


$domain = 'https://www.2cto.com';  //域名
$hostRoot = __DIR__;
$size = 20000; //每篇xml文件的数据量 

$sitemap = [
	'priority' 	=> 1,
	'changefreq'	=> 'daily', //有效值为：always、hourly、daily、weekly、monthly、yearly、never
	'sitemaphost' => $domain. '/sitemap/', //sitemap 存放url
	'sitemappath' => $hostRoot . '/sitemap/', //sitemap 存放目录
	'indexes' => $hostRoot,	//提供网站根目录
];
$class = new sitemap($sitemap); //sitemap 对象




/**
 *由于sitemap 的列表生成比较复杂所以需要重新创建一个专门生成用的函数
 * @param url 提供所有需要生成的url
 */
function sitemapList($param,$class){
	$ct 		= $param['ct'];
	$size 		= $param['size'];
	$url 		= $param['url'];
	$fileName 	= $param['fileName'];

	if($ct > $size){
		// 数据url处理
		$sum = ceil($ct/$size);
		for($i=0;$i<$sum;$i++){
			for($s=0;$s<$size;$s++){
				$one = each($url)[1];
				if($one == null){
					break;
				}

				$xml[$fileName.$i.'.xml'][]['url'] = $one;
			}
			
			if($class->sitemapXml($xml) !== true) die('error');
			unset($data,$xml);
		}
		return "success";
	}
}




?>