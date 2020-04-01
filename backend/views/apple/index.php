<?php

use app\Domain\Presenter;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var array $data */
?>
<style>
    .card {
        border: 1px #1e7e34 solid;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 1em;
    }

    /* Одноколоночное отображение на мобильных */
    @media (max-width: 600px) {
        .cards {
            display: flex;
            flex-direction: column;
        }
    }
</style>
<?php
/* @var string $message */
if (!empty($message)) :?>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
<?php endif ?>
<div class="apple-index">

    <h1>Яблоки на снегу</h1>

    <p>
        <?=
        Html::a('Раздать карты',
            Url::to(['/apple/generate']),
            ['class' => 'btn btn-success']) ?>
        <?=
        Html::a('Выкинуть гнилые',
            Url::to(['/apple/next-tick']),
            ['class' => 'btn btn-success']) ?>
    </p>

    <div class="cards">
        <?php foreach ($data as $model):
            /* @var Presenter $model */
            ?>
            <div class="card">
                <dl>
                    <dt>Цвет</dt>
                    <dd><?= $model->getColor() ?></dd>
                    <dt>Урожай</dt>
                    <dd><?= $model->getCreated() ?></dd>
                    <dt>Упало</dt>
                    <dd><?= $model->getFell() ?></dd>
                    <dt>Состояние</dt>
                    <dd><?= $model->getStatus() ?></dd>
                    <dt>Израсходовано</dt>
                    <dd><?= $model->getUsedPercentage() ?></dd>
                </dl>
                <?=
                Html::a('Сорвать',
                    Url::to(['apple/fall',
                        'id' => $model->getIdentity()]),
                    ['class' => 'btn btn-success'])
                ?>
                <?=
                Html::beginForm(Url::to('apple/eat'),
                    'post',
                    ['onsubmit' => 'savePrice(this,event)']); ?>
                <?= Html::textInput('piece'); ?>
                <?=
                Html::hiddenInput('id', $model->getIdentity());
                ?>
                <?= Html::submitButton('Откусить',
                    ['class' => 'btn btn-success']); ?>
                <?= Html::endForm(); ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
    function savePrice(form, event) {
        event.preventDefault();

        const csrfName = "<?=Yii::$app->request->csrfParam ?>";

        var details = {
            "id": form.elements["id"].value,
            "piece": form.elements["piece"].value,
        };
        details[csrfName] = form.elements[csrfName].value;

        var formParameters = [];
        for (var property in details) {
            var encodedKey = encodeURIComponent(property);
            var encodedValue = encodeURIComponent(details[property]);
            formParameters.push(encodedKey + "=" + encodedValue);
        }
        formBody = formParameters.join("&");

        const headers = new Headers({
            "Content-Type":
                "application/x-www-form-urlencoded;charset=UTF-8",
        });
        const request = new Request(`/apple/eat`, {
            method: "POST",
            headers: headers,
            body: formBody,
        });

        fetch(request)
            .then((response) => response.json())
            .then((json) => {
                location = '/apple/index?message=' + json.message;
            })
            .catch(() => {
                console.error('Request to apple/eat is fail');
                alert(
                    'Сбой запроса, подробности в инструментах' +
                    ' разработчика на вкладке Network')
            });
    }
</script>
