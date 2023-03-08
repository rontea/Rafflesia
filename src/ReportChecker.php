<?php
/**
 * Check if the report can proceed 
 */

namespace Drupal\rafflesia;

/**
 * Description of ReportChecker
 *
 * @author rontea
 */
class ReportChecker {
  
  /**
   *
   * @var type date
   */
  
  private $from;
  
  /**
   *
   * @var type Date
   */
  
  private $to;
  
  /**
   * 
   */
  
  public function __construct($from,$to) {
    
    $this->from = $from;
    $this->to = $to;
  }
  
  /**
   * 
   * @param type $from
   */
  
  public function setFrom($from){
    $this->from = $from;
  }
  /**
   * 
   * @param type $to
   */
  
  public function setTo($to){
    $this->to = $to;
  }
  /**
   * 
   * @return type
   */
  
  public function getFrom(){
    return $this->from;
  }
  
  /**
   * 
   * @return type
   */
  
  public function getTo(){
    return $this->to;
  }
  
  /**
   * Check if the query can proceed or not
   * 
   * @return boolean
   */
  public function checkProceed(){
    
    $from = $this->from;
    $to = $this->to;
    
    if(empty($from)){
      $boolean = false;
    }else if (!empty($from) && empty ($to)){  
      $boolean = true;     
    }else if (!empty($from) && !empty($to)){
      if($from > $to){
        $boolean = false;
      }else{
        $boolean = true;
      }    
    }else{
      
      if(empty($from) && !empty($to)){
        $boolean = false;
      } 
    }

    return $boolean;
  }
  
}
