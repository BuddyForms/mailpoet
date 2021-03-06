import MailPoet from 'mailpoet';
import Hooks from 'wp-js-hooks';
import Scheduling from 'newsletters/types/welcome/scheduling.jsx';
import SenderField from 'newsletters/send/sender_address_field.jsx';
import GATrackingField from 'newsletters/send/ga_tracking.jsx';

let fields = [
  {
    name: 'subject',
    label: MailPoet.I18n.t('subjectLine'),
    tip: MailPoet.I18n.t('subjectLineTip'),
    type: 'text',
    validation: {
      'data-parsley-required': true,
      'data-parsley-required-message': MailPoet.I18n.t('emptySubjectLineError'),
    },
  },
  {
    name: 'options',
    label: MailPoet.I18n.t('sendWelcomeEmailWhen'),
    type: 'reactComponent',
    component: Scheduling,
  },
  {
    name: 'sender',
    label: MailPoet.I18n.t('sender'),
    tip: MailPoet.I18n.t('senderTip'),
    fields: [
      {
        name: 'sender_name',
        type: 'text',
        placeholder: MailPoet.I18n.t('senderNamePlaceholder'),
        validation: {
          'data-parsley-required': true,
        },
      },
      {
        name: 'sender_address',
        type: 'reactComponent',
        component: SenderField,
        placeholder: MailPoet.I18n.t('senderAddressPlaceholder'),
        validation: {
          'data-parsley-required': true,
          'data-parsley-type': 'email',
        },
      },
    ],
  },
  {
    name: 'reply-to',
    label: MailPoet.I18n.t('replyTo'),
    tip: MailPoet.I18n.t('replyToTip'),
    inline: true,
    fields: [
      {
        name: 'reply_to_name',
        type: 'text',
        placeholder: MailPoet.I18n.t('replyToNamePlaceholder'),
      },
      {
        name: 'reply_to_address',
        type: 'text',
        placeholder: MailPoet.I18n.t('replyToAddressPlaceholder'),
        validation: {
          'data-parsley-type': 'email',
        },
      },
    ],
  },
  GATrackingField,
];

fields = Hooks.applyFilters('mailpoet_newsletters_3rd_step_fields', fields);

export default {
  getFields: function getFields() {
    return fields;
  },
  getSendButtonOptions: function getSendButtonOptions() {
    return {
      value: MailPoet.I18n.t('activate'),
    };
  },
};
