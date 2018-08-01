# pwtoken
## A PHP Web Token
#
# Quick Installation
## Setup
You should set up some kind of database table where you can store these three keys for each of your users:
$api_key; // long hash - I recommend 256 characters
$api_secret; // a hidden hash - I think 16 characters of a-ZA-z0-9 is enough (this var is never ever shared in public)
$api_appid; // an identifier which points directly to a specific user