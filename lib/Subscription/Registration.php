<?php
namespace MailPoet\Subscription;
use \MailPoet\Models\Setting;
use \MailPoet\Models\Subscriber;

class Registration {

  static function extendForm() {
    $label = Setting::getValue(
      'subscribe.on_register.label',
      __('Yes, add me to your mailing list.')
    );

    print '<p class="registration-form-mailpoet">
      <label for="mailpoet_subscribe_on_register">
        <input
          type="checkbox"
          id="mailpoet_subscribe_on_register"
          value="1"
          name="mailpoet[subscribe_on_register]"
        />&nbsp;'.esc_attr($label).'
      </label>
    </p>';
  }

  static function onMSRegister($result) {
    if(empty($result['errors']->errors)) {
      if(
        isset($_POST['mailpoet']['subscribe_on_register'])
        && $_POST['mailpoet']['subscribe_on_register']
      ) {
        static::subscribeNewUser(
          $result['user_name'],
          $result['user_email']
        );
      }
    }
    return $result;
  }

  static function onRegister(
    $user_login,
    $user_email = null,
    $errors = null
  ) {
    if(
      isset($_POST['mailpoet']['subscribe_on_register'])
      && $_POST['mailpoet']['subscribe_on_register']
    ) {
      static::subscribeNewUser($user_login, $user_email);
    }
  }

  private static function subscribeNewUser($login, $email) {
    $segment_ids = Setting::getValue('subscribe.on_comment.segments', array());

    if(!empty($segment_ids)) {
      Subscriber::subscribe(
        array(
          'email' => $email,
          'first_name' => $login
        ),
        $segment_ids
      );
    }
  }
}