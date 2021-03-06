<?php 
/**
* Subject,that who makes news
*/
class Newspaper implements \SplSubject{
    private $name;
    private $observers = array();
    private $content;
   
    public function __construct($name) {
        $this->name = $name;
    }

    //add observer
    public function attach(\SplObserver $observer) {
        $this->observers[] = $observer;
    }
   
    //remove observer
    public function detach(\SplObserver $observer) {
       
        $key = array_search($observer,$this->observers, true);
        //if($key){
        //    unset($this->observers[$key]);
        //}
        if(false !== $key){
            unset($this->observers[$key]);
        }
    }
   
    //set breakouts news
    public function breakOutNews($content) {
        $this->content = $content;
        $this->notify();
    }
   
    public function getContent() {
        return $this->content." ({$this->name})";
    }
   
    //notify observers(or some of them)
    public function notify() {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
}

/**
* Observer,that who recieves news
*/
class Reader implements \SplObserver{
    private $name;
   
    public function __construct($name) {
        $this->name = $name;
    }
   
    public function update(\SplSubject $subject) {
        echo $this->name.' is reading breakout news <b>'.$subject->getContent().'</b><br>';
    }
}



$newspaper = new Newspaper('Newyork Times');

$allen = new Reader('Allen');
$jim = new Reader('Jim');
$linda = new Reader('Linda');

//add reader
$newspaper->attach($allen);
$newspaper->attach($jim);
$newspaper->attach($linda);

//remove reader
$newspaper->detach($linda);

//set break outs
$newspaper->breakOutNews('USA break down!');







//=====output======
//Allen is reading breakout news USA break down! (Newyork Times)
//Jim is reading breakout news USA break down! (Newyork Times)


//Beware, you have written :
//
//        if($key){
//            unset($this->observers[$key]);
//        }
//
//When this should be :
//        if(false !== $key){
//            unset($this->observers[$key]);
//        }
//If the observer you want to delete is the first in your array, you will never delete it because the key would equal 0 and 0 == false as you know.

