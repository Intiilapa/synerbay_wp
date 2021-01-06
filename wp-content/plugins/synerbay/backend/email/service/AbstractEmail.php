<?php


namespace SynerBay\Emails\Service;


use WC_Email;

abstract class AbstractEmail
{
    public string $id;

    protected array $params;

    protected array $headers = [];

    protected array $messageParams = [];

    private WC_Email $WCEmail;

    /**
     * AbstractEmail constructor.
     *
     * @info javasolt a paramokat és az msg paramokat kulcsozott tömbben átadni
     *
     * @param array $params
     * @throws \ReflectionException
     */
    public function __construct(array $params = [])
    {
        $this->id = (new \ReflectionClass($this))->getShortName();
        $this->WCEmail = new WC_Email();
        $this->params = $params;
    }

    private function addWPFilters()
    {
        add_filter('wp_mail_from', [$this, 'getSenderMailAddress']);
        add_filter('wp_mail_from_name', [$this, 'getSenderName']);
    }

    public function send($consigneeName, $consigneeEmailAddress): void
    {
        $this->addWPFilters();

        $userData = [
            'consigneeName' => $consigneeName,
            'consigneeEmailAddress' => $consigneeEmailAddress,
        ];

        $message = apply_filters( 'woocommerce_mail_content', $this->WCEmail->style_inline( $this->getContentHtml($userData) ) );

        wp_mail($consigneeEmailAddress, $this->getSubject(), $message, $this->buildHeaders());

        $this->removeWPFilters();
    }

    abstract protected function getSubject(): string;

    public function getContentHtml(array $customData = array())
    {
        if (!is_file($this->getTemplateBasePath() . $this->getTemplateFullName())) {
            die('Missing template file!');
        }

        // kell a wc mail-nek, hogy betegye a dolgokat és szép legyen
        $settings = $this->WCEmail->settings;
        $settings['email_type'] = 'html';
        $this->WCEmail->settings = $settings;

        ob_start();

        wc_get_template(
            $this->getTemplateFullName(),
            array_merge(
                [
                    'id' => $this->id . '_' . rand(1, 1000),
                    'email' => $this,
                    'emailHeading' => $this->getEmailHead(),
                    'footerPartialFile' => $this->getTemplateBasePath() . 'partials/footer.php',
                    'headerPartialFile' => $this->getTemplateBasePath() . 'partials/header.php',
                ],
                $this->params,
                $this->getMessageParams(),
                $customData
            ),
            'synerbay/',
            $this->getTemplateBasePath());

        return ob_get_clean();
    }

    protected function getEmailHead()
    {
        return $this->getSubject();
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
        return 'Support';
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