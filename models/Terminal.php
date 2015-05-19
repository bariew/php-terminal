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
        try {
            eval($this->content.';');
            $this->result = ob_get_clean();
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
            ob_get_clean();
        }
        $endTime = microtime();
        $this->speed = array_sum(explode(' ', $endTime)) - array_sum(explode(' ', $startTime));
    }

    public static function search($string)
    {
        if (preg_match('/^([A-Z]+[\w\-\d]+)?(::)?(.*)$/', $string, $matches) && $matches[1]) {
            $functions = $matches[2]
                ? self::classMethodsList($matches[1])
                : get_declared_classes();
        } else {
            $functions = get_defined_functions()['internal'];
        }
        return array_values(preg_grep('/^'.$string.'.*$/', $functions));
    }

    private static function classMethodsList($class)
    {
        $result = [];
        if (!class_exists($class)) {
            return $result;
        }
        foreach ((new \ReflectionClass($class))->getMethods() as $method) {
            if (!$method->isPublic() || !$method->isStatic()) {
                continue;
            }
            $result[] = "{$class}::" . $method->name;
        }
        return $result;
    }

    public static function docBlock($function)
    {
        $reflection = preg_match('/^(.*)::(.*)$/', $function, $matches)
            ? new \ReflectionMethod($matches[1], $matches[2])
            : new \ReflectionFunction($function);
        return $reflection->getDocComment();
    }
}