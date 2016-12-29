<?php
    function dump($data) {
        if(is_array($data)) { 
            print "<pre>-----------------------\n";
            print_r($data);
            print "-----------------------</pre>";
        } elseif (is_object($data)) {
            print "<pre>==========================\n";
            var_dump($data);
            print "===========================</pre>";
        } else {
            print "=========&gt; ";
            var_dump($data);
            print " &lt;=========";
        }
    }

    function isset(&$variable) {
        if (isset($variable)) {
            return $variable;
        } else {
            return 'undefined';
        }
    }

    function inRange($num, $floor, $ceil) {
        if ($num >= $floor && $num <= $ceil) {
            return true;
        } else {
            return false;
        }
    }

    function multi_in_array($value, $array) { 
        foreach ($array AS $item) { 
            if (!is_array($item)) { 
                if ($item == $value) { 
                    return true; 
                } 
                continue; 
            } 

            if (in_array($value, $item)) { 
                return true; 
            } 
            else if (multi_in_array($value, $item)) { 
                return true; 
            } 
        } 
        return false; 
    }
?>