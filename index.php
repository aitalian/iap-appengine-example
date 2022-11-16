<?php

$headers = getallheaders();

$user = array_filter(array(
    'id'        => ($headers['X-Goog-Authenticated-User-Id'] ?? null),
    'email'     => ($headers['X-Goog-Authenticated-User-Email'] ?? null),
    'nickname'  => ($headers['X-Appengine-User-Nickname'] ?? null),
    'ip'        => ($headers['X-Goog-X-Appengine-User-Ip'] ??
        $headers['X-Forwarded-For'] ??
        $_SERVER['HTTP_CLIENT_IP'] ??
        $_SERVER['HTTP_X_FORWARDED_FOR'] ??
        $_SERVER['REMOTE_ADDR'] ??
        null
    ),
    'location' => implode(", ", array_filter(array(
        (isset($headers['X-Appengine-City']) ? ucwords($headers['X-Appengine-City']) : null),
        (isset($headers['X-Appengine-Region']) ? strtoupper($headers['X-Appengine-Region']) : null),
        (isset($headers['X-Appengine-Country']) ? ucwords($headers['X-Appengine-Country']) : null)
    )))
));

$greeting = "";

if (empty($user['id'])) {
    $greeting .= "Hello, Guest! I don't know much about you, since you are not authenticated with IAP.";

    if (!empty($user['location'])) {
        $greeting .= "<br/>";
        $greeting .= "I do know that you are connecting from ${user['location']}.";
    }
} else {
    if (!empty($user['nickname'])) {
        $greeting .= "Welcome, ${user['nickname']}";
    }

    if (!empty($user['email'])) {
        $greeting .= " (${user['email']})";
    }

    if (!empty($user['location'])) {
        $greeting .= " from ${user['location']}";
    }

    $greeting .= "!";

    if (!empty($user['id'])) {
        $greeting .= " Your persistent ID is: ${user['id']}.";
    }
}

if (!empty($user['ip'])) {
    $greeting .= "<br/>";
    $greeting .= "Your IP address is: ${user['ip']}";
}
?>
<!doctype html>
<html>

<head>
    <title>IAP Example with App Engine</title>
</head>

<body>
    <h1>A simple App Engine IAP Example</h1>

    <p>
        <?php echo $greeting; ?>
    </p>

    <!-- #debug information, all headers
    <?php print_r($headers); ?>
    -->

</body>

</html>