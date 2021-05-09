<form  id='testeForm' method="post" action="{{ route('audits.store') }}">
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
      <select  class="form-control" id="exampleFormControlSelect2" name="tool_name">
        <option value="lighthouse" selected>Lighthouse</option>
      </select>
    </div>

    <div class="form-check">
      <input type="checkbox" class="form-check-input" name="save_reports"/>
      <label class="form-check-label" for="save_reports">Salvar os relatórios para consultar depois?</label>
    </div>


    <button type="submit" class="btn btn-primary">Auditar</button>

</form>
