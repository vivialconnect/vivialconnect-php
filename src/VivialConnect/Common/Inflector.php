<?php
/**
 * Inflector.php
 * Last changed: $Date: 2017-03-16 13:59:00 $
 *
 * @category ActiveResource
 * @package  ActiveResource_Core
 * @author   Roman Sklenář
 * @version  $Revision$
 */

/**
 * The Inflector transforms words from singular to plural, class 
 * names to table names, modularized class names to ones without, 
 * and class names to foreign keys.
 * This solution is partialy based on Ruby on Rails 
 * ActiveSupport::Inflector (c) David Heinemeier Hansson. 
 * (http://rubyonrails.org), MIT license.
 *
 * @see http://api.rubyonrails.org/classes/Inflector.html
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @copyright  Copyright (c) 2008 Luke Baker (http://lukebaker.org)
 * @copyright  Copyright (c) 2005 Flinn Mueller (http://actsasflinn.com)
 * @license    New BSD License
 * @example    http://addons.nettephp.com/inflector
 * @package    Nette\Extras\Inflector
 * @version    0.5
 */

namespace VivialConnect\Common;

class Inflector
{

    /** 
     * List of singular nouns as rule => replacement.
     *
     * @var array
     */
    public static $singulars = array(
        '/(quiz)$/i'                   => '\1zes',
        '/^(ox)$/i'                    => '\1en',
        '/([m|l])ouse$/i'              => '\1ice',
        '/(matr|vert|ind)(?:ix|ex)$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i'             => '\1es',
        '/([^aeiouy]|qu)y$/i'          => '\1ies',
        '/(hive)$/i'                   => '\1s',
        '/(?:([^f])fe|([lr])f)$/i'     => '\1\2ves',
        '/sis$/i'                      => 'ses',
        '/([ti])um$/i'                 => '\1a',
        '/(buffal|tomat)o$/i'          => '\1oes',
        '/(bu)s$/i'                    => '\1ses',
        '/(alias|status)$/i'           => '\1es',
        '/(octop|vir)us$/i'            => '\1i',
        '/(ax|test)is$/i'              => '\1es',
        '/s$/i'                        => 's',
        '/$/'                          => 's',
    );

    /** 
     * List of plural nouns as rule => replacement.
     *
     * @var array
     */
    public static $plurals = array(
        '/(database)s$/i'                                                  => '\1',
        '/(quiz)zes$/i'                                                    => '\1',
        '/(matr)ices$/i'                                                   => '\1ix',
        '/(vert|ind)ices$/i'                                               => '\1ex',
        '/^(ox)en/i'                                                       => '\1',
        '/(alias|status)es$/i'                                             => '\1',
        '/(octop|vir)i$/i'                                                 => '\1us',
        '/(cris|ax|test)es$/i'                                             => '\1is',
        '/(shoe)s$/i'                                                      => '\1',
        '/(o)es$/i'                                                        => '\1',
        '/(bus)es$/i'                                                      => '\1',
        '/([m|l])ice$/i'                                                   => '\1ouse',
        '/(x|ch|ss|sh)es$/i'                                               => '\1',
        '/(m)ovies$/i'                                                     => '\1ovie',
        '/(s)eries$/i'                                                     => '\1eries',
        '/([^aeiouy]|qu)ies$/i'                                            => '\1y',
        '/([lr])ves$/i'                                                    => '\1f',
        '/(tive)s$/i'                                                      => '\1',
        '/(hive)s$/i'                                                      => '\1',
        '/([^f])ves$/i'                                                    => '\1fe',
        '/(^analy)ses$/i'                                                  => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i'                                                      => '\1um',
        '/(n)ews$/i'                                                       => '\1ews',
        '/s$/i'                                                            => '',
    );

    /** 
     * List of irregular nouns as rule => replacement.
     *
     * @var array
     */
    public static $irregular = array(
        'person' => 'people',
        'man'    => 'men',
        'child'  => 'children',
        'sex'    => 'sexes',
        'move'   => 'moves',
        'cow'    => 'kine',
    );

