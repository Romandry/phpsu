<?php


/**
 * XmlSchemaValidator
 *
 * XML schema array exposition validator
 */

class XmlSchemaValidator
{


    /**
     * check
     *
     * Validate schema element structure
     *
     * @param  array $schemaElement Schema element
     * @return null
     */

    public static function check($schemaElement)
    {

        if (!is_array($schemaElement)) {
            throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Schema element is not array'
            ));
        }
        if (!array_key_exists('name', $schemaElement)) {
            throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Name of schema element not found'
            ));
        }

        if (array_key_exists('attributes', $schemaElement)) {
            self::checkAttributes($schemaElement['attributes']);
        }

        $existsChildren = false;
        if (array_key_exists('children', $schemaElement)) {
            if (!is_array($schemaElement['children'])) {
                throw new SystemErrorException(array(
                    'title'       => 'Schema XML error',
                    'description' => 'Children of element is not array'
                ));
            }
            foreach ($schemaElement['children'] as $element) {
                self::check($element);
            }
            $existsChildren = true;
        }

        if (array_key_exists('value', $schemaElement)) {
            if ($existsChildren) {
                throw new SystemErrorException(array(
                    'title'       => 'Schema XML error',
                    'description' => 'Value of schema element can\'t be declared with children'
                ));
            }
            if (is_object($value) || is_resource($value) || is_array($value)) {
                throw new SystemErrorException(array(
                    'title'       => 'Schema XML error',
                    'description' => 'Value of schema element has is not string'
                ));
            }
        }

    }


    /**
     * checkAttributes
     *
     * Validate schema attributes
     *
     * @param  array $attributes Schema element attributes
     * @return null
     */

    public static function checkAttributes($attributes)
    {

        if (!is_array($attributes)) {
            throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Attributes of schema element is not array'
            ));
        }
        foreach ($attributes as $attribute) {

            if (!is_array($attribute)) {
                throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Attribute of element is not array'
                ));
            }
            if (!array_key_exists('name', $attribute)) {
                throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Name of attribute not found'
                ));
            }
            if (!array_key_exists('value', $attribute)) {
                throw new SystemErrorException(array(
                'title'       => 'Schema XML error',
                'description' => 'Name of attribute not found'
                ));
            }

        }

    }
}
