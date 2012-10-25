<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Filter
 */

namespace Zucchi\Archive\Adapter;

/**
 * Compression interface
 *
 * @category   Zend
 * @package    Zend_Filter
 */
interface AlgorithmInterface
{
/**
     * Returns the set archive
     *
     * @return Archive_Tar
     */
    public function getArchive();

    /**
     * Sets the archive to use for de-/compression
     *
     * @param  string $archive Archive to use
     * @return Tar
     */
    public function setArchive($archive);
    
    /**
     * Returns the set target path
     *
     * @return string
     */
    public function getTarget();

    /**
     * Sets the target path to use
     *
     * @param  string $target
     * @return Tar
     * @throws Exception\InvalidArgumentException if target path does not exist
     */
    public function setTarget($target);
    
    /**
     * Compresses $value with the defined settings
     *
     * @param  string $archive Data to compress
     * @return string The compressed data
     */
    public function compress($archive);

    /**
     * Decompresses $value with the defined settings
     *
     * @param  string $archive Data to decompress
     * @return string The decompressed data
     */
    public function decompress();

    /**
     * get a list of contents for the Archive
     * 
     * @param boolean $normalise normalise the returned values
     * @return 
     */
    public function listContent($normalise = true);
    
    /**
     * get the contents of a specific file from the archive
     * 
     * @param string $archive
     * @param string $file
     * @return string
     */
    public function getFileContents($file);
    
    /**
     * Return the adapter name
     *
     * @return string
     */
    public function toString();
}
