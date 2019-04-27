<?php

namespace Qbhy\Agora;

const SDK_VERSION = '1';

function getToken($appId, $appCertificate, $account, $validTimeInSeconds)
{
    $expiredTime = time() + $validTimeInSeconds;

    $tokenItems = [];
    array_push($tokenItems, SDK_VERSION);
    array_push($tokenItems, $appId);
    array_push($tokenItems, $expiredTime);
    array_push($tokenItems, md5($account . $appId . $appCertificate . $expiredTime));
    return join(":", $tokenItems);
}

function packString($value)
{
    return pack("v", strlen($value)) . $value;
}
