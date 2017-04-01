<ul>
    @if (isset($tasks))
        @forelse ($tasks as $task)
            <li>{{$task}}</li>
        @empty
            <p>Ei tehtäviä</p>
        @endforelse
    @endif
</ul>