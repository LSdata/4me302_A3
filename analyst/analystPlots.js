/*
 * This file contains functions for generating the plot charts of runtime logs.
 * The data log files are retreived from the course database service 
 */

    
/* get logfile by logfile name. Return log data in an array*/
function getLogfile(logfileName){
        var logFile = [];
        
        $.ajax({
            type: "GET", 
            url: 'backend/index.php',
            data: {key: '4me302A3', method:'logfile', value1:logfileName},
            async: false, 
            dataType: "json"
		})
        .done(function(data){
			logFile = data;
		})
		.fail(function() {
			return "ajax get logfile error";
		});
        
        return createArr(logFile);
}
    
    /*subroutine to getLogfile(), creates an array of the log data*/
    function createArr(logData){
        var arr = [];

        var lines = logData.split('\n');
        //var lineLen = lines.length;
        for(var line = 0; line < 69; line++){
            var word = lines[line].split(','); 
            var date = new Date (word[0]);
            var day = date.getDate();
            if (day ==7) {      /*date 1/7/2015 is choosen as an example. Could be changed to select the latest day*/
                arr.push({
                    "x": word[0],
                    "y": parseInt(word[1])                        
                });
            }
        }
        return arr;
    }
    
/*plottin a line chart with d3js library*/
function InitChart(lineData, svgPlace, logName) {

    var d3svg = d3.select("#"+svgPlace),
        WIDTH = 1100,
        HEIGHT = 500,
        MARGINS = {
            top: 20,
            right: 20,
            bottom: 20,
            left: 50
        };
    /*x and y axis*/
    var xLabels = lineData.map(function (d) { return d.x; })
    
    var xRange = d3.scale.ordinal()
		.rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], .1)
        .domain(xLabels);
     
    var yRange = d3.scale.linear()
        .range([HEIGHT - MARGINS.top, MARGINS.bottom])
        .domain([d3.min(lineData, function (d) {
            return d.y;
            }),
        d3.max(lineData, function (d) {
            return d.y;
        })]);

    var xAxis = d3.svg.axis()
		.scale(xRange)
		.orient("bottom")
        .innerTickSize(-HEIGHT+40)
        .outerTickSize(0)
        .tickPadding(10);

    var yAxis = d3.svg.axis()
        .scale(yRange)
        .tickSize(5)
        .orient("left")
        .tickSubdivide(true)
        .innerTickSize(-WIDTH+MARGINS.left+50)
        .outerTickSize(0)
        .tickPadding(10);

    d3svg.append("svg:g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
        .call(xAxis)
        .selectAll("text")
        .style("text-anchor", "end")
        .attr("transform", "rotate(-45)" );
         
    //x-axis label
	d3svg.append("text")
        .attr("x", (WIDTH)/ 2)
        .attr("y", HEIGHT+ MARGINS.left)
        .attr("class", "text-label")
        .attr("text-anchor", "middle")
        .text("date and time");
        
    //chart title label
    d3svg.append("text")
        .attr("x", (WIDTH)/ 2)
        .attr("y", MARGINS.top*2)
        .attr("class", "text-label")
        .attr("text-anchor", "middle")
        .text("Log name: "+logName);
    
    d3svg.append("svg:g")
        .attr("class", "y axis")
        .attr("transform", "translate(" + (MARGINS.left) + ",0)")
        .call(yAxis);

    //draw the plot line
    var lineFunc = d3.svg.line()
        .x(function (d) {
            return xRange(d.x);
        })
        .y(function (d) {
            return yRange(d.y);
        });
        
    //display the plot line
    d3svg.append("svg:path")
        .attr("d", lineFunc(lineData))
        .attr("stroke", "blue")
        .attr("stroke-width", 2)
        .attr("fill", "none");
}

   