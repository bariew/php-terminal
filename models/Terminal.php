<?php
namespace app\models;
use yii\base\Model;

class Terminal extends Model
{
    public $content;
    public $speed;
    public $language;
    public $result;

    public $oldSpeed;

    public function rules()
    {
        return [
            [['content', 'speed', 'language'], 'safe']
        ];
    }

    public function evaluate()
    {
        $this->oldSpeed = $this->speed;
        $startTime = microtime();
	ob_start();
	if ($this->language == 'python') {
	    $file = sys_get_temp_dir() . '/tmp.py';
	    file_put_contents($file, $this->content);
	    echo shell_exec("python3 {$file} 2>&1");
	} else {
            eval($this->content.';');
	}
        $this->result = ob_get_clean();
        $endTime = microtime();
        $this->speed = array_sum(explode(' ', $endTime)) - array_sum(explode(' ', $startTime));
    }

    public static function search($string)
    {
        if (preg_match('/^(\w*\\\\[-\\\\\w\d]+|[A-Z]+[-\\\\\w\d]+)(::)?(.*)$/', $string, $matches)
        ) {
            $functions = $matches[2]
                ? self::classMethodsList($matches[1])
                : get_declared_classes();
        } else {
            $functions = get_defined_functions()['internal'];
        }
        $string = preg_replace('/^\\\\(.*)$/', '$1', $string);
        return array_values(preg_grep('/^'.preg_quote($string).'.*$/', $functions));
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
        if(preg_match('/^(.*)::(.*)$/', $function, $matches)) {
            $reflection = new \ReflectionMethod($matches[1], $matches[2]);
        } elseif (preg_match('/([A-Z]+[\w\-\d]+)$/', $function, $matches)) {
            $reflection = new \ReflectionClass('\\'.$function);
        } else {
            $reflection = new \ReflectionFunction($function);
        }
        return "<h3>$function</h3><br>"
            .'<pre>'.($reflection->getDocComment() ? : $reflection).'</pre>';
    }

    public static function languageList()
    {
    	return ['php', 'python'];
    }
}
