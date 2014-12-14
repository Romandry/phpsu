<?php


/**
 * ClassUtils
 *
 * Classes and object utilites
 */

class ClassUtils
{


    /**
     * isCallableMethod
     *
     * Check for exists and available method
     *
     * @param  mixed  $obj    Name of instance of class
     * @param  string $method Name of method
     * @return bool           Status of exists and available method
     */

    public static function isCallableMethod($obj, $method)
    {

        $isAvail = false;
        if (method_exists($obj, $method)) {
            $refMethod = new ReflectionMethod($obj, $method);
            if ($refMethod->isPublic() && !$refMethod->isStatic()) {
                $isAvail = true;
            }
            unset($refMethod);
        }
        return $isAvail;

    }
}
