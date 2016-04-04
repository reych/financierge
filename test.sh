#!/usr/bin/env bash

# cucumber
phpunit --coverage-html report tests/testForUserController.php --whitelist controller/php/UserController.php
phpunit --coverage-html report tests/testForNetwork.php --whitelist model/Network.php
firefox tests/report/UserController.php.html tests/report/Network.php.html
