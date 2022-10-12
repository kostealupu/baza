<?php
/**
 * 2017-04-03
 * @param bool $cond [optional]
 */
function df_break($cond = true) {$cond && function_exists('xdebug_break') ? xdebug_break() : null;}

/**
 * 2017-01-25
 * @used-by \Dfe\CheckoutCom\Charge::metaData()
 * @used-by \Dfe\Klarna\Api\Checkout\V3\UserAgent::__construct()
 * «How to detect the current web server programmatically?» https://mage2.pro/t/2523
 * http://serverfault.com/a/164159
 * @param bool $asArray [optional]
 * @return string|string[]
 * An example of result: «Apache/2.4.20» or ['Apache', '2.4.20'].
 */
function df_webserver($asArray = false) {return dfcf(function($asArray = false) { /** @var string $r */
	# 2017-01-25 «Apache/2.4.20 (Win64) OpenSSL/1.0.2h PHP/5.6.22»
	$s = dfa($_SERVER, 'SERVER_SOFTWARE'); /** @var string|null $s */
	$r = $s ? df_first(explode(' ', $s)) : (df_is_cli() ? 'PHP CLI/' . phpversion() : 'Unknown/Unknown');
	return !$asArray ? $r : explode('/', $r);
}, func_get_args());}