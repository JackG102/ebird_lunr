<?php

namespace Drupal\ebird_lunr\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Http\ClientFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Exception\ConnectException;

/**
 * Builds the main eBird Lunr search page
 * and fetches the data from eBird API using
 * the injected HTTP Client Service.
 * 
 * Note: The form submit structure is slightly different
 * to accomodate the lunr search.  It uses JavaScript rather than
 * traditional PHP form submit handlers or AJAX for this particular
 * use case.
 */
class eBirdLunrSearch extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  protected $client_factory;
  protected $json;

  /**
   * Class constructor.
   */
  public function __construct(ClientFactory $client_factory, Json $json) {
    $this->client_factory = $client_factory;
    $this->json = $json;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client_factory'),
      $container->get('serialization.json')
    );
  }
  
  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'ebird_lunr_search';
  }
  
  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Use custom method to fetch eBird Data and make it available
    // for attached JavaScript code to search and manipulate
    $json_object = $this->fetchData();
    $form['#attached']['drupalSettings']['ebird_lunr']['ebird_lunr_search']['json_object'] = $json_object;
    
    // Attaches JS library defined module's library yml file
    $form['#attached']['library'][] = 'ebird_lunr/search';

    // Caches the form for 1 day
    $form['#cache']['max-age'] = 86400;

    // Allows form to be processed by JS, not PHP 
    $form['#attributes'] = ['onsubmit' => 'return false'];

    $form['title'] = [
      '#markup' => '
        <h1>Bird Search</h1>
        <p>Search for birds seen in Reston in the last 30 days at popular hotspots. 
        You can be broad in your search, such as typing "sparrow", or more specific, such as "Northern Mockingbird".  
        </p>
        ',
    ];

    $form['search_box'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search for birds seen in your area'),
      '#placeholder' => $this->t('Common Raven'),
      '#size' => 250,
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    $form['search_button'] = [
      '#markup' => 
        '<span id="lunr_search_button">Search</span>'
      ,
    ];

    $form['results'] = [
      '#markup' => 
        "<table class='responsive-enabled table table-hover table-striped'>
          <thead>
            <th>Species</th>
            <th>Location</th>
            <th>Date & Time</th>
            <th>Number Seen</th>
          </thead>
          <tbody id='search_results'>
          </tbody>
        </table>"
      ,
    ];

    return $form;
  }
  
  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    return [];
  }

  
  /**
   * Fetches the eBird API information using the HTTP Client Service
   * and returns a properly formatted JSON object.
   * 
   * @TODO Clean up encoding of JSON object and add to the module's
   * configuration form to allow admins the ability to change what birding
   * hotspots can be searched.  Currently, it is hardcoded.
   */
  protected function fetchData() {
    $config_module_values = \Drupal::config('ebird_lunr.settings');
    $api_key = $config_module_values->get('api_key');

    // Building the query to get eBird Data
    $client = $this->client_factory->fromOptions([
      'base_uri' => 'https://api.ebird.org/'
    ]);

    try {
      $response = $client->get('/v2/data/obs/US-VA-059/recent', [
        'query' => [
          'maxResults' => 1000,
          'r' => 'L579773,L1539888,L3490968,L876992,L1355334,L2294314,L1100184,L1927385,L2438988,L2380916',
          'back' => 30,
        ],
        'headers' => [
          'X-eBirdApiToken' => $api_key, 
        ]
      ]);

      // Fetching the eBird JSON Data and encodes it into a JSON array for Lunr search
      // @TODO Weird behavior -- had to encode/decode to get data right.
      $bird_observations = json_decode(json_encode($this->json->decode($response->getBody())));
      return $bird_observations;
    } catch (ConnectException $e) {
      // If it can't connect to eBird API returns empty JSON object
      // @TODO render on form that module can't reach eBird currently
      $bird_observations = json_decode("{}");
      return $bird_observations;
    }
  }
}