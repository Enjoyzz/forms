<?php

declare(strict_types=1);

namespace Tests\Enjoys\Forms\Elements;

use Enjoys\Forms\Elements\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function test_create_image_element()
    {
        $img = new Image('foo', 'url_address');
        $this->assertEquals('url_address', $img->getAttr('src')->getValueString());
    }
}
