<?php
namespace Df\SampleData\Model;
use Magento\Framework\Component\ComponentRegistrar as R;
use Magento\Framework\Config\Composer\Package;
use Magento\Framework\Filesystem\Directory\ReadInterface as IRead;
/**
 * 2016-09-03
 * Для устранения сбоя https://mage2.pro/t/2002
 * «Warning: file_get_contents(vendor/mage2pro/core/<...>/composer.json):
 * failed to open stream: No such file or directory
 * in vendor/magento/module-sample-data/Model/ Dependency.php on line 109»
 */
class Dependency extends \Magento\SampleData\Model\Dependency {
	/**
	 * 2020-06-16
	 * @override
	 * @see \Magento\SampleData\Model\Dependency::getSuggestsFromModules()
	 * @used-by \Magento\SampleData\Model\Dependency::getSampleDataPackages()
	 * @return array
	 * @throws \Magento\Framework\Exception\FileSystemException
	 */
	protected function getSuggestsFromModules() {
		$suggests = [];
		foreach (df_component_r()->getPaths(R::MODULE) as $moduleDir) {
			$package = $this->getModuleComposerPackageMy($moduleDir);
			$suggest = json_decode(json_encode($package->get('suggest')), true);
			if (!empty($suggest)) {
				$suggests += $suggest;
			}
		}
		return $suggests;
	}

	/**
	 * 2020-06-16
	 * @used-by getModuleComposerPackageMy()
	 * @param string $moduleDir
	 * @return Package
	 * @throws \Magento\Framework\Exception\FileSystemException
	 */
	private function getModuleComposerPackageParent($moduleDir) {
		foreach ([$moduleDir, $moduleDir . DIRECTORY_SEPARATOR . '..'] as $dir) {/** @var IRead $directory */
			$directory = df_fs_rf()->create($dir);
			if ($directory->isExist('composer.json') && $directory->isReadable('composer.json')) {
				return df_package_new(json_decode($directory->readFile('composer.json')));
			}
		}
		return df_package_new(new \stdClass);
	}

	/**
	 * 2016-09-03 «vendor/mage2pro/core/Backend/composer.json» => «vendor/mage2pro/core/composer.json»
	 * 2020-06-15
	 * The @see \Magento\SampleData\Model\Dependency::getModuleComposerPackage() method became private
	 * since 2017-03-23 by the following commit: https://github.com/magento/magento2/commit/29bc089e
	 * This commit is applied to Magento ≥ 2.3.0.
	 * @see \Magento\SampleData\Model\Dependency::getModuleComposerPackage()
	 * @used-by getSuggestsFromModules()
	 * @param string $f
	 * @return \Magento\Framework\Config\Composer\Package
	 */
	private function getModuleComposerPackageMy($f) {return $this->getModuleComposerPackageParent(
		false === strpos($f, 'mage2pro') || file_exists($f) ? $f : preg_replace(
			'#/mage2pro/core/[^/]+/#', '/mage2pro/core/', df_path_n($f)
		)
	);}
}