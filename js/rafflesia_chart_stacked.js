(function ($) {

      /* Link Data of Drupal to JS 0.1 */
      
    Drupal.behaviors.rafflesia = {
      
      attach: function (context, settings) {
        // getting the value of name to JS o.1  
        
        var data_list = [[],[]];
        
        var names = settings.rafflesia.name;
        var labels = settings.rafflesia.labels;
         data_list = settings.rafflesia.data;
        
        color = [
            'rgba(107, 126, 38, 1)',
            'rgba(38, 126, 92, 1)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(183, 36, 171, 1)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(70, 110, 195, 1)',
            'rgba(255, 159, 64, 0.2)',
        ];
        
        var count_name = names.length;
        var count_label = labels.length;
        
       
        var all_names = new Array();
        
        var y = 0;
        
        for(var x = 0; x < count_name;x++){
             
              if(x != 0){
                  all_names[y] = names[x];
                  
                  y = y + 1;
              }   
        }
        
        var listDataSet = new Array();
         
        for(var i=0; i < count_label - 1;i++){
              
            listDataSet[i] = {
                label: labels[i],
                backgroundColor: color[i],
                stack:  'stack 1',
                data: data_list[i],
            };
            
        }
        
        var data = {
            labels: all_names,
            datasets: listDataSet,

        };
   
        
        var chart = $('#totalChartStacked');
        
        var stackedBar = new Chart(chart, {
            type: 'bar',
            data: data,
            options: {
                 title:{
                        display:true,
                        text:"Activity Volumes for Each WSM"
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                responsive:true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: false
                    }]
                }
            }
        });
        
        var chart2 = $('#totalChartStacked2');
        
        var stackedBarChart = new Chart(chart2, {
            type: 'bar',
            data: data,
            options: {
                 title:{
                        display:true,
                        text:"Activity Volume Overflow of WSM "
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    },
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
        
        } // end of context
        
    }; // end of connection
  
})(jQuery); // end Jquery

