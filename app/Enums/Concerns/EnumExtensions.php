<?php

namespace App\Enums\Concerns;

trait EnumExtensions
{
    public static function find(self|string|int|null $key): ?static
    {
        if ($key instanceof self) {
            return $key;
        }

        if (is_null($key)) {
            return null;
        }

        return self::tryFrom((string) $key);
    }

    public static function findOrDefault(self|string|int|null $key): static
    {
        return self::find($key) ?? self::default();
    }

    public static function findByName(string $name, bool $fallbackToDefault = false): ?static
    {
        $sanitize = fn (string $value): string => str($value)
            ->trim()
            ->lower()
            ->replaceMatches('/[^a-z0-9 ]/i', '')
            ->replaceMatches('/\s+/', ' ')
            ->toString();

        $name = $sanitize($name);

        $found = collect(self::cases())->first(function (self $enum) use ($name, $sanitize): bool {
            if ($sanitize($enum->getName()) == $name) {
                return true;
            }

            // @phpstan-ignore-next-line - Generic trait: some enums may have aliases() method
            if (! method_exists($enum, method: 'aliases')) {
                return false;
            }

            $aliases = $enum->aliases() ?? [];

            return collect($aliases)->contains(
                fn ($alias) => $sanitize($alias) === $name || str_contains($sanitize($alias), $name),
            );
        });

        if ($found instanceof self) {
            return $found;
        }

        return $fallbackToDefault ? self::default() : null;
    }

    public static function default(): static
    {
        return self::cases()[0];
    }

    public static function getDefault(): static
    {
        return self::default();
    }

    public static function random(): static
    {
        /* @var array<int, static> $cases */
        $cases = self::cases();

        return collect($cases)->random();
    }

    public static function names(): array
    {
        return array_column(self::cases(), column_key: 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), column_key: 'value');
    }

    public static function count(): int
    {
        return count(self::cases());
    }

    public function is(self|string|int|null $enum): bool
    {
        if (is_null($enum)) {
            return false;
        }

        if ($enum instanceof self) {
            return $this === $enum;
        }

        return $this === self::tryFrom($enum);
    }

    public function isIn(array $enums, bool $strict = false): bool
    {
        $normalizedEnums = collect($enums)
            ->map(fn (self|string|int $enum): ?self => self::find($enum))
            ->filter()
            ->values()
            ->toArray();

        return in_array($this, $normalizedEnums, strict: $strict);
    }

    public function isNot(self|string|int $enum): bool
    {
        return ! $this->is($enum);
    }

    public function isNotIn(array $enums, bool $strict = false): bool
    {
        return ! $this->isIn($enums, $strict);
    }

    public function getKey(): string|int
    {
        return $this->value;
    }

    public function getName(): string
    {
        return str($this->name)->headline()
            ->lower()->ucfirst()
            ->toString();
    }

    public function getLabel(): string
    {
        return $this->getName();
    }
}
