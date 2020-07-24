<?php

namespace ArtARTs36\ModifierRequestBuilder;

use ArtARTs36\ModifierRequestBuilder\Contracts\ExternalConfig;
use ArtARTs36\ModifierRequestBuilder\Exceptions\OperatorNotAvailable;
use ArtARTs36\ModifierRequestBuilder\Support\Compilers;
use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ArtARTs36\ModifierRequestBuilder\Contracts\Client;
use ArtARTs36\ModifierRequestBuilder\Contracts\UrlStrategy;
use Illuminate\Support\Str;

/**
 * Class Builder
 * @package ArtARTs36\ModifierRequestBuilder
 */
class Builder
{
    use Compilers;

    public const SORT_DIRECTION_ASC = 'asc';
    public const SORT_DIRECTION_DESC = 'desc';

    private const MODIFIER_CONDITIONS = [
        '=' => Condition::CONDITION_EQUAL,
        '!=' => Condition::CONDITION_NOT_EQUAL,
        '<' => Condition::CONDITION_LESS,
        '<=' => Condition::CONDITION_LESS_EQUAL,
        '>' => Condition::CONDITION_GREATER,
        '>=' => Condition::CONDITION_GREATER_EQUAL,
    ];

    /** @var array */
    protected $request;

    /** @var Client */
    protected $client;

    /** @var UrlStrategy */
    protected $urlStrategy;

    /** @var ExternalConfig */
    protected $externalConfig;

    public function __construct(Client $client, UrlStrategy $urlStrategy, ExternalConfig $externalConfig)
    {
        $this->request = [
            '_count' => [],
            '_select' => [],
            '_filter' => [],
            '_sort' => [],
            '_with' => [],
            '_or_where' => [],
            '_limit' => null, // @todo в модифаере нет
            '_offset' => null, // @todo в модифаере нет
            '_distinct' => null, // @todo в модифаере нет
        ];

        $this->client = $client;
        $this->urlStrategy = $urlStrategy;
        $this->externalConfig = $externalConfig;
    }

    /**
     * @param string $operator
     * @return string
     */
    protected function getModifierCondition(string $operator): string
    {
        if (empty(static::MODIFIER_CONDITIONS[$operator])) {
            throw new OperatorNotAvailable($operator);
        }

        return static::MODIFIER_CONDITIONS[$operator];
    }

    /**
     * @param string $key
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function where(string $key, $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->request['_filter'][$key][$this->getModifierCondition($operator)] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return $this
     */
    public function whereIn(string $key, array $value): self
    {
        $this->request['_filter'][$key][Condition::CONDITION_IN] = $value;

        return $this;
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return collect($this->send(__FUNCTION__));
    }

    /**
     * @param int $count
     * @return $this
     */
    public function limit(int $count): self
    {
        $this->request['_limit'] = $count;

        return $this;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function take(int $count): self
    {
        return $this->limit($count);
    }

    /**
     * @param array|string $columns
     * @return $this
     */
    public function select($columns): self
    {
        $this->request['_select'] = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * @param array|string $columns
     * @return $this
     */
    public function addSelect($columns): self
    {
        $this->request['_select'] = array_merge(
            $this->request['_select'],
            is_array($columns) ? $columns : func_get_args()
        );

        return $this;
    }

    public function find(int $id)
    {
        $this
            ->where('id', $id)
            ->take(1);

        return $this->send(__FUNCTION__);
    }

    /**
     * @return array
     */
    public function toRequest(): array
    {
        $request = array_filter($this->request, function ($value) {
            return $value === 0 || !empty($value);
        });

        foreach (['_sort', '_select', '_with'] as $type) {
            if (!empty($this->request[$type])) {
                $method = Str::camel('compile'. $type);

                $request[$type] = $this->$method();
            }
        }

        return $request;
    }

    /**
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = self::SORT_DIRECTION_ASC): self
    {
        $this->request['_sort'][$column] = $direction;

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, static::SORT_DIRECTION_DESC);
    }

    /**
     * @param string $column
     * @return $this
     */
    public function latest(string $column = Model::CREATED_AT): self
    {
        return $this->orderByDesc($column);
    }

    /**
     * @param string $column
     * @return $this
     */
    public function oldest(string $column = Model::CREATED_AT): self
    {
        return $this->orderBy($column);
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this|Builder
     */
    public function when($value, callable $callback, callable $default = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }

    /**
     * @param array|string $relations
     * @return $this
     */
    public function with($relations): self
    {
        $this->request['_with'] = array_merge(
            $this->request['_with'] ?? [],
            is_string($relations) ? func_get_args() : $relations
        );

        return $this;
    }

    /**
     * @param mixed $relations
     * @return $this
     */
    public function withCount($relations): self
    {
        array_map(function (string $relation) {
            $this->request['_count'][] = $relation;
        }, is_array($relations) ? $relations : func_get_args());

        return $this;
    }

    /**
     * @return array
     */
    public function getEagerLoads(): array
    {
        return $this->request['_with'];
    }

    /**
     * @param int $value
     * @return $this
     */
    public function skip(int $value): self
    {
        return $this->offset($value);
    }

    /**
     * @param int $value
     * @return $this
     */
    public function offset(int $value): self
    {
        $this->request['_offset'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function distinct(): self
    {
        $this->request['_distinct'] = 1;

        return $this;
    }

    /**
     * @return $this
     */
    public function notDistinct(): self
    {
        $this->request['_distinct'] = 0;

        return $this;
    }

    /**
     * @param string $action
     * @return array
     */
    protected function send(string $action): array
    {
        return $this->client->json(
            $this->urlStrategy->method($action),
            $this->urlStrategy->url($action),
            $this->toRequest()
        );
    }
}
