<?php

namespace Reviews;

use Illuminate\Support\Str;

trait Reviewable
{
	public static function bootReviewable()
	{
		static::deleted(function ($model) {
			if (! $model->exists) {
				$model->reviews()->forceDelete();
			}
		});
	}

	public function getRouteType()
	{
		$name = (new \ReflectionClass(get_called_class()))
			->getShortName();

		return Str::snake($name, '-');
	}

	public function reviews()
	{
		return $this->morphMany(config('reviews.model'), 'reviewable');
	}

	public function scopeHighestRated($query)
	{
		$reviewClass = config('reviews.model');

		$totalAverage = $reviewClass::query()
			->where('reviewable_type', get_called_class())
			->whereHas('reviewable')
			->avg('rating');

		$averageCount = $reviewClass::query()
			->where('reviewable_type', get_called_class())
			->whereHas('reviewable')
			->count() / $this->count();

		$query->withAvg('reviews as itemAverage', 'rating')
			->withCount('reviews as itemCount')
			->orderByRaw('(IFNULL(itemAverage, 0) * itemCount + ? * ?) / (itemCount + ?) DESC', [
				$totalAverage, $averageCount, $averageCount
			]);
	}

	public function scopeWithRatings($query)
	{
		$query->withAvg('reviews as ratings_avg', 'rating')
			->withCount('reviews as ratings_count');
	}

	public function loadRatings()
	{
		$this->ratings_avg = $this->reviews()->avg('rating');

		$this->ratings_count = $this->reviews()->count();
	}
}
