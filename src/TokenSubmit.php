<?php

declare(strict_types=1);

namespace Enjoys\Forms;

use Enjoys\Forms\Elements\Hidden;

class TokenSubmit
{
    public function __construct(private Form $form)
    {
    }

    private function getToken(): string
    {
        return md5(json_encode($this->form->getOptions()));
    }

    public function getElement(): Hidden
    {
        return new Hidden(Form::_TOKEN_SUBMIT_, $this->getToken());
    }


    public function validate(): bool
    {
        /** @var array $requestData */
        $requestData = match ($this->form->getMethod()) {
            'GET' => $this->form->getRequest()->getQueryParams(),
            'POST' => $this->form->getRequest()->getParsedBody(),
            default => []
        };
        /** @var string $value */
        $value = \getValueByIndexPath(
            Form::_TOKEN_SUBMIT_,
            $requestData
        );

        return $value == $this->getToken();
    }
}
