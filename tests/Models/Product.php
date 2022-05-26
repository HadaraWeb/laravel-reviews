<?php

namespace Reviews\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Reviews\Reviewable;
use Reviews\Tests\Database\Factories\ProductFactory;

class Product extends Model
{
	use HasFactory, Reviewable;

	protected static function newFactory()
	{
		return new ProductFactory;
	}
}