<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RafflesiaTasks
 *
 * @author rontea
 */

namespace Drupal\rafflesia;

class RafflesiaTasks {
  //put your code here
  
  /**
   * The user ID of the user 
   * 
   * @var int
   */
  private $id;
  
  /**
   * Contructor for the RafflesiaTasks
   * 
   * @param type $id
   */
  
  function __construct($id) {
    $this->id = $id;
  }
  
  /**
   * Set the id of the tasks
   * 
   * @param type $id
   */
  
  public function setId($id){
    $this->id;
  }
  
  /**
   * return the user id
   * 
   * @return bool
   */
  
  public function getId(){
    
    try{
      
      $select = db_select('rafflesia_tasks', 'rt');
      $select->condition('rt.id',$this->id);
      $select->addField('rt','uid');
      $uid = $select->execute()->fetchField($column_index);
    }
      catch (\Exception $e){
        drupal_set_message(t('Exception ' . $e->getMessage()));
      }
    return $uid;
  }
}
