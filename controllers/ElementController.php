<?php

namespace halumein\like\controllers;

use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use halumein\like\models\Like;



/**
 * Default controller for the `like` module
 */
class ElementController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                    'remove' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        var_dump('index');
        die;
    }

    public function actionAdd()
    {
        $likeModel = new Like();

        $postData = \Yii::$app->request->post();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // небольшая проверка на случай если уже поставлен, например на другой вкладке браузера
        $checkModel = Like::find()->where([
            'user_id' => \Yii::$app->user->getId(),
            'model' => $postData['model'],
            'item_id' => $postData['itemId'],
            ])->one();

        if ($checkModel) {
            return [
                'response' => true,
                'url' => Url::toRoute('/like/element/remove'),
                'totalCount' => Like::find()->where([
                    'model' => $postData['model'],
                    'item_id' => $postData['itemId'],
                    ])->count()
            ];
        }

        $likeModel->user_id = \Yii::$app->user->getId();
        $likeModel->model = $postData['model'];
        $likeModel->item_id = $postData['itemId'];

        if ($likeModel->save()) {
            return [
                'response' => true,
                'url' => Url::toRoute('/like/element/remove'),
                'totalCount' => Like::find()->where([
                    'model' => $postData['model'],
                    'item_id' => $postData['itemId'],
                    ])->count()
            ];
        }

        return [
            'response' => false
        ];

    }


    public function actionRemove()
    {
        $postData = \Yii::$app->request->post();

        $likeModel = Like::find()->where([
            'user_id' => \Yii::$app->user->getId(),
            'model' => $postData['model'],
            'item_id' => $postData['itemId'],
            ])->one();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // небольшая проверка на случай если уже удалено из модального окна или на другой вкладке
        if ($likeModel) {
            // удаляем
            if ($likeModel->delete()) {
                return [
                    'response' => true,
                    'url' => Url::toRoute('/like/element/add'),
                    'totalCount' => Like::find()->where([
                        'model' => $postData['model'],
                        'item_id' => $postData['itemId'],
                        ])->count()
                ];
            }
        // если уже удалено ранее
        } else {
            return [
                'response' => true,
                'url' => Url::toRoute('/like/element/add'),
                'totalCount' => Like::find()->where([
                    'model' => $postData['model'],
                    'item_id' => $postData['itemId'],
                    ])->count()
            ];
        }

        return [
            'response' => false
        ];
    }


}
