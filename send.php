<?php

if (isset($_POST)) {
  $to = 'contact@qwe.shop';
  $subject = 'From contact@qwe.shop';
  $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=' . PHP_EOL;
  $message = '<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\'>';
  
  $token = $_POST['token'];
  
  if (check($token) === false) {
      print_r(400);
      exit();
  }
  
  foreach($_POST as $key => $value) {
		if ($key == 'token') {
				continue;
		}
    $message .= '<tr><td align=\'right\'>'.$key.':</td><td>&nbsp;<b>'.Trim(stripslashes($value)).'</b></td></tr>';
  }
  $message .= '</table>';
  $headers = 'From: contact@qwe.shop' . PHP_EOL . 'Reply-To: contact@qwe.shop' . PHP_EOL . 'Content-Type: text/html; charset=UTF-8' . PHP_EOL . 'MIME-Version: 1.0' . PHP_EOL . 'Content-Transfer-Encoding: 8bit ' . PHP_EOL;

  $mail = mail($to, $subject, $message, $headers);

  if($mail) {
    print_r(200);
  } else {
    print_r(400);
  }
}

function check($token)
{
    $params = [
        'secret' => 'captcha_key',
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return false;
    }

    $response = json_decode($response);

    return $response->success;
}
?>