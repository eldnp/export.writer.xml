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

namespace Eldnp\Export\Writer\Xml;

use Eldnp\Export\Map\AbstractMapWriter;
use Eldnp\Export\Writer\Xml\Configuration\ConfigurationProviderInterface;
use Eldnp\Export\Writer\Xml\Exception\RuntimeException;
use Eldnp\Export\Writer\Xml\RenderStrategy\RendererStrategyInterface;

/**
 * Class XmlWriter
 *
 * @package Eldnp\Export\Writer\Xml
 */
class XmlWriter extends AbstractMapWriter
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var ConfigurationProviderInterface
     */
    private $configurationProvider;

    /**
     * @var RendererStrategyInterface
     */
    private $rendererStrategy;

    /**
     * @var \XMLWriter
     */
    private $writer;

    /**
     * XmlWriter constructor.
     *
     * @param string $uri
     * @param ConfigurationProviderInterface $configurationProvider
     * @param RendererStrategyInterface $rendererStrategy
     */
    public function __construct(
        $uri,
        ConfigurationProviderInterface $configurationProvider,
        RendererStrategyInterface $rendererStrategy
    ) {
        $this->uri = $uri;
        $this->configurationProvider = $configurationProvider;
        $this->rendererStrategy = $rendererStrategy;

        $this->writer = new \XMLWriter();
        if (false === @$this->writer->openURI($uri)) {
            throw new RuntimeException("unable to open stream '{$this->uri}'");
        }
        $this->writer->setIndent($this->configurationProvider->isIndented());
        $this->writer->setIndentString($this->configurationProvider->getIndentString());
        $this->writer->startDocument(
            $this->configurationProvider->getVersion(),
            $this->configurationProvider->getEncoding()
        );
        $this->writer->startElement($this->configurationProvider->getRootElement());
    }

    /**
     * @inheritdoc
     */
    protected function writeMap(array $data)
    {
        $this->writer->startElement($this->configurationProvider->getEntityElement());
        $this->rendererStrategy->render($this->writer, $data);
        $this->writer->endElement();
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        $this->writer->flush();
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $this->writer->endElement();
        $this->writer->endDocument();
        $this->writer->flush();
    }
}
