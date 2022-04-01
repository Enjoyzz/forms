<?php


namespace Tests\Enjoys\Forms\Renderer;


class RendererTest 
{

    public function test_consctruct()
    {
         $this->markTestSkipped('переделать тест');
        $renderer = new \Enjoys\Forms\Renderer(
                (new \Enjoys\Forms\Form())->setOption('name','myform')
        );
        $this->assertEquals('myform', $renderer->getForm()->getName());
    }

}
