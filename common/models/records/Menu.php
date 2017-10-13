<?PHP

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "t_menu".
 *
 * @property integer $id
 * @property string $title
 * @property integer $pid
 * @property integer $sort
 * @property string $url
 * @property integer $hide
 * @property string $group
 * @property integer $status
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','url'],'required'],
            [['pid', 'sort', 'hide', 'status'], 'integer'],
            [['title', 'group'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'pid' => 'Pid',
            'sort' => 'Sort',
            'url' => 'Url',
            'hide' => 'Hide',
            'group' => 'Group',
            'status' => 'Status',
        ];
    }
}
