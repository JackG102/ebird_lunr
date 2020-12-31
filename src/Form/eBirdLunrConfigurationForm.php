<?php

namespace Drupal\ebird_lunr\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A configuration form that stores the eBird API key
 * and link to information about how to request one.
 * Sub URL located at /admin/config/ebird_lunr/settings
 */

class eBirdLunrConfigurationForm extends ConfigFormBase {

  const SETTINGS = 'ebird_lunr.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ebird_lunr_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('eBird API Key'),
      '#description' => $this->t('Enter in your eBird API key.  For the module to function properly, you will need add your API key here'),
      '#default_value' => $config->get('api_key'),
    ];

    $form['help'] = [
      '#markup' => "<span>If you do not have an eBird API key, you'll need to sign into your eBird account and sign up to receive one: <a href='https://ebird.org/api/keygen'>https://ebird.org/api/keygen</a></span>",
    ];

    $form['hotspot_ids'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Birding Hotspot IDs'),
      '#description' => $this->t("Enter in up to 10 birding hotspot IDs to be indexed for the search. Be sure to seperate each ID with a comma but leave no spaces between them.  For example: L579773,L1539888,L3490968'"),
      '#default_value' => $config->get('hotspot_ids'),
    ];

    $form['hotspot_ids_help'] = [
      '#markup' => "<span>If you do not know the ID of the birding hotspots you want to be searched, visit the hotspot page on ebird.org.  Every URL will end with the hotspot ID.  For example, 'https://ebird.org/hotspot/L579773', L579773 is the ID for the Glade Valley Stream.</span>",
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  $this->configFactory()->getEditable(static::SETTINGS)
    ->set('api_key', $form_state->getValue('api_key'))
    ->set('hotspot_ids', $form_state->getValue('hotspot_ids'))

    ->save();

  return parent::submitForm($form, $form_state);
  }
}
