@extends($layout)

@section('page_title', $page['title'])
@section('topnav_title', $page['title'])
@section('topnav_subtitle', $page['subtitle'])

@section('content')

{{-- BREADCRUMB --}}
@if(!empty($breadcrumb))
<div class="breadcrumb">
  @foreach($breadcrumb as $item)
    @if(!$loop->last)
      <span>{{ $item['label'] }}</span>
      <span class="sep">{{ $item['separator'] ?? '/' }}</span>
    @else
      <span class="current">{{ $item['label'] }}</span>
    @endif
  @endforeach
</div>
@endif

{{-- STATS --}}
@if(!empty($stats))
<div class="grid-4" style="margin-bottom:20px;">
  @foreach($stats as $stat)
    <div class="stat-card">
      <div class="label">{{ $stat['label'] }}</div>
      <div class="value">{{ $stat['formatted'] ?? $stat['value'] }}</div>
      <div class="delta {{ $stat['class'] ?? '' }}">
        {{ $stat['meta'] ?? '' }}
      </div>
    </div>
  @endforeach
</div>
@endif

{{-- FILTER TABS --}}
@if(!empty($filters))
<div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
  
  <div style="display:flex;gap:6px;flex-wrap:wrap;">
    @foreach($filters as $filter)
      <a href="{{ $filter['url'] }}"
         class="{{ $filter['class'] }}"
         style="{{ $filter['style'] ?? '' }}">
        {{ $filter['label'] }}
        @if(isset($filter['count']))
          ({{ $filter['count'] }})
        @endif
      </a>
    @endforeach
  </div>

  @if(!empty($actions['header']))
    @foreach($actions['header'] as $action)
      <button class="{{ $action['class'] }}"
              onclick="{{ $action['onclick'] }}">
        {{ $action['label'] }}
      </button>
    @endforeach
  @endif

</div>
@endif

{{-- FILTER BAR --}}
@if(!empty($filtersForm))
<div class="card" style="padding:12px 16px;margin-bottom:12px;">
  <form method="{{ $filtersForm['method'] }}" action="{{ $filtersForm['action'] }}">
    
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      
      @foreach($filtersForm['fields'] as $field)

        @if($field['type'] === 'search')
          <div class="search-wrap" style="flex:1;min-width:200px;">
            <span class="si">{{ $field['icon'] ?? '' }}</span>
            <input class="form-input"
                   name="{{ $field['name'] }}"
                   placeholder="{{ $field['placeholder'] }}"
                   value="{{ $field['value'] }}">
          </div>

        @elseif($field['type'] === 'select')
          <select class="form-select" name="{{ $field['name'] }}">
            @foreach($field['options'] as $opt)
              <option value="{{ $opt['value'] }}"
                {{ $opt['selected'] ? 'selected' : '' }}>
                {{ $opt['label'] }}
              </option>
            @endforeach
          </select>

        @endif

      @endforeach

      @if(!empty($filtersForm['submit']))
        <button type="submit" class="{{ $filtersForm['submit']['class'] }}">
          {{ $filtersForm['submit']['label'] }}
        </button>
      @endif

    </div>

  </form>
</div>
@endif

{{-- TABLE --}}
@if(!empty($table))
<div class="card" style="padding:0;">
  
  <div class="table-wrap">
    <table>

      {{-- HEAD --}}
      <thead>
        <tr>
          @foreach($table['headers'] as $th)
            <th>
              @if($th['type'] === 'checkbox')
                <input type="checkbox" {{ $th['attributes'] ?? '' }}>
              @else
                {{ $th['label'] }}
              @endif
            </th>
          @endforeach
        </tr>
      </thead>

      {{-- BODY --}}
      <tbody>
        @forelse($table['rows'] as $row)

        <tr style="{{ $row['style'] ?? '' }}">
          
          @foreach($row['cells'] as $cell)

            <td style="{{ $cell['style'] ?? '' }}">

              @if($cell['type'] === 'html')
                {!! $cell['value'] !!}

              @elseif($cell['type'] === 'badge')
                <span class="{{ $cell['class'] }}">
                  {{ $cell['label'] }}
                </span>

              @elseif($cell['type'] === 'actions')
                <div style="display:flex;gap:6px;">
                  @foreach($cell['items'] as $action)

                    @if($action['type'] === 'link')
                      <a href="{{ $action['url'] }}" class="{{ $action['class'] }}">
                        {{ $action['label'] }}
                      </a>

                    @elseif($action['type'] === 'form')
                      <form method="{{ $action['method'] }}" action="{{ $action['url'] }}">
                        @csrf
                        @if(!empty($action['spoof']))
                          @method($action['spoof'])
                        @endif
                        <button type="submit" class="{{ $action['class'] }}">
                          {{ $action['label'] }}
                        </button>
                      </form>

                    @endif

                  @endforeach
                </div>

              @else
                {{ $cell['value'] }}
              @endif

            </td>

          @endforeach

        </tr>

        @empty
        <tr>
          <td colspan="{{ count($table['headers']) }}"
              style="text-align:center;padding:40px;color:var(--muted);">
            {{ $table['empty'] }}
          </td>
        </tr>
        @endforelse
      </tbody>

    </table>
  </div>

  {{-- PAGINATION --}}
  @if(!empty($pagination))
  <div class="pagination">

    <span class="info">{{ $pagination['info'] }}</span>

    <div class="page-btns">
      @foreach($pagination['pages'] as $page)
        <button class="{{ $page['class'] }}"
                onclick="{{ $page['onclick'] }}">
          {{ $page['label'] }}
        </button>
      @endforeach
    </div>

  </div>
  @endif

</div>
@endif

@endsection

{{-- MODALS --}}
@section('modals')
@if(!empty($modals))
  @foreach($modals as $modal)

    <div id="{{ $modal['id'] }}"
         class="modal-overlay"
         onclick="{{ $modal['overlay_click'] ?? '' }}">
      
      <div class="modal">

        <div class="modal-title">
          {{ $modal['title'] }}
          <button class="modal-close"
                  onclick="{{ $modal['close_action'] }}">✕</button>
        </div>

        <form method="{{ $modal['form']['method'] }}"
              action="{{ $modal['form']['action'] }}">

          @csrf

          @foreach($modal['form']['fields'] as $field)

            <div class="form-group">
              <label class="form-label">
                {{ $field['label'] }}
              </label>

              @if($field['type'] === 'select')
                <select class="form-select" name="{{ $field['name'] }}">
                  @foreach($field['options'] as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                  @endforeach
                </select>
              @else
                <input class="form-input"
                       type="{{ $field['type'] }}"
                       name="{{ $field['name'] }}"
                       placeholder="{{ $field['placeholder'] ?? '' }}">
              @endif

            </div>

          @endforeach

          <div class="modal-footer">
            @foreach($modal['actions'] as $action)
              <button type="{{ $action['type'] }}"
                      class="{{ $action['class'] }}"
                      onclick="{{ $action['onclick'] ?? '' }}">
                {{ $action['label'] }}
              </button>
            @endforeach
          </div>

        </form>

      </div>
    </div>

  @endforeach
@endif
@endsection
