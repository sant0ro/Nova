<?php

namespace Hab\Core;

use Hab\Database\DatabaseQueries;

/**
 * Class Utils
 *
 * @version 0.1
 * @author Claudio Santoro
 */
final class HabUtils
{
    /**
     * Generate Token
     *
     * Using OpenSSL Random Pseudo Bytes and bin2hex.
     *
     * @Attention PHP 5.3 or Higher!
     *
     * @param bool $isSSO If is SSO
     * @return string
     */
    public static function TokenCrypto($isSSO = false)
    {
        return ($isSSO ? 'SSO-' : 'HabClient-') . bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * Generate and Store Token Hash
     *
     * Usable for Client Logon and External Client Auth
     *
     * @return string
     */
    public static function generateExternal()
    {
        // Generate the Token
        $tokenHash = DatabaseQueries::createToken();

        // Get the Hotel Base Url
        $hotelUrl = HabEngine::getInstance()->getApiSettings()->hotel->base;

        // Return HHotel Wrapper with Token and Hotel URI
        return "hhotel://{$hotelUrl}?token={$tokenHash}";
    }

    /**
     * Retrieve Remote Content and Return the Data
     *
     * @Attention: The intent of this was to use with GitHub RAW Content
     *
     * @param string $remoteURI
     * @return mixed
     */
    public static function getRemoteContent($remoteURI)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $remoteURI);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}
