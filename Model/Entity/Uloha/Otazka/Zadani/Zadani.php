<?php
namespace Model\Entity\Uloha\Otazka\Zadani;

use Model\Entity\Uloha\EntityInterface;

use Model\Entity\Uloha\Otazka\Zadani\Obsah\Obsah;
use Model\Entity\Uloha\Otazka\Zadani\Odpoved\Odpoved;

/**
 * Description of Zadani
 *
 * @author vlse2610
 */
class Zadani implements EntityInterface{
    /**
     * @var string 
     */
    private $type;
    /**
    *
    * @var Obsah
    */
    private $obsah;
    /**
    *
    * @var Odpoved
    */
   private $odpoved;

   public function getType() {
       return $this->type;
   }

   public function getObsah(): Obsah {
       return $this->obsah;
   }

   public function getOdpoved(): RowObjectOdpoved {
       return $this->odpoved;
   }

   public function setType($type) {
       $this->type = $type;
       return $this;
   }

   public function setObsah(Obsah $obsah) {
       $this->obsah = $obsah;
       return $this;
   }

   public function setOdpoved(Odpoved $odpoved) {
       $this->odpoved = $odpoved;
       return $this;
   }
}
