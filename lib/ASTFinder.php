<?php
namespace PDoc;

use \ast\Node;

use \PDoc\NodeParsers\AbstractNodeParser;

function ast_walk($ast, callable $callback, $ctx)
{
    $ctx = $callback($ast, $ctx);
    if ($ast instanceof Node) {
        foreach ($ast->children as $child) {
            ast_walk($child, $callback, $ctx);
        }
    }
}

class ASTFinder
{
    public function firstOfKind(Node $root, int $kind)
    {
        $nodes = $this->byKind($root, $kind); // TODO: optimize
        return $nodes[0] ?? null;
    }
    public function byKind($root, int $kind): array
    {
        $nodes = [];
        ast_walk($root, function ($node, $ctx) use (&$nodes, $kind) {
            if ($node instanceof Node) {
                if ($node->kind === $kind) {
                    $nodes[] = $node;
                }
            }
        }, []);
        return $nodes;
    }
    public function parseWith($root, ParseContext $ctx, int $kind, AbstractNodeParser $parser): array
    {
        $nodes = $this->byKind($root, $kind);
        return array_map(function ($node) use ($parser, $ctx) {
            return $parser->parse($node, $ctx);
        }, $nodes);
    }
}
