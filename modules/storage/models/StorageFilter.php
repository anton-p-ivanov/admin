<?php
namespace storage\models;

use app\models\Filter;
use app\models\User;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\validators\NumberValidator;

/**
 * Class StorageFilter
 *
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @package storage\models
 */
class StorageFilter extends Filter
{
    /**
     * @var string
     */
    public $owner;
    /**
     * @var float[]|string[]
     */
    public $size;
    /**
     * @var string
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function buildQuery(&$query)
    {
        try {
            $attributes = array_filter(Json::decode($this->query), function ($attribute) {
                return !empty($attribute);
            });

            $this->isActive = true;
        }
        catch (\Exception $exception) {
            $attributes = [];
        }

        foreach ($attributes as $attribute => $value) {
            switch ($attribute) {
                case 'owner':
                    $query->andFilterWhere(['{{%workflow}}.[[created_by]]' => $value]);
                    break;
                case 'type':
                    $this->filterByType($query, $value);
                    break;
                case 'size':
                    $this->filterBySize($query, $value);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param QueryInterface $query
     * @param string $type
     */
    protected function filterByType(&$query, $type)
    {
        $field = StorageFile::tableName() . '.[[type]]';
        $neutralCondition = "$field IS NULL";
        $fileTypes = Json::decode(file_get_contents(__DIR__ . "/../config/.filetypes.json"));
        $types = [
            'DOC' => [$field => $fileTypes['DOC']],
            'XLS' => [$field => $fileTypes['XLS']],
            'PRS' => [$field => $fileTypes['PRS']],
            'ARC' => [$field => $fileTypes['ARC']],
            'IMG' => ['like', $field, 'image/'],
            'VID' => ['like', $field, 'video/']
        ];

        if (array_key_exists($type, $types)) {
            $query->andFilterWhere(['or', $types[$type], $neutralCondition]);
        }
    }

    /**
     * @param QueryInterface $query
     * @param float[] $size
     */
    protected function filterBySize(&$query, $size)
    {
        $columnName = StorageFile::tableName() . '.[[size]]';
        $neutralCondition = "$columnName IS NULL";

        foreach ($size as &$value) {
            $value = $this->convertToBytes($value);
        }

        if ($size['min']) {
            $query->andFilterWhere(['or', ['>=', $columnName, (int) $size['min']], $neutralCondition]);
        }
        if ($size['max']) {
            $query->andFilterWhere(['or', ['<=', $columnName, (int) $size['max']], $neutralCondition]);
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'owner' => 'Owner',
            'size' => 'Size',
            'type' => 'Type'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who uploaded the file.',
            'size' => 'Use B (Byte), K (Kilobyte), M (Megabyte), G (Gigabyte) size measure units. ' .
                'If no measure unit provided bytes is assumed.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['owner', 'in', 'range' => array_keys(self::getOwners()), 'message' => self::t('Invalid owner.')],
            ['type', 'in', 'range' => array_keys(self::getTypes()), 'message' => self::t('Invalid type.')],
            ['size', 'validateSize']
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->query = Json::encode([
                'class' => md5(self::className()),
                'owner' => $this->owner,
                'type' => $this->type,
                'size' => $this->size
            ]);
        }

        return $isValid;
    }

    /**
     * @param $attribute
     */
    public function validateSize($attribute)
    {
        $values = $this->$attribute;
        $validator = new NumberValidator(['integerOnly' => true]);

        foreach ($values as &$value) {
            $value = preg_replace('/\s/', '', $value);
            $value = $this->convertToBytes($value);

            if (!$value) {
                continue;
            }

            if (!$validator->validate($value)) {
                $this->addError($attribute, self::t('Invalid size value.'));
            };

            if ((int) $value < 0) {
                $this->addError($attribute, self::t('Minimum 0 bytes size is allowed.'));
            }
        }

        if ($values['max'] && (int) $values['max'] < (int) $values['min']) {
            $this->addError($attribute, self::t('Max size must be greater than min size.'));
        }
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => Storage::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        $types =  [
            'DOC' => 'Document',
            'XLS' => 'Spreadsheet',
            'PRS' => 'Presentation',
            'ARC' => 'Archive',
            'IMG' => 'Image',
            'VID' => 'Video',
        ];

        return array_map('self::t', $types);
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('storage', $label);
    }

    /**
     * @param string $from
     * @return string|float
     */
    function convertToBytes($from) {
        $number = substr($from, 0, -1);
        $unit = strtoupper(substr($from, -1));
        $units = ['K', 'M', 'G', 'T', 'P'];

        if (($position = array_search($unit, $units)) !== false) {
            return $number * pow(1024, (int) $position + 1);
        }

        return $from;
    }
}
