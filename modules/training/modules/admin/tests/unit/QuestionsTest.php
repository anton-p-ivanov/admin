<?php

namespace training\modules\admin\tests;

use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use training\modules\admin\models\Answer;
use training\modules\admin\models\Question;
use training\modules\admin\models\TestQuestion;

/**
 * Class QuestionsTest
 *
 * @package training\modules\admin\tests
 */
class QuestionsTest extends Unit
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
            'tests' => 'training\modules\admin\tests\fixtures\TestsFixture',
            'lessons' => 'training\modules\admin\tests\fixtures\LessonsFixture',
            'questions' => 'training\modules\admin\tests\fixtures\QuestionsFixture',
            'tests_questions' => 'training\modules\admin\tests\fixtures\TestsQuestionsFixture',
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
    public function testQuestionValidate()
    {
        $question = new Question();

        $question->title = null;
        $this->assertFalse($question->validate(['title']));

        $question->title = $this->faker->text(1000);
        $this->assertFalse($question->validate(['title']));

        $question->type = $this->faker->text();
        $this->assertFalse($question->validate(['type']));

        $question->sort = $this->faker->text();
        $this->assertFalse($question->validate(['sort']));

        $question->sort = -100;
        $this->assertFalse($question->validate(['sort']));

        $question->value = $this->faker->text();
        $this->assertFalse($question->validate(['value']));

        $question->value = -100;
        $this->assertFalse($question->validate(['value']));

        $question->active = -1;
        $this->assertFalse($question->validate(['active']));

        $question->lesson_uuid = null;
        $this->assertFalse($question->validate(['lesson_uuid']));

        $question->lesson_uuid = $this->faker->text();;
        $this->assertFalse($question->validate(['lesson_uuid']));
    }

    /**
     * Testing element creation.
     */
    public function testQuestionCreate()
    {
        $question = new Question([
            'title' => $this->faker->text(),
            'description' => $this->faker->text(500),
            'active' => true,
            'type' => Question::TYPE_MULTIPLE,
            'sort' => 100,
            'value' => 10,
            'lesson_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'lesson-0')->toString()
        ]);

        $result = $question->insert();

        // Test whether element was created.
        $this->assertTrue($result);
    }

    /**
     * Testing updating an element.
     */
    public function testQuestionUpdate()
    {
        /* @var Question $question */
        $question = Question::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($question);

        $question->type = Question::TYPE_TEXT;
        $result = $question->save();

        $answers = Answer::find()->where([
            'question_uuid' => $question->uuid,
        ])->all();

        // Test whether element was updated.
        $this->assertTrue($result);
        $this->assertCount(0, $answers);
    }

    /**
     * Testing copying of element.
     */
    public function testQuestionCopy()
    {
        /* @var Question $question */
        $question = Question::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($question);

        // Creating user field clone
        $clone = $question->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($question->lesson_uuid === $clone->lesson_uuid);
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testQuestionDelete()
    {
        /* @var Question $question */
        $question = Question::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($question);

        // Delete user field
        $result = $question->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($question->refresh());
        $this->assertCount(0, Answer::findAll(['question_uuid' => $question->uuid]));
        $this->assertCount(0, TestQuestion::findAll(['question_uuid' => $question->uuid]));
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Question $question
     */
    protected function makeSure($question)
    {
        $this->assertTrue($question instanceof Question);
        $this->assertTrue((int) Answer::find()->where(['question_uuid' => $question->uuid])->count() > 0);
        $this->assertTrue((int) TestQuestion::find()->where(['question_uuid' => $question->uuid])->count() > 0);
    }
}