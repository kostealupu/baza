<?php
namespace Df\Config;
use Df\Config\A as ConfigA;
use Df\Config\Source\NoWhiteBlack as NWB;
use Df\Typography\Font;
use Magento\Framework\App\ScopeInterface as S;
use Magento\Store\Model\Store;
/**
 * 2015-11-09
 * @see \AlbumEnvy\Popup\Settings
 * @see \CanadaSatellite\Amelia\Settings (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
 * @see \Df\Amazon\Settings
 * @see \Df\API\Settings
 * @see \Df\Config\Settings\Configurable
 * @see \Df\Facebook\Settings
 * @see \Df\Google\Settings
 * @see \Df\OAuth\Settings
 * @see \Df\Payment\Settings
 * @see \Df\Payment\Settings\_3DS
 * @see \Df\Shipping\Settings
 * @see \Df\Sso\Settings
 * @see \Df\Zoho\Settings
 * @see \Dfe\AllPay\InstallmentSales\Settings
 * @see \Dfe\AmazonLogin\Settings\Credentials
 * @see \Dfe\BackendLoginAutocomplete\Settings
 * @see \Dfe\CurrencyConvert\Settings
 * @see \Dfe\CurrencyFormat\Settings
 * @see \Dfe\Customer\Settings\Address
 * @see \Dfe\Customer\Settings\Common
 * @see \Dfe\Dynamics365\Settings\General
 * @see \Dfe\Dynamics365\Settings\General\OAuth
 * @see \Dfe\Dynamics365\Settings\Products
 * @see \Dfe\Frontend\Settings\Common\Header
 * @see \Dfe\Frontend\Settings\ProductView\Compare
 * @see \Dfe\Frontend\Settings\ProductView\Price
 * @see \Dfe\Frontend\Settings\ProductView\Reviews
 * @see \Dfe\Frontend\Settings\ProductView\ShortDescription
 * @see \Dfe\Frontend\Settings\ProductView\Sku
 * @see \Dfe\Frontend\Settings\ProductView\StockStatus
 * @see \Dfe\Frontend\Settings\ProductView\Title
 * @see \Dfe\Frontend\Settings\ProductView\Wishlist
 * @see \Dfe\Mailgun\Settings
 * @see \Dfe\Markdown\Settings
 * @see \Dfe\Portal\Settings\General
 * @see \Dfe\Salesforce\Settings\General
 * @see \Dfe\SalesSequence\Settings
 * @see \Dfe\SMTP\Settings
 * @see \Dfe\SMTP\Settings\Mailgun
 * @see \Dfe\TwitterTimeline\Settings
 * @see \Inkifi\Map\Settings
 * @see \Inkifi\Mediaclip\Settings
 */
abstract class Settings {
	/**
	 * 2015-11-09
	 * 2016-11-24 From now on, the value should not include the trailing `/`.
	 * @used-by \Df\Config\Settings::v()
	 * @see \AlbumEnvy\Popup\Settings::prefix()
	 * @see \CanadaSatellite\Amelia\Settings::prefix() (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
	 * @see \Dfe\CurrencyConvert\Settings::prefix()
	 * @see \Df\Payment\Settings::prefix()
	 * @see \Df\Zoho\Settings::prefix::prefix()
	 * @see \Dfe\Dynamics365\Settings\General::prefix()
	 * @see \Dfe\Dynamics365\Settings\Products::prefix()
	 * @see \Dfe\Mailgun\Settings::prefix()
	 * @see \Dfe\Portal\Settings\General::prefix()
	 * @see \Dfe\SMTP\Settings::prefix()
	 * @see \Dfe\SMTP\Settings\Mailgun::prefix()
	 * @see \Doormall\Shipping\Settings::prefix()
	 * @return string
	 */
	abstract protected function prefix();

