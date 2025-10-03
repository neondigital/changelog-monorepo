<div class="p-6">
  @foreach($releases as $release)
    <div class="border border-gray-300">
      <header class="border-b border-gray-300">
        {{ $release->date->format('d/m/Y') }}
      </header>
      <h1>{{ $release->title }}</h1>
      <ul>
        @foreach($release->changes as $change)
          <li>
            <strong

            >{{ $change->type }}</strong>: {{ $change->title }}
          </li>
        @endforeach
      </ul>

      {!! $release->body !!}
    </div>
  @endforeach
</div>