<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Entities;

/**
 * Class Webhook
 * @package Fluffy\GithubClient\Entities
 */
class Webhook
{
    /** @var string */
    public $type;

    /** @var array */
    public $events;

    /**
     * Release constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->events = $data['events'];
    }

    /**
     * @param array $data
     * @return Webhook
     */
    public static function fromArray(array $data): Webhook
    {
        return new static($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function fromCollection(array $data): array
    {
        return array_map(function (array $item) {
            return static::fromArray($item);
        }, $data);
    }
}
