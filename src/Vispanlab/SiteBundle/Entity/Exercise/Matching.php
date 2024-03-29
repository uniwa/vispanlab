<?php
namespace Vispanlab\SiteBundle\Entity\Exercise;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @ORM\Entity
 */
class Matching extends BaseExercise {
    /**
     * @ORM\Column (name="question", type="text")
     */
    protected $question;
    /**
     * @ORM\Column (name="left_answers", type="json")
     */
    protected $leftAnswers = array();
    /**
     * @ORM\Column (name="right_answers", type="json")
     */
    protected $rightAnswers = array();
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

    public function getLeftAnswers() {
        return $this->leftAnswers;
    }

    public function setLeftAnswers($leftAnswers) {
        $this->leftAnswers = $leftAnswers;
    }

    public function getRightAnswers() {
        return $this->rightAnswers;
    }

    public function setRightAnswers($rightAnswers) {
        $this->rightAnswers = $rightAnswers;
        // Sanity check
        foreach($this->leftAnswers as $curAnswer) {
            $matches = explode(',', $curAnswer['matches']);
            foreach($matches as $curMatch) {
                $i = $curMatch - 1;
                if(!isset($this->rightAnswers[$i])) { echo 'Η απάντηση δεν υπάρχει στο δεξί μέρος'; die(); }
            }
        }
    }

    public function getShowInEvaluationTest() {
        return $this->showInEvaluationTest;
    }

    public function setShowInEvaluationTest($showInEvaluationTest) {
        $this->showInEvaluationTest = $showInEvaluationTest;
    }

    public function getLeftAnswersCondensed() {
        $i = 1;
        return implode('<br style="mso-data-placement:same-cell;" />', array_map(function($a) use(&$i) { return ($i++).') '.$a['answer']; }, $this->leftAnswers));
    }

    public function getRightAnswersCondensed() {
        $i = 'A';
        return implode('<br style="mso-data-placement:same-cell;" />', array_map(function($a) use(&$i) { return ($i++).') '.$a['answer']; }, $this->rightAnswers));
    }

    public function getMatchesCondensed() {
        $i = 1;
        return implode('<br style="mso-data-placement:same-cell;" />', array_map(function($a) use(&$i) {
            return ($i++).'->'.implode(',', array_map(function($m) { $j = 'A'; for($k = 1; $k < $m; $k++) { $j++; }; return $j; }, explode(',', $a['matches'])));
        }, $this->leftAnswers));
    }

    public function isAnswerCorrect($answer) {
        foreach($this->getLeftAnswers() as $inc => $curAnswer) {
            $correctMatches = explode(',', $curAnswer['matches']);
            $selectedMatches = $answer[$inc];
            $diff = array_diff($correctMatches, $selectedMatches);
            if(count($diff) > 0) {
                return false;
            }
        }
        return true;
    }
}
?>