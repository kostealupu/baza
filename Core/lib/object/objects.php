<?php
use Df\Config\ArrayItem as AI;
use Magento\Framework\DataObject as _DO;
use Magento\Framework\Model\AbstractModel as M;

/**
 * @used-by df_sc()
 * @used-by \Df\Config\Backend\Serialized::processI()
 * @param string $resultClass
 * @param string|null|array(string => mixed) $a2 [optional]
 * @param array(string => mixed) $a3 [optional]
 * @return _DO|object
 */
function df_ic($resultClass, $a2 = null, array $a3 = []) {
	/** @var string|null $expectedClass */ /** @var array(string => mixed) $params */
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($expectedClass, $params) = is_array($a2) ? [null, $a2] : [$a2, $a3];
	return df_ar(new $resultClass($params), $expectedClass);
}

/**
 * 2016-08-24
 * 2016-09-04
 * Метод getId присутствует не только у потомков @see \Magento\Framework\Model\AbstractModel,
 * но и у классов сторонних библиотек, например:
 * https://github.com/CKOTech/checkout-php-library/blob/v1.2.4/com/checkout/ApiServices/Charges/ResponseModels/Charge.php?ts=4#L170-L173
 * По возможности, задействуем и сторонние реализации.
 *
 * К сожалению, нельзя здесь для проверки публичности метода использовать @see is_callable(),
 * потому что наличие @see \Magento\Framework\DataObject::__call()
 * приводит к тому, что @see is_callable всегда возвращает true.
 * Обратите внимание, что @uses method_exists(), в отличие от @see is_callable(),
 * не гарантирует публичную доступность метода:
 * т.е. метод может у класса быть, но вызывать его всё равно извне класса нельзя,
 * потому что он имеет доступность private или protected.
 * Пока эта проблема никак не решена.
 *
 * 2016-09-05
 * Этот код прекрасно работает с объектами классов типа @see \Magento\Directory\Model\Currency
 * благодаря тому, что @uses \Magento\Framework\Model\AbstractModel::getId()
 * не просто тупо считывает значение поля id, а вызывает метод
 * @see \Magento\Framework\Model\AbstractModel::getIdFieldName()
 * который, в ссвою очередь, узнаёт имя идентифицирующего поля из своего ресурса:
 * @see \Magento\Framework\Model\AbstractModel::_init()
 * @see \Magento\Directory\Model\ResourceModel\Currency::_construct()
 *
 * @see df_hash_o() использует тот же алгоритм, но не вызывает @see df_id() ради ускорения.
 *
 * @used-by df_idn()
 * @used-by dfa_ids()
 * @see df_hash_o()
 * @param object|int|string $o
 * @param bool $allowNull [optional]
 * @return int|string|null
 */
function df_id($o, $allowNull = false) {/** @var int|string|null $r */
	$r = !is_object($o) ? $o : ($o instanceof M || method_exists($o, 'getId') ? $o->getId() : (
		$o instanceof AI ? $o->id() : null
	));
	df_assert($allowNull || $r);
	return $r;
}

/**
 * 2016-09-05
 * @used-by df_cm_backend_url()
 * @used-by df_customer_backend_url()
 * @used-by df_order_backend_url()
 * @param object|int|string $o
 * @param bool $allowNull [optional]
 * @return int
 */
function df_idn($o, $allowNull = false) {return df_nat(df_id($o, $allowNull), $allowNull);}

