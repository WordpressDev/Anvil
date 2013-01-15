<?php

use Blog\Post;

class BlogAdminController extends Controller {

	/**
	 * Display the admin home page.
	 *
	 * @return void
	 */
	public function getIndex()
	{
		$this->page->addBreadcrumb('Blog');
		$this->page->setContent('blog::admin.home');
	}

	/**
	 * Display the comments.
	 *
	 * @return void
	 */
	public function getComments()
	{
		$this->page->addBreadcrumb('Blog', 'admin/blog');
		$this->page->addBreadcrumb('Comments');

		$this->page->setContent('blog::admin.comments');
	}

	/**
	 * Show the form to create a new blog post.
	 *
	 * @return void
	 */
	public function getCreatePost()
	{
		$editing = false;
		$post = new Post;

		$this->page->addBreadcrumb('Blog', 'admin/blog');
		$this->page->addBreadcrumb('Create Post');

		$this->page->setContent('blog::admin.post', compact('editing', 'post'));
	}

	/**
	 * Create a new blog post.
	 *
	 * @return void
	 */ 
	public function postCreatePost()
	{
		$form = Validator::make(Input::all(), array(
			'title'   => 'required',
			'content' => 'required', 
		));

		if($form->passes())
		{
			$post = new Post;

			$post->author_id = Auth::user()->id;
			$post->title = Input::get('title');
			$post->content = Input::get('content');

			$post->save();

			return Redirect::to('admin/blog/post/'.$post->id);
		}

		else
		{
			$errors = $form->messages();
		}

		return Redirect::back()->withErrors($errors);
	}

	/**
	 * Show the form to edit a post.
	 *
	 * @param  int   $id
	 * @return void
	 */
	public function getPost($id)
	{
		$editing = true;
		$post = Post::find($id);

		if(is_null($post))
		{
			return Redirect::back();
		}

		$this->page->addBreadcrumb('Blog', 'admin/blog');
		$this->page->addBreadcrumb('Post');

		$this->page->setContent('blog::admin.post', compact('editing', 'post'));
	}

	/**
	 * Edit a post.
	 *
	 * @param  int   $id
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function postPost($id)
	{
		$form = Validator::make(Input::all(), array(
			'title'   => 'required',
			'content' => 'required',
		));

		if($form->passes())
		{
			$post = Post::find($id);

			if( ! is_null($post))
			{
				$post->title   = Input::get('title');
				$post->content = Input::get('content');

				$post->save();

				return Redirect::to('admin/blog/post/'.$id);
			}

			else
			{
				$errors = new MessageBag;

				$errors->add('post', 'Post does not exist.');
			}
		}

		else
		{
			$errors = $form->messages();
		}

		return Redirect::back()->withErrors($errors);
	}

	/**
	 * Delete a post.
	 *
	 * @param  int  $id
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function getDeletePost($id)
	{
		$post = Post::find($id);

		if( ! is_null($post))
		{
			$post->delete();

			return Redirect::to('admin/blog');
		}

		else
		{
			$errors = new MessageBag;

			$errors->add('post', 'Post does not exist.');
		}

		return Redirect::back()->withErrors($errors);
	}
}