<?php
use Closure as F;
use Exception as E;
use Zend_Uri as zUri;
use Zend_Uri_Exception as zUriE;
use Zend_Uri_Http as zUriH;

/**
 * 2017-05-12
 * @used-by df_domain_current()
 * @used-by ikf_pw_carrier()
 * @used-by Dfe_PortalStripe::view/frontend/templates/page/customers.phtml
 * @param string $u
 * @param bool $www [optional]
 * @param F|bool|mixed $throw [optional]
 * @return string|null
 * @throws E|zUriE
 */
function df_domain($u, $www = false, $throw = true) {return
	!($r = df_zuri($u, $throw)->getHost()) ? null : ($www ? $r : df_trim_text_left($r, 'www.'))
;}

/**
 * 2016-05-31
 * @param string $u
 * @return string
 */
function df_url_base($u) {return df_first(df_url_bp($u));}

/**
 * 2017-02-13 «https://mage2.pro/sandbox/dfe-paymill» => [«https://mage2.pro»,  «sandbox/dfe-paymill»]
 * @used-by df_url_base()
 * @used-by df_url_trim_index()
 * @param string $u
 * @return string[]
 */
function df_url_bp($u) {
	/** @var string $base */ /** @var string $path */
	if (!df_check_url($u)) {
		# 2020-03-02
		# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
		# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
		# We should support PHP 7.0.
		list($base, $path) = ['', $u];
	}
	else {
		$z = df_zuri($u); /** @var zUriH $z */
		$base = df_ccc(':', "{$z->getScheme()}://{$z->getHost()}", dftr($z->getPort(), ['80' => '']));
		$path = df_trim_ds($z->getPath());
	}
	return [$base, $path];
}

/**
 * 2019-01-12
 * 2020-01-19 The previous (also working) solution was `df_last(df_url_bp($u))`.
 * @see df_url_bp()
 * @used-by df_store_code_from_url()
 * @used-by \Df\API\Client::_p()
 * @used-by \Wolf\Filter\Block\Navigation::selectedPath()
 * @used-by \Frugue\Store\Plugin\UrlRewrite\Model\StoreSwitcher\RewriteUrl::aroundSwitch()
 * @param string $u
 * @return string
 */
function df_url_path($u) {return df_trim_ds(df_request_i($u)->getPathInfo());}

/**
 * 2017-02-13 It removes the following endinds: «/», «index/», «index/index/».
 * @used-by df_url_frontend()
 * @param string $u
 * @return string
 */
function df_url_trim_index($u) {
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($base, $path) = df_url_bp($u); /** @var string $base */ /** @var string $path */
	$a = df_explode_path($path); /** @var string[] $a */
	$i = count($a) - 1; /** @var int $i */
	while ($a && in_array($a[$i--], ['', 'index'], true)) {array_pop($a);}
	return df_cc_path($base, df_cc_path($a));
}

/**
 * 2016-05-30
 * @used-by df_domain()
 * @used-by df_replace_store_code_in_url()
 * @param string $u
 * @param F|bool|mixed $throw [optional]
 * @return zUri|zUriH|mixed
 * @throws E|zUriE
 */
function df_zuri($u, $throw = true) {return df_try(function() use($u) {return zUri::factory($u);}, $throw);}