<?php

namespace Drupal\address_extend_example\Plugin\Validation\Constraint;

use CommerceGuys\Addressing\AddressFormat\FieldOverride;
use Drupal\address_extend_example\AddressField;
use Drupal\address_extend_example\FieldOverrides;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @codeCoverageIgnore
 */
class AddressFormatConstraintBase extends Constraint
{
    /**
     * Used fields.
     *
     * @deprecated Use $fieldOverrides instead.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Field overrides.
     *
     * @var FieldOverrides
     */
    public $fieldOverrides;

    /**
     * @var string
     */
    public $blankMessage = 'This value should be blank';

    /**
     * @var string
     */
    public $notBlankMessage = 'This value should not be blank';

    /**
     * @var string
     */
    public $invalidMessage = 'This value is invalid.';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        // Convert used fields into field overrides.
        if (!empty($this->fields)) {
            $unusedFields = array_diff(AddressField::getAll(), $this->fields);
            $definition = [];
            foreach ($unusedFields as $field) {
                $definition[$field] = FieldOverride::HIDDEN;
            }
            $this->fieldOverrides = new FieldOverrides($definition);
        }

        // Ensure there's always a FieldOverrides object.
        $this->fieldOverrides = $this->fieldOverrides ?: new FieldOverrides([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
