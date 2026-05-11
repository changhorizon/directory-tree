<?php

declare(strict_types=1);

namespace ChangHorizon\DirectoryTree\Builder;

use FilesystemIterator;
use ChangHorizon\DirectoryTree\Exception\DirectoryTreeException;
use ChangHorizon\DirectoryTree\Memory\DirectoryTreeMemory;
use ChangHorizon\DirectoryTree\Memory\TreeMemoryInterface;
use ChangHorizon\DirectoryTree\Node\DirectoryNode;
use ChangHorizon\DirectoryTree\Node\NodeInterface;
use SplFileInfo;
use UnexpectedValueException;

class DirectoryTreeBuilder implements TreeBuilderInterface
{
    public function build(NodeInterface $root): TreeMemoryInterface
    {
        $this->buildIterative($root);

        return new DirectoryTreeMemory($root);
    }

    private function buildIterative(NodeInterface $root): void
    {
        $stack = [$root];

        while (!empty($stack)) {
            /** @var DirectoryNode $current */
            $current = array_pop($stack);

            if (!is_dir($current->getPath())) {
                continue;
            }

            try {
                $iterator = new FilesystemIterator(
                    $current->getPath(),
                    FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_FILEINFO,
                );
            } catch (UnexpectedValueException $e) {
                throw new DirectoryTreeException("Failed to initialize directory iterator: {$current->getPath()}", 0, $e);
            }

            // 将文件和目录分开存储
            $dirs  = [];
            $files = [];

            /** @var SplFileInfo $childInfo */
            foreach ($iterator as $childInfo) {
                $childNode = new DirectoryNode($childInfo->getPathname(), $current);

                if (is_dir($childNode->getPath())) {
                    $dirs[] = $childNode; // 存储目录
                } else {
                    $files[] = $childNode; // 存储文件
                }
            }

            // 按字母排序目录和文件
            usort($dirs, fn ($a, $b) => strcmp(basename($a->getPath()), basename($b->getPath())));
            usort($files, fn ($a, $b) => strcmp(basename($a->getPath()), basename($b->getPath())));

            // 合并目录和文件，确保目录排在前面
            $children = array_merge($dirs, $files);

            // 将排序后的子节点添加到当前节点
            foreach ($children as $child) {
                $current->addChild($child);

                if (is_dir($child->getPath())) {
                    $stack[] = $child; // 目录继续递归
                }
            }
        }
    }
}
