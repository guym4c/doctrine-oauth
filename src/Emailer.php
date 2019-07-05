<?php

use Mailgun\Mailgun;
use Mailgun\Model\Message\SendResponse;
use Slim\Views;

class Emailer {

    /**
     * @var $mailgun Mailgun
     *
     * Instance of mailgun client that this Emailer will use.
     */
    private $mailgun;

    /** @var $domain string */
    private $domain;

    /**
     * @var $from string
     *
     * Default from address. Must be in $domain.
     */
    private $from;

    /**
     * @var $replyTo string
     *
     * Default reply-to address.
     */
    private $replyTo;

    /** @var $view Slim\Views\Twig */
    private $view;

    /** @var $config array */
    private $config;

    /**
     * Emailer constructor.
     * @param Mailgun         $mailgun
     * @param Slim\Views\Twig $view
     * @param array           $config
     * @param string          $domain
     * @param string          $from
     * @param string          $replyTo
     */
    public function __construct(Mailgun $mailgun, Views\Twig $view, array $config, string $domain, string $from, string $replyTo) {
        $this->mailgun = $mailgun;
        $this->view = $view;
        $this->config = $config;
        $this->domain = $domain;
        $this->from = $from;
        $this->replyTo = $replyTo;
    }

    /**
     * @param array $params Array of email params as accepted by \Mailgun\Api\Message:send().
     * @return SendResponse
     */
    public function send(array $params): SendResponse {

        return $this->mailgun->messages()->send($this->domain,
            array_merge([
                'from'       => $this->from,
                'h:Reply-To' => $this->replyTo,
            ], $params)
        );
    }

    /**
     * Send a simple transactional email with the provided template vars.
     *
     * @param string $to
     * @param string $subject
     * @param string $text     Plaintext email.
     * @param array  $htmlVars HTML email variables.
     * @param array  $params
     * @return SendResponse
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    public function transactional(string $to, string $subject, string $text, array $htmlVars, array $params): SendResponse {
        $email = $this->view->getEnvironment()->load('email/transaction.html.twig');
        $html = $email->render(array_merge([
            'subject'           => $subject,
            'address'           => $this->config['address'],
            'privacyUrl'        => $this->config['privacyUrl'],
            'dataProtectionUrl' => $this->config['dataProtectionUrl'],
        ], $htmlVars));
        return $this->send([
            'to'      => $to,
            'subject' => 'Drafter: ' . $subject,
            'text'    => self::processEmailText($text, $params),
            'html'    => self::processEmailText($html, $params),
        ]);
    }

    private static function processEmailText($body, $params): string {
        foreach ($params as $key => $value) {
            $body = str_replace("%$key%", $value, $body);
        }
        return $body;
    }


}