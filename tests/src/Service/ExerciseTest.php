<?php
namespace Drupal\Tests\calendar\Service;

use Drupal\Tests\UnitTestCase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Statement;
use Drupal\Core\Database\Query\SelectInterface;
use Drupal\calendar\Service\Exercise as ExerciseService;

/**
 * @coversDefaultClass ExerciseService
 * @group calendar
 */
class ExerciseTest extends UnitTestCase {

  /**
   * Mock statement.
   *
   * @var \PHPUnit_Framework_MockObject_MockObject | Statement
   */
  protected $statement;

  /**
   * Mock select interface.
   *
   * @var \PHPUnit_Framework_MockObject_MockObject | SelectInterface
   */
  protected $select;

  /**
   * Mock database connection.
   *
   * @var \PHPUnit_Framework_MockObject_MockObject | Connection
   */
  protected $database;

  /**
   * Exercise service under test.
   *
   * @var ExerciseService
   */
  protected $service;

  /**
   * Counts calls to fetchAssoc().
   *
   * @var int
   */
  protected $callsToFetch;

  /**
   * Sets up required mocks and the ExerciseService under test.
   */
  public function setUp() {
    $this->statement = $this->getMockBuilder('Drupal\Core\Database\Driver\fake\FakeStatement')
      ->disableOriginalConstructor()
      ->getMock();

    $this->select = $this->getMockBuilder('Drupal\Core\Database\Query\Select')
      ->disableOriginalConstructor()
      ->getMock();

    $this->database = $this->getMockBuilder('Drupal\Core\Database\Connection')
      ->disableOriginalConstructor()
      ->getMock();

    $this->service = new ExerciseService($this->database);
  }

  /**
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    unset($this->service);
    unset($this->select);
    unset($this->database);
  }

  /**
   * Tests getLastResults method.
   *
   * @see ExerciseService::getLastResults()
   *
   * @group Calendar
   */
  public function testGetLastResults() {

    $this->callsToFetch = 0;
    $userId = 1;
    $currentDate = '2014-09-10';
    $oneWeekAgo = '2014-09-03';
    $twoWeeksAgo = '2014-08-27';

    $this->database->expects($this->any())
      ->method('select')
      ->with('calendar_exercise', 'c')
      ->will($this->returnValue($this->select));

    $this->select->expects($this->at(0))->method('condition')
      ->with('c.uid', $userId)->will($this->returnSelf());

    $this->select->expects($this->at(1))->method('condition')
      ->with('c.date', $currentDate)->will($this->returnSelf());

    $this->select->expects($this->at(4))->method('condition')
      ->with('c.uid', $userId)->will($this->returnSelf());

    $this->select->expects($this->at(5))->method('condition')
      ->with('c.date', $oneWeekAgo)->will($this->returnSelf());

    $this->select->expects($this->at(8))->method('condition')
      ->with('c.uid', $userId)->will($this->returnSelf());

    $this->select->expects($this->at(9))->method('condition')
      ->with('c.date', $twoWeeksAgo)->will($this->returnSelf());

    $this->select->expects($this->any())
      ->method('fields')
      ->with('c')
      ->will($this->returnSelf());

    $this->select->expects($this->any())
      ->method('execute')
      ->will($this->returnValue($this->statement));

    $this->statement->expects($this->any())
      ->method('fetchAllAssoc')
      ->with('id')
      ->will($this->returnCallback(array($this, 'fetchAllAssocCallback')));

    $expectedResult = array(
      'today' =>  array('mock1'),
      'one-week-ago' =>  array('mock2'),
      'two-week-ago' =>  array('mock3'),
    );

    $this->assertEquals($expectedResult, $this->service->getLastResults(1, $currentDate));
  }

  /**
   * Return value callback for fetchAllAssoc() function on mocked object.
   *
   * @return bool|string
   */
  public function fetchAllAssocCallback() {
    $this->callsToFetch++;
    switch ($this->callsToFetch) {
      case 1:
        return array('mock1');
        break;
      case 2:
        return array('mock2');
        break;
      case 3:
        return array('mock3');
        break;
      default:
        return FALSE;
        break;
    }
  }
}
