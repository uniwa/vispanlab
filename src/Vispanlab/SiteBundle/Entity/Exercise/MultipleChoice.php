<?php
namespace Vispanlab\SiteBundle\Entity\Exercise;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @ORM\Entity
 */
class MultipleChoice extends BaseExercise {
    /**
     * @ORM\Column (name="question", type="text")
     */
    protected $question;
    /**
     * @ORM\Column (name="answers", type="json")
     */
    protected $answers = array();
    /**
     * @ORM\Column (name="show_in_evaluation_test", type="integer")
     */
    protected $showInEvaluationTest;

    public function getQuestion() {
        return $this->question;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function setAnswers($answers) {
        $this->answers = $answers;
    }

    public function getCorrectAnswer() {
        foreach($this->answers as $i => $curAnswer) {
            if(isset($curAnswer['is_correct']) && $curAnswer['is_correct'] == true) {
                return $i+1;
            }
        }
    }

    public function setCorrectAnswer($i) {
        $i = $i - 1;
        foreach($this->answers as &$curAnswer) {
            unset($curAnswer['is_correct']);
        }
        if(!isset($this->answers[$i])) { echo 'Ο αριθμός της σωστής απάντησης δεν υπάρχει'; die(); }
        $this->answers[$i]['is_correct'] = true;
    }

    public function getShowInEvaluationTest() {
        return $this->showInEvaluationTest;
    }

    public function setShowInEvaluationTest($showInEvaluationTest) {
        $this->showInEvaluationTest = $showInEvaluationTest;
    }

    public function getAnswersCondensed() {
        return implode('<br style="mso-data-placement:same-cell;" />', array_map(function($a) { return (isset($a['is_correct']) && $a['is_correct'] == true ? '[x]': '').$a['answer']; }, $this->answers));
    }

    public function isAnswerCorrect($answer) {
        foreach($this->getAnswers() as $curAnswer) {
            if($curAnswer['answer'] == $answer) {
                if(isset($curAnswer['is_correct']) && $curAnswer['is_correct'] == true) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }
}
?>