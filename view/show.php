<? require_once ROOT . '/view/header.php' ?>
      <div class="container">
		    <div class="row justify-content-center">
				<div class="col-md-8 canvas">
					<div class="col-md-12">
		            <h2><? echo $article->title ?></h2>
		            <p><? echo $article->text ?></p>
		            <p><a href="<? route('edit', $article->id) ?>">Edit</a> |
		            	<a href="<? route('destroy') ?>"
		            		onclick="event.preventDefault();document.getElementById('destroy-form').submit();">Delete
		            	</a>
                    </p>
		            <form style="display:none" method="post"
		            		action="<? route('destroy') ?>" id="destroy-form">
		            	<input type="text" name="id" value="<? echo $article->id ?>">
		            </form>
		            <hr/>
			        <div id="comments-app" class="comments form-group">
					    <label for="cmt">Comments</label>
					    <textarea id="cmt" class="form-control" data-id="<? echo $article->id ?>"></textarea>
						<div id="showCmt"></div>
					</div>
		          </div>
				</div>
		    </div>
		</div>
<? require_once ROOT . '/view/footer.php' ?>










