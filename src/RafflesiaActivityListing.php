<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\rafflesia;

use Drupal\rafflesia\RafflesiaUserInformation;

/**
 * Description of RafflesiaActivityListing
 *
 * @author rontea
 */
class RafflesiaActivityListing {
  
 /**
  *
  * @var type int
  */
    
  private $user;
 
  /**
   * 
   * @param type $user
   */
  
  public function __construct($user) {
      $this->user = $user;
  }
  
  /**
   * Get The Activity Information for Edit
   * 
   * @param type $id
   * @return array
   */
  
  public function getAct($id){
   
  $activity_info = [];
  
    try{
      
     /* GET THE ACTIVITY */
    $select = db_select('rafflesia_tasks','rt');
    $select->condition('rt.id',$id,'=');
    $select->fields('rt',['id','uid','activity_id','tasks_subject','tasks_timestamp','tasks_volume']);
    

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
     
        }catch(\Exception $e){
           drupal_set_message('Missing Database Exception '. $e->getMessage());
        }
    foreach($data as $entry){
      $activity_info = array( 'id' => $entry['id'],
      'uid' => $entry['uid'], 
      'tasks_subject' => $entry['tasks_subject'], 
      'tasks_timestamp'=> $entry['tasks_timestamp'],  
      'tasks_volume' => $entry['tasks_volume'],
      'activity_id' => $entry['activity_id'],  
      );
    }   
  
  return $activity_info;
  }
  
  /**
   * 
   * @return type array 
   */
 
  public function getActList(){
   
  try{
      
     /* GET THE ACTIVITY */
    $select = db_select('rafflesia_assoc_user','rau');
    $select->condition('rau.wsmid',$this->user);
    $select->fields('rau',['managerid']);
    $select->join('rafflesia_activities','ra','ra.uid = rau.managerid');
    $select->fields('ra',['id','activity_name']);

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
     
        }catch(\Exception $e){
           drupal_set_message('Missing Database Exception '. $e->getMessage());
        }
      
    return $data;  
       
  }
  /**
   * 
   * @return type array 
   */
  public function getManActList(){
      
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
  
  
  /*
   *Get the activity 
   * 
   * user : id of user
   * status: if activity is active or deleted
   * raw: if data is process or not true or false
   * 
   * usage: $sample = RafflesiaActivityListing::activityQuery($user,1,false);
   *  
   **/
  
  public function activityQuery($user,$status,$raw){
    
    /* Set the type of form to use will be returned */
    
    
    $rows = array(); 
    
    $user_info = new RafflesiaUserInformation($user);
    
   
    $check = $user_info->checkUserManager();
    
    if($status < 1){
      
      $rows = "Status has only 1 = active and 0 = deleted";
      
    }else{
      if($check){
        // get the listing of activity Manager
        $select = db_select('rafflesia_activities','ra');
        $select->condition('ra.uid',$user);
        $select->condition('ra.activity_status',$status);
        $select->fields('ra',['id','activity_name']);
        $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        
      }else{
        
        // get the listing of activity WSM 
        $select = db_select('rafflesia_assoc_user','rau');
        $select->condition('rau.wsmid',$user);
        $select->fields('rau',['managerid']);
        $select->join('rafflesia_activities','ra','ra.uid = rau.managerid');
        $select->condition('ra.activity_status',$status);
        // db_and()->condition('ra.uid','rau.managerid')->condition('ra.activity_status',1)
        $select->fields('ra',['id','activity_name']);
        $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

      }
      
      if($raw){
        $rows = $data;
      }else{
        foreach ($data as $entry) {
          $rows[$entry['id']]= array('activity_name' => $entry['activity_name']);      
        }
      }
      
      // if row is empty
      if(empty($rows)){
          $rows = "No Data Available";
      } 
    }
    return $rows;
  }
  
  /*
   * user : the id of user
   * title : title of form
   * required: if form is required
   * usage:
   *  
   * $data = RafflesiaActivityListing::listActivityDropDown($user,'Activity',True);
   * $form['activity_name'] = $data;
   * 
  */
  public function listActivityDropDown($user,$title,$required){
    
    $status = 1;
    $raw = true;
    
    $rows = self::activityQuery($user, $status, $raw);
    
    $results = array();
    
    // perform the query 
    
    foreach ($rows as  $entry){
        $results[$entry['id']] = $entry['activity_name'];
    }
    
    // build the array form 
    
    $type = 'select';
   
    $data =  array(
      '#type' => $type,
      '#title' => t($title),
      '#options' => $results,
      '#required' => $required,
    );
        
    return $data;
    
  }
  
}
