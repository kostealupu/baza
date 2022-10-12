<?php
use Magento\Catalog\Model\Product as P;
use Magento\Framework\App\Filesystem\DirectoryList as DL;

/**
 * 2019-09-20
 * @see df_product_image_url()
 * @used-by \Dfe\Color\Observer\ProductImportBunchSaveAfter::execute()
 * @param P $p
 * @param string|null $type [optional]
 * @param array(string => string) $attrs [optional]
 * @return string
 */
function df_product_image_path(P $p, $type = null, $attrs = []) {return df_media_url2path(df_product_image_url(
	$p, $type, $attrs
));}

/**
 * 2019-08-21
 * @used-by \Dfe\Color\Observer\ProductSaveBefore::execute()
 * @used-by \MageWorx\OptionFeatures\Helper\Data::getThumbImageUrl(canadasatellite.ca, https://github.com/canadasatellite-ca/site/issues/46)
 * @used-by \TFC\Core\Plugin\Catalog\Block\Product\View\GalleryOptions::afterGetOptionsJson()
 * @see df_media_path_absolute()
 * @param string $rel
 * @return string
 */
function df_product_image_path2abs($rel) {return df_cc_path(df_product_images_path(), df_trim_ds_left($rel));}

/**
 * 2020-10-26
 * @used-by \TFC\Image\Command\C1::image()
 * @used-by \TFC\Image\Command\C1::p()
 * @param string $abs
 * @return string
 */
function df_product_image_path2rel($abs) {return df_trim_text_left($abs, df_product_images_path() . '/');}

/**
 * 2020-10-26
 * 2020-11-22 It does not end with `/`.
 * @used-by df_product_image_path2abs()
 * @used-by df_product_image_path2rel()
 * @used-by df_product_images_path_rel()
 * @used-by \TFC\Image\Command\C1::image()
 * @used-by \TFC\Image\Command\C1::images()
 * @used-by \TFC\Image\Command\C3::p()
 * @return string
 */
function df_product_images_path() {return df_path_absolute(DL::MEDIA, 'catalog/product');}

/**
 * 2020-11-22 «pub/media/catalog/product»
 * @used-by \TFC\Image\Command\C3::p()
 * @return string
 */
function df_product_images_path_rel() {return dfcf(function() {return df_path_relative(df_product_images_path());});}

/**
 * 2019-08-23
 * @used-by \Dfe\Color\Observer\ProductSaveBefore::execute()
 * @see df_media_path_absolute()
 * @param string $rel
 * @return string
 */
function df_product_image_tmp_path2abs($rel) {return df_path_absolute(
	DL::MEDIA, 'tmp/catalog/product/' . df_trim_ds_left($rel)
);}