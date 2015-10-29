/*
 * Functions to plot the chart of the stock market values.
 * Using d3js library and Yahoo Finance API.
 */
    
    //get the organization's latest 8 weeks' historical values
    function getCSV(stockName){
        var allData;
        
		$.ajax({
			type: "GET", 
			url: 'https://query.yahooapis.com/v1/public/yql',
			data: { 'q': 'SELECT * FROM csv WHERE url="http://ichart.finance.yahoo.com/table.csv?s='+stockName+'&g=w&ignore=.csv"',
					'format': 'json',
					'jsonCompat': 'new',
					},
			async: false, 
			dataType: "json"
		})
		.done(function(data){
            allData = data;
		})
		.fail(function() {
			return "error in csvFkn";
		});
		return createArr(allData);
	}
    
    /*subroutine to create an array*/
    function createArr(allData){
        var arr = [];
        var countRow = 0;

        $.each(allData.query.results.row, function (key, value) {

            if (key > 0 && countRow <8) {
                countRow++;

                arr.push({
                    "x": value.col0,
                    "y": +value.col4                        
                });
            }
		});

        return arr;
    }
    
/*the plot chart with d3js library*/
function InitChart(lineData) {
    
    var d3svg = d3.select("#d3svg"),
        WIDTH = 500,
        HEIGHT = 250,
        MARGINS = {
            top: 20,
            right: 20,
            bottom: 20,
            left: 50
        };
    
    //x and y axis
    var xLabels = lineData.map(function (d) { return d.x; })
    
    var xRange = d3.scale.ordinal()
		.rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], .1)
        .domain(xLabels);
        
    var yRange = d3.scale.linear()
        .range([HEIGHT - MARGINS.top, MARGINS.bottom])
        .domain([d3.min(lineData, function (d) {
            return d.y;
            })-5,
        d3.max(lineData, function (d) {
            return d.y;
        })+5]);

    var xAxis = d3.svg.axis()
		.scale(xRange)
		.orient("bottom");

    var yAxis = d3.svg.axis()
      .scale(yRange)
      .tickSize(5)
      .orient("left")
      .tickSubdivide(true);

    d3svg.append("svg:g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
        .call(xAxis)
        .selectAll("text")
        .style("text-anchor", "end")
        .attr("dx", "-.8em")
        .attr("dy", "-.55em")
        .attr("transform", "rotate(-45)" );
        
         d3svg.append("svg:g")
        .attr("class", "y axis")
        .attr("transform", "translate(" + (MARGINS.left) + ",0)")
        .call(yAxis);
         
    // x axis label
	d3svg.append("text")
        .attr("x", (WIDTH)/ 2)
        .attr("y", HEIGHT+ MARGINS.left)
        .attr("class", "text-label")
        .attr("text-anchor", "middle")
        .text("date of the weekley stock market values");
   
    //create the plot line
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
        
    //trendline with r-square value
	var xSeries = d3.range(1, xLabels.length + 1);
	var ySeries = lineData.map(function(d) { return parseFloat(d.y); });
    
    var leastSquaresCoeff = leastSquares(xSeries, ySeries);
		
		// apply the reults of the least squares regression
		var x1 = xLabels[0];
		var y1 = leastSquaresCoeff[0] + leastSquaresCoeff[1];
		var x2 = xLabels[xLabels.length - 1];
		var y2 = leastSquaresCoeff[0] * xSeries.length + leastSquaresCoeff[1];
		var trendData = [[x1,y1,x2,y2]];
		
		var trendline = d3svg.selectAll(".trendline")
			.data(trendData);
			
		trendline.enter()
			.append("line")
			.attr("class", "trendline")
			.attr("x1", function(d) { return xRange(d[0]); })
			.attr("y1", function(d) { return yRange(d[1]); })
			.attr("x2", function(d) { return xRange(d[2]); })
			.attr("y2", function(d) { return yRange(d[3]); })
			.attr("stroke", "black")
			.attr("stroke-width", 1);
            
        //trendline r-square value text
        d3svg.append("text")
			.text("r-sq: " + leastSquaresCoeff[2].toFixed(2)) //2 decimals
			.attr("class", "text-label")
            .attr("x", function(d) {return xRange(x2) - 10;})
			.attr("y", function(d) {return yRange(y2) - 10;});
}

    // returns slope, intercept and r-square of the line
	function leastSquares(xSeries, ySeries) {
		var reduceSumFunc = function(prev, cur) { return prev + cur; };
		
		var xBar = xSeries.reduce(reduceSumFunc) * 1.0 / xSeries.length;
		var yBar = ySeries.reduce(reduceSumFunc) * 1.0 / ySeries.length;

		var ssXX = xSeries.map(function(d) { return Math.pow(d - xBar, 2); })
			.reduce(reduceSumFunc);
		
		var ssYY = ySeries.map(function(d) { return Math.pow(d - yBar, 2); })
			.reduce(reduceSumFunc);
			
		var ssXY = xSeries.map(function(d, i) { return (d - xBar) * (ySeries[i] - yBar); })
			.reduce(reduceSumFunc);
			
		var slope = ssXY / ssXX;
		var intercept = yBar - (xBar * slope);
		var rSquare = Math.pow(ssXY, 2) / (ssXX * ssYY);
		
		return [slope, intercept, rSquare];
	}