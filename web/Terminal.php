<?php
/**
 * Created by PhpStorm.
 * User: pt
 * Date: 27.04.15
 * Time: 15:02
 */

class Terminal
{
    public $languages = array('php', 'c++', 'perl', 'yii2');
    public $query;
    public $lang;
    public $speed;
    public $result;

    public function __construct()
    {
        require(__DIR__ . '/../vendor/autoload.php');

        require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

        if (!$this->lang  = @$_POST['lang']) {
            return true;
        } else if (!$this->query = @$_POST['newPhp']) {
            return true;
        }
        $startTime = microtime();
        ob_start();
        $this->execute($this->lang, $this->query);
        $this->result = ob_get_clean();
        $endTime = microtime();
        $this->speed = array_sum(explode(' ', $endTime)) - array_sum(explode(' ', $startTime));
    }

    function execute($lang, $string)
    {
        switch($lang){
            case 'perl': $this->exPerl($string);
                break;
            case 'c++' : $this->evalC($string);
                break;
            case 'yii2' :
            default: $this->exPhp($string);
        }
    }

    function exPhp($string)
    {
        eval($string.';');
    }

    function exPerl($string)
    {
        $string = "perl -e '$string';";
        exec($string,$output);
        foreach($output as $line){
            echo $line;
        }
    }

    function evalC($string)
    {
        $filePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'eval.c';
        file_put_contents($filePath, $string);
        exec("cint -E {$filePath} -c -p", $output);
        foreach($output as $line){
            echo $line;
        }
    }
}