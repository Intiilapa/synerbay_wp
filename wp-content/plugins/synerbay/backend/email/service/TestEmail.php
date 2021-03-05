<?php


namespace SynerBay\Emails\Service;


class TestEmail extends AbstractEmail
{
    protected function getMessageParams(): array
    {
        return [
            'message' => 'heló-sziasss'
        ];
    }

    protected function getTemplateName(): string
    {
        return 'testMail';
    }

    protected function getSubject(): string
    {
        return 'Test e-mail';
    }
}