@extends('./layout/base')

@section('main')

<div class="row" id="app">
<div class="col-sm-12">
    <div class="col-sm-12">

        @if(session()->get('success'))
          <div class="alert alert-success">
            {{ session()->get('success') }}
          </div>
        @endif
      </div>
    <h1 class="display-3">Contacts</h1>

    
  <table class="table table-striped">
    <thead>
        <div>
            <a style="margin: 19px;" href="{{ route('contacts.create')}}" class="btn btn-primary">New contact</a>
        </div>
        <tr>
          <td>ID</td>
          <td>Name</td>
          <td>Email</td>
          <td>Job Title</td>
          <td>City</td>
          <td>Country</td>
          <td colspan = 2>Actions</td>
        </tr>
    </thead>
    <tbody>
        @foreach($contacts as $contact)
        <tr>
            <td>{{$contact->id}}</td>
            <td>{{$contact->first_name}} {{$contact->last_name}}</td>
            <td>{{$contact->email}}</td>
            <td>{{$contact->job_title}}</td>
            <td>{{$contact->city}}</td>
            <td>{{$contact->country}}</td>
            <td>
                <a href="{{ route('contacts.edit',$contact->id)}}" class="btn btn-primary">Edit</a>
            </td>
            <td>
                <form action="{{ route('contacts.destroy', $contact->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>

  <form method="post" id='testeForm'>
          @csrf
          <div class="form-group">
              <label for="sites">Sites:</label>
              <input type="text" class="form-control" name="sites" />
          </div>

         

          
          <div class="form-group">
            <label for="exampleFormControlSelect2">Escolha qual será a ferramenta de análise</label>
            <select multiple class="form-control" id="exampleFormControlSelect2">
              <option>Lighthouse</option>
              <option>WAVE</option>
            </select>
          </div>

          <div class="form-check">
          
            <input type="checkbox" class="form-check-input" name="last_name"/>
            <label class="form-check-label" for="last_name">Salvar os resultados para consultar depois?</label>
          </div>


          <button type="submit" class="btn btn-primary">Update</button>

  </form>

  <button class="btn btn-primary" data-id="audit-lighthouse" type="submit">Audit</button>
<div>
</div>
@endsection


<script>
// document.addEventListener("DOMContentLoaded", function () {
// });


const urlBase =  'http://localhost:8000/api/teste'

async function audit(sites)
{ 
 console.log('isso pode demorar um pouco')
  await fetch(urlBase)
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(function(err){
      console.log(err)
    });
}





document.addEventListener('submit', function (event) {
  event.preventDefault();

  const inputs = event.target.elements
  teste(inputs)

});


async function teste(request)
{

  const sites = request['sites'].value

  const rawReponse = await fetch(urlBase, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    method: 'POST',
    body: JSON.stringify({sites: [sites]})
  })
  const content = await rawReponse.json();

  if(content.message){
    console.log('erro')
  }
  console.log(content)
}

</script>

