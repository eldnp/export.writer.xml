<?php
/*
 * This file is part of Eldnp/export.writer.xml.
 *
 * Eldnp/export.writer.xml is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Eldnp/export.writer.xml is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Eldnp/export.writer.xml.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @see       https://github.com/eldnp/export.writer.xml for the canonical source repository
 * @copyright Copyright (c) 2017 Oleg Verevskoy <verevskoy@gmail.com>
 * @license   https://github.com/eldnp/export.writer.xml/blob/master/LICENSE GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace EldnpTest\Export\Writer\Xml\Configuration;

use Eldnp\Export\Writer\Xml\Configuration\SimpleConfigurationProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleConfigurationProviderTest
 *
 * @package EldnpTest\Export\Writer\Xml\Configuration
 */
class SimpleConfigurationProviderTest extends TestCase
{
    public function testInterfaceImplementation()
    {
        $this->assertInstanceOf(
            '\Eldnp\Export\Writer\Xml\Configuration\ConfigurationProviderInterface',
            new SimpleConfigurationProvider('root', 'element')
        );
    }

    /**
     * @param SimpleConfigurationProvider $cp
     * @param array $expectedValues
     *
     * @dataProvider constructorDataProvider
     */
    public function testConstructor(SimpleConfigurationProvider $cp, $expectedValues)
    {
        list ($rootElement, $entityElement, $version, $encoding, $indent, $indentString) = $expectedValues;
        $this->assertEquals($rootElement, $cp->getRootElement());
        $this->assertEquals($entityElement, $cp->getEntityElement());
        $this->assertEquals($version, $cp->getVersion());
        $this->assertEquals($encoding, $cp->getEncoding());
        $this->assertEquals($indent, $cp->isIndented());
        $this->assertEquals($indentString, $cp->getIndentString());
    }

    public function constructorDataProvider()
    {
        return array(
            array(
                new SimpleConfigurationProvider('root', 'entity', '1.0', 'windows-1251', false, "\t"),
                array('root', 'entity', '1.0', 'windows-1251', false, "\t"),
            ),
            array(
                new SimpleConfigurationProvider('root1', 'Entity', '1.1', 'windows-1251', false),
                array('root1', 'Entity', '1.1', 'windows-1251', false, '    '),
            ),
            array(
                new SimpleConfigurationProvider('root1', 'Entity', '1.1', 'windows-1251'),
                array('root1', 'Entity', '1.1', 'windows-1251', true, '    '),
            ),
            array(
                new SimpleConfigurationProvider('root1', 'Entity', '1.1'),
                array('root1', 'Entity', '1.1', 'UTF-8', true, '    '),
            ),
            array(
                new SimpleConfigurationProvider('Root', 'entity1'),
                array('Root', 'entity1', '1.0', 'UTF-8', true, '    '),
            ),
        );
    }
}
