<?php

declare(strict_types=1);

namespace ChangHorizon\DirectoryTree\Memory;

use ChangHorizon\DirectoryTree\Node\NodeInterface;

interface TreeMemoryInterface
{
    public function getRoot(): NodeInterface;

    /**
     * @return NodeInterface[]
     */
    public function getAncestors(NodeInterface $node): array;

    /**
     * @return NodeInterface[]
     */
    public function getSiblings(NodeInterface $node): array;

    /**
     * @return NodeInterface[]
     */
    public function getDescendants(NodeInterface $node): array;
}
