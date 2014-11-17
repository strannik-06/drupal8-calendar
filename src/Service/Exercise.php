<?php
namespace Drupal\calendar\Service;

use Drupal\Core\Database\Connection;

/**
 * Service for exercise entity.
 */
class Exercise
{
    /**
     * @var Connection
     */
    protected $database;

    /**
     * @param Connection $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * Save an entry in the database.
     *
     * @param array $entry
    *
     * @return int
     * @throws \Exception
     */
    public static function insert($entry)
    {
        $returnValue = NULL;
        try {
            // todo: rewrite using connection service
            $returnValue = db_insert('recycle_batterypack')
                ->fields($entry)
                ->execute();
        }
        catch (\Exception $e) {
            drupal_set_message(t('db_insert failed. Message = %message, query= %query', array(
                '%message' => $e->getMessage(),
                '%query' => $e->query_string,
            )), 'error');
        }

        return $returnValue;
    }
}
