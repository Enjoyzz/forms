[![Code Intelligence Status](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/code-intelligence.svg?b=3.x)](https://scrutinizer-ci.com/code-intelligence)
[![Code Coverage](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/coverage.png?b=3.x)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=3.x)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/quality-score.png?b=3.x)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=3.x)
![php 8.0](https://github.com/Enjoyzz/forms/workflows/php%208.0%20dev%205.x/badge.svg)

### Init form
```php
use Enjoys\Forms\Form;
$form = new Form();
$form = new Form('get');
$form = new Form('get', 'action.php');
```

### Added Elements
```php
use Enjoys\Forms\Form;
use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Elements;

$form = new Form();

$form
    ->text('name', 'label')
    ->setAttr(AttributeFactory::create('id', uniqid()))
    ->addRule(Rules::REQUIRED)
;

//or
$textElement = (new Elements\Text('name', 'label'))
    ->setAttr(AttributeFactory::create('id', uniqid()))
    ->addRule(Rules::REQUIRED)
;
$form->addElement($textElement);
```

```php
\Enjoys\Forms\AttributeFactory::create('name', 'value') // return AttributeInterface (string: name="value")
\Enjoys\Forms\AttributeFactory::createFromArray([
    'name' => 'value',
    'id' => 'my-id',
]) // return AttributeInterface[] (string: [name="value", id="my-id"])
```


### Run built-in server for view example
```shell
php -S localhost:8000 -t ./example .route
```
