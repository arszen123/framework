<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/25/18
 * Time: 4:25 PM
 */

namespace Framework;


class DBConnection
{

    private static $path;

    /**
     * @var resource
     */
    private $file;

    protected $fileName;

    protected $validator;

    protected $workCols;

    protected $valid;

    protected $key;

    public static function initConnection($path)
    {
        self::$path = $path;
    }

    private function openConnection($type, $tmp = false)
    {
        if (!$tmp) {
            $this->file = fopen(self::$path . '/' . $this->fileName, $type);
        } else {
            $date = new \DateTime();
            $this->file = fopen(self::$path . '/' . $date->getTimestamp() . '_' .$this->fileName, $type);
        }
    }

    private function closeConnection()
    {
        fclose($this->file);
    }

    public function save()
    {
        $data = $this->getModelAsString($this);
        $this->openConnection('a');
        fwrite($this->file, $this->escape($data));
        fwrite($this->file, "\n");

        $this->closeConnection();
    }

    private function getModelAsString($item)
    {
        $workCols = explode(',', $item->workCols);
        $data = [];
        foreach ($workCols as $col) {
            $func = 'get' . ucfirst($col);
            if ($col != $item->key || $item->$func() !== null) {
                $data[] = $item->$func();
            } else {
                $data[] = $item->getLastRow()->$func() + 1;
            }
        }
        return implode(';', $data);
    }
    public function update()
    {
        $allElement = $this->getBy();

        $func = 'get' . ucfirst($this->key);

        $this->openConnection('w');

        foreach ($allElement as $element) {
            if ($this->$func() === $element->$func()) {
                fwrite($this->file, $this->escape($this->getModelAsString($this)));
            } else {
                fwrite($this->file, $this->escape($this->getModelAsString($element)));
            }
            fwrite($this->file, "\n");
        }

        $this->closeConnection();
    }

    public function getLastRow()
    {
        $this->openConnection('r');
        $cursor = -1;
        $line = '';
        fseek($this->file, $cursor, SEEK_END);
        $char = fgetc($this->file);

        /**
         * Trim trailing newline chars of the file
         */
        while ($char === "\n" || $char === "\r") {
            fseek($this->file, $cursor--, SEEK_END);
            $char = fgetc($this->file);
        }

        /**
         * Read until the start of file or first newline char
         */
        while ($char !== false && $char !== "\n" && $char !== "\r") {
            /**
             * Prepend the new char
             */
            $line = $char . $line;
            fseek($this->file, $cursor--, SEEK_END);
            $char = fgetc($this->file);
        }
        $this->closeConnection();
        if (strlen($line) > 0) {
            return $this->getArrayAsModel(explode(',', $this->workCols),
                explode(';', $line));
        }
        return new $this;
    }

    public function getBy(array $cols = array())
    {
        $this->openConnection('r');

        $workCols = $this->getCols($cols);
        $realWorkCols = explode(',', $this->workCols);
        $result = [];
        while (($line = fgets($this->file)) !== false) {
            $data = explode(';', $line);
            $break = 0;
            foreach ($workCols as $col => $value) {
                if ($data[$col] != $value) {
                    $break = 1;
                    break;
                }
            }
            if (!$break) {
                $result[] = $this->getArrayAsModel($realWorkCols, $data);
            }
        }

        $this->closeConnection();

        return $result;
    }

    private function getArrayAsModel($workCols, $data)
    {
        $result = new $this;
        for ($i = 0; $i < count($workCols); $i++) {
            $func = 'set' . ucfirst($workCols[$i]);
            if (method_exists($result, $func)) {
                $result->$func($data[$i]);
            } else {
                $reflection = new \ReflectionClass($result);
                $prop = $reflection->getProperty("id");
                $prop->setAccessible(true);
                $prop->setValue($result, $data[$i]);
            }
        }
        return $result;
    }

    private function getCols($cols)
    {
        $workCols = explode(',', $this->workCols);
        $result = [];
        foreach ($cols as $col => $value) {
            $key = array_search($col, $workCols);
            $result[$key] = $value;
        }
        return $result;
    }

    public function validate()
    {
        foreach ($this->validator as $key => $item) {
            $item .= '|';
            $validators = explode('|', $item);
            $func = 'get' . ucfirst($key);
            if ($this->$func() === null && in_array('required', $validators)) {
                $this->valid = false;
                return;
            }
            if (in_array('email', $validators) && isset($this->$key)) {
                $email = explode('@', $this->$key);
                if (count($email) !== 2 || count(explode('.',
                        $email[1])) !== 2) {
                    $this->valid = false;
                    return;
                }
            }
        }
        $this->valid = true;
    }

    private function escape($str)
    {
        return preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-;\<\>]/s', '', $str);
    }

}