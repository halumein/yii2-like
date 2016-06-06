<?php
namespace halumein\like\widgets;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use halumein\like\models\Like;

class LikeButton extends \yii\base\Widget
{
    public $text = NULL;
    public $model = NULL;
    public $cssClass = NULL;
    public $cssClassInList = NULL;
    public $textInList = NULL;
    public $htmlTag = 'span';
    public $template = NULL;


    public function init()
    {
        parent::init();

        \halumein\like\assets\WidgetAsset::register($this->getView());

        if ($this->text === NULL) {
            $this->text = 'Мне нравится';
        }

        if ($this->cssClass === NULL) {
            $this->cssClass = 'hal-like-button';
        }

        if ($this->textInList === NULL) {
            $this->textInList = 'В избранном';
        }

        if ($this->cssClassInList === NULL) {
            $this->cssClassInList = 'in-list';
        }

        return true;
    }

    public function run()
    {
        if (!is_object($this->model)) {
            return false;
        }

        $action = 'add';
        $url = '/like/element/add';
        $model = $this->model;


        $totalCount = Like::find()->where([
            'model' => $model::className(),
            'item_id' => $model->id,
            ])->count();

        $textTemplate = $this->text . ' ' .$totalCount;

        $currentUserId = \Yii::$app->user->getId();

        if ($currentUserId === $model->user_id) {
            return Html::tag($this->htmlTag, $textTemplate, [
                'class' => 'hal-like-button-my-likes',
                'data-role' => 'hal_like_button_my_likes',
            ]);
        }

        $elementModel = Like::find()->where([
            'user_id' => $currentUserId,
            'model' => $model::className(),
            'item_id' => $model->id,
            ])->one();

        if ($elementModel) {
            $textTemplate = $this->text . ' ' . $totalCount;
            $this->cssClass .= ' '.$this->cssClassInList;
            $action = 'remove';
            $url = '/like/element/remove';
        }

        return Html::tag($this->htmlTag, $textTemplate, [
            'class' => $this->cssClass,
            'data-role' => 'hal_like_button',
            'data-url' => Url::toRoute($url),
            'data-action' => $action,
            'data-item-id' => $model->id,
            'data-model' => $model::className()
        ]);
    }
}
