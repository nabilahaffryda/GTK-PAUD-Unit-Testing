<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class SaveException extends Exception implements ClientAware
{
    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @return bool
     * @api
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory()
    {
        return 'save-exception';
    }
}
