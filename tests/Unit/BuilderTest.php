<?php

namespace ArtARTs36\ModifierRequestBuilder\Tests\Unit;

use ArtARTs36\ModifierRequestBuilder\Builder;
use ArtARTs36\ModifierRequestBuilder\Exceptions\OperatorNotAvailable;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\CallReflectionMethod;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\MakeClient;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\MakeExternalConfig;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\MakeUrlStrategy;
use ArtARTs36\ModifierRequestBuilder\Tests\Traits\SetReflectionProperty;
use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package ArtARTs36\ModifierRequestBuilder\Tests\Unit
 * @covers \ArtARTs36\ModifierRequestBuilder\Builder
 */
class BuilderTest extends TestCase
{
    use MakeExternalConfig;
    use SetReflectionProperty;
    use MakeClient;
    use MakeUrlStrategy;
    use CallReflectionMethod;

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::where
     */
    public function testWhere(): void
    {
        $builder = $this->make();

        //

        $key = 'name';
        $value = 'artem';

        //

        $builder->where($key, $value);

        //

        $expected = [
            '_filter' => [
                'name' => [
                    'equal' => 'artem',
                ],
            ]
        ];

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder->where($key, '=', $value);

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::limit
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::take
     */
    public function testLimitAndTake(): void
    {
        $builder = $this->make();

        $count = 5;

        $expected = [
            '_limit' => $count,
        ];

        //

        $builder->limit($count);

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder->take($count);

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::orderBy
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::orderByDesc
     */
    public function testOrderBy(): void
    {
        $builder = $this->make();

        $column = 'name';

        //

        $builder->orderBy($column);

        $expected = [
            '_sort' => 'name',
        ];

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder->orderBy($column, 'desc');

        $expected = [
            '_sort' => '-name',
        ];

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder->orderByDesc($column);

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $twoColumn = 'patronymic';
        $threeColumn = 'last_name';

        $builder
            ->orderBy($column)
            ->orderBy($twoColumn)
            ->orderByDesc($threeColumn);

        $expected = [
            "_sort" => "name,patronymic,-last_name"
        ];

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::latest
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::oldest
     */
    public function testLatestAndOldest(): void
    {
        $builder = $this->make();

        //

        $expected = [
            '_sort' => '-created_at',
        ];

        $builder->latest();

        //

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        //

        $expected = [
            '_sort' => 'created_at',
        ];

        $builder->oldest();

        //

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::select
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::addSelect
     */
    public function testSelect(): void
    {
        $builder = $this->make();

        $builder->select('name', 'family');

        $expected = [
            '_select' => 'name,family',
        ];

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder
            ->select('name')
            ->addSelect('family');

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::getEagerLoads
     */
    public function testWith(): void
    {
        $builder = $this->make();

        $builder->with('posts', 'comments');

        $expected = [
            '_with' => 'posts,comments',
        ];

        self::assertEquals($expected, $builder->toRequest());
        self::assertEquals(['posts', 'comments'], $builder->getEagerLoads());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::when
     */
    public function testWhen(): void
    {
        $builder = $this->make();

        $builder->when(true, function (Builder $builder) {
            $builder->with('posts');
        });

        $builder->when(false, function (Builder $builder) {
            $builder->with('comments');
        });

        //

        $expected = [
            '_with' => 'posts',
        ];

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::withCount
     */
    public function testWithCount(): void
    {
        $builder = $this->make();

        $relations = ['posts', 'comments'];

        $builder->withCount($relations);

        $expected = [
            '_count' => $relations,
        ];

        //

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::find
     */
    public function testFind(): void
    {
        $id = rand(1, 99999);

        $builder = $this->make();

        //

        $expected = [
            '_filter' => [
                'id' => [
                    'equal' => $id,
                ],
            ],
            '_limit' => 1,
        ];

        //

        $builder->find($id);

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::offset
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::skip
     */
    public function testOffset(): void
    {
        $builder = $this->make();

        $offset = 5;

        $builder->offset($offset);

        $expected = [
            '_offset' => $offset,
        ];

        //

        self::assertEquals($expected, $builder->toRequest());

        //

        $builder = $this->make();

        $builder->skip($offset);

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::distinct
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::notDistinct
     */
    public function testDistinct(): void
    {
        $builder = $this->make();

        $expected = [
            '_distinct' => 1,
        ];

        $builder->distinct();

        //

        self::assertEquals($expected, $builder->toRequest());

        //

        $expected['_distinct'] = 0;

        $builder->notDistinct();

        self::assertEquals($expected, $builder->toRequest());
    }

    /**
     * @covers \ArtARTs36\ModifierRequestBuilder\Builder::getModifierCondition
     */
    public function testGetModifierCondition(): void
    {
        $builder = $this->make();

        $response = $this->callMethodViaReflection($builder, 'getModifierCondition', '=');

        self::assertEquals(Condition::CONDITION_EQUAL, $response);

        self::expectException(OperatorNotAvailable::class);

        $this->callMethodViaReflection($builder, 'getModifierCondition', rand(1, 99));
    }

    /**
     * @return Builder
     */
    protected function make(): Builder
    {
        return new Builder(
            $this->makeClient(),
            $this->makeUrlStrategy(),
            $this->makeExternalConfig()
        );
    }
}
