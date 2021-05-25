<?php
namespace Model\Entity\Uloha\Otazka\Zadani\Odpoved\Data;

use Model\Entity\Uloha\EntityInterface;

/**
 * Description of Data
 *
 * @author vlse2610
 */
class Data implements EntityInterface {
    /**
     *
     * @var string 
     */
    private $label;
    
    /**
     *
     * @var array of string 
     */
    private $content;
    
    /**
     *
     * @var int 
     */
    private $ok;
    
    public function getLabel() {
        return $this->label;
    }

    public function getContent() {
        return $this->content;
    }

    public function getOk() {
        return $this->ok;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    public function setContent(array $content) {
        $this->content = $content;
        return $this;
    }

    public function setOk($ok) {
        $this->ok = $ok;
        return $this;
    }
}
