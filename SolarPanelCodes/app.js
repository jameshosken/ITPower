/*
Jan 3, 2018
by Jenny Lim and Mathura MG
May 1, 2017
by Dorothy Lam
based on:
+ Tom Igoe's HTTP code
+ npm MODBUS-SERIAL library
*/

// create an empty modbus client
var ModbusRTU = require("modbus-serial");
var client = new ModbusRTU();

var batteryVoltage;
var chargingCurrent;
var rawData;

// you can do this with http or https:
var http = require('https');
const mysql = require('mysql');

//details to connect to the SQL database where the values are stored
const connection = mysql.createConnection({
  host: '',
  user: '',
  password: '',
  database: ''
});



// open connection to a serial port
client.connectRTUBuffered("/dev/ttyUSB0", {
  baudrate: 9600
});
client.setID(1);
openConnection();
setInterval(read, 1000 * 60);

function read() {
 // console.log('running read');
  // reads 1 register at 0x0008
  client.readHoldingRegisters(0x008, 1, function(err, data) {
    if (err) {
      console.log(err);
    } else {
      rawData = data.data;
      // Round the number to two decimal points and keep the two digits even if they're 0
      batteryVoltage = (Math.round(((data.data * 96.667) / 32768) * 100) / 100).toFixed(2);
  //    console.log("battery voltage: ", batteryVoltage, "V");
      readCurrent();
    }
  });
}

function readCurrent() {
  client.readHoldingRegisters(0x00B, 1, function(err, data) {
    if (err) {
      console.log(err);
    } else {
      // Round the number to two decimal points and keep the two digits even if they're 0
      chargingCurrent = Math.round((data.data * 100) / 100).toFixed(2);
      // var chargingCurrentRaw = (data.data * 96.667) / 32768
    //  console.log("charging current: ", chargingCurrent, "mA");
      post();
    }
  })
  }
  
  /*
  the callback function to be run when the response comes in.
  this callback assumes a chunked response, with several 'data'
  events and one final 'end' response.
*/

function callback(response) {
  var result = ''; // string to hold the response

  // as each chunk comes in, add it to the result string:
  response.on('data', function(data) {
    result += data;
  });

  // when the final chunk comes in, print it out:
  response.on('end', function() {
    console.log(result);
  });
}

function openConnection() {
  connection.connect((err) => {
    if (err) throw err;
    console.log('Connected!');
  });
}
function post() {
  let query = 'INSERT INTO BatteryValues (RecordTime, RawReading, BatteryVoltage, ChargingCurrent) VALUES (CURTIME(),$

  connection.query(query, function(error, results, fields) {
    if (error) throw error;
    //console.log(results[0]);
  });

 // connection.end();
}


  
  
