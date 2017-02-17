<div class="t-pagination">
    @if( isset($firstPageLink) )
        <a href="{!! $firstPageLink!!}"><i class="fa fa-angle-left"></i></a>
    @else
        <a class="disabled" style="cursor: pointer;"><i class="fa fa-angle-left"></i></a>
    @endif

    <div class="t-pagination-number">
        @lang('admin.pages.common.label.page') {{ $currentPage  . " / " . $lastPage }}
        <i class="fa fa-angle-down"></i>

        <div class="t-pagination-dropdown">
            <ul>
                @foreach( $pages as $page)
                    @if( $page['current'] )
                        <li>@lang('admin.pages.common.label.page') {{ $page['number'] }} <i style='font-size: 10px; margin-left: 10px; color: #337ab7;' class='fa fa-check'></i></li>
                    @else
                        <li><a href="{!! $page['link'] !!}">Page {{ $page['number'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    @if( isset($lastPageLink) )
        <a href="{!! $lastPageLink!!}"><i class="fa fa-angle-right"></i></a>
    @else
        <a class="disabled" style="cursor: pointer;"><i class="fa fa-angle-right"></i></a>
    @endif
</div>
