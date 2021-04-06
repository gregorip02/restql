<?php

namespace Testing\Feature\Clausules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Testing\TestCase;

final class SortClausuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Sort resources by id desc.
     */
    public function sortResourcesByIdDesc(): void
    {
        $data = $this->json('get', 'restql', [
            'articles' => [
                'sort' => ['id', 'desc']
            ]
        ])->json()['data']['articles'];

        $dataDesc = array_reverse(Arr::sort($data, 'id'));

        $this->assertEquals(json_encode($data), json_encode($dataDesc));
    }

    /**
     * @test Sort resources by id asc.
     */
    public function sortResourcesByIdAsc(): void
    {
        $data = $this->json('get', 'restql', [
            'articles' => [
                'sort' => ['id', 'asc']
            ]
        ])->json()['data']['articles'];

        $dataAsc = Arr::sort($data, 'id');

        $this->assertEquals(json_encode($data), json_encode($dataAsc));
    }

    /**
     * @test Sort resources by title desc using explicit method.
     */
    public function sortResourcesByTitleDescUsingExplicitMethod(): void
    {
        $data = $this->json('get', 'restql', [
            'articles' => [
                'sort' => [
                    'column' => 'title',
                    'direction' => 'desc'
                ]
            ]
        ])->json()['data']['articles'];

        $dataAsc = array_reverse(Arr::sort($data, 'title'));

        $this->assertEquals(json_encode($data), json_encode($dataAsc));
    }

    /**
     * @test Sort resources by title asc using explicit method.
     */
    public function sortResourcesByTitleAscUsingExplicitMethod(): void
    {
        $data = $this->json('get', 'restql', [
            'articles' => [
                'sort' => [
                    'column' => 'title'
                ]
            ]
        ])->json()['data']['articles'];

        $dataAsc = Arr::sort($data, 'title');

        $this->assertEquals(json_encode($data), json_encode($dataAsc));
    }
}
