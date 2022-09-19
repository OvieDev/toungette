<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use OvieDev\Toungette\Scheme;

final class SchemeTests extends TestCase{

    public function test_add(): void {
        $scheme = new Scheme("test_component_schem.json", "fallback");
        $scheme->modify_key("key.new", ["hey", "im", "new"]);

        $this->assertSame(["hey", "im", "new"], $scheme->getKeys()["key.new"]);
    }

    /**
     * @depends test_add
     */
    public function test_reset(): void {
        $scheme = new Scheme("test_component_schem.json", "fallback");
        $scheme->modify_key("key.new", ["hey", "im", "new"]);
        $scheme->reset_scheme();

        $this->assertSame(1, count($scheme->getKeys()));
    }


    public function test_remove_key(): void
    {
        $scheme = new Scheme("test_component_schem.json", "fallback");
        $scheme->delete_key("key.coolkey");

        $this->assertSame(0, count($scheme->getKeys()));

        $this->expectException(Exception::class);
        $scheme->delete_key("key.notexist");
    }

    public function test_push(): void
    {
        $scheme = new Scheme("test_component_schem.json", "fallback");
        $scheme->push_to_schema("de", true, []);

        $this->assertSame("de", $scheme->getSchema()[3]);
        $this->assertSame($scheme->fallback, $scheme->getKeys()["key.coolkey"][3]);

        $scheme->push_to_schema("fr", false, ["not a fallback"]);

        $this->assertSame("fr", $scheme->getSchema()[4]);
        $this->assertSame("not a fallback", $scheme->getKeys()["key.coolkey"][4]);
    }
}
