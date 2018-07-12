<?php
namespace PDoc;

use \ast\Node;

use \PDoc\NodeParsers\AbstractNodeParser;

/**
 * Recursively traverse the ast
 * @param \ast\Node $ast The next node to visit
 * @param callable $callback The function to call on each node
 */
function ast_walk($ast, callable $callback): void
{
    $callback($ast);
    if ($ast instanceof Node) {
        foreach ($ast->children as $child) {
            ast_walk($child, $callback);
        }
    }
}

class ASTFinder
{
    /**
     * Find the first node that matches a certain kind.
     *
     * @param Node $root The node from which to start searching.
     * @param int $kind The Node::kind property to match.
     * @return Node The first matching node.
     */
    public function firstOfKind(Node $root, int $kind): Node
    {
        $nodes = $this->byKind($root, $kind); // TODO: optimize
        return $nodes[0] ?? null;
    }
    /**
     * Find all nodes that match a certain kind.
     *
     * @param Node $root The node from which to start searching.
     * @param int $kind The Node::kind property to match.
     * @return Node[] All the matching nodes.
     */
    public function byKind(Node $root, int $kind): array
    {
        $nodes = [];
        ast_walk($root, function ($node) use (&$nodes, $kind) {
            if ($node instanceof Node) {
                if ($node->kind === $kind) {
                    $nodes[] = $node;
                }
            }
        });
        return $nodes;
    }
    /**
     * Find nodes and return the result of parsing them with a parser.
     * @param Node $root The starting node.
     * @param ParseContext $ctx The context to pass to parser on each invokation.
     * @param int $kind Parse only nodes matching this kind.
     * @param AbstractNodeParser $parser The parser to use for parsing.
     * @return \PDoc\Entities\AbstractEntity[] The results of parsing with the parser.
     */
    public function parseWith($root, ParseContext $ctx, int $kind, AbstractNodeParser $parser): array
    {
        $nodes = $this->byKind($root, $kind);
        return array_map(function ($node) use ($parser, $ctx) {
            return $parser->parse($node, $ctx);
        }, $nodes);
    }
}
