<?php

namespace Qbhy\Agora\Auth;

class Message
{
    public $salt;
    public $ts;
    public $privileges;

    public function __construct()
    {
        $this->salt = rand(0, 100000);

        $this->ts = time() + 24 * 3600;

        $this->privileges = array();
    }

    public function packContent()
    {
        $buffer = unpack("C*", pack("V", $this->salt));
        $buffer = array_merge($buffer, unpack("C*", pack("V", $this->ts)));
        $buffer = array_merge($buffer, unpack("C*", pack("v", sizeof($this->privileges))));
        foreach ($this->privileges as $key => $value) {
            $buffer = array_merge($buffer, unpack("C*", pack("v", $key)));
            $buffer = array_merge($buffer, unpack("C*", pack("V", $value)));
        }
        return $buffer;
    }

    public function unpackContent($msg)
    {
        $pos  = 0;
        $salt = unpack("V", substr($msg, $pos, 4))[1];
        $pos  += 4;
        $ts   = unpack("V", substr($msg, $pos, 4))[1];
        $pos  += 4;
        $size = unpack("v", substr($msg, $pos, 2))[1];
        $pos  += 2;

        $privileges = array();
        for ($i = 0; $i < $size; $i++) {
            $key                 = unpack("v", substr($msg, $pos, 2));
            $pos                 += 2;
            $value               = unpack("V", substr($msg, $pos, 4));
            $pos                 += 4;
            $privileges[$key[1]] = $value[1];
        }
        $this->salt       = $salt;
        $this->ts         = $ts;
        $this->privileges = $privileges;
    }
}
