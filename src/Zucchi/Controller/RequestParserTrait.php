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
    protected function parseWhere($where = false)
    {
        $operators = array(
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

        $modes = array(
            'or' => 'or',
            'and' => 'and',
        );

        if (is_array($where)) {
            if (array_key_exists('expressions', $where)) {
                $where = $this->parseComplexWhere($where, $operators, $modes);
            } else {
                $where = $this->parseSimpleWhere($where, $operators, $modes);
            }
        } else if (!is_string($where)) {
            $where = false;
        }

        return $where;
    }


    /**
     * @param $where
     * @param $operators
     * @param $modes
     * @return array
     */
    protected function parseComplexWhere($where, $operators, $modes)
    {
        if (array_key_exists('expressions', $where)) {

            if (isset($where['mode']) && isset($modes[$where['mode']])) {
                $where['mode'] = $modes[$where['mode']];
            } else {
                $where['mode'] = $modes['and'];
            }

            foreach ($where['expressions'] as $index => &$expression) {
                if (isset($expression['mode']) && isset($modes[$expression['mode']])) {
                    $expression['mode'] = $modes[$expression['mode']];
                } else {
                    $expression['mode'] = $expression['and'];
                }
                $expression['fields'] = $this->parseSimpleWhere($expression['fields'], $operators, $modes);
            }
        }

        return $where;
    }

    /**
     * @param $where
     * @param $operators
     * @param $modes
     * @return array
     */
    protected function parseSimpleWhere($where, $operators, $modes)
    {
        // loop through and sanitize the where statement
        foreach ($where as $field => &$value) {
            if (is_array($value)) {
                if (isset($value['value']) && is_string($value['value']) && strlen($value['value'])) {
                    if (isset($value['operator']) && isset($operators[$value['operator']])) {
                        $value['operator'] = $operators[$value['operator']];
                    } else {
                        $value['operator'] = $operators['eq'];
                    }

                    if (isset($value['mode']) && isset($modes[$value['mode']])) {
                        $value['mode'] = $modes[$value['mode']];
                    } else {
                        $value['mode'] = $modes['and'];
                    }
                }
            } else if (is_string($value) && strlen($value)) {
                $value = array(
                    'mode' => $modes['and'],
                    'operator' => $operators['eq'],
                    'value' => $value
                );
            }
        }

        return $where;
    }
}
