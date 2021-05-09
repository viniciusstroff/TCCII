@extends('./layout/base')

@section('main')

<div class="row" >
    <div class="col-sm-12" id="app" >
        <div class="col-sm-12">

            @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            @endif
        </div>

        <h1 class="display-3">Audições</h1>
        <form  id='testeForm'>
            @csrf

            <div class="form-row">
              <div class="col">
                  <label for="sites">Sites:</label>
                  <input type="text" class="form-control token-input" id="token-input"name="sites" />

              </div>
              <div class="row">
                <button type="button" class="btn btn-primary " id="add-site">ADD</button>
              </div>
            </div>

            <div class="list-sites">
              <ul id="list" class="list-group"></ul>
            </div>

            <div class="form-group col-4">
              <label for="exampleFormControlSelect2">Escolha qual será a ferramenta de análise</label>
              <select  class="form-control" id="exampleFormControlSelect2" name="tool_name">
                <option value="lighthouse" selected>Lighthouse</option>
              </select>
            </div>

            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="save_reports"/>
              <label class="form-check-label" for="save_reports">Salvar os relatórios para consultar depois?</label>
            </div>


            <button type="button" id="audit" class="btn btn-primary">Auditar</button>
        </form>

    <div>
    <report-list-component></report-list-component>
</div>

@endsection

