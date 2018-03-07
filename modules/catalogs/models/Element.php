<?php

namespace catalogs\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\PurifyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Site;
use app\models\Workflow;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\HttpException;

/**
 * Class Element
 *
 * @property string $uuid
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $content
 * @property boolean $active
 * @property \DateTime $active_from_date
 * @property \DateTime $active_to_date
 * @property string $code
 * @property integer $sort
 * @property string $catalog_uuid
 * @property string $workflow_uuid
 *
 * @property ElementTree[] $tree
 * @property ElementTree $node
 * @property Catalog $catalog
 * @property Workflow $workflow
 * @property array $locations
 *
 * @package catalogs\models
 */
class Element extends ActiveRecord
{
    /**
     * Element type `file`
     */
    const ELEMENT_TYPE_ELEMENT = 'E';
    /**
     * Element type `folder`
     */
    const ELEMENT_TYPE_SECTION = 'S';
    /**
     * @var array
     */
    public static $types = [self::ELEMENT_TYPE_SECTION, self::ELEMENT_TYPE_ELEMENT];
    /**
     * @var array
     */
    public $active_dates = [];
    /**
     * @var array
     */
    private $_locations;
    /**
     * @var \yii\db\Transaction
     */
    private $_transaction;
    /**
     * @var Site[]
     */
    private $_sites;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_elements}}';
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('catalogs/elements', $label);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => 'Up to 200 characters length. Inline HTML-tags allowed.',
            'description' => 'Maximum 500 characters allowed. Inline HTML-tags allowed.',
            'content' => 'Element content. HTML is allowed.',
            'locations' => 'Select one of available locations.',
            'active' => 'Whether an element is active for public use.',
            'active_from_date' => 'Specifies the date after that an element should be visible.',
            'active_to_date' => 'Specifies the date after that an element will be blocked for public use.',
            'sites' => 'Select sites where an element will be available.',
            'code' => 'Will be generated if empty.',
            'sort' => 'Default is 100.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'locations' => 'Locations',
            'active' => 'Active',
            'active_from_date' => 'Active from',
            'active_to_date' => 'Active to',
            'sort' => 'Sorting index',
            'sites' => 'Sites',
            'code' => 'Symbolic code'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function rules()
    {
        /* Only user fields should be validated */

        $rules = [
            ['locations', 'validateLocations', 'when' => [$this, 'hasLocations']],
            [['title', 'locations'], 'required'],
            ['title', 'string', 'max' => 200],
            ['code', 'string', 'max' => 200],
            ['active', 'boolean'],
            ['code', 'match', 'pattern' => '/^[\w\d\-\_]+$/'],
            ['description', 'string', 'max' => 500],
            ['content', 'safe'],
            ['sites', 'exist', 'targetClass' => Site::class, 'targetAttribute' => 'uuid', 'allowArray' => true],
            ['sites', 'required', 'when' => function (Element $model) {
                return !$model->isSection();
            }],
            // Sort field
            [
                'sort',
                'integer',
                'min' => 0,
            ],
            // Date fields
            ['active_dates', 'each', 'rule' => [
                'date',
                'format' => \Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => 'active_dates',
            ]],
            ['active_dates', 'validateDateRange'],
        ];

        return $rules;
    }

    /**
     * @param string $attribute
     */
    public function validateDateRange($attribute)
    {
        $value = $this->$attribute;
        if (!empty($value['active_to_date'])
            && ($value['active_from_date'] > $value['active_to_date'])
        ) {
            $this->addError($attribute . '[active_to_date]', self::t('This date must be greater than first one.'));
        }
    }

    /**
     * @return bool
     */
    public function hasLocations(): bool
    {
        return is_array($this->_locations) && count($this->_locations) > 0;
    }

    /**
     * @return bool
     */
    public function isSection(): bool
    {
        return $this->type === self::ELEMENT_TYPE_SECTION;
    }

    /**
     * @param bool $checkDates
     * @return bool
     */
    public function isActive($checkDates = true)
    {
        $isActive = $this->active === 1;

        if (!$checkDates) {
            return $isActive;
        }

        if ($this->active_from_date) {
            $isActive = $isActive && (new \DateTime($this->active_from_date))->getTimestamp() < time();
        }

        if ($this->active_to_date) {
            $isActive = $isActive && (new \DateTime($this->active_to_date))->getTimestamp() > time();
        }

        return $isActive;
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['wf'] = WorkflowBehavior::class;
        $behaviors['sl'] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'immutable' => true
        ];
        $behaviors['pf'] = [
            'class' => PurifyBehavior::class,
            'attributes' => ['title', 'description'],
            'config' => [
                'AutoFormat.RemoveEmpty' => true,
                'AutoFormat.RemoveSpansWithoutAttributes' => true,
                'HTML.Allowed' => 'a[href],b,strong,i,em,span,*[class]'
            ]
        ];

        return $behaviors;
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if ($result = parent::beforeValidate()) {
            // Filter user selected locations
            if ($this->_locations) {
                $this->filterLocations();
            }

            $patterns = [
                '/\s{2,}/u',
                '/^\s+/u',
                '/\s+$/u'
            ];

            $this->title = preg_replace($patterns, '', $this->title);
            $this->description = preg_replace($patterns, '', $this->description);
        }

        return $result;
    }

    /**
     * @throws HttpException
     */
    public function beforeDelete()
    {
        // To avoid nested tree collisions `Element` model could not be deleted directly.
        // Use `ElementTree::delete()` method.
        throw new HttpException(400, self::t('Could not perform `delete` action to an `Element` model'));
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);

        if ($result) {
            $this->_transaction = $this->getDb()->beginTransaction();

            if (is_array($this->active_dates)) {
                $this->parseActiveDates();
            }
        }

        return $result;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // Calling method parent implementation
        parent::afterSave($insert, $changedAttributes);

        $result = true;

        if (!$this->isSection()) {
            // Insert sites
            $this->insertSites();
        }

        if ($result) {
            ($insert ? $this->insertNodes() : $this->updateNodes());
        }

        $methodName = $result ? 'commit' : 'rollBack';
        $this->_transaction->$methodName();
    }

    /**
     * Insert sites.
     */
    protected function insertSites()
    {
        ElementSite::deleteAll(['element_uuid' => $this->uuid]);

        if ($this->_sites) {
            $insert = array_map(function ($site_uuid) {
                return ['element_uuid' => $this->uuid, 'site_uuid' => $site_uuid];
            }, $this->_sites);

            if ($insert) {
                \Yii::$app->db->createCommand()
                    ->batchInsert(ElementSite::tableName(), ['element_uuid', 'site_uuid'], $insert)
                    ->execute();
            }
        }
    }

    /**
     * @param $attribute
     */
    public function validateLocations($attribute)
    {
        $query = ElementTree::find()
            ->type(self::ELEMENT_TYPE_ELEMENT)
            ->andWhere(['{{%catalogs_elements_tree}}.[[tree_uuid]]' => $this->$attribute]);

        if ($query->count() > 0) {
            $this->addError($attribute, self::t('One or more selected locations are not a directory.'));
        }
    }

    /**
     * Filter user selected locations
     */
    protected function filterLocations()
    {
        if (!is_array($this->_locations)) {
            $this->_locations = [$this->_locations];
        }

        // Filtering empty values
        $this->_locations = array_filter($this->_locations, function ($value) {
            return !empty($value);
        });

        // Filtering non-unique values
        $this->_locations = array_unique($this->_locations);

        // If a tree node is a `directory` only one location is allowed
        if ($this->isSection() && $this->_locations) {
            $this->_locations = [array_shift($this->_locations)];
        }
    }

    /**
     * Insert new node into element`s tree
     */
    protected function insertNodes()
    {
        if (!$this->_locations) {
            $this->_locations = [$this->catalog->tree_uuid];
        }

        $nodes = ElementTree::find()
            ->type(self::ELEMENT_TYPE_SECTION)
            ->where(['{{%catalogs_elements_tree}}.[[tree_uuid]]' => $this->_locations])
            ->all();

        foreach ($nodes as $node) {
            $tree = new ElementTree();
            $tree->element_uuid = $this->uuid;
            $tree->{'appendTo'}($node);
        }
    }

    /**
     * Update node in element`s tree
     */
    protected function updateNodes()
    {
        $tree = $this->tree;
        if ($this->isSection()) {
            $node = array_shift($tree);

            if (!$this->_locations) {
                $this->_locations = [$this->catalog->tree_uuid];
            }

            $parent = ($parent = $node->parents(1)->one()) ?: new ElementTree();

            if ($parent && [$parent->tree_uuid] !== $this->_locations) {
                $root = ElementTree::find()
                    ->type(self::ELEMENT_TYPE_SECTION)
                    ->where(['{{%catalogs_elements_tree}}.[[tree_uuid]]' => $this->_locations])
                    ->one();

                if ($root) {
                    $node->appendTo($root);
                }
            }
        }
        else {
            // Delete old tree nodes
            foreach ($tree as $node) {
                $node->detachBehavior('element');
                $node->delete();
            }

            // Insert new tree nodes
            $this->insertNodes();
        }
    }

    /**
     * @return array
     */
    public function getLocations()
    {
        if ($this->_locations === null && $this->tree) {
            foreach ($this->tree as $node) {
                if ($parent = $node->parents(1)->one()) {
                    $this->_locations[] = $parent->tree_uuid;
                }
            }
        }

        return $this->_locations;
    }

    /**
     * @param array $locations
     */
    public function setLocations($locations)
    {
        $this->_locations = $locations;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasMany(ElementTree::class, ['element_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(ElementTree::class, ['element_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['uuid' => 'catalog_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return Site[]
     */
    public function getSites()
    {
        if ($this->_sites === null) {
            $this->_sites = $this
                ->hasMany(ElementSite::class, ['element_uuid' => 'uuid'])
                ->select('site_uuid')
                ->column();
        }

        return $this->_sites;
    }

    /**
     * @param $sites
     */
    public function setSites($sites)
    {
        $this->_sites = $sites;
    }

    /**
     * @return Element
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $clone->code = null;

        $unsafeAttributes = ['type', 'catalog_uuid', 'active_from_date', 'active_to_date', 'sites'];
        foreach ($unsafeAttributes as $unsafeAttribute) {
            $clone->$unsafeAttribute = $this->$unsafeAttribute;
        }

        return $clone;
    }

    /**
     * @param string $format
     */
    public function formatDatesArray($format = null)
    {
        foreach (['active_from_date', 'active_to_date'] as $attribute) {
            if ($this->$attribute) {
                $this->$attribute = \Yii::$app->formatter->asDatetime($this->$attribute, $format);
            }
        }
    }

    /**
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    protected function parseActiveDates()
    {
        foreach ($this->active_dates as $name => $date) {
            if (is_int($date)) {
                $expression = new Expression("FROM_UNIXTIME(:$name)", [":$name" => $date]);
                $this->setAttribute($name, $expression);
            }
            else {
                $this->setAttribute($name, null);
            }
        }
    }
}
