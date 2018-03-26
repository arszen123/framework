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

    public static function initConnection($path)
    {
        self::$path = $path;
    }

    private function openConnection($type)
    {
        var_dump(self::$path .'/' . $this->fileName);
        $this->file = fopen(self::$path .'/' . $this->fileName, $type);
    }

    private function closeConnection()
    {
        fclose($this->file);
    }

    public function save()
    {
        $this->openConnection('a');

        $workCols = explode(',', $this->workCols);
        $data = [];
        foreach ($workCols as $col) {
            $func = 'get' . ucfirst($col);
            if ($col === 'password') {
                $data[] = hash('sha256',$this->$func());
            } else {
                $data[] = $this->$func();
            }
        }
        fwrite($this->file, implode(';', $data));
        fwrite($this->file, "\n");

        $this->closeConnection();
    }

    public function getBy(array $cols)
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
        for ($i=0;$i<count($workCols);$i++) {
            $func = 'set' . ucfirst($workCols[$i]);
            $result->$func($data[$i]);
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
            $validators = explode('|', $item);
            if (!isset($this->$key) && in_array('required', $validators)) {
                $this->valid = false;
                return;
            }
            if (in_array('email', $validators) && isset($this->$key)) {
                $email = explode('@', $this->$key);
                if (count($email) !== 2 || count(explode('.', $email[1])) !== 2) {
                    $this->valid = false;
                    return;
                }
            }
        }
        $this->valid = true;
    }


}