<?php

declare(strict_types=1);

namespace ChangHorizon\DirectoryTree\Node;

interface NodeInterface
{
    public function getPath(): string;

    public function getLocation(): string;

    public function getParent(): ?NodeInterface;

    public function addChild(NodeInterface $child): void;

    /**
     * @return ?NodeInterface[]
     */
    public function getChildren(): ?array;
}
