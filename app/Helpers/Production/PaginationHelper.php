<?php

namespace App\Helpers\Production;

use App\Helpers\PaginationHelperInterface;
use Illuminate\Support\Facades\Request;

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
            'limit' => $limit,
            'offset' => $offset,
        ];
    }

    public function data(
        $order,
        $direction,
        $offset,
        $limit,
        $count,
        $path,
        $query,
        $paginationNumber = 5
    ) {
        if (empty($query) || !is_array($query)) {
            $query = [];
        }
        $data = $this->normalize($offset, $limit, 100, 10);
        $offset = $data['offset'];
        $limit = $data['limit'];
        $page = intval($offset / $limit) + 1;
        $data = [];
        if ($page != 1) {
            $data['firstPageLink'] = $this->generateLink(1, $path, $query, $order, $direction);
        }
        $lastPage = intval(($count - 1) / $limit) + 1;

        if ($page < $lastPage) {
            $data['lastPageLink'] = $this->generateLink($lastPage, $path, $query, $order, $direction);
        }
        if( $count == $paginationNumber ) {
            $minPage = 1;
            $maxPage = $count;
        } else {
            $minPage = $page - intval($paginationNumber / 2);
            if ($minPage < 1) {
                $minPage = 1;
            }
            $maxPage = $minPage + $paginationNumber;
        }

        $data['pageListContainFirstPage'] = $minPage == 1 ? true : false;
        $data['pageListContainLastPage'] = false;

        $data['lastPage'] = $lastPage;
        $data['currentPage'] = $page;

        $data['pages'] = [];
        for ($i = $minPage; $i <= $maxPage; ++$i) {
            if ($i > $lastPage) {
                break;
            }
            $data[ 'pages' ][] = [
                'number'  => $i,
                'link'    => $this->generateLink( $i, $path, $query, $order, $direction ),
                'current' => ( $i == $page ) ? true : false,
            ];
            if ($i == $lastPage) {
                $data['pageListContainLastPage'] = true;
            }
        }

        $data['previousPageLink'] = $page <= 1 ? '' : $this->generateLink($page - 1, $path, $query, $order, $direction);
        $data['nextPageLink'] = $page >= $lastPage ? '' : $this->generateLink($page + 1, $path, $query, $order, $direction);

        return $data;
    }

    public function sort($orderField, $displayName)
    {
        $page       = Request::get('page', 1);
        $order      = Request::get('order', 'id');
        $direction  = ( Request::get('direction', 'desc') == 'desc' ) ? 'asc' : 'desc';
        if( $order == $orderField ) {
            $link = "<a href='?page=$page&order=$orderField&direction=$direction'>$displayName</a>";
        } else {
            $link = "<a href='?page=$page&order=$orderField&direction=asc'>$displayName</a>";
        }
        return $link;
    }

    public function render(
        $order,
        $direction,
        $offset,
        $limit,
        $count,
        $path,
        $query,
        $paginationNumber = 5,
        $template = 'shared.bottomPagination'
    ) {
        $data = $this->data($order, $direction, $offset, $limit, $count, $path, $query, $paginationNumber);

        return view($template, $data);
    }

    private function generateLink($page, $path, $query, $order, $direction)
    {
        return $path.'?'.http_build_query(array_merge($query, ['page' => $page, 'order' => $order, 'direction' => $direction]));
    }
}
