<?php
/**
 * ZucchiModel (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiModel for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Zucchi\Traits;

/**
 * Class TraitUtils
 *
 * Utility class for testing and manipulation of PHP arrays.
 * Declared abstract, as we have no need for instantiation.
 *
 * @author Rick Nicol <rick@zucchi.co.uk>
 */
abstract class TraitsUtils
{
    /**
     * Return an array of all the Traits that a given
     * $class and it's parents use.
     *
     * @param $class
     * @param bool $autoload
     * @return array
     */
    public static function getTraits($class, $autoload = true)
    {
        $traits = array();
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while($class = get_parent_class($class));

        return array_unique($traits);
    }
}