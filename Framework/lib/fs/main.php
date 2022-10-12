<?php
use Magento\Framework\App\Filesystem\DirectoryList as DL;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\Read as DirectoryRead;
use Magento\Framework\Filesystem\Directory\ReadFactory as DirectoryReadFactory;
use Magento\Framework\Filesystem\Directory\ReadInterface as IDirectoryRead;
use Magento\Framework\Filesystem\Directory\Write as DirectoryWrite;
use Magento\Framework\Filesystem\Directory\WriteInterface as IDirectoryWrite;
use Magento\Framework\Filesystem\File\Read as FileRead;
use Magento\Framework\Filesystem\File\ReadInterface as IFileRead;
use Magento\Framework\Filesystem\File\Write as FileWrite;
use Magento\Framework\Filesystem\File\WriteInterface as IFileWrite;
use Magento\Framework\Filesystem\Io\File as File;
use Magento\Framework\Filesystem\Io\Sftp;

/**
 * 2019-02-24
 * @used-by df_mkdir()
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload\Pureprint::writeLocal()
 * @used-by \KingPalm\Core\Plugin\Aitoc\OrdersExportImport\Model\Processor\Config\ExportConfigMapper::aroundToConfig()
 * @return File
 */
function df_file() {return df_o(File::class);}

/**
 * 2015-12-08
 * @param string $p
 * @param string $relativeFileName
 * @return string
 */
function df_file_read($p, $relativeFileName) {
	$reader = df_fs_r($p); /** @var DirectoryRead|IDirectoryRead $reader */
	$file = $reader->openFile($relativeFileName, 'r'); /** @var IFileRead|FileRead $file */
	try {$r = $file->readAll();} /** @var string $r */
	finally {$file->close();}
	return $r;
}

/**
 * 2015-11-29
 * 2015-11-30
 * @see \Magento\Framework\Filesystem\Directory\Write::openFile() creates the parent directories automatically:
 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Filesystem/Directory/Write.php#L247
 * 2017-04-03 The possible directory types for filesystem operations: https://mage2.pro/t/3591
 * 2018-07-06 The `$append` parameter has been added. 
 * 2020-02-14 If $append is `true`, then $contents will be written on a new line. 
 * @used-by df_report()
 * @used-by df_sync()
 * @used-by \Df\GoogleFont\Font\Variant::ttfPath()
 * @used-by \Df\GoogleFont\Fonts\Png::create()
 * @used-by \Df\GoogleFont\Fonts\Sprite::draw()
 * @param string|string[] $p
 * @param string $contents
 * @param bool $append [optional]
 */
function df_file_write($p, $contents, $append = false) {
	/**
	 * 2017-04-22
	 * С не-строками @uses \Magento\Framework\Filesystem\Driver\File::fileWrite() упадёт,
	 * потому что там стоит код: $lenData = strlen($data);
	 */
	df_param_s($contents, 1);
	/** @var string $type */ /** @var string $relative */
	# 2020-03-02
	# The square bracket syntax for array destructuring assignment (`[…] = […]`) requires PHP ≥ 7.1:
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	list($type, $relative) = is_array($p) ? $p : [DL::ROOT, df_path_relative($p)];
	$writer = df_fs_w($type); /** @var DirectoryWrite|IDirectoryWrite $writer */
	/**
	 * 2018-07-06
	 * Note 1.
	 * https://php.net/manual/function.fopen.php#refsect1-function.fopen-parameters
	 * 'w':	Open for writing only;
	 * 		place the file pointer at the beginning of the file and truncate the file to zero length.
	 * 		If the file does not exist, attempt to create it.
	 * 'a'	Open for writing only; place the file pointer at the end of the file.
	 * 		If the file does not exist, attempt to create it.
	 * 		In this mode, fseek() has no effect, writes are always appended.
	 */
	$file = $writer->openFile($relative, $append ? 'a' : 'w'); /** @var IFileWrite|FileWrite $file */
	/**
	 * 2015-11-29
	 * By analogy with @see \Magento\MediaStorage\Model\File\Storage\Synchronization::synchronize()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/MediaStorage/Model/File/Storage/Synchronization.php#L61-L68
	 * Please note the following comments:
	 *
	 * 1) https://mage2.pro/t/274
	 * «\Magento\MediaStorage\Model\File\Storage\Synchronization::synchronize() wrongly leaves a file in the locked state in case of an exception»
	 *
	 * 2) https://mage2.pro/t/271
	 * «\Magento\MediaStorage\Model\File\Storage\Synchronization::synchronize() suppresses its exceptions for a questionably reason»
	 *
	 * 3) https://mage2.pro/t/272
	 * «\Magento\MediaStorage\Model\File\Storage\Synchronization::synchronize() duplicates the code in the try and catch blocks, propose to use a «finally» block»
	 *
	 * 4) https://mage2.pro/t/273
	 * «\Magento\MediaStorage\Model\File\Storage\Synchronization::synchronize() contains a wrong PHPDoc comment for the $file variable»
	 */
	try {
		$file->lock();
		try {
			/**
			 * 2018-07-06
			 * Note 1. https://stackoverflow.com/a/4857194
			 * Note 2.
			 * @see ftell() and @see \Magento\Framework\Filesystem\File\Read::tell() do not work here
			 * even if the file is opened in the `a+` mode:
			 * https://php.net/manual/function.ftell.php#116885
			 * «When opening a file for reading and writing via fopen('file','a+')
			 * the file pointer should be at the end of the file.
			 * However ftell() returns int(0) even if the file is not empty.»
			 */
			if ($append && 0 !== filesize(BP . "/$relative")) {
				# 2018-07-06 «PHP fwrite new line» https://stackoverflow.com/a/15130410
				$contents = PHP_EOL . $contents;
			}
			$file->write($contents);
		}
		finally {$file->unlock();}
	}
	finally {$file->close();}
}

/**
 * 2015-11-29
 * @used-by df_fs_r()
 * @used-by df_fs_w()
 * @return Filesystem
 */
function df_fs() {return df_o(Filesystem::class);}

/**
 * 2017-04-03 Портировал из РСМ. Никем не используется.
 * @param string $p
 */
function df_fs_delete($p) {File::rmdirRecursive(df_param_sne($p, 0));}

/**
 * 2019-08-23
 * @used-by df_fs_etc()
 * @used-by df_mkdir_log()
 * @return DL
 */
function df_fs_dl() {return df_o(DL::class);}

/**
 * 2015-11-30
 * @used-by df_media_reader()
 * @used-by df_path_relative()
 * @param string $p
 * @return DirectoryRead|IDirectoryRead
 */
function df_fs_r($p) {return df_fs()->getDirectoryRead($p);}

/**
 * 2020-06-16
 * @used-by \Df\SampleData\Model\Dependency::getModuleComposerPackageParent()
 * @return DirectoryReadFactory
 */
function df_fs_rf() {return df_o(DirectoryReadFactory::class);}

/**
 * 2015-11-29
 * 2017-04-03 The possible directory types for filesystem operations: https://mage2.pro/t/3591
 * @used-by df_file_write()
 * @used-by df_media_writer()
 * @used-by df_sync()
 * @param string $type
 * @return DirectoryWrite|IDirectoryWrite
 */
function df_fs_w($type) {return df_fs()->getDirectoryWrite($type);}

/**
 * 2019-02-24
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload::_p()
 * @return Sftp
 */
function df_sftp() {return df_o(Sftp::class);}