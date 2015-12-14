<?php

namespace OroB2B\Bundle\TaxBundle\Tests\Unit\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroEntitySelectOrCreateInlineType;
use OroB2B\Bundle\TaxBundle\Form\Type\TaxSelectType;

class TaxSelectTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaxSelectType
     */
    protected $type;

    protected function setUp()
    {
        $this->type = new TaxSelectType();
    }

    public function testGetName()
    {
        $this->assertEquals(TaxSelectType::NAME, $this->type->getName());
    }

    public function testGetParent()
    {
        $this->assertEquals(OroEntitySelectOrCreateInlineType::NAME, $this->type->getParent());
    }

    public function testConfigureOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with(
                $this->callback(
                    function (array $options) {
                        $this->assertArrayHasKey('autocomplete_alias', $options);
                        $this->assertArrayHasKey('create_form_route', $options);
                        $this->assertArrayHasKey('configs', $options);
                        $this->assertEquals('orob2b_tax_tax_autocomplete', $options['autocomplete_alias']);
                        $this->assertEquals('orob2b_tax_tax_create', $options['create_form_route']);
                        $this->assertEquals(
                            ['placeholder' => 'orob2b.tax.form.choose'],
                            $options['configs']
                        );

                        return true;
                    }
                )
            );

        $this->type->configureOptions($resolver);
    }
}
