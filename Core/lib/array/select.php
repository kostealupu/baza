<?php
use Closure as F;
use Magento\Config\Model\Config\Structure\AbstractElement as AE;
use Magento\Framework\DataObject as _DO;
use Traversable as T;

/**
 * 2015-02-11
 * Аналог @see array_column() для коллекций.
 * Ещё один аналог: @see \Magento\Framework\Data\Collection::getColumnValues(),
 * но его результат — не ассоциативный.
 * 2016-07-31 При вызове с 2-мя параметрами эта функция идентична функции @see df_each()
 * 2017-07-09
 * Now the function accepts an array as $object.
 * Even in this case it differs from @see array_column():
 * array_column() misses the keys: https://3v4l.org/llMrL
 * df_column() preserves the keys.
 * @used-by df_index()
 * @used-by df_product_images_additional()
 * @used-by \Wolf\Filter\Block\Navigation::hDropdowns()
 * @param \Traversable|array(int|string => _DO|array(string => mixed)) $c
 * @param string|\Closure $fv
 * @param string|null $fk [optional]
 * @return array(int|string => mixed)
 */
function df_column($c, $fv, $fk = null) {return df_map_kr($c, function($k, $v) use($fv, $fk) {return [
	!$fk ? $k : df_call($v, $fk), df_call($v, $fv)
];});}

/**
 * Раньше функция @see dfa() была универсальной:
 * она принимала в качестве аргумента $entity как массивы, так и объекты.
 * В 99.9% случаев в качестве параметра передавался массив.
 * Поэтому ради ускорения работы системы вынес обработку объектов в отдельную функцию @see dfo().
 * 2020-01-29
 * If $k is an array, then the function returns a subset of $a with $k keys in the same order as in $k.
 * We heavily rely on it, e.g., in some payment methods: @see \Dfe\Dragonpay\Signer\Request::values()
 * @see dfac()
 * @see dfad()
 * @see dfaoc()
 * @see dfo()
 * @used-by df_bt_has()
 * @used-by df_ci_get()
 * @used-by df_cli_argv()
 * @used-by df_countries_options()
 * @used-by df_currencies_options()
 * @used-by df_db_name()
 * @used-by df_deployment_cfg()
 * @used-by df_fe_attrs()
 * @used-by df_fe_fc()
 * @used-by df_github_request()
 * @used-by df_log_l()
 * @used-by df_oi_get()
 * @used-by df_package()
 * @used-by df_request_method()
 * @used-by df_trd()
 * @used-by df_visitor_ip()
 * @used-by dfa_prepend()
 * @used-by dfac()
 * @used-by dfad()
 * @used-by dfaoc()
 * @used-by \Alignet\Paymecheckout\Controller\Classic\Response::execute() (innomuebles.com, https://github.com/innomuebles/m2/issues/11)
 * @used-by \Alignet\Paymecheckout\Model\Client\Classic\Order\DataGetter::userCodePayme() (innomuebles.com, https://github.com/innomuebles/m2/issues/17)
 * @used-by \Amasty\Checkout\Model\Optimization\LayoutJsDiffProcessor::moveArray(canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/120)
 * @used-by \Df\Config\Source::options()
 * @used-by \Df\Core\Controller\Index\Index::execute()
 * @used-by \Df\Core\O::a()
 * @used-by \Df\Framework\Form\Element\Fieldset::select()
 * @used-by \Df\Framework\Log\Dispatcher::handle()
 * @used-by \Df\Framework\Plugin\Css\PreProcessor\File\FileList\Collator::afterCollate()
 * @used-by \Df\Framework\Request::extra()
 * @used-by \Df\GoogleFont\Font\Variant\Preview\Params::fromRequest()
 * @used-by \Df\Payment\Charge::metadata()
 * @used-by \Df\Payment\W\Reader::r()
 * @used-by \Df\Payment\W\Reader::test()
 * @used-by \Df\PaypalClone\Signer::v()
 * @used-by \Df\Security\BlackList::has()
 * @used-by \Df\Sentry\Client::__construct()
 * @used-by \Df\Sentry\Client::capture()
 * @used-by \Df\Sentry\Client::get_http_data()
 * @used-by \Df\Sso\CustomerReturn::mc()
 * @used-by \Dfe\AlphaCommerceHub\W\Event::providerRespL()
 * @used-by \Dfe\CurrencyFormat\O::postProcess()
 * @used-by \Dfe\Dragonpay\Signer\Request::values()
 * @used-by \Dfe\Dragonpay\Signer\Response::values()
 * @used-by \Dfe\IPay88\Signer\Request::values()
 * @used-by \Dfe\IPay88\Signer\Response::values()
 * @used-by \Dfe\Robokassa\Signer\Request::values()
 * @used-by \Dfe\Robokassa\Signer\Response::values()
 * @used-by \Dfe\SecurePay\Signer\Request::values()
 * @used-by \Dfe\TwoCheckout\Method::charge()
 * @used-by \DxMoto\Core\Observer\CanLog::execute()
 * @used-by \Mageside\CanadaPostShipping\Model\Carrier::_doRatesRequest() (canadasatellite.ca)
 * @used-by \Mangoit\MediaclipHub\Helper\Data::getMediaClipProjects()
 * @used-by \TFC\Core\Observer\CanLog::execute()
 * @used-by \TFC\GoogleShopping\Att\Brand::v() (tradefurniturecompany.co.uk, https://github.com/tradefurniturecompany/google-shopping/issues/8)
 * @used-by \VegAndTheCity\Core\Plugin\Mageplaza\Search\Helper\Data::afterGetProducts()
 * @param array(int|string => mixed) $a
 * @param string|string[]|int|null $k
 * @param mixed|callable $d
 * @return mixed|null|array(string => mixed)
 */
