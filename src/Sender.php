<?php

use Mailgun\Mailgun;

class Sender {

    /** @var $emailer Emailer */
    public $email;

    /** @var $mailgun Mailgun */
    public $mailgun;

    /**
     * Sender constructor.
     * @param Emailer $emailer
     * @param Mailgun $mailgun
     */
    public function __construct(Emailer $emailer, Mailgun $mailgun) {
        $this->email = $emailer;
        $this->mailgun = $mailgun;
    }

}