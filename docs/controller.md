# Controllers

## AbstractRestController

The AbstractRestController provide a small level of abstraction from the default Zend\Mvc\Controller\AbstractRestfulController.
It provide a default output and short circuit for any HTTP methods not implemented by its ancestors. There is also a
method that provides a simplification of nested views an example of which can be seen at
https://github.com/zucchi/ZucchiAdmin/blob/master/src/ZucchiAdmin/Crud/ControllerTrait.php

## Traits

These are reusable traits for use with Controllers in your ZF2 application

### RequestParserTrait

This is a simple trait with just one method

```
protected function parseWhere(Request $request)
```

This accepts a Zend\Http\Request Object and then parses the query string and builds a slightly more usable array
structure that can then be used to build `WHERE` statements for querying a datastore. The output for this method if
directly usable by the ZucchiDoctrine module.

## Plugins

### Messenger

A simple Messenger Plugin for controller to act as a simple container for messages you want to output

```
@todo add example
```

