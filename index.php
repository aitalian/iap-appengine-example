<?php

$headers = getallheaders();

$user = array_filter(array(
    'id'    => ($headers['X-Goog-Authenticated-User-Id'] ?? null),
    'email' => ($headers['X-Goog-Authenticated-User-Email'] ?? null)
));

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
?>
<!doctype html>
<html>

<head>
    <title>IAP Example with App Engine</title>
</head>

<body>
    <h1>A simple App Engine IAP Example</h1>

    <?php
    if (!empty($user)) {
    ?>
        <p>
            Hello, <?php echo $user['email']; ?>! Your persistent ID is <?php echo $user['id']; ?>.
        </p>
    <?php
    } else {
    ?>
        <p>
            You are not authenticated with IAP.
        </p>
    <?php
    }
    ?>

    <p>
        Your IP address is: <?php echo $ip; ?>
    </p>

<!-- #debug information, all headers
<?php print_r($headers); ?>
-->

</body>

</html>