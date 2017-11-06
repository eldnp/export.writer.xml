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

namespace Eldnp\Export\Writer\Xml\RenderStrategy;

/**
 * Class AttributeRendererStrategy
 *
 * @package Eldnp\Export\Writer\Xml\RenderStrategy
 */
class AttributeRendererStrategy implements RendererStrategyInterface
{
    /**
     * @inheritdoc
     */
    public function render(\XMLWriter $writer, array $data)
    {
        foreach ($data as $attribute => $value) {
            $writer->writeAttribute($attribute, $value);
        }
    }
}
