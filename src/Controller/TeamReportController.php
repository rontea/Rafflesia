<?php
/**
* @file
* Contains \Drupal\mymodule\Controller\CalendarController class.
*/
namespace Drupal\rafflesia\Controller;

use Drupal\Core\Database\Database;
use Drupal\Core\Controller\ControllerBase;
use Drupal\rafflesia\RafflesiaGetReport;
use Drupal\rafflesia\RafflesiaUserInformation;

/**
* Calendar Reports
*/

class TeamReportController extends ControllerBase {
  
  /*
   * Build The Calendar JS
   **/
  
  public function build($from,$to){
    
    $dateFormat = 'Y-m-d';
    $timeFormat = 'g:i:s A';
    
    $userid = \Drupal::currentUser()->id();
    $report = new RafflesiaGetReport($from,$to);
    $report->setToTeam(1);
    $report->setUser($userid);
    $action = $report->generateAction();
    $bundle = $report->getTeamBundle();
     
    /* Get WSM of Manager */
    
    drupal_set_message($action);
    
    /* Build Info */
     if(empty($to)){
        $build['message'] = array(
         '#markup' => $this->t('This is the result of Date only @from :', [ '@from' => $from ]),
        );
     }else{
       $build['message'] = array(
         '#markup' => $this->t('This is the result of From @from and To @to :', [ '@from' => $from , '@to' => $to ]),
        );
     }
     
     /* Build Data */
     $build['table_activities'] = array(
      '#type' => 'table',
      '#header' => $bundle['name'],
      '#rows' => $bundle['volume'],
      '#empty' => t('No entries available.'),
      ); 
    $count = count($bundle['volume']);
    
    $t_vol = [];
    
    for($x = 0; $x < $count; $x++){
          if($x == 0){
              $t_vol[$x] = 0;
          }else{
              if(empty($bundle['volume'][$count][$x])){
                $t_vol[$x] = 0;
              }else{
                $t_vol[$x] = $bundle['volume'][$count][$x];
              }
          }
    }
     // should be generate in my theme
     $build['js_chart']['#attached']['library'][] = 'rafflesia/rafflesia_js_chart1'; 
     /* Link Data of Drupal to JS 0.1 on rafflesia_js_chart.js */
     $build['sample_connection']['#attached']['drupalSettings']['rafflesia']['name'] = $bundle['name'];
     $build['sample_connection2']['#attached']['drupalSettings']['rafflesia']['volume'] = $t_vol;
     // User Color 
     $build['color']['#attached']['drupalSettings']['rafflesia']['color'] = $bundle['color'];
     
     // Get the Data Graph
     $start = 7;
     $data_set = [];
     
     $data_set = $report->taskPerHour($start);
     
     $build['data_set_wsm']['#attached']['drupalSettings']['rafflesia']['data_set_wsm'] = $data_set['names'];
     $build['data_set_task']['#attached']['drupalSettings']['rafflesia']['data_set'] = $data_set['task'];
     
     // Build for Horizontalbar Graph
     $count_name = count($bundle['name']);
     $activity_list = [];
     $volume_list = [];
     $temp_num = 0;
     for($k=0;$k<$count_name;$k++){
       
      for($j=0;$j<count($bundle['volume']);$j++){
        
        if($k==0){
          if($j < (count($bundle['volume'])-1)){
            $activity_list[$j] = $bundle['volume'][$j][0];
          }
        }else{
          
          if(!empty($bundle['volume'][$j][$k])){
             $volume_list[$temp_num][$j] = $bundle['volume'][$j][$k];
          }else{
            $volume_list[$temp_num][$j] = 0;
          }
        }
      }
      if($k!=0){
         $temp_num++;
      }
     }
     $build['act_name_list']['#attached']['drupalSettings']['rafflesia']['act_list'] = $activity_list;
     $build['act_list_vol']['#attached']['drupalSettings']['rafflesia']['act_volume'] = $volume_list;
     
     /* Weekly Chart */
     
    $weekly_report =  $report->taskPerDay(1);
    
    $build['weekly_vol']['#attached']['drupalSettings']['rafflesia']['weekly_vol'] = $weekly_report['data'];
    $build['weekly_set_wsm']['#attached']['drupalSettings']['rafflesia']['weekly_set_wsm'] = $weekly_report['names'];
    
    
    // build the chart
     $build['chart_twig'] = array(
      '#theme' => 'chart',
      '#var' => 'Rafflesia Database',
      );
    
     // no cache 
    $build['#cache']['max-age'] = 0; 
    return $build;   
  }
 
}
