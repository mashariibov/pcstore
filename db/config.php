<?php

$db =new PDO("sqlite:".__DIR__."/data.sqlite");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