/**
 * 2017-01-12
 * 1) PHP, к сожалению, не разрешает в выражении с new делать выражением имя класса:
 * https://3v4l.org/U6TJR
 * Поэтому и создал эту небольшую функцию.
 * В отличие от @see df_new_om(), она не использует Object Manager.
 * 2) Впервые использую в своём коде возможность argument unpacking, появившуюся в PHP 5.6:
 * https://3v4l.org/eI2vf
 * http://stackoverflow.com/a/25781989
 * https://php.net/manual/functions.arguments.php#example-145
 * @used-by df_newa()
 * @used-by \Df\Payment\Currency::f()
 * @used-by \Df\Payment\W\F::__construct()
 * @used-by \Df\PaypalClone\Charge::p()
 * @used-by \Df\PaypalClone\Signer::_sign()
 * @used-by \Df\Sso\Button::s()
 * @used-by \Df\Sso\CustomerReturn::c()
 * @used-by \Df\StripeClone\Facade\Card::create()
 * @used-by \Df\StripeClone\P\Charge::sn()
 * @used-by \Df\StripeClone\P\Preorder::request()
 * @used-by \Df\StripeClone\P\Reg::request()
 * @used-by \Df\Zoho\API\Client::i()
 * @used-by \Dfe\CheckoutCom\Handler::p()
 * @used-by \Dfe\SMTP\Strategy::options()
 * @used-by \Dfe\TwoCheckout\Handler::p()
 * @param string $c
 * @param mixed ...$args
 * @return object
 */
function df_new($c, ...$args) {return new $c(...$args);}

/**
 * 2017-01-12
 * PHP, к сожалению, не разрешает в выражении с new делать выражением имя класса.
 * Поэтому и создал эту небольшую функцию.
 * В отличие от @see df_new_om(), она не использует Object Manager.
 * @used-by dfs_con()
 * @used-by \Df\API\Facade::p()
 * @used-by \Df\Payment\W\F::aspect()
 * @used-by \Df\Zf\Validate\StringT\Parser::getZendValidator()
 * @param string $c
 * @param string|null $expected [optional]
 * @param mixed ...$args
 * @return object
 */
function df_newa($c, $expected = null, ...$args) {return df_ar(df_new($c, ...$args), $expected);}

/**
 * 2016-01-06
 * 2017-01-12 Use @see df_new() if you do not need Object Manager.
 * @see df_new_omd()
 * @used-by df_category_c()
 * @used-by df_cms_blocks()
 * @used-by df_controller_raw()
 * @used-by df_csv_o()
 * @used-by df_currency()
 * @used-by df_db_transaction()
 * @used-by df_load()
 * @used-by df_mail()
 * @used-by df_mail_shipment()
 * @used-by df_oi_load()
 * @used-by df_order_c()
 * @used-by df_package_new()
 * @used-by df_pc()
 * @used-by df_region_name()
 * @used-by df_request_i()
 * @used-by df_review_summary()
 * @used-by df_url_backend_new()
 * @used-by dfp_refund()
 * @used-by ikf_product_c()
 * @used-by ikf_project()
 * @used-by mc_h()
 * @used-by \Df\Customer\AddAttribute\Address::p()
 * @used-by \Df\Customer\Plugin\Model\ResourceModel\AddressRepository::aroundSave()
 * @used-by \Df\Directory\Model\Country::c()
 * @used-by \Df\Framework\Plugin\Data\Form\Element\Fieldset::beforeAddField()
 * @used-by \Df\Framework\Upgrade::sEav()
 * @used-by \Df\Payment\ConfigProvider::p()
 * @used-by \Df\Sales\Model\Order\Payment::getInvoiceForTransactionId()
 * @used-by \Df\Sales\Plugin\Model\Order\Address\Renderer::addressConfig()
 * @used-by \Df\Sso\CustomerReturn::mc()
 * @used-by \Df\Sso\CustomerReturn::register()
 * @used-by \Dfe\SalesSequence\Plugin\Model\Manager::aroundGetSequence()
 * @used-by \Doormall\Shipping\Method::collectRates()
 * @used-by \Frugue\Shipping\Method::collectRates()
 * @used-by \Inkifi\Mediaclip\API\Entity\Order\Item::mProduct()
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload\Pureprint::pShipment()
 * @used-by \Inkifi\Pwinty\Controller\Index\Index::execute()
 * @used-by \Interactivated\Quotecheckout\Controller\Index\Updateordermethod::execute() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/116)
 * @used-by \KingPalm\B2B\Setup\V140\MoveDataToAddress::p()
 * @used-by \Mangoit\MediaclipHub\Controller\Index\AddToCart::execute()
 * @used-by \PPCs\Core\Plugin\Quote\Model\QuoteRepository::aroundGetActiveForCustomer() 
 * @param string $c
 * @param array(string => mixed) $p [optional]
 * @return _DO|object
 */
