<?php

namespace Reviews;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

trait HandlesReviews
{
	public function store(Request $request)
	{
		$this->validator($request->all())->validate();

		if (! in_array(
				$request['reviewable_type'], config('reviews.reviewables')
			)) {
			abort(422, "The reviewable_type does not exist.");
		}

		if (! $reviewable = $request['reviewable_type']::find(
			$request['reviewable_id']
		)) {
			abort(422, "The reviewable_id does not exist.");
		}

		$user = auth()->user();

		// Reject multiple reviews from a signle user
		if ($user->hasAlreadyReviewed($reviewable))
		{
			return abort(403);
		}

		$review = $user->reviews()
			->create($request->all());

		return $review;
	}

	public function update($id, Request $request)
	{
		$this->validator($request->all())->validate();

		$user = auth()->user();

		$review = $this->findReviewByUserOrFail($id, $user);

		$review->update(
			\Arr::except($request->all(), ['reviewable_type', 'reviewable_id'])
		);

		return $review;
	}

	public function delete($id, Request $request)
	{
		$user = auth()->user();

		$review = $this->findReviewByUserOrFail($id, $user);

		$review->delete();
	}

	protected function findReviewByUserOrFail($id, $user)
	{
		return $user->reviews()->anyApprovalStatus()->findOrFail($id);
	}
}
