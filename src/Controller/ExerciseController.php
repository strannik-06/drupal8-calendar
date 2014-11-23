<?php
namespace Drupal\calendar\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\calendar\Service\Exercise as ExerciseService;

class ExerciseController extends ControllerBase {
  /**
   * @var ExerciseService
   */
  protected $service;

  /**
   * @param ExerciseService $service
   */
  public function __construct(ExerciseService $service) {
    $this->service = $service;
  }

  /**
   * @param ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('calendar.exercise'));
  }

  /**
   * @return array
   */
  public function indexAction() {
    $account = \Drupal::currentUser();

    return array(
      '#theme' => 'calendar_index',
      '#results' => $this->service->getLastResults($account->id()),
      '#username' => $account->getUsername(),
    );
  }
}
