<div class="card my-4 shadow-lg bg-white rounded border-0">
  <h5 class="card-header">Pencarian</h5>
  <div class="card-body">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="pencarian...">
      <span class="input-group-btn">
        <button class="btn btn-secondary" type="button">Cari</button>
      </span>
    </div>
  </div>
</div>

<div class="card my-4 shadow-lg mb-5 bg-white rounded border-0 d-none d-lg-block">
  <h5 class="card-header">Kategori</h5>
  @foreach($kategoris as $data)
  <a href="/kategori/{{$data->id}}" class="list-group-item border-0">{{$data->nama_kategori}}</a>
  @endforeach
</div>



<div class="card my-4 shadow-lg bg-white rounded border-0 d-block d-lg-none">
  <h5 class="card-header">Kategori</h5>

  <div class="card-body">
    <div class="input-group">
      <div class="dropdown btn-block">
        <button class="btn btn-secondary dropdown-toggle btn-block" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Kategori
        </button>
        <div class="dropdown-menu btn-block" aria-labelledby="dropdownMenuButton">
          @foreach($kategoris as $data)
          <a class="dropdown-item" href="#">{{$data->nama_kategori}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>