	/**
	 * 2015-11-09
	 * @used-by \CanadaSatellite\Amelia\Settings::sticky() (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
	 * @used-by \Df\API\Settings::test()
	 * @used-by \Df\Payment\Settings\_3DS::disable_()
	 * @used-by \Df\Payment\Settings\_3DS::enable_()
	 * @used-by \Df\Payment\Settings\Options::isLimited()
	 * @used-by \Df\Payment\Settings\Proxy::enable()
	 * @used-by \Dfe\AlphaCommerceHub\ConfigProvider::option()
	 * @used-by \Dfe\Moip\ConfigProvider::config()
	 * @used-by \Dfe\Stripe\ConfigProvider::config()
	 * @used-by \Dfe\TBCBank\Settings::tokenization()
	 * @used-by \Dfe\YandexKassa\Charge::pLoan()
	 * @used-by \Dfe\YandexKassa\Charge::pTax()
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @param bool $d [optional]
	 * @return int
	 */
	final function b($k = null, $s = null, $d = false) {return df_bool($this->v($k ?: df_caller_f(), $s, $d));}

	/**
	 * 2016-03-09
	 * Может возвращать строку или false.
	 * @used-by \Dfe\Stripe\Settings::prefill()
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return string|false
	 */
	final function bv($k= null, $s = null) {return $this->v($k ?: df_caller_f(), $s) ?: false;}

	/**
	 * 2016-03-14
	 * @used-by \Df\Payment\Settings\Options::allowed()
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return string[]
	 */
	final function csv($k = null, $s = null) {return df_csv_parse($this->v($k ?: df_caller_f(), $s));}

	/**
	 * 2016-08-04
	 * 2017-02-05
	 * @see \Dfe\BackendLoginAutocomplete\Settings::enable()
	 * @used-by \AlbumEnvy\Popup\Content::_toHtml()
	 * @used-by \CanadaSatellite\Amelia\Block::_toHtml() (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
	 * @used-by \Df\Framework\Mail\TransportObserver::execute()
	 * @used-by \Dfe\Klarna\Observer\ShortcutButtonsContainer::execute()
	 * @used-by \Dfe\Portal\Plugin\Store\Model\PathConfig::afterGetDefaultPath()
	 * @used-by \Dfe\Portal\Plugin\Theme\Model\View\Design::beforeGetConfigurationDesignTheme()
	 * @used-by \Dfe\Sift\Observer::f()
	 * @used-by \Dfe\Stripe\Block\Js::_toHtml()
	 * @used-by \Dfe\TBCBank\API\Client::proxy()
	 * @used-by \Dfe\Vantiv\API\Client::proxy()
	 * @used-by \Stock2Shop\OrderExport\Observer\OrderSaveAfter::execute()
	 * @param null|string|int|S $s [optional]
	 * @return bool
	 */
	function enable($s = null) {return $this->b(null, $s);}

	/**
	 * 2015-11-09
	 * @used-by \AlbumEnvy\Popup\Settings::content()
	 * @used-by \Df\ZohoBI\Settings::organization()
	 * @used-by \Dfe\Qiwi\Settings::apiID()
	 * @used-by \Dfe\SalesSequence\Settings::padLength()
	 * @used-by \Dfe\TwitterTimeline\Block::_toHtml()
	 * @used-by \Dfe\TwitterTimeline\Settings::limit()
	 * @used-by \Dfe\YandexKassa\Settings::scid()
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return int
	 */
	final function i($k = null, $s = null) {return df_int($this->v($k ?: df_caller_f(), $s));}

	/**
	 * 2015-12-26
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return int
	 */
	final function nat($k = null, $s = null) {return df_nat($this->v($k ?: df_caller_f(), $s));}

	/**
	 * 2015-12-26
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return int
	 */
	final function nat0($k = null, $s = null) {return df_nat0($this->v($k ?: df_caller_f(), $s));}

