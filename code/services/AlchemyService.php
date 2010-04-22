<?php
/*

Copyright (c) 2009, SilverStripe Australia PTY LTD - www.silverstripe.com.au
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of SilverStripe nor the names of its contributors may be used to endorse or promote products derived from this software
      without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
OF SUCH DAMAGE.
*/

/**
 * A service that uses the AlchemyAPI to retrieve information about content
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 */
class AlchemyService
{
    protected $api;

	/**
	 * How many characters should content have before it is indexed?
	 *
	 * @var int
	 */
	public static $char_limit = 400;

	public static $config = array(
		'api_url' => 'http://access.alchemyapi.com',
		'api_key' => '',
	);

	public static function set_api_key($key) {
		self::$config['api_key'] = $key;
	}

	public function  __construct() {
		$this->api = new WebApiClient(self::$config['api_url']);
		$this->api->setMethods(self::$methods);
		$this->api->setGlobalParam('apikey', self::$config['api_key']);
		$this->api->setGlobalParam('outputMode', 'json');
	}

	public function getEntities($content) {
		return $this->call('getEntities', array('text' => $content));
	}

	public function getKeywords($content) {
		return $this->call('getKeywords', array('text' => $content));
	}

	public function call($method, $args = null) {
		return $this->api->callMethod($method, $args);
	}


	public static $methods = array(
		'getEntities' => array(
			'method' => 'GET',
			'url' => '/calls/text/TextGetRankedNamedEntities',
			'params' => array('text'),
			'cache' => 600,
			'return' => 'json'
			// 'enctype' => Zend_Http_Client::ENC_FORMDATA,
		),
		'getCategory' => array(
			'method' => 'GET',
			'url' => '/calls/text/TextGetCategory',
			'params' => array('text'),
			'cache' => 600,
			'return' => 'json'
		),
		
		'getKeywords' => array(
			'method' => 'GET',
			'url' => '/calls/text/TextGetRankedKeywords',
			'params' => array('text'),
			'cache' => 600,
			'return' => 'json'
		),
	);
}
?>