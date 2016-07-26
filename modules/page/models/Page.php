<?php

namespace app\modules\page\models;

use app\modules\admin\behaviors\SeoModel;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\image\drivers\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $lft
 * @property string $rgt
 * @property string $depth
 * @property string $tree
 * @property string $image
 * @property string $alias
 * @property string $title
 * @property resource $description
 * @property resource $content
 * @property string $meta_d
 * @property string $meta_k
 * @property string $meta_t
 *
 * @mixin NestedSetsBehavior
 * @mixin NestedSetsQueryBehavior
 * @mixin SeoModel
 */
class Page extends ActiveRecord
{
    const NO_IMAGE = 'no_page_image.png';
    const IMAGE_WIDTH = 213;
    const IMAGE_HEIGHT = 115;

    /** @var $file */
    public $file;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => SeoModel::className()],
            [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return PageQuery
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'unique'],
            ['title', 'required'],
            ['title', 'string', 'max' => 250],
            ['alias', 'unique'],
            ['alias', 'required'],
            ['alias', 'string', 'max' => 250],
            [['description', 'content'], 'safe'],
            [['meta_d', 'meta_k', 'meta_t'], 'string'],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Yii::t('page', 'Created at'),
            'updated_at' => Yii::t('page', 'Updated at'),
            'alias' => Yii::t('page', 'Alias'),
            'title' => Yii::t('page', 'Title'),
            'description' => Yii::t('page', 'Description'),
            'content' => Yii::t('page', 'Content'),
            'file' => Yii::t('page', 'File'),
            'meta_t' => Yii::t('page', 'Page title'),
            'meta_d' => Yii::t('page', 'Page description'),
            'meta_k' => Yii::t('page', 'Page keywords'),
        ];
    }

    /**
     * @return int
     */
    public function currentParent()
    {
        $current_parent = $this->parents(1)->one();
        $parent = is_null($current_parent) ? 0 : (int)$current_parent->id;

        return $parent;
    }

    /**
     * @param null $imgName
     * @return string
     */
    public function getPathToImages($imgName = null)
    {
        return Yii::getAlias('@web/uploads/page/' . $imgName);
    }

    /**
     * @param null $imgName
     * @return bool|string
     */
    public function getAbsolutePathToImages($imgName = null)
    {
        return Yii::getAlias('@webroot/uploads/page/' . $imgName);
    }

    /**
     * Before save model to DB
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (Yii::$app->request->isPost) {
                /** @var UploadedFile $file */
                $file = UploadedFile::getInstance($this, 'file');

                if (!is_null($file) && $this->validate()) {
                    $this->saveFile($file);
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getImageSrc()
    {
        if ($this->image) {
            return $this->getPathToImages($this->image);
        }

        return '/images/' . self::NO_IMAGE;
    }

    /**
     * @param UploadedFile $file
     */
    private function saveFile($file)
    {
        FileHelper::createDirectory($this->getAbsolutePathToImages(), 0777);

        if (is_file($this->getAbsolutePathToImages($this->image))) {
            unlink($this->getAbsolutePathToImages($this->image));
        }

        $name = uniqid();
        $this->image = $name . '.' . $file->extension;
        $file->saveAs($this->getAbsolutePathToImages($this->image));

        Yii::$app->image->load($this->getAbsolutePathToImages($this->image))
            ->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, Image::CROP)
            ->save($this->getAbsolutePathToImages($this->image), 100);
    }

    /**
     * @return string
     */
    public function href()
    {
        return Url::toRoute(['/page/frontend/view', 'alias' => $this->alias]);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
