(function ($) {

      /* Link Data of Drupal to JS 0.1 */
      
    Drupal.behaviors.rafflesia = {
      
      attach: function (context, settings) {
        // getting the value of name to JS o.1  
        
        var name = settings.rafflesia.name;
        var volume = settings.rafflesia.volume;
        
        
        color = settings.rafflesia.color;
          
        var names = new Array();
        var colors = new Array();
        var volumetotal = new Array();
        
        var count_name = name.length;
       
        var y = 0;
        
        for(var x = 0; x < count_name;x++){
               
              if(x != 0){
                  names[y] = name[x];
                  volumetotal[y] = volume[x];
                  y = y + 1;
              }   
        }
        
        var ctx = $("#totalChart");
            var myChart = new Chart(ctx, {
                  type: 'bar',
                    data: {
                        labels: names,
                        datasets: [{
                            label: "Hide Volumes",
                            data: volumetotal,
                            backgroundColor: color,
                            borderColor: color,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        title:{
                            display:true,
                            text:"Total Volume of Each WSM"
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
            }); // end of graph
              
        /* Line Chart */
            
            var day = 24;
            var half = 12;
            var start = 7; // use military time for the start e.g. 0 for 12 AM
            var total = 0;
            var location = "AM";

            var timeDataSet = new Array();
            
            for(var y=0;y<day;y++){
                
               if(start >= half){

                    if(y == 0){
                          
                        total = start - 12;
                        if(total == 0){
                            total = half;
                        }
                        
                        timeDataSet[y] = total + 'PM';
                        location = "PM";
                        
                       }else if (location == "AM"){
                           
                           if(total < half){
                             total = total + 1;
                             timeDataSet[y] = total + 'AM';    
                           }else{
                             total = 1;
                             timeDataSet[y] = total + 'PM';
                             location = "PM";
                           }
                       }else{
                           
                           if (location == "PM"){
                                if(total < half){
                                  total = total + 1;
                                  timeDataSet[y] = total + 'PM';    
                                }else{
                                  total = 1;
                                  timeDataSet[y] = total + 'AM';
                                  location = "AM";
                                }
                           }   
                       } 
               }else{
                   
                    if(start < half){  
                       if(y == 0){
                            if(start == 0){
                                total = 12;
                                timeDataSet[y] = total + 'AM';
                                location = "AM";
                            }else{   
                                total = start;
                                timeDataSet[y] = total + 'AM';
                                location = "AM";
                            }   
                       }else if (location == "AM"){
                           
                           if(total < half){
                             total = total + 1;
                             timeDataSet[y] = total + 'AM';    
                           }else{
                             total = 1;
                             timeDataSet[y] = total + 'PM';
                             location = "PM";
                           }   
                       }else{
                           
                           if (location == "PM"){
                                if(total < half){
                                  total = total + 1;
                                  timeDataSet[y] = total + 'PM';    
                                }else{
                                  total = 1;
                                  timeDataSet[y] = total + 'AM';
                                  location = "AM";
                                }
                           }
                       }    
                    } // end if
                } // end else
            } //end for
            
            var wsm = settings.rafflesia.data_set_wsm;
            var data_set = settings.rafflesia.data_set;
            
            var listDataSet = new Array();
           
            /* Generate the Data */
            for(var z = 0; z < wsm.length;z++){
                
                    listDataSet[z] = {
                        label: "Hide "+wsm[z]+"",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: color[z],
                        borderColor: color[z],
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: color[z],
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color[z],
                        pointHoverBorderColor: color[z],
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: data_set[z],
                        spanGaps: true,

                    };
                            
            }
            
            var tlc = $("#totalLineChart");
            // data
            var data = {
                labels: timeDataSet,
                datasets: listDataSet
            };
            // options
            var options = {
              title:{
                display:true,
                text:"Tasks Done Per Hour by WSM", 
               },
              scales: {
                yAxes: [{
                    stacked: false
                }]
              }
            };
            
        // define line graph
        
        var myLineChart = new Chart(tlc, {
            type: 'line',
            data: data,
            options: options
        });
        
        /* Horizontal Bar Chart */ 
        var act_list = settings.rafflesia.act_list;
        var act_volume = settings.rafflesia.act_volume;
        
        var act_names = new Array();
        
        var dataInfo = new Array(); 
        
         // generate the colors
         var genColor = new Array();
         
         // get the color
         for(var a=0;a<names.length;a++){
            genColor[a] = new Array();
            for(var b=0;b<act_list.length;b++){
                genColor[a][b] = color[a];
            }
         }
         // populate the data   
         for(var k=0;k<names.length;k++){
            dataInfo[k] = 
                {
                    label: 'Hide ' + names[k],
                    backgroundColor: genColor[k],
                    borderColor: genColor[k] ,
                    borderWidth: 1,
                    data: act_volume[k],
                };   
        }
        
        // define horizontal bar
        dataHorizontal = {
            labels: act_list,
            datasets: dataInfo,
           
        };  
        
        var hbc = $('#totalHorizontalChart');
        
        var rafHBarChart = new Chart(hbc, {
            type: 'horizontalBar',
            data: dataHorizontal,
            options: {
               title:{
                display:true,
                text:"Activity Total of Each WSM", 
               },
           },
        });
        
        /* Weekly Volume */
        
        var weeklyLabel = new Array('Monday' , 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        var weeklyWsm = settings.rafflesia.weekly_set_wsm;
        var weeklyVolume = settings.rafflesia.weekly_vol;
        
        var weekly_datasets = new Array();
        // set data
        for(var i =0; i<weeklyWsm.length;i++){
             weekly_datasets[i] = 
                {
                label: 'Hide ' + weeklyWsm[i] ,
                backgroundColor: color[i],
                borderColor: color[i],
                borderWidth: 1,
                data: weeklyVolume[i] ,
               };   
        }    
        // map data   
        var weeklyData = {
            labels: weeklyLabel, 
            datasets : weekly_datasets,
        };
           
       
        var weeklyChartId = $('#weeklyChart');
        // define weekly volume
        var weeklyChart = new Chart(weeklyChartId, {
            type: 'bar',
            data: weeklyData,
            options: {
               title:{
                display:true,
                text:"Daily Volume of WSM in a Week", 
               },
           }, 
        });
        
        } // end of context
        
    }; // end of connection
  
})(jQuery); // end Jquery