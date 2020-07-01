<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\CustomRule;
use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class RuleCustomTest
 */
class RuleCustomTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected int $countInputs = 7;

    /**
     * @throws FieldException
     */
    public function testFieldCharWithCustomRule(): void
    {
        $rule = new Field('char', [new MyRule()]);
        $expected = [null, null, FieldException::class, '-1', '10', '50.5', ''];
        $message = [null, null, 'invalid azerty value', null, null, null, null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }
}

/**
 * Class MyRule
 */
class MyRule extends CustomRule
{

    /**
     * @param $value
     *
     * @return mixed
     * @throws FieldException
     */
    public function applyRule($value)
    {
        if ($value === 'azerty') {
            throw new FieldException('invalid azerty value');
        }

        return $value;
    }
}
