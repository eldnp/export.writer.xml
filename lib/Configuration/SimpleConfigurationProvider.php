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
 * along with Eldnp/export.writer.xml. If not, see <http://www.gnu.org/licenses/>.
 *
 * @see       https://github.com/eldnp/export.writer.xml for the canonical source repository
 * @copyright Copyright (c) 2017 Oleg Verevskoy <verevskoy@gmail.com>
 * @license   https://github.com/eldnp/export.writer.xml/blob/master/LICENSE GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace Eldnp\Export\Writer\Xml\Configuration;

/**
 * Class SimpleConfigurationProvider
 *
 * @package Eldnp\Export\Writer\Xml\Configuration
 */
class SimpleConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * @var string
     */
    private $rootElement;

    /**
     * @var string
     */
    private $entityElement;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $encoding;

    /**
     * @var bool
     */
    private $indent;

    /**
     * @var string
     */
    private $indentString;

    /**
     * SimpleConfigurationProvider constructor.
     *
     * @param string $rootElement
     * @param string $entityElement
     * @param string $version
     * @param string $encoding
     * @param bool $indent
     * @param string $indentString
     */
    public function __construct(
        $rootElement,
        $entityElement,
        $version = '1.0',
        $encoding = 'UTF-8',
        $indent = true,
        $indentString = '    '
    ) {
        $this->rootElement = $rootElement;
        $this->entityElement = $entityElement;
        $this->version = $version;
        $this->encoding = $encoding;
        $this->indent = $indent;
        $this->indentString = $indentString;
    }


    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @inheritdoc
     */
    public function getRootElement()
    {
        return $this->rootElement;
    }

    /**
     * @inheritdoc
     */
    public function getEntityElement()
    {
        return $this->entityElement;
    }

    /**
     * @inheritdoc
     */
    public function isIndented()
    {
        return $this->indent;
    }

    /**
     * @inheritdoc
     */
    public function getIndentString()
    {
        return $this->indentString;
    }
}
