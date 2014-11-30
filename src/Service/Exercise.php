<?php
namespace Drupal\calendar\Service;

use Drupal\Core\Database\Connection;

/**
 * Service for exercise entity.
 */
class Exercise {
  /**
   * @var Connection
   */
  protected $database;

  /**
   * @param Connection $database
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * @param integer $userId
   * @param string  $currentDate
   *
   * @return array
   */
  public function getLastResults($userId, $currentDate = null) {
    if (!isset($currentDate)) {
      $currentDate = date('Y-m-d');
    }

    return array(
      'today' => $this->findByUserAndDate($userId, $currentDate),
      'one-week-ago' => $this->findByUserAndDate($userId,
        date('Y-m-d', strtotime("$currentDate - 1 week"))),
      'two-week-ago' => $this->findByUserAndDate($userId,
        date('Y-m-d', strtotime("$currentDate - 2 week"))),
    );
  }

  /**
   * @param integer $userId
   * @param string  $date
   *
   * @return mixed
   */
  protected function findByUserAndDate($userId, $date) {
    $query = $this->database->select('calendar_exercise', 'c');
    $query->condition('c.uid', $userId);
    $query->condition('c.date', $date);

    return $query
      ->fields('c')
      ->execute()
      ->fetchAllAssoc('id');
  }
}
