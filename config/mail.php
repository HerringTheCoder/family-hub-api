<?php
return [
  "driver" => env('MAIL_DRIVER'),
  "host" => env('MAIL_HOST'),
  "port" => env('MAIL_PORT'),
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => env('MAIL_USERNAME'),
  "password" => env('MAIL_PASSWORD'),
  "sendmail" => "/usr/sbin/sendmail -bs"

];