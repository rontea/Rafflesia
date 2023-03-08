<?php

/***
 * 
 */

namespace Drupal\rafflesia;

use Drupal\rafflesia\RafflesiaUserInformation;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Description of RafflesiaGetReport
 *
 * @author rontea
 */

class RafflesiaGetReport {
  
  /**
   *
   * @var type array
   */
  
  private $data = array();
  /**
   * id of the user
   * 
   * @var type int 
   */
  private $user;
  
  /**
   *
   * @var type date
   */
  
  private $to;
  
  /**
   *
   * @var type date
   */
  
  private $from;
  
  /**
   *
   * @var type array 
   */
  
  private $id = array();
  
  /**
   *
   * @var type string
   */
  
  private $forTeam = 0;
  
  /**
   *
   * @var type Constant array
   */
  
  private $QUARTER1 = array('days' =>64,'description' => 'January 1 - March 31','from' => '1/1','to' => '3/31');
  
  /**
   *
   * @var type Constant array
   */
  
  private $QUARTER2 = array('days' =>65,'description' => 'April 1 – June 30','from' => '4/1','to' => '6/31');
  
  /**
   *
   * @var type Constant array
   */
  private $QUARTER3 = array('days' =>66,'description' => 'July 1 – September 30','from' => '7/1','to' => '9/31');
  
  
  /**
   *
   * @var type Constant array
   */
  private $QUARTER4 = array('days' =>65,'description' => 'October 1 – December 31','from' => '10/1','to' => '12/31');
  
  /**
   *
   * @var type Constant array
   */
  
  private $YEAR = array('days' =>365,'description' => 'January 1 - December 31','from' => '1/1','to' => '12/31');
  
  /**
   *
   * @var type string
   */
  
  private $dateFormat = 'Y-m-d';
  
  /**
   *
   * @var type string
   */
  
  private $timeFormat = 'g:i:s A';
  
  /**
   *
   * @var type array
   */
  
  private $activity_with_total = array();
  
  /**
   * the total volume
   *
   * @var type int 
   */
  
  private $total_volume;
  
  /**
   * bundle of data
   *
   * @var type array
   */

  private $team_bundle = [];
  
  /**
   * Set from and to
   * 
   * @param type $from
   * @param type $to
   */

  public function __construct($from,$to) {
     $this->from = $from;
     $this->to = $to;
  }

  /**
   * set the user id for query
   *  
   * @param type int
   */
 
  public function setUser($userid){  
    
    $this->user = $userid;
    

  }
  /**
   * set the type of report 1 if for team and 0 if for single
   * 
   * @param type $team
   */
  public function setToTeam($team){
    if($team == 0){
      $this->forTeam = 0;
    }else{
      $this->forTeam = 1;
    }
  }
  
  /**
   * set the date if not define in contructor or update from to
   * 
   * @param type $from
   * @param type $to
   */
  
  public function setDates($from,$to){
    
    $this->from = $from;
    $this->to = $to;
  }
  
  
  /**
   * Get the user if provided
   * 
   * @return type int
   */
  
  public function getUser(){
    return $this->user;
  }
  
  /**
   * get the activity with number of volume
   * 
   * @return type array
   */
  
  public function getData(){
    return $this->data;
  }
  
  /**
   * get the total of volume of each activity
   * 
   * @return type array
   */
  
  public function getAWTotal(){
    return $this->activity_with_total;
  }
  
  /**
   * Get the grand total of all the activities
   * 
   * @return type int
   */
  public function getVolumeTotal(){
    return $this->total_volume;
  }
  
  /**
   * Get all the activities of the current user
   * 
   * @return type array
   */
  
  public function getAllActivity(){
    
    $userInfo = new RafflesiaUserInformation($this->user);
    
    if($userInfo->checkUserManager()){
      $id = $this->user;
    }else{
      $id = $userInfo->getManagerId();
    }
    try{
      $select = db_select('rafflesia_activities','ra');
      $select->condition('ra.uid',$id);
      $select->fields('ra',['id','activity_name']);

      $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

          }catch(Exception $e){
            drupal_set_message('Missing Database Exception '. $e->getMessage());
          }
    $activityList = array();
    
    foreach ($data as $key => $rows){
      
      $activityList[$key] =array( 'id' => $rows['id'] , 'activity_name' => $rows['activity_name']);
     
    }
    return $activityList;
  }
  
  public function getTeamBundle(){
    return $this->team_bundle;
  }
  
 
 /**
  * Generate the report to Join Activity and Tasks Table
  * 
  * @set array 
  */ 
  
