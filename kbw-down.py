###################################
#    Script to mesure downtime    #
#	Thx KabelBW		  #
###################################

import os
import datetime

f = open('./kbw-down.py','r')

row=""
file_data=[]

while(True):
	temp = f.read(1)
	if not temp:
		break
	elif temp=="\n":
		file_data.append(row)
		row=""
	else:
		row=row+temp
f.close()

#
## Lines to print out the file
#
#for i in range(0,len(file_data)):
#	print(file_data[i])

#ip = "example.com"
#if not os.system("ping -c 2 "+ip) == 0:
	file_data.insert(len(file_data)-2,"#"+str(datetime.datetime.now()))
	print(','.join(file_data))


#####################################
#####################################
#	  ---LogFile---		    #
#	Accumulated Time(h):	    #
#Date and time:			    #
#
#2016-07-08 02:07:12.976570
#####################################
#####################################

