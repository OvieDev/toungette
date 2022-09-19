<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use OvieDev\Toungette\Scheme;

final class SchemeTests extends TestCase{

    private Scheme $scheme;

    protected function setUp(): void
    {
        $this->scheme = new Scheme("test_component_schem.json", "fallback");
    }

    public function test_add(): void {
        $this->scheme->modify_key("key.new", ["hey", "im", "new"]);

        $this->assertSame(["hey", "im", "new"], $this->scheme->get_keys()["key.new"]);
    }

    /**
     * @depends test_add
     */
    public function test_reset(): void {
        $this->scheme = new Scheme("test_component_schem.json", "fallback");
        $this->scheme->modify_key("key.new", ["hey", "im", "new"]);
        $this->scheme->reset_scheme();

        $this->assertSame(1, count($this->scheme->get_keys()));
    }


    public function test_remove_key(): void
    {
        $this->scheme->delete_key("key.coolkey");

        $this->assertArrayNotHasKey("key.coolkey", $this->scheme->get_keys());

        $this->expectException(Exception::class);
        $this->scheme->delete_key("key.notexist");
    }

    public function test_push(): void
    {
        $this->scheme->push_to_schema("de", true, []);

        $this->assertContains("de", $this->scheme->get_schema());
        $this->assertContains($this->scheme->fallback, $this->scheme->get_keys()["key.coolkey"]);

        $this->scheme->push_to_schema("fr", false, ["not a fallback"]);

        $this->assertContains("fr", $this->scheme->get_schema());
        $this->assertContains("not a fallback", $this->scheme->get_keys()["key.coolkey"]);
    }

    /**
     * @depends test_push
     */
    public function test_pop(): void
    {
        $this->scheme->push_to_schema("de", true, []);

        $this->scheme->pop_from_schema();
        $this->assertSame(3, count($this->scheme->get_schema()));
    }

    public function test_find_key(): void
    {
        $val = $this->scheme->get_key_with_lang("key.coolkey", "en");
        $this->assertSame("hi", $val);
    }
}
