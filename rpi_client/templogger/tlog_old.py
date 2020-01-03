#!/usr/bin/python

#Open temp

from datetime import datetime
import socket

w1dir = "/sys/bus/w1/devices/"
w1master = w1dir + "w1_bus_master1/"
wire1temp = open(w1master + "w1_master_slave_count", "r")
sensor_count = wire1temp.read()
wire1temp.close()

wire1temp = open(w1master + "w1_master_slaves", "r")
sensor_names = wire1temp.read().split()
wire1temp.close()

hostname = socket.gethostname()

#print "sensor count: ", sensor_count


for name in sensor_names:
	tempdevn = w1dir + name + "/w1_slave"
	tempdev = open(tempdevn, "r")
	tempdata = tempdev.read()
	tempdev.close()
	index = tempdata.find("t=")
	temp = 0.0
	if index > 0: 
		tempstring = tempdata[index+2:None]
		temp = float(tempstring)/1000
	post="?Sensor="+name.strip()+"&temp="+str(temp)
	post += "&time="+datetime.now().strftime("%Y-%m-%dT%H:%M:%S")
	post +="&hostname="+hostname
	print post

	logfile = "/home/pi/templogger/tlog1.txt"
	logf = open(logfile, "a")
	logf.write(post+"\n")
	logf.close()









