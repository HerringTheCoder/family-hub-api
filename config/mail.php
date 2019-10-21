<?php
return [
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "f75f8508ccb316",
  "password" => "2ec70cbabb7c84",
  "sendmail" => "/usr/sbin/sendmail -bs"
];