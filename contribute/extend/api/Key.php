<?php
namespace api;

class Key {

	protected $hash;
	protected $apiSignPrivate = 'MIICXQIBAAKBgQCULlpgB/ay/6DbAkUsSHuYn/XHv0kAnrKWO3g/CPneXWzfBBrNqTG/izYgJTRPwQSwFhUxWzz6Wd4iWqMB2gDyMyu90GzurORgI3E0Jv5A7GpPv+NCox+UpdPnFVkTjh1S3DQAI8kSgYTXKg/Vxh8IQ0WAQmHpEFuznE56B60HlwIDAQABAoGAIs3UWlPkhNx3ypj46FLJ/OotT1JFckjUB2dGcf/IuIrXBHaxWPbRgXzQJLK4W7cpQ7acGbClXOP4wbbqLIgoyl7uKTAT8/AF3XEuOEkeYxPGT+zM6HAQKZk8Y+EhUdFvMkwu84UmtgS5VMBKaQMAKj6HO8jCw6Lu2WuKrOu2T4ECQQDC9MJwHR879k1lVvmiH2uDfm8aKS2vjynmrGIj4whHAQ9Lmn9BcU6+klR6vYE8cd0HjyQ52IckGIp0MvBE56jXAkEAwpQ2h0rWYGsyVzIi2PqBFpvBMkcXclCtHTgZMwiYwuCpwXDHexPXVDIy9mIGWVPOiZqMIU7AeYz6yHpkrxv/QQJBAKI0QJ5FLJqYF9bsIXDJEYvrIwcyIafCxor8+/59w4JIGHC/z4ckfe7DEvS4PRGMbuj+KJbUV8QpgvCg/RVnZSsCQASd5alSIkIJaRlejCsfzn5N1ciunSTOOz1NPnPSiWVeVUjMtz75WouZ4VGtQ79M2MK1EwwbRT/dE3o8RTVOccECQQCJHASKItyLdrj/7tGvcGSoXpgWMuF4IpHF8ZtXZfNRi5fYlFHX8KDMwXMB7+aaFlQL0HGcSOAZ0uWROJIzZpcl';

	protected $apiSignPublic = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCULlpgB/ay/6DbAkUsSHuYn/XHv0kAnrKWO3g/CPneXWzfBBrNqTG/izYgJTRPwQSwFhUxWzz6Wd4iWqMB2gDyMyu90GzurORgI3E0Jv5A7GpPv+NCox+UpdPnFVkTjh1S3DQAI8kSgYTXKg/Vxh8IQ0WAQmHpEFuznE56B60HlwIDAQAB';

	//渠道的对接api 秘钥
	public static function apiKey($value){
		return (new key)->md5($value)->sha1($value);
	}


	//md5加密
	public function md5($value){
		$this->hash['md5'] = hash('md5',$value . $this->apiSignPrivate);

		return $this;
	}

	//sha256加密
	public function sha1($value){
		$this->hash['sha1'] = hash('sha256',$value . $this->apiSignPrivate);
		
		return $this;
	}

	//获取加密类型
	public function get($type){
		return $this->hash[$type];
	}




}

?>