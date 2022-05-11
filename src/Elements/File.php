<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Descriptionable;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;

use function iniSize2bytes;

class File extends Element implements Ruleable, Descriptionable
{
    use Traits\Description;
    use Traits\Rules {
        addRule as private parentAddRule;
    }

    protected string $type = 'file';

    /**
     * @throws ExceptionRule
     */
    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        $this->addRule(Rules::UPLOAD, [
            'system'
        ]);
    }
    public function setMultiple(): self
    {
        $this->setAttribute(AttributeFactory::create('multiple'));
        return $this;
    }

    public function addAccept(string $accept): self
    {
        $attribute = $this->getAttribute('accept');
        if ($attribute === null) {
            $attribute = AttributeFactory::create('accept');
            $attribute->setMultiple(true);
            $attribute->setSeparator(',');
            $this->setAttribute($attribute);
        }

        $attribute->add($accept);
        return $this;
    }

    /**
     * @param string[] $accepts
     * @return $this
     */
    public function setAccepts(array $accepts): self
    {
        $this->removeAttribute('accept');
        foreach ($accepts as $accept) {
            $this->addAccept($accept);
        }
        return $this;
    }

    public function prepare()
    {
        $this->getForm()?->setAttribute(AttributeFactory::create('enctype', 'multipart/form-data'));
        $this->getForm()?->setMethod('post');
        $this->setMaxFileSize(iniSize2bytes(ini_get('upload_max_filesize')));
    }


    public function setMaxFileSize(int $bytes): self
    {
        $this->getForm()?->hidden('MAX_FILE_SIZE', (string) $bytes);
        return $this;
    }


    /**
     * @throws ExceptionRule
     */
    public function addRule(string $ruleClass, mixed ...$params): File
    {
        if ($ruleClass !== Rules::UPLOAD) {
            throw new ExceptionRule(
                \sprintf("К элементу [%s] можно подключить только правило: [%s]", __CLASS__, Rules::UPLOAD)
            );
        }
        return $this->parentAddRule($ruleClass, ...$params);
    }
}