	/**
	 * 2015-12-07
	 * I have corrected the method, so it now returns null for an empty value
	 * (avoids to decrypt a null-value or an empty string).
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @param mixed|callable $d [optional]
	 * 2017-02-08
	 * Параметр $d нужен обязательно, потому что этот метод с этим параметром вызывается из @used-by \Df\Payment\Settings::testableGeneric()
	 * @used-by \Df\Config\Source\API\Key::apiKey()
	 * @used-by \Df\Payment\Settings::testableGeneric()
	 * @used-by \Df\Payment\Settings\Proxy::password()
	 * @used-by \Dfe\CurrencyConvert\Settings::accessKey()
	 * @used-by \Dfe\Sift\Settings::signatureKey()
	 * @used-by \Dfe\SMTP\Settings\Mailgun::password()
	 * @used-by \Inkifi\Map\Settings::keyGoogle()
	 * @used-by \Inkifi\Map\Settings::keyMapBox()
	 * @used-by \Inkifi\Map\Settings::keyOpenCage()
	 * @return string|null
	 */
	final function p($k = null, $s = null, $d = null) {
		$r = $this->v($k ?: df_caller_f(), $s); /** @var string|mixed $r */
		return df_if2($r, df_encryptor()->decrypt($r), $d);
	}

	/**
	 * 2016-03-08
	 * 2017-10-25
	 * @uses df_is_backend() is a dirty hack here:
	 * a call for @see df_is_system_config()
	 * from @see \Dfe\Portal\Plugin\Theme\Model\View\Design::beforeGetConfigurationDesignTheme()
	 * breaks my frontend...
	 * https://github.com/mage2pro/portal/blob/0.4.4/Plugin/Theme/Model/View/Design.php#L13-L33
	 * Maybe @see \Dfe\Portal\Plugin\Store\Model\PathConfig::afterGetDefaultPath() is also an offender...
	 * https://github.com/mage2pro/portal/blob/0.4.4/Plugin/Store/Model/PathConfig.php#L7-L17
	 * @used-by _a()
	 * @used-by _font()
	 * @used-by _matrix()
	 * @used-by v()
	 * @used-by \Df\Config\Source\WaitPeriodType::calculate()
	 * @param null|string|int|S|Store|array(string, int) $s [optional]
	 * @return null|string|int|S|Store|array(string, int)
	 */
	final function scope($s = null) {return !is_null($s) ? $s : (
		df_is_backend() && df_is_system_config() ? df_scope() : $this->scopeDefault()
	);}

