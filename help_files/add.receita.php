<section>
<div class="page-header" id="section-receitas">
  <h2>Inserir Receita. <small>adicione a sua receita.</small></h2>
</div>

<!-- FORM -->
<form class="form-horizontal">

  <fieldset>
      <legend><small>Text</small></legend>
      <div class="form-group">
          <div class="col-sm-1"><label for="recipe-category" class="control-label">Categoria</label></div>
          <div class="col-sm-5"><select class="form-control" id="recipe-category"><option value="">categoria</option></select></div>
      </div>
      <div class="form-group has-feedback has-feedback-left">
          <div class="col-sm-1"><label for="recipe-name" class="control-label">Nome</label></div>
          <div class="col-sm-11"><input type="text" class="form-control" id="recipe-name"></div>
          <i class="form-control-feedback glyphicon glyphicon-asterisk"></i>
      </div>
  </fieldset>

  <fieldset>
      <legend><small>Imagem da receita.</small></legend>
      <output id="img-output"></output>
      <input type="file" id="img-input"/>
      <br>
      <script type="text/javascript">
          if (window.FileReader) {
              document.getElementById('img-input').addEventListener('change', handleFileSelect, false);
          } else {
              alert('This browser does not support FileReader');
          }

          function handleFileSelect(evt) {
              var files = evt.target.files;
              var f = files[0];
              var reader = new FileReader();

              reader.onload = (function(theFile) {
              return function(e) {
                  document.getElementById('img-output').innerHTML = ['<img src="', e.target.result,'" title="', theFile.name, '" width="200" />'].join('');
              };
          })(f);

          reader.readAsDataURL(f);
          }
      </script>
  </fieldset>

  <fieldset>
      <legend><small>Text</small></legend>
      <div class="form-group">
          <div class="col-sm-1"><label for="recipe-recipe" class="control-label">Receita</label></div>
          <div class="col-sm-11"><textarea id="recipe-recipe" cols="30" rows="10" class="form-control" style="resize:none;"></textarea></div>
      </div>

      <!-- INGREDIENTES -->
      <div class="form-group">
          <div class="col-sm-1"><label for="recipe-ingredients-list" class="control-label">Ingredientes</label></div>
          <div class="col-sm-3"><textarea id="recipe-ingredients-list" cols="15" rows="15" class="form-control" style="resize:none;" placeholder="Lista de ingredientes"></textarea></div>

          <div class="col-sm-5">
              <label for="ingredient-category" class="control-label"><small>categoria</small></label>
              <?php
                  $con = new dbconnection();
                  echo $con->createSelectBox("SELECT DISTINCT caption FROM cat_ingredientes WHERE caption !='' ","onchange='getIngredients()'","ingredient-category","ingredient-category[]","caption","caption","","enabled");
              ?>
              <script type="text/javascript">
                  var result = "";
                  function getIngredients(){
                      var e = document.getElementById("ingredient-category");
                      var userselection = e.options[e.selectedIndex].text;
                      $.post("../include/ajax_req.php", {caption: userselection}, function(data){
                      document.getElementById("recipe-ingredient-holder").innerHTML = data;
                      });
                  }


              </script>

              <label for="recipe-ingredient" class="control-label"><small>ingrediente</small></label>
              <div id="recipe-ingredient-holder"></div>

              <label for="ingredient-amount" class="control-label"><small>quantidade</small></label>
              <input type="text" class="form-control" id="ingredient-amount">

              <hr>
              <input class="btn btn-primary" id="btn_ingredient" type="button" onclick="populateList()" value=" add + ">
              <script type="text/javascript">
                  var x = 0;
                  function populateList(){
                      window.x++;
                      document.getElementById("recipe-ingredients-list").value = document.getElementById("recipe-ingredients-list").value +
                      window.x + " | " + document.getElementById("recipe-ingredient").value + " | " +
                      document.getElementById("ingredient-amount").value + '\n'
                  }
              </script>
          </div>
      </div>
  </fieldset>

  <fieldset>
      <legend><small>Text</small></legend>
      <div class="form-group">
          <div class="col-sm-1"><label for="cb-recipe-private" class="control-label">Privado</label></div>
          <div class="col-sm-5"><input id="cb-recipe-private" class="form-control" value="true" type="checkbox"></div>
      </div>
  </fieldset>
  <fieldset>
      <legend><small>Text</small></legend>
      <div class="form-group">
          <div class="text-right">
              <div class="btn-group">
                  <button type="submit" class="btn btn-success" name="btn_save_recipe">Save</button>
                  <button type="submit" class="btn btn-default" name="btn_cancel_recipe">Cancel</button>
              </div>
          </div>
      </div>
  </fieldset>
</form>
</section>