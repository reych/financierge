#!/usr/bin/env bash

cucumber
cd tests
phpunit --coverage-html report testForUserController.php --whitelist ../controller/php/UserController.php
phpunit --coverage-html report testForNetwork.php --whitelist ../model/Network.php
firefox report/Network.php.html report/UserController.php.html 