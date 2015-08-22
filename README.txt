WDA - Assignment 2

Student No. s3407908
Student Email: s3407908@student.rmit.edu.au
Student Name: James Nicastri

TEAM DETAILS:
-None - This assignment was completed individually


LAMP STACK DETAILS:
This application is deployed at, and can be tested at: http://54.79.117.156/
Standard LAMP Stack used:
- PHP
- Apache2
- MySQL
- Linux Server (ubuntu)
- crontab has also been used on the server to schedule a job (details below)


SOURCE CONTROL:
Bitbucket private repo is here: https://bitbucket.org/jnicastri/wda_a2
I granted access to Halil Ali on 23/08/2015


NOTES TO MARKER:
Stored Procedures have been used for all database scripts.
I have provided a master script to install all of them at once if you need 
to install them locally. Installing the database itself does not install 
the stored procedures, you will need the master script. All procedures are 
also available as individual files (easier to read) in the 'sprocs' folder.


The 'OrderTransaction' entity in my database has data entered into is via 
a scheduled job that runs every hour (crontab job on the server). The 
script that is executed is 'AutoTasks.php' (available in the php directory 
of my submission) and it simply executes the 'OrderTrans_Create' procedure. 
The crontab is:
0 * * * * /usr/bin/php -f /var/www/html/A2/php/AutoTasks.php


SAMPLE/TEST USERS - already in the database, with listings, bidding etc.

Username:Password

korban@example.com: password
duke@example.com: password1
nathan@example.com: password6
jason@example.com: password9
wendy@example.com: password3
fabian@example.com: password6
leeloo@example.com: password7

 
 
