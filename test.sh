#!/usr/bin/env bash

cucumber
phpunit --coverage-html report tests/testForUserController.php --whitelist controller/php/userController.php
phpunit --coverage-html report tests/testForNetwork.php --whitelist model/Network.php
