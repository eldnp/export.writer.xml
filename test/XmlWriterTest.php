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

namespace EldnpTest\Export\Writer\Xml;

use Eldnp\Export\Writer\Xml\Configuration\ConfigurationProviderInterface;
use Eldnp\Export\Writer\Xml\Configuration\SimpleConfigurationProvider;
use Eldnp\Export\Writer\Xml\Exception\RuntimeException;
use Eldnp\Export\Writer\Xml\RenderStrategy\AttributeRendererStrategy;
use Eldnp\Export\Writer\Xml\RenderStrategy\ElementRendererStrategy;
use Eldnp\Export\Writer\Xml\RenderStrategy\RendererStrategyInterface;
use Eldnp\Export\Writer\Xml\XmlWriter;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlWriterTest
 *
 * @package EldnpTest\Export\Writer\Xml
 */
class XmlWriterTest extends TestCase
{
    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function createTemporaryFile()
    {
        if (false === $temporaryFileName = tempnam(sys_get_temp_dir(), 'xml')) {
            throw new \RuntimeException('unable create temporary file');
        }

        return $temporaryFileName;
    }

    /**
     * @return ConfigurationProviderInterface[]
     */
    private function getAvailableConfigurations()
    {
        return array(
            new SimpleConfigurationProvider('root-1', 'entity-1', '1.1', 'windows-1251', true, "\t"),
            new SimpleConfigurationProvider('Root-2', 'Entity-2', '1.1', 'windows-1251', false),
            new SimpleConfigurationProvider('root-3', 'entity-3', '1.1', 'windows-1251'),
            new SimpleConfigurationProvider('Root-4', 'Entity-4', '1.1'),
            new SimpleConfigurationProvider('root-5', 'entity-5'),
        );
    }

    /**
     * @return RendererStrategyInterface[]
     */
    private function getAvailableRendererStrategies()
    {
        return array(
            'element' => new ElementRendererStrategy(),
            'attribute' => new AttributeRendererStrategy(),
        );
    }

    private function getDestinationFileName($rendererStrategyName, ConfigurationProviderInterface $configuration)
    {
        $fileNameParts = array(
            $rendererStrategyName,
            $configuration->getVersion(),
            $configuration->getEncoding(),
            $configuration->isIndented() ? 'WithIndent' : 'WithoutIndent',
        );
        if ($configuration->isIndented()) {
            $fileNameParts[] = $configuration->getIndentString() == "\t" ? 'Tabs' : 'Spaces';
        }

        return implode('.', $fileNameParts) . '.xml';
    }

    private function getData()
    {
        static $data = array(
            array(
                'field1' => 'value 1',
                'field2' => 'value 2',
                'field3' => 'long value with quotes \' " `',
                'field4' => 'long value with special chars < ! <!-- //-->>',
            ),
            array(
                'field-1' => 'value 1',
                'field-2' => 'value 2',
                'field-3' => 'long value with quotes \' " `',
                'field-4' => 'long value with special chars < ! <!-- //-->>',
            ),
        );
        return $data;
    }

    public function writeDataProvider()
    {
        $data = array();
        foreach ($this->getAvailableConfigurations() as $configuration) {
            foreach ($this->getAvailableRendererStrategies() as $strategyName => $strategy) {
                $data[] = array(
                    realpath(__DIR__ . '/_fixture/' . $this->getDestinationFileName($strategyName, $configuration)),
                    $configuration,
                    $strategy,
                );
            }
        }
        return $data;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnableToOpenUriException()
    {
        /** @var ConfigurationProviderInterface $configuration */
        $configuration = $this->prophesize(
            '\Eldnp\Export\Writer\Xml\Configuration\ConfigurationProviderInterface'
        )->reveal();
        /** @var RendererStrategyInterface $rendererStrategy */
        $rendererStrategy = $this->prophesize(
            '\Eldnp\Export\Writer\Xml\RenderStrategy\RendererStrategyInterface'
        )->reveal();
        new XmlWriter('undefined-scheme://undefined-resource', $configuration, $rendererStrategy);
    }

    /**
     * @param string $expectedXmlFile
     * @param ConfigurationProviderInterface $configurationProvider
     * @param RendererStrategyInterface $rendererStrategy
     *
     * @dataProvider writeDataProvider
     */
    public function testWrite(
        $expectedXmlFile,
        ConfigurationProviderInterface $configurationProvider,
        RendererStrategyInterface $rendererStrategy
    ) {
        $temporaryFile = $this->createTemporaryFile();
        $uri = "file://{$temporaryFile}";
        $writer = new XmlWriter($uri, $configurationProvider, $rendererStrategy);
        foreach ($this->getData() as $data) {
            $writer->write($data);
        }
        $writer->flush();
        $writer->close();
        $content = file_get_contents($temporaryFile);
        unlink($temporaryFile);
        $this->assertStringEqualsFile($expectedXmlFile, $content);
    }
}
