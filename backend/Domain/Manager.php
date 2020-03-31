<?php


namespace app\Domain;

use app\models\Apple;
use Throwable;
use Yii;
use yii\base\Exception;

class Manager
{
    const ON_HAND = 'упало/лежит на земле';
    const DEAD = 'гнилое яблоко';

    private static $colors = [
        'красный',
        'оранжевый',
        'жёлтый',
        'зелёный',
        'голубой',
        'синий',
        'фиолетовый',
    ];
    /* @var Apple $apple */
    private $apple;

    public function __construct($apple = null)
    {
        $isProper = $apple instanceof Apple;
        if ($isProper) {
            $this->apple = $apple;
        }
        if (!$isProper) {
            $this->apple = static::generate();
        }
    }

    public static function generate()
    {
        $index = mt_rand(0, count(static::$colors) - 1);
        $color = static::$colors[$index];
        $birthDay = mt_rand(0, time());

        $apple = new Apple();
        $apple->color = $color;
        $apple->created_at = $birthDay;
        $apple->save();

        return $apple;
    }

    /**
     * @throws \yii\db\Exception
     */
    public static function nextTick()
    {
        $connection = Yii::$app->getDb();

        $table = Apple::tableName();
        $command = $connection->createCommand("
DELETE FROM $table
WHERE fell_at IS NOT NULL 
AND fell_at < :NOW - 60*60*5
");
        $now = time();
        $command->bindValue(':NOW', $now);
        $command->execute();
    }

    /**
     * @param $apple
     * @throws Exception
     */
    public function fall()
    {
        $apple = $this->apple;
        $may = !static::isFell($apple);
        if ($may) {
            $apple->fell_at = time();
            $apple->status = self::ON_HAND;
            $apple->save();
        }
        if (!$may) {
            throw new Exception(
                'Яблоко упало ранее, упасть ещё раз невозможно');
        }
    }

    /**
     * @param $apple
     * @return bool
     */
    private static function isFell($apple)
    {
        return !is_null($apple->fell_at);
    }

    /**
     * @param int $piece
     * @throws Exception
     */
    public function eat($piece = 0)
    {
        $apple = $this->apple;
        $isFell = static::isFell($apple);
        if (!$isFell) {
            throw new Exception(
                'Яблоко ещё не упало, кушать не получиться');
        }
        $isDead = static::isDead($apple);
        if ($isDead) {
            throw new Exception(
                'Яблоко гнилое, кушать нельзя');
        }
        if ($piece < 0) {
            throw new Exception('
            Откусить отрицательную часть нельзя');
        }
        $isEnough = $apple->used_percentage + $piece - 100 <= 0;
        if (!$isEnough) {
            throw new Exception(
                'Заданная часть яблока больше чем от яблока осталось');
        }
        if ($isEnough) {
            $apple->used_percentage = $apple->used_percentage + $piece;
            $apple->save();
        }
    }

    /**
     * @param Apple $apple
     * @return bool
     */
    private static function isDead(Apple $apple)
    {
        return $apple->status === self::DEAD;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function kill()
    {
        $apple = $this->apple;
        $isDead = static::isDead($apple);
        if ($isDead) {
            throw new Exception(
                'Яблоко уже гнилое, быть дважды гнилым нельзя');
        }
        $isFell = static::isFell($apple);
        if (!$isFell) {
            throw new Exception(
                'Яблоко ещё не упало, сгнить не может');
        }
        if (!$isDead && $isFell) {
            $apple->status = self::DEAD;
            $apple->used_percentage = 100;
            $apple->delete();
        }

    }
}