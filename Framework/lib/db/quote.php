<?php
/**
 * 2016-01-27
 * @used-by df_next_increment_set()
 * @param string $v
 * @return string
 */
function df_db_quote($v) {return df_conn()->quoteIdentifier($v);}

/**
 * @used-by df_db_or()
 * @used-by \Df\Sso\CustomerReturn::mc()
 * @param string $text
 * @param mixed $value
 * @param string|null $type [optional]
 * @param int|null $count [optional]
 * @return string
 */
function df_db_quote_into($text, $value, $type = null, $count = null) {return df_conn()->quoteInto(
	$text, $value, $type, $count
);}