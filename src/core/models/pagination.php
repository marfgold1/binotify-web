<?php
declare(strict_types=1);
namespace MusicApp\Core\Models;

class Pagination {
    public readonly int $page;
    public readonly int $offset;
    public readonly int $nextOffset;
    public readonly int $lastPage;
    public readonly int $total;
    public readonly int $limit;
    public readonly int $count;
    public readonly array $models;

    public function __construct(array $opts, array $models=[]) {
        $this->limit = $opts['limit'];
        $this->total = $opts['total'] ?? 0;
        $this->lastPage = intval(ceil($this->total / $this->limit));
        $this->page = intval(max(1, min($this->lastPage, $opts['page'])));
        $this->offset = ($this->page - 1) * $this->limit;
        $this->nextOffset = $this->offset + $this->limit;
        $this->count = count($models);
        $this->models = $models;
    }

    protected function str($page=null, $limit=null) : string {
        return http_build_query([
            'page' => $page ?? $this->page,
            'limit' => $limit ?? $this->limit,
        ]);
    }

    public function __toString() : string{
        return $this->str();
    }

    public function to(int $pageNo) : string {
        return $this->str($pageNo);
    }

    public function isLast() : bool {
        return $this->page == $this->lastPage;
    }

    public function next() : string {
        return $this->str($this->page + 1);
    }

    public function prev() : string {
        return http_build_query([
            "page" => $this->page - 1,
            "limit" => $this->limit
        ]);
    }
}
?>
