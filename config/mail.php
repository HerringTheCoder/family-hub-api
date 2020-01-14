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

  //GOOGLE MAIL CONFIG
  // 'driver' => env('MAIL_DRIVER', 'smtp'),
  //   'host' => env('MAIL_HOST', 'smtp.gmail.com'),
  //   'port' => env('MAIL_PORT', 587),
  //   'from' => ['address' => 'familyhubpwsz@gmail.com', 'name' => 'Family Hub'],
  //   'encryption' => env('MAIL_ENCRYPTION', 'tls'),
  //   'username' => env('MAIL_USERNAME'),
  //   'password' => env('MAIL_PASSWORD'),
  //   'sendmail' => '/usr/sbin/sendmail -bs',
  //   'pretend' => false,