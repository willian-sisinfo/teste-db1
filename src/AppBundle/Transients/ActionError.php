<?php

namespace AppBundle\Transients;

class ActionError {

    public $status;
    public $erros;

    public function __construct($message) {
        $this->setStatus('ERRO');
        $error = new \stdClass();
        $error->message = $message;

        $errorArr = array();
        $errorArr[0] = $error;

        $this->setErros($errorArr);
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getErros() {
        return $this->erros;
    }

    /**
     * @param array $erros
     */
    public function setErros($erros) {
        $this->erros = $erros;
    }



}