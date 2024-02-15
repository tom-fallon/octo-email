<?php

namespace Api\src\Messages;

class ErrorMessages
{

    /**
     * @return string
     */
    public static function fatalErrorMessage(): string
    {
        return 'Fatal Error';
    }

    /**
     * @return string
     */
    public static function duplicateEntryMessage(): string
    {
        return 'Duplicate entry';
    }
}
