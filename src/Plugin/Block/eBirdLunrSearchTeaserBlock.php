<?php

namespace Drupal\ebird_lunr\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A block that renders a EbirdLunrSearchTeaser form.
 * When submitted, form sends value to search page.
 * This block is ideally placed on front page to advertise and
 * funnel visitors to bird search page.
 * 
 * @Block(
 *  id = "ebird_lunr_search_teaser_block", 
 *  admin_label = @Translation("eBird Lunr Search Teaser Block")
 * )
 */

 class eBirdLunrSearchTeaserBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var FormBuilderInterface $form_builder
   */
  protected $form_builder;
  
  /**
   * Injects Form Builder service into block plugin
   * 
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Form\FormBuilder $form
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilder $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->form_builder = $form_builder;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * Renders the block with the eBirdLunrSearchTeaser form using 
   * the injected Form Builder service
   */
  public function build() {
    $build = [];
    $build = $this->form_builder->getForm('Drupal\ebird_lunr\Form\eBirdLunrSearchTeaser');

    return $build;
  }
 }