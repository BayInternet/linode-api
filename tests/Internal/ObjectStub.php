<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2016 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
//----------------------------------------------------------------------

namespace Tests\Linode\Internal;

use Linode\Internal\AbstractObject;
use Linode\LinodeClient;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @property    bool $flag
 */
class ObjectStub extends AbstractObject
{
    public $flag;

    /**
     * {@inheritdoc}
     */
    public function __construct(LinodeClient $client, array $data = [], $parent = null)
    {
        parent::__construct($client, $data, $parent);
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return rtrim('/tests/' . $this->id, '/');
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('flag', [
            new Constraints\Type(['type' => 'bool']),
            new Constraints\NotNull(),
        ]);
    }
}
