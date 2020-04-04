# Compolomus IniObject

[![License](https://poser.pugx.org/compolomus/IniObject/license)](https://packagist.org/packages/compolomus/IniObject)

[![Build Status](https://scrutinizer-ci.com/g/Compolomus/IniObject/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/IniObject/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Compolomus/IniObject/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/IniObject/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Compolomus/IniObject/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/IniObject/?branch=master)
[![Code Climate](https://codeclimate.com/github/Compolomus/IniObject/badges/gpa.svg)](https://codeclimate.com/github/Compolomus/IniObject)
[![Downloads](https://poser.pugx.org/compolomus/IniObject/downloads)](https://packagist.org/packages/compolomus/IniObject)

# Install:

composer require compolomus/IniObject

# Usage:

```php

use Compolomus\IniObject\IniObject;

require __DIR__ . '/vendor/autoload.php';

$json = '{"test":{"param1":1,"param2":2},"test2":{"param3":3,"param4":4}}';

$array = json_decode($json, true); // convert to array

$object = new IniObject(
    /*
     If file exists, a load file, else set name to file save
    */
    'test.ini', // null default
    /*
     Array params to preload values
    */
    $array,
    /*
     Config params or default config (override protected property)
    */
    [
        'strict'    => false,
        'overwrite' => true,
    ]
);

/*

[test]

param1 = 1
param2 = 2

[test2]

param3 = 3
param4 = 4
*/
echo $object; // ini file data

/*
 Get section params
*/

$params = $object->getSection('test')->toArray();

echo '<pre>' . print_r($params, true) . '</pre>';
/*
 Array
(
    [param1] => 1
    [param2] => 2
)
*/

/*
 Get param by name
*/

$param = $object->getSection('test')->getParam('param1'); // 1

```
**More features see in tests**
