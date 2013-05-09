<?php
namespace Zucchi\Controller;

use Zend\Http\Request;
/**
 * RequestParserTrait.php
 *
 * @link      http://github.com/zucchifor the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 * @author Matt Cockayne <matt@zucchi.co.uk>
 */
trait RequestParserTrait
{
    /**
     * Processes the WHERE clauses and operators provided in the request into
     * a format usable by the getList() method of the services.
     * @return array
     */
    protected function parseWhere(Request $request)
    {
        $clauses = array(
            'is'    => 'is',
            'eq'    => '=',
            'gt'    => '>',
            'gte'   => '>=',
            'lt'    => '<',
            'lte'   => '<=',
            'neq'   => '!=',
            'between' => 'between',
            'fuzzy' => 'like',
            'regex' => 'regexp',
        );

        $where = $request->getQuery('where', array());

        // loop through and sanitize the where statement
        foreach ($where as $field => &$value) {
            if (is_array($value)) {
                if (isset($value['value']) && is_string($value['value']) && strlen($value['value'])) {
                    if (isset($value['operator']) && isset($clauses[$value['operator']])) {
                        $value['operator'] = $clauses[$value['operator']];
                    } else {
                        $value['operator'] = '=';
                    }
                }
            } else if (is_string($value) && strlen($value)){
                $value = array(
                    'operator'  => '=',
                    'value'     => $value
                );
            }
        }

        return $where;
    }
}
