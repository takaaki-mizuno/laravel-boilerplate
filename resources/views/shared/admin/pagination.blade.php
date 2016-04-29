<ul class="pagination pagination-sm no-margin pull-right">
    @if( $offset - $limit >= 0 )
    <li><a href="{!! $baseUrl !!}?offset={{ $offset-$limit }}&limit={{ $limit }}{!! isset($query) ? $query : "" !!}">&laquo;</a></li>
    @else
    <li><a>&laquo;</a></li>
    @endif
    @if( $offset + $limit <= $count )
    <li><a href="{!! $baseUrl !!}?offset={{ $offset+$limit }}&limit={{ $limit }}{!! isset($query) ? $query : "" !!}">&raquo;</a></li>
    @else
    <li><a>&raquo;</a></li>
    @endif
</ul>