    /** 
     * List of uncountable nouns.
     *å
     * @var array
     */
    public static $uncountable = array(
        'equipment', 
        'information', 
        'rice', 
        'money', 
        'species', 
        'series', 
        'fish', 
        'sheep',
    );

    /** @var bool  use Ruby on Rails ActiveRecord naming conventions? */
    public static $railsStyle = true;

    /**
     * The reverse of pluralize, returns the singular form of a word.
     *
     * @param string $word The word to singularize.
     *
     * @return string
     */
    public static function singularize($word)
    {
        $lower = strtolower($word);

        if (self::isSingular($word) === true) {
            return $word;
        }

        if (self::isCountable($word) === false) {
            return $word;
        }

        if (self::isIrregular($word) === true) {
            foreach (self::$irregular as $single => $plural) {
                if ($lower === $plural) {
                    return $single;
                }
            }
        }

        foreach (self::$plurals as $rule => $replacement) {
            if (preg_match($rule, $word) > 0) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return false;
    }

    /**
     * Returns the plural form of the word.
     *
     * @param string $word The word to pluralize.
     *
     * @return string
     */
    public static function pluralize($word)
    {
        $lower = strtolower($word);

        if (self::isPlural($word) === true) {
            return $word;
        }

        if (self::isCountable($word) === false) {
            return $word;
        }

        if (self::isIrregular($word) === true) {
            return self::$irregular[$lower];
        }

        foreach (self::$singulars as $rule => $replacement) {
            if (preg_match($rule, $word) > 0) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return false;
    }

    /**
     * Returns true if the given string is a singular noun, false otherwise.
     *
     * @param string $word The word to check.
     *
     * @return boolean
     */
    public static function isSingular($word)
    {
        if (self::isCountable($word) === false) {
            return true;
        }

        return (self::isPlural($word) === false);
    }

    /**
     * Returns true if the given string is a plural noun, false otherwise.
     *
     * @param string $word The word to check.
     *
     * @return boolean
     */
    public static function isPlural($word)
    {
        $lower = strtolower($word);

        if (self::isCountable($word) === false) {
            return true;
        }

        if (self::isIrregular($word) === true) {
            return (in_array($lower, array_values(self::$irregular)) === true);
        }

        foreach (self::$plurals as $rule => $replacement) {
            if (preg_match($rule, $word) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the given string is a countable noun, false otherwise.
     *
     * @param string $word The word to check.
     *
     * @return boolean
     */
    public static function isCountable($word)
    {
        $lower = strtolower($word);
        return (in_array($lower, self::$uncountable) === false);
    }


    /**
     * Returns true if the given string is an irregular noun, false otherwise.
     *
     * @param string $word The word to check.
     *
     * @return boolean
     */
    public static function isIrregular($word)
    {
        $lower = strtolower($word);
        return (in_array($lower, self::$irregular) === true || array_key_exists($lower, self::$irregular) === true);
    }

    /**
     * Turns a number into an ordinal string used to denote the
     * position in an ordered sequence such as 1st, 2nd, 3rd, 4th.
     *
     * @param integer $number The number to ordinalize.
     *
     * @return string
     */
    public static function ordinalize($number)
    {
        $number = (int) $number;

        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number.'th';
        } else {
            switch ($number % 10) {
            case 1: 
                return $number.'st';
            case 2: 
                return $number.'nd';
            case 3: 
                return $number.'rd';
            default: 
                return $number.'th';
            }
        }
    }

    /**
     * By default, camelize() converts strings to UpperCamelCase. If the second 
     * argument is set to false then camelize() produces lowerCamelCase. camelize() 
     * will also convert '/' to '\' which is useful for converting paths to namespaces.
     *
     * @param string $word       Lower case and underscored word.
     * @param bool   $firstUpper First letter in uppercase?
     *
     * @return string
     */
    public static function camelize($word, $firstUpper=true)
    {
        $args = array(
            "strtoupper('\\2')", 
            "strtoupper('\\2')",
        );
        $word = preg_replace(array('/(^|_)(.)/e', '/(\/)(.)/e'), $args, strval($word));
        if ($firstUpper === true) {
            return ucfirst($word);
        } else {
            return lcfirst($word);
        }
    }

    /**
     * Replaces underscores with dashes in the string.
     *
     * @param string $word Underscored word.
     *
     * @return string
     */
    public static function dasherize($word)
    {
        return preg_replace('/_/', '-', strval($word));
    }

    /**
     * Capitalizes all the words and replaces some characters in the string 
     * to create a nicer looking title. Titleize() is meant for creating 
     * pretty output.
     *
     * @param string $word Underscored word.
     *
     * @return string
     */
    public static function titleize($word)
    {
        return preg_replace(array("/\b('?[a-z])/e"), array("ucfirst('\\1')"), self::humanize(self::underscore($word)));
    }

    /**
     * The reverse of camelize(). Makes an underscored form from the expression 
     * in the string. Changes '::' to '/' to convert namespaces to paths.
     *
     * @param string $word Camel cased word.
     *
     * @return string
     */
    public static function underscore($word)
    {
        return strtolower(preg_replace('/([A-Z]+)([A-Z])/', '\1_\2', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', $word)));
    }

    /**
     * Capitalizes the first word and turns underscores into spaces and strips _id.
     * Like titleize(), this is meant for creating pretty output.
     *
     * @param string $word Lower case and underscored word.
     *
     * @return string
     */
    public static function humanize($word)
    {
        $regexes = array(
            '/_id$/', 
            '/_/',
        );
        $replace = array(
            '', 
            ' ',
        );
        return ucfirst(strtolower(preg_replace($regexes, $replace, $word)));
    }

    /**
     * Removes the namespace part from the expression in the string.
     *
     * @param string $class Class name in namespace.
     *
     * @return string
     */
    public static function demodulize($class)
    {
        $class = ltrim(strval($class), '\\');
        $pos   = strrpos($class, '\\');
        if ($pos !== false) {
            $class = substr($class, ($pos + 1));
        }
        
        return preg_replace('/^.*::/', '', $class);
    }

    /**
     * Creates the name of a table like Rails does for models to table names.
     * This method uses the pluralize method on the last word in the string.
     *
     * @param string $class Class name.
     *
     * @return string
     */
    public static function tableize($class)
    {
        $count = substr_count($class, 'Model', 1);
        if ($count > 0) {
            $class = preg_replace('/Model$/', '', $class);
        }
        
        $table = self::pluralize($class);
        
        if (self::$railsStyle === true) {
            return self::underscore($table);
        } else {
            return self::camelize($table);
        }
    }

    /**
     * Create a class name from a plural table name like Rails does for table
     * names to models. Note that this returns a string and not a Class.
     * To convert to an actual class follow classify() with constantize().
     *
     * @param string $table Table name.
     *
     * @return string
     */
    public static function classify($table)
    {
        return self::camelize(self::singularize($table));
    }

    /**
     * Creates a foreign key name from a class name. Second parameter sets 
     * whether the method should put '_' between the name and 'id'/'Id'.
     *
     * @param string $class Class name.
     *
     * @return string
     */
    public static function foreignKey($class)
    {
        $result = self::underscore(self::demodulize($class));
        if (self::isPlural($class) === true) {
            $result = self::underscore(self::singularize($class));
        }
        
        if (self::$railsStyle === true) {
            $result .= '_id';
        } else {
            $result .= 'Id';
        }
        
        return $result;
    }

    /**
     * Create the name of an intersect entity of M:N relation of the given tables.
     *
     * @param string $local      Local table name.
     * @param string $referenced Referenced table name.
     *
     * @return string
     */
    public static function intersectEntity($local, $referenced)
    {
        $separator = '';
        if (self::$railsStyle === true) {
            $separator = '_';
        }
        
        return self::tableize(self::demodulize($local)).$separator.self::tableize(self::demodulize($referenced));
    }
}
