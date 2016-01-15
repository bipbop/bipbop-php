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
    
    public function getCode() {
        return $this->bipbopCode();
    }

    public function getSource() {
        return $this->bipbopSource();
    }

    public function getId() {
        return $this->bipbopId();
    }

    public function getMessage() {
        return $this->bipbopMessage();
    }

    public function getPushable() {
        return $this->bipbopPushable();
    }

    public function setAttributes($code, $source, $id, $message, $pushable) {
        $this->bipbopCode = $code;
        $this->bipbopSource = $source;
        $this->bipbopId = $id;
        $this->bipbopMessage = $message;
        $this->bipbopPushable = $pushable;
    }

}
