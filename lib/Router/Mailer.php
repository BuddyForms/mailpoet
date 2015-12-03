<?php
namespace MailPoet\Router;

use MailPoet\Models\Setting;

require_once(ABSPATH . 'wp-includes/pluggable.php');

if(!defined('ABSPATH')) exit;

class Mailer {
  function __construct() {
    list($this->fromName, $this->fromEmail) = $this->getSetting('sender');
    $this->mailer = $this->getSetting('mailer');
    $this->from = sprintf('%s <%s>', $this->fromName, $this->fromEmail);
  }

  function send($newsletter, $subscriber) {
    $subscriber = $this->transformSubscriber($subscriber);
    $mailer = $this->buildMailer();
    return $mailer->send($newsletter, $subscriber);
  }

  function buildMailer() {
    switch($this->mailer['method']) {
      case 'AmazonSES':
        $mailer = new $this->mailer['class'](
          $this->mailer['region'],
          $this->mailer['access_key'],
          $this->mailer['secret_key'],
          $this->from
        );
        break;
      case 'ElasticEmail':
        $mailer = new $this->mailer['class'](
          $this->mailer['api_key'],
          $this->fromEmail, $this->fromName
        );
        break;
      case 'MailGun':
        $mailer = new $this->mailer['class'](
          $this->mailer['domain'],
          $this->mailer['api_key'],
          $this->from
        );
        break;
      case 'MailPoet':
        $mailer = new $this->mailer['class'](
          $this->mailer['api_key'],
          $this->fromEmail,
          $this->fromName
        );
        break;
      case 'Mandrill':
        $mailer = new $this->mailer['class'](
          $this->mailer['api_key'],
          $this->fromEmail, $this->fromName
        );
        break;
      case 'SendGrid':
        $mailer = new $this->mailer['class'](
          $this->mailer['api_key'],
          $this->fromEmail,
          $this->fromName
        );
        break;
      case 'SMTP':
        $mailer = new $this->mailer['class'](
          $this->mailer['host'],
          $this->mailer['port'],
          $this->mailer['authentication'],
          $this->mailer['encryption'],
          $this->fromEmail,
          $this->fromName);
        break;
    }
    return $mailer;
  }

  function transformSubscriber($subscriber) {
    if(!is_array($subscriber)) return $subscriber;
    $first_name = (isset($subscriber['first_name'])) ? $subscriber['first_name'] : '';
    $last_name = (isset($subscriber['last_name'])) ? $subscriber['last_name'] : '';
    if(!$first_name && !$last_name) return $subscriber['email'];
    $subscriber = sprintf('%s %s <%s>', $first_name, $last_name, $subscriber['email']);
    $subscriber = trim(preg_replace('!\s\s+!', ' ', $subscriber));
    return $subscriber;
  }

  function getSetting($setting) {
    switch($setting) {
      case 'mailer':
        // TODO: remove
        /*      $mailers = array(
                array(
                  'method' => 'AmazonSES',
                  'type' => 'API',
                  'access_key' => 'AKIAJM6Y5HMGXBLDNSRA',
                  'secret_key' => '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',
                  'region' => 'us-east-1'
                ),
                array(
                  'method' => 'ElasticEmail',
                  'type' => 'API',
                  'api_key' => '997f1f7f-41de-4d7f-a8cb-86c8481370fa'
                ),
                array(
                  'method' => 'MailGun',
                  'type' => 'API',
                  'api_key' => 'key-6cf5g5qjzenk-7nodj44gdt8phe6vam2',
                  'domain' => 'mrcasual.com'
                ),
                array(
                  'method' => 'MailPoet',
                  'api_key' => 'dhNSqj1XHkVltIliyQDvMiKzQShOA5rs0m_DdRUVZHU'
                ),
                array(
                  'method' => 'Mandrill',
                  'type' => 'API',
                  'api_key' => '692ys1B7REEoZN7R-dYwNA'
                ),
                array(
                  'method' => 'SendGrid',
                  'type' => 'API',
                  'api_key' => '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'
                ),
                array(
                  'method' => 'SMTP',
                  'host' => 'email-smtp.us-west-2.amazonaws.com',
                  'port' => 587,
                  'authentication' => array(
                    'login' => 'AKIAIGPBLH6JWG5VCBQQ',
                    'password' => 'AudVHXHaYkvr54veCzqiqOxDiMMyfQW3/V6F1tYzGXY3'
                  ),
                  'encryption' => 'tls'
                ),
                array(
                  'method' => 'WPMail'
                )
              );*/
        $mailer = Setting::getValue('mta', null);
        if(!$mailer) throw new \Exception('Mailing method has not been configured.');
        $mailer['class'] = 'MailPoet\\Mailer\\' .
          ((isset($mailer['type'])) ?
            $mailer['type'] . '\\' . $mailer['method'] :
            $mailer['method']
          );
        return $mailer;
        break;;
      case 'sender':
        $sender = Setting::getValue($setting, null);
        return array($sender['name'], $sender['address']);
        break;
      default:
        return Setting::getValue($setting, null);
        break;
    }
  }
}