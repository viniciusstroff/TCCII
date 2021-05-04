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
    <h1 class="display-3">Audições</h1>

    <form method="post" id='testeForm'>
            @csrf

            <div class="form-row">
              <div class="col">
                  <label for="sites">Sites:</label>
                  <input type="text" class="form-control token-input" id="token-input"name="sites" />
                  
              </div>
              <div class="row">
                <button type="button" class="btn btn-primary " id="add-site" onclick="addItem()">ADD</button>
              </div>
            </div>
            
            <div class="list-sites">
              <ul id="list"></ul>
            </div>

            <div class="form-group col-4">
              <label for="exampleFormControlSelect2">Escolha qual será a ferramenta de análise</label>
              <select  class="form-control" id="exampleFormControlSelect2">
                <option value="lighthouse" selected>Lighthouse</option>
              </select>
            </div>

            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="save_reports"/>
              <label class="form-check-label" for="save_reports">Salvar os relatórios para consultar depois?</label>
            </div>


            <button type="submit" class="btn btn-primary">Auditar</button>

    </form>
<div>
</div>
@endsection


<script>
const items = []
const urlBase =  'http://localhost:8000/api/lighthouse'



// new Tokenfield({
//   el: document.querySelector('.token-input')
// })

const addItem = () => {
  const item = document.getElementById('token-input').value
  const ul = document.getElementById('list')
  if(!item)return

  items.push(item)
  const li = document.createElement("li");
  li.appendChild(document.createTextNode(item));
  ul.appendChild(li);
}


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

document.addEventListener('submit', async function (event) {
  event.preventDefault();

  const inputs = event.target.elements
   teste(inputs)


});


async function teste(request)
{

  const site = request['sites'].value

  const response = await fetch(urlBase, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    method: 'POST',
    body: JSON.stringify({site: site})
  })
  const content = await response.json();

  if(content.message){
    console.log('erro')
  }
  console.log(content)
}

</script>

