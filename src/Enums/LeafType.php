<?php
declare(strict_types=1);

namespace Fluffy\GithubClient\Enums;

interface LeafType
{
    /** @var string */
    public const BLOB = 'blob';

    /** @var string */
    public const TREE = 'tree';

    /** @var string */
    public const COMMIT = 'commit';
}
