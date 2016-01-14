<?php
namespace MailPoet\Config;
use \MailPoet\Models\Setting;

class Hooks {
  function __construct() {
  }

  function init() {
    $subscribe_settings = Setting::getValue('subscribe');

    if($subscribe_settings !== null) {
      // Subscribe in comments
      if(
        isset($subscribe_settings['on_comment']['enabled'])
        && $subscribe_settings['on_comment']['enabled']
      ) {
        add_action(
          'comment_form_after_fields',
          '\MailPoet\Subscription\Comment::extendForm'
        );

        add_action(
          'comment_post',
          '\MailPoet\Subscription\Comment::onSubmit',
          60,
          2
        );

        add_action(
          'wp_set_comment_status',
          '\MailPoet\Subscription\Comment::onStatusUpdate',
          60,
          2
        );
      }
    }

    // Subscribe in registration form
    if(
      isset($subscribe_settings['on_register']['enabled'])
      && $subscribe_settings['on_register']['enabled']
    ) {
      if(is_multisite()) {
        add_action(
          'signup_extra_fields',
          '\MailPoet\Subscription\Registration::extendForm'
        );
        add_action(
          'wpmu_validate_user_signup',
          '\MailPoet\Subscription\Registration::onMSRegister',
          60,
          1
        );
      } else {
        add_action(
          'register_form',
          '\MailPoet\Subscription\Registration::extendForm'
        );
        add_action(
          'register_post',
          '\MailPoet\Subscription\Registration::onRegister',
          60,
          3
        );
      }
    }

    // WP Users synchronization
    add_action(
      'user_register',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );
    add_action(
      'added_existing_user',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );
    add_action(
      'profile_update',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );
    add_action(
      'delete_user',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );
    // multisite
    add_action(
      'deleted_user',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );
    add_action(
      'remove_user_from_blog',
      '\MailPoet\Segments\WP::synchronizeUser',
      1
    );

    add_filter(
      'image_size_names_choose',
      array(
        $this,
        'appendImageSizes'
      )
    );
  }

  function appendImageSizes($sizes) {
    return array_merge($sizes, array(
      'mailpoet_newsletter_max' => __('MailPoet Newsletter'),
    ));
  }
}
