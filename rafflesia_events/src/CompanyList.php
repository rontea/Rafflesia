<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\rafflesia_events;

/**
 * Description of CompanyList
 *
 * @author rontea
 */

class CompanyList {
 
  /**
   * The id of the Company
   * @var type int
   */
  
  private $id;
  
  /**
   * The Name of the Company
   * @var type string
   */
 
  private $companyName;
  
  
  /**
   * Constructor set the id
   * @param type $id
   */
  
  public function __construct($id) {
    $this->id = $id;
  }
  
  /**
   * Set the id
   * @param type $id
   */
  
  public function setId($id){
    $this->id = $id;
  }
  
  /**
   * Return the Company name
   * @return type string
   */

  public function getCompanyName() {
    return $this->companyName;
  }
  
  public function getEvents(){
      
    try{
        $select = db_select('rafflesia_activities','ra')
                ->fields('ra',['id','activity_name'])
                ->condition('ra.uid',$this->user,'=');
        $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);   
      }
          catch (\Exception $e){
             drupal_set_message('Missing Database Exception '. $e->getMessage());
          }
      return $data;
  }
 
  
}
