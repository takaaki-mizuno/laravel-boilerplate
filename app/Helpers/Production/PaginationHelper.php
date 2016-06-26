<?php

namespace App\Helpers\Production;

use App\Helpers\PaginationHelperInterface;

class PaginationHelper implements PaginationHelperInterface
{
    public function normalize($offset, $limit, $maxLimit, $defaultLimit)
    {
        if ($limit <= 0 || $limit > $maxLimit) {
            $limit = $defaultLimit;
        }
        $page = intval($offset / $limit);
        $offset = $limit * $page;

        return [
            'limit'  => $limit,
            'offset' => $offset,
        ];
    }

    public function render($offset, $limit, $count, $path, $query, $paginationNumber = 5, $template = 'shared.pagination')
    {
        if (empty($query) || !is_array($query)) {
            $query = [];
        }
        $data = $this->normalize($offset, $limit, 100, 10);
        $offset = $data['offset'];
        $limit = $data['limit'];
        $page = intval($offset / $limit) + 1;
        $data = [];
        if ($page != 1) {
            $data['firstPageLink'] = $path;
            if (count($query) > 0) {
                $data['firstPageLink'] = $path.'?'.http_build_query($query);
            } else {
                $data['firstPageLink'] = $path;
            }
        }
        $lastPage = intval(($count - 1) / $limit) + 1;
        if ($page < $lastPage) {
            $data['lastPageLink'] = $path;
            $data['lastPageLink'] = $path.'?'.http_build_query(array_merge($query, ['offset' => ($lastPage - 1) * $limit, 'limit' => $limit]));
        }
        $minPage = $page - intval($paginationNumber / 2);
        if ($minPage < 1) {
            $minPage = 1;
        }
        $data['pages'] = [];
        for ($i = $minPage; $i < ($minPage + $paginationNumber); $i++) {
            if ($i > $lastPage) {
                break;
            }
            $data['pages'][] = [
                'number'  => $i,
                'link'    => $path.'?'.http_build_query(array_merge($query, ['offset' => ($i - 1) * $limit, 'limit' => $limit])),
                'current' => ($i == $page) ? true : false,
            ];
        }

        return view($template, $data);
    }
}
