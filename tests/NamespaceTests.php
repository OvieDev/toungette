<?php

use OvieDev\Toungette\Scheme;
use PHPUnit\Framework\TestCase;

final class NamespaceTests extends TestCase
{
    private Scheme $scheme;

    protected function setUp(): void
    {
        $this->scheme = new Scheme("test_component_schem.json", "fallback");
    }

    public function test_namespace_fetching() {
        $this->assertSame("agree", $this->scheme->json["mycoolnamespace"]["key"][2]);
    }

    /**
     * @depends test_namespace_fetching
     */
    public function test_add_key() {
        $this->scheme->add_key("mycoolnamespace", "yay", ["wot", "is", "dis"]);
        $namespace = $this->scheme->get_namespace("mycoolnamespace");
        $this->assertSame(["wot", "is", "dis"], $namespace["yay"]);
    }
}