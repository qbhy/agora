<?php

namespace Qbhy\Agora\Auth;

use function Qbhy\Agora\packString;

class DynamicKey5
{

    public static $version = "005";
    public static $NO_UPLOAD = "0";
    public static $AUDIO_VIDEO_UPLOAD = "3";

// InChannelPermissionKey
    public static $ALLOW_UPLOAD_IN_CHANNEL = 1;

// Service Type
    public static $MEDIA_CHANNEL_SERVICE = 1;
    public static $RECORDING_SERVICE = 2;
    public static $PUBLIC_SHARING_SERVICE = 3;
    public static $IN_CHANNEL_PERMISSION = 4;

    static function generateRecordingKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs)
    {
        return self::generateDynamicKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs, self::$RECORDING_SERVICE, array());
    }

    static function generateMediaChannelKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs)
    {
        return self::generateDynamicKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs, self::$MEDIA_CHANNEL_SERVICE, array());
    }

    static function generateInChannelPermissionKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs, $permission)
    {
        $extra[self::$ALLOW_UPLOAD_IN_CHANNEL] = $permission;
        return self::generateDynamicKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs, self::$IN_CHANNEL_PERMISSION, $extra);
    }

    static function generateDynamicKey($appID, $appCertificate, $channelName, $ts, $randomInt, $uid, $expiredTs, $serviceType, $extra)
    {
        $signature = self::generateSignature($serviceType, $appID, $appCertificate, $channelName, $uid, $ts, $randomInt, $expiredTs, $extra);
        $content   = self::packContent($serviceType, $signature, hex2bin($appID), $ts, $randomInt, $expiredTs, $extra);
        // echo bin2hex($content);
        return self::$version . base64_encode($content);
    }

    static function generateSignature($serviceType, $appID, $appCertificate, $channelName, $uid, $ts, $salt, $expiredTs, $extra)
    {
        $rawAppID          = hex2bin($appID);
        $rawAppCertificate = hex2bin($appCertificate);

        $buffer = pack("S", $serviceType);
        $buffer .= pack("S", strlen($rawAppID)) . $rawAppID;
        $buffer .= pack("I", $ts);
        $buffer .= pack("I", $salt);
        $buffer .= pack("S", strlen($channelName)) . $channelName;
        $buffer .= pack("I", $uid);
        $buffer .= pack("I", $expiredTs);

        $buffer .= pack("S", count($extra));
        foreach ($extra as $key => $value) {
            $buffer .= pack("S", $key);
            $buffer .= pack("S", strlen($value)) . $value;
        }

        return strtoupper(hash_hmac('sha1', $buffer, $rawAppCertificate));
    }

    static function packString($value)
    {
        return pack("S", strlen($value)) . $value;
    }

    static function packContent($serviceType, $signature, $appID, $ts, $salt, $expiredTs, $extra)
    {
        $buffer = pack("S", $serviceType);
        $buffer .= packString($signature);
        $buffer .= packString($appID);
        $buffer .= pack("I", $ts);
        $buffer .= pack("I", $salt);
        $buffer .= pack("I", $expiredTs);

        $buffer .= pack("S", count($extra));
        foreach ($extra as $key => $value) {
            $buffer .= pack("S", $key);
            $buffer .= packString($value);
        }

        return $buffer;
    }

}