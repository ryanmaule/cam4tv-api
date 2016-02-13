To do:
- Move redirect out of confirm.php
- Record login in sessions
- Create Logout button on AppleTV
- Verify resource using OAuth (code currently disabled)
- Create script to post "currently viewed model" from AppleTV to db
- Create Second Screen script to read and display "currently viewed model"


Purpose: To authenticate an AppleTV device using AUTH_CODE.

Step 1: User opens CAM4TV
Step 2: User is promoted to visit http://dylan.ryanmaule.com/cam4tv/auth and enter code
Step 3: User visits URL
Step 4: If logged into CAM4, user continues - otherwise user must authenticate
Step 5: User is prompted to enter code from TV
Step 6: User authentication key is stored in the CAM4TV DB
Step 7: User authentication key is stored in CAM4 DB with expiry date
Step 8: User is shown a success message and asked to check their TV for details
Step 9: TV checks every 10 seconds for authentication at http://dylan.ryanmaule.com/cam4tv/auth/validate.php?a=AUTH_CODE
Step 10: Script querys CAM4TV DB to see if auth code has a key, if so, CAM4 DB is queried to see if key is valid
Step 11: If key is value, script returns SUCCESS and CAM4TV app is displayed.  If key is missing soft fail RETRY is sent.
Step 12: If key is expired, script returns EXPIRED and CAM4TV generates a new code for authorization
Step 13: If key is banned, script returns BANNED and CAM4TV prints apology explaining that service is unavailable
Step 14: If country is not allowed, script returns UNAVAILABLE and CAM4TV prints apology explaining the service is unavailable
Step 15: All requests are logged in CAM4TV DB

Development mode

During development mode, CAM4 DB cannot be queried for auth.  
As a result a fake login will be displayed which will accept dummy auth (guest/guest).
Request will be sent to http://dylan.ryanmaule.com/cam4tv/dummy/login.php?u=guest&p=guest&r=SUCCESS
r= may be used to simulate each response type. On success a KEY_CODE will be returned
Test for valid keys request will be sent to http://dylan.ryanmaule.com/cam4tv/dummy/validate.php?k=KEY_CODE
Requests will be tested against the CAM4TV DB dummy_users table

dummy_users
- user_id
- username
- password_hash
- password_salt
- key_code
- key_expiry
- created

devices
- device_id
- auth_code
- key_code
- last_history_id
- first_history_id
- device_type
- created

history
- history_id
- auth_code
- ip_address
- device_type
- device_id
- country
- timestamp

$result = md5($salt.$string);	

OAuth2: http://bshaffer.github.io/oauth2-server-php-docs/cookbook/
PubNub: https://github.com/pubnub/pubnub-angular