<?php

namespace app\components\behaviors;

use app\models\Workflow;
use app\models\WorkflowStatus;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class WorkflowBehavior
 * @package app\components\behaviors
 */
class WorkflowBehavior extends Behavior
{
    /**
     * @var string
     */
    public $defaultStatus = WorkflowStatus::WORKFLOW_STATUS_DEFAULT;

    /**
     * Event handler
     */
    public function beforeSave()
    {
        /* @var ActiveRecord $owner */
        $owner = $this->owner;

        /* @var Workflow $workflow */
        $workflow = ($owner->isNewRecord || !isset($owner->workflow))
            ? new Workflow(['status' => $this->defaultStatus])
            : $owner->{'workflow'};

        $workflow->load(\Yii::$app->request->post());

        if ($workflow->save() && $owner->hasAttribute('workflow_uuid')) {
            $owner->setAttribute('workflow_uuid', $workflow->uuid);
        }
    }

    /**
     * This event handler triggers when ActiveRecord item has been deleted.
     */
    public function afterDelete()
    {
        /* @var ActiveRecord $owner */
        $owner = $this->owner;

        /* @var Workflow $workflow */
        if ($workflow = $owner->{'getWorkflow'}()->one()) {
            $workflow->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }
}
