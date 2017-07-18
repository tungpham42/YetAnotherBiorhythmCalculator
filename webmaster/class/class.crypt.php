<?php
class Crypt {
	private $config;
	public function __construct($config) {
		$this -> config = $config;
	}
	
	public function encode($string) {
 		/* Open the cipher */
    $td = mcrypt_module_open($this->config->ciphername, '', $this->config->modename, '');

    /* Create the IV and determine the keysize length, use MCRYPT_RAND
     * on Windows instead */
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), $this->config->source);
    $ks = mcrypt_enc_get_key_size($td);

		/* Create key */
    $key = substr(md5($this->config->key), 0, $ks);

    /* Intialize encryption */
    mcrypt_generic_init($td, $key, $iv);

    /* Encrypt data */
    $encrypted = mcrypt_generic($td, $string);

    /* Terminate encryption handler */
    mcrypt_generic_deinit($td);
		
		/* Return encrypted key */
		return array(
			'iv' => $iv,
			'encrypted' => $encrypted,
		);
	}
	
	public function decode($iv, $encrypted) {
 		/* Open the cipher */
    $td = mcrypt_module_open($this->config->ciphername, '', $this->config->modename, '');

    /* Create the IV and determine the keysize length, use MCRYPT_RAND
     * on Windows instead */
    $ks = mcrypt_enc_get_key_size($td);
		
		/* Create key */
    $key = substr(md5($this->config->key), 0, $ks);

    /* Intialize encryption */
    mcrypt_generic_init($td, $key, $iv);

    /* Encrypt data */
    $decrypted = mdecrypt_generic($td, $encrypted);

    /* Terminate encryption handler */
    mcrypt_generic_deinit($td);
		//mcrypt_module_close($td);
		
		/* Return decrypted key */
		return trim($decrypted);	
	}
}