 protected function getJoinActivity(){
    /* set from and to*/
    $from = $this->from;
    $to = $this->to;
   
    /* Query */
    try{
    $select = db_select('rafflesia_activities', 'ra');
    $select->join('rafflesia_tasks','rt','ra.id = rt.activity_id');
   
        $select->fields('ra' , ['activity_name'])
        ->fields('rt' , ['activity_id','tasks_subject','tasks_volume','tasks_timestamp','uid','id']);
        $select->condition('rt.tasks_timestamp',array($from,$to),'BETWEEN')
        ->condition('rt.uid',$this->user,'=');
           
    $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    
        }catch(Exception $e){
           drupal_set_message('Missing Database Exception '. $e->getMessage());
        }
    $x = 0;    
    $row_table_activities = [];
    /* Get Path */
    try{
      // Get the Previous Path 
      $current_path = \Drupal::service('path.current')->getPath();
      // Provides a default path validator and access checker.
      $url_object = \Drupal::service('path.validator')->getUrlIfValid($current_path); 
      // Get the Route Name
      $route_name = $url_object->getRouteName();
      // Get the Route Parameters of the link $current path
      $route_parameters = $url_object->getrouteParameters();
      // Check the Route Name to get the required to go back to previous page
     if($route_name == 'rafflesia.report_today'){
       $path = 0;
     }else{
       $path = 1;
       $from_route = $route_parameters['from'];
       $to_route = $route_parameters['to'];
     }
    }
        catch (\Exception $e){
          drupal_set_message(t('Exception ' . $e->getMessage()));
        }
    /* Create the Action */
    try{  
      // get the route link 
      $item =  Url::fromRoute('rafflesia.report_today_edit');
      $item_delete =  Url::fromRoute('rafflesia.report_today_edit_delete');  
     
    } catch (\Exception $e){
          drupal_set_message(t('Exception ' . $e->getMessage()));
        }
        
    // Map the query output 
    try{
      foreach ($data as $key =>  $row){  
        $row_table_activities[$key] =  array($row['activity_id'],$row['activity_name'], $row['tasks_subject'],
        $row['tasks_volume'], date($this->dateFormat . ' ' . $this->timeFormat ,
        $row['tasks_timestamp']),Link::fromTextAndUrl(t('Update'), 
        $item->setRouteParameters(
            array('id' => $row['id'], 'path' => $path, 'from' => $from_route, 'to' => $to_route)))->toString(),
        Link::fromTextAndUrl(t('Delete'), 
            $item_delete->setRouteParameters(
            array('id' => $row['id'], 'path' => $path, 'from' => $from_route, 'to' => $to_route)))->toString(),  
        );   
      }
    }
      catch (\Exception $e){
         drupal_set_message(t('Exception ' . $e->getMessage()));
      }
   // add the data ready for extraction 
   $this->data = $row_table_activities;
   
   // get data
   $temp = 0;
   
   // total of each activity
   $total = 0;
   
   // over all total volume
   $grand_total = 0;
   
   // get all the aactivity 
   $activity_name = $this->getAllActivity();
   
   // count the data that was aquired
   $count_inputs = count($row_table_activities);
   // count the activity name 
   $limit = count($activity_name);
   // create the placeholder
   $activity_temp = [];
   
   /* Get the activity name only */
   for($z = 0; $z < $limit; $z++ ){
     $activity_temp[$z] = array( 'id' =>  $activity_name[$z]['id'] , 
       'activity_name' => $activity_name[$z]['activity_name'] , 'total' => 0);
   }
   
   // get the count of activity temp
   $limit_temp = count($activity_temp);
  
   /* Map The Data */
   for($y = 0; $y < $limit_temp; $y++ ){
     
      for($x = 0; $x < $count_inputs; $x++){
         
        if($activity_temp[$y]['id'] == $row_table_activities[$x][0]){
           
          $temp = $row_table_activities[$x][3];   
          $total = $temp + $total;
           
        }
      }
     // set the total
     $activity_temp[$y]['total'] = $total;
     // set the grand total
     $grand_total = $grand_total + $total;
     // reset data to new section
     $total = 0;
     $temp = 0;
   }
   // set the mapping for extraction
   $this->activity_with_total = $activity_temp;
   $this->total_volume = $grand_total;
  }
  
