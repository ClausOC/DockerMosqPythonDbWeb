# Docker, Mosquitto, Nginx, PHP, MariaDB, Python

I made this for using it with my RPi, so I could log data from several ESP32 units, that was programmed to measure temperature, pressure and humidity.
Data is send using MQTT (Mosquitto) and stored in the database.


This is a Docker setup with:
1. Mosquitto
2. Nginx
3. Php
4. Maria DB
5. Python

Xdebug is added to PHP and debugpy is added to Python. This way both PHP and Python can be debugged from VS Code

# Mosquitto with Docker
Info for setting up Mosquitto with Docker is borrowed from here: https://github.com/sukesh-ak/setup-mosquitto-with-docker.  
This is a very nice guide - thanks for the good work!

There are default 3 users that can connect to Mosquitto: user1, user2 and sensor.
Passwords are user1, user2 and sensordata.
For better security these should be deleted and more strong passwords should be used :-)

# Nginx with Docker
The setup info is a mix from many different sites and stackoverflow question/answers.

