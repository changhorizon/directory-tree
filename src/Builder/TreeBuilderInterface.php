<?php

declare(strict_types=1);

namespace ChangHorizon\DirectoryTree\Builder;

use ChangHorizon\DirectoryTree\Memory\TreeMemoryInterface;
use ChangHorizon\DirectoryTree\Node\NodeInterface;

interface TreeBuilderInterface
{
    public function build(NodeInterface $root): TreeMemoryInterface;
}
