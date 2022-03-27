<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;
use Enjoys\Forms\Exception\ExceptionRule;
use Enjoys\Forms\Interfaces\Ruled;
use Enjoys\Forms\Rules;
use Enjoys\Forms\Traits;

use function iniSize2bytes;


class File extends Element implements Ruled
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
        $this->addRule(Rules::UPLOAD, null, [
            'system'
        ]);
    }

    public function prepare()
    {
        $this->getForm()->setAttribute('enctype', 'multipart/form-data');
        $this->getForm()->setMethod('post');
        $this->setMaxFileSize(iniSize2bytes(ini_get('upload_max_filesize')));
    }

    /**
     *
     * @param int $bytes
     * @return $this
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->getForm()->hidden('MAX_FILE_SIZE', (string) $bytes);
        return $this;
    }

    /**
     *
     * @param string $ruleName
     * @param string $message
     * @param array $params
     * @return $this
     */
    public function addRule(string $ruleName, ?string $message = null, $params = [])
    {
        if (\strtolower($ruleName) !== \strtolower(Rules::UPLOAD)) {
            throw new ExceptionRule(
                \sprintf("К элементу [%s] можно подключить только правило: [%s]", __CLASS__, Rules::UPLOAD)
            );
        }
        return $this->parentAddRule($ruleName, $message, $params);
    }
}
