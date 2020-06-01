<div class="card my-4 shadow-lg p-3 mb-5 rounded border-0">
  <div class="card-header bg-white">
    <h3>Chat</h3>
  </div>
  <div class="list-group overflow-auto">
    @foreach($queryuser as $data)
    <a href="/pesan/{{$data->id}}" class="list-group-item list-group-item-action border-0">
      <img src="{{ asset('photo_profile/'.$data->foto)}}" width="30" height="30" class="d-inline-block align-top mr-1" alt="">
      {{$data->name}}
    </a>
    @endforeach
  </div>
</div>