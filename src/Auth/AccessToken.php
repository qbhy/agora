<?php

namespace Qbhy\Agora\Auth;

use function Qbhy\Agora\packString;

class AccessToken
{
    const Privileges = array(
        "kJoinChannel"               => 1,
        "kPublishAudioStream"        => 2,
        "kPublishVideoStream"        => 3,
        "kPublishDataStream"         => 4,
        "kPublishAudioCdn"           => 5,
        "kPublishVideoCdn"           => 6,
        "kRequestPublishAudioStream" => 7,
        "kRequestPublishVideoStream" => 8,
        "kRequestPublishDataStream"  => 9,
        "kInvitePublishAudioStream"  => 10,
        "kInvitePublishVideoStream"  => 11,
        "kInvitePublishDataStream"   => 12,
        "kAdministrateChannel"       => 101,
    );

    public $appID, $appCertificate, $channelName, $uid;
    public $message;

    function __construct()
    {
        $this->message = new Message();
    }

    function setUid($uid)
    {
        if ($uid === 0) {
            $this->uid = "";
        } else {
            $this->uid = $uid . '';
        }
    }

    function isNonemptyString($name, $str)
    {
        if (is_string($str) && $str !== "") {
            return true;
        }
        echo $name . " check failed, should be a non-empty string";
        return false;
    }

    static function init($appID, $appCertificate, $channelName, $uid)
    {
        $accessToken = new AccessToken();

        if (!$accessToken->isNonemptyString("appID", $appID) ||
            !$accessToken->isNonemptyString("appCertificate", $appCertificate) ||
            !$accessToken->isNonemptyString("channelName", $channelName)) {
            return null;
        }

        $accessToken->appID          = $appID;
        $accessToken->appCertificate = $appCertificate;
        $accessToken->channelName    = $channelName;

        $accessToken->setUid($uid);
        $accessToken->message = new Message();
        return $accessToken;
    }

    static function initWithToken($token, $appCertificate, $channel, $uid)
    {
        $accessToken = new AccessToken();
        if (!$accessToken->extract($token, $appCertificate, $channel, $uid)) {
            return null;
        }
        return $accessToken;
    }

    function addPrivilege($key, $expireTimestamp)
    {
        $this->message->privileges[$key] = $expireTimestamp;
        return $this;
    }

    function extract($token, $appCertificate, $channelName, $uid)
    {
        $ver_len   = 3;
        $appid_len = 32;
        $version   = substr($token, 0, $ver_len);
        if ($version !== "006") {
            echo 'invalid version ' . $version;
            return false;
        }

        if (!$this->isNonemptyString("token", $token) ||
            !$this->isNonemptyString("appCertificate", $appCertificate) ||
            !$this->isNonemptyString("channelName", $channelName)) {
            return false;
        }

        $appid   = substr($token, $ver_len, $appid_len);
        $content = (base64_decode(substr($token, $ver_len + $appid_len, strlen($token) - ($ver_len + $appid_len))));

        $pos         = 0;
        $len         = unpack("v", $content . substr($pos, 2))[1];
        $pos         += 2;
        $sig         = substr($content, $pos, $len);
        $pos         += $len;
        $crc_channel = unpack("V", substr($content, $pos, 4))[1];
        $pos         += 4;
        $crc_uid     = unpack("V", substr($content, $pos, 4))[1];
        $pos         += 4;
        $msgLen      = unpack("v", substr($content, $pos, 2))[1];
        $pos         += 2;
        $msg         = substr($content, $pos, $msgLen);

        $this->appID = $appid;
        $message     = new Message();
        $message->unpackContent($msg);
        $this->message = $message;

        //non reversable values
        $this->appCertificate = $appCertificate;
        $this->channelName    = $channelName;
        $this->setUid($uid);
        return true;
    }

    function build()
    {
        $msg = $this->message->packContent();
        $val = array_merge(unpack("C*", $this->appID), unpack("C*", $this->channelName), unpack("C*", $this->uid), $msg);

        $sig = hash_hmac('sha256', implode(array_map("chr", $val)), $this->appCertificate, true);

        $crc_channel_name = crc32($this->channelName) & 0xffffffff;
        $crc_uid          = crc32($this->uid) & 0xffffffff;

        $content = array_merge(unpack("C*", packString($sig)), unpack("C*", pack("V", $crc_channel_name)), unpack("C*", pack("V", $crc_uid)), unpack("C*", pack("v", count($msg))), $msg);
        $version = "006";
        $ret     = $version . $this->appID . base64_encode(implode(array_map("chr", $content)));
        return $ret;
    }
}
