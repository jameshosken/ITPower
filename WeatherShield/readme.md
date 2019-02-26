# ITP Weather Station

## Introduction

ITP now owns a sparkfun weather station with the associated shield (v12). This repo is for projects of, documentation for, and ideas about this station.

## Glossary
*To define terms used in this repo.*

- **Weather Station**: The T shaped object with wind speed, wind direction, and rain guage sensors attached
- **Weather Shield**: The red PCB that mounts to your Arduino Uno. Comes with 2 RJ-11 connectors, and a few nifty envoronment sensors.

## Super Quick Setup Guide
*Get started as fast as possible*

1. Stabilise your weather station (I used a speed clamp and zip ties)
2. Plug in the Wind and Rain cable connectors from the weather station into the weather shield. Mount the shield onto an Arduino.
3. Install **Sparkfun MPL3115** and **Sparkfun Si7021** from the Arduino IDE libraries manager
4. Upload the code in **Arduino/sparkfun_weather_shield_basic.ino** from this repo
5. Open the Serial Monitor and observe you wondrous weather data print to the screen.

## More Detailed Setup Guide
*Links to more details*

View [Sparkfun's Github Guide](https://github.com/sparkfun/Weather_Shield) to setting up the weather shield

### Libraries Required

These two libraries are required to run the basic weather station example. Both can be installed via the Ardiuno IDE libraries manager

- Sparkfun MPL3115
- Sparkfun Si7021

## Contributing
Until we have the ability to create issues on this repo, the best way to contribute is to:
- Fork the ITPower repo to your own GitHub account
- Create whatever changes you would like to see
- Open a new Pull Request

The Weather Station for ITPower is an open source project and encourages ideas and suggestions from everyone! Please feel free to get in touch.
