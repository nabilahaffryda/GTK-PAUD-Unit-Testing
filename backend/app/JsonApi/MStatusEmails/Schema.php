<?php

namespace App\JsonApi\MStatusEmails;

use App\Models\MStatusEmail;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'm_status_emails';

    /**
     * @param MStatusEmail $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param MStatusEmail $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'singkat'    => $resource->singkat,
            'keterangan' => $resource->keterangan,
        ];
    }

    /**
     * @param MStatusEmail $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
        ];
    }
}
