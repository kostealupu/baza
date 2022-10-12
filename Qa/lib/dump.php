<?php
use Df\Qa\Dumper;

/**
 * Обратите внимание, что мы намеренно не используем для @uses Df_Core_Dumper
 * объект-одиночку, потому что нам надо вести учёт выгруженных объектов,
 * чтобы не попасть в бесконечную рекурсию при циклических ссылках.
 * @see df_type()
 * @used-by df_assert_eq()
 * @used-by df_bool()
 * @used-by df_extend()
 * @used-by df_sentry()
 * @used-by df_type()
 * @used-by dfc()
 * @used-by dfs_con()
 * @used-by \Df\Framework\Form\Element\Text::getValue()
 * @used-by \Df\Geo\Test\Basic::t01()
 * @used-by \Dfe\Dynamics365\Test\OAuth::discovery()
 * @used-by \Dfe\Portal\Test\Basic::t01()
 * @used-by \Dfe\Portal\Test\Basic::t02()
 * @used-by \Dfe\Robokassa\Test\Basic::t01()
 * @used-by \Hotlink\Brightpearl\Model\Api\Transport::_submit() (tradefurniturecompany.co.uk, https://github.com/tradefurniturecompany/site/issues/122)
 * @param \Magento\Framework\DataObject|mixed[]|mixed $v
 * @return string
 */
function df_dump($v) {return Dumper::i()->dump($v);}

/**
 * 2015-04-05
 * @see df_dump()
 * @used-by df_ar()        
 * @used-by df_assert_gd()
 * @used-by df_assert_traversable()
 * @used-by df_customer()
 * @used-by df_oq_currency_c()
 * @used-by df_order()
 * @used-by df_result_s()
 * @used-by dfaf()
 * @used-by dfpex_args()
 * @param mixed $v
 * @return string
 */
function df_type($v) {return is_object($v) ? sprintf('an object: %s', get_class($v), df_dump($v)) : (is_array($v)
	? (10 < ($c = count($v)) ? "«an array of $c elements»" : 'an array: ' . df_dump($v))
	/** 2020-02-04 We should not use @see df_desc() here */
	: (is_null($v) ? '`null`' : sprintf('«%s» (%s)', df_string($v), gettype($v)))
);}