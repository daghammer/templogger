## Raspberry pi client code for reading and uploading temp readings from DS18B20 sensors

Some tips may be found here: http://www.circuitbasics.com/raspberry-pi-ds18b20-temperature-sensor-tutorial/

Add support for the humid sensor: DHT22/AM2302:

https://www.instructables.com/id/Raspberry-Pi-Tutorial-How-to-Use-the-DHT-22/

Tested using pin GPIO6

Setup:
sudo nano /boot/config.txt :
Append line:
dtoverlay=w1-gpio

In raspi-config - enable 1 wire interface

After reboot:

sudo modprobe w1-gpio

sudo modprobe w1-therm

cd /sys/bus/w1/devices


Also wise to consider enabling whatchdog on raspberry:

https://diode.io/blog/running-forever-with-the-raspberry-pi-hardware-watchdog

