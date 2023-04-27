<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Rancoud\Model;

use DateTime;

/**
 * Class Field.
 */
class Field
{
    protected string $type;

    /** @var mixed */
    protected $default = false;

    protected array $rules;

    protected bool $notNull = false;

    protected bool $isKey = false;
    protected bool $isPKey = false;
    protected bool $isFKey = false;

    protected array $enumValues = [];

    /** @var mixed */
    protected $min;

    /** @var mixed */
    protected $max;

    protected array $range = [null, null];

    protected string $patternTime = '[0-2][0-9]:[0-5][0-9]:[0-5][0-9]';
    protected string $patternDate = '[1-9][0-9]{3}-[0-1][0-9]-[0-3][0-9]';

    /**
     * Field constructor.
     *
     * @param string $type
     * @param array  $rules
     * @param mixed  $default
     *
     * @throws FieldException
     */
    public function __construct(string $type, array $rules = [], $default = false)
    {
        $this->setType($type);
        $this->setRules($rules);
        $this->setDefault($default);
    }

    /**
     * @return bool
     */
    public function isPrimaryKey(): bool
    {
        return $this->isPKey;
    }

    /**
     * @return bool
     */
    public function isForeignKey(): bool
    {
        return $this->isFKey;
    }

    /**
     * @return bool
     */
    public function isNotNull(): bool
    {
        return $this->notNull;
    }

