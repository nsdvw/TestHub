<?php
namespace TestHubBundle\Helper;

/**
 * Class StringGenerator
 *
 * Helper to generate random strings
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

    /**
     * @return string
     */
    private static function getCharacters()
    {
        return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        . '~`!@#$%^&*()-=+_][{}|?><';
    }
}
