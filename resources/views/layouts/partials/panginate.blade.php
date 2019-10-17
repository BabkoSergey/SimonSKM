@if(isset($pages) && $pages > 1)  

    <div class="pagination">
        <a href="{{ '?page=1'.($filterParamsStr ?? '') }}" class="pagination-link" data-hidden="{{ ($page == 1) ? 'true' : 'false' }}">{{ __('!_previous') }}</a>
        <ul class="pagination-list">
            @for ($i = 1; $i <= $pages; $i++)
                <li class="pagination-item">
                    <a href="{{ '?page='.$i.($filterParamsStr ?? '') }}" class="pagination-item-link {{ ($page == $i) ? ' active' : '' }}">{{ $i }}</a>
                </li>                
            @endfor            
        </ul>
        <a href="{{ '?page='.($page == $pages ? $page : $page+1).($filterParamsStr ?? '') }}" class="pagination-link" data-hidden="{{ ($page == $pages) ? 'true' : 'false' }}">{{ __('!_next') }}</a>
        <a href="{{ '?page='.$pages.($filterParamsStr ?? '') }}" class="pagination-link" data-hidden="{{ ($page == $pages) ? 'true' : 'false' }}">{{ __('!_last') }}</a>
    </div>
        
@endif    