    /**
     * @param string $type
     *
     * @throws FieldException
     */
    protected function setType(string $type): void
    {
        $props = ['int', 'float', 'char', 'varchar', 'text', 'date', 'datetime', 'time', 'timestamp', 'year'];

        if (\mb_strpos($type, 'enum:') === 0) {
            $this->treatEnum($type);

            return;
        }

        if (!\in_array($type, $props, true)) {
            throw new FieldException('Incorrect Type. Valid type: ' . \implode(', ', $props) . ', enum:v1,v2');
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     */
    protected function treatEnum(string $type): void
    {
        $this->type = 'enum';

        $values = \mb_substr($type, 5);
        $this->enumValues = \explode(',', $values);
    }

    /**
     * @param array $rules
     *
     * @throws FieldException
     */
    protected function setRules(array $rules = []): void
    {
        $props = ['pk', 'fk', 'unsigned', 'email', 'not_null', 'max', 'min', 'range'];

        foreach ($rules as $rule) {
            if ($rule instanceof CustomRule) {
                continue;
            }

            if (\mb_strpos($rule, 'min:') === 0) {
                $this->extractMinRule($rule);
                continue;
            }

            if (\mb_strpos($rule, 'max:') === 0) {
                $this->extractMaxRule($rule);
                continue;
            }

            if (\mb_strpos($rule, 'range:') === 0) {
                $this->extractRangeRule($rule);
                continue;
            }

            if (!\in_array($rule, $props, true)) {
                $messageBonus = ', max:int, min:int, range:int,int';
                throw new FieldException('Incorrect Rule. Valid rule: ' . \implode(', ', $props) . $messageBonus);
            }

            if ($rule === 'not_null') {
                $this->notNull = true;
            }

            if ($rule === 'pk') {
                $this->isPKey = $this->isKey = true;
            }

            if ($rule === 'fk') {
                $this->isFKey = $this->isKey = true;
            }
        }

        $this->rules = $rules;
    }

    /**
     * @param string $rule
     */
    protected function extractMinRule(string $rule): void
    {
        if ($this->type === 'float') {
            $this->min = (float) \mb_substr($rule, 4);
        } else {
            $this->min = (int) \mb_substr($rule, 4);
        }
    }

    /**
     * @param string $rule
     */
    protected function extractMaxRule(string $rule): void
    {
        if ($this->type === 'float') {
            $this->max = (float) \mb_substr($rule, 4);
        } else {
            $this->max = (int) \mb_substr($rule, 4);
        }
    }

    /**
     * @param string $rule
     */
    protected function extractRangeRule(string $rule): void
    {
        $range = \mb_substr($rule, 6);
        $this->range = \explode(',', $range);
        if ($this->type === 'float') {
            $this->range[0] = (float) $this->range[0];
            $this->range[1] = (float) $this->range[1];
        } else {
            $this->range[0] = (int) $this->range[0];
            $this->range[1] = (int) $this->range[1];
        }
    }

    /**
     * @param mixed $default
     *
     * @throws FieldException
     */
    protected function setDefault($default): void
    {
        $this->default = $default;

        if ($default !== false) {
            $this->default = $this->formatValue($default);
        }
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return mixed|null
     */
    public function formatValue($value)
    {
        if ($value === false) {
            return $this->applyDefault(false);
        }

        if ($this->notNull === false && $value === null) {
            return null;
        }

        $value = $this->convertType($value);
        $value = $this->applyRules($value);

        return $this->applyDefault($value);
    }

    /**
     * @param $value
     *
     * @throws FieldException
     *
     * @return mixed
     */
    protected function convertType($value)
    {
        if ($value === null && $this->notNull) {
            throw new FieldException('Null not authorized');
        }

        $function = 'convertTo' . \ucfirst($this->type);

        return $this->$function($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return int|mixed
     */
    protected function convertToInt($value)
    {
        if (!\is_numeric($value)) {
            throw new FieldException('Invalid int value');
        }

        $value = (int) $value;

        if ($value < 0 && \in_array('unsigned', $this->rules, true)) {
            $value = 0;
        }

        if ($this->isKey && $value < 1) {
            throw new FieldException('Invalid key value');
        }

        return $this->applyMinMaxRangeInt($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return float
     */
    protected function convertToFloat($value): float
    {
        if (!\is_numeric($value)) {
            throw new FieldException('Invalid float value');
        }

        $value = (float) $value;

        if ($value < 0 && \in_array('unsigned', $this->rules, true)) {
            $value = 0;
        }

        return $this->applyMinMaxRangeFloat($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToChar($value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToVarchar($value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToText($value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToDate($value): string
    {
        $date = (string) $value;

        if ($this->isInvalidPattern('/' . $this->patternDate . '/', $date)) {
            throw new FieldException('Invalid date value');
        }

        $date = DateTime::createFromFormat('Y-m-d', $date);

        return $date->format('Y-m-d');
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToDatetime($value): string
    {
        $datetime = (string) $value;

        if ($this->isInvalidPattern('/' . $this->patternDate . ' ' . $this->patternTime . '/', $datetime)) {
            throw new FieldException('Invalid datetime value');
        }

        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);

        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToTime($value): string
    {
        $time = (string) $value;

        if ($this->isInvalidPattern('/' . $this->patternTime . '/', $time)) {
            throw new FieldException('Invalid time value');
        }

        $hour = (int) \mb_substr($time, 0, 2);
        if ($hour > 23) {
            throw new FieldException('Invalid time value');
        }

        return $time;
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return false|string
     */
    protected function convertToTimestamp($value)
    {
        if (\is_numeric($value)) {
            $value = (int) $value;
            if ($this->isInvalidBoundaryTimestamp($value)) {
                throw new FieldException('Invalid timestamp value');
            }

            return \date('Y-m-d H:i:s', $value);
        }

        $timestampString = (string) $value;
        $timestampInt = \strtotime($timestampString);

        if ($timestampInt === false) {
            throw new FieldException('Invalid timestamp value');
        }

        if ($this->isInvalidBoundaryTimestamp($timestampInt)) {
            throw new FieldException('Invalid timestamp value');
        }

        return \date('Y-m-d H:i:s', $timestampInt);
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return int|mixed
     */
    protected function convertToYear($value)
    {
        if (!\is_numeric($value)) {
            throw new FieldException('Invalid year value');
        }

        $year = (int) $value;

        if ($year < 0 && \in_array('unsigned', $this->rules, true)) {
            $year = 0;
        }

        $year = $this->applyMinMaxRangeInt($year);

        if ($year < 1901 || $year > 2155) {
            throw new FieldException('Invalid year value');
        }

        return $year;
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function convertToEnum($value): string
    {
        $value = (string) $value;

        if (!\in_array($value, $this->enumValues, true)) {
            throw new FieldException('Invalid enum value');
        }

        return $value;
    }

    /**
     * @param string $pattern
     * @param mixed  $value
     *
     * @return bool
     */
    protected function isInvalidPattern(string $pattern, $value): bool
    {
        $matches = [];
        \preg_match_all($pattern, $value, $matches);

        return \count($matches[0]) < 1;
    }

    /**
     * @param int $timestamp
     *
     * @return bool
     */
    protected function isInvalidBoundaryTimestamp(int $timestamp): bool
    {
        return $timestamp < 0 || $timestamp > 2147483647;
    }

    /**
     * @param mixed $value
     *
     * @return int|mixed
     */
    protected function applyMinMaxRangeInt($value)
    {
        if ($this->min !== null) {
            $value = (int) \max($this->min, $value);
        }

        if ($this->max !== null) {
            $value = (int) \min($this->max, $value);
        }

        if ($this->range[0] !== null && $this->range[1] !== null) {
            $value = \max($this->range[0], $value);
            $value = \min($this->range[1], $value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return float
     */
    protected function applyMinMaxRangeFloat($value): float
    {
        if ($this->min !== null) {
            $value = (float) \max($this->min, $value);
        }

        if ($this->max !== null) {
            $value = (float) \min($this->max, $value);
        }

        if ($this->range[0] !== null && $this->range[1] !== null) {
            $value = (float) \max($this->range[0], $value);
            $value = (float) \min($this->range[1], $value);
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function applyMinMaxRangeString(string $value): string
    {
        if (($this->min !== null) && \mb_strlen($value) < $this->min) {
            throw new FieldException('Invalid min length');
        }

        if ($this->max !== null) {
            $value = \mb_substr($value, 0, $this->max);
        }

        if ($this->range[0] !== null && $this->range[1] !== null) {
            if (\mb_strlen($value) < $this->range[0]) {
                throw new FieldException('Invalid min length');
            }

            $value = \mb_substr($value, 0, $this->range[1]);
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function applyRules($value)
    {
        foreach ($this->rules as $rule) {
            if ($rule instanceof CustomRule) {
                $value = $rule->applyRule($value);
                continue;
            }

            $function = 'applyRule' . \ucfirst($rule);

            if (\method_exists($this, $function)) {
                $value = $this->$function($value);
            }
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @throws FieldException
     *
     * @return string
     */
    protected function applyRuleEmail(string $value): string
    {
        $pos = \mb_strpos($value, '@');
        $length = \mb_strlen($value);
        if ($pos === false) {
            throw new FieldException('Invalid email value');
        }

        if ($pos === 0 || $pos === $length - 1) {
            throw new FieldException('Invalid email value');
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @throws FieldException
     *
     * @return mixed|null
     */
    protected function applyDefault($value)
    {
        if ($this->notNull && $value === false) {
            if ($this->default === false) {
                throw new FieldException('Invalid default value');
            }

            return $this->default;
        }

        if (!$this->notNull && $value === false) {
            if ($this->default !== false) {
                return $this->default;
            }

            return null;
        }

        return $value;
    }
}