	/**
	 * @used-by b()
	 * @used-by bv()
	 * @used-by csv()
	 * @used-by i()
	 * @used-by json()
	 * @used-by nat()
	 * @used-by nat0()
	 * @used-by nwb()
	 * @used-by nwbn()
	 * @used-by p()
	 * @used-by \CanadaSatellite\Amelia\Settings::url()  (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
	 * @used-by \Df\Amazon\Settings::merchantId()
	 * @used-by \Df\API\Settings::probablyTestable()
	 * @used-by \Df\Config\Source\WaitPeriodType::calculate()
	 * @used-by \Df\Facebook\Settings::appId()
	 * @used-by \Df\GingerPaymentsBase\ConfigProvider::config()
	 * @used-by \Df\GingerPaymentsBase\Settings::btId()
	 * @used-by \Df\GingerPaymentsBase\Settings::domain()
	 * @used-by \Df\Google\Settings::clientId()
	 * @used-by \Df\OAuth\Settings::clientId()
	 * @used-by \Df\OAuth\Settings::refreshToken()
	 * @used-by \Df\Oro\Settings\General::username()
	 * @used-by \Df\Payment\Charge::description()
	 * @used-by \Df\Payment\ConfigProvider::configOptions()
	 * @used-by \Df\Payment\Init\Action::preconfigured()
	 * @used-by \Df\Payment\Method::s()
	 * @used-by \Df\Payment\Settings::applicableForQuoteByMinMaxTotal()
	 * @used-by \Df\Payment\Settings::description()
	 * @used-by \Df\Payment\Settings::messageFailure()
	 * @used-by \Df\Payment\Settings\BankCard::dsd()
	 * @used-by \Df\Payment\Settings\Options::needShow()
	 * @used-by \Df\Payment\Settings\Proxy::host()
	 * @used-by \Df\Payment\Settings\Proxy::port()
	 * @used-by \Df\Payment\Settings\Proxy::username()
	 * @used-by \Df\Payment\Source\Identification::get()
	 * @used-by \Df\Sso\Settings::regCompletionMessage()
	 * @used-by \Df\Sso\Settings\Button::label()
	 * @used-by \Df\Sso\Settings\Button::type()
	 * @used-by \Dfe\AllPay\Settings::descriptionOnKiosk()
	 * @used-by \Dfe\AmazonLogin\Settings\Button::nativeColor()
	 * @used-by \Dfe\AmazonLogin\Settings\Button::nativeSize()
	 * @used-by \Dfe\AmazonLogin\Settings\Button::nativeType()
	 * @used-by \Dfe\AmazonLogin\Settings\Credentials::id()
	 * @used-by \Dfe\BlackbaudNetCommunity\Settings::url()
	 * @used-by \Dfe\CheckoutCom\Settings::actionDesired()
	 * @used-by \Dfe\CheckoutCom\Settings::prefill()
	 * @used-by \Dfe\CheckoutCom\Settings::prefill()
	 * @used-by \Dfe\Customer\Settings\Address::telephone()
	 * @used-by \Dfe\Dynamics365\Settings\General::url()
	 * @used-by \Dfe\Dynamics365\Settings\General\OAuth::url_auth()
	 * @used-by \Dfe\Dynamics365\Settings\General\OAuth::url_token()
	 * @used-by \Dfe\Dynamics365\Settings\Products::priceList()
	 * @used-by \Dfe\FacebookLogin\Settings\Button::nativeSize()
	 * @used-by \Dfe\Frontend\Settings\ProductView\Sku::label()
	 * @used-by \Dfe\Frontend\Settings\ProductView\Sku::labelSuffix()
	 * @used-by \Dfe\Frontend\Settings\ProductView\Sku::needHideFor()
	 * @used-by \Dfe\Frontend\Settings\ProductView\StockStatus::needHideFor()
	 * @used-by \Dfe\Moip\Settings\Boleto::instructions()
	 * @used-by \Dfe\Paymill\Settings::prefill()
	 * @used-by \Dfe\Portal\Settings\General::moduleName()
	 * @used-by \Dfe\Robokassa\Test\Basic::t04()
	 * @used-by \Dfe\Salesforce\Settings\General::domain()
	 * @used-by \Dfe\SecurePay\Settings::forceResult()
	 * @used-by \Dfe\SecurePay\Settings::merchantID_3DS()
	 * @used-by \Dfe\SMTP\Settings::service()
	 * @used-by \Dfe\SMTP\Settings\Mailgun::login()
	 * @used-by \Dfe\TBCBank\Settings::certificate()
	 * @used-by \Dfe\TwitterTimeline\Settings::html()
	 * @used-by \Dfe\ZohoCRM\Settings::domain()
	 * @used-by \Inkifi\Mediaclip\Settings::id()
	 * @used-by \Inkifi\Mediaclip\Settings::key()
	 * @used-by \Inkifi\Mediaclip\Settings::url()
	 * @used-by \Inkifi\Pwinty\Settings::id()
	 * @used-by \Inkifi\Pwinty\Settings::key()
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store|array(string, int) $s [optional]
	 * @param mixed|callable $d [optional]
	 * @return array|string|null|mixed
	 */
	final function v($k = null, $s = null, $d = null) {return df_cfg(
		$this->prefix() . '/' . self::phpNameToKey($k ?: df_caller_f()), $this->scope($s), $d
	);}

