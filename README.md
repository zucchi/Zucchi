# Zucchi Framework Extensions


Custom extensions and additions to Zend Framework 2

**This Library uses PHP 5.4 features**

## Components

*    Controller - Common controller features
*    DateTime - Custom Date/Time objects with pre defined __toString
*    Debug - Debug utilities
*    Event - Event Tools and traits
*    Form - Form factory with custom hydration
*    Image - Generic representation of an Image
*    ServiceManager - Service Manager Tools and Traits
*    Traits - A helper to get the traits of all ancestors
*    View - Custom Helpers and strategies


## Request Parser Trait

This trait introduces consistent parsing of parameters in a query string that can then be consumed by appropriate Zucchi Query builder classes

Simple query
<pre>
?where[forename][value]=john
&where[forname][operator]=fuzzy
</pre>

Complex nexted where query without explicit field definitions
<pre>
?where[mode]=and
&where[expressions][0][mode]=or
&where[expressions][0][forename][value]=john
&where[expressions][0][forename][operator]=fuzzy
&where[expressions][0][surname][value]=john
&where[expressions][0][surname][operator]=fuzzy
&where[expressions][0][expressions][0][mode]=and
&where[expressions][0][expressions][0][email][value]=john
&where[expressions][0][expressions][0][email][operator]=fuzzy
&where[expressions][0][expressions][0][username][value]=john
&where[expressions][0][expressions][0][username][operator]=fuzzy
&where[expressions][1][id][value][0]=1
&where[expressions][1][id][operator]=nin
</pre>

Complex nexted where query with explicit field definitions to allow use of "mode" & "expressions" as a field
<pre>
?where[mode]=and
&where[expressions][0][mode]=and
&where[expressions][0][fields][forename][value]=john
&where[expressions][0][fields][forename][operator]=fuzzy
&where[expressions][0][fields][mode][value]=active
&where[expressions][0][fields][surname][operator]=eq
</pre>

Available operators and modes include 
<pre>
    protected $requestOperators = array(
        'is'    => 'is',
        'eq'    => '=',
        'gt'    => '>',
        'gte'   => '>=',
        'lt'    => '<',
        'lte'   => '<=',
        'neq'   => '!=',
        'in'   => 'in',
        'nin'   => 'not in',
        'between' => 'between',
        'fuzzy' => 'like',
        'regex' => 'regexp',
    );

    protected $requestModes = array(
        'or' => 'or',
        'and' => 'and',
    );
</pre>
