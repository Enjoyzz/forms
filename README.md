[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FEnjoyzz%2Fforms%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/Enjoyzz/forms/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Enjoyzz/forms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/forms/?branch=master)
![php 8.0](https://github.com/Enjoyzz/forms/workflows/php%208.0/badge.svg)

### Init form
```php
use Enjoys\Forms\Form;
$form = new Form();
//or
$form = new Form('get', 'action.php');
```

### Added Elements

```php
use Enjoys\Forms\Form;
use Enjoys\Forms\Elements;

$form = new Form();
$form->text('name', 'label');

//or
$textElement = new Elements\Text('name', 'label');
$form->addElement($textElement);
```

### List Elements

- button
- captcha (need CaptchaInterface Implement)
- checkbox
- color
- datalist
- date
- datetime
- datetimelocal
- email
- file
- group
- header
- hidden
- html
- image
- month
- number
- password
- radio
- range
- reset
- search
- select
- submit
- tel
- text
- textarea
- time
- url
- week


### Run built-in server for view example
```shell
port=$(shuf -i 2048-65000 -n 1);
php -S localhost:"${port}" -t ./example .route
```
