<ul>
    @forelse ($tasklists as $tasklist)
        <li>Tehtävälista {{$tasklist->id}}, luotu {{$tasklist->created_at}}</li>
    @empty
        <p>Ei tehtävälistoja</p>
    @endforelse
</ul>