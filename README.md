[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FEnjoyzz%2Fforms%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/Enjoyzz/forms/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=master)
![php 8.0](https://github.com/Enjoyzz/forms/workflows/php%208.0/badge.svg)

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
