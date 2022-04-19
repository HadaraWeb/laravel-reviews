<?php

namespace Reviews\Tests;

use Reviews\Tests\Database\Factories\ProductFactory;
use Reviews\Tests\Database\Factories\ReviewFactory;
use Reviews\Tests\Models\Product;

class ReviewableTest extends TestCase
{
	/** @test */
	public function it_can_be_sorted_by_the_highest_rated()
	{
		$products = ProductFactory::times(4)->create();

		$products[0]->reviews()->saveMany(
			ReviewFactory::times(1)->make([
				'rating' => 5
			])
		);

		$products[1]->reviews()->saveMany(
			ReviewFactory::times(4)->make([
				'rating' => 5
			])
		);

		$products[2]->reviews()->saveMany(
			ReviewFactory::times(4)->make([
				'rating' => 1
			])
		);

		$products[3]->reviews()->saveMany(
			ReviewFactory::times(1)->make([
				'rating' => 1
			])
		);				

		$highestRated = Product::query()->highestRated()->get();

		$this->assertEquals($products[1]->id, $highestRated[0]->id);
		$this->assertEquals($products[2]->id, $highestRated->last()->id);
	}
}