	/**
	 * 2015-12-30
	 * @used-by \Dfe\AllPay\InstallmentSales\Settings::plans()
	 * @used-by \Dfe\CurrencyFormat\Settings::get()
	 * @used-by \Dfe\Sift\Settings::pm()
	 * @used-by \Doormall\Shipping\Settings::partners()
	 * @param string|null $k [optional]
	 * @param string $itemClass
	 * @param null|string|int|S|Store $s [optional]
	 * @return ConfigA
	 */
	final protected function _a($itemClass, $k = null, $s = null) {return dfcf(
		function($itemClass, $k, $s) {return
			ConfigA::i($itemClass, !$this->enable($s) ? [] : $this->json($k, $s))
		;}, [$itemClass, $k ?: df_caller_f(), df_scope_code($this->scope($s))]
	);}

	/**
	 * 2015-12-16
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return Font
	 */
	final protected function _font($k = null, $s = null) {return dfc($this, function($k, $s) {return
		new Font($this->json($k, $s))
	;}, [$k ?: df_caller_f(), df_scope_code($this->scope($s))]);}

	/**
	 * 2016-01-29
	 * @param int $i Номер строки
	 * @param int $j Номер столбца
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @param string|null $d [optonal]
	 * @return Font
	 */
	final protected function _matrix($i, $j, $k = null, $s = null, $d = null) {return
		dfa(dfa(dfc($this, function($k, $s) {return
			$this->json($k, $s)
		;}, [$k ?: df_caller_f(), df_scope_code($this->scope($s))]), $i, []), $j, $d)
	;}

	/**
	 * 2016-07-31
	 * 2016-08-04
	 * Ошибочно писать здесь self::s($class)
	 * потому что класс ребёнка не обязательно должен быть наследником класса родителя:
	 * ему достаточно быть наследником @see \Df\Config\Settings
	 * @used-by \Dfe\AllPay\Settings::installmentSales()
	 * @param string $c
	 * @return Settings
	 */
	final protected function child($c) {return self::s($this->_scope, $c);}

	/**
	 * 2016-05-13
	 * 2016-06-09
	 * Если опция не задана, но метод возвращает «да».
	 * Если опция задана, то смотрим уже тип ограничения: белый или чёрный список.
	 * @param string $suf
	 * @param string $v
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return bool
	 */
	final protected function nwb($suf, $v, $k = null, $s = null) {return NWB::is(
		$this->v($k = $k ?: df_caller_f(), $s), $v, $this->csv("{$k}_$suf", $s)
	);}

	/**
	 * 2016-06-09
	 * Если опция не задана, но метод возвращает «нет».
	 * Если опция задана, то смотрим уже тип ограничения: белый или чёрный список.
	 * 2017-10-20 $v can be null in the @see \Df\Payment\Settings\_3DS::enable_() case.
	 * @used-by \Df\Payment\Settings\_3DS::countries()
	 * @param string $suf
	 * @param string|null $v
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return bool
	 */
	final protected function nwbn($suf, $v, $k = null, $s = null) {return
		!is_null($v) && NWB::isNegative($this->v($k = $k ?: df_caller_f(), $s), $v, $this->csv("{$k}_$suf", $s))
	;}

	/**
	 * 2017-03-27
	 * @used-by scope()
	 * @see \Df\Payment\Settings::scopeDefault()
	 * @see \Df\Payment\Settings\_3DS::scopeDefault()
	 * @return int|S|Store|null|string
	 */
	protected function scopeDefault() {return $this->_scope;}

	/**
	 * 2019-01-12
	 * @used-by s()
	 * @see \Df\Config\Settings\Configurable::__construct()
	 * @see \Df\Payment\Settings::__construct()
	 * @see \Df\Payment\Settings\_3DS::__construct()
	 * @see \Df\Shipping\Settings::__construct()
	 * @param int|S|Store|null|string $s
	 */
	private function __construct($s = null) {$this->_scope = $s;}

