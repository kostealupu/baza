<?php
use Df\Core\Helper\Text as T;
use Magento\Framework\Phrase as P;
/**
 * 2015-11-22
 * @param string|string[]|P|P[] $s
 * @return string|string[]
 */
function df_quote_double($s) {return df_t()->quote($s, T::QUOTE__DOUBLE);}

/**
 * @param string|string[]|P|P[] $s
 * @return string|string[]
 */
function df_quote_russian($s) {return df_t()->quote($s, T::QUOTE__RUSSIAN);}

/**
 * @used-by df_ejs()
 * @param string|string[]|P|P[] $s
 * @return string|string[]
 */
function df_quote_single($s) {return df_t()->quote($s, T::QUOTE__SINGLE);}