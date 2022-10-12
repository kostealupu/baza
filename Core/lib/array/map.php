<?php
use Df\Core\Exception as DFE;

/**
 * PHP supports global constants since 5.3:
 * http://www.codingforums.com/php/303927-unexpected-t_const-php-version-5-2-17-a.html#post1363452
 * @used-by df_find()
 * @used-by df_map()
 */
const DF_AFTER = 1;

/**
 * PHP supports global constants since 5.3:
 * http://www.codingforums.com/php/303927-unexpected-t_const-php-version-5-2-17-a.html#post1363452
 * @used-by df_find()
 * @used-by df_map()
 * @used-by df_map_k()
 * @used-by df_map_kr()
 * @used-by \Df\Payment\Method::amountFactor()
 */
const DF_BEFORE = -1;

/**
 * 2015-02-07
 * Эта функция аналогична методу @see \Magento\Framework\Data\Collection::walk(),
 * и даже может использоваться вместо @see \Magento\Framework\Data\Collection::walk(),
 * однако, в отличие от @see \Magento\Framework\Data\Collection::walk(),
 * она способна работать не только с коллекцией,
 * но также с массивом объектов и объектом, поддерживающим интерфейс @see \Traversable.
 *
 * 2016-07-31
 * При вызове с 2-мя параметрами эта функция идентична функции @see df_column()
 *
 * 2017-07-09
 * Now the function accepts an array as $object.
 * Even in this case it differs from @see array_column():
 * array_column() misses the keys: https://3v4l.org/llMrL
 * df_column() preserves the keys.
 *
 * @used-by df_category_names()
 * @used-by \Df\Config\Backend\ArrayT::processI()
 * @used-by \Df\Core\GlobalSingletonDestructor::process()
 * @param \Traversable|array(int|string => _DO|array(string => mixed)) $c
 * @param string|callable $f
 * @param mixed ...$p
 * @return mixed[]|string[]
 */
function df_each($c, $f, ...$p) {return df_map(function($v) use($f, $p) {return df_call($v, $f, $p);}, $c);}

/**
 * 2015-02-11
 * Эта функция аналогична @see array_map(), но обладает 3-мя дополнительными возможностями:
 * 1) её можно применять не только к массивам, но и к @see \Traversable.
 * 2) она позволяет удобным способом передавать в $callback дополнительные параметры
 * 3) позволяет передавать в $callback ключи массива
 * до и после основного параметра (элемента массива).
 * 4) позволяет в результате использовать нестандартные ключи
 * Обратите внимание, что
 *		df_map('Df_Cms_Model_ContentsMenu_Applicator::i', $this->getCmsRootNodes())
 * эквивалентно
 *		$this->getCmsRootNodes()->walk('Df_Cms_Model_ContentsMenu_Applicator::i')
 * @used-by df_clean_r()
 * @used-by df_db_credentials()
 * @used-by df_mail()
 * @used-by df_prices()
 * @used-by ikf_api_oi()
 * @used-by \CanadaSatellite\Core\Plugin\Magento\Catalog\Model\Product::afterGetPreconfiguredValues() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/83)
 * @used-by \CanadaSatellite\Core\Plugin\Magento\Sales\Model\Order::afterCanInvoice() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/256)
 * @used-by \Df\Payment\ConfigProvider\GlobalT::icons()
 * @used-by \Dfe\Color\Image::probabilities()
 * @used-by \Dfe\Color\Plugin\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual::afterGetJsonConfig()
 * @used-by \Dfe\Sift\Payload\OQI::p()
 * @used-by \Dfe\YandexKassa\Source\Option::map()
 * @used-by \Inkifi\Map\HTML::tiles()
 * @used-by \Inkifi\Mediaclip\API\Facade\Order\Item::files()
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload\Pureprint::pOI()
 * @used-by \Inkifi\Pwinty\Event::shipments()
 * @used-by \Justuno\M2\Controller\Response\Catalog::execute()
 * @used-by \Justuno\M2\Controller\Response\Inventory::execute()
 * @used-by \Justuno\M2\Inventory\Variants::p()
 * @used-by \Wolf\Filter\Controller\Index\Change::execute()
 * @used-by \Wolf\Filter\Observer\ControllerActionPredispatch::execute()
 * @used-by vendor/mage2pro/color/view/frontend/templates/index.phtml
 * @param array|callable|\Traversable $a1
 * @param array|callable|\Traversable $a2
 * @param mixed|mixed[] $pAppend [optional]
 * @param mixed|mixed[] $pPrepend [optional]
 * @param int $keyPosition [optional]
 * @param bool $returnKey [optional]
 * @return array(int|string => mixed)
 * @throws DFE
 */
