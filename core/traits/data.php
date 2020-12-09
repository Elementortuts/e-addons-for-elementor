<?php

namespace EAddonsForElementor\Core\Traits;

/**
 * @author francesco
 */
trait Data {

    /**
     * Split a string by a string
     * <p>Returns an array of strings, each of which is a substring of <code>string</code> formed by splitting it on boundaries formed by the string <code>delimiter</code>.</p>
     * @param string $delimiter <p>The boundary string.</p>
     * @param string $string <p>The input string.</p>
     * @param int $limit <p>If <code>limit</code> is set and positive, the returned array will contain a maximum of <code>limit</code> elements with the last element containing the rest of <code>string</code>.</p> <p>If the <code>limit</code> parameter is negative, all components except the last -<code>limit</code> are returned.</p> <p>If the <code>limit</code> parameter is zero, then this is treated as 1.</p>
     * @param string $format <p>Perform a function an chunk, use functions like trim, intval, absint.</p>
     * @return array <p>Returns an <code>array</code> of <code>string</code>s created by splitting the <code>string</code> parameter on boundaries formed by the <code>delimiter</code>.</p><p>If <code>delimiter</code> is an empty <code>string</code> (""), <b>explode()</b> will return <b><code>FALSE</code></b>. If <code>delimiter</code> contains a value that is not contained in <code>string</code> and a negative <code>limit</code> is used, then an empty <code>array</code> will be returned, otherwise an <code>array</code> containing <code>string</code> will be returned.</p>
     */
    public static function explode($string = '', $delimiter = ',', $limit = PHP_INT_MAX, $format = null) {
        $tmp = array();
        if (is_array($string)) {
            return $string;
        }
        $strings = explode($delimiter, $string, $limit);
        $strings = array_map('trim', $strings);
        foreach ($strings as $value) {
            if ($value != '') {
                $tmp[] = $value;
            }
        }
        $strings = $tmp;
        if (!empty($strings) && $format) {
            $strings = array_map($format, $strings);
        }
        return $strings;
    }

    /**
     * Join array elements with a string
     * <p>Join array elements with a <code>glue</code> string.</p><p><b>Note</b>:</p><p><b>implode()</b> can, for historical reasons, accept its parameters in either order. For consistency with <code>explode()</code>, however, it may be less confusing to use the documented order of arguments.</p>
     * @param string $glue <p>Defaults to an empty string.</p>
     * @param array $pieces <p>The array of strings to implode.</p>
     * @param bool $listed <p>Return array as a list, maybe use it with empty glue.</p>
     * @return string <p>Returns a string containing a string representation of all the array elements in the same order, with the glue string between each element.</p>
     */
    public static function implode($pieces = array(), $glue = ', ', $listed = false) {
        $string = '';
        if (!empty($pieces) && is_array($pieces)) {
            if ($listed) {
                $string .= (is_string($listed)) ? '<'.$listed.'>' : '<ul>';
            }
            $i = 0;
            foreach ($pieces as $av) {
                if ($listed) {
                    $string .= '<li>';
                }
                if (is_object($av)) {
                    $av = self::to_string($av);
                }
                if (is_array($av)) {
                    $string .= self::implode($av, $glue, $listed);
                } else {
                    if ($i) {
                        $string .= $glue;
                    }
                    $string .= $av;
                }
                if ($listed) {
                    $string .= '</li>';
                }
                $i++;
            }
            if ($listed) {
                $string .= (is_string($listed)) ? '</'.$listed.'>' : '</ul>';
            }
        }
        return $string;
    }
    
    public static function adjust_data($value, $single = true) {
        if (!empty($value)) {
            if (is_array($value)) {
                if ($single === true || count($value) == 1) {
                    return self::adjust_data(reset($value), $single);
                }
            }
            return $value;
        }
        return '';
    }
    
    public static function strip_tag($tag, $content = '') {
        $content = preg_replace('/<' . $tag . '[^>]*>/i', '', $content);
        $content = preg_replace('/<\/' . $tag . '>/i', '', $content);
        return $content;
    }

    public static function get_array_value($array = array(), $keys = array()) {
        if (!empty($keys)) {
            $key = array_shift($keys);
            if (!isset($array[$key])) {
                return false;
            } else {
                if (is_array($array[$key])) {
                    return self::get_array_value($array[$key], $keys);
                } else {
                    return $array[$key];
                }
            }
        }
        return $array;
    }

    public static function set_array_value($array = array(), $keys = array(), $value = false) {
        if (is_array($keys) && !empty($keys)) {
            $key = array_shift($keys);
            if (!is_array($array)) {
                $array = array();
            }
            if (!isset($array[$key])) {
                $array[$key] = array();
            }
            $array[$key] = self::set_array_value($array[$key], $keys, $value);
            if (is_null($array[$key]) && is_null($value)) {
                unset($array[$key]);
            }
        } else {
            $array = $value;
        }
        return $array;
    }
    
    public static function get_object_method($obj, $method, $params = array()) {
        $value = false;
        $reflection = new \ReflectionMethod(get_class($obj), $method);
        if ($reflection->isStatic()) {
            if (!empty($params)) {
                $value = $obj::{$method}($params);
            } else {
                $value = $obj::{$method}();
            }
        } else {
            if (!empty($params)) {
                $value = $obj->{$method}($params);
            } else {
                $value = $obj->{$method}();
            }
        }
        return $value;
    }

    public static function to_string($data, $listed = false) {
        if (is_object($data)) {            
            switch (get_class($data)) {
                case 'WP_Term':
                    return $data->name;            
                case 'WP_Post':
                    return $data->post_title;
                case 'WP_User':
                    return $data->display_name;
                default:
                    $data = (array) $data;
            }
        }
        if (is_array($data)) {
            if (!empty($data['post_title'])) {
                return $data['post_title'];
            }
            if (!empty($data['display_name'])) {
                return $data['display_name'];
            }
            if (!empty($data['name'])) {
                return $data['name'];
            }
            if (count($data) == 1) {
                $first = reset($data);
                return self::to_string($first);
            }
            return self::implode($data, ', ', $listed);
        }
        return $data;
    }
    
    public static function empty($source, $key = false) {
        if (is_array($source)) {
            $source = array_filter($source);
        }        
        if ($key) {
            return \Elementor\Utils::is_empty($source, $key);
        }
        return empty($source);
    }

}