  protected  function getTeamActivity(){
    
    // set the from and to
    $from = $this->from;
    $to = $this->to;
    
    // check if the user is a manager or not to set the manager id
    $user_information = new RafflesiaUserInformation($this->user);
    if($user_information->checkUserManager()){
      $id = $this->user;
    }else{
      $id = $user_information->getManagerId();
    }
    // the the id of the person in query
    $user_information->setUser($id);
 
    // get the list of wsm
    $wsm = $user_information->getListWSM();
    // get the list of activity 
    $activity_name = $this->getAllActivity();
    // count wsm
    $wsm_count = count($wsm);
    // count activity
    $act_name_count = count($activity_name);
    
    /* Set the following variable */
    $activities = [];
    $wsm_fname = [];
    $act_name_only = [];
    $activity_temp = [];
    $color = [];
    // set the total
    $total = 0;
    
    // query the information 
    for($x = 0; $x < $wsm_count; $x++){
      // get the first name of each user
      $wsm_fname[] = $wsm[$x]['first_name'];
      $color[$x] = $wsm[$x]['color'];
      // get the person's data
      try{
      $select = db_select('rafflesia_activities', 'ra');
      $select->join('rafflesia_tasks','rt','ra.id = rt.activity_id');
          $select->fields('ra' , ['activity_name','id'])
          ->fields('rt' , ['tasks_subject','tasks_volume','tasks_timestamp','uid']); 
          $select->condition('rt.tasks_timestamp',array($from,$to),'BETWEEN')
          ->condition('rt.uid',$wsm[$x]['id'],'=');
          
      $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

          }catch(Exception $e){
             drupal_set_message('Missing Database Exception '. $e->getMessage());
          }

      // get the activity of all user to be listed in one
      foreach($data as $row){
           $activities[] =  array( 'id' => $row['id'],'volume' => $row['tasks_volume'], 'uid' => $row['uid']);  
      }
      // count the return value for processing 
      $count = count($activities);
        /* Map The Data */
       for($y = 0; $y < $act_name_count; $y++ ){
         // if max is reach generate the activity that will appear on the left side
         if($x == ( $wsm_count -1)){
            $act_name_only[$y] = $activity_name[$y]['activity_name'];
         }
          // make sure that array will not be offset
         $activity_temp[$y][$x] = 0;
          
          // map the query to the activity 
          for($z = 0; $z < $count  ; $z++){
            // check to map
            if($activity_name[$y]['id'] == $activities[$z]['id']){              
               $activity_temp[$y][$x] = $activities[$z]['volume'] + $activity_temp[$y][$x];
            }
          }
          // generate the total for each activity volume
          $total = $total + $activity_temp[$y][$x];
          
       }
      // get the last array to generate total at the bottom 
      $direct = $act_name_count+1;
      // map the total to the bottom
      $activity_temp[$direct][$x] =  $total;
      // reset total
      $total = 0;
      // reset placeholder of activities of each user
      $activities = []; 
    }
    
    /* Add the Necessary Information */
    for($y = 0; $y < $act_name_count; $y++ ){
      array_unshift($activity_temp[$y], $act_name_only[$y]);
    }
    /* Add the Total Name */
    array_unshift($activity_temp[$act_name_count+1], "Total");
    array_unshift($wsm_fname,t("Activities"));
    
    /* Add the Bundle */
    $this->team_bundle = array( 
      'name' => $wsm_fname , 
      'volume' => $activity_temp,
      'color' => $color,
    );
    
  }

  /**
   * Choose the report 
   */
  
  protected function choose(){
    // choose the filter of the report
    if($this->forTeam == 0){
      $this->getJoinActivity();
    }else{
      $this->getTeamActivity();      
    }
  }

  public function generateAction(){
    
    $from = $this->from;
    $to = $this->to;
    
    if(empty($from)){
        // if from is null
        return t('From is not set');
      
    }else if (!empty($from) && empty ($to)){
      // from is available and to is empty
      $this->from = strtotime(''.$from.' 00:00:00');
      $this->to = strtotime(''.$from.' 24:00:00');
        
        /* Find the Report */
        $this->choose();
        
       return t('Report Generated For This Day Only '. date($this->dateFormat .' '. $this->timeFormat ,$this->from)
               . ' To ' . date($this->dateFormat .' '. $this->timeFormat ,$this->to));
       
    }else if (!empty($from) && !empty($to)){
        // return the report
    
      if($from > $to){
        
        return t('"From" cannot be Greater than "To"');
        
      }else if($from == $to){
        
        $this->from = strtotime(''.$from.' 00:00:00');
        $this->to = strtotime(''.$from.' 24:00:00');
          
          /* Find the Report */
        
        $this->choose();
      
        return t('My Report Single Day');
        
      }else{
        $this->from = strtotime(''.$from.' 00:00:00');
        $this->to = strtotime(''.$to.' 23:59:59');
        /* Find the Report */
        
        $this->choose();
        return t('Report Generated From '. date($this->dateFormat .' '. $this->timeFormat ,$this->from)
               . ' To ' . date($this->dateFormat .' '. $this->timeFormat ,$this->to));
      }  
    }else{
      if(empty($from) && !empty($to)){
        return t('Cannot Generate From date is missing');
      }
    }
  }
  
