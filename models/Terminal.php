<?php
namespace app\models;
use yii\base\Model;

class Terminal extends Model
{
    public $content;
    public $speed;
    public $result;

    public $oldSpeed;

    public function rules()
    {
        return [
            [['content', 'speed'], 'safe']
        ];
    }

    public function evaluate()
    {
        $this->oldSpeed = $this->speed;
        $startTime = microtime();
        ob_start();
        eval($this->content.';');
        $this->result = ob_get_clean();
        $endTime = microtime();
        $this->speed = array_sum(explode(' ', $endTime)) - array_sum(explode(' ', $startTime));
    }

    public function search($string)
    {

    }
}