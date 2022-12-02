<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\AttributeInterface;
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

    /**
     * @return $this
     */
    public function setAttribute(AttributeInterface $attribute, string $namespace = 'general'): File
    {
        parent::setAttribute($attribute, $namespace);
        $this->isMultiple();
        return $this;
    }

    private function isMultiple(): void
    {
        if ($this->getAttribute('multiple') !== null && !str_ends_with($this->getName(), '[]')) {
            $id = $this->getAttribute('id') ?? AttributeFactory::create('id', $this->getName());
            $this->setName($this->getName() . '[]');
            // т.к. id уже переписан, восстанавливаем его
            $this->setAttribute($id);
        }
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

    public function prepare(): bool
    {
        $this->getForm()?->setAttribute(AttributeFactory::create('enctype', 'multipart/form-data'));
        $this->getForm()?->setMethod('post');
        $this->setMaxFileSize(iniSize2bytes(ini_get('upload_max_filesize')));
        return false;
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
