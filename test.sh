#!/usr/bin/env bash

cucumber
cd tests
phpunit --coverage-html report TestForUserController.php --whitelist ../controller/php/UserController.php
phpunit --coverage-html report TestForNetwork.php --whitelist ../model/Network.php
firefox report/Network.php.html report/UserController.php.html 
