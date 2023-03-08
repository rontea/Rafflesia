<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Drupal\rafflesia;

/**
 * Description of RafflesiaReportConditionChecker
 *
 * @author rontea
 */

class RafflesiaUserInformation{
    
/**
 *
 * @var type int
 */
 protected $user;

 /**
  * The type of user if Manager or Not
  * 
  * @var type int
  */

 protected $type;

/**
 * user list of array
 * 
 * @var type array
 */

 protected $listUser = array();


 public function __construct($user) {
     $this->user = $user;
 }


/**
 * set the user id.
 *
 * @param int
 *   The id of the user
 * 
 *   RafflesiaUserInformation::setUser().
 */

 public function setUser($userid){
   $this->user = $userid;
 }


/**
 * Gets the user id.
 *
 * @return int
 *   RafflesiaUserInformation::getUser().
 */

 public function getUser(){
   return $this->user;
 }

 /**
 * Gets the type if it is 0 or 1.
 *
 * @return int| 0 or 1
 *   RafflesiaUserInformation::Type().
 */

 public function getType(){
  return $this->type = self::userType();
 }

 /**
 * Gets the type if it is 0 or 1.
 *
 * @return int
 *   0 for Manager and 1 for WSM
 *   self::userType().
 */

 protected function userType(){

   try{   
     $select = db_select('user__field_user_infomation','uf');
     $select->condition('entity_id',$this->user);
     $select->addField('uf','field_user_infomation_type_name');
     $type = $select->execute()->fetchField();

           }catch(\Exception $e){
               drupal_set_message('Missing Database Exception '. $e->getMessage());
           }

   return $type;

 }
    
/**
* Gets the Manager ID of the user.
*
* @return int
*   An int id of the manager
*   RafflesiaUserInformation::getManagerId().
*/

public function getManagerId(){
try{
  $select = db_select('rafflesia_assoc_user','rau');
  $select->condition('rau.wsmid',$this->user);
  $select->addField('rau', 'managerId');
  $managerId = $select->execute()->fetchField();
        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }
  return $managerId;

}

/**
* Check if user is Manager or not.
*
* @return boolean
*   Boolean True if Manager or False if WSM
*   
*/

public function checkUserManager(){

    $type = self::userType();

    if($type == 0){
        $check = true;
    }else{
        $check = false;
    }

  return $check;
}


/* 
* Get All WSM under a Manager or Role of Report Sender
*
* @return array
*     Array of user information that will provide First Name and Last Name with the entity id   
*/

public function getUserInformation(){

  try{
    $select = db_select('rafflesia_assoc_user','u');
    $select->fields('u',['wsmid','managerId']);
    $select->condition('u.managerId',$this->user);
    $select->join('user__field_user_infomation','au','au.entity_id=u.wsmid');
    $select->fields('au', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }

    $rows = array();

    foreach ($data as $entry) {
    $rows[$entry['entity_id']]= ['first_name' => $entry['field_user_infomation_first_name'], 
        'last_name' => $entry['field_user_infomation_last_name']];
    }

  return $rows;

}
/**
 * Get the List of WSM
 * @return type array
 */

public function getListWSM(){

  try{
    $select = db_select('rafflesia_assoc_user','u');
    $select->fields('u',['wsmid','managerId']);
    $select->condition('u.managerId',$this->user);
    $select->join('user__field_user_infomation','au','au.entity_id=u.wsmid');
    $select->fields('au', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name','field_user_infomation_color_hexa']);

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }

    $rows = [];

    foreach ($data as $entry) {
    $rows[]= [ 'id' => $entry['entity_id'], 'first_name' => $entry['field_user_infomation_first_name'], 
        'last_name' => $entry['field_user_infomation_last_name'], 'color' => $entry['field_user_infomation_color_hexa']];
    }

  return $rows;
}

/**
 * 
 * @param type $manager_id
 * @return type
 */
public function getListWSMID($manager_id){

  try{
    $select = db_select('rafflesia_assoc_user','u');
    $select->fields('u',['wsmid','managerId']);
    $select->condition('u.managerId',$manager_id);
    $select->join('user__field_user_infomation','au','au.entity_id=u.wsmid');
    $select->fields('au', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }

    $rows = [];

    foreach ($data as $entry) {
    $rows[]= [ 'id' => $entry['entity_id'], 'first_name' => $entry['field_user_infomation_first_name'], 
        'last_name' => $entry['field_user_infomation_last_name']];
    }

  return $rows;
}


/**
 * 
 * @return type
 */

public function getIndividualInformation(){

  try{
    $select = db_select('user__field_user_infomation','au');
    $select->fields('au', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
    $select->condition('au.entity_id', $this->user,'=');
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }

  $rows = array();

    foreach ($data as $entry) {
    $rows[$entry['entity_id']] = ['first_name' => $entry['field_user_infomation_first_name'] , 'last_name' => $entry['field_user_infomation_last_name']];               
    }
  return $rows;
}

// Clean the list of WSM coming from Query

public function getWsmClean($wsm){
  if($this->type == 0){

  $listWsm = array_filter($wsm);

  $data = array();

  foreach( $listWsm as $key => $entry){
       $data[$key] = $entry;           
  }

}else{
    $data = 'No Data Available';
} 

return $data;
}
/* 
* Get All WSM Not Under a Manager
*
* @return array
*     Array of user information that will provide First Name and Last Name with the entity id   
*/
public function getIndividualInformationNotAssoc(){
  try{
    $select = db_select('user__field_user_infomation','n');
    $select->join('rafflesia_assoc_user','rau');
    $select->condition('rau.managerId',$this->user);
    $select->condition(db_and()->condition('n.field_user_infomation_type_name','1')->condition('n.entity_id', 'rau.id', 'NOT IN'));
    $select->fields('n', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);

    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\Exception $e){
          drupal_set_message('Error '. $e->getMessage());
        }

}
  /*
   * This will get the First Name of the User  
   * 
   * @return string
   *    A string First Name of the user
   */

  public function getSingleUser(){

    try{
      $select = db_select('user__field_user_infomation','rau');
      $select->condition('rau.entity_id',$this->user);
      $select->addField('rau', 'field_user_infomation_first_name');
      $firstName = $select->execute()->fetchField();
    }
          catch (\Exception $e){
            drupal_set_message('Error ' .  $e->getMessage());
          }

    return $firstName;

  }

/**
 * Get the Information of a specific user
 * @return array
 */

  public function getUserAutFill(){

  try{
    $select = db_select('user__field_user_infomation','rau');
    $select->condition('rau.entity_id',$this->user);
    $select->fields('rau', ['entity_id','field_user_infomation_first_name','field_user_infomation_last_name','field_user_infomation_type_name']);
    $info = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }
        catch (\Exception $e){
          drupal_set_message('Error ' .  $e->getMessage());
        }

  $user_info = [];      

  foreach($info as $rows){
    $user_info['entity_id'] = [$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name'],
      $rows['field_user_infomation_type_name']];
  }

  return $user_info;
  }

   /**
    * 
    * @param type $entry
    * @return type
    */

  public static function load($entry = array()) {

  $select = db_select('user__field_user_infomation', 'users');
  $select->fields('users');

  // Add each field and value as a condition to this query.
  foreach ($entry as $field => $value) {
    $select->condition($field, $value);
  }
  // Return the result in object format.
  return $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }
  /**
   * Get user's Manager
   * @param type $wsm_id
   * @return type array
   */
  public function getManager($wsm_id){
    
  try{

    $select = db_select('rafflesia_assoc_user', 'rau');
    $select->condition('rau.wsmid',$wsm_id);
    $select->join('user__field_user_infomation','users','users.entity_id=rau.managerId');
    $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
      }catch(\Exception $e){
        drupal_set_message('Error ' .  $e->getMessage());
      }
    
    $user_info = [];
    
    foreach($data as $rows){
      $user_info[] = [$rows['entity_id'],$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
     
    }  
     
    return $user_info;
    
  }
  /**
   * Get All Manager
   * @return type array
   */
  
  public function getAllManager(){
  
  try{
    $select = db_select('user__field_user_infomation','users');
    $select->condition('users.field_user_infomation_type_name',0,'=');
    $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

      }catch(\Exception $e){
        drupal_set_message('Error ' .  $e->getMessage());
      }
  $user_info = [];
    
  foreach($data as $rows){
    $user_info[] = [$rows['entity_id'],$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
     
  }  
     
  return $user_info;
      
  }
  
  /**
   * get all manager 
   * @return type array
   */
  
  public function getAllMapUser(){
    
  try{

  $select = db_select('rafflesia_assoc_user', 'rau');
  $select->join('user__field_user_infomation','users','users.entity_id=rau.wsmid');
  $select->addField('rau', 'managerId');
  $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
  $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  
    }catch(\Exception $e){
      drupal_set_message('Error ' .  $e->getMessage());
    } 
    
  $user_info = [];  

  foreach($data as $rows){
    $user_info[] = [$rows['managerId'],$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
  }  
  
  return $user_info;
  
  }
  
  /**
   * get all user from other
   * @return type array
   */
  
  public function getAllNotMyUser(){
    
  try{

  $select = db_select('rafflesia_assoc_user', 'rau');
  $select->join('user__field_user_infomation','users','users.entity_id=rau.wsmid');
  $select->condition('rau.managerId',$this->user,'<>');
  $select->addField('rau', 'managerId');
  $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
  $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  
    }catch(\Exception $e){
      drupal_set_message('Error ' .  $e->getMessage());
    } 
    
  $user_info = [];  

  foreach($data as $rows){
    $user_info[] = [$rows['managerId'],$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
  }  
  
  return $user_info;
  
  }
  
  /**
   * Get all not my user
   * @return type array
   */
  public function getAllManagerExclude(){
  
  try{
    $select = db_select('user__field_user_infomation','users');
    $select->condition(db_and()->condition('users.field_user_infomation_type_name',0,'=')->condition('entity_id',$this->user,'<>'));
    $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

      }catch(\Exception $e){
        drupal_set_message('Error ' .  $e->getMessage());
      }
  $user_info = [];
    
  foreach($data as $rows){
    $user_info[] = [$rows['entity_id'],$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
     
  }  
     
  return $user_info;
      
  }
  
  /**
   * Get the wsm under a Manager
   * @param type $manager_id
   * @return type array
   */
 
  public function getUserofManager($manager_id){
  try{

  $select = db_select('rafflesia_assoc_user', 'rau');
  $select->condition('rau.managerId',$manager_id,'=');
  $select->join('user__field_user_infomation','users','users.entity_id=rau.wsmid');
  $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
  $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  
    }catch(\Exception $e){
      drupal_set_message('Error ' .  $e->getMessage());
    } 
    
  $user_info = [];  

  foreach($data as $rows){
    $user_info[] = [$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
  }  
  
  return $user_info;
 
  }
  
  /**
   * Get all information for the Manager User Association
   * @return type array of Bundle
   */

  public function getAllUserAssoc(){
  
  /* Get MY WSM's */  
  try{

  $select = db_select('rafflesia_assoc_user', 'rau');
  $select->condition('rau.managerId',$this->user);
  $select->join('user__field_user_infomation','users','users.entity_id=rau.wsmid');
  $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name']);
  $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
  
    }catch(\Exception $e){
      drupal_set_message('Error ' .  $e->getMessage());
    }
  
    
  $user_info = [];  

  foreach($data as $rows){
    $user_info[] = [$rows['field_user_infomation_first_name'], $rows['field_user_infomation_last_name']];
  }    
  
  // Get All Manager Currently has Users
  $all_manager = $this->getAllManagerExclude();
  // count manager
  $count_manager = count($all_manager);
  
  // prepare the placeholder
  $other_user = array( 
    'wsm' => "",
    'header' => "",
    );
  
  /* get the wsm of each Manager */
  
  for($x = 0;$x < $count_manager;$x++){
   
    $other_user['wsm'][$x] = $this->getUserofManager($all_manager[$x][0]);
    $other_user['header'][$x] = $all_manager[$x];
  }
   
  /* Not Yet Registered Users , get the information */
  try{
   $select_NR = db_select('rafflesia_assoc_user', 'rau');
   $select_NR->fields('rau', ['id','wsmid']);
   // get only the id value
   $keys = $select_NR->execute()->fetchAllKeyed();
    }catch(\Exception $e){
      drupal_set_message('Error ' .  $e->getMessage());
    } 
    
  $user_notin = [];
  
  // if not empty filter 
  if(!empty($keys)){  
    try{
      // if there are association 
      $select = db_select('rafflesia_assoc_user', 'rau');
      $select->join('user__field_user_infomation','users');
      $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name','field_user_infomation_type_name']);
      $select->condition( db_and()->condition('users.field_user_infomation_type_name',0,'<>')->condition('users.entity_id',$keys,'NOT IN'));
      $wsm_not_added = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\Exception $e){
          drupal_set_message('Error ' .  $e->getMessage());
        }  
           
  }else{
    try{
      // if there is no association get all
      $select = db_select('user__field_user_infomation','users');
      $select->fields('users',['entity_id','field_user_infomation_first_name','field_user_infomation_last_name','field_user_infomation_type_name']);
      $select->condition('users.field_user_infomation_type_name',0,'<>');
      $wsm_not_added = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
         
        }catch(\Exception $e){
          drupal_set_message('Error ' .  $e->getMessage());
        }
  }   
  /* Get the information */
  foreach($wsm_not_added as $rows2){
    $user_notin[$rows2['entity_id']] = [ 'first_name' => $rows2['field_user_infomation_first_name'], 
    'last_name' => $rows2['field_user_infomation_last_name']
    ];
  }
 
  /* List of user the has been associated*/
  
  $bundle = array (
    'names' => $user_info, // get the names under a Manager
    'not_in' =>  $user_notin, // get information not under the manager
    'users' => $other_user, // get the information of other manager user
  );

  return $bundle;
    
  }
      
}