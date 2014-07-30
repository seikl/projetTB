<?php
    //pour lever une exception en cas de warning()
    function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
    {
        if (0 === error_reporting()) {
            return false;
        }

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler('handleError');
    //--------------------------------------------
?>
