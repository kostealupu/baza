<?php
/**
 * 2016-12-01
 * @used-by \Df\Sso\CustomerReturn::mc()
 * @param string|array(string|mixed)|null ...$cs
 * @return string
 */
function df_db_or(...$cs) {return implode(' OR ', array_map(function($c) {return implode(
	!is_array($c) ? $c : df_db_quote_into($c[0], $c[1]), ['(', ')']
);}, df_clean($cs)));}

/**
 * 2015-04-13
 * @used-by df_fetch()
 * @used-by df_fetch_col()
 * @used-by df_fetch_col_max()
 * @used-by df_table_delete()
 * @param int|string|int[]|string[] $v
 * @param bool $not [optional]
 * @return string
 */
function df_sql_predicate_simple($v, $not = false) {return
	is_array($v) ? ($not ? 'NOT IN (?)' : 'IN (?)') : ($not ? '<> ?' : '= ?')
;}