<?php
namespace Oreilly\ModernPHP\Url;

class Scanner
{
	/*
	 * @var array 一个由 URL 组成的数组 
	 */
	protected $urls;
	
	/*
	 * @var \GuzzleHttp\Client
	 */
	protected $httpClient;
	
	/*
	 * 构造方法
	 * @param array $urls 一个要扫描的 URL 数组
	 */
	public function __construct(array $urls)
	{
		$this->urls = $urls;
		$this->httpClient = new \GuzzleHttp\Client();
	}
	
	/*
	 * 获取死链
	 * @return array
	 */
	public function getInvalidUrls()
	{
		$invalidUrls = array();
		foreach ($this->urls as $url) {
			try {
				$statusCode = $this->getStatusCodeForUrl($url);
			} catch (Exception $e) {
				$statusCode = 500;
			}
			
			if ($statusCode >= 400){
				array_push($invalidUrls, array(
					'url'    => $url,
					'status' => $statusCode
				));
			}
		}
		return $invalidUrls;
	}
	
	/*
	 * 获取指定 URL 的 HTTP 状态码
	 * @param string $url 远程 URL
	 * @return int HTTP 状态码
	 */
	protected function getStatusCodeForUrl($url)
	{
		$httpResponse = $this->httpClient->options($url);
		return $httpResponse->getStatusCode();
	}
}