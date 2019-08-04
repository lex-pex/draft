<? require_once ROOT . '/view/header.php' ?>
      <div class="container">
		    <div class="row justify-content-center">
  				<div class="col-md-8 canvas">
              <? foreach ($articles as $article): ?>
                <div class="col-md-12">
                  <h2><? echo $article->title ?></h2>
                  <p><? echo substr($article->text, 0, 100) ?><a href="<? route('show', $article->id) ?>"> &raquo; Read more...</a></p>
                </div>
              <? endforeach ?>
              <div class="col-md-12">
                <a href="<? echo '/blog/' . $prevPage ?>">< Prev Page</a> |
                <a href="<? echo '/blog/' . $nextPage ?>">Next Page ></a>
              </div>
  				</div>
		    </div>
		</div>
<? require_once ROOT . '/view/footer.php' ?>























