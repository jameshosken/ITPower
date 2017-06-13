var fr = .1;
var url;
var i;

function setup() {
	frameRate(fr); // redraw every 10 second
	url = 'http://localhost:8080/data'; // the url where we can get the data
	createCanvas(800, 600); // create a canvas
	background('#ec5a13'); // set the background

	textAlign(CENTER);
	i = 100;
}

function draw() {
  loadJSON(url, showData); // load data as JSON object from url
}

function showData(enertivData) {
	var device = enertivData.names[0]; // get device name
	var time = enertivData.data[0].x; // get time
	var usage = enertivData.data[0][device]; // get usage number

	console.log('device: ' + device);
	console.log('time: ' + time);
	console.log('usage: ' + usage);

	var estime = new Date(time); // convert time to date
	var min = estime.toString().split(':')[1]; // get minute of the time

	var size = map(usage, 0, 4, 0, 240);// usage: 0 - 4, map to 0 - 240

	if (i === 100) {
		// draw the device name
		fill(255);
		strokeWeight(0.3);
		textSize(20);
		text(device, width / 2, height / 2 - 170);

		// draw the time
		var startTime = 'Start From ' + estime.toString().split(':')[0] + ':' + estime.toString().split(':')[1];
		textSize(16);
	  	text(startTime, width / 2, height / 2 - 120);

	  	// draw the lines
	  	fill('#f2ffc0');
	  	stroke('#f2ffc0');
		for (var j = 0; j < 240; j += 40) {
		  	line(i, height / 2 + 150 - j, width - 140, height / 2 + 150 - j);
		}
	}

	// draw the circle on each line
	strokeWeight(4);
	line(i, height / 2 + 150, i, height / 2 + 150 - size);
	ellipse(i, height / 2 + 150 - size, 3, 3);

	// draw the minute under each line
	strokeWeight(0);
	text(usage, i, height / 2 + 150 - size - 20);
	text(min, i, height / 2 + 170);

	// move i to the right a little to draw the next line and circle
	i += 50;
}
