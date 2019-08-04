<? require_once ROOT . '/view/header.php' ?>
  <div class="container">
    <div class="row justify-content-center">
		  <div class="col-md-8 canvas">
			  <div class="col-md-12">
          <? if($errors): ?>
          <? foreach ($errors as $e): ?>
          <div class="alert alert-danger"><? echo $e ?></div>
          <? endforeach ?>
          <? endif ?>
          <form method="post" action="<? route('store') ?>">
            <div class="form-group">
              <label for="title">Header:</label>
              <input type="text" name="title" class="form-control" id="title" required>
            </div>
            <div class="form-group">
              <label for="pwd">Text:</label>
              <textarea type="text" name="text" class="form-control" id="text" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form> 
			  </div>
	    </div>
	  </div>
  </div>
<? require_once ROOT . '/view/footer.php' ?>
















