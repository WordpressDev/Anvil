<?php

class CommentsPlugin extends Plugin {

	/**
	 * Get the comments for a certain area.
	 *
	 * @param  string  $area
	 * @return array
	 */
	public function get($area = null, $order = 'DESC')
	{
		$comments = Comment::with('author');

		if( ! is_null($area))
		{
			$comments->where('area', $area);
		}

		return $comments->orderBy('id', $order)->get();
	}

	/**
	 * A quick helper to show a comment area.
	 *
	 * @param  string  $area
	 * @return string
	 */
	public function show($area)
	{
		return View::make('comments::comments', compact('area'))->render();
	}
}