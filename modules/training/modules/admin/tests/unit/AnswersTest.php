<?php

namespace training\modules\admin\tests;

use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use training\modules\admin\models\Answer;

/**
 * Class AnswersTest
 *
 * @package training\modules\admin\tests
 */
class AnswersTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'lessons' => 'training\modules\admin\tests\fixtures\LessonsFixture',
            'questions' => 'training\modules\admin\tests\fixtures\QuestionsFixture',
            'answers' => 'training\modules\admin\tests\fixtures\AnswersFixture',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@training', '@app/modules/training');

        $this->faker = Factory::create();
    }

    /**
     * Testing validations.
     */
    public function testAnswerValidate()
    {
        $answer = new Answer();

        $answer->answer = null;
        $this->assertFalse($answer->validate(['answer']));

        $answer->sort = $this->faker->text();
        $this->assertFalse($answer->validate(['sort']));

        $answer->sort = -100;
        $this->assertFalse($answer->validate(['sort']));

        $answer->valid = 2;
        $this->assertFalse($answer->validate(['valid']));
    }

    /**
     * Testing element creation.
     */
    public function testAnswerCreate()
    {
        $answer = new Answer([
            'answer' => $this->faker->text(),
            'valid' => true,
            'question_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-0')->toString()
        ]);

        $result = $answer->insert();

        $this->makeSure($answer);

        $valid = Answer::find()->where([
            'question_uuid' => $answer->question_uuid,
            'valid' => true
        ])->all();

        // Test whether element was updated.
        $this->assertTrue($result);
        $this->assertTrue(count($valid) === 1);
        $this->assertTrue($valid[0]->uuid === $answer->uuid);
    }

    /**
     * Testing updating an element.
     */
    public function testAnswerUpdate()
    {
        /* @var Answer $answer */
        $answer = Answer::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'answer-1')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($answer);

        $answer->valid = true;

        $result = $answer->save();

        $valid = Answer::find()->where([
            'question_uuid' => $answer->question_uuid,
            'valid' => true
        ])->all();

        // Test whether element was updated.
        $this->assertTrue($result);
        $this->assertTrue(count($valid) === 1);
        $this->assertTrue($valid[0]->uuid === $answer->uuid);
    }

    /**
     * Testing copying of element.
     */
    public function testAnswerCopy()
    {
        /* @var Answer $type */
        $answer = Answer::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'answer-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($answer);

        // Creating user field clone
        $clone = $answer->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($answer->question_uuid === $clone->question_uuid);
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testAnswerDelete()
    {
        /* @var Answer $answer */
        $answer = Answer::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'answer-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($answer);

        // Delete user field
        $result = $answer->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($answer->refresh());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Answer $answer
     */
    protected function makeSure($answer)
    {
        $this->assertTrue($answer instanceof Answer);
    }
}