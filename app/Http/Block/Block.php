<?php

namespace App\Http\Block;

use Illuminate\Support\Facades\Hash;

class Block
{
    public $prevHash;
    public $data;
    public $timeStamp;
    public $hash;
    public $mineVar;
    public $target;
    public $subHash;
    public $mineTime;

    public function __construct($prevHash, $data)
    {
        $this->prevHash = $prevHash;
        $this->data = $data;
        $this->timeStamp = now();

        $this->hash = $this->calculateHash();
        $this->mineVar = 0;
        $this->mineTime = 0;
        $this->target = "";
        $this->subHash = "";
    }

    public function calculateHash()
    {
        $dataString = json_encode($this->data);
        return Hash::make($this->prevHash . $dataString . $this->mineVar . $this->timeStamp);
    }

    public function mine($difficulty, $maxExecutionTime = 2500)
    {
        ini_set('max_execution_time', 1200);
        if ($difficulty == 1){
            $this->target = str_repeat('a', $difficulty);
            $startTime = time();

            while (substr($this->hash, 33, $difficulty) !== $this->target) {
                $this->mineVar++;
//                dd($this->mineVar++);
                $this->hash = $this->calculateHash();
                $this->subHash = substr($this->hash, 33, $difficulty);
                $this->mineTime = time() - $startTime;
                if (time() - $startTime >= $maxExecutionTime) {
                    throw new \Exception('Mining time exceeded the maximum execution time.');
                }
            }
        }elseif ($difficulty == 2){
            $this->target = "a";
            $startTime = time();

            while (substr($this->hash, 33, 1) !== $this->target || is_numeric(substr($this->hash, 34, 1)) == false || (int)substr($this->hash, 34, 1) % 3 != 0) {
                $this->mineVar++;
                $this->hash = $this->calculateHash();
                $this->subHash = substr($this->hash, 34, 1);
                $this->mineTime = time() - $startTime;
                if (time() - $startTime >= $maxExecutionTime) {
                    throw new \Exception('Mining time exceeded the maximum execution time.');
                }
            }
        }else{
            $this->target = "a";
            $startTime = time();

            while (substr($this->hash, 33, 1) !== $this->target || is_numeric(substr($this->hash, 34, 1)) == false || (int)substr($this->hash, 34, 1) % 3 != 0 || substr($this->hash, 35, 1) != "p") {
                $this->mineVar++;
                $this->hash = $this->calculateHash();
                $this->subHash = substr($this->hash, 34, 1);
                $this->mineTime = time() - $startTime;
                if (time() - $startTime >= $maxExecutionTime) {
                    throw new \Exception('Mining time exceeded the maximum execution time.');
                }
            }
        }
    }
}
