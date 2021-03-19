<?php


namespace SynerBay\Emails\Service;

use ReflectionClass;
use ReflectionException;
use WC_Email;

abstract class AbstractEmail
{
    public string $id;

    protected array $params;

    protected array $attachments = [];

    protected array $headers = [];

    protected array $messageParams = [];

    private WC_Email $WCEmail;

    private ?string $body = '';

    /**
     * AbstractEmail constructor.
     *
     * @info javasolt a paramokat és az msg paramokat kulcsozott tömbben átadni
     *
     * @param array $params
     * @param bool $attachments
     */
    public function __construct(array $params = [], $attachments = false)
    {
        try {
            $this->id = (new ReflectionClass($this))->getShortName();
        } catch (ReflectionException $e) {
            $this->id = get_called_class();
        }

        $this->WCEmail = new WC_Email();
        $this->params = $params;

        if ($attachments) {
            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }

            $this->attachments = $attachments;
        }
    }

    public function setParams(array $params = [])
    {
        $this->params = $params;
    }

    public function send($consigneeName, $consigneeEmailAddress, array $customData = [], $attachments = false): void
    {
        $this->addWPFilters();

        $customData['consigneeName'] = $consigneeName;
        $customData['consigneeEmailAddress'] = $consigneeEmailAddress;

        $message = apply_filters('woocommerce_mail_content', $this->renderBody($customData));

        if ($attachments) {
            $emailAttachments = !is_array($attachments) ? [$attachments] : $attachments;
        } else {
            $emailAttachments = $this->attachments;
        }

        wp_mail($consigneeEmailAddress, $this->getSubject(), $message, $this->buildHeaders(), $emailAttachments);

        $this->removeWPFilters();
    }

    private function addWPFilters()
    {
        add_filter('wp_mail_from', [$this, 'getSenderMailAddress']);
        add_filter('wp_mail_from_name', [$this, 'getSenderName']);
    }

    private function renderBody(array $customData = [])
    {
        $body = $this->body;
        if (empty($body)) {
            $this->body = $body = $this->WCEmail->style_inline($this->getContentHtml());
        }

        foreach ($customData as $replaceKey => $value) {
            $body = str_replace(sprintf('[%s]', $replaceKey), $value, $body);
        }

        return $body;
    }

    public function getContentHtml()
    {
        if (!is_file($this->getTemplateBasePath() . $this->getTemplateFullName())) {
            die('Missing template file!');
        }

        ob_start();

        wc_get_template(
            $this->getTemplateFullName(),
            array_merge(
                [
                    'id'                => $this->id,
                    'email'             => $this,
                    'emailHeading'      => $this->getEmailHead(),
                    'footerPartialFile' => $this->getTemplateBasePath() . 'partials/footer.php',
                    'headerPartialFile' => $this->getTemplateBasePath() . 'partials/header.php',
                ],
                $this->params,
                $this->getMessageParams()
            ),
            'synerbay/',
            $this->getTemplateBasePath());

        return ob_get_clean();
    }

    private function getTemplateBasePath()
    {
        return SYNERBAY_DIR . '/backend/email/template/';
    }

    protected function getTemplateFullName(): string
    {
        return sprintf('%s.php', $this->getTemplateName());
    }

    protected function getTemplateName(): string
    {
        return 'defaultEmailTemplate';
    }

    protected function getEmailHead()
    {
        return $this->getSubject();
    }

    abstract protected function getSubject(): string;

    protected function getMessageParams(): array
    {
        return [];
    }

    private function buildHeaders(): array
    {
        return [sprintf('Content-Type: %s', $this->getContentType())] + $this->headers;
    }

    protected function getContentType(): string
    {
        return 'text/html; charset=UTF-8';
    }

    private function removeWPFilters()
    {
        remove_filter('wp_mail_from', [$this, 'getSenderMailAddress']);
        remove_filter('wp_mail_from_name', [$this, 'getSenderName']);
    }

    public function getSenderName(): string
    {
        return 'SynerBay';
    }

    public function getSenderMailAddress(): string
    {
        return 'noreply@synerbay.com';
    }

    protected function addHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    protected function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}