function dfa(array $a, $k, $d = null) {return
	# 2016-02-13
	# Нельзя здесь писать `return df_if2(isset($array[$k]), $array[$k], $d);`
	# потому что получим «Notice: Undefined index».
	# 2016-08-07
	# В \Closure мы можем безнаказанно передавать параметры,
	# даже если closure их не поддерживает https://3v4l.org/9Sf7n
	is_null($k) ? $a : (is_array($k) ? dfa_select_ordered($a, $k) : (isset($a[$k]) ? $a[$k] : (
		df_contains($k, '/') ? dfa_deep($a, $k, $d) : df_call_if($d, $k)
	)))
;}

/**
 * 2020-01-29
 * @used-by df_credentials()
 * @used-by dfe_portal_module()
 * @used-by \Df\Framework\Request::clean()
 * @used-by \Df\Framework\Request::extra()
 * @param F $f
 * @param string|string[]|null $k [optional]
 * @param mixed|callable|null $d [optional]
 * @return mixed
 */
function dfac(F $f, $k = null, $d = null) {return dfa(dfcf($f, [], [], false, 1), $k, $d);}

/**
 * 2020-01-29     
 * @used-by df_call()
 * @used-by \Df\Config\Backend::fc()
 * @used-by \Df\Payment\Block\Info::ii()
 * @used-by \Df\Payment\Method::ii()
 * @param _DO|AE $o
 * @param string|string[]|null $k [optional]
 * @param mixed|callable|null $d [optional]
 * @return _DO|AE|mixed
 */
function dfad($o, $k = null, $d = null) {return is_null($k) ? $o : dfa(df_gd($o), $k, $d);}

/**
 * 2020-01-29
 * @used-by \Df\Config\A::get()
 * @used-by \Df\Framework\Form\Element\Fieldset::v()
 * @used-by \Df\Payment\TM::req()
 * @used-by \Df\Payment\TM::res0()
 * @used-by \Dfe\CheckoutCom\Response::a()
 * @param object $o
 * @param F $f
 * @param string|string[]|null $k [optional]
 * @param mixed|callable|null $d [optional]
 * @return mixed
 */
function dfaoc($o, F $f, $k = null, $d = null) {return dfa(dfc($o, $f, [], false, 1), $k, $d);}

/**
 * 2015-02-08
 * 2020-01-29
 * 1) It returns a subset of $a with $k keys in the same order as in $k.
 * 2) Normally, you should use @see dfa() instead because it is shorter and calls dfa_select_ordered() internally.
 * @used-by dfa()
 * @param array(string => string)|T $a
 * @param string[] $k
 * @return array(string => string)
 */
function dfa_select_ordered($a, array $k)  {
	$resultKeys = array_fill_keys($k, null); /** @var array(string => null) $resultKeys */
	/**
	 * 2017-10-28
	 * During the last 2.5 years, I had the following code here:
	 * 		array_merge($resultKeys, df_ita($source))
	 * It worked wronly, if $source contained SOME numeric-string keys like "99":
	 * https://github.com/mage2pro/core/issues/40#issuecomment-340139933
	 *
	 * «A key may be either an integer or a string.
	 * If a key is the standard representation of an integer, it will be interpreted as such
	 * (i.e. "8" will be interpreted as 8, while "08" will be interpreted as "08").»
	 * https://php.net/manual/language.types.array.php
	 *
	 * «If, however, the arrays contain numeric keys, the later value will not overwrite the original value,
	 * but will be appended.
	 * Values in the input array with numeric keys will be renumbered
	 * with incrementing keys starting from zero in the result array.»
	 * https://php.net/manual/function.array-merge.php
	 * https://github.com/mage2pro/core/issues/40#issuecomment-340140297
	 * `df_ita($source) + $resultKeys` does not solve the problem,
	 * because the result keys are ordered in the `$source` order, not in the `$resultKeys` order:
	 * https://github.com/mage2pro/core/issues/40#issuecomment-340140766
	 * @var array(string => string) $resultWithGarbage
	 */
	$resultWithGarbage = dfa_merge_numeric($resultKeys, df_ita($a));
	return array_intersect_key($resultWithGarbage, $resultKeys);
}