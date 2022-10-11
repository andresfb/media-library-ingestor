<?php

namespace App\Services\ContentGenerators;

use Illuminate\Database\Eloquent\Model;

class BaseContentService
{
    protected array $records;

    protected string $tag = '';

    protected string $class = '';

    protected int $totalRecords = 0;

    protected int $maxContentReuse;

    protected Model $model;

    protected ?Model $current = null;

    public function __construct()
    {
        $this->model = new $this->class();
        $pieces = explode('\\', $this->class);
        $this->tag = array_pop($pieces);
        $this->maxContentReuse = (int) config('posts.max_content_reuse');
    }

    /**
     * loadRecords Method.
     *
     * @param int $total
     * @return void
     */
    public function loadRecords(int $total): void
    {
        $this->records = $this->model->where('used', '<=', $this->maxContentReuse)
            ->inRandomOrder()
            ->limit($total)
            ->get()
            ->all();
    }

    /**
     * getTotal Method.
     *
     * @return int
     */
    public function getTotal(): int
    {
        if (!empty($this->totalRecords)) {
            return $this->totalRecords;
        }

        $this->totalRecords = $this->model
            ->where('used', '<=', $this->maxContentReuse)
            ->cache(604800)
            ->count();

        return $this->totalRecords;
    }

    /**
     * next Method.
     *
     * @return bool
     */
    public function next(): bool
    {
        $this->current = array_shift($this->records);
        if (empty($this->current)) {
            return false;
        }

        return true;
    }

    /**
     * getTag Method.
     *
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * markUsed Method.
     *
     * @return void
     */
    public function markUsed(): void
    {
        $this->current->increment('used');
    }
}