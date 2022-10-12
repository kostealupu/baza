<?php
/**
 * @uses Mage_Core_Model_Resource::getTableName() не кэширует результаты своей работы,
 * и, глядя на реализацию @see Mage_Core_Model_Resource_Setup::getTable(),
 * которая выполняет кэширование для @see Mage_Core_Model_Resource::getTableName(),
 * я решил сделать аналогичную функцию, только доступную в произвольном контексте.
 * @used-by df_db_column_add()
 * @used-by df_db_column_describe()
 * @used-by df_db_column_drop()
 * @used-by df_db_column_exists()
 * @used-by df_db_column_rename()
 * @used-by df_db_drop_pk()
 * @used-by df_db_from()
 * @used-by df_next_increment()
 * @used-by df_next_increment_set()
 * @used-by df_table_delete()
 * @used-by df_table_exists()
 * @used-by \Aheadworks\AdvancedReviews\Model\ResourceModel\Indexer\Statistics::getSelectForStatisticsData() (canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/81)
 * @used-by \Alignet\Paymecheckout\Model\Client\Classic\Order\DataGetter::userCodePayme() (innomuebles.com, https://github.com/innomuebles/m2/issues/17)
 * @used-by \Df\Customer\AddAttribute\Customer::p()
 * @used-by \Df\InventoryCatalog\Plugin\Model\ResourceModel\AddStockDataToCollection::aroundExecute()
 * @used-by \Dfe\Markdown\DbRecord::__construct()
 * @used-by \KingPalm\B2B\Setup\UpgradeData::_process()
 * @param string|string[] $n
 * @return string
 */
function df_table($n) {return dfcf(function($n) {return df_db_resource()->getTableName($n);}, [$n]);}

/**
 * 2015-04-12
 * @used-by df_table_delete_not()
 * @param string $t
 * @param string $columnName
 * @param int|string|int[]|string[] $values
 * @param bool $not [optional]
 */
function df_table_delete($t, $columnName, $values, $not = false) {
	$condition = df_sql_predicate_simple($values, $not); /** @var string $condition */
	df_conn()->delete(df_table($t), ["{$columnName} {$condition}" => $values]);
}

/**
 * 2015-04-12
 * 2019-01-12 It is never used.
 * @param string $t
 * @param string $column
 * @param int|string|int[]|string[] $values
 */
function df_table_delete_not($t, $column, $values) {df_table_delete($t, $column, $values, $not = true);}

/**
 * 2019-11-30
 * @used-by \Justuno\M2\Setup\UpgradeSchema::tr()
 * @param string $t
 * @return bool
 */
function df_table_exists($t) {return df_conn()->isTableExists(df_table($t));}