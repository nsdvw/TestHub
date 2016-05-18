<?php
namespace TestHubBundle\Helper;

/**
 * Class StringGenerator
 * @package TestHubBundle\Helper
 */
class StringGenerator
{
    /**
     * Length of generated string
     */
    const LENGTH = 40;

    /**
     * @return string
     */
    private static function getCharacters()
    {
        return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        . '~`!@#$%^&*()-=+_][{}|?><';
    }

    /**
     * @return string
     */
    public static function generateToken()
    {
        $string = self::generateString();
        return sha1($string);
    }

    /**
     * @return string
     */
    public static function generateString()
    {
        $string = '';
        $characters = self::getCharacters();
        $charactersLength = strlen($characters);
        for ($i = 0; $i < self::LENGTH; $i++) {
            $string .= $characters[rand(0, $charactersLength - 1)];
        }
        return $string;
    }
}
