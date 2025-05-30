<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Rancoud\Model;

class Field
{
    protected string $type;

    protected mixed $default = false;

    protected array $rules;

    protected bool $notNull = false;

    protected bool $isKey = false;

    protected bool $isPKey = false;

    protected bool $isFKey = false;

    protected array $enumValues = [];

    protected float|int|null $min = null;

    protected float|int|null $max = null;

    protected array $range = [null, null];

    protected string $patternTime = '[0-2][0-9]:[0-5][0-9]:[0-5][0-9]';

    protected string $patternDate = '[1-9][0-9]{3}-[0-1][0-9]-[0-3][0-9]';

    /** @throws FieldException */
    public function __construct(string $type, array $rules = [], $default = false)
    {
        $this->setType($type);
        $this->setRules($rules);
        $this->setDefault($default);
    }

    public function isPrimaryKey(): bool
    {
        return $this->isPKey;
    }

    public function isForeignKey(): bool
    {
        return $this->isFKey;
    }

    public function isNotNull(): bool
    {
        return $this->notNull;
    }

    /** @throws FieldException */
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

    protected function treatEnum(string $type): void
    {
        $this->type = 'enum';

        $values = \mb_substr($type, 5);
        $this->enumValues = \explode(',', $values);
    }

    /** @throws FieldException */
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

    protected function extractMinRule(string $rule): void
    {
        if ($this->type === 'float') {
            $this->min = (float) \mb_substr($rule, 4);
        } else {
            $this->min = (int) \mb_substr($rule, 4);
        }
    }

    protected function extractMaxRule(string $rule): void
    {
        if ($this->type === 'float') {
            $this->max = (float) \mb_substr($rule, 4);
        } else {
            $this->max = (int) \mb_substr($rule, 4);
        }
    }

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

    /** @throws FieldException */
    protected function setDefault(mixed $default): void
    {
        $this->default = $default;

        if ($default !== false) {
            $this->default = $this->formatValue($default);
        }
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @throws FieldException
     *
     * @return mixed|null
     */
    public function formatValue(mixed $value): mixed
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

    /** @throws FieldException */
    protected function convertType(mixed $value): mixed
    {
        if ($value === null && $this->notNull) {
            throw new FieldException('Null not authorized');
        }

        $function = 'convertTo' . \ucfirst($this->type);

        return $this->{$function}($value);
    }

    /** @throws FieldException */
    protected function convertToInt(mixed $value): int
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

    /** @throws FieldException */
    protected function convertToFloat(mixed $value): float
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

    /** @throws FieldException */
    protected function convertToChar(mixed $value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /** @throws FieldException */
    protected function convertToVarchar(mixed $value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /** @throws FieldException */
    protected function convertToText(mixed $value): string
    {
        $value = (string) $value;

        return $this->applyMinMaxRangeString($value);
    }

    /** @throws FieldException */
    protected function convertToDate(mixed $value): string
    {
        $date = (string) $value;

        if ($this->isInvalidPattern('/' . $this->patternDate . '/', $date)) {
            throw new FieldException('Invalid date value');
        }

        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        return $date->format('Y-m-d');
    }

    /** @throws FieldException */
    protected function convertToDatetime(mixed $value): string
    {
        $datetime = (string) $value;

        if ($this->isInvalidPattern('/' . $this->patternDate . ' ' . $this->patternTime . '/', $datetime)) {
            throw new FieldException('Invalid datetime value');
        }

        $datetime = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $datetime);

        return $datetime->format('Y-m-d H:i:s');
    }

    /** @throws FieldException */
    protected function convertToTime(mixed $value): string
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

    /** @throws FieldException */
    protected function convertToTimestamp(mixed $value): string
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

    /** @throws FieldException */
    protected function convertToYear(mixed $value): int
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

    /** @throws FieldException */
    protected function convertToEnum(mixed $value): string
    {
        $value = (string) $value;

        if (!\in_array($value, $this->enumValues, true)) {
            throw new FieldException('Invalid enum value');
        }

        return $value;
    }

    protected function isInvalidPattern(string $pattern, mixed $value): bool
    {
        $matches = [];
        \preg_match_all($pattern, $value, $matches);

        return \count($matches[0]) < 1;
    }

    protected function isInvalidBoundaryTimestamp(int $timestamp): bool
    {
        return $timestamp < 0 || $timestamp > 2147483647;
    }

    protected function applyMinMaxRangeInt(mixed $value): int
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

    protected function applyMinMaxRangeFloat(mixed $value): float
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

    /** @throws FieldException */
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

    protected function applyRules(mixed $value): mixed
    {
        foreach ($this->rules as $rule) {
            if ($rule instanceof CustomRule) {
                $value = $rule->applyRule($value);

                continue;
            }

            $function = 'applyRule' . \ucfirst($rule);

            if (\method_exists($this, $function)) {
                $value = $this->{$function}($value);
            }
        }

        return $value;
    }

    /** @throws FieldException */
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
     * @throws FieldException
     *
     * @return mixed|null
     */
    protected function applyDefault(mixed $value): mixed
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
