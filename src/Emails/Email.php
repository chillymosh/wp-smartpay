<?php

namespace SmartPay\Emails;

use SmartPay\Payments\SmartPay_Payment;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class Email
{
    /** Holds the from name **/
    private $from_name = '';

    /** Holds the from email **/
    private $from_email = '';

    /** Holds the content type **/
    private $content_type = 'text/html';

    /** Holds the content type is html **/
    private $html = true;

    /** Holds the headers **/
    private $headers = '';

    /** The single instance of this class **/
    private static $instance = null;

    /**
     * Construct Email class.
     *
     * @since x.x.x
     * @access private
     */
    private function __construct()
    {
        add_action('phpmailer_init', [$this, 'mailtrap']);
    }

    /**
     * Main Email Instance.
     *
     * Ensures that only one instance of Email exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since x.x.x
     * @return object|Email
     * @access public
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Actions)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Mailtrap Config
    public function mailtrap($phpmailer)
    {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '0f2bafb11669af';
        $phpmailer->Password = '6379f0acbb154e';
    }


    /**
     * Get the email from name
     *
     * @since x.x.x
     */
    public function get_from_name()
    {
        if (!$this->from_name) {
            $this->from_name = smartpay_get_option('from_name', get_bloginfo('name'));
        }

        return apply_filters('smartpay_email_from_name', wp_specialchars_decode($this->from_name), $this);
    }

    /**
     * Get the email from address
     *
     * @since x.x.x
     */
    public function get_from_email()
    {
        if (!$this->from_email) {
            $this->from_email = smartpay_get_option('from_email');
        }

        if (empty($this->from_email) || !is_email($this->from_email)) {
            $this->from_email = get_option('admin_email');
        }

        return apply_filters('smartpay_email_from_email', $this->from_email, $this);
    }

    /**
     * Get the email content type
     *
     * @since x.x.x
     */
    public function get_content_type()
    {
        if (!$this->content_type && $this->html) {
            $this->content_type = 'text/html';
        } else {
            $this->content_type = 'text/plain';
        }

        return apply_filters('smartpay_email_content_type', $this->content_type, $this);
    }

    /**
     * Get the email headers
     *
     * @since x.x.x
     */
    public function get_headers()
    {
        if (!$this->headers) {
            $this->headers  = "From: {$this->get_from_name()} <{$this->get_from_email()}>\r\n";
            $this->headers .= "Reply-To: {$this->get_from_email()}\r\n";
            $this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";
        }

        return apply_filters('smartpay_email_headers', $this->headers, $this);
    }

    /**
     * Email the download link(s) and payment confirmation to user
     *
     * @since x.x.x
     *
     * @return void
     */
    public function send_purchase_receipt($payment_id)
    {
        if (!$payment_id) return;

        $payment = new SmartPay_Payment(absint($payment_id));

        $to_email = $payment->email;
        $to_email = 'alaminfirdows@gmail.com';

        // Email Subject
        $subject      = smartpay_get_option('purchase_email_subject', __('Purchase Receipt', 'smartpay'));
        $subject      = wp_specialchars_decode($subject);

        // Heading
        $heading      = smartpay_get_option('purchase_email_heading', __('Purchase Receipt', 'smartpay'));

        // $attachments  = apply_filters('smartpay_receipt_attachments', array(), $payment_id, $payment_data);

        // Email body
        $body      = 'Email body here!';

        $email = new SmartPay_Email;
        $email->to_email    = $to_email;
        $email->subject     = $subject;
        $email->body        = $body;
        $email->headers     = $this->get_headers();
        $email->attachments = '';

        var_dump($email->send());
    }
}