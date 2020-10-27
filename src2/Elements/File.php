<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Elements;

/**
 * Description of File
 *
 * @author deadl
 */
class File extends \Enjoys\Forms2\Element
{

    private string $type = 'file';
    protected $needParent = true;

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
//        $this->addRule(Rules::UPLOAD, null, [
//            'system'
//        ]);
        //  parent::setAttribute('method', 'dddd');
        //$this->setMaxFileSize(self::phpIniSize2bytes(ini_get('upload_max_filesize')), false);
    }

    public function prepare()
    {
        $this->getParent()->setAttributes([
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ]);
        $this->setMaxFileSize(\iniSize2bytes(ini_get('upload_max_filesize')));

       // $this->unsetParent();
    }

    /**
     * 
     * @param int $bytes
     * @return $this
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->getParent()->hidden('MAX_FILE_SIZE', (string) $bytes);
        return $this;
    }

    public function baseHtml()
    {
        return "<input type=\"{$this->type}\"{$this->getAttributes()}>";
    }
    /**
     *
     * @param string $ruleName
     * @param string $message
     * @param array $params
     * @return $this
     */
//    public function addRule(string $ruleName, ?string $message = null, $params = [])
//    {
//        if (\strtolower($ruleName) !== \strtolower(Rules::UPLOAD)) {
//            throw new ExceptionRule(
//                    \sprintf("К элементу [%s] можно подключить только правило: [%s]", __CLASS__, Rules::UPLOAD)
//            );
//        }
//        return parent::addRule($ruleName, $message, $params);
//    }
////    


}
