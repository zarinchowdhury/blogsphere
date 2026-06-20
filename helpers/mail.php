<?php

function sendMail($to, $subject, $message) {

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: BlogSphere <no-reply@blogsphere.com>" . "\r\n";

    return mail($to, $subject, $message, $headers);
}