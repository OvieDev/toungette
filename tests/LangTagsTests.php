<?php
use OvieDev\Toungette\Translator;

class LangTagsTests extends \PHPUnit\Framework\TestCase
{
    private Translator $translator;

    protected function setUp(): void
    {
        $this->translator = new Translator("test_component_schem.json", "index.tounge", "pl");
    }

    public function testBlockSuccess() : void
    {
        $this->translator->translate();
        $text = $this->translator->text;
        $this->assertSame("<body><b>cool</b></body>", $text);
    }

    public function testBlockFailure() : void
    {
        $this->translator->lang="en";
        $this->translator->translate();
        $text = $this->translator->text;
        $this->assertSame("<body><b>hi</b><div>hi</div> This won't be in polish version of the site </body>", $text);
    }
}