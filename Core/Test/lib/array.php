<?php
namespace Df\Core\Test\lib;
# 2017-07-13
class arrayT extends \Df\Core\TestCase {
	/** 2017-07-13 */
	function t00() {}

	/** @test 2020-02-05 */
	function t01_dfak_transform() {
		$a = ['promotions' => [['description' => 'Test']]];
		echo df_dump(dfak_prefix($a, '$', true));
	}
}