function df_map($a1, $a2, $pAppend = [], $pPrepend = [], $keyPosition = 0, $returnKey = false) {
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($a, $f) = dfaf($a1, $a2); /** @var array|\Traversable $a */ /** @var callable $f */
	/** @var array(int|string => mixed) $r */
	if (!$pAppend && !$pPrepend && 0 === $keyPosition && !$returnKey) {
		$r = array_map($f, df_ita($a));
	}
	else {
		$pAppend = df_array($pAppend); $pPrepend = df_array($pPrepend);
		$r = [];
		foreach ($a as $k => $v) {/** @var int|string $k */ /** @var mixed $v */ /** @var mixed[] $primaryArgument */
			switch ($keyPosition) {
				case DF_BEFORE:
					$primaryArgument = [$k, $v];
					break;
				case DF_AFTER:
					$primaryArgument = [$v, $k];
					break;
				default:
					$primaryArgument = [$v];
			}
			$fr = call_user_func_array($f, array_merge($pPrepend, $primaryArgument, $pAppend)); /** @var mixed $fr */
			if (!$returnKey) {
				$r[$k] = $fr;
			}
			else {
				$r[$fr[0]] = $fr[1]; # 2016-10-25 It allows to return custom keys.
			}
		}
	}
	return $r;
}

/**
 * 2016-08-09 Функция принимает аргументы в любом порядке.
 * @used-by df_kv()
 * @used-by df_kv_table()
 * @used-by df_modules_my()
 * @used-by dfe_modules_log()
 * @used-by dfp_methods()
 * @used-by \Df\Payment\ConfigProvider::configOptions()
 * @used-by \Df\Qa\Dumper::dumpArrayElements()
 * @used-by \Df\Qa\Trace\Formatter::p()
 * @used-by \Df\Sentry\Client::send()
 * @used-by \Dfe\PostFinance\Signer::sign()
 * @used-by \Wolf\Filter\Block\Navigation::hDropdowns()
 * @used-by vendor/mage2pro/color/view/frontend/templates/index.phtml
 * @param array|callable|\Traversable $a1
 * @param array|callable|\Traversable $a2
 * @return array(int|string => mixed)
 */
function df_map_k($a1, $a2) {return df_map($a1, $a2, [], [], DF_BEFORE);}

/**
 * 2016-11-08 Функция принимает аргументы в любом порядке.
 * @used-by dfak_transform()
 * @used-by \Df\Config\Source\Block::map()
 * @used-by \Df\Core\Text\Regex::getErrorCodeMap()
 * @used-by \Dfe\Color\Image::labels()
 * @used-by \Dfe\Color\Plugin\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual::afterGetJsonConfig()
 * @used-by \Dfe\Robokassa\Api\Options::p()
 * @used-by \Doormall\Shipping\Partner\Entity::locations()
 * @used-by \Justuno\M2\Catalog\Images::p()
 * @used-by \Justuno\M2\Catalog\Variants::variant()
 * @param array|callable|\Traversable $a1
 * @param array|callable|\Traversable $a2
 * @return array(int|string => mixed)
 * @throws DFE
 */
function df_map_kr($a1, $a2) {return df_map($a1, $a2, [], [], DF_BEFORE, true);}

/**
 * 2016-11-08 Функция принимает аргументы в любом порядке.
 * @used-by df_category_children_map()
 * @used-by df_modules_my()
 * @used-by df_parse_colon()
 * @used-by dfe_packages()
 * @used-by dfe_portal_stripe_customers()
 * @used-by \Df\Config\Source\Block::map()
 * @used-by \Df\Directory\Model\ResourceModel\Country\Collection::mapFrom3To2()
 * @used-by \Df\Directory\Model\ResourceModel\Country\Collection::mapFromCodeToName()
 * @used-by \Df\Framework\Form\Element\Multiselect::getElementHtml()
 * @used-by \Df\Oro\Test\Basic::t02_orders_stripe()
 * @used-by \Df\ZohoBI\Source\Organization::fetch()
 * @used-by \Dfe\PostFinance\Source\Hash\Algorithm::map()
 * @used-by \Dfe\Spryng\Source\Account::fetch()
 * @used-by \Mineralair\Core\Controller\Modal\Index::execute()
 * @used-by \TFC\GoogleShopping\Products::atts() (tradefurniturecompany.co.uk, https://github.com/tradefurniturecompany/google-shopping/issues/1)
 * @param array|callable|\Traversable $a1
 * @param array|callable|\Traversable $a2
 * @return array(int|string => mixed)
 */
function df_map_r($a1, $a2) {return df_map($a1, $a2, [], [], 0, true);}