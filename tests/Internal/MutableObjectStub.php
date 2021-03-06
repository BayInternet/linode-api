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

use Linode\Internal\AbstractMutableObject;
use Linode\LinodeClient;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @property    string $id
 * @property    bool   $flag
 */
class MutableObjectStub extends AbstractMutableObject
{
    protected $flag;

    /**
     * @param   LinodeClient $client
     * @param   bool         $flag
     */
    public function __construct(LinodeClient $client, $flag)
    {
        parent::__construct($client, ['flag' => $flag]);
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

    /**
     * {@inheritdoc}
     */
    protected function getMutableProperties()
    {
        return [
            'flag' => $this->flag,
        ];
    }
}
