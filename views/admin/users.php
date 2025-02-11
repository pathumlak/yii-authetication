<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

?>

<p>
    <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'username',
        'role',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {edit} {delete}',
            'buttons' => [
                'edit' => function ($url, $model) {
                    return Html::a('Edit', ['edit', 'id' => $model->id], [
                        'class' => 'btn btn-primary btn-sm',
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('View', ['view', 'id' => $model->id], [
                        'class' => 'btn btn-info btn-sm',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this user?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ],
]); ?>