	/**
	 * 2015-12-16
	 * @param string|null $k [optional]
	 * @param null|string|int|S|Store $s [optional]
	 * @return mixed[]
	 */
	private function json($k = null, $s = null) {return df_eta(@df_json_decode($this->v($k ?: df_caller_f(), $s)));}

	/**
	 * 2019-01-11
	 * @used-by child()
	 * @used-by scopeDefault()
	 * @var int|S|Store|null|string
	 */
	private $_scope;

	/**
	 * 2016-08-04
	 * 2016-11-25
	 * Замечание №1.
	 * Отныне метод возвращает класс не обязательно из базовой папки (например, \Df\Sso\Settings),
	 * а из папки с тем же окончанием, что и у вызываемого класса.
	 * Например, \Df\Sso\Settings\Button::convention() будет искать класс в папке Settings\Button
	 * модуля, к которому относится класс $c.
	 * Замечание №2.
	 * Используем 2 уровня кэширования, и оба они важны:
	 * 1) Кэширование self::s() приводит к тому, что вызов s() непосредственно для класса
	 * возвращает тот же объект, что и вызов convention(). Это очень важно.
	 * 2) Кэширование dfcf() позволяет нам не рассчитывать df_con_heir()
	 * при каждом вызове convention().
	 * 2017-03-27 Заменил @see df_con_heir() на df_con_hier()
	 * @used-by dfs()
	 * @used-by \Df\Sso\Button::sModule()
	 * @param object|string $c
	 * @return self
	 */
	final static function convention($c) {return dfcf(function($c, $def) {return self::s(null, df_con_hier(
		$c, $def
	));}, [df_cts($c), static::class]);}

	/**
	 * 2016-07-12 http://php.net/manual/function.get-called-class.php#115790
	 * @used-by child()
	 * @used-by convention()
	 * @used-by ikf_pw_api()
	 * @used-by \CanadaSatellite\Amelia\Block::_toHtml() (canadasatellite.ca, https://github.com/canadasatellite-ca/amelia/issues/1)
	 * @used-by \Dfe\Sift\API\B\Event::p()
	 * @used-by \Dfe\Sift\API\Facade\GetDecisions::path()
	 * @used-by \Dfe\Sift\Controller\Index\Index::checkSignature()
	 * @used-by \Dfe\Sift\Js::_toHtml()
	 * @used-by \Dfe\Sift\Observer::f()
	 * @used-by \Dfe\Sift\Payload\Payment::p()
	 * @used-by \Dfe\Sift\Test\CaseT\API\Event::t01_add_item_to_cart()
	 * @used-by \Inkifi\Mediaclip\API\Client::s()
	 * @used-by \Inkifi\Pwinty\API\Client::s()
	 * @used-by \Inkifi\Pwinty\AvailableForDownload::_p()
	 * @used-by \Mangoit\MediaclipHub\Helper\Data::CheckoutWithSingleProduct()
	 * @used-by \Mangoit\MediaclipHub\Helper\Data::GetStoreAuthorizationHeader()
	 * @param Store|int|null $s [optional]
	 * @param string $c [optional]
	 * @return self
	 */
	static function s($s = null, $c = null) {return dfcf(
		function($s, $c) {return new $c($s);}, [df_store($s), $c ?: static::class]
	);}

	/**
	 * 2016-12-24
	 * From now on, keys can have a leading digit (e.g.: «3DS»).
	 * PHP methods for such keys should be prefixed with «_».
	 * E.g., the @see \Dfe\Omise\Settings::_3DS() method handles the «test3DS» and «live3DS» keys.
	 * @used-by v()
	 * @used-by \Df\API\Settings::testableGeneric()
	 * @param string $name
	 * @return string
	 */
	final protected static function phpNameToKey($name) {return df_trim_left($name, '_');}
}