  public function taskPerHour($start){
    
    $day = 24;
    
    $userInfo = new RafflesiaUserInformation($this->user);
    // set the wsm
    $wsm = []; 
    /* check if the person who request is a manager or a wsm */
    if($userInfo->checkUserManager()){
      $wsm = $userInfo->getListWSM();
    }else{
      $manager_id = $userInfo->getManager($this->user);
      $wsm = $userInfo->getListWSMID($manager_id[0][0]);
    }
    
    $num_wsm = count($wsm);
    
    /* set from and to*/
    $from = $this->from;
    $to = $this->to;
    $data_set = [];
    $row_volume = [];
    $temp_data_set = [];
    for($x=0;$x<$num_wsm;$x++){
        /* Query */
        try{
        $select = db_select('rafflesia_tasks', 'rt');
            $select->fields('rt' , ['tasks_volume','tasks_timestamp']);
            $select->condition('rt.tasks_timestamp',array($from,$to),'BETWEEN')
            ->condition('rt.uid',$wsm[$x]['id'],'=');
        $select->orderBy('rt.tasks_timestamp', 'ASC');
        $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

            }catch(Exception $e){
               drupal_set_message('Missing Database Exception '. $e->getMessage());
            }
        // Map the query output 
        foreach ($data as $row){   
          $row_volume[] =  [$row['tasks_timestamp'],$row['tasks_volume']];
        }
        $temp = 0;
        $count_row = count($row_volume);
        // check each information
        for($y=0;$y<$day;$y++){
          $from_con =  $y;
          $to_con =  ($y+1);

          $data_set[$x][$y] = 0;
          // check each data
          if(!empty($row_volume)){
            for($z=0;$z<$count_row;$z++){
              // data set allocate to right time

              if(date('H',$row_volume[$z][0]) >=  $from_con && date('H',$row_volume[$z][0]) < $to_con){
                // add the data
                $data_set[$x][$y] = $row_volume[$z][1] + $data_set[$x][$y];
              }            
            }
          }   
        }
        $row_volume = "";  
        /* Map Data base on start */
        
        $temp_count = ($day-1) - $start;
        $counter = $start;
        $j=0;
        for($i=0;$i<$day;$i++){
          $temp_data_set[$i] = 0;
          if($i > $temp_count){
            $temp_data_set[$i] = $data_set[$x][$j];
            $j++;
          }else{
            $temp_data_set[$i] = $data_set[$x][$counter];
            $counter++;
          }
        }
        
       $data_set[$x] = "";
       $data_set[$x] = $temp_data_set;
       $temp_data_set = "";
    }
    $wsm_data_set = []; 
    
    /* Get Only The Right Data */
    foreach($wsm as $row_wsm){
      $wsm_data_set[] = $row_wsm['first_name']; 
    }
    
    return $task_per_hour = [
      'names' => $wsm_data_set,
      'task' => $data_set
    ]; 
  }
  
  public function taskPerDay($start) {
    
    $userInfo = new RafflesiaUserInformation($this->user);
    // set the wsm
    $wsm = []; 
    /* check if the person who request is a manager or a wsm */
    if($userInfo->checkUserManager()){
      $wsm = $userInfo->getListWSM();
    }else{
      $manager_id = $userInfo->getManager($this->user);
      $wsm = $userInfo->getListWSMID($manager_id[0][0]);
    }
    
    $num_wsm = count($wsm);
    
    /* set from and to*/
    $from = $this->from;
    $to = $this->to;
    $data_set = [];
    $row_volume = [];
    $temp_data_set = [];
    
    $day = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    
    for($x=0;$x<$num_wsm;$x++){
        /* Query */
        try{
        $select = db_select('rafflesia_tasks', 'rt');
            $select->fields('rt' , ['tasks_volume','tasks_timestamp']);
            $select->condition('rt.tasks_timestamp',array($from,$to),'BETWEEN')
            ->condition('rt.uid',$wsm[$x]['id'],'=');
        $select->orderBy('rt.tasks_timestamp', 'ASC');
        $data = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

            }catch(Exception $e){
               drupal_set_message('Missing Database Exception '. $e->getMessage());
            }
        // Map the query output 
        foreach ($data as $row){   
          $row_volume[] =  [$row['tasks_timestamp'],$row['tasks_volume']];
        }
       
        $count_row = count($row_volume);
        // check each information
        
        for($y = 0;$y<7;$y++){
          $data_set[$x][$y] = 0;
          // check each data
          if(!empty($row_volume)){
            for($z=0;$z<$count_row;$z++){
              // data set allocate to right day
              
              if(date('D',$row_volume[$z][0]) == $day[$y]){
                // add the data
                $data_set[$x][$y] = $row_volume[$z][1] + $data_set[$x][$y]; 
               
              }            
            }
          }
        }  
        
        $row_volume = "";
        
    }
    
    $wsm_data_set = [];
    
    /* Get Only The Right Data */
    foreach($wsm as $row_wsm){
      $wsm_data_set[] = $row_wsm['first_name']; 
    }
    
    return $weekly_report = [
      'names' =>  $wsm_data_set ,
      'data' => $data_set ,
     ];
    
    
  }
  
}
