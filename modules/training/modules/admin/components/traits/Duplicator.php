<?php

namespace training\modules\admin\components\traits;

use training\modules\admin\models\Answer;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Question;
use training\modules\admin\models\Test;
use training\modules\admin\models\TestQuestion;

/**
 * Trait Duplicator
 *
 * @package training\modules\admin\components\traits
 */
trait Duplicator
{
    /**
     * @param Lesson $lesson
     * @param string $uuid
     * @return bool
     */
    protected function duplicateLesson(Lesson $lesson, $uuid)
    {
        $lesson->course_uuid = $uuid;
        $clone = $lesson->duplicate();
        if ($clone->save()) {
            foreach ($lesson->questions as $question) {
                $this->duplicateQuestion($question, $clone->uuid);
            }

            return true;
        }

        return false;
    }

    /**
     * @param Question $question
     * @param string $uuid
     * @return bool
     */
    protected function duplicateQuestion(Question $question, $uuid)
    {
        $question->lesson_uuid = $uuid;
        $clone = $question->duplicate();
        if ($clone->save()) {
            foreach ($question->answers as $answer) {
                $this->duplicateAnswer($answer, $clone->uuid);
            }

            return true;
        }

        return false;
    }

    /**
     * @param Answer $answer
     * @param string $uuid
     * @return bool
     */
    protected function duplicateAnswer(Answer $answer, $uuid)
    {
        $answer->question_uuid = $uuid;
        $clone = $answer->duplicate();

        if ($clone->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param Test $test
     * @param string $uuid
     * @return bool
     */
    protected function duplicateTest(Test $test, $uuid)
    {
        $test->course_uuid = $uuid;
        $clone = $test->duplicate();

        if ($clone->save()) {
            $data = [];
            $questions = TestQuestion::find()
                ->where(['test_uuid' => $test->uuid])
                ->select('question_uuid')
                ->column();

            foreach ($questions as $question) {
                $data[] = [
                    'test_uuid' => $clone->uuid,
                    'question_uuid' => $question
                ];
            }

            return \Yii::$app->db->createCommand()
                ->batchInsert(TestQuestion::tableName(), ['test_uuid', 'question_uuid'], $data)
                ->execute();
        }

        return false;
    }
}