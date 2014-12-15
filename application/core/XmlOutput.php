<?php


/**
 * XmlOutput
 *
 * Xml output context builder class
 */

class XmlOutput
{


    /**
     * $_xmlDOM
     *
     * Root DOMDocument object
     */

    private static $_xmlDOM = null;


    /**
     * getContent
     *
     * Generate XML string
     *
     * @param  mixed  $data    Input data
     * @param  array  $schema  XSD-schema exposed as array
     * @param  array  $docType Doctype and XML namespace
     * @return string          Generated XML string
     */

    public static function getContent($data, array $schema, array $docType)
    {

        if ($docType === null) {
            self::$_xmlDOM = new DOMDocument("1.0", "utf-8");
        } else {

            $imp = new DOMImplementation();
            $dtd = $imp->createDocumentType($docType['name'], '', $docType['id']);
            self::$_xmlDOM = $imp->createDocument("", "", $dtd);
            self::$_xmlDOM->encoding = 'utf-8';

        }

        self::$_xmlDOM->formatOutput = true;
        self::$_xmlDOM->substituteEntities = true;

        $mainAttributes = array();
        if (array_key_exists('attributes', $schema)) {
            $mainAttributes = $schema['attributes'];
        }
        if (sizeof($data) > 1) {
            $data = array('response' => $data);
        }

        self::_createXmlChildren(
            $data,
            self::$_xmlDOM,
            $mainAttributes,
            array($schema)
        );
        return self::$_xmlDOM->saveXML();

    }


    /**
     * _createXmlChildren
     *
     * Create XML children with schema
     *
     * @param  mixed       $data           Input data pointer
     * @param  DOMDocument $parentNode     Parent node pointer
     * @param  array       $parentSchema   Parent node schema segment pointer
     * @param  mixed       $schemaElements Current schema segment
     * @return null
     */

    private static function _createXmlChildren( & $data, DOMDocument & $parentNode, array & $parentSchema, $schemaElements = null)
    {

        if (is_array($data)) {

            $dataLength = sizeof($data);
            foreach ($data as $key => $value) {

                $useSchemaElement = false;
                $isNumericItems   = true;

                $currentSchema = array(
                    'name'       => 'item',
                    'attributes' => array(),
                    'attrvalues' => array(),
                    'repeat'     => false
                );

                if (!is_numeric($key)) {
                    $currentSchema['name'] = $key;
                    $isNumericItems = false;
                }
                if ($schemaElements !== null) {
                    foreach ($schemaElements as $schemaElement) {
                        if ($isNumericItems || $currentSchema['name'] == $schemaElement['name']) {
                            $currentSchema = array_merge($currentSchema, $schemaElement);
                            $useSchemaElement = true;
                            break;
                        }
                    }
                }

                // value is array, need recursive execute
                if (is_array($value)) {

                    $childrenSchema = null;
                    if (array_key_exists('children', $currentSchema)) {
                        $childrenSchema = $currentSchema['children'];
                    }
                    if ($currentSchema['repeat'] !== true) {
                        $value = array($value);
                    }

                    foreach ($value as $vv) {

                        $element = self::$_xmlDOM->createElement($currentSchema['name']);
                        self::_createXmlChildren($vv, $element, $currentSchema, $childrenSchema);


                        /**
                         * set attributes of element,
                         * WARNING! SET ONLY AFTER MAKE CHILDREN,
                         * BECAUSE CHILDREN MAYBE SET THIS ATTRIBUTES!
                         *
                         * append element into parent node
                         */

                        self::_setElementAttributes($element, $currentSchema);
                        $parentNode->appendChild($element);

                    }

                // value is not array
                } else {

                    if (is_object($value) or is_resource($value)) {
                        throw new SystemErrorException(array(
                            'title'       => 'Schema XML error',
                            'description' => 'Value of schema element is not string'
                        ));
                    }

                    $isParentAttributeSet = false;
                    if (array_key_exists('attributes', $parentSchema)) {
                        foreach ($parentSchema['attributes'] as $k => $attribute) {
                            if ($currentSchema['name'] == $attribute['name']) {
                                $parentSchema['attrvalues'][$attribute['name']] = $value;
                                $isParentAttributeSet = true;
                            }
                        }
                    }

                    $acceptValue = (!is_bool($value) && $value !== '' && $value !== null);
                    if (!$isParentAttributeSet && $acceptValue) {
                        if ($dataLength == 1 && $parentNode->childNodes->length == 0) {
                            $parentNode->appendChild(self::$_xmlDOM->createTextNode($value));
                        } else {
                            $element = self::$_xmlDOM->createElement($currentSchema['name']);
                            self::_setElementAttributes($element, $currentSchema);
                            $element->appendChild(self::$_xmlDOM->createTextNode($value));
                            $parentNode->appendChild($element);
                        }
                    }

                }

                $dataLength--;

            }

        }

    }


    /**
     * _setElementAttributes
     *
     * Set attributes of element with schema
     *
     * @param  DOMDocument $element Current node pointer
     * @param  array       $data    Pointer of node data
     * @return null
     */

    private static function _setElementAttributes(DOMDocument & $element, array & $data)
    {

        if (array_key_exists('attributes', $data)) {
            foreach ($data['attributes'] as $attribute) {

                $name  = $attribute['name'];
                $value = $attribute['value'];

                // required value from data
                if ($value === true) {
                    $element->setAttribute($name, $data['attrvalues'][$name]);
                // custom value from data
                } else if ($value === false) {
                    if (array_key_exists($name, $data['attrvalues'])) {
                        if ($data['attrvalues'][$name] != '') {
                            $element->setAttribute($name, $data['attrvalues'][$name]);
                        }
                    }
                // custom value from schema
                } else {
                    $element->setAttribute($attribute['name'], $attribute['value']);
                }

            }
        }

    }
}
