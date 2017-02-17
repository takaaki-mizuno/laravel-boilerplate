<div class="text-center">
    <ul class="pagination">
        @if( isset($firstPageLink) )
            <li><a href="{!! $firstPageLink!!}"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>
        @else
            <li class="disabled"><a><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>
        @endif
        @foreach( $pages as $page)
            @if( $page['current'] )
                <li class="active"><a>{{ $page['number'] }}</a></li>
            @else
                <li><a href="{!! $page['link'] !!}">{{ $page['number'] }}</a></li>
            @endif
        @endforeach
        @if( isset($lastPageLink) )
            <li><a href="{!! $lastPageLink!!}"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
        @else
            <li class="disabled"><a><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
        @endif
    </ul>
</div>