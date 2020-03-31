<?php


namespace app\Domain;


use app\models\Apple;

class Presenter
{
    private $identity;
    private $color;
    private $created;
    private $fell;
    private $status;
    private $usedPercentage;

    private function __construct(
        $identity, $color, $created, $fell, $status, $used)
    {
        $this->identity = $identity;
        $this->color = $color;
        $this->created = $created;
        $this->fell = $fell;
        $this->status = $status;
        $this->usedPercentage = $used;
    }

    public static function make($apple)
    {
        /* @var Apple $apple */
        $identity = (int)$apple->id;
        $color = $apple->color;
        $created = gmdate("Y-m-d H:i", $apple->created_at);
        $fell = $apple->fell_at
            ? gmdate("Y-m-d H:i", $apple->fell_at)
            : 'Никогда';
        $status = $apple->status;
        $used = number_format($apple->used_percentage) . '%';

        return new static(
            $identity, $color, $created, $fell, $status, $used);
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getFell()
    {
        return $this->fell;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getUsedPercentage()
    {
        return $this->usedPercentage;
    }
}