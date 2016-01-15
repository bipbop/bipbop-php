<?php

namespace BIPBOP\Client;

/**
 * BIPBOP Exception
 */
class Exception extends \Exception {

    protected $bipbopCode;
    protected $bipbopSource;
    protected $bipbopId;
    protected $bipbopMessage;
    protected $bipbopPushable;

    public function getBIPBOPCode() {
        return $this->bipbopCode;
    }

    public function getBIPBOPSource() {
        return $this->bipbopSource;
    }

    public function getBIPBOPId() {
        return $this->bipbopId;
    }

    public function getBIPBOPMessage() {
        return $this->bipbopMessage;
    }

    public function getBIPBOPPushable() {
        return $this->bipbopPushable;
    }

    public function setAttributes($code, $source, $id, $message, $pushable) {
        $this->bipbopCode = (int) $code;
        $this->bipbopSource = $source;
        $this->bipbopId = $id;
        $this->bipbopMessage = $message;
        $this->bipbopPushable = $pushable;
    }

}
