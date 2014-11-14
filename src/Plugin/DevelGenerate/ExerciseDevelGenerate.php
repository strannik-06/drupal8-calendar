<?php
namespace Drupal\sport_calendar\Plugin\DevelGenerate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\devel_generate\DevelGenerateBase;

/**
 * Provides a ExampleDevelGenerate plugin.
 *
 * @DevelGenerate(
 *   id = "sport_calendar",
 *   label = "exercises",
 *   description = "Generate a given number of exercises. Optionally delete current exercises.",
 *   url = "sport_calendar_generate",
 *   permission = "administer devel_generate",
 *   settings = {
 *     "num" = 50,
 *     "kill" = FALSE
 *   }
 * )
 */
class ExerciseDevelGenerate extends DevelGenerateBase
{
    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state)
    {

        $form['num'] = array(
            '#type' => 'textfield',
            '#title' => t('How many exercises would you like to generate?'),
            '#default_value' => $this->getSetting('num'),
            '#size' => 10,
        );

        $form['kill'] = array(
            '#type' => 'checkbox',
            '#title' => t('Delete all examples before generating new exercises.'),
            '#default_value' => $this->getSetting('kill'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateElements(array $values)
    {
        $num = $values['num'];
        $kill = $values['kill'];

        if ($kill) {
            $this->setMessage(t('Old examples have been deleted.'));
        }

        // TODO: add logic of saving

        $this->setMessage(t('!num_exercises created.',
            array('!num_exercises' => \Drupal::translation()->formatPlural($num, '1 exercise', '@count exercises'))));
    }

    /**
     * {@inheritdoc}
     */
    public function validateDrushParams($args)
    {
        $values = array(
            'num' => array_shift($args),
            'kill' => drush_get_option('kill'),
        );

        return $values;
    }

}
