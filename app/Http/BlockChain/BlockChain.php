<?php

namespace App\Http\BlockChain;

use App\Http\Block\Block;
use App\Models\Billchain;
use Illuminate\Support\Facades\Hash;

class BlockChain
{
    public $difficulty;
    public $chain;

    public function __construct($difficulty) {
        if (sizeof(Billchain::all()) == 0){
            $preHash0 = Hash::make("isgenesis");
            $genesisBlock = new Block($preHash0, ['isgenesis' => true]);
            $this->difficulty = $difficulty;
            $this->chain = [$genesisBlock];
        }
    }

    public function getLastBlock() {
        return end($this->chain);
    }

    public function addBlock($data) {
        $lastBlock = $this->getLastBlock();
        $newBlock = new Block($lastBlock->hash, $data);
        $start = microtime(true);
        $newBlock->mine($this->difficulty);
        $end = microtime(true) - $start;

        $this->chain[] = $newBlock;
    }

    public function isValid() {
        for ($i = 1; $i < count($this->chain); $i++) {
            $currentBlock = new Block($this->chain[$i]->prevHash, $this->chain[$i]->data);
            $currentBlock->prevHash =$this->chain[$i]->prevHash;
            $currentBlock->data =$this->chain[$i]->data;
            $currentBlock->timeStamp =$this->chain[$i]->timeStamp;
            $currentBlock->hash =$this->chain[$i]->hash;
            $currentBlock->mineVar =$this->chain[$i]->mineVar;
            $currentBlock->target =$this->chain[$i]->target;
            $currentBlock->subHash =$this->chain[$i]->subHash;
            $currentBlock->mineTime =$this->chain[$i]->mineTime;

            $prevBlock = $this->chain[$i - 1];
            $stringBlock = $currentBlock->prevHash . json_encode($currentBlock->data) . $currentBlock->mineVar . $currentBlock->timeStamp;
            if (!Hash::check($stringBlock, $currentBlock->calculateHash())) {
                return false;
            }
            if ($currentBlock->prevHash != $prevBlock->hash) {
                return false;
            }
        }
        return true;
    }
}