function df_new_om($c, array $p = []) {return df_om()->create($c, $p);}

/**
 * 2017-04-08
 * @used-by df_oq_sa()
 * @used-by \CanadaSatellite\Core\Plugin\Magento\Sales\Api\Data\OrderInterface::afterGetPayment() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/29)
 * @used-by \Dfe\Markdown\Plugin\Ui\Component\Form\Element\Wysiwyg::beforePrepare()
 * @used-by \Doormall\Shipping\Method::collectRates()
 * @used-by \Frugue\Core\Plugin\Sales\Model\Quote::afterGetAddressesCollection()
 * @used-by \Frugue\Shipping\Method::collectRates()
 * @used-by \KingPalm\B2B\Block\Registration::e()
 * @used-by \KingPalm\B2B\Block\Registration::form()
 * @param string $c
 * @param array(string => mixed) $d [optional]
 * @return _DO|object
 */
function df_new_omd($c, array $d = []) {return df_om()->create($c, ['data' => $d]);}

/**
 * 2015-03-23
 * @see df_ic()
 * @used-by \Df\GingerPaymentsBase\Settings::os()
 * @used-by \Df\Payment\Settings::_options()
 * @param string $resultClass
 * @param string|null $expectedClass [optional]
 * @param array(string => mixed) $params [optional]
 * @param string $cacheKeySuffix [optional]
 * @return _DO|object
 */
function df_sc($resultClass, $expectedClass = null, array $params = [], $cacheKeySuffix = '') {
	static $cache; /** @var array(string => object) $cache */
	$key = $resultClass . $cacheKeySuffix; /** @var string $key */
	if (!isset($cache[$key])) {
		$cache[$key] = df_ic($resultClass, $expectedClass, $params);
	}
	return $cache[$key];
}

/**
 * 2016-08-23
 * 2017-10-08
 * isset($object->{$key}) returns false for the non-public properties: https://3v4l.org/bRAcp
 * E.g., the following code returns `0`:
 * 		class A {private $b = 3;}
 * 		$a = new A;
 * 		echo intval(isset($a->{'b'}));
 * @see dfa()
 * @used-by \Alignet\Paymecheckout\Model\Client\Classic\Order\DataGetter::userCodePayme() (innomuebles.com, https://github.com/innomuebles/m2/issues/17)
 * @used-by \Df\StripeClone\Facade\Charge::cardData()
 * @used-by \MageSuper\Faq\Observer\CheckRecaptcha3::execute() (canadasatellite.ca)
 * @param object $o
 * @param string|int $k
 * @param mixed|callable|null $d [optional]
 * @return mixed|null
 */
function dfo($o, $k, $d = null) {return isset($o->{$k}) ? $o->{$k} : df_call_if($d, $k);}

/**
 * 2017-07-11
 * It returns a singleton of a class from the $caller module with the $owner or $suf suffix.
 * The result should be a descendant of the $owner, and should exist (it is not defaulted to $owner).
 * @used-by df_oauth_app()
 * @used-by \Df\Zoho\App::s()
 * @used-by \Df\ZohoBI\API\Facade::s()
 * @param string|object $caller
 * @param string|null $suf [optional]
 * @return object
 */
function dfs_con($caller, $suf = null) {
	$bt = df_bt(0, 2)[1]; /** @var array(string => mixed) $bt */
	# 2020-02-25
	# "«Undefined index: class in vendor/mage2pro/core/Core/lib/object/objects.php on line 214»":
	# https://github.com/mage2pro/core/issues/95
	if (!($owner = dfa($bt, 'class')) && !$suf) { /** @var string|null $owner */
		df_error("The backtrace frame is invalid for dfs_con() because it lacks the `class` key:\n%s\nThe key should exist if the `suf` argument is not passed to dfs_con().", df_dump($bt));
	}
	return dfcf(function($owner, $m, $suf) {return
		df_newa(df_con($m, $suf), $owner)
	;}, [$owner, df_module_name_c($caller), $suf ?: df_class_suffix($owner)]);
}