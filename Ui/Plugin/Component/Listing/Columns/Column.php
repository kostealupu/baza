<?php
namespace Df\Ui\Plugin\Component\Listing\Columns;
use Magento\Ui\Component\Listing\Columns\Column as Sb;
# 2016-07-28
class Column {
	/**
	 * 2016-07-28 Цель плагина — возбуждение события «df_column__prepare».
	 * @see \Magento\Ui\Component\Listing\Columns\Column::prepare()
	 * @param Sb $sb
	 */
	function beforePrepare(Sb $sb) {df_dispatch('df_column__prepare', [self::COLUMN => $sb]);}

	/**
	 * 2018-07-28
	 * @used-by beforePrepare()
	 * @used-by \Df\Payment\Observer\ColumnPrepare::execute()
	 */
	const COLUMN = 'column';
}