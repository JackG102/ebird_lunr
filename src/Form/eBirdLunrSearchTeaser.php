<?php

namespace Drupal\ebird_lunr\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The teaser form rendered by the eBirdLunrSearchTeaser block.
 * The form passes the search text to the
 * bird search page through a GET request and redirects the user.
 */
class eBirdLunrSearchTeaser extends FormBase {
  
  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'ebird_lunr_search_teaser';
  }
  
  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#markup' => '
        <h2>Bird Search</h2>
        <p>Search for birds seen in Reston in the last 30 days at popular hotspots.</p>
        ',
    ];

    $form['search_box'] = [
      '#type' => 'textfield',
      '#placeholder' => $this->t('Pileated Woodpecker'),
      '#size' => 250,
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }
  
  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('ebird_lunr.search-page', [
      'search' => $form_state->getValue('search_box'),
    ]);
  }
}