<?php

declare(strict_types=1);

namespace Fluffy\GithubClient\Models;

use Fluffy\GithubClient\Entities\Git;

class Tree
{
    /**
     * @param Git\Tree $tree
     * @param StagedFile[] $staged
     * @param DeletedFile[] $deleted
     */
    public static function fromGitTree(Git\Tree $tree, array $staged = [], array $deleted = [])
    {
        $leafs = $tree->getTree();

        $deletedPaths = array_map(function (DeletedFile $deletedFile) {
            return $deletedFile->getPath();
        }, $deleted);

        $stagedMapping = [];
        foreach ($staged as $file) {
            $stagedMapping[$file->getPath()] = $file;
        }

        $withoutDeleted = array_values(array_filter($leafs, function (array $leaf) use ($deletedPaths) {
            return !in_array($leaf['path'], $deletedPaths, true);
        }));

        for ($i = 0; $i < count ($withoutDeleted); $i++) {
            $path = $withoutDeleted[$i]['path'];

            if (array_key_exists($path, $stagedMapping)) {
                $withoutDeleted[$i]['sha'] = $stagedMapping[$path]->getBlob()->getSha();
            }
        }

        return;
    }
}
