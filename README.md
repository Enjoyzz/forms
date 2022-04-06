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

```php
\Enjoys\Forms\AttributeFactory::create('name', 'value') // return AttributeInterface (string: name="value")
\Enjoys\Forms\AttributeFactory::createFromArray([
    'name' => 'value',
    'id' => 'my-id',
]) // return AttributeInterface[] (string: [name="value", id="my-id"])
```